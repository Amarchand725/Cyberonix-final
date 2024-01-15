<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('permissions-list');
        $title = 'All Permissions';

        // $model = Permission::orderby('id','DESC')->groupBy('label')->get();
        $model = [];
        Permission::groupBy('label')
            ->latest()
            ->chunk(100, function ($permissions) use (&$model) {
                foreach ($permissions as $permission) {
                    $model[] = $permission;
                }
        });
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('label', function ($model) {
                    return '<span class="text-primary">'.Str::upper($model->label).'</span>';
                })
                ->addColumn('permissions', function ($model) {
                    return view('admin.permissions.permissions', ['model' => $model])->render();
                })
                ->addColumn('permission_url', function ($model) {
                    return $model->name;
                })
                ->addColumn('action', function($model){
                    return view('admin.permissions.tickets-action', ['model' => $model])->render();
                })
                ->rawColumns(['permissions', 'label', 'action'])
                ->make(true);
        }

        return view('admin.permissions.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['label'] = Str::lower($request->name);

        if(!empty($request->custom)){
            $this->validate($request, [
                'name' => 'unique:permissions,name',
                'label' => 'required|max:191',
                'custom' => 'max:191',
            ]);
        }else{
            $this->validate($request, [
                'label' => 'required',
                'name' => 'required|max:191',
                'custom' => 'max:191',
                'permissions.*' => 'required',
                'permissions' => 'required'
            ]);
        }

        DB::beginTransaction();

        try{
            $input_permissions = $request->permissions;
            if(!empty($request->custom)){
                $input_permissions[] = $request->custom;
            }

            if(!empty($input_permissions)){
                foreach($input_permissions as $permission){
                    Permission::create([
                        'label' =>  Str::lower($request->name),
                        'name' =>  str_replace(' ', '_', Str::lower($request->name)).'-'.$permission,
                        'guard_name' => 'web',
                    ]);
                }
            }

            DB::commit();
            \LogActivity::addToLog('New Permission Added');
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('permission-delete');
        $find = Permission::where('label', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Permission";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Permission has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Permission Deleted');
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
}
