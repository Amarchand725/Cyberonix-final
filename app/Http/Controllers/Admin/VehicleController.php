<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Models\Vehicle;
use App\Models\BodyType;
use App\Models\VehicleRent;
use App\Models\VehicleUser;
use App\Models\VehicleImage;
use App\Models\VehicleOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\VehicleInspection;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $this->authorize('vehicles-list');
        $title = 'All Vehicles';

        $data['vehicle_owners'] = VehicleOwner::where('status', 1)->latest()->get();
        $data['body_types'] = BodyType::where('status', 1)->get();

        // $model = Vehicle::orderby('id', 'desc')->get();

        $model = [];
        Vehicle::latest()
            ->chunk(100, function ($vehicles) use (&$model) {
                foreach ($vehicles as $vehicle) {
                    $model[] = $vehicle;
                }
            });

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
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->addColumn('rent', function ($model) {
                    if (!empty($model->hasRent->rent)) {
                        return '<span class="fw-semibold">' . number_format($model->hasRent->rent) . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('owner_id', function ($model) {
                    return view('admin.fleet.vehicles.owner_profile', ['model' => $model])->render();
                })
                ->editColumn('registration_number', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->registration_number . '</span>';
                })
                ->editColumn('name', function ($model) {
                    return view('admin.fleet.vehicles.vehicle_profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.fleet.vehicles.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'owner_id', 'name', 'rent', 'registration_number', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicles.index', compact('title', 'data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'owner_id' => 'required',
            'name' => 'required',
            // 'thumbnail' => 'required|max:2048',
            'body_type' => 'required',
            'model_year' => 'required',
            'color' => 'required',
            'transmission' => 'required',
            'engine_capacity' => 'required',
            'mileage' => 'required',
            'registration_province' => 'required',
            'registration_city' => 'required',
            'registration_number' => 'required',
            'rent' => 'required',
            'status' => 'required',
            'video' => 'max:51200'
        ]);

        try {
            $videoName = '';
            if ($request->file('video')) {
                $video = $request->file('video');
                $size = $video->getSize();
                $size = $size / (1024 * 1024); // convert into mb; 
                if ($size > 50) {
                    return response()->json(["error" =>  "Video file must be less than 50 MB!"]);
                }
                $videoName = rand() . '_vehicle.' . $video->getClientOriginalExtension();
                $video->move(public_path('upload/vehicle/video'), $videoName);
            }

            $thumbnailName = '';
            if ($request->file('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = rand() . '_vehicle.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('upload/vehicle/thumbnails'), $thumbnailName);
            }

            $insert = array(
                'owner_id' => $request->owner_id,
                'name' => $request->name,
                'thumbnail' => $thumbnailName,
                'body_type' => $request->body_type,
                'model_year' => $request->model_year,
                'color' => $request->color,
                'transmission' => $request->transmission,
                'engine_capacity' => $request->engine_capacity,
                'mileage' => $request->mileage,
                'registration_province' => $request->registration_province,
                'registration_city' => $request->registration_city,
                'registration_number' => $request->registration_number,
                'additional' => $request->additional ? $request->additional : '',
                'video' => $videoName,
                'status' => $request->status
            );
            $model = Vehicle::create($insert);

            if ($model && isset($request->rent)) {
                VehicleRent::create([
                    'vehicle_id' => $model->id,
                    'rent' => $request->rent,
                    'effective_date' => date('Y-m-d'),
                ]);
            }

            if ($model && $uploadImages = $request->file('images')) {
                foreach ($uploadImages as $uploadImage) {
                    $image = $uploadImage;
                    if (in_array($image->getClientOriginalName(), $request->input('image_names'))) {
                        $imageName = rand() . '_vehicle.' . $image->getClientOriginalExtension();
                        $image->move(public_path('upload/vehicle/images'), $imageName);
                        VehicleImage::create([
                            'vehicle_id' => $model->id,
                            'image' => $imageName,
                        ]);
                    }
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $model = Vehicle::where('id', $id)->first();
        return (string) view('admin.fleet.vehicles.show_content', compact('model'));
    }

    public function edit($id)
    {
        $data = [];
        $this->authorize('vehicles-edit');
        $data['model'] = Vehicle::where('id', $id)->first();
        $data['vehicle_owners'] = VehicleOwner::where('status', 1)->latest()->get();
        $data['body_types'] = BodyType::where('status', 1)->get();
        return (string) view('admin.fleet.vehicles.edit_content', compact('data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'owner_id' => 'required',
            'name' => 'required',
            'body_type' => 'required',
            'model_year' => 'required',
            'color' => 'required',
            'transmission' => 'required',
            'engine_capacity' => 'required',
            'mileage' => 'required',
            'registration_province' => 'required',
            'registration_city' => 'required',
            'registration_number' => 'required',
            'rent' => 'required',
            'status' => 'required',
            'video' => 'max:10240'
        ]);

        try {
            $model = Vehicle::where('id', $request->vehicle_id)->first();

            if ($request->file('video')) {
                $video = $request->file('video');
                $videoName = rand() . '_vehicle.' . $video->getClientOriginalExtension();
                $video->move(public_path('upload/vehicle/video'), $videoName);

                $model->video = $videoName;
            }

            if ($request->file('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = rand() . '_vehicle.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('upload/vehicle/thumbnails'), $thumbnailName);

                $model->thumbnail = $thumbnailName;
            }

            $model->owner_id = $request->owner_id;
            $model->name = $request->name;
            $model->body_type = $request->body_type;
            $model->model_year = $request->model_year;
            $model->color = $request->color;
            $model->transmission = $request->transmission;
            $model->engine_capacity = $request->engine_capacity;
            $model->mileage = $request->mileage;
            $model->registration_province = $request->registration_province;
            $model->registration_city = $request->registration_city;
            $model->registration_number = $request->registration_number;
            $model->additional = $request->additional;
            $model->status = $request->status;
            $model->save();

            if ($model && isset($request->rent)) {
                $vehicle_rent = VehicleRent::orderby('id', 'desc')->where('vehicle_id', $request->vehicle_id)->where('end_date', NULL)->first();
                if (!empty($vehicle_rent)) {
                    $vehicle_rent->rent = $request->rent;
                    $vehicle_rent->save();
                } else {
                    VehicleRent::create([
                        'vehicle_id' => $request->vehicle_id,
                        'rent' => $request->rent,
                        'effective_date' => date('Y-m-d'),
                    ]);
                }
            }

            if ($model && $uploadImages = $request->file('images')) {
                foreach ($uploadImages as $uploadImage) {
                    $image = $uploadImage;
                    if (in_array($image->getClientOriginalName(), $request->input('image_names'))) {
                        $imageName = rand() . '_vehicle.' . $image->getClientOriginalExtension();
                        $image->move(public_path('upload/vehicle/images'), $imageName);
                        VehicleImage::create([
                            'vehicle_id' => $request->vehicle_id,
                            'image' => $imageName,
                        ]);
                    }
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('vehicles-delete');
        if (isset($vehicle) && !empty($vehicle)) {
            $historyArray['model_id'] = $vehicle->id;
            $historyArray['model_name'] = "\App\Models\Vehicle";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Vehicle has been deleted";
            $model = $vehicle->delete();
            if ($model) {
                LogActivity::addToLog('Vehicle Deleted');
                LogActivity::deleteHistory($historyArray);
                $onlySoftDeleted = Vehicle::onlyTrashed()->count();
                return response()->json([
                    'status' => true,
                    'trash_records' => $onlySoftDeleted
                ]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function status($id)
    {
        $model = Vehicle::where('id', $id)->first();
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

    public function removeImage($id)
    {
        $model = VehicleImage::where('id', $id)->delete();
        if ($model) {
            return true;
        } else {
            return false;
        }
    }

    public function trashed(Request $request)
    {
        $title = 'All Trashed Vehicles';

        $model = Vehicle::orderby('id', 'desc')->onlyTrashed()->get();
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

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->addColumn('rent', function ($model) {
                    if (!empty($model->hasRent->rent)) {
                        return 'PKR. ' . number_format($model->hasRent->rent);
                    } else {
                        return '-';
                    }
                })
                ->editColumn('owner_id', function ($model) {
                    return view('admin.fleet.vehicles.owner_profile', ['model' => $model])->render();
                })
                ->editColumn('name', function ($model) {
                    return view('admin.fleet.vehicles.vehicle_profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="' . route('vehicles.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'owner_id', 'name', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicles.index', compact('title'));
    }
    public function restore($id)
    {
        $find = Vehicle::onlyTrashed()->where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Vehicle";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Vehicle has been restored";
            $restore = $find->restore();
            if (!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function inspectionsHistory($vehicle_id)
    {
        $data = [];
        $data['models'] = VehicleInspection::orderby('id', 'desc')->where('vehicle_id', $vehicle_id)->get();
        return (string) view('admin.fleet.vehicles.show_inspections_history', compact('data'));
    }

    public function usersHistory($vehicle_id)
    {
        $data = [];
        $data['models'] = VehicleUser::orderby('id', 'desc')->where('vehicle_id', $vehicle_id)->get();
        return (string) view('admin.fleet.vehicles.show_users_history', compact('data'));
    }
}
