<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Helpers\LogActivity;
use Auth;
use App\Models\User;
use App\Models\WorkShift;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\DepartmentUser;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('departments-list');
        $title = 'All Departments';
        $data = [];

        $data['parent_departments'] = Department::where('status', 1)->get();
        $data['work_shifts'] = WorkShift::where('status', 1)->get();

        //Get Department Manager role users.
        // $data['department_managers'] = User::select(['id', 'first_name', 'last_name'])->role(['Department Manager'])->where('is_employee', 1)->where('status', 1)->get();
        $admins = User::role(['Admin'])->select(['id', 'first_name', 'last_name'])->get()->toArray();
        $managers = User::role(['Department Manager'])->select(['id', 'first_name', 'last_name'])->where('status', 1)->where('is_employee', 1)->get()->toArray();
        $data['department_managers'] = array_merge($admins, $managers);

        $model = Department::orderby('id', 'desc')->get();
        if($request->ajax() && $request->loaddata == "yes") {
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
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('parent_department_id', function ($model) {
                    if(isset($model->parentDepartment) && !empty($model->parentDepartment->name)) {
                        return '<span class="fw-semibold">'.$model->parentDepartment->name.'</span>';
                    }else{
                        return '-';
                    }
                })
                ->editColumn('manager_id', function ($model) {
                    if(isset($model->manager) && !empty($model->manager->first_name)){
                        return view('admin.departments.manager-profile', ['employee' => $model->manager])->render();
                    }else{
                        $label = '<span class="badge bg-label-danger"><i class="fa fa-times"></i>&nbsp;&nbsp; No Manager Assigned</span>';
                    }

                    return $label;
                })
                ->editColumn('name', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->name.'</span>';
                })
                ->addColumn('action', function($model){
                    return view('admin.departments.action', ['model' => $model])->render();
                })
                 ->rawColumns(['status', 'name', 'manager_id', 'parent_department_id', 'action'])
                ->make(true);
        }

        return view('admin.departments.index', compact('title', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:departments', 'max:255'],
            'parent_department_id' => ['required'],
            'description' => ['max:500'],
            'location' => ['max:250'],
        ]);

        $department = $request->except(['work_shift_id']);

        DB::beginTransaction();

        try{
            $model = Department::create($department);
            if($model){
                DB::commit();

                \LogActivity::addToLog('New Department Added');
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => false]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($department_id)
    {
        $model = Department::findOrFail($department_id);
        return (string) view('admin.departments.show_content', compact('model'));
    }

    public function edit(Department $department)
    {
        $this->authorize('departments-edit');
        $data = [];
        $data['model'] = $department;
        $data['departments'] = Department::where('status', 1)->get();
        $data['work_shifts'] = WorkShift::where('status', 1)->get();
        // $data['department_managers'] = User::select(['id', 'first_name', 'last_name'])->role(['Department Manager'])->where('is_employee', 1)->where('status', 1)->get();
        $admins = User::role(['Admin'])->select(['id', 'first_name', 'last_name'])->get()->toArray();
        $managers = User::role(['Department Manager'])->select(['id', 'first_name', 'last_name'])->where('status', 1)->where('is_employee', 1)->get()->toArray();
        $data['department_managers'] = array_merge($admins, $managers);

        return (string) view('admin.departments.edit_content', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:departments,id,'.$department->id,
            'description' => ['max:500'],
            'location' => ['max:250'],
        ]);

        $department_inputs = $request->except(['work_shift_id']);

        DB::beginTransaction();

        try{
            $department->update($department_inputs);
            DB::commit();

            \LogActivity::addToLog('Department Updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $this->authorize('departments-delete');

        if(isset($department) && !empty($department)){
            $historyArray['model_id'] = $department->id;
            $historyArray['model_name'] = "\App\Models\Department";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Department has been deleted";
            $model = $department->delete();
            if($model) {
                LogActivity::addToLog('Department Deleted');
                LogActivity::deleteHistory($historyArray);
                $onlySoftDeleted = Department::onlyTrashed()->count();
                return response()->json([
                    'status' => true,
                    'trash_records' => $onlySoftDeleted
                ]);
            } else{
                return false;
            }
        } else{
            return false;
        }
    }

    public function trashed(Request $request)
    {
        $model = Department::onlyTrashed()->latest()->get();
        $title = 'All Trashed Departments';

        if($request->ajax()) {
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
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('parent_department_id', function ($model) {
                    if(isset($model->parentDepartment) && !empty($model->parentDepartment->name)) {
                        return $model->parentDepartment->name;
                    }else{
                        return '-';
                    }
                })
                ->editColumn('manager_id', function ($model) {
                    if(isset($model->manager) && !empty($model->manager->first_name)){
                        $label = '<span class="badge bg-label-success"><i class="fa fa-check"></i>'.
                                    $model->manager->first_name .' '. $model->manager->last_name.
                                '</span>';
                    }else{
                        $label = '<span class="badge bg-label-danger"><i class="fa fa-times"></i> Not Assigned Manager</span>';
                    }

                    return strip_tags($label);
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('departments.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->make(true);
        }

        return view('admin.departments.index', compact('title'));
    }
    public function restore($id)
    {
       $find = Department::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Department";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Department has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function status($department_id)
    {
        $model = Department::where('id', $department_id)->first();
        if($model->status==1){
            $model->status = 0;
        }else{
            $model->status = 1;
        }

        $model->save();

        if($model){
            return true;
        }
    }

    public function getEmployees(Request $request){
        $department = Department::where('id', $request->department_id)->first();
        if(Auth::user()->hasRole('Admin') && $department->name=='Main Department'){
            $dept_users = Department::where('parent_department_id', $department->id)->get(['manager_id']);
            $dept_user_ids = [];
            foreach($dept_users as $dept_user){
                $dept_user_ids[] = $dept_user->manager_id;
            }
        }else{
            $dept_users = DepartmentUser::where('department_id', $request->department_id)->where('user_id', '!=', $department->manager_id)->where('end_date', null)->get(['user_id']);
            $dept_user_ids = [];
            foreach($dept_users as $dept_user){
                $dept_user_ids[] = $dept_user->user_id;
            }
        }


        $users = User::whereIn('id', $dept_user_ids)->get(['id', 'first_name', 'last_name', 'slug']);

        return $users;
    }
}
