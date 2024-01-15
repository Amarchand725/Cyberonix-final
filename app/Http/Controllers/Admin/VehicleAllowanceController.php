<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Models\User;
use App\Models\VehicleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\VehicleAllowance;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ImportantNotification;
use Auth;

class VehicleAllowanceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('vehicle_allowances-list');
        $data = [];

        $title = 'All Vehicle Allowances';

        //All Employees who have vehicles already
        $all_vehicle_users = VehicleUser::where('end_date', NULL)->where('status', 1)->get();
        $vehicle_and_allowance_employees = [];
        foreach ($all_vehicle_users as $vehicle_user) {
            $vehicle_and_allowance_employees[] = $vehicle_user->user_id;
        }

        //All Employees who have vehicle allowances already
        $all_vehicle_allowance_users = VehicleAllowance::where('end_date', NULL)->where('status', 1)->get();
        foreach ($all_vehicle_allowance_users as $allowance_vehicle_user) {
            $vehicle_and_allowance_employees[] = $allowance_vehicle_user->user_id;
        }

        //Getting employees who have not booked in vehicle or allowance already
        // $data['users'] = User::whereNotIn('id', $vehicle_and_allowance_employees)->where('is_employee', 1)->where('status', 1)->get();

        $data['users'] = User::where('is_employee', 1)->where('status', 1)->get();

        // $model = VehicleAllowance::orderby('id', 'desc')->get();

        $model = [];
        VehicleAllowance::latest()
            ->chunk(100, function ($vehicle_allowances) use (&$model) {
                foreach ($vehicle_allowances as $vehicle_allowance) {
                    $model[] = $vehicle_allowance;
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
                ->editColumn('effective_date', function ($model) {
                    if (!empty($model->effective_date)) {
                        return Carbon::parse($model->effective_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('end_date', function ($model) {
                    if (!empty($model->end_date)) {
                        return Carbon::parse($model->end_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('allowance', function ($model) {
                    $column = "";
                    $column .= '<span class="fw-semibold">';
                    if (!empty($model->currency_code)) {
                        $currencyCode = $model->currency_code . " ";
                    } else {
                        $currencyCode = "PKR ";
                    }
                    $column .= $currencyCode;
                    $column .=  !empty($model->allowance) ?  number_format($model->allowance) : 0;
                    $column .= '</span>';
                    return $column;
                })
                ->editColumn('vehicle', function ($model) {
                    return '<span class="text-primary fw-semibold text-capitalize">' . $model->vehicle . '</span>';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.fleet.vehicle_allowances.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.fleet.vehicle_allowances.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'user_id', 'vehicle', 'allowance', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_allowances.index', compact('title', 'data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'vehicle' => 'required',
            'allowance' => 'required',
            'effective_date' => 'required',
        ]);
        try {
            $insert = array(
                'vehicle' => $request->vehicle,
                'user_id' => $request->user,
                'allowance' => $request->allowance,
                'effective_date' => $request->effective_date,
                'note' => $request->note ? $request->note : '',
                'currency_code' => $request->currency ?? "PKR",
                'currency_rate' => $request->conversion_rate ?? null,
                'conversion_amount' => $request->conversion_amount_hidden ?? null,
            );

            $model = VehicleAllowance::create($insert);

            $login_user = Auth::user();
            $notification_data = [
                'id' => $model->id,
                'date' => $model->effective_date,
                'type' => 'Vehicle Allowance',
                'name' => $login_user->first_name . ' ' . $login_user->last_name,
                'profile' => $login_user->profile->profile,
                'title' => 'You have awarded with car allowance.',
                'reason' => 'Awarded with car allowance.',
            ];

            if (isset($notification_data) && !empty($notification_data)) {
                $model->hasUser->notify(new ImportantNotification($notification_data));
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->authorize('vehicle_allowances-delete');
        $find = VehicleAllowance::where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleAllowance";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Vehicle Allowance has been deleted";
            $model = $find->delete();
            if ($model) {
                LogActivity::addToLog('Vehicle Allowance Deleted');
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

    public function show($id)
    {
        $model = VehicleAllowance::where('id', $id)->first();
        return (string) view('admin.fleet.vehicle_allowances.show_content', compact('model'));
    }

    public function edit($id)
    {
        $data = [];
        $this->authorize('vehicle_allowances-edit');
        $data['model'] = VehicleAllowance::where('id', $id)->first();
        $data['users'] = User::orderby('id', 'desc')->where('is_employee', 1)->get();
        return (string) view('admin.fleet.vehicle_allowances.edit_content', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'user' => 'required',
            'vehicle' => 'required',
            'allowance' => 'required',
            'effective_date' => 'required',
        ]);
        try {
            $model = VehicleAllowance::where('id', $id)->first();
            $model->vehicle = $request->vehicle;
            $model->user_id = $request->user;
            $model->allowance = $request->allowance;
            $model->effective_date = $request->effective_date;
            $model->note = $request->note;
            $model->currency_code  =  $request->currency ?? "PKR";
            $model->currency_rate  =  $request->conversion_rate ?? null;
            $model->conversion_amount  =  $request->conversion_amount_hidden ?? null;
            $model->save();

            $login_user = Auth::user();
            $notification_data = [
                'id' => $model->id,
                'date' => $model->effective_date,
                'type' => 'Vehicle Allowance',
                'name' => $login_user->first_name . ' ' . $login_user->last_name,
                'profile' => $login_user->profile->profile,
                'title' => 'Your car allowance has been updated.',
                'reason' => 'Updated.',
            ];

            if (isset($notification_data) && !empty($notification_data)) {
                $model->hasUser->notify(new ImportantNotification($notification_data));
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function status($id)
    {
        $model = VehicleAllowance::where('id', $id)->first();
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

    public function trashed(Request $request)
    {
        $title = 'All Trashed Allowances';
        $model = VehicleAllowance::onlyTrashed();

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
                ->editColumn('effective_date', function ($model) {
                    if (!empty($model->effective_date)) {
                        return Carbon::parse($model->effective_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('end_date', function ($model) {
                    if (!empty($model->end_date)) {
                        return Carbon::parse($model->end_date)->format('d, M Y');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('allowance', function ($model) {
                    $column = "";
                    $column .= '<span class="fw-semibold">';
                    if (!empty($model->currency_code)) {
                        $currencyCode = $model->currency_code . " ";
                    } else {
                        $currencyCode = "PKR ";
                    }
                    $column .= $currencyCode;
                    $column .=  !empty($model->allowance) ?  number_format($model->allowance) : 0;
                    $column .= '</span>';
                    return $column;
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.fleet.vehicle_allowances.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="' . route('vehicle_allowances.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'user_id', 'action'])
                ->make(true);
        }

        return view('admin.fleet.vehicle_allowances.index', compact('title'));
    }
    public function restore($id)
    {
        $find = VehicleAllowance::onlyTrashed()->where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\VehicleAllowance";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Vehicle Allowance has been restored";
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
