<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Models\Vehicle;
use App\Models\VehicleRent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VehicleRentController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $this->authorize('vehicle_rents-list');
        $title = 'All Vehicle Rents';

        $data['vehicles'] = Vehicle::orderby('id', 'desc')->where('status', 1)->get();
        // $model = VehicleRent::orderby('id', 'desc')->where('status', 1)->get();

        $model = [];
        VehicleRent::where('status', 1)
            ->latest()
            ->chunk(100, function ($rents) use (&$model) {
                foreach ($rents as $rent) {
                    $model[] = $rent;
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
                ->editColumn('effective_date', function ($model) {
                    return Carbon::parse($model->effective_date)->format('d, M Y');
                })
                ->editColumn('rent', function ($model) {
                    return '<span class="fw-semibold">'.number_format($model->rent).'</span>';
                })
                ->editColumn('vehicle_id', function ($model) {
                    return view('admin.fleet.vehicle_rents.vehicle_profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.fleet.vehicle_rents.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'vehicle_id', 'rent', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_rents.index', compact('title', 'data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'vehicle' => 'required',
            'rent' => 'required',
            'effective_date' => 'required',
            'note' => 'max:255',
        ]);

        try{
            $vehicle_rent = VehicleRent::where('vehicle_id', $request->vehicle)->where('status', 1)->first();
            if(!empty($vehicle_rent)){
                $vehicle_rent->end_date = date('Y-m-d');
                $vehicle_rent->status = 0;
                $vehicle_rent->save();
            }

            $insert = array(
                'vehicle_id' => $request->vehicle,
                'rent' => $request->rent,
                'effective_date' => $request->effective_date,
                'note' => $request->note,
            );

            VehicleRent::create($insert);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id){
        $model = VehicleRent::where('id', $id)->first();
        return (string) view('admin.fleet.vehicle_rents.show_content', compact('model'));
    }

    public function edit($id)
    {
        $data = [];
        $this->authorize('vehicle_rents-edit');
        $data['model'] = VehicleRent::where('id', $id)->first();
        $data['vehicles'] = Vehicle::orderby('id', 'desc')->where('status', 1)->get();
        return (string) view('admin.fleet.vehicle_rents.edit_content', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'vehicle' => 'required',
            'rent' => 'required',
            'effective_date' => 'required',
            'note' => 'max:255',
        ]);

        try{
            $vehicle_rent = VehicleRent::where('vehicle_id', $request->vehicle)->where('status', 1)->first();
            if(!empty($vehicle_rent)){
                $vehicle_rent->end_date = date('Y-m-d');
                $vehicle_rent->status = 0;
                $vehicle_rent->save();
            }

            $update = array(
                'vehicle_id' => $request->vehicle,
                'rent' => $request->rent,
                'effective_date' => $request->effective_date,
                'note' => $request->note,
                'status' => 1,
            );

            VehicleRent::find($id)->update($update);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->authorize('vehicle_rents-delete');
        $find = VehicleRent::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleRent";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Vehicle Rent has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Vehicle Rent Deleted');
                LogActivity::deleteHistory($historyArray);
                $onlySoftDeleted = VehicleRent::onlyTrashed()->count();
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

    public function status($id)
    {
        $model = VehicleRent::where('id', $id)->first();
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
        $title = 'All Trashed Vehicle Rents';

        $model = VehicleRent::orderby('id', 'desc')->onlyTrashed()->get();
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
                ->editColumn('effective_date', function ($model) {
                    return Carbon::parse($model->effective_date)->format('d, M Y');
                })
                ->editColumn('rent', function ($model) {
                    return 'PKR. '. number_format($model->rent);
                })
                ->editColumn('vehicle_id', function ($model) {
                    return view('admin.fleet.vehicle_rents.vehicle_profile', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('vehicle_rents.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'vehicle_id', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_rents.index', compact('title'));
    }
    public function restore($id)
    {
        $find = VehicleRent::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleRent";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Vehicle Rent has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function rentHistory($vehicle_id){
        $data = [];
        $data['models'] = VehicleRent::orderby('id', 'desc')->where('vehicle_id', $vehicle_id)->where('status', 0)->get();
        return (string) view('admin.fleet.vehicle_rents.show_rent_history', compact('data'));
    }
}
