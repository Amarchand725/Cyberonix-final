<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use Auth;
use App\Models\User;
use App\Models\Profile;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use Illuminate\Http\Request;
use App\Models\EmployeeLetter;
use Illuminate\Support\Carbon;
use App\Models\VehicleAllowance;
use App\Models\VehicleInspection;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ImportantNotification;

class VehicleUserController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $this->authorize('vehicle_users-list');
        if(Auth::user()->hasRole('Admin')){
            $title = 'All Vehicle Users';
        }else{
            $title = 'My Vehicles';
        }

        //All Employees who have vehicles already
        $all_vehicle_users = VehicleUser::where('end_date', NULL)->where('status', 1)->get();
        $vehicle_and_allowance_employees = [];
        foreach($all_vehicle_users as $vehicle_user){
            $vehicle_and_allowance_employees[] = $vehicle_user->user_id;
        }

        //All Employees who have vehicle allowances already
        $all_vehicle_allowance_users = VehicleAllowance::where('end_date', NULL)->where('status', 1)->get();
        foreach($all_vehicle_allowance_users as $allowance_vehicle_user){
            $vehicle_and_allowance_employees[] = $allowance_vehicle_user->user_id;
        }

        //Getting employees who have not booked in vehicle or allowance already
        // $data['users'] = User::whereNotIn('id', $vehicle_and_allowance_employees)->where('is_employee', 1)->where('status', 1)->get();

        $data['users'] = User::where('is_employee', 1)->where('status', 1)->get();

        $user_vehicles = VehicleUser::whereIn('user_id', $vehicle_and_allowance_employees)->where('end_date', NULL)->where('status', 1)->get();

        $all_user_vehicles = [];
        foreach($user_vehicles as $user_vehicle){
            $all_user_vehicles[] = $user_vehicle->vehicle_id;
        }

        $data['available_vehicles'] = Vehicle::whereNotIn('id', $all_user_vehicles)->where('status', 1)->get();

        // $model = VehicleUser::orderby('id', 'desc')->where('user_id', Auth::user()->id)->get();

        $model = [];
        VehicleUser::where('user_id', Auth::user()->id)
            ->latest()
            ->chunk(100, function ($vehicle_users) use (&$model) {
                foreach ($vehicle_users as $vehicle_user) {
                    $model[] = $vehicle_user;
                }
        });

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
                ->editColumn('end_date', function ($model) {
                    return Carbon::parse($model->end_date)->format('d, M Y');
                })
                ->editColumn('deliver_date', function ($model) {
                    return Carbon::parse($model->deliver_date)->format('d, M Y');
                })
                ->editColumn('vehicle_id', function ($model) {
                    return view('admin.fleet.vehicle_users.vehicle_profile', ['model' => $model])->render();
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.fleet.vehicle_users.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.fleet.vehicle_users.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'vehicle_id', 'user_id', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_users.index', compact('title', 'data'));
    }

    public function allVehicleUsers(Request $request)
    {
        $data = [];
        $this->authorize('admin_vehicle_users_list-list');
        $title = 'All Vehicle Users';
        $temp = 'All Vehicle Users';

        //All Employees who have vehicles already
        $all_vehicle_users = VehicleUser::where('end_date', NULL)->where('status', 1)->get();
        $vehicle_and_allowance_employees = [];
        foreach($all_vehicle_users as $vehicle_user){
            $vehicle_and_allowance_employees[] = $vehicle_user->user_id;
        }

        //All Employees who have vehicle allowances already
        $all_vehicle_allowance_users = VehicleAllowance::where('end_date', NULL)->where('status', 1)->get();
        foreach($all_vehicle_allowance_users as $allowance_vehicle_user){
            $vehicle_and_allowance_employees[] = $allowance_vehicle_user->user_id;
        }

        //Getting employees who have not booked in vehicle or allowance already
        // $data['users'] = User::whereNotIn('id', $vehicle_and_allowance_employees)->where('is_employee', 1)->where('status', 1)->get();

        $data['users'] = User::where('is_employee', 1)->where('status', 1)->get();

        $user_vehicles = VehicleUser::whereIn('user_id', $vehicle_and_allowance_employees)->where('end_date', NULL)->where('status', 1)->get();

        $all_user_vehicles = [];
        foreach($user_vehicles as $user_vehicle){
            $all_user_vehicles[] = $user_vehicle->vehicle_id;
        }

        $data['available_vehicles'] = Vehicle::whereNotIn('id', $all_user_vehicles)->where('status', 1)->get();

        $model = VehicleUser::orderby('id', 'desc')->get();

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
                ->editColumn('end_date', function ($model) {
                    return Carbon::parse($model->end_date)->format('d, M Y');
                })
                ->editColumn('deliver_date', function ($model) {
                    return Carbon::parse($model->deliver_date)->format('d, M Y');
                })
                ->editColumn('vehicle_id', function ($model) {
                    return view('admin.fleet.vehicle_users.vehicle_profile', ['model' => $model])->render();
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.fleet.vehicle_users.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.fleet.vehicle_users.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'vehicle_id', 'user_id', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_users.index', compact('title', 'data', 'temp'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'user_cnic' => 'required',
            'vehicle' => 'required',
            'deliver' => 'required',
        ]);
        try{
            $vehicle_assigned = VehicleUser::where('vehicle_id', $request->vehicle)->where('status', 1)->first();
            if(!empty($vehicle_assigned)){
                $vehicle_assigned->status = 0;
                $vehicle_assigned->save();
            }

            $model = array(
                'user_id' => $request->user,
                'vehicle_id' => $request->vehicle,
                'deliver_date' => $request->deliver,
                'note' => $request->note?$request->note:'',
                'status' => 1
            );
            $model = VehicleUser::create($model);
            if($model){
                $profile = Profile::where('user_id', $request->user)->first();
                $profile->cnic = $request->user_cnic;
                $profile->save();

                EmployeeLetter::create([
                    'created_by' => Auth::user()->id,
                    'vehicle_user_id' => $model->id,
                    'employee_id' => $request->user,
                    'title' => 'vehical_letter',
                    'effective_date' => $request->deliver,
                ]);

                $login_user = Auth::user();
                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->effective_date,
                    'type' => $model->title,
                    'name' => $login_user->first_name.' '.$login_user->last_name,
                    'profile' => $login_user->profile->profile,
                    'title' => 'You have alloted company maintain car.',
                    'reason' => 'Alloted Company maintain car.',
                ];

                if(isset($notification_data) && !empty($notification_data)){
                    $model->hasUser->notify(new ImportantNotification($notification_data));
                }
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id){
        $model = VehicleUser::where('id', $id)->first();
        return (string) view('admin.fleet.vehicle_users.show_content', compact('model'));
    }

    public function edit($id)
    {
        $data = [];

        $data['model'] = VehicleUser::where('id',$id)->first();

        //All Employees who have vehicles already
        $all_vehicle_users = VehicleUser::where('user_id', '!=', $data['model']->user_id)->where('vehicle_id', '!=', $data['model']->vehicle_id)->where('end_date', NULL)->where('status', 1)->get();
        $vehicle_and_allowance_employees = [];
        foreach($all_vehicle_users as $vehicle_user){
            $vehicle_and_allowance_employees[] = $vehicle_user->user_id;
        }

        //All Employees who have vehicle allowances already
        $all_vehicle_allowance_users = VehicleAllowance::where('user_id', '!=', $data['model']->user_id)->where('end_date', NULL)->where('status', 1)->get();
        foreach($all_vehicle_allowance_users as $allowance_vehicle_user){
            $vehicle_and_allowance_employees[] = $allowance_vehicle_user->user_id;
        }

        $data['users'] = User::where('is_employee', 1)->where('status', 1)->get();

        $user_vehicles = VehicleUser::whereIn('user_id', $vehicle_and_allowance_employees)->where('end_date', NULL)->where('status', 1)->get();

        $all_user_vehicles = [];
        foreach($user_vehicles as $user_vehicle){
            $all_user_vehicles[] = $user_vehicle->vehicle_id;
        }

        $data['available_vehicles'] = Vehicle::whereNotIn('id', $all_user_vehicles)->where('status', 1)->get();

        $user = User::where('id', $data['model']->user_id)->first();
        $user_cnic = '';
        if(isset($user->profile) && !empty($user->profile->cnic)){
            $user_cnic = $user->profile->cnic;
        }else if(isset($user->hasPreEmployee) && !empty($user->hasPreEmployee->cnic)){
            $user_cnic = $user->hasPreEmployee->cnic;
        }

        $data['user_cnic'] = $user_cnic;
        return (string) view('admin.fleet.vehicle_users.edit_content', compact('data'));
    }

    public function update(Request $request, $vehicle_user_id)
    {
        $this->validate($request, [
            'user' => 'required',
            'user_cnic' => 'required',
            'vehicle' => 'required',
            'deliver' => 'required',
        ]);
        try{
            $update = array(
                'user_id' => $request->user,
                'vehicle_id' => $request->vehicle,
                'deliver_date' => $request->deliver,
                'note' => $request->note?$request->note:'',
                'status' => 1
            );
            $model = VehicleUser::find($vehicle_user_id)->update($update);
            if($model){
                $profile = Profile::where('user_id', $request->user)->first();
                $profile->cnic = $request->user_cnic;
                $profile->save();

                $login_user = Auth::user();
                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->effective_date,
                    'type' => $model->title,
                    'name' => $login_user->first_name.' '.$login_user->last_name,
                    'profile' => $login_user->profile->profile,
                    'title' => 'Updated your company maintain car.',
                    'reason' => 'Updated Company maintain car.',
                ];

                if(isset($notification_data) && !empty($notification_data)){
                    $model->hasUser->notify(new ImportantNotification($notification_data));
                }
            }

            $emp_letter = EmployeeLetter::where('vehicle_user_id', $vehicle_user_id)->where('employee_id', $request->user)->latest()->first();
            if(!empty($emp_letter)){
                $emp_letter->deliver = $request->deliver;
                $emp_letter->save();
            }else{
                EmployeeLetter::create([
                    'created_by' => Auth::user()->id,
                    'vehicle_user_id' => $vehicle_user_id,
                    'employee_id' => $request->user,
                    'title' => 'vehical_letter',
                    'effective_date' => $request->deliver,
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->authorize('vehicle_users-delete');
        $find = VehicleUser::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleUser";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Vehicle User has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Vehicle User Deleted');
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

    public function status($id)
    {
        $model = VehicleUser::where('id', $id)->first();
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
    public function trashed(Request $request)
    {
        $model = VehicleUser::onlyTrashed()->get();
        $title = 'All Trashed Vehicle Users';

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

                    return $label;
                })
                ->editColumn('end_date', function ($model) {
                    return Carbon::parse($model->end_date)->format('d, M Y');
                })
                ->editColumn('deliver_date', function ($model) {
                    return Carbon::parse($model->deliver_date)->format('d, M Y');
                })
                ->editColumn('vehicle_id', function ($model) {
                    return view('admin.fleet.vehicle_users.vehicle_profile', ['model' => $model])->render();
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.fleet.vehicle_users.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('vehicle_users.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'vehicle_id', 'user_id', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_users.index', compact('title'));
    }
    public function restore($id)
    {
        $find = VehicleUser::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleUser";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Vehicle User has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function share(Request $request){
        $this->validate($request, [
            'user' => 'required',
            'deliver_date' => 'required',
        ]);

        $sharing_vehicle = VehicleUser::where('id', $request->vehicle_user_id)->first();

        try{
            $insert = array(
                'user_id' => $request->user,
                'vehicle_id' => $sharing_vehicle->vehicle_id,
                'deliver_date' => $request->deliver_date,
                'status' => 1,
                'note' => $request->note?$request->note:''
            );

            $model = VehicleUser::create($insert);
            if(!empty($model)){
                $sharing_vehicle->end_date = $request->deliver_date;
                $sharing_vehicle->status = 0;
                $sharing_vehicle->save();

                $profile = Profile::where('user_id', $request->user)->first();
                $profile->cnic = $request->user_cnic;
                $profile->save();

                $login_user = Auth::user();
                $notification_data = [
                    'id' => $model->id,
                    'date' => $model->effective_date,
                    'type' => $model->title,
                    'name' => $login_user->first_name.' '.$login_user->last_name,
                    'profile' => $login_user->profile->profile,
                    'title' => 'You have alloted company maintain car.',
                    'reason' => 'Alloted Company maintain car.',
                ];

                if(isset($notification_data) && !empty($notification_data)){
                    $model->hasUser->notify(new ImportantNotification($notification_data));
                }

                EmployeeLetter::create([
                    'created_by' => Auth::user()->id,
                    'vehicle_user_id' => $model->id,
                    'employee_id' => $request->user,
                    'title' => 'vehical_letter',
                    'effective_date' => $request->deliver_date,
                ]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function inspectionHistory($vehicle_id, $user_id){
        $data = [];
        $data['models'] = VehicleInspection::where('vehicle_user_id', $user_id)->where('vehicle_id', $vehicle_id)->get();
        return (string) view('admin.fleet.vehicle_users.show_inspection_history', compact('data'));
    }
}
