<?php

namespace App\Exports;

use App\Models\Insurance;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InsuranceExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = Insurance::get();
        $insurance_data = [];
        $counter = 1;
        foreach($data as $item){
            $sex = 'F';
            if($item->sex==1){
                $sex = 'M';
            }
            $marital_status = 'S';
            if($item->marital_status==1){
                $marital_status = 'M';
            }
            $user = [
                'sno' =>$counter++,
                'cnic' => $item->cnic_number,
                'dob' => date('d/m/Y', strtotime($item->date_of_birth)),
                'sex' => $sex,
                'full_name' => Str::ucfirst($item->name_as_per_cnic),
                'relationship' => 'Self',
                'marital_status' => $marital_status,
            ];

            $insurance_data[] = $user;

            foreach($item->hasInsuranceMeta as $insurance_meta){
                $sex = 'F';
                if($insurance_meta->sex==1){
                    $sex = 'M';
                }
                $user_meta = [
                    'sno' => '',
                    'cnic' => $insurance_meta->cnic_number??'',
                    'dob' => date('d/m/Y', strtotime($insurance_meta->date_of_birth))??'',
                    'sex' => $sex,
                    'full_name' => Str::ucfirst($insurance_meta->name),
                    'relationship' => Str::ucfirst($insurance_meta->relationship),
                    'marital_status' => '',
                ];

                $insurance_data[] = $user_meta;
            }
        }

        return collect($insurance_data);
    }

    public function headings(): array
    {
        return [
            'S.No#',
            'CNIC',
            'DOB',
            'Sex',
            'Full Name',
            'Relationship',
            'Marital Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $data = $this->collection();

        foreach ($data as $index => $item) {
            if ($item['relationship'] === "Self") {
                // Highlight rows where the relationship is "Self"
                $rowNumber = $index + 2; // Add 2 to account for header row and 0-based index

                // Change the background color for the specified row to black
                $sheet->getStyle("A$rowNumber:G$rowNumber")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '759396', // Black background color
                        ],
                    ],
                    'font' => [
                        'color' => [
                            'rgb' => 'FFFFFF', // White font color
                        ],
                        'bold' => true,
                        'size' => 11,
                    ],
                ]);
            }
        }

        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        // Define the styles for the header row
        $sheet->getStyle('A1:G1')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00', // Background color (in this case, yellow)
                ],
            ],
        ]);

        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
    }
}
