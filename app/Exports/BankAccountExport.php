<?php

namespace App\Exports;

use App\Models\BankAccount;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BankAccountExport implements FromCollection, WithHeadings, WithStyles
{
     use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $bank_accounts_data = BankAccount::where('status', 1)->get();
        $bank_accounts = [];
        $counter = 1;
        foreach($bank_accounts_data as $account){
            $employee_name = '';
            if(isset($account->employee) && !empty($account->employee->first_name)){
                $employee_name = $account->employee->first_name.' '.$account->employee->last_name;
            }
            
            $bank_accounts[] = [
                'sno' =>$counter++,
                'user_id' => $employee_name,
                'bank_name' => $account->bank_name,
                'branch_code' => $account->branch_code,
                'title' => $account->title,
                'iban' => $account->iban,
                'account' => $account->account,
            ];
        }

        return collect($bank_accounts);
    }

    public function headings(): array
    {
        return [
            'S.NO#',
            'EMPLOYEE',
            'BANK NAME',
            'BRANCH CODE',
            'TITLE',
            'IBAN',
            'ACCOUNT',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        // Define the styles for the header row
        $sheet->getStyle('A1:G1')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 12
            ]
        ]);

        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
    }
}
