<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    /**
     * @var array
     */
    private $records;

    /**
     * @param array $records
     */
    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return collect($this->records);
    }

    public function map($record): array
    {
        $total_days = 0;
        $regulars = 0;
        $late_ins = 0;
        $early_outs = 0;
        $half_days = 0;
        $absents = 0;
        $shift = '';
        if (!empty($record->userWorkingShift)) {
            $shift = $record->userWorkingShift->workShift;
        } else {
            if (isset($record->departmentBridge->department->departmentWorkShift->workShift) && !empty($record->departmentBridge->department->departmentWorkShift->workShift->id)) {
                $shift = $record->departmentBridge->department->departmentWorkShift->workShift;
            }
        }

        $daysData = getMonthDaysForSalary(2023, 11);
        // $data = exportMonthlyAttendanceData($daysData->first_date, $daysData->last_date, $record);
        $statistics = getAttandanceCount($record->id, $daysData->first_date, $daysData->last_date, "all", $shift);

        $total_days = $statistics['totalDays'];
        $regulars = $regulars + $statistics['workDays'];
        $late_ins = $late_ins + $statistics['lateIn'];
        $early_outs = $early_outs + $statistics['earlyOut'];
        $half_days = $half_days + $statistics['halfDay'];
        $absents = $absents + $statistics['absent'];
 
        return [
            'sno' => $record->id,
            'month' => date("M", strtotime($daysData->month)),
            'name' => getUserName($record),
            'working_days' => $total_days ?? 0,
            'regular' => $regulars ?? 0,
            'late_in' => $late_ins ?? 0,
            'early_out' => $early_outs ?? 0,
            'half_day' => $half_days ?? 0,
            'absents' => $absents ?? 0,
        ];
    }

    public function headings(): array
    {
        return [
            'S.NO#',
            'MONTH',
            'EMPLOYEE',
            'WORKING DAYS',
            'REGULAR DAYS',
            'LATE IN',
            'EARLY OUTS',
            'HALF DAYS',
            'ABSENTS',

        ];
    }

    public function styles(Worksheet $sheet)
    {
        // $sheet->getStyle('A1:I1')->applyFromArray([
        //     'alignment' => [
        //         'horizontal' => Alignment::HORIZONTAL_CENTER,
        //     ],
        //     'font' => [
        //         'bold' => true,
        //         'size' => 12,
        //     ],
        // ]);

        // $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
        //     'alignment' => [
        //         'horizontal' => Alignment::HORIZONTAL_CENTER,
        //         'vertical' => Alignment::VERTICAL_CENTER,
        //     ],
        // ]);
    }
}
