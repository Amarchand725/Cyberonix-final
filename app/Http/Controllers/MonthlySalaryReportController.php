<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Exports\MonthlySalarySheet;
use App\Models\MonthlySalaryReport;
use Maatwebsite\Excel\Facades\Excel;

class MonthlySalaryReportController extends Controller
{
    public function index(Request $request, $getMonth = null, $getYear = null)
    {
        $this->authorize('monthly_salary_report-list');
        $title = 'Monthly Salary Report';
        $month = '';
        $year = '';
        $url = '';
        $selectMonth = "";
        $month_year = Carbon::now()->format("Y-m");
        $start_from_monthly_report = MonthlySalaryReport::first();
        $model = MonthlySalaryReport::with('hasEmployee')->select("*");
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('actual_salary', function ($model) {
                    $currencyCode =  $model->currency->symbol ?? $model->currency_code;
                    return $currencyCode . " " .  number_format($model->actual_salary);
                })
                ->editColumn('bank', function ($model) {
                    return $model->bank->bank_name ?? "-";
                })
                ->editColumn('car_allowance', function ($model) {
                    $currencyCode =  $model->currency->symbol ?? $model->currency_code;
                    return $currencyCode . " " .  number_format($model->car_allowance);
                })
                ->editColumn('earning_salary', function ($model) {
                    $currencyCode =  $model->currency->symbol ?? $model->currency_code;
                    return $currencyCode . " " .  number_format($model->earning_salary);
                })
                ->editColumn('approved_days_amount', function ($model) {
                    $currencyCode =  $model->currency->symbol ?? $model->currency_code;
                    return $currencyCode . " " .  number_format($model->approved_days_amount);
                })
                ->editColumn('deduction', function ($model) {
                    $currencyCode =  $model->currency->symbol ?? $model->currency_code;
                    return $currencyCode . " " .  number_format($model->deduction);
                })
                ->editColumn('net_salary', function ($model) {
                    $currencyCode =  $model->currency->symbol ?? $model->currency_code;
                    return $currencyCode . " " .  number_format($model->net_salary);
                })
                ->editColumn('generated_date', function ($model) {
                    return date('d-M-Y', strtotime($model->generated_date));
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.salary_monthly_reports.employee-profile', ['employee' => $model])->render();
                })

                ->addColumn('action', function ($model) {
                    return view('admin.salary_monthly_reports.employee-action', ['employee' => $model])->render();
                })

                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance = $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->whereHas("hasEmployee", function ($query)  use ($search) {
                                $query->where('first_name', 'LIKE', "%$search%")
                                    ->orWhere('last_name', 'LIKE', "%$search%")
                                    ->orWhere('email', 'LIKE', "%$search%");
                            });
                        });
                    }
                    if (isset($request->department) && !empty($request->department) && $request->department != "all") {
                        $department = getDepartmentFromID($request->department);
                        $myDpartUsers = getDepartmentUsers($department);
                        if (!empty($myDpartUsers)) {
                            $instance = $instance->whereIn('employee_id', $myDpartUsers->pluck("user_id")->toArray());
                        }
                    }
                    if (isset($request->month) && !empty($request->month) && $request->month != null) {
                        $explode = explode("-", $request->month);
                        $year = isset($explode[0]) && !empty($explode[0]) ? $explode[0] : "";
                        $month = isset($explode[1]) && !empty($explode[1]) ? $explode[1] : "";
                        $date = Carbon::createFromFormat('Y-m', $year . '-' . $month);
                        $month_year = $date->format('m/Y');
                        if (!empty($month_year)) {
                            $instance = $instance->where("month_year", $month_year);
                        }
                    } else {
                        $currentDate = Carbon::now();
                        $defaultMonth = date('m', strtotime($currentDate));
                        $defaultYear = date('Y', strtotime($currentDate));
                        $date = Carbon::createFromFormat('Y-m', $defaultYear . '-' . $defaultMonth);
                        $month_year = $date->format('m/Y');
                        if (!empty($month_year)) {
                            $instance = $instance->where("month_year", $month_year);
                        }
                    }
                })
                ->rawColumns(['employee_id', 'bank', 'action' , 'total_days', 'earning_days' , 'absent_days' , 'late_in_days' , 'half_days' ])
                ->make(true);
        }

        return view('admin.salary_monthly_reports.index', compact('title', 'selectMonth', 'month', 'year', 'month_year', 'url'));
    }

    public function monthlySalaryReportDownload(Request $request, $getMonth = null, $getYear = null, $department = null)
    {

        $month = $getMonth;
        $year = $getYear;
        if ($request->ajax()) {
            if (isset($request->month) && !empty($request->month) && $request->month != null) {
                $explode = explode("-", $request->month);
                $year = isset($explode[0]) && !empty($explode[0]) ? $explode[0] : "";
                $month = isset($explode[1]) && !empty($explode[1]) ? $explode[1] : "";
            } else {
                $currentDate = Carbon::now();
                $month = date('m', strtotime($currentDate));
                $year = date('Y', strtotime($currentDate));
            }

            if (isset($request->department) && !empty($request->department) && $request->department != "all") {
                $department = getDepartmentFromID($request->department);
                $department_id = $department->id;
            } else {
                $department_id =  null;
            }
            return route('monthly_salary_reports.export_monthly_salary_report.download', [$month, $year, $department_id]);
        }

        $title = "Monthly Salary Report of " . date("F", mktime(0, 0, 0, $month, 1)) . ' ' . $year; // Replace with your desired title
        $file_name = date('d-m-Y') . '.xlsx';

        $name = "";
        if (isset($department) && !empty($department) && $department != "all") {

            $department = getDepartmentFromID($department);

            $dname = $department->name;
            if (!empty($dname)) {
                $dname = str_replace(" ", "-", $dname);
                $dname = strtoupper($dname);
                $name .= $dname . "-";
            }
        }

        if (isset($request->month) && !empty($request->month) && $request->month != null) {
            $explode = explode("-", $request->month);
            $year = isset($explode[0]) && !empty($explode[0]) ? $explode[0] : "";
            $month = isset($explode[1]) && !empty($explode[1]) ? $explode[1] : "";
        }
        $name .=  "SALARY-REPORT-";
        $name .=  $month;
        $name .= "-" . $year;
        $name .=   '.xlsx';

        return Excel::download(new MonthlySalarySheet($title, $month, $year, $department), $name);
    }
}
