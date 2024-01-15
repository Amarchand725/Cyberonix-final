<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\IpManagement;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IpManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("ip_management-list");
        $title = 'All Ip Addresses';
        $model = [];
        IpManagement::latest()
            ->chunk(100, function ($ipAddresses) use (&$model) {
                foreach ($ipAddresses as $ipAddress) {
                    $model[] = $ipAddress;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('ip_address', function ($model) {
                    return $model->ip_address ?? "-";
                })
                ->addColumn('status', function ($model) {
                    if ($model->status == 1) {
                        return '<span class="text-success fw-semibold">Allow</span>';
                    } else {
                        return '<span class="text-danger fw-semibold">Black List</span>';
                    }
                })
                ->addColumn('date', function ($model) {
                    return !empty($model->created_at) ?  $model->created_at->format("M d,Y / h:i A") : "-";
                })
                ->addColumn('action', function ($model) {
                    return view('admin.ip-managements.action', ['model' => $model])->render();
                })
                ->rawColumns(['ip_address', 'status', 'date', 'action'])
                ->make(true);
        }

        return view('admin.ip-managements.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("ip_management-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("ip_management-create");
        $this->validate($request, [
            'ip_address' => 'required|unique:ip_managements|ip',
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $model = IpManagement::create([
                'ip_address' => $request->ip_address ?? null,
                'status' => $request->status ?? 0
            ]);
            if ($model) {
                DB::commit();
                LogActivity::addToLog('New Ip Address Added');
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize("ip_management-edit");
        $model = IpManagement::where('id', $id)->first();
        return (string) view('admin.ip-managements.edit_content', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("ip_management-edit");
        $this->validate($request, [
            'ip_address' => 'required|ip|unique:ip_managements,ip_address,' . $id,
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $find = IpManagement::where('id', $id)->first();
            if (isset($find) && !empty($find)) {
                $update = $find->update([
                    'ip_address' => $request->ip_address ?? null,
                    'status' => $request->status ?? 0,
                ]);
                if ($update > 0) {
                    DB::commit();
                    LogActivity::addToLog('Ip Address Updated');
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }
            } else {
                return response()->json(['error' => 'Data Not Found']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize("ip_management-delete");
        $find = IpManagement::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\IpManagement";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Ip has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Ip Deleted');
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
        $this->authorize("ip_management-trashed");
        $title = 'All Trashed Ip Addresses';
        $trashed = true;
        if ($request->ajax()) {
            $model = IpManagement::onlyTrashed();
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('ip_address', function ($model) {
                    return $model->ip_address ?? "-";
                })
                ->addColumn('status', function ($model) {
                    if ($model->status == 1) {
                        return '<span class="text-success fw-semibold">Allow</span>';
                    } else {
                        return '<span class="text-danger fw-semibold">Black List</span>';
                    }
                })
                ->addColumn('date', function ($model) {
                    return !empty($model->created_at) ?  $model->created_at->format("M d,Y / h:i A") : "-";
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="javascript:;" class="btn btn-icon btn-label-info waves-effect restoreBtn" data-route="' . route('ip-managements.restore', $model->id) . '">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['ip_address', 'status', 'date', 'action'])
                ->make(true);
        }
        return view('admin.ip-managements.index', compact('title', 'trashed'));
    }

    public function restore($id)
    {
        $this->authorize("ip_management-restore");
        $find = IpManagement::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\IpManagement";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Ip has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return response()->json(['success' =>  true, 'message' => 'Record Restored Successfully']);
            }
        } else {
            return false;
        }

    }
}
