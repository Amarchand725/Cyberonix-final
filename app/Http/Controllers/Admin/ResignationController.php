<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Carbon\Carbon;
use App\Mail\Email;
use App\Models\User;
use App\Models\AssetUser;
use App\Models\WorkShift;
use App\Models\Department;
use App\Models\JobHistory;
use App\Models\Designation;
use App\Models\Resignation;
use App\Models\VehicleUser;
use App\Helpers\LogActivity;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use App\Models\HiringHistory;
use App\Models\SalaryHistory;
use App\Models\AuthorizeEmail;
use App\Models\DepartmentUser;
use App\Models\AssetUserHistory;
use App\Models\EmploymentStatus;
use App\Models\WorkingShiftUser;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\UserEmploymentStatus;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ImportantNotification;
use App\Notifications\ImportantNotificationWithMail;

class ResignationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('resignations-list');
        $data = [];
        $title = 'My Resignations';

        $user = Auth::user();
        $data['employment_status'] = EmploymentStatus::where('name', 'Resign')->first();

        $model = [];
        Resignation::where('employee_id', $user->id)
            ->where('is_rehired', 0)
            ->latest()
            ->chunk(100, function ($resignations) use (&$model) {
                foreach ($resignations as $resignation) {
                    $model[] = $resignation;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Rejected">Rejected</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('subject', function ($model) {
                    $subject = '-';
                    if (!empty($model->subject)) {
                        $subject = $model->subject;
                    }

                    return $subject;
                })
                ->editColumn('resignation_date', function ($model) {
                    return '<span class="text-primary fw-semibold">' . Carbon::parse($model->resignation_date)->format('d, M Y') . '</span>';
                })
                ->editColumn('last_working_date', function ($model) {
                    return Carbon::parse($model->last_working_date)->format('d, M Y');
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.resignations.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('notice_period', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->notice_period . '</span>';
                })
                ->editColumn('employment_status_id', function ($model) {
                    $label = '';
                    if (isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus)) {
                        $label = '<span class="badge bg-label-' . $model->hasEmploymentStatus->class . '">' .
                            $model->hasEmploymentStatus->name .
                            '</span>';
                    }
                    return $label;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.resignations.action', ['data' => $model])->render();
                })
                ->rawColumns(['employee_id', 'employment_status_id', 'status', 'resignation_date', 'notice_period', 'action'])
                ->make(true);
        }

        return view('admin.resignations.index', compact('title', 'user', 'data'));
    }

    public function teamResignations(Request $request)
    {
        $this->authorize('team_resignations-list');
        $data = [];
        $title = 'All Team Resignations';

        $user = Auth::user();

        $employees_ids = [];
        $department_ids = [];
        $model = [];

        $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
        $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
        $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
        if (!empty($child_departments) && count($child_departments) > 0) {
            $department_ids = array_unique(array_merge($department_ids, $child_departments));
        }

        $employees_ids = DepartmentUser::orderby('id', 'desc')->whereIn('department_id',  $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
        Resignation::whereIn('employee_id', $employees_ids)
            ->where('is_rehired', 0)
            ->latest()
            ->chunk(100, function ($resignations) use (&$model) {
                foreach ($resignations as $resignation) {
                    $model[] = $resignation;
                }
            });
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Rejected">Rejected</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('resignation_date', function ($model) {
                    return '<span class="text-primary fw-semibold">' . Carbon::parse($model->resignation_date)->format('d, M Y') . '</span>';
                })
                ->editColumn('last_working_date', function ($model) {
                    return Carbon::parse($model->last_working_date)->format('d, M Y');
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.resignations.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('notice_period', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->notice_period . '</span>';
                })
                ->editColumn('employment_status_id', function ($model) {
                    $label = '';
                    if (isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus)) {
                        $label = '<span class="badge bg-label-' . $model->hasEmploymentStatus->class . '">' .
                            $model->hasEmploymentStatus->name .
                            '</span>';
                    }
                    return $label;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.resignations.action', ['data' => $model])->render();
                })
                ->rawColumns(['employee_id', 'employment_status_id', 'status', 'resignation_date', 'notice_period', 'action'])
                ->make(true);
        }

        return view('admin.resignations.team_resignations', compact('title', 'user', 'data'));
    }

    public function employeeResignations(Request $request)
    {
        $this->authorize('employee_resignations-list');
        $data = [];
        $title = 'All Resignations';

        $user = Auth::user();

        $model = [];
        Resignation::where('employee_id', '!=', $user->id)
            ->where('is_rehired', 0)
            ->latest()
            ->chunk(100, function ($resignations) use (&$model) {
                foreach ($resignations as $resignation) {
                    $model[] = $resignation;
                }
            });
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Rejected">Rejected</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('resignation_date', function ($model) {
                    return '<span class="text-primary fw-semibold">' . Carbon::parse($model->resignation_date)->format('d, M Y') . '</span>';
                })
                ->editColumn('last_working_date', function ($model) {
                    return Carbon::parse($model->last_working_date)->format('d, M Y');
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.resignations.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('notice_period', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->notice_period . '</span>';
                })
                ->editColumn('employment_status_id', function ($model) {
                    $label = '';
                    if (isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus)) {
                        $label = '<span class="badge bg-label-' . $model->hasEmploymentStatus->class . '">' .
                            $model->hasEmploymentStatus->name .
                            '</span>';
                    }
                    return $label;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.resignations.action', ['data' => $model])->render();
                })
                ->rawColumns(['employee_id', 'employment_status_id', 'status', 'resignation_date', 'notice_period', 'action'])
                ->make(true);
        }

        return view('admin.resignations.employee_resignations', compact('title', 'user', 'data'));
    }

    public function reHiredEmployees(Request $request)
    {
        $this->authorize('employee_rehire-list');
        $data = [];
        $title = 'All Re-Hired Employees';
        $rehired_page = 're-hired';

        $user = Auth::user();

        // $user = $logined_user;

        // $employees_ids = [];

        if ($user->hasRole('Admin')) {
            // $employees_ids = DepartmentUser::where('end_date',  NULL)->pluck('user_id')->toArray();

            // foreach ($department_users as $department_user) {
            //     $emp_data = User::where('id', $department_user->user_id)->first(['id', 'first_name', 'last_name', 'slug']);
            //     if (!empty($emp_data)  && $emp_data->id != Auth::user()->id) {
            //         $employees_ids[] = $emp_data->id;
            //     }
            // }

            $model = Resignation::where('is_rehired', 1)->latest()->get();

            // $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
            // $data['roles'] = Role::orderby('id', 'desc')->get();
            // $data['departments'] = Department::orderby('id', 'desc')->has('departmentWorkShift')->has('manager')->where('status', 1)->get();
            // $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
            // $data['work_shifts'] = WorkShift::where('status', 1)->get();
        } elseif ($user->hasRole('Department Manager')) {
            // $emp_statuses = ['Terminated', 'Retirements'];
            // $data['employment_statues'] = EmploymentStatus::whereIn('name', $emp_statuses)->get();

            // $department = Department::where('manager_id', $logined_user->id)->first();
            // if (isset($department) && !empty($department->id)) {
            //     $department_id = $department->id;
            // }
            // $department_users = DepartmentUser::where('department_id',  $department_id)->where('end_date', NULL)->get();
            // foreach ($department_users as $department_user) {
            //     $employee = User::where('id', $department_user->user_id)->first(['id', 'first_name', 'last_name', 'slug']);
            //     if (!empty($employee)) {
            //         $employees_ids[] = $employee->id;
            //     }
            // }

            // $employees_ids[] = Auth::user()->id;

            $employees_ids = getTeamMemberIds($user);
            $model = Resignation::whereIn('employee_id', $employees_ids)->where('is_rehired', 1)->latest()->get();
        }

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()

                ->editColumn('updated_at', function ($model) {
                    return Carbon::parse($model->updated_at)->format('d, M Y');
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.resignations.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('employment_status_id', function ($model) {
                    $label = '';
                    if (isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus)) {
                        $label = '<span class="badge bg-label-' . $model->hasEmploymentStatus->class . '">' .
                            $model->hasEmploymentStatus->name .
                            '</span>';
                    }
                    return $label;
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '';

                    if (isset($model->hasEmployee) && !empty($model->hasEmployee->status)) {
                        if ($model->hasEmployee->status) {
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Active">Active</span>';
                        } else {
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="De-active">De-active</span>';
                        }
                    }

                    return $label;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.resignations.action', ['data' => $model])->render();
                })
                ->rawColumns(['employee_id', 'employment_status_id', 'emp_status', 'action'])
                ->make(true);
        }

        return view('admin.resignations.rehired-employees', compact('title', 'user', 'rehired_page'));
    }
    public function hiringHistory(Request $request)
    {
        $this->authorize('employee_rehire_history-list');
        $title = 'Hiring History';
        $rehired_page = 're-hired';
        $user = getUser();

        $model = HiringHistory::latest()->get();
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('performer', function ($model) {
                    return  !empty($model->createdBy) ?  userWithHtml($model->createdBy) : "-";
                })
                ->addColumn('employee', function ($model) {
                    return !empty($model->user)  ?  userWithHtml($model->user) : "-";
                })
                ->addColumn('date', function ($model) {
                    return Carbon::parse($model->date)->format('d, M Y');
                })
                ->addColumn('type', function ($model) {
                    $label = '';
                    if ($model->type == 1) {
                        $label = '<span class="badge bg-label-danger">Terminated</span>';
                    } elseif ($model->type == 2) {
                        $label = '<span class="badge bg-label-success">Re-Hired</span>';
                    }
                    return $label;
                })

                ->addColumn('status', function ($model) {
                    $label = '';
                    $class = !empty($model->employeyStatus->class) ? $model->employeyStatus->class : "-";
                    $status = !empty($model->employeyStatus->name) ? $model->employeyStatus->name : "-";
                    $label = '<span class="badge bg-label-' . $class . '">' . $status . '</span>';
                    return $label;
                })
                ->addColumn('action_date', function ($model) {
                    return formatDate($model->created_at);
                })
                // ->addColumn('action', function ($model) {
                // })
                ->rawColumns(['performer', 'employee', 'type',  'date', 'status',  'action_date'])
                ->make(true);
        }

        return view('admin.resignations.hiringHistory', compact('title', 'user', 'rehired_page'));
    }

    public function adminReHiredEmployees(Request $request)
    {
        $this->authorize('admin_employee_re_hire-list');
        $title = 'All Re-Hired Employees';
        $rehired_page = 're-hired';

        $user = Auth::user();

        // $employees_ids = [];

        // $department_users = DepartmentUser::where('end_date',  NULL)->get();

        // foreach ($department_users as $department_user) {
        //     $emp_data = User::where('id', $department_user->user_id)->first(['id', 'first_name', 'last_name', 'slug']);
        //     if (!empty($emp_data)  && $emp_data->id != Auth::user()->id) {
        //         $employees_ids[] = $emp_data->id;
        //     }
        // }

        $model = Resignation::where('is_rehired', 1)->latest()->get();

        // $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        // $data['roles'] = Role::orderby('id', 'desc')->get();
        // $data['departments'] = Department::orderby('id', 'desc')->has('departmentWorkShift')->has('manager')->where('status', 1)->get();
        // $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
        // $data['work_shifts'] = WorkShift::where('status', 1)->get();


        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()

                ->editColumn('updated_at', function ($model) {
                    return Carbon::parse($model->updated_at)->format('d, M Y');
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.resignations.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('employment_status_id', function ($model) {
                    $label = '';
                    if (isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus)) {
                        $label = '<span class="badge bg-label-' . $model->hasEmploymentStatus->class . '">' .
                            $model->hasEmploymentStatus->name .
                            '</span>';
                    }
                    return $label;
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '';

                    if (isset($model->hasEmployee) && !empty($model->hasEmployee->status)) {
                        if ($model->hasEmployee->status) {
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Active">Active</span>';
                        } else {
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="De-active">De-active</span>';
                        }
                    }

                    return $label;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.resignations.action', ['data' => $model])->render();
                })
                ->rawColumns(['employee_id', 'employment_status_id', 'emp_status', 'action'])
                ->make(true);
        }

        return view('admin.resignations.admin-rehired-employees', compact('title', 'user', 'rehired_page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (isset($request->from) && $request->from == 'termination') {
            $this->validate($request, [
                'employment_status' => 'required',
                'notice_period' => 'required',
                'resignation_date' => 'required',
                'reason_for_resignation' => 'max:500',
            ]);
        } else {
            $this->validate($request, [
                'subject' => 'required',
                'resignation_date' => 'required',
                'reason_for_resignation' => 'max:500',
            ]);
        }

        DB::beginTransaction();

        try {
            $updatedDataArrayHirtory = [];
            if (Auth::user()->hasRole('Admin') && isset($request->user_id)) {
                // Assuming $request->resignation_date is a valid date in a format Carbon can parse
                $resignationDate = Carbon::parse($request->resignation_date);

                // Add one month to the date
                if ($request->notice_period == 'Immediately') {
                    $last_working_date = $resignationDate;
                } elseif ($request->notice_period == 'One Week') {
                    $last_working_date = $resignationDate->addWeek();
                } else {
                    $last_working_date = $resignationDate->addMonth();
                }
                $employee  = !empty($request->user_id) ? getUser($request->user_id) : "";
                $employeeStatus = !empty($employee) ? $employee->employeeStatus->employmentStatus->id : null;
                $ifexist = Resignation::where('employee_id', $request->user_id)->latest()->first();
                if (!empty($ifexist)) {
                    $ifexist->employment_status_id = $request->employment_status;
                    $ifexist->save();
                }

                $subject = '';
                if (isset($request->subject)) {
                    $subject = $request->subject;
                }
                //testing

                $resignation = Resignation::create([
                    'created_by' => Auth::user()->id,
                    'is_manager_approved' => now(),
                    'is_concerned_approved' => now(),
                    'employee_id' => $request->user_id,
                    'employment_status_id' => $request->employment_status,
                    'subject' => $subject,
                    'resignation_date' => $request->resignation_date,
                    'reason_for_resignation' => $request->reason_for_resignation,
                    'notice_period' => $request->notice_period,
                    'last_working_date' => date('Y-m-d', strtotime($last_working_date)),
                    'status' => 2,
                    'comment' => 'Terminated by admin',
                ]);

                if ($resignation) {


                    $newResignationRelationArray = [
                        'employment_status' => $resignation->hasEmploymentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $resignation, 'resignation', 'create', null, $newResignationRelationArray);

                    //employee
                    $model = User::where('id', $request->user_id)->first();



                    $login_user = Auth::user();
                    $notification_data = [
                        'id' => $resignation->id,
                        'date' => $request->resignation_date,
                        'type' => $resignation->hasEmploymentStatus->name,
                        'name' => $login_user->first_name . ' ' . $login_user->last_name,
                        'profile' => $login_user->profile->profile,
                        'title' => 'You have new update about ' . $resignation->hasEmploymentStatus->name,
                        'reason' => $resignation->reason_for_resignation,
                    ];

                    if (isset($notification_data) && !empty($notification_data)) {
                        if (getAppMode() == "live") {
                            $model->notify(new ImportantNotification($notification_data));

                            if ($model->hasRole('Department Manager')) {
                                $parent_department = Department::where('manager_id', $model->id)->first();
                                $manager = $parent_department->parentDepartment->manager;
                            } else {
                                $manager = $model->departmentBridge->department->manager;
                            }
                            if (!empty($manager)) {
                                $manager->notify(new ImportantNotification($notification_data));
                            }
                        }
                    }

                    \LogActivity::addToLog('New Termination Added by admin');

                    if ($request->notice_period == 'Immediately') {
                        //close job employment status
                        $user_emp_status = UserEmploymentStatus::orderby('id', 'desc')->where('user_id', $model->id)->where('end_date', null)->first();

                        if(isset($user_emp_status) && !empty($user_emp_status)) {
                            $oldUserEmploymentStatusData = $user_emp_status->getOriginal();
                            $oldUserEmploymentStatusRelationArray = [
                                'employment_status' => $user_emp_status->employmentStatus->name ?? null,
                            ];

                            $user_emp_status->employment_status_id = $resignation->employment_status_id;
                            $user_emp_status->end_date = $resignation->last_working_date;
                            $user_emp_status->save();

                            $user_emp_status->refresh();
                            $newUserEmploymentStatusRelationArray = [
                                'employment_status' => $user_emp_status->employmentStatus->name ?? null,
                            ];
                            $updatedDataArrayHirtory[] = getUpdatedData($oldUserEmploymentStatusData, $user_emp_status, 'user_employment_status', null, $oldUserEmploymentStatusRelationArray, $newUserEmploymentStatusRelationArray, ['start_date' => $user_emp_status->start_date]);
                        }

                        //close job history
                        $job_history = JobHistory::orderby('id', 'desc')->where('user_id', $model->id)->where('end_date', null)->first();
                        if(isset($job_history) && !empty($job_history)) {
                            $oldJobHistoryData = $job_history->getOriginal();
                            
                            $oldJobRelationArray = [
                                'designation' => $job_history->designation->title ?? null,
                                'employment_status' => $job_history->userEmploymentStatus->employmentStatus->name ?? null,
                            ];

                            $job_history->end_date = $resignation->last_working_date;
                            $job_history->employment_status_id = $request->employment_status;
                            $job_history->save();
                            $job_history->refresh();
                            
                            $newJobRelationArray = [
                                'designation' => $job_history->designation->title ?? null,
                                'employment_status' => $job_history->userEmploymentStatus->employmentStatus->name ?? null,
                            ];
                            $updatedDataArrayHirtory[] = getUpdatedData($oldJobHistoryData, $job_history, 'job_history', null, $oldJobRelationArray, $newJobRelationArray, ['joining_date' => $job_history->joining_date ?? null]);
                        }

                        //close salary history
                        $salary_history = SalaryHistory::orderby('id', 'desc')->where('user_id', $model->id)->where('end_date', null)->first();
                        if (!empty($salary_history)) {
                            $oldSalaryHistoryData = $salary_history->getOriginal();
                            $salary_history->end_date = $resignation->last_working_date;
                            $salary_history->status = 0;
                            $salary_history->save();
                            $salary_history->refresh();
                            $updatedDataArrayHirtory[] = getUpdatedData($oldSalaryHistoryData, $salary_history, 'salary_history', null, null, null, ['effective_date' => $salary_history->effective_date ?? null]);
                        } else {
                            $createSalaryHistory = SalaryHistory::create([
                                'created_by' => Auth::user()->id,
                                'user_id' => $model->id,
                                'job_history_id' => $job_history->id,
                                'salary' => 0,
                                'effective_date' => $resignation->last_working_date,
                                'end_date' => $resignation->last_working_date,
                                'status' => 0,
                            ]);
                            $updatedDataArrayHirtory[] = getUpdatedData(null, $createSalaryHistory, 'salary_history', 'create');
                        }

                        //close DepartmentUser
                        $user_dept = DepartmentUser::orderby('id', 'desc')->where('user_id', $model->id)->where('end_date', null)->first();
                        if(isset($user_dept) && !empty($user_dept)) {
                            $oldDepartmentUserData = $user_dept->getOriginal();
                            $oldDepartmenRelationArray = [
                                'department' => $user_dept->department->name ?? null,
                            ];

                            $user_dept->end_date = $resignation->last_working_date;
                            $user_dept->save();

                            $user_dept->refresh();
                            $newDepartmenRelationArray = [
                                'department' => $user_dept->department->name ?? null,
                            ];
                            $updatedDataArrayHirtory[] = getUpdatedData($oldDepartmentUserData, $user_dept, 'user_department', null, $oldDepartmenRelationArray, $newDepartmenRelationArray, ['start_date' => $user_dept->start_date]);
                        }

                        //close DepartmentUser
                        $user_work_shift = WorkingShiftUser::orderby('id', 'desc')->where('user_id', $model->id)->first();
                        if (!empty($user_work_shift)) {
                            $oldWorkingShiftUserData = $user_work_shift->getOriginal();
                            $oldWorkingShiftUserRelationArray = [
                                'working_shift' => $user_work_shift->workShift->name ?? null,
                            ];

                            $user_work_shift->end_date = $resignation->last_working_date;
                            $user_work_shift->save();

                            $user_work_shift->refresh();
                            $newWorkingShiftUserRelationArray = [
                                'working_shift' => $user_work_shift->workShift->name ?? null,
                            ];
                            $updatedDataArrayHirtory[] = getUpdatedData($oldWorkingShiftUserData, $user_work_shift, 'working_shift_user', null, $oldWorkingShiftUserRelationArray, $newWorkingShiftUserRelationArray, ['start_date' => $user_work_shift->start_date]);
                        }

                        //close VehicleUser After clearness
                        $vehicle_user = VehicleUser::orderby('id', 'desc')->where('user_id', $model->id)->where('end_date', NULL)->first();
                        if (!empty($vehicle_user)) {
                            $vehicle_user->end_date = $resignation->last_working_date;
                            $vehicle_user->save();
                        }

                        $userAssets = AssetUser::orderBy('id', 'desc')
                            ->where('employee_id', $model->id)
                            ->where('end_date', NULL)
                            ->get();

                        if (!$userAssets->isEmpty()) {
                            foreach ($userAssets as $userAsset) {
                                $userAsset->status = 0; // De-activate assigned asset
                                $userAsset->unassigned_at = $resignation->last_working_date;
                                $userAsset->end_date = $resignation->last_working_date;
                                $userAsset->save();

                                $assetHis = AssetHistory::create([
                                    'asset_id' => $userAssets->asset->asset->id,
                                    'quantity' => 1,
                                    'created_by' => Auth::user()->id,
                                    'type' => 1, //induct
                                    'remarks' => 'Quantity Increased',
                                ]);

                                if ($assetHis) {
                                    AssetUserHistory::create([
                                        'asset_user_id' => $model->id,
                                        'asset_detail_id' => $userAssets->asset_detail_id,
                                        'employee_id' => $model->id,
                                        'creator_id' => Auth::user()->id,
                                        'date' => $resignation->last_working_date,
                                        'remarks' => 'Auto generated by system.',
                                        'type' => 2,
                                    ]);
                                }
                            }
                        }
                        //close VehicleUser After clearness

                        //de-active employee and remove from employment
                        $oldUserData = $model->getOriginal();
                        $model->status = 0; //set to deactive
                        $model->is_employee = 0; //set to deactive
                        $model->save();
                        $model->refresh();
                        $updatedDataArrayHirtory[] = getUpdatedData($oldUserData, $model, 'user');
                    }

                    DB::commit();

                    //send email.
                    // $admin_user = User::role('Admin')->first();

                    $mailData = [
                        'from' => 'termination',
                        'title' => 'Employee Termination Notification',
                        'employee' => $model->first_name . ' ' . $model->last_name,
                    ];
                    if (getAppMode() == "live") {
                        if (!empty(sendEmailTo($model, 'employee_termination')) && !empty(sendEmailTo($model, 'employee_termination')['cc_emails'])) {
                            $to_emails = sendEmailTo($model, 'employee_termination')['to_emails'];
                            $cc_emails = sendEmailTo($model, 'employee_termination')['cc_emails'];
                            Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
                        } else {
                            $to_emails = sendEmailTo($model, 'employee_termination')['to_emails'];
                            Mail::to($to_emails)->send(new Email($mailData));
                        }
                    }
                    \LogActivity::addToLog('Terminated employee');
                    // return response()->json(['success' => true]);
                    //send email.
                }
            } else {
                // Assuming $request->resignation_date is a valid date in a format Carbon can parse
                $resignationDate = Carbon::parse($request->resignation_date);

                $employee  = !empty($request->user_id) ? getUser($request->user_id) : "";
                $employeeStatus = !empty($employee) ? $employee->employeeStatus->employmentStatus->id : null;
                // Add one month to the date
                $last_working_date = $resignationDate->addMonth();

                $ifexist = Resignation::where('status', '!=', 0)->where('employee_id', Auth::user()->id)->latest()->first();
                if (!empty($ifexist)) {
                    $ifexist->employment_status_id = $request->employment_status;
                    $ifexist->save();
                }

                $model = Resignation::create([
                    'created_by' => Auth::user()->id,
                    'employee_id' => Auth::user()->id,
                    'employment_status_id' => $request->employment_status,
                    'subject' => $request->subject,
                    'resignation_date' => $request->resignation_date,
                    'reason_for_resignation' => $request->reason_for_resignation,
                    'notice_period' => 'One Month',
                    'last_working_date' => date('Y-m-d', strtotime($last_working_date)),
                ]);
                if ($model) {

                    $newResignationRelationArray = [
                        'employment_status' => $model->hasEmploymentStatus->name ?? null,
                    ];
                    $updatedDataArrayHirtory[] = getUpdatedData(null, $model, 'resignation', 'create', null, $newResignationRelationArray);

                    $login_user = Auth::user();
                    $notification_data = [
                        'id' => $model->id,
                        'date' => $request->resignation_date,
                        'type' => $model->hasEmploymentStatus->name,
                        'name' => $login_user->first_name . ' ' . $login_user->last_name,
                        'profile' => $login_user->profile->profile,
                        'title' => 'has applied for ' . $model->hasEmploymentStatus->name,
                        'reason' => $request->reason_for_resignation,
                    ];

                    if (isset($notification_data) && !empty($notification_data)) {
                        if ($login_user->hasRole('Department Manager')) {
                            $parent_department = Department::where('manager_id', $login_user->id)->first();
                            $manager = $parent_department->parentDepartment->manager;
                        } else {
                            $manager = $login_user->departmentBridge->department->manager;
                        }
                        if (!empty($manager)) {
                            $manager->notify(new ImportantNotificationWithMail($notification_data));
                        }
                    }

                    \LogActivity::addToLog('New Resignation Added');
                    DB::commit();
                }

                // return response()->json(['success' => true]);
            }



            $saveHiring = HiringHistory::create([
                "user_id" => $request->user_id,
                "created_by" => getUser()->id,
                "date" => $request->resignation_date,
                "type" => 1, //1 for resign , 2 for rehire
                "remarks" => $request->reason_for_resignation ?? null,
                "employee_status" =>  $request->employment_status ?? null,
            ]);

            if(isset($saveHiring) && !empty($saveHiring)) {
                $newHiringHistoryRelationArray = [
                    'employment_status' => $saveHiring->employeyStatus->name ?? null,
                ];
                $updatedDataArrayHirtory[] = getUpdatedData(null, $saveHiring, 'hiring_history', 'create', null, $newHiringHistoryRelationArray);
                
                if(isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
                    saveLogs($updatedDataArrayHirtory, "User", $model->id, 4, "Employee", 8);
                }

                return response()->json(['success' => true]);
            }




        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($model_id)
    {
        $model = Resignation::findOrFail($model_id);
        return (string) view('admin.resignations.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
    {
        $this->authorize('resignations-edit');
        // $user = Auth::user();

        // if ($user->hasRole('Admin') || $user->hasRole('Department Manager')) {
        //     $emp_statuses = ['Terminated', 'Retirements'];
        //     $employment_statues = EmploymentStatus::whereIn('name', $emp_statuses)->get();
        // } else {
        //     $emp_statuses = ['Retirements'];
        //     $employment_statues = EmploymentStatus::whereIn('name', $emp_statuses)->get();
        // }

        $model = Resignation::where('id', $id)->first();
        return (string) view('admin.resignations.edit_content', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'subject' => 'required',
            'resignation_date' => 'required',
            'reason_for_resignation' => 'max:500',
        ]);

        DB::beginTransaction();

        try {
            $model = Resignation::where('id', $id)->first();

            // Assuming $request->resignation_date is a valid date in a format Carbon can parse
            $resignationDate = Carbon::parse($request->resignation_date);

            // Add one month to the date
            $last_working_date = $resignationDate->addMonth();

            $model->created_by = Auth::user()->id;
            $model->employee_id = Auth::user()->id;
            $model->subject = $request->subject;
            $model->resignation_date = $request->resignation_date;
            $model->last_working_date = date('Y-m-d', strtotime($last_working_date));
            $model->reason_for_resignation = $request->reason_for_resignation;
            $model->save();

            if ($model) {
                $login_user = Auth::user();
                $notification_data = [
                    'id' => $model->id,
                    'date' => $request->resignation_date,
                    'type' => $model->hasEmploymentStatus->name,
                    'name' => $login_user->first_name . ' ' . $login_user->last_name,
                    'profile' => $login_user->profile->profile,
                    'title' => 'has updated request for ' . $model->hasEmploymentStatus->name,
                    'reason' => $request->reason_for_resignation,
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    if ($login_user->hasRole('Department Manager')) {
                        $parent_department = Department::where('manager_id', $login_user->id)->first();
                        $manager = $parent_department->parentDepartment->manager;
                    } else {
                        $manager = $login_user->departmentBridge->department->manager;
                    }
                    $manager->notify(new ImportantNotificationWithMail($notification_data));
                }

                \LogActivity::addToLog('Resignation Updated');
                DB::commit();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('resignations-delete');
        $find = Resignation::where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Resignation";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Resignation has been deleted";
            $model = $find->delete();
            if ($model) {
                LogActivity::addToLog('Resignation Deleted');
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
        $title = 'All Trashed Resignations';
        $temp = 'All Trashed Resignations';
        $user = Auth::user();

        $model = Resignation::onlyTrashed()->where('employee_id', Auth::user()->id)->orderby('id', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Rejected">Rejected</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('resignation_date', function ($model) {
                    return Carbon::parse($model->resignation_date)->format('d, M Y');
                })
                ->editColumn('last_working_date', function ($model) {
                    return Carbon::parse($model->last_working_date)->format('d, M Y');
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.resignations.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('employment_status_id', function ($model) {
                    $label = '';
                    if (isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus)) {
                        $label = '<span class="badge bg-label-' . $model->hasEmploymentStatus->class . '">' .
                            $model->hasEmploymentStatus->name .
                            '</span>';
                    }
                    return $label;
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="' . route('resignations.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['employee_id', 'status', 'employment_status_id', 'action'])
                ->make(true);
        }

        return view('admin.resignations.index', compact('title', 'user', 'temp'));
    }
    public function restore($id)
    {
        $find = Resignation::onlyTrashed()->where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Resignation";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Resignation has been restored";
            $restore = $find->restore();
            if (!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function status(Request $request, $id)
    {
        $logined_user = Auth::user();

        $model = Resignation::where('id', $id)->first();
        if ($logined_user->hasRole('Department Manager')) {
            if ($request->status_type == 'approve') {
                $model->status = 1;
            } else {
                $model->status = 3;
            }
            $model->comment = 'Manager: <br />' . $request->comment;
            $model->is_manager_approved = now();
            $model->save();

            if ($request->status_type == 'approve') {
                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->resignation_date,
                    'type' => $model->hasEmploymentStatus->name,
                    'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                    'profile' => $logined_user->profile->profile,
                    'title' => 'Your request for ' . $model->hasEmploymentStatus->name . ' has been approved by manager.',
                    'reason' => $request->comment,
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $model->hasEmployee->notify(new ImportantNotificationWithMail($notification_data));
                }
                \LogActivity::addToLog('Approved Resignation by Manager');
            } else {
                $notification_data = [
                    'id' => $model->id,
                    'date' => $request->resignation_date,
                    'type' => $model->hasEmploymentStatus->name,
                    'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                    'profile' => $logined_user->profile->profile,
                    'title' => 'Your request for ' . $model->hasEmploymentStatus->name . ' has been rejected by manager.',
                    'reason' => $request->comment,
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $model->hasEmployee->notify(new ImportantNotificationWithMail($notification_data));
                }

                \LogActivity::addToLog('Rejected Resignation by Manager');
            }
        } else {
            if ($request->status_type == 'approve') {
                $model->status = 2;
            } else {
                $model->status = 3;
            }
            $model->comment = $model->comment . ' <br /> Admin: ' . $request->comment;
            $model->is_concerned_approved = now();
            $model->save();

            if ($request->status_type == 'approve') {
                \LogActivity::addToLog('Approved Resignation by Admin');

                //get user record.
                $user = User::where('id', $model->employee_id)->first();

                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->resignation_date,
                    'type' => $model->hasEmploymentStatus->name,
                    'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                    'profile' => $logined_user->profile->profile,
                    'title' => 'Your request for ' . $model->hasEmploymentStatus->name . ' has been approved by admin.',
                    'reason' => $request->comment,
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $user->notify(new ImportantNotificationWithMail($notification_data));
                }

                //send email.
                try {
                    $admin_user = User::role('Admin')->first();

                    $body = "Dear All, <br /><br />" .
                        "I am writing to inform you that we have terminated the employment of " . $user->first_name . " from our organization, effective immediately. <br /><br />" .
                        "As per company policy, I am notifying you of this termination and providing you with the necessary information for payroll and other administrative purposes. <br /><br />" .

                        $user->first_name . " 's final paycheck will be processed and distributed in accordance with state and federal laws.Please note that Amar Chand will no longer have access to our organization's portals, systems, and resources, effective immediately. We kindly request that you take the necessary steps to revoke their access and ensure the security of our systems and data.. <br /><br />" .

                        "If you have any questions or concerns regarding this matter, please do not hesitate to contact me. <br /><br /><br />" .
                        "Thank you for your attention to this matter. <br /><br />";

                    $thanks_regards = "Sincerely, <br /><br />" .
                        $admin_user->first_name;

                    $mailData = [
                        'title' => 'Employee Termination Notification - ' . $user->first_name,
                        'body' => $body,
                        'footer' => $thanks_regards
                    ];

                    if (!empty(sendEmailTo($user, 'employee_resignation')) && !empty(sendEmailTo($user, 'employee_resignation')['cc_emails'])) {
                        $to_emails = sendEmailTo($user, 'employee_resignation')['to_emails'];
                        $cc_emails = sendEmailTo($user, 'employee_resignation')['cc_emails'];
                        Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
                    } else {
                        $to_emails = sendEmailTo($user, 'employee_resignation')['to_emails'];
                        Mail::to($to_emails)->send(new Email($mailData));
                    }

                    \LogActivity::addToLog('Resigned employee');
                    return response()->json(['success' => true]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return $e->getMessage();
                }
                //send email.
            } else {
                //get user record.
                $user = User::where('id', $model->employee_id)->first();

                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->resignation_date,
                    'type' => $model->hasEmploymentStatus->name,
                    'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                    'profile' => $logined_user->profile->profile,
                    'title' => 'Your request for ' . $model->hasEmploymentStatus->name . ' has been rejected by Admin.',
                    'reason' => $request->comment,
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $user->notify(new ImportantNotificationWithMail($notification_data));
                }
                \LogActivity::addToLog('Rejected Resignation by Admin');
            }
        }

        return response()->json(['success' => true]);
    }

    public function terminatedEmployees(Request $request)
    {

        $this->authorize('terminated_employees-list');
        $title = 'Terminated Employees';

        $user = Auth::user();

        // $user = $logined_user;
        // $employees_ids = [];
        // $department_users = DepartmentUser::where('end_date',  NULL)->get();

        // $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        // $data['roles'] = Role::orderby('id', 'desc')->get();
        // $data['departments'] = Department::orderby('id', 'desc')->has('departmentWorkShift')->has('manager')->where('status', 1)->get();
        // $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
        // $data['work_shifts'] = WorkShift::where('status', 1)->get();

        $currentDate = Carbon::now();
        $carbonDate = Carbon::parse($currentDate);

        $year = $carbonDate->year;
        $month = $carbonDate->month;

        $model = [];
        Resignation::where('is_rehired', 0)
            ->where('status', 2) //Approved by admin
            ->latest()
            ->chunk(100, function ($resignations) use (&$model) {
                foreach ($resignations as $resignation) {
                    $model[] = $resignation;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('employee_id', function ($model) {
                    return view('admin.resignations.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('employment_status_id', function ($model) {
                    $label = '';
                    if (isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus)) {
                        $label = '<span class="badge bg-label-' . $model->hasEmploymentStatus->class . '">' . $model->hasEmploymentStatus->name . '</span>';
                    }
                    return $label;
                })
                ->editColumn('last_working_date', function ($model) {
                    return '<span class="text-primary fw-semibold">' . Carbon::parse($model->last_working_date)->format('d, M Y') . '</span>';
                })
                ->editColumn('updated_at', function ($model) {
                    return date('d, M Y', strtotime($model->updated_at));
                })
                ->editColumn('status', function ($model) {
                    $label = '';
                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Rejected">Rejected</span>';
                            break;
                    }
                    return $label;
                })
                ->editColumn('user_vehical', function ($model) {
                    $label = 'No';
                    if (isset($model->hasUserVehical) && !empty($model->hasUserVehical->hasVehicle->name)) {
                        $label = '<span class="badge bg-label-success">' . $model->hasUserVehical->hasVehicle->name . '</span>';
                    } else {
                        $label = '<span class="badge bg-label-danger">' . $label . '</span>';
                    }
                    return $label;
                })
                ->addColumn('last_month', function ($model) {
                    return date('M, Y', strtotime($model->resignation_date));
                })
                ->addColumn('absent', function ($model) {
                    return getLastMonthAttendanceReport($model->resignation_date, $model->employee_id, 'absent');
                })
                ->addColumn('half_days', function ($model) {
                    return getLastMonthAttendanceReport($model->resignation_date, $model->employee_id, 'half_days');
                })
                ->addColumn('lateIn', function ($model) {
                    return getLastMonthAttendanceReport($model->resignation_date, $model->employee_id, 'lateIn');
                })
                ->addColumn('action', function ($model) {
                    return view('admin.resignations.action_pre_termination', ['data' => $model])->render();
                })
                ->rawColumns(['employee_id', 'employment_status_id', 'user_vehical', 'working_days', 'status', 'last_working_date', 'action'])
                ->make(true);
        }
        return view('admin.resignations.terminated_employees', compact('title', 'user'));
    }
}
