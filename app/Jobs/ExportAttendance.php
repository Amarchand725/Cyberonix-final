<?php

namespace App\Jobs;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportAttendance implements WithHeadings, WithStyles
{
    private $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function headings(): array
    {
        return [
            'S.NO#',
            'MONTH',
            'FROM',
            'TO',
            'EMPLOYEE',
            'WORKING DAYS',
            'REGULAR DAYS',
            'LATE IN',
            'EARLY OUTS',
            'HALF DAYS',
            'ABSENTS',
            'SHIFT',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    public function handle()
    {
        try {
            // Adjust the filename or path as needed
            $filename = 'export_' . date('YmdHis') . '.csv';
            $file_path = public_path("exports/") . $filename;

            // Generate the CSV file using a streamed response
            $response = new StreamedResponse(function () {
                $handle = fopen('php://output', 'w');
                // Add CSV headers
                fputcsv($handle, $this->headings());

                // Iterate through the records and add data
                foreach ($this->records as $record) {
                    // Map your data here
                    $daysData = getMonthDaysForSalary(2023, 10);
                    $month = "2023/10";
                    $report = attendanceCount($daysData->first_date, $daysData->last_date, $record);

                    // Add a new row with data
                    fputcsv($handle, [
                        'sno' => $record->id,
                        'month' =>  $month,
                        'from' => $daysData->first_date ?? "-",
                        'to' => $daysData->last_date ?? "-",
                        'name' => getUserName($record->id),
                        'working_days' =>  $report->total_days ?? 0,
                        'regular' => 0,
                        'late_in' => 0,
                        'early_out' => 0,
                        'half_day' => 0,
                        'absents' => 0,
                        'shift' => '-',
                    ]);
                }

                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $filename,
            ]);

            // Send the response to download the file immediately
            $response->send();

            Log::info('File is ready for download at: ' . $file_path);
        } catch (Exception $e) {
            Log::error('File is not downloaded: ' . $e->getMessage());
        }
    }
}
