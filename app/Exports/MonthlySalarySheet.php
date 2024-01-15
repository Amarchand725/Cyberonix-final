<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\MonthlySalaryReport;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlySalarySheet implements FromCollection, WithHeadings, WithStyles
{
    protected $title, $month, $year, $department;

    public function __construct($title, $month, $year, $department)
    {
        $this->title = $title;
        $this->month = $month;
        $this->year = $year;
        $this->department = $department;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $currentDate = Carbon::now();
        $month_year = date('m/Y', strtotime($currentDate));

        if (!empty($this->month) && !empty($this->year)) {
            $month_year = $this->month . '/' . $this->year;
        } else {
            $month_year = date('m/Y', strtotime($currentDate));
        }

        if (isset($this->department) && !empty($this->department) && $this->department != "all") {
            $deparment = $this->department;
            if (!empty($deparment)) {
                $myDpartUsers = getDepartmentUsers($deparment);
            }
        }
        $record = MonthlySalaryReport::with('hasEmployee', 'bank', 'department', 'currency')->where('month_year', $month_year);
        if (isset($myDpartUsers) && !empty($myDpartUsers)) {
            $record = $record->whereIn('employee_id', $myDpartUsers->pluck("user_id")->toArray());
        }
        $records = $record->get();
        $employees = [];
        $counter = 0;
        foreach ($records as $model) {

            $currency_code = isset($model->hasEmployee->salaryHistory->getCurrency->symbol) && !empty($model->hasEmployee->salaryHistory->getCurrency->symbol) ? $model->hasEmployee->salaryHistory->getCurrency->symbol : "Rs.";
            $counter = $counter + 1;
            $designation = '-';
            if (isset($model->hasEmployee->jobHistory->designation) && !empty($model->hasEmployee->jobHistory->designation->title)) {
                $designation = $model->hasEmployee->jobHistory->designation->title;
            }
            $department = '-';
            if (isset($model->department) && !empty($model->department->name)) {
                $department = $model->department->name;
            }

            $totalDays =  $model->total_days ?? "N/A";
            $absent_days =  $model->absent_days ?? "N/A";
            $late_in_days =   $model->late_in_days ?? "N/A";
            $halfDays =  $model->half_days ?? "N/A";
            // $earning_days = $model->earning_days;
            $employees[] = [
                'sNo' => $counter,
                'employee' => !empty($model->hasEmployee) ?  getUserName($model->hasEmployee) : "-",
                'designation' => $designation ?? "-",
                'department' => $department ?? "-",
                'bank_name' => $model->bank->bank_name ?? "-",
                'bank_account' => $model->bank->account ?? "-",
                'bank_iban' => $model->bank->iban ?? "-",
                'bank_branch_code' => $model->bank->branch_code ?? "-",
                'cnic' => $model->hasEmployee->profile->cnic?? "-",
                'total_days' => $totalDays ?? "-",
                'absent_days' => $model->absent_days ?? "-",
                'earning_days' => $model->earning_days ?? "-",
                'half_days' => $model->half_days ?? "-",
                'late_in_days' => $model->late_in_days  ?? "-",
                'actual_salary' => $currency_code . " "  .   number_format($model->actual_salary),
                'car_allowance' => $currency_code . " "  .   number_format($model->car_allowance),
                'earning_salary' => $currency_code . " "  .  number_format($model->earning_salary),
                'approved_days_amount' => $currency_code . " "  . number_format($model->approved_days_amount),
                'deduction' => $currency_code . " "  . number_format($model->deduction),
                'net_salary' => $currency_code . " "  . number_format($model->net_salary),
                'created_at' => date('d, M Y', strtotime($model->generated_date)),
            ];
        }
        return collect($employees);
    }

    public function headings(): array
    {
        return [
            [$this->title], // Additional custom heading
            [
                'S.No#',
                'Employee',
                'Designation',
                'Department',
                'Bank Name',
                'Bank Account#',
                'Bank IBAN#',
                'Bank Branch Code',
                'CNIC',
                'Total Days',
                'Absent Days',
                'Earning Days',
                'Half Days',
                'Late In Days',
                'Actual Salary',
                'Car Allowance',
                'Earning',
                'Approved Days Amount',
                'Deduction',
                'Net Salary',
                'Created At'
            ]
            // Standard column headings
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getColumnDimension('P')->setWidth(20);
        $sheet->getColumnDimension('Q')->setWidth(20);
        $sheet->getColumnDimension('R')->setWidth(20);
        $sheet->getColumnDimension('S')->setWidth(20);
        $sheet->getColumnDimension('T')->setWidth(20);

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
