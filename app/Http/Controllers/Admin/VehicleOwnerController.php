<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Models\Vehicle;
use App\Models\VehicleOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VehicleOwnerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('vehicle_owners-list');
        $title = 'All Vehicle Owners';
        $temp = 'All Vehicle Owners';

        // $model = VehicleOwner::orderby('id', 'desc')->get();

        $model = [];
        VehicleOwner::latest()
            ->chunk(100, function ($vehicle_owners) use (&$model) {
                foreach ($vehicle_owners as $vehicle_owner) {
                    $model[] = $vehicle_owner;
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
                ->editColumn('phone', function ($model) {
                    return $model->phone??'N/A';
                })
                ->editColumn('name', function ($model) {
                    return view('admin.fleet.vehicle_owners.owner_profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.fleet.vehicle_owners.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'name', 'mobile', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_owners.index', compact('title', 'temp'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'max:255',
        ]);
        try{
            $insert = array(
                'company_name' => $request->company_name,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 1
            );
            VehicleOwner::create($insert);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id){
        $model = VehicleOwner::where('id', $id)->first();
        return (string) view('admin.fleet.vehicle_owners.show_content', compact('model'));
    }

    public function edit($id)
    {
        $this->authorize('vehicle_owners-edit');
        $model = VehicleOwner::where('id', $id)->first();
        return (string) view('admin.fleet.vehicle_owners.edit_content', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'max:255',
        ]);

        try{
            $update = array(
                'company_name' => $request->company_name,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            );
            VehicleOwner::findOrFail($id)->update($update);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $find = VehicleOwner::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleOwner";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Vehicle Owner has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Vehicle Owner Deleted');
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
        $model = VehicleOwner::where('id', $id)->first();
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
    public function vehicles($id){
        $data = [];
        $data['models'] = Vehicle::where('owner_id', $id)->where('status', 1)->get();
        return (string) view('admin.fleet.vehicle_owners.vehicles_content', compact('data'));
    }

    public function trashed(Request $request)
    {
        $title = 'All Trashed Owners';
        $model = VehicleOwner::orderby('id', 'desc')->onlyTrashed()->get();
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
                ->editColumn('phone', function ($model) {
                    return $model->phone??'N/A';
                })
                ->editColumn('name', function ($model) {
                    return view('admin.fleet.vehicle_owners.owner_profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('vehicle_owners.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'name', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_owners.index', compact('title'));
    }
    public function restore($id)
    {
        $find = VehicleOwner::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleOwner";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Vehicle Owner has been restored";
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
