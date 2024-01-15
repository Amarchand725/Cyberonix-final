<?php

namespace App\Imports;

use App\Models\BankAccount;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BankAccountImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        
        foreach ($collection as $index => $row) {
            if (isset($row['employee_id']) && !empty($row['employee_id'])) {
                $employee = Profile::where("employment_id", $row['employee_id'])->first();
                if (!empty($employee->user)) {
                    $user_id = $employee->user->id;
                } else {
                    $user_id  = 0;
                }
                $account_tittle = $row['account_title'] ?? null;
                $account_no = $row['account_no'] ?? null;
                $iban = $row['iban'] ?? null;
                $bank_name = $row['bank_name'] ?? null;
                $branch_code = $row['branch_code'] ?? null;

                $check = BankAccount::where("user_id", $user_id)->where("status", 1)->first();
                if (!empty($check)) {
                    $check->delete();
                }
                $create = BankAccount::create([
                    "user_id" => $user_id ?? null,
                    "bank_name" => $bank_name,
                    "branch_code" => $branch_code,
                    "iban" => $iban,
                    "account" => $account_no,
                    "title" => $account_tittle,
                    "upload_from_excel" => 1,
                ]);
            }
        }
    }
}
