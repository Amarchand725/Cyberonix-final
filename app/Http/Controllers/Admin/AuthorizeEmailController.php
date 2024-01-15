<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use Str;
use App\Models\User;
use App\Models\Department;
use App\Models\DepartmentUser;
use Illuminate\Http\Request;
use App\Models\AuthorizeEmail;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AuthorizeEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('authorize_emails-list');
        $title = 'All Authorized Emails';

        $users = [];
        $departmentUserIds = [];

        $managerIds = User::select(['id'])->role(['Department Manager', 'Admin'])->where('is_employee', 1)->where('status', 1)->pluck('id')->toArray();
        //Adding It Department Users
        $department = Department::where('name', 'It Department')->first();
        if(!empty($department)){
            $departmentUserIds = DepartmentUser::where('department_id', $department->id)->where('end_date', null)->pluck('id')->toArray();
        }

        $userIds = array_unique(array_merge($managerIds, $departmentUserIds));
        $users = User::select(['id', 'first_name', 'last_name', 'email'])->whereIn('id', $userIds)->where('is_employee', 1)->where('status', 1)->get();

        $model = AuthorizeEmail::orderby('id', 'desc')->get();
        if ($request->ajax() && $request->loaddata == "yes") {
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
                ->addColumn('email_title', function ($model) {
                    return '<span class="text-primary fw-semibold">' . Str::title($model->email_title) . '</span>';
                })
                ->editColumn('to_emails', function ($model) {
                    return '<span class="fw-semibold">' . view('admin.authorize_emails.to_emails', ['model' => $model])->render() . '</span>';
                })
                ->editColumn('cc_emails', function ($model) {
                    return '<span class="fw-semibold">' . view('admin.authorize_emails.cc_emails', ['model' => $model])->render() . '</span>';
                })
                ->addColumn('action', function ($model) {
                    return view('admin.authorize_emails.action', ['model' => $model])->render();
                })
                ->rawColumns(['to_emails', 'cc_emails', 'status', 'email_title', 'action'])
                ->make(true);
        }

        return view('admin.authorize_emails.index', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email_title' => 'required|max:255',
            'to_emails' => 'required',
        ]);

        DB::beginTransaction();

        try {
            AuthorizeEmail::create([
                'email_title' => $request->email_title,
                'to_emails' => json_encode($request->to_emails),
                'cc_emails' => json_encode($request->cc_emails),
            ]);

            DB::commit();

            \LogActivity::addToLog('Authorize User Emails Added');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('authorize_emails-edit');
        $model = AuthorizeEmail::where('id', $id)->first();

        $users = [];
        $departmentUserIds = [];

        $managerIds = User::select(['id'])->role(['Department Manager', 'Admin'])->where('is_employee', 1)->where('status', 1)->pluck('id')->toArray();

        //Adding It Department Users
        $department = Department::where('name', 'It Department')->first();
        if(!empty($department)){
            $departmentUserIds = DepartmentUser::where('department_id', $department->id)->where('end_date', null)->pluck('id')->toArray();
        }

        $userIds = array_unique(array_merge($managerIds, $departmentUserIds));
        $users = User::select(['id', 'first_name', 'last_name', 'email'])->whereIn('id', $userIds)->where('is_employee', 1)->where('status', 1)->get();

        return (string) view('admin.authorize_emails.edit_content', compact('model', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $authorize_id)
    {
        $this->validate($request, [
            'email_title' => 'required|max:255',
            'to_emails' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $authorize = AuthorizeEmail::where('id', $authorize_id)->first();
            $authorize->email_title = $request->email_title;
            $authorize->to_emails = json_encode($request->to_emails);
            $authorize->cc_emails = json_encode($request->cc_emails);
            $authorize->save();

            DB::commit();

            \LogActivity::addToLog('Authorize Email Updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthorizeEmail $authorize_email)
    {
        $this->authorize('authorize_emails-delete');

        if (isset($authorize_email) && !empty($authorize_email)) {
            $historyArray['model_id'] = $authorize_email->id;
            $historyArray['model_name'] = "\App\Models\AuthorizeEmail";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Authorize Email has been deleted";
            $model = $authorize_email->delete();
            if ($model) {
                LogActivity::addToLog('Authorize Email Deleted');
                LogActivity::deleteHistory($historyArray);
                return response()->json([
                    'status' => true,
                ]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function trashed(Request $request)
    {
        $title = 'All Trashed Authorized Emails';
        $model = AuthorizeEmail::orderby('id', 'desc')->onlyTrashed()->get();
        if ($request->ajax()) {
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

                    return strip_tags($label);
                })
                ->addColumn('email_title', function ($model) {
                    if ($model->email_title == 'new_employee_info') {
                        return 'New Employee Information';
                    } elseif ($model->email_title == 'employee_termination') {
                        return 'Employee Termination';
                    }
                })
                ->editColumn('cc_emails', function ($model) {
                    return view('admin.authorize_emails.cc_emails', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="' . route('authorize_emails.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['to_emails', 'cc_emails', 'action'])
                ->make(true);
        }

        return view('admin.authorize_emails.index', compact('title'));
    }
    public function restore($id)
    {
        $find = AuthorizeEmail::onlyTrashed()->where('id', $id)->first();

        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\AuthorizeEmail";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Authorize Email has been restored";
            $restore = $find->restore();
            if (!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }
}
