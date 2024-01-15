<?php

namespace App\Http\Controllers\Admin;



use DB;
use Str;
use DateTime;
use Carbon\Carbon;
use App\Mail\Email;
use App\Models\User;
use App\Models\UserContact;
use App\Models\Profile;
use App\Models\Resignation;
use App\Models\Vehicle;
use App\Models\Position;
use App\Models\UserLeave;
use App\Models\WorkShift;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\JobHistory;
use App\Models\Designation;
use App\Models\Discrepancy;
use Illuminate\Http\Request;
use App\Models\SalaryHistory;
use App\Models\AuthorizeEmail;
use App\Models\DepartmentUser;
use App\Models\EmployeeLetter;
use App\Models\EmploymentStatus;
use App\Models\WorkingShiftUser;
use Illuminate\Validation\Rules;
use App\Rules\MobileNumberFormat;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\UserEmploymentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SalaryIncreamentNotification;
use App\Notifications\ImportantNotificationWithMail;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;
use App\Helpers\LogActivity;
use App\Models\EmployeeConversionRate;
use App\Models\HiringHistory;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('employees-list');
        $data['title'] = 'All Employees';
        $data['trashed'] = false;

        $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        $data['roles'] = Role::orderby('id', 'desc')->get();
        $data['departments'] = Department::orderby('id', 'desc')->where('status', 1)->get();
        $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
        $emp_statuses = ['Terminated', 'Voluntary', 'Layoffs', 'Retirements'];
        $data['termination_employment_statues'] = EmploymentStatus::whereIn('name', $emp_statuses)->get();

        $data['work_shifts'] = WorkShift::where('status', 1)->get();

        $records = User::latest()->where('is_employee', 1)->select("*");
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                        return '<span class="text-primary">' . $model->departmentBridge->department->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';

                    if (isset($model->employeeStatusEndDateNull->employmentStatus) && !empty($model->employeeStatusEndDateNull->employmentStatus->name)) {
                        if ($model->employeeStatusEndDateNull->employmentStatus->name == 'Terminated') {
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        } elseif ($model->employeeStatusEndDateNull->employmentStatus->name == 'Permanent') {
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        } elseif ($model->employeeStatusEndDateNull->employmentStatus->name == 'Probation') {
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        } else {
                            $label = '<span class="badge bg-label-info me-1">' . $model->employeeStatusEndDateNull->employmentStatus->name . '</span>';
                        }
                    }

                    return $label;
                })
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.employee-action', ['employee' => $model])->render();
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance = $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->where('first_name', 'LIKE', "%$search%")
                                ->orWhere('last_name', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%");
                        });
                    }
                    if ($request->emp_status != "all" && !empty($request->emp_status)) {
                        $instance = $instance->whereHas("employeeStatusEndDateNull", function ($w) use ($request) {
                            $w->where('employment_status_id', $request->emp_status);
                        });
                    }

                    if (isset($request->department) && !empty($request->department) && $request->department != "all") {
                        $department = getDepartmentFromID($request->department);
                        $myDpartUsers = getDepartmentUsers($department);
                        if (!empty($myDpartUsers)) {
                            $instance = $instance->whereIn('id', $myDpartUsers->pluck("user_id")->toArray());
                        }
                    }

                    if (isset($request->shift) && !empty($request->shift) && $request->shift != "all") {
                        $getShift = getShiftFromId($request->shift);
                        if (!empty($getShift)) {
                            $shiftUsers = getShiftUsers($getShift);
                            if (!empty($shiftUsers)) {
                                $instance = $instance->where(function ($w) use ($shiftUsers) {
                                    $w->whereIn('id', $shiftUsers);
                                });
                            } else {
                            }
                        }
                    }
                })
                ->rawColumns(['emp_status', 'status', 'first_name', 'role', 'Department', 'action'])
                ->make(true);
        }

        return view('admin.employees.index', $data);
    }

    public function employeesForIt(Request $request)
    {
        $this->authorize('employees_for_it-list');
        $data['title'] = 'All Employees';
        $data['trashed'] = false;

        $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        $data['roles'] = Role::orderby('id', 'desc')->get();
        $data['departments'] = Department::orderby('id', 'desc')->where('status', 1)->get();
        $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
        $emp_statuses = ['Terminated', 'Voluntary', 'Layoffs', 'Retirements'];
        $data['termination_employment_statues'] = EmploymentStatus::whereIn('name', $emp_statuses)->get();

        $data['work_shifts'] = WorkShift::where('status', 1)->get();

        $records = User::latest()->where('is_employee', 1)->select("*");
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                        return '<span class="text-primary">' . $model->departmentBridge->department->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';

                    if (isset($model->employeeStatusEndDateNull->employmentStatus) && !empty($model->employeeStatusEndDateNull->employmentStatus->name)) {
                        if ($model->employeeStatusEndDateNull->employmentStatus->name == 'Terminated') {
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        } elseif ($model->employeeStatusEndDateNull->employmentStatus->name == 'Permanent') {
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        } elseif ($model->employeeStatusEndDateNull->employmentStatus->name == 'Probation') {
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        } else {
                            $label = '<span class="badge bg-label-info me-1">' . $model->employeeStatusEndDateNull->employmentStatus->name . '</span>';
                        }
                    }

                    return $label;
                })
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.employee_for_it_action', ['employee' => $model])->render();
                })
                ->rawColumns(['emp_status', 'status', 'first_name', 'role', 'Department', 'action'])
                ->make(true);
            }
        return view('admin.employees.employees_for_it_index', $data);
    }

    public function newJoinings(Request $request)
    {
        $this->authorize('new_employee_joinings-list');
        $data['title'] = 'All New Joinings';
        $data['trashed'] = false;

        $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        $data['roles'] = Role::orderby('id', 'desc')->get();

        $data['departments'] = Department::orderby('id', 'desc')->where('status', 1)->get();
        $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
        $emp_statuses = ['Terminated', 'Voluntary', 'Layoffs', 'Retirements'];
        $data['termination_employment_statues'] = EmploymentStatus::whereIn('name', $emp_statuses)->get();

        $data['work_shifts'] = WorkShift::where('status', 1)->get();

        $current_date = Carbon::now();
        $start_date = $current_date->copy()->subMonth()->toDateString();
        $end_date = $current_date->copy()->toDateString();

        //Getting new joining's first month records.
        $records = User::where('is_employee', 1)
            ->whereHas('profile', function ($query)  use ($start_date, $end_date) {
                $query->whereBetween('joining_date', [$start_date, $end_date]);
            })
            ->latest()
            ->select("*");

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                        return '<span class="text-primary">' . $model->departmentBridge->department->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';

                    if (isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name)) {
                        if ($model->employeeStatus->employmentStatus->name == 'Terminated') {
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Permanent') {
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Probation') {
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        } else {
                            $label = '<span class="badge bg-label-info me-1">' . $model->employeeStatus->employmentStatus->name . '</span>';
                        }
                    }

                    return $label;
                })
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.employee-action', ['employee' => $model])->render();
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance = $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->where('first_name', 'LIKE', "%$search%")
                                ->orWhere('last_name', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%");
                        });
                    }
                    if ($request->emp_status != "all" && !empty($request->emp_status)) {
                        $instance = $instance->whereHas("employeeStatus", function ($w) use ($request) {
                            $w->where('employment_status_id', $request->emp_status);
                        });
                    }

                    if (isset($request->department) && !empty($request->department) && $request->department != "all") {
                        $department = getDepartmentFromID($request->department);
                        $myDpartUsers = getDepartmentUsers($department);
                        if (!empty($myDpartUsers)) {
                            $instance = $instance->whereIn('id', $myDpartUsers->pluck("user_id")->toArray());
                        }
                    }

                    if (isset($request->shift) && !empty($request->shift) && $request->shift != "all") {
                        $getShift = getShiftFromId($request->shift);
                        if (!empty($getShift)) {
                            $shiftUsers = getShiftUsers($getShift);
                            if (!empty($shiftUsers)) {
                                $instance = $instance->where(function ($w) use ($shiftUsers) {
                                    $w->whereIn('id', $shiftUsers);
                                });
                            } else {
                            }
                        }
                    }
                })
                ->rawColumns(['emp_status', 'status', 'first_name', 'role', 'Department', 'action'])
                ->make(true);
        }

        return view('admin.employees.index', $data);
    }
    public function employeePermanent($employee_id)
    {
        DB::beginTransaction();
        $login_user = Auth::user();

        try {

            $updatedDataArrayHirtory = [];
            $user_emp_status = UserEmploymentStatus::orderby('id', 'desc')->where('end_date', null)->where('user_id', $employee_id)->first();
            if (!empty($user_emp_status)) {
                $user_emp_status->end_date = date('Y-m-d');
                $user_emp_status->save();
                $user_emp_status->refresh();
                $oldUserEmploymentStatusData = $user_emp_status->getOriginal();
                $oldUserEmploymentStatusRelationArray = [
                    'employment_status' => $user_emp_status->employmentStatus->name ?? null,
                ];

                $user_emp_status_create = UserEmploymentStatus::create([
                    'user_id' => $employee_id,
                    'employment_status_id' => 2, //permanent
                    'start_date' => date('Y-m-d'),
                ]);

                $newUserEmploymentStatusRelationArray = [
                    'employment_status' => $user_emp_status_create->employmentStatus->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData($oldUserEmploymentStatusData, $user_emp_status_create, 'user_employment_status', 'both', $oldUserEmploymentStatusRelationArray, $newUserEmploymentStatusRelationArray);
            } else {
                $user_emp_status_create = UserEmploymentStatus::create([
                    'user_id' => $employee_id,
                    'employment_status_id' => 2, //permanent
                    'start_date' => date('Y-m-d'),
                ]);
                $user_emp_status_create->refresh();

                $newUserEmploymentStatusRelationArray = [
                    'employment_status' => $user_emp_status_create->employmentStatus->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData(null, $user_emp_status_create, 'user_employment_status', 'create', null, $newUserEmploymentStatusRelationArray);
            }

            $job_history = JobHistory::orderby('id', 'desc')->where('user_id', $employee_id)->where('end_date', null)->first();

            $new_job_job_history = $job_history;
            if (!empty($job_history)) {
                $job_history->end_date = date('Y-m-d');
                $job_history->save();
                $oldJobHistoryData = $job_history->getOriginal();
                $oldJobRelationArray = [
                    'designation' => $job_history->designation->title ?? null,
                    'employment_status' => isset($oldUserEmploymentStatusRelationArray['employment_status']) && !empty($oldUserEmploymentStatusRelationArray['employment_status']) ? $oldUserEmploymentStatusRelationArray['employment_status'] : null,
                ];


                $new_job_job_history = JobHistory::create([
                    'designation_id' => $job_history->designation_id,
                    'user_id' => $employee_id,
                    'employment_status_id' => 2, //Permanent Employee status
                    'joining_date' => date('Y-m-d'),
                    'vehicle_name' => $job_history->vehicle_name,
                    'vehicle_cc' => $job_history->vehicle_cc,
                ]);
                $newJobRelationArray = [
                    'designation' => $new_job_job_history->designation->title ?? null,
                    'employment_status' => $new_job_job_history->userEmploymentStatus->employmentStatus->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData($oldJobHistoryData, $new_job_job_history, 'job_history', 'both', $oldJobRelationArray, $newJobRelationArray);
            }

            $last_job_history_id = isset($job_history->id) && !empty($job_history->id) ? $job_history->id : null;
            $last_salary = SalaryHistory::where('job_history_id', $last_job_history_id)->where('end_date', null)->where('status', 1)->first();
            if (!empty($last_salary)) {
                $oldSalaryHistoryData = $last_salary->getOriginal();
                $last_salary->status = 0;
                $last_salary->end_date =  date('Y-m-d');
                $last_salary->save();
                $last_salary->refresh();

                $salary_history = SalaryHistory::create([
                    'created_by' => Auth::user()->id,
                    'user_id' => $last_salary->user_id ?? null,
                    'job_history_id' => $new_job_job_history->id ?? null,
                    'raise_salary' => $last_salary->raise_salary ?? null,
                    'salary' => $last_salary->salary ?? null,
                    'effective_date' => date('Y-m-d'),
                    'currency_code' => $last_salary->currency_code ?? null,
                    'currency_rate' => $last_salary->currency_rate ?? null,

                ]);

                $updatedDataArrayHirtory[] = getUpdatedData($oldSalaryHistoryData, $salary_history, 'salary_history', 'update');
            } else {
                $salary_history = SalaryHistory::create([
                    'created_by' => Auth::user()->id,
                    'user_id' => $employee_id ?? null,
                    'job_history_id' => $new_job_job_history->id ?? null,
                    'raise_salary' => 0,
                    'salary' => 0,
                    'effective_date' => date('Y-m-d'),
                ]);
                $updatedDataArrayHirtory[] = getUpdatedData(null, $salary_history, 'salary_history', 'create');
            }

            $newEmployeeLetterCreate = EmployeeLetter::create([
                'created_by' => Auth::user()->id,
                'employee_id' => $employee_id,
                'title' => 'promotion_letter',
                'effective_date' => date('Y-m-d'),
                'validity_date' => NULL,
            ]);
            $updatedDataArrayHirtory[] = getUpdatedData(null, $newEmployeeLetterCreate, 'employee_letter', 'create');

            if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                saveLogs($updatedDataArrayHirtory, 'User', $employee_id, 4, 'Employee', 6);
            }


            DB::commit();

            \LogActivity::addToLog('Employee has been permanent');
            $model = User::where('id', $employee_id)->first();

            // send email on salary increments.
            try {
                // $body = "Dear ".$model->first_name." ". $model->last_name.", <br /><br />".
                //         "I hope this email finds you well. I am writing to inform you about an important update regarding your employment. We are pleased to announce that your hard work, dedication, and valuable contributions to the company have been recognized. <br /><br />".
                //         "After careful consideration, we have decided to permanent. You have been permanent employees in this company regards outstanding performance, commitment, and the value you bring to our organization. <br /><br />".

                // $footer = "Best regards,, <br /><br />".
                //             "HR Department";

                $mailData = [
                    'from' => 'salary_increments',
                    'title' => 'Permanent',
                    'name' => $model->first_name . " " . $model->last_name,
                ];

                $increment_message = [
                    'id' => $new_job_job_history->id,
                    'profile' => $login_user->profile->profile,
                    'name' => $login_user->first_name . ' ' . $login_user->last_name,
                    'title' => 'Congratulation! You have been permanent.',
                    'message' => 'This promotion reflects your outstanding performance, commitment, and the value you bring to our organization.',
                ];

                $model->notify(new SalaryIncreamentNotification($increment_message));

                if (!empty(sendEmailTo($model, 'promotion')) && !empty(sendEmailTo($model, 'promotion')['cc_emails'])) {
                    $to_emails = sendEmailTo($model, 'promotion')['to_emails'];
                    $cc_emails = sendEmailTo($model, 'promotion')['cc_emails'];
                    Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
                } elseif (!empty(sendEmailTo($model, 'promotion')['to_emails'])) {
                    $to_emails = sendEmailTo($model, 'promotion')['to_emails'];
                    Mail::to($to_emails)->send(new Email($mailData));
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
            //send email.

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $updatedDataArrayHirtory = [];
        $domain = config("project.domain");
        $request->validate(
            [
                'first_name' => ['required', 'string', 'max:255'],
                'gender' => ['required'],
                'email' => ['required', 'ends_with:' . $domain, 'string', 'email', 'max:255', 'unique:' . User::class],
                'employment_status_id' => 'required',
                'designation_id' => 'required',
                'department_id' => 'required',
                'role_ids' => 'required',
                'role_ids*' => 'required',
                'joining_date' => 'required',
                'work_shift_id' => 'required',
                'employment_id' => 'max:200',
                'salary' => 'max:255',
            ]
        );

        DB::beginTransaction();
        $message = "";
        try {

            // Email account details
            $user_email = $request->email;
            // cPanel API credentials
            $cpanelUsername = config("project.cpanelUsername");
            $cpanelToken = config("project.cpanelToken");
            $cpanelDomain = config("project.cpanelDomain");
            $user_password =  config("project.defaultPassword");

            if (getAppMode() == 'live') {
                $checkWebMail = checkWebMail($request->email);
                if (isset($checkWebMail) && $checkWebMail['success'] == false) {
                    $create_email_response = $this->createEmailAccount($cpanelUsername, $cpanelToken, $cpanelDomain, $user_email, $user_password);
                    if (isset($create_email_response) && !empty($create_email_response) && $create_email_response == 'failed') {
                        $message .= 'Email: ' .  $user_email  . ' already exist on Web Mail!';
                    }
                } else {
                    $message .= 'Email: ' .  $user_email  . ' already exist on Web Mail!';
                }
            }
            $emp_name = $request->first_name . ' ' . $request->last_name . ' ' . Str::random(5);

            $model = [
                'created_by' => Auth::user()->id,
                'status' => 1,
                'slug' => Str::slug($emp_name),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($user_password),
            ];

            $model = User::create($model);
            $model->assignRole($request->role_ids);
            $rolesIds = $model->roles->pluck('id')->toArray() ?? null;
            $rolesNames = $model->roles->pluck('name')->toArray() ?? null;
            $saveRoleHistory = [
                'id' => json_encode($rolesIds) ?? null,
                'name' => json_encode($rolesNames) ?? null,
            ];

            $updateRoleArrayHistory = [];
            $updateRoleArrayHistory['old']['role'] = null;
            $updateRoleArrayHistory['updated']['role'] = $saveRoleHistory;
            $updatedDataArrayHirtory[] = $updateRoleArrayHistory;

            if ($model) {
                $updatedDataArrayHirtory[] = getUpdatedData(null, $model, 'user', 'create');


                $profileCreate = Profile::create([
                    'user_id' => $model->id,
                    'employment_id' => $request->employment_id,
                    'joining_date' => $request->joining_date,
                    'gender' => $request->gender,
                    'phone_number' => $request->phone_number,
                ]);
                if (isset($profileCreate->id)) {
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $profileCreate, 'profile', 'create');
                }

                $job_history = JobHistory::create([
                    'created_by' => Auth::user()->id,
                    'user_id' => $model->id,
                    'designation_id' => $request->designation_id,
                    'employment_status_id' => $request->employment_status_id,
                    'joining_date' => $request->joining_date,
                ]);
                if (isset($job_history)) {
                    $newJobRelationArray = [
                        'designation' => $job_history->designation->title ?? null,
                        'employment_status' => $job_history->userEmploymentStatus->employmentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $job_history, 'job_history', 'create', null, $newJobRelationArray);
                }
                if ($job_history && !empty($request->salary)) {
                    $history =  SalaryHistory::create([
                        'created_by' => Auth::user()->id,
                        'user_id' => $model->id,
                        'job_history_id' => $job_history->id,
                        'salary' => $request->salary,
                        'effective_date' => $request->joining_date,
                        'status' => 1,
                        'currency_code' => $request->currency ?? null,
                        'currency_rate' => $request->conversion_rate ?? 0,
                        'conversion_amount' => $request->conversion_amount_hidden ?? 0,
                    ]);
                    if (isset($history)) {
                        $updatedDataArrayHirtory[] = getUpdatedData(null, $history, 'salary_history', 'create');
                        if (isset($request->currency) && !empty($request->currency) && $request->currency !== "PKR") {
                            $createEmployeeConversionRate = EmployeeConversionRate::create([
                                "user_id" => $model->id ?? null,
                                "salary_history_id" => $history->id ?? null,
                                "month" => Carbon::now()->format("m"),
                                "year" => Carbon::now()->format("Y"),
                                "salary" => $history->salary ?? 0,
                                'currency_code' => $request->currency ?? null,
                                'currency_rate' => $request->conversion_rate ?? 0,
                                'conversion_amount' => $request->conversion_amount_hidden ?? 0,
                                "status" => 1,
                            ]);
                        }
                        if (isset($createEmployeeConversionRate)) {
                            $updatedDataArrayHirtory[] = getUpdatedData(null, $createEmployeeConversionRate, 'employee_conversion_rate', 'create');
                        }
                    }
                }

                if (!empty($request->department_id)) {
                    $createDepartment = DepartmentUser::create([
                        'department_id' => $request->department_id,
                        'user_id' => $model->id,
                        'start_date' => $request->joining_date,
                    ]);
                    if (isset($createDepartment)) {
                        $newDepartmenRelationArray = [
                            'department' => $createDepartment->department->name ?? null,
                        ];
                        $updatedDataArrayHirtory[] = getUpdatedData(null, $createDepartment, 'user_department', 'create', null, $newDepartmenRelationArray);
                    }
                }

                $createUserEmploymentSatatus = UserEmploymentStatus::create([
                    'user_id' => $model->id,
                    'employment_status_id' => $request->employment_status_id,
                    'start_date' => $request->joining_date,
                ]);
                if (!empty($createUserEmploymentSatatus)) {
                    $newUserEmploymentStatusRelationArray = [
                        'employment_status' => $createUserEmploymentSatatus->employmentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $createUserEmploymentSatatus, 'user_employment_status', 'create', null, $newUserEmploymentStatusRelationArray);
                }

                $createWorkingShiftUser = WorkingShiftUser::create([
                    'user_id' => $model->id,
                    'working_shift_id' => $request->work_shift_id,
                    'start_date' => $request->joining_date,
                ]);
                if (isset($createWorkingShiftUser)) {
                    $newWorkingShiftUserRelationArray = [
                        'working_shift' => $createWorkingShiftUser->workShift->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $createWorkingShiftUser, 'working_shift_user', 'create', null, $newWorkingShiftUserRelationArray);
                }

                DB::commit();

                //send email with password.
                if (getAppMode() != "local") {
                    //Employee portal credentials mail
                    $employee_info = [
                        'name' => $model->first_name . ' ' . $model->last_name,
                        'email' => $model->email,
                        'password' => $user_password,
                    ];


                    $mailData = [
                        'from' => 'welcome',
                        'title' => 'Welcome to Our Team - Important Onboarding Information',
                        'employee_info' => $employee_info,
                    ];

                    Mail::to($user_email)->send(new Email($mailData));
                }

                //Joining Email to departments
                $manager_name = '';
                if (isset($model->departmentBridge->department->manager) && !empty($model->departmentBridge->department->manager->first_name)) {
                    $manager_name = $model->departmentBridge->department->manager->first_name;
                }

                $designation_name = '';
                if (isset($model->jobHistory->designation) && !empty($model->jobHistory->designation->title)) {
                    $designation_name = $model->jobHistory->designation->title;
                }
                $department_name = '';
                if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department->name)) {
                    $department_name = $model->departmentBridge->department->name;
                }
                $work_shift_name = '';
                if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                    $work_shift_name = $model->userWorkingShift->workShift->name;
                }
                $joining_date = '';
                if (isset($model->profile) && !empty($model->profile->joining_date)) {
                    $joining_date = date('d M Y', strtotime($model->profile->joining_date));
                }
                if (getAppMode() != "local") {
                    $employee_info = [
                        'name' => $model->first_name . ' ' . $model->last_name,
                        'email' => $model->email,
                        'password' => $user_password,
                        'manager' => $manager_name,
                        'designation' => $designation_name,
                        'department' => $department_name,
                        'shift_time' => $work_shift_name,
                        'joining_date' => $joining_date,
                    ];

                    $mailData = [
                        'from' => 'employee_info',
                        'title' => 'Employee Approval and Joining Information',
                        'employee_info' => $employee_info,
                    ];

                    if (!empty(sendEmailTo($model, 'new_employee_info')) && !empty(sendEmailTo($model, 'new_employee_info')['cc_emails'])) {
                        $to_emails = sendEmailTo($model, 'new_employee_info')['to_emails'];
                        $cc_emails = sendEmailTo($model, 'new_employee_info')['cc_emails'];
                        Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
                    } elseif (!empty(sendEmailTo($model, 'new_employee_info')) && !empty(sendEmailTo($model, 'new_employee_info')['to_emails'])) {
                        $to_emails = sendEmailTo($model, 'new_employee_info')['to_emails'];
                        Mail::to($to_emails)->send(new Email($mailData));
                    }
                }

                if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                    saveLogs($updatedDataArrayHirtory, "User", $model->id, 3, "Employee", 1);
                }

                $message .= " Employee has been created Successfully!";
                \LogActivity::addToLog('Employee added');

                return response()->json(['success' => true, "message" => $message]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // API function to create an email account
    function createEmailAccount($cpanelUsername, $cpanelToken, $cpanelDomain, $emailUsername, $emailPassword)
    {
        $buildRequest = json_encode([
            'cpanel_jsonapi_version' => 2,
            'cpanel_jsonapi_module' => 'Email',
            'cpanel_jsonapi_func' => 'add_pop',
            'email' => $emailUsername,
            'password' => $emailPassword,
            'quota' => 'unlimited'
        ]);

        $query = "https://{$cpanelDomain}:2083/execute/Email/add_pop";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $buildRequest);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: cpanel {$cpanelUsername}:{$cpanelToken}"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $responseData = json_decode($response, true);
            if ($responseData['errors'] === null) {
                return 'success';
            } else {
                return 'failed';
            }
        } else {
            return 'failed';
        }
    }

    public function edit($slug)
    {
        $this->authorize('employees-edit');
        $data = [];
        $data['model'] = User::with('jobHistory', 'employeeStatus')->where('slug', $slug)->first();
        $data['positions'] = Position::orderby('id', 'desc')->where('status', 1)->get();
        $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        $data['roles'] = Role::orderby('id', 'desc')->get();
        $data['departments'] = Department::orderby('id', 'desc')->get();
        $data['work_shifts'] = WorkShift::where('status', 1)->get();
        $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();

        $logined_user = Auth::user();
        if($logined_user->hasPermissionTo('employees_for_it-list')){
            return (string) view('admin.employees.employee_for_it_edit_content', compact('data'));
        }else{
            return (string) view('admin.employees.edit_content', compact('data'));
        }
    }

    public function update(Request $request, $slug)
    {
        $updatedDataArrayHirtory = [];
        $user = User::where('slug', $slug)->first();
        $domain = config("project.domain");
        $request->validate(
            [
                'first_name' => ['required', 'string', 'max:255'],
                'gender' => ['required'],
                'email' => 'required|max:255|ends_with:' . $domain, '|unique:users,id,' . $user->id,
                'employment_status_id' => 'required',
                'designation_id' => 'required',
                'department_id' => 'required',
                'role_ids' => 'required',
                'role_ids*' => 'required',
                'joining_date' => 'required',
                'work_shift_id' => 'required',
                'employment_id' => 'max:200',
                'salary' => 'max:255',
                // 'generate_email' => 'required'
            ],
            [
                // 'generate_email.required' =>  'Please select an option',
                'gender.required' => 'Please select a gender',
            ]
        );

        DB::beginTransaction();

        try {
            $oldUserData = $user->getOriginal();

            $user->created_by = Auth::user()->id;
            $user->status = 1;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;

            if ($user->email != $request->email) {
                $user->email = $request->email;
            }

            $user->save();
            $oldRolesIds = $user->roles->pluck('id')->toArray() ?? null;
            $oldRolesNames = $user->roles->pluck('name')->toArray() ?? null;

            $user->syncRoles($request->role_ids);
            $user->refresh();

            $newRolesIds = $user->roles->pluck('id')->toArray() ?? null;
            $newRolesNames = $user->roles->pluck('name')->toArray() ?? null;

            $saveOldRoleHistory = [
                'id' => json_encode($oldRolesIds) ?? null,
                'name' => json_encode($oldRolesNames) ?? null,
            ];

            $saveNewRoleHistory = [
                'id' => json_encode($newRolesIds) ?? null,
                'name' => json_encode($newRolesNames) ?? null,
            ];

            $checkUpdateChange = array_diff_assoc($saveOldRoleHistory, $saveNewRoleHistory);

            $updateRoleArrayHistory = [];
            $updateRoleArrayHistory['old']['role'] = !empty($checkUpdateChange) ? $saveOldRoleHistory : null;
            $updateRoleArrayHistory['updated']['role'] = !empty($checkUpdateChange) ? $saveNewRoleHistory : null;
            $updatedDataArrayHirtory[] = $updateRoleArrayHistory;

            if ($user) {
                $updatedDataArrayHirtory[] = getUpdatedData($oldUserData, $user, 'user');
                $profile = Profile::where('user_id', $user->id)->first();
                if (isset($profile) && !empty($profile)) {
                    $oldProfileData = $profile->getOriginal();
                    $profileResult = $profile->update([
                        'employment_id' => $request->employment_id ?? null,
                        'joining_date' => $request->joining_date ?? null,
                        'gender' => $request->gender ?? null,
                        'phone_number' => $request->phone_number ?? null,
                    ]);
                    $updatedDataArrayHirtory[] = getUpdatedData($oldProfileData, $profile, 'profile');
                }

                // Profile::where('user_id', $user->id)->update([
                //     'employment_id' => $request->employment_id,
                //     'joining_date' => $request->joining_date,
                //     'gender' => $request->gender,
                //     'phone_number' => $request->phone_number,
                // ]);

                $job_history = JobHistory::where('user_id', $user->id)->where('end_date', null)->first();

                if (isset($job_history) && !empty($job_history)) {
                    $oldJobHistoryData = $job_history->getOriginal();
                    $oldJobRelationArray = [
                        'designation' => $job_history->designation->title ?? null,
                        'employment_status' => $job_history->userEmploymentStatus->employmentStatus->name ?? null,
                    ];
                    $jobHistoryResult = $job_history->update([
                        'created_by' => Auth::user()->id,
                        'designation_id' => $request->designation_id,
                        'employment_status_id' => $request->employment_status_id,
                        'joining_date' => $request->joining_date,
                    ]);
                    $job_history->refresh();
                    $newJobRelationArray = [
                        'designation' => $job_history->designation->title ?? null,
                        'employment_status' => $job_history->userEmploymentStatus->employmentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData($oldJobHistoryData, $job_history, 'job_history', null, $oldJobRelationArray, $newJobRelationArray);
                }

                // $job_history = JobHistory::where('user_id', $user->id)->update([
                //     'created_by' => Auth::user()->id,
                //     'designation_id' => $request->designation_id,
                //     'employment_status_id' => $request->employment_status_id,
                //     'joining_date' => $request->joining_date,
                // ]);

                if (!empty($request->department_id)) {
                    $user_department = DepartmentUser::where('user_id', $user->id)->where('end_date', NULL)->first();
                    if (isset($user_department) && !empty($user_department)) {
                        $oldDepartmentUserData = $user_department->getOriginal();
                        $oldDepartmenRelationArray = [
                            'department' => $user_department->department->name ?? null,
                        ];
                        $departmentUserResult = $user_department->update([
                            'department_id' => $request->department_id,
                            'start_date' => $request->joining_date,
                        ]);
                        $user_department->refresh();
                        $newDepartmenRelationArray = [
                            'department' => $user_department->department->name ?? null,
                        ];
                        $updatedDataArrayHirtory[] = getUpdatedData($oldDepartmentUserData, $user_department, 'user_department', null, $oldDepartmenRelationArray, $newDepartmenRelationArray);
                    }

                    // $user_department = DepartmentUser::where('user_id', $user->id)->where('end_date', NULL)->update([
                    //     'department_id' => $request->department_id,
                    //     'start_date' => $request->joining_date,
                    // ]);
                }

                if ($job_history && !empty($request->salary)) {
                    $salary_history = SalaryHistory::where('user_id', $user->id)->where('end_date', null)->where("status", 1)->first();
                    if (!empty($salary_history)) {
                        $oldSalaryHistoryData = $salary_history->getOriginal();
                        if (!empty($salary_history->currentConversionRate)) {
                            $oldEmployeeConversionRateData = $salary_history->currentConversionRate->getOriginal();
                            $salary_history->currentConversionRate->update([
                                'salary' => $request->salary ?? null,
                                'currency_code' => $request->currency ?? null,
                                'currency_rate' => $request->conversion_rate ?? 0,
                                'conversion_amount' => $request->conversion_amount_hidden ?? 0,
                            ]);
                            $updatedDataArrayHirtory[] = getUpdatedData($oldEmployeeConversionRateData, $salary_history->currentConversionRate, 'employee_conversion_rate');
                        } else {

                            if (isset($request->currency) && !empty($request->currency) && $request->currency !== "PKR") {
                                $create = EmployeeConversionRate::create([
                                    "user_id" => $user->id ?? null,
                                    "salary_history_id" => $salary_history->id ?? null,
                                    "month" => Carbon::now()->format("m"),
                                    "year" => Carbon::now()->format("Y"),
                                    "salary" => $request->salary ?? 0,
                                    'currency_code' => $request->currency ?? null,
                                    'currency_rate' => $request->conversion_rate ?? 0,
                                    'conversion_amount' => $request->conversion_amount_hidden ?? 0,
                                    "status" => 1,
                                ]);

                                $updatedDataArrayHirtory[] = getUpdatedData(null, $create, 'employee_conversion_rate', 'create');
                            }
                        }

                        $salary_history->salary = $request->salary;
                        $salary_history->effective_date = $request->joining_date;
                        $salary_history->currency_code = $request->currency ?? null;
                        $salary_history->currency_rate = $request->conversion_rate ?? null;
                        $salary_history->conversion_amount = $request->conversion_amount_hidden ?? null;
                        $salary_history->save();
                        $updatedDataArrayHirtory[] = getUpdatedData($oldSalaryHistoryData, $salary_history, 'salary_history');
                    } else {
                        $history =   SalaryHistory::create([
                            'created_by' => Auth::user()->id,
                            'user_id' => $user->id,
                            'job_history_id' => $user->jobHistory->id ?? null,
                            'salary' => $request->salary ?? 0,
                            'effective_date' => $request->joining_date ?? null,
                            'status' => 1,
                            'currency_code' => $request->currency ?? null,
                            'currency_rate' => $request->conversion_rate ?? null,
                            'conversion_amount' => $request->conversion_amount_hidden ?? null,
                        ]);
                        $updatedDataArrayHirtory[] = getUpdatedData(null, $history, 'salary_history', 'create');
                        if (isset($history)) {
                            if (isset($request->currency) && !empty($request->currency) && $request->currency !== "PKR") {
                                $create = EmployeeConversionRate::create([
                                    "user_id" => $user->id ?? null,
                                    "salary_history_id" => $history->id ?? null,
                                    "month" => Carbon::now()->format("m"),
                                    "year" => Carbon::now()->format("Y"),
                                    "salary" => $request->salary ?? 0,
                                    'currency_code' => $request->currency ?? null,
                                    'currency_rate' => $request->conversion_rate ?? 0,
                                    'conversion_amount' => $request->conversion_amount_hidden ?? 0,
                                    "status" => 1,
                                ]);
                                $updatedDataArrayHirtory[] = getUpdatedData(null, $create, 'employee_conversion_rate', 'create');
                            }
                        }
                    }
                }

                $user_emp_status = UserEmploymentStatus::orderby('id', 'desc')->where('user_id', $user->id)->where('end_date', null)->first();
                if (!empty($user_emp_status)) {
                    $oldUserEmploymentStatusData = $user_emp_status->getOriginal();
                    $oldUserEmploymentStatusRelationArray = [
                        'employment_status' => $user_emp_status->employmentStatus->name ?? null,
                    ];
                    $user_emp_status->employment_status_id = $request->employment_status_id;
                    $user_emp_status->start_date = $request->joining_date;
                    $user_emp_status->save();
                    $user_emp_status->refresh();
                    $newUserEmploymentStatusRelationArray = [
                        'employment_status' => $user_emp_status->employmentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData($oldUserEmploymentStatusData, $user_emp_status, 'user_employment_status', null, $oldUserEmploymentStatusRelationArray, $newUserEmploymentStatusRelationArray);
                } else {
                    $createUserEmploymentSatatus = UserEmploymentStatus::create([
                        'user_id' => $user->id,
                        'employment_status_id' => $request->employment_status_id,
                        'start_date' => $request->joining_date,
                    ]);
                    $newUserEmploymentStatusRelationArray = [
                        'employment_status' => $createUserEmploymentSatatus->employmentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $createUserEmploymentSatatus, 'user_employment_status', 'create', null, $newUserEmploymentStatusRelationArray);
                }



                $user_work_shift = WorkingShiftUser::orderby('id', 'desc')->where('user_id', $user->id)->where('end_date', null)->first();
                if (!empty($user_work_shift)) {
                    $oldWorkingShiftUserData = $user_work_shift->getOriginal();
                    $oldWorkingShiftUserRelationArray = [
                        'working_shift' => $user_work_shift->workShift->name ?? null,
                    ];
                    $user_work_shift->working_shift_id = $request->work_shift_id;
                    $user_work_shift->start_date = $request->joining_date;
                    $user_work_shift->save();
                    $user_work_shift->refresh();
                    $newWorkingShiftUserRelationArray = [
                        'working_shift' => $user_work_shift->workShift->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData($oldWorkingShiftUserData, $user_work_shift, 'working_shift_user', null, $oldWorkingShiftUserRelationArray, $newWorkingShiftUserRelationArray);
                } else {
                    $createWorkingShiftUser = WorkingShiftUser::create([
                        'user_id' => $user->id,
                        'working_shift_id' => $request->work_shift_id,
                        'start_date' => $request->joining_date,
                    ]);
                    $newWorkingShiftUserRelationArray = [
                        'working_shift' => $createWorkingShiftUser->workShift->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $createWorkingShiftUser, 'working_shift_user', 'create', null, $newWorkingShiftUserRelationArray);
                }

                DB::commit();

                if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                    saveLogs($updatedDataArrayHirtory, "User", $user->id, 4, "Employee", 2);
                }
            }

            //send email if email changed and generated new password.

            \LogActivity::addToLog('Employee updated');


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($slug)
    {
        $this->authorize('employee-show');
        $title = 'Show Details';
        $model = User::where('slug', $slug)->withTrashed()->first();
        $histories = SalaryHistory::orderby('id', 'desc')->where('user_id', $model->id)->get();
        $user_permanent_address = UserContact::where('user_id', $model->id)->where('key', 'permanent_address')->first();
        $user_current_address = UserContact::where('user_id', $model->id)->where('key', 'current_address')->first();
        $user_emergency_contacts = UserContact::where('user_id', $model->id)->where('key', 'emergency_contact')->get();
        return view('admin.employees.show', compact('model', 'histories', 'title', 'user_permanent_address', 'user_current_address', 'user_emergency_contacts'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('employees-delete');
        $find = User::where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\User";
            $historyArray['type'] = "1";
            $historyArray['event_id'] = 10;
            $historyArray['remarks'] = "Employee has been deleted";
            $model = $find->delete();
            if ($model) {
                LogActivity::addToLog('Employee Deleted');
                LogActivity::deleteHistory($historyArray);
                return response()->json([
                    'status' => true,
                ]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function trashed(Request $request)
    {
        $title = 'All Trashed Employees Records';
        $records = User::where('is_employee', 1)->onlyTrashed()->select("*");
        $trashed = true;
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                        return '<span class="text-primary">' . $model->departmentBridge->department->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';

                    if (isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name)) {
                        if ($model->employeeStatus->employmentStatus->name == 'Terminated') {
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Permanent') {
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Probation') {
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        } else {
                            $label = '<span class="badge bg-label-info me-1">' . $model->employeeStatus->employmentStatus->name . '</span>';
                        }
                    }

                    return $label;
                })
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="' . route('employees.restore', $model->id) . '"  class="btn btn-icon btn-label-info waves-effect restore-btn" data-id="' . $model->id . '">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance = $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->where('first_name', 'LIKE', "%$search%")
                                ->orWhere('last_name', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%");
                        });
                    }
                    if ($request->emp_status != "all" && !empty($request->emp_status)) {
                        $instance = $instance->whereHas("employeeStatus", function ($w) use ($request) {
                            $w->where('employment_status_id', $request->emp_status);
                        });
                    }

                    if (isset($request->department) && !empty($request->department) && $request->department != "all") {
                        $department = getDepartmentFromID($request->department);
                        $myDpartUsers = getDepartmentUsers($department);
                        if (!empty($myDpartUsers)) {
                            $instance = $instance->whereIn('id', $myDpartUsers->pluck("user_id")->toArray());
                        }
                    }

                    if (isset($request->shift) && !empty($request->shift) && $request->shift != "all") {
                        $getShift = getShiftFromId($request->shift);
                        if (!empty($getShift)) {
                            $shiftUsers = getShiftUsers($getShift);
                            if (!empty($shiftUsers)) {
                                $instance = $instance->where(function ($w) use ($shiftUsers) {
                                    $w->whereIn('id', $shiftUsers);
                                });
                            } else {
                            }
                        }
                    }
                })
                ->rawColumns(['emp_status', 'status', 'first_name', 'role', 'Department', 'action'])
                ->make(true);
        }

        return view('admin.employees.index', compact('title', 'trashed'));
    }
    public function restore($id)
    {
        $find = User::onlyTrashed()->where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\User";
            $historyArray['type'] = "2";
            $historyArray['event_id'] = 11;
            $historyArray['remarks'] = "Employee has been restored";
            $restore = $find->restore();
            if (!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return redirect()->back()->with('error', 'Failed to restore.');
        }
    }

    public function status(Request $request, $user_id)
    {
        $updatedDataArrayHirtory = [];
        $model = User::where('id', $user_id)->first();
        $oldUserData = $model->getOriginal();
        if ($request->status_type == 'status') {
            if ($model->status == 1) {
                $model->status = 0;
                $event_id = 4;
            } else {
                $model->status = 1; //Active
                $event_id = 3;
            }

            $model->save();
            $model->refresh();
            //send email if possible

            $updatedDataArrayHirtory[] = getUpdatedData($oldUserData, $model, 'user');
            if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                saveLogs($updatedDataArrayHirtory, 'User', $model->id, 4, 'Employee', $event_id);
            }

            \LogActivity::addToLog('Status updated');
            return response()->json(['success' => true]);
        } elseif ($request->status_type == 'remove') {
            $model->is_employee = 0;
            $model->save();
            $model->refresh();
            $updatedDataArrayHirtory[] = getUpdatedData($oldUserData, $model, 'user');
            if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                saveLogs($updatedDataArrayHirtory, 'User', $model->id, 4, 'Employee', 9);
            }
            \LogActivity::addToLog('Removed from list');
            return response()->json(['success' => true]);
        }
        // elseif($request->status_type=='terminate'){
        //     $user_emp_status = UserEmploymentStatus::orderby('id', 'desc')->where('user_id', $user_id)->first();
        //     $user_emp_status->end_date = date('Y-m-d');
        //     $user_emp_status->save();

        //     $terminate_status_id = EmploymentStatus::where('name', 'Terminated')->first()->id;

        //     UserEmploymentStatus::create([
        //         'user_id' => $user_id,
        //         'employment_status_id' => $terminate_status_id,
        //         'start_date' => date('Y-m-d'),
        //     ]);

        //     $model->status = 0; //set to deactive
        //     $model->save();

        //     //send email.
        //     try{
        //         $admin_user = User::role('Admin')->first();

        //         $body = "Dear All, <br /><br />".
        //                 "I am writing to inform you that we have terminated the employment of ".$model->first_name." from our organization, effective immediately. <br /><br />".
        //                 "As per company policy, I am notifying you of this termination and providing you with the necessary information for payroll and other administrative purposes. <br /><br />".

        //                 $model->first_name. " 's final paycheck will be processed and distributed in accordance with state and federal laws.Please note that Amar Chand will no longer have access to our organization's portals, systems, and resources, effective immediately. We kindly request that you take the necessary steps to revoke their access and ensure the security of our systems and data.. <br /><br />".

        //                 "If you have any questions or concerns regarding this matter, please do not hesitate to contact me. <br /><br /><br />".
        //                 "Thank you for your attention to this matter. <br /><br />";

        //         $thanks_regards = "Sincerely, <br /><br />".
        //                           $admin_user->first_name;

        //         $mailData = [
        //             'title' => 'Employee Termination Notification - '.$model->first_name,
        //             'body' => $body,
        //             'footer' => $thanks_regards
        //         ];

        //         if(!empty(sendEmailTo($model, 'employee_termination')) && !empty(sendEmailTo($model, 'employee_termination')['cc_emails'])){
        //             $to_emails = sendEmailTo($model, 'employee_termination')['to_emails'];
        //             $cc_emails = sendEmailTo($model, 'employee_termination')['cc_emails'];
        //             Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
        //         }elseif(!empty(sendEmailTo($model, 'employee_termination')['to_emails'])){
        //             $to_emails = sendEmailTo($model, 'employee_termination')['to_emails'];
        //             Mail::to($to_emails)->send(new Email($mailData));
        //         }

        //         \LogActivity::addToLog('Terminated employee');
        //         return response()->json(['success' => true]);
        //     } catch (\Exception $e) {
        //         DB::rollback();
        //         return $e->getMessage();
        //     }
        //     //send email.
        // }
    }

    public function getPromoteData(Request $request)
    {
        $data = [];

        $data['model'] = User::where('id', $request->user_id)->first();
        $data['departments'] = Department::where('status', 1)->latest()->get();
        $data['designations'] = Designation::orderby('id', 'desc')->latest()->where('status', 1)->get();
        $data['roles'] = Role::orderby('id', 'desc')->latest()->get();
        $data['salaryHistory'] = $data['model']->salaryHistory;
        return (string) view('admin.employees.promote', compact('data'));
    }

    public function promote(Request $request)
    {

        $request->validate([
            'department_id' => 'required',
            'designation_id' => 'required',
            'raise_salary' => 'required',
            'effective_date' => 'required',
        ]);

        DB::beginTransaction();

        try {


            $updatedDataArrayHirtory = [];
            $user_emp_status = UserEmploymentStatus::orderby('id', 'desc')->where('end_date', null)->where('user_id', $request->user_id)->first();
            if (!empty($user_emp_status)) {
                $user_emp_status->end_date = date('Y-m-d');
                $user_emp_status->save();
                $user_emp_status->refresh();
                $oldUserEmploymentStatusData = $user_emp_status->getOriginal();
                $oldUserEmploymentStatusRelationArray = [
                    'employment_status' => $user_emp_status->employmentStatus->name ?? null,
                ];

                $user_emp_status_create = UserEmploymentStatus::create([
                    'user_id' => $request->user_id,
                    'employment_status_id' => 2, //permanent
                    'start_date' => date('Y-m-d'),
                ]);

                $newUserEmploymentStatusRelationArray = [
                    'employment_status' => $user_emp_status_create->employmentStatus->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData($oldUserEmploymentStatusData, $user_emp_status_create, 'user_employment_status', 'both', $oldUserEmploymentStatusRelationArray, $newUserEmploymentStatusRelationArray);
            } else {
                $user_emp_status_create = UserEmploymentStatus::create([
                    'user_id' => $request->user_id,
                    'employment_status_id' => 2, //permanent
                    'start_date' => date('Y-m-d'),
                ]);

                $newUserEmploymentStatusRelationArray = [
                    'employment_status' => $user_emp_status_create->employmentStatus->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData(null, $user_emp_status_create, 'user_employment_status', 'create', null, $newUserEmploymentStatusRelationArray);
            }

            $job_history = JobHistory::orderby('id', 'desc')->where('user_id', $request->user_id)->where('end_date', null)->first();

            $new_job_job_history = $job_history;

            //current
            if (!empty($job_history)) {
                if ($job_history->designation_id != $request->designation_id || !empty($request->vehicle_name)) {
                    $oldJobRelationArray = [
                        'designation' => $job_history->designation->title ?? null,
                        'employment_status' => $job_history->userEmploymentStatus->employmentStatus->name ?? null,
                    ];
                    $job_history->end_date = $request->effective_date;
                    $job_history->save();
                    $job_history->refresh();
                    $oldJobHistoryData = $job_history->getOriginal();

                    $new_job_job_history = JobHistory::create([
                        'designation_id' => $request->designation_id,
                        'user_id' => $request->user_id,
                        'employment_status_id' => 2, //Permanent Employee status
                        'joining_date' => $request->effective_date,
                        'vehicle_name' => $request->vehicle_name,
                        'vehicle_cc' => $request->vehicle_cc,
                    ]);
                    $newJobRelationArray = [
                        'designation' => $new_job_job_history->designation->title ?? null,
                        'employment_status' => $new_job_job_history->userEmploymentStatus->employmentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData($oldJobHistoryData, $new_job_job_history, 'job_history', 'both', $oldJobRelationArray, $newJobRelationArray);
                }
            } else {
                $new_job_job_history = JobHistory::create([
                    'designation_id' => $request->designation_id,
                    'user_id' => $request->user_id,
                    'employment_status_id' => 2, //Permanent Employee status
                    'joining_date' => $request->effective_date,
                    'vehicle_name' => $request->vehicle_name,
                    'vehicle_cc' => $request->vehicle_cc,
                ]);
                $newJobRelationArray = [
                    'designation' => $new_job_job_history->designation->title ?? null,
                    'employment_status' => $new_job_job_history->userEmploymentStatus->employmentStatus->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData(null, $new_job_job_history, 'job_history', 'create', null, $newJobRelationArray);
            }
            $last_salary = SalaryHistory::where('job_history_id', $job_history->id)->where('end_date', null)->where('status', 1)->first();

            $salary_history = $last_salary;
            if (!empty($last_salary)) {

                $oldSalaryHistoryData = $last_salary->getOriginal();
                $last_salary->status = 0;
                $last_salary->end_date = $request->effective_date;
                $last_salary->save();
                $last_salary->refresh();

                $updated_salary = (int)$last_salary->salary + (int)$request->raise_salary;
                $updated_conversion = (int)$last_salary->conversion_amount + (int)$request->conversion_amount_hidden;
                $salary_history = SalaryHistory::create([
                    'created_by' => Auth::user()->id,
                    'user_id' => $request->user_id,
                    'job_history_id' => $new_job_job_history->id,
                    'raise_salary' => $request->raise_salary,
                    'salary' => $updated_salary,
                    'effective_date' => $request->effective_date,
                    'currency_code' => $last_salary->currency_code ?? null,
                    'currency_rate' => $last_salary->currency_rate ?? null,

                ]);

                $updatedDataArrayHirtory[] = getUpdatedData($oldSalaryHistoryData, $salary_history, 'salary_history', 'update');
            } else {
                $salary_history = SalaryHistory::create([
                    'created_by' => Auth::user()->id,
                    'user_id' => $request->user_id,
                    'job_history_id' => $new_job_job_history->id,
                    'raise_salary' => $request->raise_salary,
                    'salary' => (int)$request->raise_salary,
                    'effective_date' => $request->effective_date,
                ]);
                $updatedDataArrayHirtory[] = getUpdatedData(null, $salary_history, 'salary_history', 'create');
            }
            // dd("wait" , $salary_history);
            $user_department = DepartmentUser::orderby('id', 'desc')->where('user_id', $request->user_id)->where('end_date', null)->first();
            if (!empty($user_department) && $user_department->department_id != $request->department_id) {
                $user_department->end_date = $request->effective_date;
                $user_department->save();
                $user_department->refresh();
                $oldDepartmentUserData = $user_department->getOriginal();
                $oldDepartmenRelationArray = [
                    'department' => $user_department->department->name ?? null,
                ];

                $newDepartmentUserCreate = DepartmentUser::create([
                    'department_id' => $request->department_id,
                    'user_id' => $request->user_id,
                    'start_date' => $request->effective_date,
                ]);
                $newDepartmenRelationArray = [
                    'department' => $newDepartmentUserCreate->department->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData($oldDepartmentUserData, $newDepartmentUserCreate, 'user_department', 'both', $oldDepartmenRelationArray, $newDepartmenRelationArray);
            }
            if (empty($user_department)) {
                $newDepartmentUserCreate = DepartmentUser::create([
                    'department_id' => $request->department_id,
                    'user_id' => $request->user_id,
                    'start_date' => $request->effective_date,
                ]);
                $newDepartmenRelationArray = [
                    'department' => $newDepartmentUserCreate->department->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData(null, $newDepartmentUserCreate, 'user_department', 'create', null, $newDepartmenRelationArray);
            }
            //current

            $newEmployeeLetterCreate = EmployeeLetter::create([
                'created_by' => Auth::user()->id,
                'employee_id' => $request->user_id,
                'title' => 'promotion_letter',
                'effective_date' => $request->effective_date,
                'validity_date' => $request->validity_date ?? NULL,
            ]);
            $updatedDataArrayHirtory[] = getUpdatedData(null, $newEmployeeLetterCreate, 'employee_letter', 'create');

            DB::commit();

            if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                saveLogs($updatedDataArrayHirtory, 'User', $request->user_id, 4, 'Employee', 7);
            }



            \LogActivity::addToLog('Employee has been promoted');
            $model = User::where('id', $request->user_id)->first();

            // send email on salary increments.
            try {
                $current_salary = 0;
                if (isset($model->salaryHistory) && !empty($model->salaryHistory->salary)) {
                    $current_salary = $model->salaryHistory->salary;
                }

                $updated_salary = $current_salary + $request->raise_salary;
                if (getAppMode() == "live") {

                    $body = [
                        'name' => $model->first_name . ' ' . $model->last_name,
                        'effective_date' => date('d M Y', strtotime($request->effective_date)),
                        'current_salary' => number_format($current_salary),
                        'increased_salary' => number_format($request->raise_salary),
                        'updated_salary' => number_format($updated_salary),
                    ];

                    $mailData = [
                        'from' => 'salary_increments',
                        'title' => 'Promotion',
                        'body' => $body,
                    ];

                    $increament_message = [
                        'id' => $salary_history->id,
                        'profile' => $salary_history->createdBy->profile->profile,
                        'name' => $salary_history->createdBy->first_name . ' ' . $salary_history->createdBy->last_name,
                        'title' => 'Congratulation! You have been promoted.',
                        'message' => 'This promotion reflects your outstanding performance, commitment, and the value you bring to our organization.',
                    ];

                    $model->notify(new SalaryIncreamentNotification($increament_message));

                    if (!empty(sendEmailTo($model, 'promotion')) && !empty(sendEmailTo($model, 'promotion')['cc_emails'])) {
                        $to_emails = sendEmailTo($model, 'promotion')['to_emails'];
                        $cc_emails = sendEmailTo($model, 'promotion')['cc_emails'];
                        Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
                    } elseif (!empty(sendEmailTo($model, 'promotion')['to_emails'])) {
                        $to_emails = sendEmailTo($model, 'promotion')['to_emails'];
                        Mail::to($to_emails)->send(new Email($mailData));
                    }
                }
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
            //send email.

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function storeWorkShift(Request $request)
    {
        $request->validate([
            'working_shift_id' => 'required',
            'start_date' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $updatedDataArrayHirtory = [];
            $login_user = Auth::user();
            $current_shift = WorkingShiftUser::orderby('id', 'desc')->where('user_id', $request->user_id)->where('end_date', null)->first();
            if (isset($current_shift) && !empty($current_shift) && $current_shift->working_shift_id == $request->working_shift_id) {
                return response()->json(['success' => false, "message" =>  "Please select another shift if you want to update!"]);
            }
            if (isset($current_shift) && !empty($current_shift) && $current_shift->working_shift_id != $request->working_shift_id) {
                $current_shift->end_date = $request->start_date;
                $current_shift->save();
                $oldWorkingShiftUserData = $current_shift->getOriginal();
                $oldWorkingShiftUserRelationArray = [
                    'working_shift' => $current_shift->workShift->name ?? null,
                ];
                $model = WorkingShiftUser::create([
                    'working_shift_id' => $request->working_shift_id,
                    'user_id' => $request->user_id,
                    'start_date' => $request->start_date,
                ]);
                $newWorkingShiftUserRelationArray = [
                    'working_shift' => $model->workShift->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData($oldWorkingShiftUserData, $model, 'working_shift_user', 'both', $oldWorkingShiftUserRelationArray, $newWorkingShiftUserRelationArray);
            }
            if (empty($current_shift)) {
                $model = WorkingShiftUser::create([
                    'working_shift_id' => $request->working_shift_id,
                    'user_id' => $request->user_id,
                    'start_date' => $request->start_date,
                ]);

                $newWorkingShiftUserRelationArray = [
                    'working_shift' => $model->workShift->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData(null, $model, 'working_shift_user', 'create', null, $newWorkingShiftUserRelationArray);
            }
            if(!empty($updatedDataArrayHirtory)){
                $notification_data = [
                    'id' => $model->id ?? "",
                    'date' => $model->start_date,
                    'type' => 'shift',
                    'name' => $login_user->first_name . ' ' . $login_user->last_name,
                    'profile' => $login_user->profile->profile,
                    'title' => 'Your shift has been updated',
                    'reason' => 'updated.',
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $model->hasEmployee->notify(new ImportantNotificationWithMail($notification_data));

                    if ($model->hasEmployee->hasRole('Department Manager')) {
                        $parent_department = Department::where('manager_id', $model->user_id)->first();
                        $manager = $parent_department->parentDepartment->manager;
                    } else {
                        $manager = $model->hasEmployee->departmentBridge->department->manager;
                    }

                    $manager->notify(new ImportantNotificationWithMail($notification_data));
                }


                if (!empty($model->hasEmployee->hasRole) && $model->hasEmployee->hasRole('Department Manager')) {
                    $parent_department = Department::where('manager_id', $model->user_id)->first();
                    if (isset($parent_department) && !empty($parent_department)) {
                        $manager = $parent_department->parentDepartment->manager;
                    }
                } else {
                    if (isset($model->hasEmployee->departmentBridge->department->manager) && !empty($model->hasEmployee->departmentBridge->department->manager)) {
                        $manager = $model->hasEmployee->departmentBridge->department->manager;
                    }
                }
                if (isset($manager) && !empty($manager)) {
                    $manager->notify(new ImportantNotificationWithMail($notification_data));
                }
            }
            DB::commit();
            if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                saveLogs($updatedDataArrayHirtory, 'User', $request->user_id, 4, 'Employee', 5);
            }


            \LogActivity::addToLog('Shift has been updated.');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            DB::rollback();
            return redirect()->back()->with('error', 'Error. ' . $e->getMessage());
        }
    }

    public function salaryDetails($getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('employee_salary_details-list');
        $title = 'Salary Details';

        $data = [];

        $logined_user = Auth::user();

        if (isset($user_slug) && !empty($user_slug)) {
            $user = User::where('slug', $user_slug)->first();
        } else {
            $user = $logined_user;
        }
        $currency_code = !empty($user) ?  getCurrencyCodeForSalary($user) :  "Rs.";
        $user_joining_date = date('m/Y');
        if (isset($user->joiningDate->joining_date) && !empty($user->joiningDate->joining_date)) {
            $user_joining_date = date('m/Y', strtotime($user->joiningDate->joining_date));
        }

        $data['user_joining_date'] = $user_joining_date;

        $employees = [];

        if ($logined_user->hasRole('Department Manager')) {
            // $department = Department::where('manager_id', $logined_user->id)->first();
            // $departs = [];
            // if (isset($department) && !empty($department->id)) {
            //     $departs[] = $department->id;
            // }
            // $sub_deps = Department::where('parent_department_id', $department->id)->get();
            // if (!empty($sub_deps)) {
            //     foreach ($sub_deps as $sub_dept) {
            //         $departs[] = $sub_dept->id;
            //     }
            // }

            // $department_users = DepartmentUser::whereIn('department_id',  $departs)->where('end_date', NULL)->get();
            // foreach ($department_users as $department_user) {
            //     $dep_users = User::where('id', $department_user->user_id)->where('status', 1)->where('is_employee', 1)->first(['id', 'first_name', 'last_name', 'slug']);
            //     if (!empty($dep_users)) {
            //         $employees[] = $dep_users;
            //     }
            // }

            $employees = getTeamMembers($logined_user);
        } elseif ($logined_user->hasRole('Admin') || $logined_user->hasPermissionTo('payslip_editable-create')) {
            $employees = User::select(['id', 'first_name', 'last_name', 'slug'])->orderby('id', 'desc')->get();
        }

        $month = date('m');
        $year = date('Y');

        $data['month'] = date('m');
        $data['year'] = date('Y');

        $data['user'] = $user;
        $data['employees'] = $employees;
        $daysData = getMonthDaysForSalary();

        $data['currentMonth'] = date('m/Y');
        if (date('d') > 25) {
            $data['currentMonth'] = date('m/Y', strtotime('first day of +1 month'));

            $data['month'] = date('m', strtotime('first day of +1 month'));
            if ($data['month'] == 01) {
                $data['year'] = date('Y', strtotime('first day of +1 month'));
            }
        }
        if (!empty($user->employeeStatus->end_date)) {
            // $data['currentMonth'] = date('m/Y', strtotime($user->employeeStatus->end_date));
            if (isset($getMonth) && !empty($getMonth)) {
                $filterMonthYear = $getYear . '/' . $getMonth;
                $lastMonthYear = date('Y/m', strtotime($user->employeeStatus->end_date));
                if ($filterMonthYear <= $lastMonthYear) {
                    $data['month'] = $getMonth;
                    $data['year'] = $getYear;
                } else {
                    $data['month'] = date('m', strtotime($user->employeeStatus->end_date));
                    $data['year'] = date('Y', strtotime($user->employeeStatus->end_date));
                }
            }
        } else {
            if (isset($getMonth) && !empty($getMonth)) {
                $data['month'] = $getMonth;
                $data['year'] = $getYear;
            }
        }

        $daysData = getMonthDaysForSalary($data['year'], $data['month']);

        if (!$logined_user->hasPermissionTo('allow_salary_details-show')) {
            if (checkSalarySlipGenerationDate($daysData) == true) {
                $message = "We're in the process of preparing the salary slips, and they'll be available shortly.";
                return view('admin.salary.salary-details', compact('title', 'message', 'data'));
            }
        }

        $total_earning_days = $daysData->total_days;
        if ((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))) {
            $empStartMonthDate = $user->employeeStatus->start_date;
            $empStartMonthDate = Carbon::parse($empStartMonthDate);
            $startMonthDate = Carbon::parse($daysData->first_date);
            $endMonthDate = Carbon::parse($daysData->last_date);
            $monthYear = $data['month'] . '/' . $data['year'];
            if ($empStartMonthDate->gte($startMonthDate) && $empStartMonthDate->lte($endMonthDate) && date('m', strtotime($startMonthDate)) <= date('m', strtotime($empStartMonthDate))) {
                $total_earning_days = $empStartMonthDate->diffInDays($endMonthDate->addDay());
            } elseif ($monthYear == $data['currentMonth']) {
                $currentDate = Carbon::now();
                $total_earning_days = $currentDate->diffInDays($startMonthDate);
            } else {
                $salaryMonthYear = Carbon::createFromFormat('m/Y', $monthYear);
                $currentMonthYear = Carbon::createFromFormat('m/Y', $data['currentMonth']);
                if ($salaryMonthYear->greaterThan($currentMonthYear)) {
                    $explode_month_year = explode('/', $data['currentMonth']);
                    $data['month'] = $explode_month_year[0];
                    $data['year'] = $explode_month_year[1];
                    return redirect('employees/salary_details/' . $data['month'] . '/' . $data['year'] . '/' . $data['user']->slug);
                }
            }
        }

        $data['total_earning_days'] = $total_earning_days;

        $date = date('F Y', mktime(0, 0, 0, $data['month'], 1, $data['year']));
        $data['month_year'] = $date;

        $date = Carbon::create($data['year'], $data['month']);

        // Create a Carbon instance for the specified month
        $dateForMonth = Carbon::create($data['year'], $data['month'], 1);

        // Calculate the start date (26th of the specified month)
        $startDate = $dateForMonth->copy()->subMonth()->startOfMonth()->addDays(25);
        $endDate = $dateForMonth->copy()->startOfMonth()->addDays(25);

        // Calculate the total days
        $data['totalDays'] = $startDate->diffInDays($endDate);

        $data['salary'] = 0;
        if (isset($user->salaryHistory) && !empty($user->salaryHistory->salary)) {
            $data['salary'] =  $user->salaryHistory->salary;
            $data['per_day_salary'] = $data['salary'] / $data['totalDays'];
        } else {
            $data['per_day_salary'] = 0;
            $data['actual_salary'] =  0;
        }

        if (isset($user->userWorkingShift) && !empty($user->userWorkingShift->working_shift_id)) {
            $data['shift'] = $user->userWorkingShift->workShift;
        } else {
            $data['shift'] = defaultShift();
        }

        $statistics = getAttandanceCount($data['user']->id, $data['year'] . "-" . ((int)$data['month'] - 1) . "-26", $data['year'] . "-" . (int)$data['month'] . "-25", 'all', $data['shift']);

        $lateIn = count($statistics['lateInDates']);
        $earlyOut = count($statistics['earlyOutDates']);
        $total_discrepancies = $lateIn + $earlyOut;

        $filled_discrepencies = Discrepancy::where('user_id', $user->id)->where('status', 1)->whereBetween('date', [$startDate, $endDate])->count();

        $total_over_discrepancies = 0;
        $discrepancies_absent_days = 0;
        $data['late_in_early_out_amount'] = 0;
        if ($filled_discrepencies > 2 && $total_discrepancies > $filled_discrepencies) {
            $total_over_discrepancies = $total_discrepancies - $filled_discrepencies;
            $discrepancies_absent_days = floor($total_over_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        } elseif ($total_over_discrepancies > 2) {
            $discrepancies_absent_days = floor($total_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        }

        //Calculation late in and early out days amount.
        $total_approved_discrepancies = 0;

        if ($filled_discrepencies > 2) {
            $total_approved_discrepancies = floor($total_over_discrepancies / 3);
            $total_approved_discrepancies = $total_approved_discrepancies / 2;
        }
        $data['totalDiscrepanciesEarlyOutApprovedAmount'] = $total_approved_discrepancies * $data['per_day_salary'];
        //Calculation late in and early out days amount.

        $filled_full_day_leaves = UserLeave::where('user_id', $user->id)
            ->where('status', 1)
            ->whereMonth('start_at', $data['month'])
            ->whereYear('start_at', $data['year'])
            ->where('behavior_type', 'Full Day')
            ->get();

        $filled_full_day_leaves = $filled_full_day_leaves->sum('duration');

        $filled_half_day_leaves = UserLeave::where('user_id', $user->id)
            ->where('status', 1)
            ->whereMonth('start_at', $data['month'])
            ->whereYear('start_at', $data['year'])
            ->where('behavior_type', 'First Half')
            ->orWhere('behavior_type', 'Last Half')
            ->count();

        $over_half_day_leaves = 0;
        if ($filled_half_day_leaves > 0) {
            $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
            $over_half_day_leaves = $filled_half_day_leaves / 2;

            $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
        } else {
            $over_half_day_leaves = $statistics['halfDay'] / 2;
            $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
        }

        $over_absent_days = 0;
        if ($filled_full_day_leaves > 0) {
            $over_absent_days = $statistics['absent'] - $filled_full_day_leaves;
            $data['absent_days_amount'] = $over_absent_days * $data['per_day_salary'];
        } else {
            $data['absent_days_amount'] = $statistics['absent'] * $data['per_day_salary'];
            $over_absent_days = $statistics['absent'];
        }

        //calculation approved absent and half days amount.
        $totalApprovedFullDayHalfDays = $filled_half_day_leaves + $filled_full_day_leaves;
        $totalApprovedFullDayHalfDaysAmount = 0;
        if ($totalApprovedFullDayHalfDays > 0) {
            $totalApprovedFullDayHalfDaysAmount = $totalApprovedFullDayHalfDays * $data['per_day_salary'];
        }
        $data['totalApprovedFullDayHalfDayAmount'] = $totalApprovedFullDayHalfDaysAmount;
        //calculation approved absent and half days amount.

        //total Approved Amount
        $data['totalApprovedAmount'] = $data['totalApprovedFullDayHalfDayAmount'] + $data['totalDiscrepanciesEarlyOutApprovedAmount'];
        //total Approved Amount

        $total_full_and_half_days_absent = $over_absent_days + $over_half_day_leaves;

        $all_absents = $total_full_and_half_days_absent + $discrepancies_absent_days;
        $all_absent_days_amount = $data['per_day_salary'] * $all_absents;
        $logData['all_absents'] = $all_absents;
        $logData['over_half_day_leaves'] = $over_half_day_leaves;
        $data['earning_days_amount'] =  $data['total_earning_days'] * $data['per_day_salary'];

        if (!empty($user->hasAllowance) && date('Y-m-d') >= date('Y-m-d', strtotime($user->hasAllowance->effective_date))) {
            $data['car_allowance'] = $user->hasAllowance->allowance;
        } else {
            $data['car_allowance'] = 0;
        }
        $data['total_actual_salary'] = number_format($data['salary'] + $data['car_allowance']);
        $totalApprovedDaysAndAbsentDaysAmount = $data['totalApprovedAmount'] + $all_absent_days_amount;
        $total_earning_salary = $data['earning_days_amount'] - $totalApprovedDaysAndAbsentDaysAmount;
        $data['total_earning_salary'] = number_format($data['earning_days_amount'] + $data['car_allowance']);
        $data['total_leave_discrepancies_approve_salary'] = $all_absent_days_amount;
        $all_absent_days_amount = $data['late_in_early_out_amount'] + $data['half_days_amount'] + $data['absent_days_amount'];
        $total_net_salary = $data['earning_days_amount'] - $all_absent_days_amount;
        $data['net_salary'] = number_format($total_net_salary + $data['car_allowance']);
        // return $data;
        return view('admin.salary.salary-details',  compact('title', 'data', 'currency_code'));
    }

    public function generateSalarySlip($getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('generate_pay_slip-create');
        $title = 'Pay Slip';
        $data = [];

        $logined_user = Auth::user();

        if (isset($user_slug) && !empty($user_slug)) {
            $user = User::where('slug', $user_slug)->first();
        } else {
            $user = $logined_user;
        }

        $month = date('m');
        $year = date('Y');

        $data['user'] = $user;
        $daysData = getMonthDaysForSalary();
        $data['currentMonth'] = date('m/Y');
        if (date('d') > 25) {
            $data['currentMonth'] = date('m/Y', strtotime('first day of +1 month'));

            $data['month'] = date('m', strtotime('first day of +1 month'));
            if ($data['month'] == 01) {
                $data['year'] = date('Y', strtotime('first day of +1 month'));
            }
        }
        if (isset($getMonth) && !empty($getMonth)) {
            $data['month'] = $getMonth;
            $data['year'] = $getYear;
        }
        $daysData = getMonthDaysForSalary($data['year'], $data['month']);

        if (!$logined_user->hasPermissionTo('allow_salary_details-show')) {
            if (checkSalarySlipGenerationDate($daysData) == true) {
                $message = 'We are generating slip wait...';
                return view('admin.salary.salary-details', compact('title', 'message', 'data'));
            }
        }

        $total_earning_days = $daysData->total_days;
        if ((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))) {
            $empStartMonthDate = $user->employeeStatus->start_date;
            $empStartMonthDate = Carbon::parse($empStartMonthDate);
            $startMonthDate = Carbon::parse($daysData->first_date);
            $endMonthDate = Carbon::parse($daysData->last_date);
            $monthYear = $data['month'] . '/' . $data['year'];
            if ($empStartMonthDate->gte($startMonthDate) && $empStartMonthDate->lte($endMonthDate) && date('m', strtotime($startMonthDate)) <= date('m', strtotime($empStartMonthDate))) {
                $total_earning_days = $empStartMonthDate->diffInDays($endMonthDate->addDay());
            } elseif ($monthYear == $data['currentMonth']) {
                $currentDate = Carbon::now();
                $total_earning_days = $currentDate->diffInDays($startMonthDate);
            } else {
                $salaryMonthYear = Carbon::createFromFormat('m/Y', $monthYear);
                $currentMonthYear = Carbon::createFromFormat('m/Y', $data['currentMonth']);
                if ($salaryMonthYear->greaterThan($currentMonthYear)) {
                    $explode_month_year = explode('/', $data['currentMonth']);
                    $data['month'] = $explode_month_year[0];
                    $data['year'] = $explode_month_year[1];
                    return redirect('employees/generate_salary_slip/' . $data['month'] . '/' . $data['year'] . '/' . $data['user']->slug);
                }
            }
        }

        $data['total_earning_days'] = $total_earning_days;

        $date = Carbon::createFromFormat('Y-m', $data['year'] . '-' . $data['month']);
        $data['month_year'] = $date->format('M Y');

        $date = Carbon::create($data['year'], $data['month']);

        // Create a Carbon instance for the specified month
        $dateForMonth = Carbon::create($data['year'], $data['month'], 1);

        // Calculate the start date (26th of the specified month)
        $startDate = $dateForMonth->copy()->subMonth()->startOfMonth()->addDays(25);
        $endDate = $dateForMonth->copy()->startOfMonth()->addDays(25);

        // Calculate the total days
        $data['totalDays'] = $startDate->diffInDays($endDate);

        $data['salary'] = 0;
        if (isset($user->salaryHistory) && !empty($user->salaryHistory->salary)) {
            $data['salary'] =  $user->salaryHistory->salary;
            $data['per_day_salary'] = $data['salary'] / $data['totalDays'];
        } else {
            $data['per_day_salary'] = 0;
            $data['actual_salary'] =  0;
        }

        if (isset($user->userWorkingShift) && !empty($user->userWorkingShift->working_shift_id)) {
            $data['shift'] = $user->userWorkingShift->workShift;
        } else {
            $data['shift'] = defaultShift();
        }

        $statistics = getAttandanceCount($data['user']->id, $data['year'] . "-" . ((int)$data['month'] - 1) . "-26", $data['year'] . "-" . (int)$data['month'] . "-25", 'all', $data['shift']);

        $lateIn = count($statistics['lateInDates']);
        $earlyOut = count($statistics['earlyOutDates']);

        $total_discrepancies = $lateIn + $earlyOut;

        $filled_discrepencies = Discrepancy::where('user_id', $user->id)->where('status', 1)->whereBetween('date', [$startDate, $endDate])->count();

        $total_over_discrepancies = 0;
        $discrepancies_absent_days = 0;
        $data['late_in_early_out_amount'] = 0;
        if ($filled_discrepencies > 2 && $total_discrepancies > $filled_discrepencies) {
            $total_over_discrepancies = $total_discrepancies - $filled_discrepencies;
            $discrepancies_absent_days = floor($total_over_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        } elseif ($total_over_discrepancies > 2) {
            $discrepancies_absent_days = floor($total_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        }

        //Calculation late in and early out days amount.
        $total_approved_discrepancies = 0;

        if ($filled_discrepencies > 2) {
            $total_approved_discrepancies = floor($total_over_discrepancies / 3);
            $total_approved_discrepancies = $total_approved_discrepancies / 2;
        }
        $data['totalDiscrepanciesEarlyOutApprovedAmount'] = $total_approved_discrepancies * $data['per_day_salary'];
        //Calculation late in and early out days amount.


        $filled_full_day_leaves = UserLeave::where('user_id', $user->id)
            ->where('status', 1)
            ->whereMonth('start_at', $data['month'])
            ->whereYear('start_at', $data['year'])
            ->where('behavior_type', 'Full Day')
            ->get();

        $filled_full_day_leaves = $filled_full_day_leaves->sum('duration');

        $filled_half_day_leaves = UserLeave::where('user_id', $user->id)
            ->where('status', 1)
            ->whereMonth('start_at', $data['month'])
            ->whereYear('start_at', $data['year'])
            ->where('behavior_type', 'First Half')
            ->orWhere('behavior_type', 'Last Half')
            ->count();

        $over_half_day_leaves = 0;
        if ($filled_half_day_leaves > 0) {
            $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
            $over_half_day_leaves = $filled_half_day_leaves / 2;

            $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
        } else {
            $over_half_day_leaves = $statistics['halfDay'] / 2;
            $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
        }

        $over_absent_days = 0;
        if ($filled_full_day_leaves > 0) {
            $over_absent_days = $statistics['absent'] - $filled_full_day_leaves;
            $data['absent_days_amount'] = $over_absent_days * $data['per_day_salary'];
        } else {
            $data['absent_days_amount'] = $statistics['absent'] * $data['per_day_salary'];
        }

        //calculation approved absent and half days amount.
        $totalApprovedFullDayHalfDays = $filled_half_day_leaves + $filled_full_day_leaves;
        $totalApprovedFullDayHalfDaysAmount = 0;
        if ($totalApprovedFullDayHalfDays > 0) {
            $totalApprovedFullDayHalfDaysAmount = $totalApprovedFullDayHalfDays * $data['per_day_salary'];
        }
        $data['totalApprovedFullDayHalfDayAmount'] = $totalApprovedFullDayHalfDaysAmount;
        //calculation approved absent and half days amount.

        //total Approved Amount
        $data['totalApprovedAmount'] = $data['totalApprovedFullDayHalfDayAmount'] + $data['totalDiscrepanciesEarlyOutApprovedAmount'];
        //total Approved Amount

        $total_full_and_half_days_absent = $over_absent_days + $over_half_day_leaves;

        $all_absents = $total_full_and_half_days_absent + $discrepancies_absent_days;
        $all_absent_days_amount = $data['per_day_salary'] * $all_absents;
        $logData['all_absents'] = $all_absents;
        $logData['over_half_day_leaves'] = $over_half_day_leaves;
        $data['earning_days_amount'] =  $data['total_earning_days'] * $data['per_day_salary'];

        if (!empty($user->hasAllowance) && date('Y-m-d') >= date('Y-m-d', strtotime($user->hasAllowance->effective_date))) {
            $data['car_allowance'] = $user->hasAllowance->allowance;
        } else {
            $data['car_allowance'] = 0;
        }
        $data['total_actual_salary'] = number_format($data['salary'] + $data['car_allowance']);
        $totalApprovedDaysAndAbsentDaysAmount = $data['totalApprovedAmount'] + $all_absent_days_amount;
        $total_earning_salary = $data['earning_days_amount'] - $totalApprovedDaysAndAbsentDaysAmount;
        $data['total_earning_salary'] = number_format($data['earning_days_amount'] + $data['car_allowance']);
        $data['total_leave_discrepancies_approve_salary'] = $all_absent_days_amount;
        $all_absent_days_amount = $data['late_in_early_out_amount'] + $data['half_days_amount'] + $data['absent_days_amount'];
        $total_net_salary = $data['earning_days_amount'] - $all_absent_days_amount;
        $data['net_salary'] = number_format($total_net_salary + $data['car_allowance']);

        $currency_code = !empty($user) ?  getCurrencyCodeForSalary($user) :  "PKR";
        return view('admin.salary.salary-slip', compact('title', 'data', 'currency_code'));
    }

    public function getTeamMembers($user_id = null)
    {
        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        } else {
            $user = User::findOrFail(Auth::user()->id);
        }

        // $team_members = [];
        // $department_ids = [];

        // if ($user->hasRole('Admin')) {
        //     $dep_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
        //     if (!empty($dep_ids)) {
        //         $department_ids = array_unique(array_merge($department_ids, $dep_ids));
        //     }
        // } else if ($user->hasRole('Department Manager')) {
        //     $manager_depts = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
        //     $department_ids = array_unique(array_merge($department_ids, $manager_depts));
        //     $child_departments = Department::whereIn('parent_department_id', $manager_depts)->where('status', 1)->pluck('id')->toArray();
        //     if (!empty($child_departments) && count($child_departments) > 0) {
        //         $department_ids = array_unique(array_merge($department_ids, $child_departments));
        //     }
        // } else {
        //     if (!empty($user->departmentBridge->department_id)) {
        //         $department_ids[] = $user->departmentBridge->department_id;
        //     }
        // }

        // $team_member_ids = DepartmentUser::whereIn('department_id', $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
        // $team_members = User::where('id', '!=', $user->id)->whereIn('id', $team_member_ids)->where('is_employee', 1)->where('status', 1)->get();

        $team_members = getTeamMembers($user);
        return (string) view('admin.employees.team-members', compact('team_members', 'user_id'));
    }

    public function teamSummary($user_id)
    {
        $user = User::findOrFail($user_id);

        $department = Department::where('manager_id', $user->id)->where('status', 1)->first();
        $department_users = DepartmentUser::where('department_id', $department->id)->where('end_date', null)->get(['user_id']);
        $employees_check_ins = [];
        $absent_employees = [];
        foreach ($department_users as $department_user) {
            if ($department_user->user_id != $user->id) {
                $dept_user = User::where('id', $department_user->user_id)->first();
                $shift = WorkingShiftUser::where('user_id', $department_user->user_id)->where('start_date', '<=', today()->format('Y-m-d'))->orderBy('id', 'desc')->first();

                if (empty($shift)) {
                    $shift = $dept_user->departmentBridge->department->departmentWorkShift->workShift;
                } else {
                    $shift = $shift->workShift;
                }

                $current_date = date('Y-m-d');
                $next_date = date("Y-m-d", strtotime('+1 day ' . $current_date));
                $start_time = date("Y-m-d h:i A", strtotime($current_date . ' ' . $shift->start_time));
                $end_time = date("Y-m-d h:i A", strtotime($next_date . ' ' . $shift->end_time));

                $start_time = date("Y-m-d h:i A", strtotime($current_date . ' ' . $shift->start_time));
                $end_time = date("Y-m-d h:i A", strtotime($next_date . ' ' . $shift->end_time));

                $shift_start_time = date("Y-m-d h:i A", strtotime('+16 minutes ' . $start_time));
                $shift_end_time = date("Y-m-d h:i A", strtotime('-16 minutes ' . $end_time));

                $shift_start_halfday = date("Y-m-d h:i A", strtotime('+121 minutes ' . $start_time));
                $shift_end_halfday = date("Y-m-d h:i A", strtotime('-121 minutes ' . $end_time));

                $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
                $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));

                $punchIn = Attendance::where('user_id', $department_user->user_id)->where('work_shift_id', $shift->id)->whereBetween('in_date', [$start, $end])->where('behavior', 'I')->orderBy('id', 'asc')->first();
                $punchOut = Attendance::where('user_id', $department_user->user_id)->where('work_shift_id', $shift->id)->whereBetween('in_date', [$start, $end])->where('behavior', 'O')->orderBy('id', 'desc')->first();

                $label = '-';
                $type = '';
                $checkSecond = true;
                if (!empty($punchIn)) {
                    if (strtotime($punchIn->in_date) > strtotime($shift_start_time) && strtotime($punchIn->in_date) < strtotime($shift_start_halfday)) {
                        $label = '<span class="badge bg-label-late-in"> Late In</span>';
                        $type = 'lateIn';
                        $employees_check = $punchIn;
                        $employees_check_out = $punchOut;
                        $employees_check['label'] = $label;
                        $employees_check['type'] = $type;
                        $employees_check_ins[] = $employees_check;
                    } else if (strtotime($punchIn->in_date) > strtotime($shift_start_halfday)) {
                        $label = '<span class="badge bg-label-half-day"> Half Day</span>';
                        $type = 'firsthalf';
                        $checkSecond = false;
                        $employees_check = $punchIn;
                        $employees_check_out = $punchOut;
                        $employees_check['label'] = $label;
                        $employees_check['type'] = $type;
                        $employees_check_ins[] = $employees_check;
                    } else if ($type == 'regular') {
                        $label = '<span class="badge bg-label-regular">Regular</span>';
                        $type = 'regular';
                        $employees_check = $punchIn;
                        $employees_check_out = $punchOut;
                        $employees_check['label'] = $label;
                        $employees_check['type'] = $type;
                        $employees_check_ins[] = $employees_check;
                    }
                }
                // if(!empty($punchOut)){
                //     $punchOutRecord=new DateTime($punchOut->in_date);
                //     $checkOut=$punchOutRecord->format('h:i A');
                //     if($checkSecond && (strtotime($punchOut->in_date) < strtotime($shift_end_time) && strtotime($punchOut->in_date) > strtotime($shift_end_halfday))){
                //         $label='<span class="badge bg-label-warning"><i class="far fa-dot-circle text-danger"></i> Early Out</span>';
                //         $type='earlyout';
                //         $employees_check = $punchIn;
                //         $employees_check_out = $punchOut;
                //         $employees_check['label'] = $label;
                //         $employees_check['type'] = $type;
                //         $employees_check_ins[] = $employees_check;
                //     }else if(strtotime($punchOut->in_date) < strtotime($shift_end_halfday)){
                //         $label='<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i>Last Half-Day</span>';
                //         $type='lasthalf';

                //         $employees_check = $punchIn;
                //         $employees_check_out = $punchOut;
                //         $employees_check['label'] = $label;
                //         $employees_check['type'] = $type;
                //         $employees_check_ins[] = $employees_check;
                //     }
                // }

                if ((empty($punchIn)) && strtotime($end_time) <= strtotime(date('Y-m-d h:i A'))) {
                    $label = '<span class="badge bg-label-full-day"> Absent</span>';
                    $type = 'absent';
                    $employees_check[] = $dept_user;
                    $employees_check['label'] = $label;
                    $employees_check['type'] = $type;
                    $employees_check_ins[] = $employees_check;
                }
            }
        }

        return view('admin.employees.team-summary', compact('employees_check_ins'));
    }

    public function teamMembers(Request $request)
    {

        $this->authorize('team_members-list');
        $title = 'Team Members';
        $login_user = Auth::user();

        // $user = User::where('slug', $login_user->slug)->first();
        // $data = [];

        // $employee_ids = [];

        // $user_department = Department::where('manager_id', $login_user->id)->where('status', 1)->first();
        // $departments = Department::where('parent_department_id', $user_department->id)->where('status', 1)->get();
        // foreach ($departments as $department_manager) {
        //     $user = User::where('id', $department_manager->manager_id)->where('is_employee', 1)->where('status', 1)->first();
        //     if (!empty($user)) {
        //         $employee_ids[] = $user->id;
        //     }
        // }

        // $model = User::whereIn('id', $employee_ids)->where('is_employee', 1)->where('status', 1)->get();
        // $team_members = [];
        // $department_ids = [];

        // if ($user->hasRole('Admin')) {
        //     $dep_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
        //     if (!empty($dep_ids)) {
        //         $department_ids = array_unique(array_merge($department_ids, $dep_ids));
        //     }
        // } else if ($user->hasRole('Department Manager')) {
        //     $manager_depts = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
        //     $department_ids = array_unique(array_merge($department_ids, $manager_depts));
        //     $child_departments = Department::whereIn('parent_department_id', $manager_depts)->where('status', 1)->pluck('id')->toArray();
        //     if (!empty($child_departments) && count($child_departments) > 0) {
        //         $department_ids = array_unique(array_merge($department_ids, $child_departments));
        //     }
        // } else {
        //     if (!empty($user->departmentBridge->department_id)) {
        //         $department_ids[] = $user->departmentBridge->department_id;
        //     }
        // }

        // $team_member_ids = DepartmentUser::whereIn('department_id', $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
        // $model = User::where('id', '!=', $user->id)->whereIn('id', $team_member_ids)->where('is_employee', 1)->where('status', 1)->get();

        $model = getTeamMembers($login_user);
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->addColumn('department', function ($model) {
                    if (Auth::user()->hasRole('Admin')) {
                        if (isset($model->hasManagerDepartment) && !empty($model->hasManagerDepartment->name)) {
                            return '<span class="text-primary fw-semibold">' . $model->hasManagerDepartment->name . '</span>';
                        } else {
                            return '-';
                        }
                    } else {
                        if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                            return '<span class="text-primary fw-semibold">' . $model->departmentBridge->department->name . '</span>';
                        } else {
                            return '-';
                        }
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return '<span class="fw-semibold">' . $model->userWorkingShift->workShift->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.team-members-action', ['employee' => $model])->render();
                })
                ->rawColumns(['status', 'first_name', 'role', 'department', 'shift', 'action'])
                ->make(true);
        }

        return view('admin.employees.team-members-list', compact('title'));
    }


    public function managerTeamMembers(Request $request)
    {
        $this->authorize('manager_team_member-list');
        $title = 'Team Members';
        $login_user = Auth::user();

        // $data = [];
        // $employee_ids = [];
        // $dept_ids = [];

        // if ($login_user->hasRole('Department Manager')) {
        //     $department = Department::where('manager_id', $login_user->id)->first();
        //     if (isset($department) && !empty($department->id)) {
        //         $department_id = $department->id;
        //         $department_manager = $department->manager;

        //         $dept_ids[] = $department->id;
        //         $sub_dep = Department::where('parent_department_id', $department->id)->where('manager_id', Auth::user()->id)->first();
        //         if (!empty($sub_dep)) {
        //             $dept_ids[] = $sub_dep->id;
        //         } else {
        //             $sub_deps = Department::where('parent_department_id', $department->id)->get();
        //             if (!empty($sub_deps) && count($sub_deps)) {
        //                 foreach ($sub_deps as $sub_dept) {
        //                     $dept_ids[] = $sub_dept->id;
        //                 }
        //             }
        //         }
        //     }
        //     $department_users = DepartmentUser::orderby('id', 'desc')->whereIn('department_id',  $dept_ids)->where('end_date', null)->get();
        //     foreach ($department_users as $department_user) {
        //         $user = User::where('id', '!=', Auth::user()->id)->where('id', $department_user->user_id)->where('is_employee', 1)->where('status', 1)->first();
        //         if (!empty($user)) {
        //             $employee_ids[] = $user->id;
        //         }
        //     }
        // }

        // $model = User::whereIn('id', $employee_ids)->where('is_employee', 1)->where('status', 1)->get();

        $model = getTeamMembers($login_user);

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->addColumn('department', function ($model) {
                    if (Auth::user()->hasRole('Admin')) {
                        if (isset($model->hasManagerDepartment) && !empty($model->hasManagerDepartment->name)) {
                            return '<span class="text-primary fw-semibold">' . $model->hasManagerDepartment->name . '</span>';
                        } else {
                            return '-';
                        }
                    } else {
                        if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                            return '<span class="text-primary fw-semibold">' . $model->departmentBridge->department->name . '</span>';
                        } else {
                            return '-';
                        }
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return '<span class="fw-semibold">' . $model->userWorkingShift->workShift->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.team-members-action', ['employee' => $model])->render();
                })
                ->rawColumns(['status', 'first_name', 'role', 'department', 'shift', 'action'])
                ->make(true);
        }

        return view('admin.employees.manager-team-members-list', compact('title'));
    }

    public function userDirectPermissionEdit($slug)
    {
        $user = User::where('slug', $slug)->first();
        $user_permissions = $user->getPermissionNames();
        $models = Permission::orderby('id', 'DESC')->groupBy('label')->get();

        return (string) view('admin.employees.edit-direct-permission', compact('user', 'models', 'user_permissions'));
    }

    public function userDirectPermissionUpdate(Request $request, $user_slug)
    {
        DB::beginTransaction();

        try {
            $user = User::where('slug', $user_slug)->first();

            $user->syncPermissions($request->input('permissions'));

            DB::commit();

            \LogActivity::addToLog('Direct Permission assigned');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getUserDetails(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user_details = '';
        if (isset($user->hasPreEmployee) && !empty($user->hasPreEmployee)) {
            $user_details = $user->hasPreEmployee;
        } else if (isset($user->profile) && !empty($user->profile)) {
            $user_details = $user->profile;
        }

        return $user_details;
    }

    public function reHire(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $domain = config("project.domain");
        $resignation = Resignation::orderby('id', 'desc')->where('status', 2)->where('employee_id', $user->id)->first(); //2=terminated approved by admin.
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'email' => 'required|max:255|ends_with:' . $domain . '|unique:users,id,' . $user->id,
            'employment_status_id' => [
                'required',
                'not_in:3',
            ],
            'designation_id' => 'required',
            'department_id' => 'required',
            'role_ids' => 'required',
            'role_ids*' => 'required',
            'joining_date' => 'required',
            'work_shift_id' => 'required',
            'employment_id' => 'max:200',
            'salary' => 'max:255',
        ]);

        DB::beginTransaction();
        $message = "";
        try {
            $resignation->employment_status_id = $request->employment_status_id;
            $resignation->is_rehired = 1;
            $resignation->save();

            // cPanel API credentials
            $cpanelUsername = config("project.cpanelUsername");
            $cpanelToken = config("project.cpanelToken");
            $cpanelDomain =  config("project.cpanelDomain");


            // Email account details
            $user_email = $request->email;
            // $user_password = Str::random(8);
            $user_password =  config("project.defaultPassword");

            if (getAppMode() == "live") {
                $checkWebMail = checkWebMail($request->email);
                if (isset($checkWebMail) && $checkWebMail['success'] == false) {
                    $create_email_response = $this->createEmailAccount($cpanelUsername, $cpanelToken, $cpanelDomain, $user_email, $user_password);

                    if (isset($create_email_response) && !empty($create_email_response) && $create_email_response == 'failed') {
                        $message .= 'Email: ' .  $user_email  . ' already exist on Web Mail!';
                    } else {
                    }
                }
                $message .= 'Email: ' .  $user_email  . ' already exist on Web Mail!';
            }

            $user->created_by = Auth::user()->id;
            $user->status = 1;
            $user->is_employee = 1;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;

            if ($user->email != $request->email) {
                $user->email = $user_email;
                $user->password = Hash::make($user_password);
            }

            $user->save();
            $user->syncRoles($request->role_ids);

            if ($user) {
                Profile::where('user_id', $user->id)->update([
                    'employment_id' => $request->employment_id,
                    'joining_date' => $request->joining_date,
                    'gender' => $request->gender,
                    'phone_number' => $request->phone_number,
                ]);

                $job_history = JobHistory::create([
                    'created_by' => Auth::user()->id,
                    'user_id' => $user->id,
                    'designation_id' => $request->designation_id,
                    'employment_status_id' => $request->employment_status_id,
                    'joining_date' => $request->joining_date,
                ]);

                if (!empty($request->department_id)) {
                    DepartmentUser::create([
                        'department_id' => $request->department_id,
                        'user_id' => $user->id,
                        'start_date' => $request->joining_date,
                    ]);
                }

                if ($job_history && !empty($request->salary)) {
                    SalaryHistory::create([
                        'created_by' => Auth::user()->id,
                        'user_id' => $user->id,
                        'job_history_id' => $user->jobHistory->id,
                        'salary' => $request->salary,
                        'effective_date' => $request->joining_date,
                        'status' => 1,
                    ]);
                }

                UserEmploymentStatus::create([
                    'user_id' => $user->id,
                    'employment_status_id' => $request->employment_status_id,
                    'start_date' => $request->joining_date,
                ]);

                WorkingShiftUser::create([
                    'user_id' => $user->id,
                    'working_shift_id' => $request->work_shift_id,
                    'start_date' => $request->joining_date,
                ]);
                HiringHistory::create([
                    "user_id" => $user->id,
                    "created_by" => getUser()->id,
                    "date" => $request->joining_date,
                    "type" => 2, //1 for resign , 2 for rehire
                    "employee_status" =>   $request->employment_status_id ?? null,
                    "remarks" => "Rehired - " . getUserName($user),
                ]);
                DB::commit();
            }

            //send email with password.
            $model = $user;
            if (getAppMode() == "live") {
                //Employee portal credentials mail
                $employee_info = [
                    'name' => $model->first_name . ' ' . $model->last_name,
                    'email' => $model->email,
                    'password' => $user_password,
                ];


                $mailData = [
                    'from' => 'welcome',
                    'title' => 'Welcome to Our Team - Important Onboarding Information',
                    'employee_info' => $employee_info,
                ];

                Mail::to($user_email)->send(new Email($mailData));
            }
            //Joining Email to departments
            $manager_name = '';
            if (isset($model->departmentBridge->department->manager) && !empty($model->departmentBridge->department->manager->first_name)) {
                $manager_name = $model->departmentBridge->department->manager->first_name;
            }

            $designation_name = '';
            if (isset($model->jobHistory->designation) && !empty($model->jobHistory->designation->title)) {
                $designation_name = $model->jobHistory->designation->title;
            }
            $department_name = '';
            if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department->name)) {
                $department_name = $model->departmentBridge->department->name;
            }
            $work_shift_name = '';
            if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                $work_shift_name = $model->userWorkingShift->workShift->name;
            }
            $joining_date = '';
            if (isset($model->profile) && !empty($model->profile->joining_date)) {
                $joining_date = date('d M Y', strtotime($model->profile->joining_date));
            }
            if (getAppMode() == "live") {
                $employee_info = [
                    'name' => $model->first_name . ' ' . $model->last_name,
                    'email' => $model->email,
                    'password' => $user_password,
                    'manager' => $manager_name,
                    'designation' => $designation_name,
                    'department' => $department_name,
                    'shift_time' => $work_shift_name,
                    'joining_date' => $joining_date,
                ];
                $mailData = [
                    'from' => 'employee_info',
                    'title' => 'Employee Approval and Joining Information',
                    'employee_info' => $employee_info,
                ];
                if (!empty(sendEmailTo($model, 'new_employee_info')) && !empty(sendEmailTo($model, 'new_employee_info')['cc_emails'])) {
                    $to_emails = sendEmailTo($model, 'new_employee_info')['to_emails'];
                    $cc_emails = sendEmailTo($model, 'new_employee_info')['cc_emails'];
                    Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
                } elseif (!empty(sendEmailTo($model, 'new_employee_info')['to_emails'])) {
                    $to_emails = sendEmailTo($model, 'new_employee_info')['to_emails'];
                    Mail::to($to_emails)->send(new Email($mailData));
                }
            }
            //send email with password.
            $message .= " Employee has been rehired Successfuly!";
            \LogActivity::addToLog('Employee re-hired');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }



    public function getCurrencyRate(Request $request)
    {
        $conversionCode = "PKR";
        if (!empty($request->code) && !empty($request->salary)) {
            $baseCode = $request->code;
            $data = currencyConverter($baseCode, $conversionCode, $request->salary);
            return $data;
        }
    }
}
