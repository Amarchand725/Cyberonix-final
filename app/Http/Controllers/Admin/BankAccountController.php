<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Imports\BankAccountImport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('bank_accounts-list');
        $title = 'All Bank Accounts';

        $model = [];
        BankAccount::latest()
            ->chunk(100, function ($bank_accounts) use (&$model) {
                foreach ($bank_accounts as $bank_account) {
                    $model[] = $bank_account;
                }
        });

        if($request->ajax() && $request->loaddata == "yes"){
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('upload_from_excel', function ($model) {
                    $label = '';
                    if (!empty($model->upload_from_excel)) {

                        switch ($model->upload_from_excel) {
                            case 1:
                                $label = '<span class="badge bg-label-success" text-capitalized="">YES</span>';
                                break;
                            case 0:
                                $label = '<span class="badge bg-label-danger" text-capitalized="">NO</span>';
                                break;

                            case null:
                                $label = '<span class="badge bg-label-danger" text-capitalized="">NO</span>';
                                break;
                        }
                    }
                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.bank_accounts.employee-profile', ['model' => $model])->render();
                })
                ->editColumn('bank_name', function ($model) {
                    return '<span class="text-primary fw-semibold">' . $model->bank_name . '</span>';
                })
                ->addColumn('action', function ($model) {
                    return view('admin.bank_accounts.action', ['model' => $model])->render();
                })
                ->editColumn('title', function ($model) {
                    return '<span class="fw-semibold">' . $model->title . '</span>';
                })
                ->editColumn('account', function ($model) {
                    return '<span class="fw-semibold">' . $model->account . '</span>';
                })
                ->rawColumns(['user_id', 'bank_name', 'title', 'account', 'status', 'action' , 'upload_from_excel'])
                ->make(true);
        }

        return view('admin.bank_accounts.index', compact('title', 'model'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Bank Account';
        $employees = [];
        if(Auth::user()->hasRole('Admin')){
            $employees = User::where('is_employee', 1)->where('status', 1)->get();
        }

        return view('admin.bank_accounts.create', compact('title', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('Admin')) {
            $request['user_id'] = Auth::user()->id;
        }
        $request->validate([
            'user_id' => ['required'],
            'account' => ['required', 'unique:bank_accounts', 'max:50'],
            'bank_name' => ['required', 'string', 'max:255'],
            'branch_code' => ['required', 'string', 'max:10'],
            'iban' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
        ]);

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = BankAccount::create($input);
            if ($model) {
                DB::commit();
            }

            \LogActivity::addToLog('Bank Account Added');

            if(Auth::user()->hasPermissionTo('bank_accounts-list')){
                return redirect()->route('bank_accounts.index')->with('message', 'Account added successfully.');
            } else {
                return redirect()->route('bank_accounts.edit', $model->id)->with('message', 'Account added successfully.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($account_id)
    {
        $model = BankAccount::findOrFail($account_id);
        return (string) view('admin.bank_accounts.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('bank_accounts-edit');
        $title = 'Edit Bank Account Details';

        $employees = [];
        if(Auth::user()->hasRole('Admin')){
            $employees = User::where('is_employee', 1)->where('status', 1)->get();
        }

        $model = BankAccount::where('id', $id)->first();
        return view('admin.bank_accounts.edit', compact('title', 'model', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasRole('Admin')) {
            $request['user_id'] = Auth::user()->id;
        }
        $bankDetail = BankAccount::where('id', $id)->first();
        $request->validate([
            'user_id' => ['required'],
            'account' => 'required|max:55|unique:bank_accounts,id,' . $bankDetail->id,
            'bank_name' => ['required', 'string', 'max:255'],
            'branch_code' => ['required', 'string', 'max:10'],
            'iban' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
        ]);

        DB::beginTransaction();

        try {
            $bankDetail->user_id = $request->user_id;
            $bankDetail->account = $request->account;
            $bankDetail->bank_name = $request->bank_name;
            $bankDetail->branch_code = $request->branch_code;
            $bankDetail->iban = $request->iban;
            $bankDetail->title = $request->title;
            $bankDetail->save();

            if ($bankDetail) {
                DB::commit();
            }

            \LogActivity::addToLog('Bank Account Details Updated');

            if(Auth::user()->hasPermissionTo('bank_accounts-list')){
                return redirect()->route('bank_accounts.index')->with('message', 'Bank Account Details Updated Successfully.');
            }else{
                return redirect()->back()->with('message', 'Bank Account Details Updated Successfully.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function status($account_id)
    {
        $this->authorize('bank_accounts-status');
        $model = BankAccount::where('id', $account_id)->first();
        if ($model->status == 1) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }

        $model->save();

        if ($model) {
            return true;
        }
    }

    public function import(Request $request)
    {
        try {
            if ($request->file('file')) {

                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();

                $extension = $file->getClientOriginalExtension();
                if (!empty($extension) && $extension == "csv") {
                    $import = Excel::import(new BankAccountImport, $file);
                    return apiResponse(true, "File has been imported", null, 200);
                } else {
                    return apiResponse(false, "File format not supported", null, 200);
                }
            } else {
            }
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), null, 200);
        }
    }
}
