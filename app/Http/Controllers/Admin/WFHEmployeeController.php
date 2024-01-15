<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Models\User;
use App\Models\WFHEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WFHEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('wfh_employee-list');
        $data = [];

        $title = 'All WFH Employees';
        $employees = User::orderby('id', 'desc')->where('status', 1)->where('is_employee', 1)->get();
        $model = WFHEmployee::orderby('id', 'desc')->get();
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('role', function($model){
                    if(isset($model->hasEmployee) && !empty($model->hasEmployee->getRoleNames())){
                        return '<span class="badge bg-label-primary">'.$model->hasEmployee->getRoleNames()->first().'</span>';
                    }else{
                        return '-';
                    }
                })
                ->addColumn('Department', function($model){
                    if(isset($model->hasEmployee->departmentBridge->department) && !empty($model->hasEmployee->departmentBridge->department)){
                        return '<span class="text-primary">'.$model->hasEmployee->departmentBridge->department->name.'</span>';
                    }else{
                        return '-';
                    }
                })
                ->addColumn('shift', function($model){
                    if(isset($model->hasEmployee->userWorkingShift->workShift) && !empty($model->hasEmployee->userWorkingShift->workShift->name)) {
                        return $model->hasEmployee->userWorkingShift->workShift->name;
                    }else{
                        return '-';
                    }
                })
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
                ->editColumn('user_id', function ($model) {
                    return view('admin.wfh_employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.wfh_employees.employee-action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'user_id', 'role', 'Department', 'action'])
                ->make(true);
        }

        return view('admin.wfh_employees.index', compact('title', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wfh_employees' => 'required|array',
            'wfh_employees.*' => 'required',
        ]);

        DB::beginTransaction();

        try{
            foreach($request->wfh_employees as $wfh_employee) {
                WFHEmployee::create([
                    'created_by' => Auth::user()->id,
                    'user_id' => $wfh_employee,
                    'note' => $request->note,
                    'status' => $request->status==''?1:0,
                ]);
            }

            DB::commit();

            \LogActivity::addToLog('WFH Employees added');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->authorize('wfh_employee-edit');
        $title = 'Edit WFH Employee';
        $employees = User::orderby('id', 'desc')->where('status', 1)->where('is_employee', 1)->get();
        $model = WFHEmployee::where('id', $id)->first();

        return (string) view('admin.wfh_employees.edit_content', compact('title', 'model', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $user = WFHEmployee::where('id', $id)->first();

        $request->validate([
            'user_id' => 'required',
        ]);

        DB::beginTransaction();

        try{
            $user->created_by = Auth::user()->id;
            $user->status = $request->status=="1"?1:0;
            $user->user_id = $request->user_id;
            $user->note = $request->note;
            $user->save();

            if($user){
                DB::commit();
            }

            \LogActivity::addToLog('WFH Employee updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $title = 'WFH Employee Details';
        $model = WFHEmployee::where('id', $id)->first();
        return (string) view('admin.wfh_employees.show_content', compact('title', 'model'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('wfh_employee-delete');
        $find = WFHEmployee::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\WFHEmployee";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "WFH Employee has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('WFH Employee Deleted');
                LogActivity::deleteHistory($historyArray);
                return response()->json([
                    'status' => true,
                ]);
            } else{
                return false;
            }
        } else{
            return false;
        }
    }

    public function status(Request $request, $id)
    {
        $model = WFHEmployee::where('id', $id)->first();

        try{
            if($model->status==1) {
                $model->status = 0;
            } else {
                $model->status = 1; //Active
            }

            $model->save();

            DB::commit();

            \LogActivity::addToLog('Status updated');
            return response()->json(['success' => true]);

            \LogActivity::addToLog('WFH employee status updated');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
