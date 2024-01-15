<?php

namespace App\Http\Controllers\Admin;

use App\Models\Insurance;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InsuranceExport;
use App\Exports\BankAccountExport;

class ExportController extends Controller
{
    public function exportExcel()
    {
        $this->authorize('export_insurance-create');
        $file_name = date('d-m-Y').'.xlsx';
        return Excel::download(new InsuranceExport, $file_name);
    }
    public function exportPdf()
    {
        $this->authorize('export_insurance-create');
        $file_name = date('d-m-Y').'.pdf';
        // Fetch your data or prepare it as needed
        $data = Insurance::where('status', 1)->get(); // Replace with your actual data source

        // Create an instance of the export class with your data
        $export = new InsuranceExport($data);

        // Generate and return the PDF response
        return $export->download($file_name);
    }
    
    public function bankAccountsExportExcel()
    {
        $this->authorize('export_bank_account-create');
        $file_name = date('d-m-Y').'.xlsx';
        return Excel::download(new BankAccountExport, $file_name);
    }
    public function bankAccountsExportPdf()
    {
        $this->authorize('export_bank_account-create');
        $file_name = date('d-m-Y').'.pdf';
        // Fetch your data or prepare it as needed
        $data = BankAccount::where('status', 1)->get(); // Replace with your actual data source

        // Create an instance of the export class with your data
        $export = new BankAccountExport($data);

        // Generate and return the PDF response
        return $export->download($file_name);
    }
}
