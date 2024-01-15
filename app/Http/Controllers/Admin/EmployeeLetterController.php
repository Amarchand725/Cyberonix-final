<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use PDF;
use Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\VehicleUser;
use Illuminate\Http\Request;
use App\Models\EmployeeLetter;
use App\Models\LetterTemplate;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Notifications\ImportantNotification;

class EmployeeLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('employee_letters-list');
        $title = 'All Employee Letters';


        $employees = User::orderby('id', 'desc')->where('is_employee', 1)->where('status', 1)->get(['id', 'slug', 'first_name', 'last_name']);
        // $data = EmployeeLetter::latest()->get();

        $data = [];

        EmployeeLetter::with("hasEmployee", "hasEmployee.profile")->whereHas("hasEmployee.profile")->latest()
            ->chunk(100, function ($letters) use (&$data) {
                foreach ($letters as $letter) {
                    if(isset($letter->hasEmployee->profile) && !empty($letter->hasEmployee->profile))  {
                        $data[] = $letter;
                    }
                }
        });

        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('title', function ($data) {
                    return formatLetterTitle($data->title);
                })
                ->editColumn('effective_date', function ($data) {
                    if (!empty($data->effective_date)) {
                        return Carbon::parse($data->effective_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('validity_date', function ($data) {
                    if (!empty($data->validity_date)) {
                        return Carbon::parse($data->validity_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('created_at', function ($data) {
                    if (!empty($data->created_at)) {
                        return Carbon::parse($data->created_at)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('employee_id', function ($data) {

                    return userWithHtml($data->hasEmployee);
                })
                ->editColumn('title', function ($data) {
                    $title = str_replace('_', ' ', $data->title);
                    return '<span class="text-primary fw-semibold">' . ucwords($title) . '</span>';
                })
                ->addColumn('action', function ($data) {
                    return view('admin.employee_letters.action', ['model' => $data])->render();
                })
                ->rawColumns(['employee_id', 'title', 'action'])
                ->make(true);
        }

        return view('admin.employee_letters.index', compact('title', 'employees'));
    }

    public function allEmployeeLetters(Request $request)
    {
        $this->authorize('employee_all_letters-list');
        $title = 'All Employee Letters';

        $employees = User::orderby('id', 'desc')->where('is_employee', 1)->where('status', 1)->get(['id', 'slug', 'first_name', 'last_name']);

        // $data = EmployeeLetter::where('employee_id', Auth::user()->id)->latest()->get();

        $data = [];
        EmployeeLetter::where('employee_id', Auth::user()->id)
            ->latest()
            ->chunk(100, function ($letters) use (&$data) {
                foreach ($letters as $letter) {
                    $data[] = $letter;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('title', function ($data) {
                    return formatLetterTitle($data->title);
                })
                ->editColumn('effective_date', function ($data) {
                    if (!empty($data->effective_date)) {
                        return Carbon::parse($data->effective_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('validity_date', function ($data) {
                    if (!empty($data->validity_date)) {
                        return Carbon::parse($data->validity_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('created_at', function ($data) {
                    if (!empty($data->created_at)) {
                        return Carbon::parse($data->created_at)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('employee_id', function ($data) {
                    return view('admin.employee_letters.employee-profile', ['model' => $data])->render();
                })
                ->editColumn('title', function ($data) {
                    $title = str_replace('_', ' ', $data->title);
                    return '<span class="text-primary fw-semibold">' . ucwords($title) . '</span>';
                })
                ->addColumn('action', function ($data) {
                    return view('admin.employee_letters.action', ['model' => $data])->render();
                })
                ->rawColumns(['employee_id', 'title', 'action'])
                ->make(true);
        }

        return view('admin.employee_letters.all_letters', compact('title', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'title' => 'required',
            'effective_date' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $model = EmployeeLetter::create([
                'created_by' => Auth::user()->id,
                'employee_id' => $request->employee_id,
                'title' => $request->title,
                'effective_date' => $request->effective_date,
                'validity_date' => $request->validity_date ?? NULL,
            ]);

            DB::commit();

            if ($model) {
                $login_user = Auth::user();

                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->effective_date,
                    'type' => $model->title,
                    'name' => $login_user->first_name . ' ' . $login_user->last_name,
                    'profile' => $login_user->profile->profile,
                    'title' => 'Your ' . formatLetterTitle($model->title) . ' has been created.',
                    'reason' => 'Your letter has been created.',
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $model->hasEmployee->notify(new ImportantNotification($notification_data));
                }

                \LogActivity::addToLog('New letter Added');

                return response()->json(['success' => true]);
            } else {
                return response()->json(['failed' => false]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($model_id)
    {
        $employee_letter = EmployeeLetter::findOrFail($model_id);
        if ($employee_letter->title == "joining_letter") {
            $model = $this->joiningLetterData($employee_letter);
            return (string) view('admin.employee_letters.joining_letter', compact('model', 'employee_letter'));
        } elseif ($employee_letter->title == "vehical_letter") {
            $model = $this->vehicleLetterData($employee_letter);
            return (string) view('admin.employee_letters.vehicle_letter', compact('model', 'employee_letter'));
        } elseif ($employee_letter->title == "promotion_letter") {
            $model = $this->promotionLetterData($employee_letter);
            return (string) view('admin.employee_letters.promotion_letter', compact('model', 'employee_letter'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('employee_letters-edit');
        $model = EmployeeLetter::where('id', $id)->first();
        $employees = User::orderby('id', 'desc')->where('is_employee', 1)->where('status', 1)->get(['id', 'slug', 'first_name', 'last_name']);
        return (string) view('admin.employee_letters.edit_content', compact('model', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'title' => 'required',
            'effective_date' => 'required',
        ]);

        $model = EmployeeLetter::where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->employee_id = $request->employee_id;
            $model->title = $request->title;
            $model->effective_date = $request->effective_date;
            $model->validity_date = $request->validity_date ?? NULL;
            $model->save();

            if ($model) {
                $login_user = Auth::user();

                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->effective_date,
                    'type' => $model->title,
                    'name' => $login_user->first_name . ' ' . $login_user->last_name,
                    'profile' => $login_user->profile->profile,
                    'title' => 'Your ' . formatLetterTitle($model->title) . ' has been updated.',
                    'reason' => 'Your letter has been updated.',
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $model->hasEmployee->notify(new ImportantNotification($notification_data));
                }

                DB::commit();
                \LogActivity::addToLog('Employee Letter Updated');

                return response()->json(['success' => true]);
            }
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
        $this->authorize('employee_letters-delete');

        $find = EmployeeLetter::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\EmployeeLetter";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Employee Letter has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Employee Letter Deleted');
                LogActivity::deleteHistory($historyArray);
                return response()->json([
                    'status' => true,
                ]);
            } else{
                return false;
            }
        } else{
            return false;
        }
    }

    public function trashed(Request $request)
    {
        $title = 'All Trashed Employee Letters';
        if ($request->ajax()) {
            if (Auth::user()->hasRole('Admin')) {
                $data = EmployeeLetter::onlyTrashed()->latest()->get();
            } else {
                $data = EmployeeLetter::onlyTrashed()->where('employee_id', Auth::user()->id)->latest()->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('title', function ($data) {
                    return formatLetterTitle($data->title);
                })
                ->editColumn('effective_date', function ($data) {
                    if (!empty($data->effective_date)) {
                        return Carbon::parse($data->effective_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('validity_date', function ($data) {
                    if (!empty($data->validity_date)) {
                        return Carbon::parse($data->validity_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('created_at', function ($data) {
                    if (!empty($data->created_at)) {
                        return Carbon::parse($data->created_at)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('employee_id', function ($data) {
                    return view('admin.employee_letters.employee-profile', ['model' => $data])->render();
                })
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('employee_letters.restore', $data->id) . '" class="btn btn-icon btn-label-info waves-effect">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['employee_id', 'action'])
                ->make(true);
        }

        return view('admin.employee_letters.index', compact('title'));
    }
    public function restore($id)
    {
        $find = EmployeeLetter::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\EmployeeLetter";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Employee Letter has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function downloadLetter($employee_letter_id)
    {
        $employee_letter = EmployeeLetter::findOrFail($employee_letter_id);

        if ($employee_letter->title == "joining_letter") {
            $model = $this->joiningLetterData($employee_letter);
            $pdf = PDF::loadView('admin.employee_letters.joining-pdf-letter', compact('model'));
        } elseif ($employee_letter->title == "vehical_letter") {
            $model = $this->vehicleLetterData($employee_letter);
            $pdf = PDF::loadView('admin.employee_letters.vehicle-pdf-letter', compact('model'));
        } elseif ($employee_letter->title == "promotion_letter") {
            $model = $this->promotionLetterData($employee_letter);
            $pdf = PDF::loadView('admin.employee_letters.promotion-pdf-letter', compact('model'));
        }

        $download_file_name = $employee_letter->title;
        return $pdf->download($download_file_name . '.pdf');
    }

    public function joiningLetterData($employee_letter)
    {
        $employee_name = '';
        if (isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->first_name)) {
            $employee_name = $employee_letter->hasEmployee->first_name . ' ' . $employee_letter->hasEmployee->last_name;
        }

        $is_vehicle = '';
        $vehicle_cc = '';
        if (isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->is_vehicle)) {
            $is_vehicle = $employee_letter->hasEmployee->is_vehicle;
            $vehicle_cc = $employee_letter->hasEmployee->jobHistory->vehicle_cc;
        }

        $employee_designation = '';
        if (isset($employee_letter->hasEmployee->joiningDesignation->designation) && !empty($employee_letter->hasEmployee->joiningDesignation->designation->title)) {
            $employee_designation = $employee_letter->hasEmployee->joiningDesignation->designation->title;
        }

        $employee_department = '';
        if (isset($employee_letter->hasEmployee->joiningDepartmentBridge->department) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->name)) {
            $employee_department = $employee_letter->hasEmployee->joiningDepartmentBridge->department->name;
        }

        $employee_salary = 0;
        if (isset($employee_letter->hasEmployee->joiningSalary) && !empty($employee_letter->hasEmployee->joiningSalary->salary)) {
            $employee_salary = $employee_letter->hasEmployee->joiningSalary->salary;
        }

        $employee_salary_in_words = '';
        if (isset($employee_letter->hasEmployee->joiningSalary) && !empty($employee_letter->hasEmployee->joiningSalary->salary)) {
            $employee_salary_in_words = $this->convertNumberToText($employee_letter->hasEmployee->joiningSalary->salary);
        }

        $reporting_name = '';
        if (isset($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->first_name)) {
            $reporting_name = $employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->first_name . ' ' . $employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->last_name;
        }

        $reporting_designation = '';
        if (isset($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->jobHistory->designation) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->jobHistory->designation->title)) {
            $reporting_designation = $employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->jobHistory->designation->title;
        }

        $reporting_department = '';
        if (isset($employee_letter->hasEmployee->joiningDepartmentBridge->department) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->name)) {
            $reporting_department = $employee_letter->hasEmployee->joiningDepartmentBridge->department->name;
        }


        $model = [
            'title' => formatLetterTitle($employee_letter->title),
            'effective_date' => date('d, M Y', strtotime($employee_letter->effective_date)),
            'is_vehicle' => $is_vehicle,
            'vehicle_cc' => $vehicle_cc,
            'name' => $employee_name,
            'designation' => $employee_designation,
            'department' => $employee_department,
            'salary' => number_format($employee_salary),
            'salary_in_words' => $employee_salary_in_words,
            'reporting_name' => $reporting_name,
            'reporting_designation' => $reporting_designation,
            'reporting_department' => $reporting_department,
            'validity_date' => date('d, M Y', strtotime($employee_letter->validity_date)),
        ];

        return (object)$model;
    }

    public function vehicleLetterData($employee_letter)
    {
        $employee_name = '';
        $employee_cnic = '';
        if (isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->first_name)) {
            $employee_name = $employee_letter->hasEmployee->first_name . ' ' . $employee_letter->hasEmployee->last_name;
            $employee_cnic = $employee_letter->hasEmployee->profile->cnic;
        }

        $vehicle_name = '';
        $vehicle_reg_number = '';
        if (isset($employee_letter->hasUserVehicle->hasVehicle) && !empty($employee_letter->hasUserVehicle->hasVehicle->name)) {
            $vehicle_name = $employee_letter->hasUserVehicle->hasVehicle->name;
            $vehicle_reg_number = $employee_letter->hasUserVehicle->hasVehicle->registration_number;
        }


        $model = [
            'title' => formatLetterTitle($employee_letter->title),
            'effective_date' => date('d, F Y', strtotime($employee_letter->effective_date)),
            'name' => $employee_name,
            'cnic' => $employee_cnic,
            'vehicle_name' => $vehicle_name,
            'vehicle_reg_number' => $vehicle_reg_number,
        ];

        return (object)$model;
    }

    public function promotionLetterData($employee_letter)
    {
        $employee_name = '';
        if (isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->first_name)) {
            $employee_name = $employee_letter->hasEmployee->first_name . ' ' . $employee_letter->hasEmployee->last_name;
        }

        $employee_designation = '';
        if (isset($employee_letter->hasEmployee->jobHistory->designation) && !empty($employee_letter->hasEmployee->jobHistory->designation->title)) {
            $employee_designation = $employee_letter->hasEmployee->jobHistory->designation->title;
        }

        $employee_department = '';
        if (isset($employee_letter->hasEmployee->departmentBridge->department) && !empty($employee_letter->hasEmployee->departmentBridge->department->name)) {
            $employee_department = $employee_letter->hasEmployee->departmentBridge->department->name;
        }

        $current_salary = 0;
        if (isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)) {
            $current_salary = $employee_letter->hasEmployee->salaryHistory->salary;
        }

        $raise_salary = 0;
        if (isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)) {
            $raise_salary = $employee_letter->hasEmployee->salaryHistory->raise_salary;
        }

        $employee_salary_in_words = '';
        if (isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)) {
            $employee_salary_in_words = $this->convertNumberToText($employee_letter->hasEmployee->salaryHistory->salary);
        }

        $vehicle_name = '';
        if (isset($employee_letter->hasEmployee->jobHistory) && !empty($employee_letter->hasEmployee->jobHistory->vehicle_name)) {
            $vehicle_name = $employee_letter->hasEmployee->jobHistory->vehicle_name;
        }

        $vehicle_cc = '';
        if (isset($employee_letter->hasEmployee->jobHistory) && !empty($employee_letter->hasEmployee->jobHistory->vehicle_cc)) {
            $vehicle_cc = $employee_letter->hasEmployee->jobHistory->vehicle_cc;
        }

        $salary_percentage = 0;
        if (isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)) {
            $salary_percentage = ($employee_letter->hasEmployee->salaryHistory->raise_salary / $employee_letter->hasEmployee->salaryHistory->salary) * 100;
        }

        $model = [
            'title' => formatLetterTitle($employee_letter->title),
            'effective_date' => date('F, d Y', strtotime($employee_letter->effective_date)),
            'name' => $employee_name,
            'employee_designation' => $employee_designation,
            'employee_department' => $employee_department,
            'raise_salary' => number_format($raise_salary),
            'salary' => number_format($current_salary),
            'employee_salary_in_words' => $employee_salary_in_words,
            'vehicle_name' => $vehicle_name,
            'vehicle_cc' => $vehicle_cc,
            'increased_percent' => number_format($salary_percentage, 2),
        ];

        return (object)$model;
    }

    function convertNumberToText($num = '')
    {
        $num    = (string) ((int) $num);

        if ((int) ($num) && ctype_digit($num)) {
            $words  = array();

            $num    = str_replace(array(',', ' '), '', trim($num));

            $list1  = array(
                '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
                'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
                'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
            );

            $list2  = array(
                '', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
                'seventy', 'eighty', 'ninety', 'hundred'
            );

            $list3  = array(
                '', 'thousand', 'million', 'billion', 'trillion',
                'quadrillion', 'quintillion', 'sextillion', 'septillion',
                'octillion', 'nonillion', 'decillion', 'undecillion',
                'duodecillion', 'tredecillion', 'quattuordecillion',
                'quindecillion', 'sexdecillion', 'septendecillion',
                'octodecillion', 'novemdecillion', 'vigintillion'
            );

            $num_length = strlen($num);
            $levels = (int) (($num_length + 2) / 3);
            $max_length = $levels * 3;
            $num    = substr('00' . $num, -$max_length);
            $num_levels = str_split($num, 3);

            foreach ($num_levels as $num_part) {
                $levels--;
                $hundreds   = (int) ($num_part / 100);
                $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
                $tens       = (int) ($num_part % 100);
                $singles    = '';

                if ($tens < 20) {
                    $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
                } else {
                    $tens = (int) ($tens / 10);
                    $tens = ' ' . $list2[$tens] . ' ';
                    $singles = (int) ($num_part % 10);
                    $singles = ' ' . $list1[$singles] . ' ';
                }
                $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
            }
            $commas = count($words);
            if ($commas > 1) {
                $commas = $commas - 1;
            }

            $words  = implode(', ', $words);

            $words  = trim(str_replace(' ,', ',', ucwords($words)), ', ');
            if ($commas) {
                $words  = str_replace(',', ' and', $words);
            }

            return $words;
        } else if (!((int) $num)) {
            return 'Zero';
        }
        return '';
    }
}
