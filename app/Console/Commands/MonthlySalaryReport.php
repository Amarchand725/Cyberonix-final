<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserLeave;
use App\Models\WorkShift;
use App\Models\Discrepancy;
use Illuminate\Console\Command;
use App\Models\MonthlySalaryReport as SalaryReport;
use Illuminate\Support\Facades\Log;

class MonthlySalaryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly-salary-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly salary report excel sheet.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [];
        $logData = [];
        $employees = User::where('is_employee', 1)->where('status', 1)->get();
        foreach ($employees as $employee) {
            $logData['employee'] = $employee->email;

            //Generate Custom month salary report
            // $data['month'] = 12;
            // $data['year'] = 2023;
            // $daysData = getMonthDaysForSalary($data['year'], $data['month']);
            //Generate Custom month salary report

            //Generate current month salary report
            $daysData = getMonthDaysForSalary();
            $data['month'] = $daysData->month;
            $data['year'] = $daysData->year;
            //Generate current month salary report

            $total_earning_days = $daysData->total_days;
            if ((isset($employee->employeeStatus->start_date) && !empty($employee->employeeStatus->start_date))) {
                $empStartMonthDate = $employee->employeeStatus->start_date;
                $empStartMonthDate = Carbon::parse($empStartMonthDate);
                $startMonthDate = Carbon::parse($daysData->first_date);
                $endMonthDate = Carbon::parse($daysData->last_date);

                if ($empStartMonthDate->gte($startMonthDate) && $empStartMonthDate->lte($endMonthDate) && date('m', strtotime($startMonthDate)) <= date('m', strtotime($empStartMonthDate))) {
                    $total_earning_days = $empStartMonthDate->diffInDays($endMonthDate->addDay());
                }
                // else{
                //     // Get the current date
                //     $currentDate = Carbon::now();
                //     // Calculate the difference in days
                //     $total_earning_days = $currentDate->diffInDays($startMonthDate);
                // }
            }

            $data['total_earning_days'] = $total_earning_days;
            $logData['earning_days'] = $total_earning_days;
            $date = Carbon::createFromFormat('Y-m', $data['year'] . '-' . $data['month']);
            $data['month_year'] = $date->format('m/Y');

            $date = Carbon::create($data['year'], $data['month']);

            // Create a Carbon instance for the specified month
            $dateForMonth = Carbon::create(null, $data['month'], 1);

            // Calculate the start date (26th of the specified month)
            $startDate = $dateForMonth->copy()->subMonth()->startOfMonth()->addDays(25);
            $endDate = $dateForMonth->copy()->startOfMonth()->addDays(25);

            // Calculate the total days
            $data['totalDays'] = $startDate->diffInDays($endDate);
            $logData['total_days'] = $total_earning_days;
            $data['salary'] = 0;
            if (isset($employee->salaryHistory) && !empty($employee->salaryHistory->salary)) {
                $data['salary'] =  $employee->salaryHistory->salary;
                $data['per_day_salary'] = $data['salary'] / $data['totalDays'];
            } else {
                $data['per_day_salary'] = 0;
                $data['actual_salary'] =  0;
            }

            if (isset($employee->userWorkingShift) && !empty($employee->userWorkingShift->working_shift_id)) {
                $data['shift'] = $employee->userWorkingShift->workShift;
            } else {
                $data['shift'] = defaultShift();
            }

            $statistics = getAttandanceCount($employee->id, $data['year'] . "-" . ((int)$data['month'] - 1) . "-26", $data['year'] . "-" . (int)$data['month'] . "-25", 'all', $data['shift']);

            $lateIn = count($statistics['lateInDates']);

            // if($employee->email == "kashan.danish@cyberonix.org" ){
            //     Log::info("EMPLOYEE:" . $employee->email ." ---- ". json_encode($lateIn));
            // }
            $earlyOut = count($statistics['earlyOutDates']);
            $logData['lateIn'] = $lateIn;
            $logData['earlyOut'] = $earlyOut;
            $total_discrepancies = $lateIn + $earlyOut;

            $filled_discrepencies = Discrepancy::where('user_id', $employee->id)->where('status', 1)->whereBetween('date', [$startDate, $endDate])->count();

            // $total_over_discrepancies = $total_discrepancies - $filled_discrepencies;
            // $discrepancies_absent_days = 0;
            // if ($total_over_discrepancies > 2) {
            //     $discrepancies_absent_days = floor($total_over_discrepancies / 3);
            //     $discrepancies_absent_days = $discrepancies_absent_days / 2;
            // }
            // $lateIn = $lateIn
            $total_absent_days = 0;
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

            $total_absent_days += $discrepancies_absent_days;

            //Calculation late in and early out days amount.
            $total_approved_discrepancies = 0;

            if($filled_discrepencies > 2){
                $total_approved_discrepancies = floor($total_over_discrepancies / 3);
                $total_approved_discrepancies = $total_approved_discrepancies / 2;

            }
            $data['totalDiscrepanciesEarlyOutApprovedAmount'] = $total_approved_discrepancies*$data['per_day_salary'];
            //Calculation late in and early out days amount.

            // $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];

            $filled_full_day_leaves = UserLeave::where('user_id', $employee->id)
                ->where('status', 1)
                ->whereMonth('start_at', $data['month'])
                ->whereYear('start_at', $data['year'])
                ->where('behavior_type', 'Full Day')
                ->get();

            $filled_full_day_leaves = $filled_full_day_leaves->sum('duration');

            $filled_half_day_leaves = UserLeave::where('user_id', $employee->id)
                ->where('status', 1)
                ->whereMonth('start_at', $data['month'])
                ->whereYear('start_at', $data['year'])
                ->where('behavior_type', 'First Half')
                ->orWhere('behavior_type', 'Last Half')
                ->count();

            // $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
            // $over_half_day_leaves = 0;
            // if($filled_half_day_leaves > 0){
            //     $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
            //     $over_half_day_leaves = $filled_half_day_leaves / 2;
            // }

            $over_half_day_leaves = 0;
            if($filled_half_day_leaves > 0){
                $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
                $over_half_day_leaves = $filled_half_day_leaves / 2;

                $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
            }else{
                $over_half_day_leaves = $statistics['halfDay']/2;
                $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
            }

            $total_absent_days += $over_half_day_leaves;

            $over_absent_days = 0;
            if($filled_full_day_leaves > 0){
                $over_absent_days = $statistics['absent'] - $filled_full_day_leaves;
                $data['absent_days_amount'] = $over_absent_days * $data['per_day_salary'];
            }else{
                $data['absent_days_amount'] = $statistics['absent']*$data['per_day_salary'];
                $over_absent_days = $statistics['absent'];
            }

            $total_absent_days += $over_absent_days;

            //calculation approved absent and half days amount.
            $totalApprovedFullDayHalfDays = $filled_half_day_leaves + $filled_full_day_leaves;
            $totalApprovedFullDayHalfDaysAmount = 0;
            if($totalApprovedFullDayHalfDays > 0){
                $totalApprovedFullDayHalfDaysAmount = $totalApprovedFullDayHalfDays * $data['per_day_salary'];
            }
            $data['totalApprovedFullDayHalfDayAmount'] = $totalApprovedFullDayHalfDaysAmount;
            //calculation approved absent and half days amount.

            //total Approved Amount
            $data['totalApprovedAmount'] = $data['totalApprovedFullDayHalfDayAmount']+$data['totalDiscrepanciesEarlyOutApprovedAmount'];
            //total Approved Amount

            // $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];

            // $over_absent_days = $statistics['absent'] - $filled_full_day_leaves;
            // $data['absent_days_amount'] = $over_absent_days * $data['per_day_salary'];

            $total_full_and_half_days_absent = $over_absent_days + $over_half_day_leaves;

            $all_absents = $total_full_and_half_days_absent + $discrepancies_absent_days;
            $all_absent_days_amount = $data['per_day_salary'] * $all_absents;
            $logData['all_absents'] = $all_absents;
            $logData['over_half_day_leaves'] = $over_half_day_leaves;
            $data['earning_days_amount'] =  $data['total_earning_days'] * $data['per_day_salary'];

            if (!empty($employee->hasAllowance) && date('Y-m-d') >= date('Y-m-d', strtotime($employee->hasAllowance->effective_date))) {
                $data['car_allowance'] = $employee->hasAllowance->allowance;
            } else {
                $data['car_allowance'] = 0;
            }
            $data['total_actual_salary'] = $data['salary'] ;
            $totalApprovedDaysAndAbsentDaysAmount = $data['totalApprovedAmount']+$all_absent_days_amount;
            $total_earning_salary = $data['earning_days_amount'] - $totalApprovedDaysAndAbsentDaysAmount;
            $data['total_earning_salary'] = $data['earning_days_amount'];
            $data['total_leave_discrepancies_approve_salary'] = $all_absent_days_amount;
            $all_absent_days_amount = $data['late_in_early_out_amount'] + $data['half_days_amount'] + $data['absent_days_amount'];
            $total_net_salary = $data['earning_days_amount'] - $all_absent_days_amount;
            $data['net_salary'] = $total_net_salary+$data['car_allowance'];
            // Log::info("LOG INFO DATA : " . json_encode($logData));

            $earning_days = $total_earning_days - $total_absent_days;

            // dd($data);
            SalaryReport::create([
                'employee_id' => $employee->id,
                'month_year' => $data['month_year'],
                'actual_salary' =>  $data['total_actual_salary'],
                'car_allowance' =>  $data['car_allowance'],
                'earning_salary' =>  $total_earning_salary,
                'approved_days_amount' => $data['totalApprovedAmount'] ?? 0,
                'deduction' =>  $all_absent_days_amount, //deduction
                'net_salary' =>  $data['net_salary'],
                'generated_date' =>  date('Y-m-d'),
                'total_days' => $total_earning_days ?? 0,
                'earning_days' => $earning_days ?? 0,
                'absent_days' => $statistics['absent'] ?? 0,
                'late_in_days' => $lateIn ?? 0,
                'half_days' => $statistics['halfDay']  ?? 0,
                'currency_code' => isset($employee->salaryHistory->getCurrency->code) && !empty($employee->salaryHistory->getCurrency->code) ? $employee->salaryHistory->getCurrency->code : null,
                'bank_account_id' => isset($employee->bank) && !empty($employee->bank) ? $employee->bank->id : null,
                'department_id' => isset($employee->departmentBridge->department->id) && !empty($employee->departmentBridge->department->id) ? $employee->departmentBridge->department->id : null,
            ]);
        }

    }
}
