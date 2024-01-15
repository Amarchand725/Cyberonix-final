<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\User;
use App\Models\WorkShift;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\DepartmentUser;
use App\Models\EmploymentStatus;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('roles-list');
        $data['per_page_records'] = 10;
        $data['title'] = 'All Roles';
        $data['models'] = Permission::orderby('id','DESC')->groupBy('label')->get();
        $data['work_shifts'] = WorkShift::orderby('id', 'desc')->get();
        $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        $data['roles'] = Role::orderby('id', 'desc')->get();
        $data['departments'] = Department::orderby('id', 'desc')->where('status', 1)->get();
        $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
        $data['employees_users'] = User::orderby('id', 'desc')->where('is_employee', 1)->take(5)->get();
        $emp_statuses = ['Terminated', 'Voluntary', 'Layoffs', 'Retirements'];
        $data['termination_employment_statues'] = EmploymentStatus::whereIn('name', $emp_statuses)->get();

        $model = User::orderby('id', 'desc')->where('is_employee', 1)->get();
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('role', function($model){
                    return $model->getRoleNames()->first();
                })
                ->addColumn('Department', function($model){
                    if(isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)){
                        return $model->departmentBridge->department->name;
                    }else{
                        return '-';
                    }
                })
                ->addColumn('shift', function($model){
                    if(isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    }else{
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';
                    
                    if(isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name)){
                        if($model->employeeStatus->employmentStatus->name=='Terminated'){
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        }elseif($model->employeeStatus->employmentStatus->name=='Permanent'){
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        }elseif($model->employeeStatus->employmentStatus->name=='Probation'){
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        }
                    }

                    return $label;
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.employees.employee-action', ['employee' => $model])->render();
                })
                ->rawColumns(['first_name', 'action', 'emp_status'])
                ->make(true);
        }

        return view('admin.roles.index', $data);
    }

    public function getRoleEmployees(Request $request) {
        $records = User::where('is_employee', 1)->select("*");
        if ($request->ajax()) {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                        return '<span class="text-primary">' . $model->departmentBridge->department->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';

                    if (isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name)) {
                        if ($model->employeeStatus->employmentStatus->name == 'Terminated') {
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Permanent') {
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Probation') {
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        } else {
                            $label = '<span class="badge bg-label-info me-1">' . $model->employeeStatus->employmentStatus->name . '</span>';
                        }
                    }

                    return $label;
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
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.employee-action', ['employee' => $model])->render();
                })
                ->rawColumns(['emp_status', 'status', 'first_name', 'role', 'Department', 'action'])
                ->make(true);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:roles', 'max:100'],
        ]);

        DB::beginTransaction();

        try{
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->input('permissions'));

            if($role){
                DB::commit();
            }

            \LogActivity::addToLog('New Role Added');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('roles-edit');
        $role = Role::where('id', $id)->first();
        $role_permissions = $role->getPermissionNames();
        $models = Permission::orderby('id','DESC')->groupBy('label')->get();
        $roles = Role::orderby('id', 'desc')->get();

        return (string) view('admin.roles.edit_content', compact('role', 'models', 'roles', 'role_permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:150|unique:roles,id,'.$request->role_id,
        ]);

        DB::beginTransaction();

        try{
            $role = Role::where('id', $request->role_id)->first();
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($request->input('permissions'));

            DB::commit();

            \LogActivity::addToLog('Role Updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
