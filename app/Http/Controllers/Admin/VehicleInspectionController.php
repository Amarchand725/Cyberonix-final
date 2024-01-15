<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\VehicleInspection;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VehicleInspectionController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $this->authorize('vehicle_inspections-list');
        $title = 'Vehicle Inspection';
        $vehicle_users = VehicleUser::where('status', 1)->get();

        $user_vehicle_ids = [];
        foreach($vehicle_users as $vehicle_user){
            $user_vehicle_ids[] = $vehicle_user->vehicle_id;
        }

        $data['vehicles'] = Vehicle::whereIn('id', $user_vehicle_ids)->where('status', 1)->get(['id', 'name', 'model', 'color', 'registration_number']);

        // $model = VehicleInspection::latest()->get();

        $model = [];
        VehicleInspection::latest()
            ->chunk(100, function ($inspections) use (&$model) {
                foreach ($inspections as $inspection) {
                    $model[] = $inspection;
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
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('receive_date', function ($model) {
                    return Carbon::parse($model->receive_date)->format('d, M Y');
                })
                ->editColumn('delivery_date', function ($model) {
                    return Carbon::parse($model->delivery_date)->format('d, M Y');
                })
                ->editColumn('vehicle_id', function ($model) {
                    return view('admin.fleet.vehicle_inspections.vehicle_profile', ['model' => $model])->render();
                })
                ->editColumn('vehicle_user_id', function ($model) {
                    return view('admin.fleet.vehicle_inspections.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.fleet.vehicle_inspections.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'vehicle_id', 'vehicle_user_id', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_inspections.index', compact('title', 'data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'vehicle' => 'required',
            'delivery_date' => 'required',
            'receive' => 'required',
        ]);
        try{
            $vehicle_user = VehicleUser::where('vehicle_id', $request->vehicle)->first();
            $insert = array(
                'vehicle_user_id' => $vehicle_user->user_id,
                'vehicle_id' => $request->vehicle,
                'delivery_date' => $request->delivery_date,
                'receive_date' => $request->receive,
                'delivery_details' => $request->delivery_details??'',
                'inspection_details' => $request->inspection_details??''
            );
            VehicleInspection::create($insert);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id){
        $model = VehicleInspection::where('id', $id)->first();
        return (string) view('admin.fleet.vehicle_inspections.show_content', compact('model'));
    }

    public function edit($id)
    {
        $data = [];
        $this->authorize('vehicle_inspections-edit');

        $data['model'] = VehicleInspection::where('id', $id)->first();

        $data['vehicles'] = Vehicle::where('status', 1)->get(['id', 'name', 'model', 'color', 'registration_number']);
        return (string) view('admin.fleet.vehicle_inspections.edit_content', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'vehicle' => 'required',
            'delivery_date' => 'required',
            'receive' => 'required',
        ]);
        try{
            $vehicle_user = VehicleUser::where('vehicle_id', $request->vehicle)->first();
            $update = array(
                'vehicle_user_id' => $vehicle_user->user_id,
                'vehicle_id' => $request->vehicle,
                'delivery_date' => $request->delivery_date,
                'receive_date' => $request->receive,
                'delivery_details' => $request->delivery_details??'',
                'inspection_details' => $request->inspection_details??''
            );
            VehicleInspection::find($id)->update($update);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->authorize('vehicle_inspections-delete');
        $find = VehicleInspection::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleInspection";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Vehicle Inspection has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Vehicle Inspection Deleted');
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

    public function trashed(Request $request)
    {
        $title = 'All Trashed Vehicle Inspections';
        $model = VehicleInspection::onlyTrashed()->get();
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
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('receive_date', function ($model) {
                    return Carbon::parse($model->receive_date)->format('d, M Y');
                })
                ->editColumn('delivery_date', function ($model) {
                    return Carbon::parse($model->delivery_date)->format('d, M Y');
                })
                ->editColumn('vehicle_id', function ($model) {
                    return view('admin.fleet.vehicle_inspections.vehicle_profile', ['model' => $model])->render();
                })
                ->editColumn('vehicle_user_id', function ($model) {
                    return view('admin.fleet.vehicle_inspections.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('vehicle_inspections.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'vehicle_id', 'vehicle_user_id', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_inspections.index', compact('title'));
    }
    public function restore($id)
    {
        $find = VehicleInspection::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleInspection";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Vehicle Inspection has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }
}
