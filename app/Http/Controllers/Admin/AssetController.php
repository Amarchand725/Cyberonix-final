<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetDamage;
use App\Models\AssetDetail;
use App\Models\AssetHistory;
use App\Models\AssetUser;
use App\Models\AssetUserHistory;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("assets-list");
        $data['title'] = 'Assets ';
        $model = [];
        Asset::latest()
            ->chunk(100, function ($announcements) use (&$model) {
                foreach ($announcements as $announcement) {
                    $model[] = $announcement;
                }
            });
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('category', function ($model) {
                    return $model->category->name ?? '-';
                })
                ->addColumn('name', function ($model) {
                    return $model->name ?? "-";
                })
                ->addColumn('quantity', function ($model) {
                    $model =  $model->quantity ?? "0";
                    return $model;
                })
                ->addColumn('assigned', function ($model) {

                    $model  =  checkassignedAssets($model) ?? 0;
                    return $model;
                })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('action', function ($model) {
                    return view('admin.assets.action', ['model' => $model])->render();
                })
                ->rawColumns(['name', 'status', 'date', 'action'])
                ->make(true);
        }

        $data['assignedCount'] = AssetDetail::whereHas("asset")->whereNotNull("assignee")->where(function($query) {
            $query->where("is_damage", null)->orWhere("is_damage", 2);
        })->pluck("id")->toArray();


        $data['unassigned'] = AssetDetail::whereHas("asset")->whereNull("assignee")->where(function($query) {
            $query->where("is_damage", null)->orWhere("is_damage", 2);
        })->pluck("id")->toArray();





        $data['total'] = AssetDetail::whereHas("asset")->whereNull("assignee")->where(function($query) {
            $query->where("is_damage", null)->orWhere("is_damage", 2);
        })->pluck("id")->toArray();


        $data['damageCount'] = AssetDetail::whereHas("asset")->where("is_damage", 1)->pluck("id")->toArray();


        $data['totalAssetSummary'] = AssetDetail::whereHas("asset")->where("is_damage", "!=", 1)->orderby("id", "asc")->pluck("id")->toArray();
        return view('admin.assets.index', $data);
    }
    public function create()
    {
        $this->authorize("assets-create");
    }
    public function store(Request $request)
    {
        $this->authorize("assets-create");
        $this->validate($request, [
            'category_id' => 'required|integer',
            'name' => 'required|max:255',
            'quantity' => 'required|numeric',
            'price' => 'required',
        ], [
            "category_id" => "Category field is required",
        ]);
        DB::beginTransaction();
        try {
            $store = Asset::create([
                'name' => $request->name,
                'quantity' => $request->quantity,
                'creator_id' => Auth::user()->id,
                // 'asset_uid' => !empty($request->name) ?  generateAssetUID($request->name) :  "",
                'category_id' => $request->category_id,
                'status' => $request->status ?? 1,
            ]);
            if ($store->id) {
                // save Asset details ( individual entries of each item with Unique ID )
                for ($i = 1; $i <= $request->quantity; $i++) {
                    AssetDetail::create([
                        "asset_id" => $store->id,
                        "uid" => !empty($request->name) ?  generateAssetUID($request->name) :  "",
                        "price" => $request->price ?? 0,
                    ]);
                }
                // save Asset details ( individual entries of each item with Unique ID )
                // save history
                // $saveHistory = AssetHistory::create([
                //     "created_by" => Auth::user()->id ?? '',
                //     "asset_id" => $store->id,
                //     "quantity" => $request->quantity ?? 1,
                //     "type" => 1, //induction
                //     "remarks" => "Quantity increased",
                // ]);
                updateAssetHistory($store->id, $request->quantity ?? 1, 1, "Quantity Increased");
                DB::commit();
                LogActivity::addToLog('New Asset Added');
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function show(string $id)
    {
        $this->authorize("assets-show");
        $data['show'] = Asset::where("id", $id)->first();
        if (!empty($data['show'])) {
            $data['title'] = $data['show']->name ??  " " . " Details";
            return view("admin.assets.show", $data);
        } else {
            return redirect()->back()->with("error", "Asset Details not found!");
        }
    }
    public function edit(string $id)
    {
        $this->authorize("assets-edit");
        $model = Asset::where('id', $id)->first();
        return (string) view('admin.assets.edit_content', compact('model'));
    }
    public function update(Request $request, string $id)
    {
        $this->authorize("assets-edit");
        $this->validate($request, [
            'category_id' => 'required|integer',
            'name' => 'required|max:255',
            'quantity' => 'required|numeric',
        ], [
            "category_id" => "Category field is required",
        ]);
        DB::beginTransaction();
        try {
            $find = Asset::where('id', $id)->first();
            if (!empty($find)) {
                $update = $find->update([
                    'name' => $request->name ?? '',
                    'quantity' => $request->quantity ?? '',
                    'category_id' => $request->category_id ?? '',
                    'status' => $request->status ?? 1,
                ]);
                if ($update > 0) {
                    $find = Asset::with('assetHistory')->where('id', $id)->first();
                    if (!empty($find->assetHistory)) {
                        if ($find->assetHistory->quantity > $request->quantity) {
                            $type = 2;
                            $message = "Quantity Decreased";
                        } else {
                            $type = 1;
                            $message = "Quantity Increased";
                        }
                        $updateHistory = $find->assetHistory->update([
                            "asset_id" => $id,
                            "quantity" => $request->quantity ?? 1,
                            "type" => $type  ?? null, //induction
                            "remarks" => $message ?? null,
                            "last_updated_by" => Auth::user()->id ?? ''
                        ]);
                    } else {
                        $saveHistory = AssetHistory::create([
                            "created_by" => Auth::user()->id ?? '',
                            "asset_id" => $id,
                            "quantity" => $request->quantity ?? 1,
                            "type" => 1, //induction
                            "remarks" => "Quantity increased",
                        ]);
                    }


                    DB::commit();
                    LogActivity::addToLog('Asset Updated');
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function destroy(string $id)
    {
        $this->authorize("assets-delete");
        $find = Asset::where("id", $id)->first();

        if (!empty($find)) {
            $deleteArray['model_id'] = $find->id;
            $deleteArray['model_name'] = "\App\Models\Asset";
            $deleteArray['type'] = "1";
            $deleteArray['remarks'] = "Asset has been deleted";
            $model = $find->delete();
            if ($model) {
                LogActivity::addToLog('Asset Deleted');
                LogActivity::deleteHistory($deleteArray);
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
    public function trashed(Request $request)
    {
        $this->authorize("assets-trashed");
        $model = Asset::onlyTrashed()->latest()->get();
        $title = 'All Trashed Assets';
        $trashed = true;
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('category', function ($model) {
                    return $model->category->name ?? '-';
                })
                ->addColumn('name', function ($model) {
                    return $model->name ?? "-";
                })
                ->addColumn('quantity', function ($model) {
                    return $model->quantity ?? "0";
                })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="javascript:;" data-route="' . route('assets.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect restore-btn" data-id="' . $model->id . '">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['name', 'status', 'date', 'action'])
                ->make(true);
        }

        return view('admin.assets.assets-trashed', compact('title', 'trashed'));
    }
    public function addMore(Request $request, $id)
    {
        $model = Asset::where('id', $id)->first();
        return (string) view('admin.assets.add_more_content', compact('model'));
    }
    public function addMoreUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'quantity' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $asset = Asset::with('lastDetail')->where('id', $id)->first();
            if (!empty($asset)) {
                $newQuantity = (int)$request->quantity + (int)$asset->quantity ?? 0;
                $update = $asset->update([
                    'quantity' => $newQuantity ?? 0,
                ]);
                if ($update > 0) {
                    // save Asset details ( individual entries of each item with Unique ID )
                    for ($i = 1; $i <= $request->quantity; $i++) {
                        AssetDetail::create([
                            "asset_id" => $asset->id,
                            "uid" => !empty($asset->name) ?  generateAssetUID($asset->name) :  "",
                            "price" => $asset->lastDetail->price ?? 0,
                        ]);
                    }
                    // save Asset details ( individual entries of each item with Unique ID )


                    $saveHistory = AssetHistory::create([
                        "created_by" => Auth::user()->id ?? '',
                        "asset_id" => $id,
                        "quantity" => $request->quantity ?? 1,
                        "type" => 1, //induction
                        "remarks" => "Quantity increased",
                    ]);
                }

                DB::commit();
                LogActivity::addToLog('Asset Updated');
                return response()->json(['success' => true]);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function restore($id)
    {
        $this->authorize("assets-restore");
        $check = Asset::onlyTrashed()->where('id', $id)->first();
        if (!empty($check)) {
            $deleteArray['model_id'] = $check->id;
            $deleteArray['model_name'] = "\App\Models\Asset";
            $deleteArray['type'] = "2";
            $deleteArray['remarks'] = "Asset has been restored";
            $restore = $check->restore();
            if (!empty($restore)) {
                LogActivity::deleteHistory($deleteArray);
                return response()->json([
                    'status' => true,
                ]);
            }
        } else {
            return false;
        }
    }
    public function assign(Request $request, $id)
    {
        $this->authorize("assets-assign");
        $data['model'] = AssetDetail::where('id', $id)->first();
        $data['users'] = User::where("status", 1)->get();
        $data['date'] = Carbon::now()->toDateString();
        return (string) view('admin.assets.assign_to_user_content', $data);
    }
    public function assignUpdate(Request $request, $id)
    {
        $this->authorize("assets-assign");
        $this->validate($request, [
            'employee_id' => 'required',
            'effective_date' => 'required|date'
        ]);
        DB::beginTransaction();
        try {
            $detail = AssetDetail::where("id", $id)->first();
            if (!empty($detail)) {
                $asset = $detail->asset;
                $employee = getUser($request->employee_id);
                if (!empty($asset)) {
                    if (!empty($detail->assignee)) {
                        return response()->json(['success' => false, 'message' => 'Item: "' . $asset->name . '" is currently assigned to Employee: "' . getUserName($detail->assignee) . '" ']);
                    }
                    $quantity  = 1;
                    $updateQuantity = $asset->quantity - 1;
                    $uid = $detail->uid;
                    $detail->update([
                        'assignee' => $employee->id,
                        'remarks' => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                    ]);
                    $assign = AssetUser::create([
                        "asset_detail_id" => $detail->id ?? null,
                        "employee_id" => $employee->id ?? null,
                        "assigned_by" => Auth::user()->id ?? null,
                        "assigned_at" => Carbon::now()->toDateTimeString(),
                        "effective_date" => $request->effective_date ?? null,
                        "status" => 1,
                        'remarks' => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                    ]);
                    if (!empty($assign)) {
                        // save history
                        $history = AssetUserHistory::create([
                            "asset_user_id" => $assign->id ?? null,
                            "asset_detail_id" => $detail->id ?? null,
                            "employee_id" => $employee->id ?? null,
                            "creator_id" => $assign->assigned_by ?? null,
                            "date" => Carbon::now()->toDateTimeString(),
                            "remarks" => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                            "type" => 1,
                        ]);
                        if ($history->id) {
                            // $saveHistory = AssetHistory::create([
                            //     "created_by" => Auth::user()->id ?? '',
                            //     "asset_id" => $id,
                            //     "quantity" => $quantity,
                            //     "type" => 2, //deduction
                            //     "remarks" => "Quantity Decreased",
                            // ]);
                            updateAssetHistory($id, 1, 2, "Quantity Decreased");
                        }
                    }

                    $asset->update(["quantity" => $updateQuantity]);
                    DB::commit();
                    LogActivity::addToLog('Asset Updated');
                    return response()->json(['success' => true]);
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function assignedList(Request $request)
    {
        $this->authorize("assets-assignee-list");
        $data['title'] = 'Assigned Assets';
        $model = [];
        AssetUser::latest()
            ->chunk(100, function ($assets) use (&$model) {
                foreach ($assets as $asset) {
                    $model[] = $asset;
                }
            });
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('category', function ($model) {
                    return $model->asset->category->name ?? '-';
                })
                ->addColumn('name', function ($model) {
                    return $model->asset->name ?? "-";
                })
                ->addColumn('assignBy', function ($model) {
                    return  userWithHtml($model->assignBy) ?? "-";
                })
                ->addColumn('assignee', function ($model) {
                    return userWithHtml($model->user) ?? "-";
                })
                ->addColumn('date', function ($model) {
                    return !empty($model->effective_date) ? formatDate($model->effective_date)  : '-';
                })
                ->addColumn('action', function ($model) {
                    return view('admin.assets.assignedActions', ['model' => $model])->render();
                })
                ->rawColumns(['name', 'assignBy', 'assignee', 'status', 'date', 'action'])
                ->make(true);
        }
        return view('admin.assets.assignedAssets', $data);
    }
    public function removeAssignee(Request $request, $id)
    {
        $this->authorize("assets-unassign");
        $find = AssetUser::where("id", $id)->first();
        if (!empty($find)) {
            $asset = $find->asset;
            $quantity = $asset->quantity + 1;
            $asset->update(['quantity' => $quantity]);
            $model = $find->delete();
            if ($model) {
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
    public function getChildAsset(Request $request, $id)
    {
        $this->authorize("assets-show");
        $model = [];
        AssetDetail::with('assigneeUser')->where("asset_id", $id)->orderby("id", "asc")
            ->chunk(100, function ($assets) use (&$model) {
                foreach ($assets as $asset) {
                    $model[] = $asset;
                }
            });
        if ($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('uid', function ($model) {
                    return   $model->uid ?? '-';
                })
                ->addColumn('price', function ($model) {
                    return $model->price ?? "-";
                })
                ->addColumn('assignee', function ($model) {
                    if (!empty($model->assigneeUser)) {
                        return userWithHtml($model->assigneeUser);
                    } else {
                        return '<span style="font-weight:bold;" class="badge bg-label-danger" > Un-Assigned </span>';
                    }
                })
                ->addColumn('damage', function ($model) {
                    if (!empty($model->is_damage) && $model->is_damage == 1) {
                        return '<span class="badge bg-label-danger">YES</span>';
                    } else {
                        return '<span class="badge bg-label-success">NO</span>';
                    }
                })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('action', function ($model) {
                    $assetId =  $model->id ?? '';
                    $parentAssetId = $model->asset->id ?? '';
                    $action = "";
                    $action .= '<div class="row"><div class="col-md-2"><div class="d-flex align-items-center">
                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                    <div class="dropdown-menu dropdown-menu-end m-0">';
                    if ($model->is_damage != 1) {
                        if (empty($model->assigneeUser) || $model->assigneeUser == "null") {
                            $action .= '<a href="javascript:;" data-placement="top" title="Assign to an employee"  class="dropdown-item assign-asset" data-slug="' . $model->id . '"  data-edit-url="' . route('assets.assign', $model->id) . '"  data-url="' . route('assets.assignUpdate', $model->id) . '" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">Assign</a>';
                        }
                        if (!empty($model->assigneeUser)) {
                            $action .= '<a href="javascript:;" class="dropdown-item un-assign" data-id="' . $model->id . '" data-del-url="">Un Assign</a>';
                        }
                        $action .= '<a href="javascript:;" class="dropdown-item mark-as-damage"  data-asset-detail-id="' . $model->id . '"  >Mark as Damage</a>';
                    }
                    $action .= '<a href="' . route('assets.assetAssigneeLogs', $model->id) . '" class="dropdown-item  "  target="_blank"  >View Logs</a>';
                    $action .= '</div></div></div>';

                    // if ($model->is_damage == 1) {
                    //     $action .= '<div class="col-md-8"><span class="badge bg-label-danger" text-capitalized="">Damaged Item</span></div>';
                    // }
                    // $action .= '</div>';
                    return $action;
                })
                ->rawColumns(['uid', 'price', 'damage', 'assignee', 'date', 'action'])
                ->make(true);
        }
    }
    public function unassignModal(Request $request)
    {
        $this->authorize("assets-unassign");
        $data['asset'] = AssetDetail::where("id", $request->id)->first();
        return view("admin.assets.unassign_from_user_content", $data);
    }
    public function unassignedFromEmployee(Request $request)
    {

        $this->authorize("assets-unassign");
        try {
            $asset = AssetDetail::where("id", $request->id)->first();
            if (!empty($asset) && !empty($asset->asset)) {
                $parentAsset = $asset->asset;
                $assignee = $asset->assignee;
                $assetUser = AssetUser::where("asset_detail_id", $asset->id)->where('employee_id', $assignee)->where("status", 1)->orderby("id", "desc")->first();
                if (!empty($assetUser)) {
                    AssetUserHistory::create([
                        "asset_user_id" => $assetUser->id ?? null,
                        "asset_detail_id" => $asset->id ?? null,
                        "employee_id" => $assignee ?? null,
                        "creator_id" => Auth::user()->id ?? null,
                        "date" => Carbon::now()->toDateTimeString() ?? null,
                        "remarks" => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                        "type" => 2,
                    ]);
                    if ($request->damage == 1) {
                        AssetDamage::create([
                            "creator_id" => Auth::user()->id,
                            "asset_detail_id" => $asset->id ?? null,
                            "last_assignee" => $assetUser->employee_id ?? null,
                            "return_date" => Carbon::now()->toDateString(),
                            "remarks" => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                        ]);
                    }

                    $assetUser->update([
                        'status' => 0,
                        'end_date' => Carbon::now()->toDateTimeString(),
                        'unassigned_by' => Auth::user()->id,
                        'unassigned_at' => Carbon::now()->toDateTimeString(),
                        'is_damage' => $request->damage ?? 2,
                        'remarks' => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                    ]);
                }
                if ($request->damage == 2) {
                    $updated_quantity = $parentAsset->quantity + 1;
                    updateAssetHistory($parentAsset->id, 1, 1, "Quantity Increased");
                    $parentAsset->update([
                        "quantity" => $updated_quantity,
                    ]);
                }

                $asset->update([
                    "assignee" => null,
                    "remarks" => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                    "is_damage" => $request->damage ?? 2,
                ]);
                LogActivity::addToLog('Asset Updated');
                return apiResponse(true, "Unassigned from this employee", null, 200);
            }
        } catch (Exception $e) {
            return apiResponse(true, $e->getMessage(), null, 200);
        }
    }
    public function assetAssigneeLogs(Request $request, $id)
    {
        $this->authorize("assets-view-logs");
        $data['title']  = "Asset Logs";
        $data['show'] = AssetDetail::where('id', $id)->first();
        if (!empty($data['show'])) {
            return  view('admin.assets.assignee_history', $data);
        } else {
            return redirect()->back()->with("error", "Asset Logs Not Found!");
        }
    }
    public function assetAssigneeLogsList(Request $request, $id)
    {
        $this->authorize("assets-view-logs");
        $model = [];
        $assetDetail = AssetDetail::where("id", $id)->first();
        AssetUser::where("asset_detail_id", $assetDetail->id)->latest()
            ->chunk(100, function ($assets) use (&$model) {
                foreach ($assets as $asset) {
                    $model[] = $asset;
                }
            });
        if ($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('user', function ($model) {
                    $user = !empty($model->user) ? userWithHtml($model->user) : "-";
                    return $user;
                })
                ->addColumn('assignBy', function ($model) {
                    $assignBy = !empty($model->assignBy)  ? userWithHtml($model->assignBy) : "-";
                    return $assignBy;
                })
                ->addColumn('effective_date', function ($model) {
                    $date = !empty($model->effective_date)  ? formatDate($model->effective_date) : "-";
                    return $date;
                })
                ->addColumn('end_date', function ($model) {
                    $date = !empty($model->end_date)  ? formatDate($model->end_date) : "-";
                    return $date;
                })
                ->addColumn('status', function ($model) {
                    if (!empty($model->status) && $model->status == 1) {
                        $status = '<span class="badge bg-label-success">Assigned</span>';
                    } else {
                        $status = '<span class="badge bg-label-danger">Not Assigned</span>';
                    }
                    return $status;
                })
                ->addColumn('is_damage', function ($model) {
                    if (!empty($model->is_damage) && $model->is_damage == 1) {
                        $damage = '<span class="badge bg-label-danger">YES</span>';
                    } else {
                        $damage = '<span class="badge bg-label-success">NO</span>';
                    }
                    return $damage;
                })
                ->addColumn('remarks', function ($model) {
                    $remarks = !empty($model->remarks)  ? $model->remarks : "-";
                    return $remarks;
                })
                ->addColumn('last_updated', function ($model) {
                    $date = !empty($model->updated_at)  ? formatDateTime($model->updated_at) : "-";
                    return $date;
                })->addColumn('action', function ($model) {
                    $action = "";
                    $action .= '<div class="d-flex align-items-center">
                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                    <div class="dropdown-menu dropdown-menu-end m-0">';
                    $action .= '<a href="javascript:;" class="dropdown-item  asset-user-timeline" data-route="' . route('assets.assetUsertimeline', $model->id) . '">Timeline</a>';
                    $action .= '</div></div>';
                    return $action;
                })
                ->rawColumns(['user', 'assignBy', 'effective_date', 'end_date', 'status', 'is_damage', 'remarks', 'last_updated', 'action'])
                ->make(true);
        }
    }
    public function assetUsertimeline(Request $request, $id)
    {
        $this->authorize("assets-view-logs");
        $data['histories'] = AssetUserHistory::where("asset_user_id", $id)->orderby("id", "asc")->get();
        if (!empty($data['histories'])) {
            $assetUser = AssetUser::where("id", $id)->first();
            if (!empty($assetUser)) {
                $data['damage'] = AssetDamage::where("asset_detail_id", $assetUser->asset_detail_id)->first();
            }
            return view("admin.assets.assignee_timeline", $data);
        } else {
            return false;
        }
    }
    public function markAsDamage(Request $request)
    {
        $this->authorize("assets-mark_as_damaged");
        $assetDetail = AssetDetail::where("id", $request->id)->first();
        try {
            if (!empty($assetDetail)) {
                $assetUser = AssetUser::where("asset_detail_id", $assetDetail->id)->where('employee_id', $assetDetail->assignee)->where("status", 1)->orderby("id", "desc")->first();
                if (!empty($assetUser)) {
                    AssetUserHistory::create([
                        "asset_user_id" => $assetUser->id ?? null,
                        "asset_detail_id" => $assetDetail->id ?? null,
                        "employee_id" => $assetUser->employee_id ?? null,
                        "creator_id" => Auth::user()->id ?? null,
                        "date" => Carbon::now()->toDateTimeString() ?? null,
                        "remarks" => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                        "type" => 2,
                    ]);
                    $assetUser->update([
                        'status' => 0,
                        'end_date' => Carbon::now()->toDateTimeString(),
                        'unassigned_by' => Auth::user()->id,
                        'unassigned_at' => Carbon::now()->toDateTimeString(),
                        'is_damage' => 1,
                        'remarks' => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                    ]);
                } else {
                    $updated_quantity = $assetDetail->asset->quantity - 1;
                    updateAssetHistory($assetDetail->asset->id, 1, 2, "Quantity Decreased");
                    $assetDetail->asset->update([
                        "quantity" => $updated_quantity,
                    ]);
                }
                AssetDamage::create([
                    "creator_id" => Auth::user()->id,
                    "asset_detail_id" => $assetDetail->id ?? null,
                    "last_assignee" => $assetUser->employee_id ?? null,
                    "return_date" => Carbon::now()->toDateString(),
                    "remarks" => $request->remarks ?? "SYSTEM GENERATED REMARKS",
                ]);
                $assetDetail->update([
                    "is_damage" => 1,
                    "remarks" => $request->remarks ?? "SYSTEM GENERATE REMARKS",
                    "assignee" => null,
                ]);
                LogActivity::addToLog('Asset Marked as damage');
                return apiResponse(true, "Marked as damage", null, 200);
            }
        } catch (Exception $e) {
            return apiResponse(true, $e->getMessage(), null, 200);
        }
    }
}
