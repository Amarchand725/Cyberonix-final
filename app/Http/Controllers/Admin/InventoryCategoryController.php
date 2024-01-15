<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("inventory_category-list");
        $title = 'Inventory Categories';
        $model = [];
        InventoryCategory::latest()
            ->chunk(100, function ($announcements) use (&$model) {
                foreach ($announcements as $announcement) {
                    $model[] = $announcement;
                }
            });
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('name', function ($model) {
                    return $model->name ?? "-";
                })
                ->addColumn('status', function ($model) {
                    if ($model->status == 1) {
                        return '<span class="badge bg-label-success fw-semibold">Active</span>';
                    } else {
                        return '<span class="badge bg-label-danger fw-semibold">In-Active</span>';
                    }
                })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('action', function ($model) {
                    return view('admin.inventory-categories.action', ['model' => $model])->render();
                })
                ->rawColumns(['name', 'status', 'date', 'action'])
                ->make(true);
        }

        return view('admin.inventory-categories.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("inventory_category-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("inventory_category-create");
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        DB::beginTransaction();

        try {
            $model = InventoryCategory::create([
                'name' => $request->name,
                'status' => $request->status ?? 1,

            ]);
            DB::commit();
            LogActivity::addToLog('New Category Added');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize("inventory_category-edit");
        $model = InventoryCategory::where('id', $id)->first();
        return (string) view('admin.inventory-categories.edit_content', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("inventory_category-edit");
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        DB::beginTransaction();
        try {
            $find = InventoryCategory::where('id', $id)->first();
            if (!empty($find)) {
                $update = $find->update([
                    'name' => $request->name ?? '',
                    'status' => $request->status ?? 0,
                ]);
                if ($update > 0) {
                    DB::commit();
                    LogActivity::addToLog('Inventory Category Updated');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize("inventory_category-delete");
        $find = InventoryCategory::where("id", $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\InventoryCategory";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Inventory Category has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Inventory Category Deleted');
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
        $this->authorize("inventory_category-trashed");
        $model = InventoryCategory::onlyTrashed()->latest()->get();
        $title = 'All Trashed Categories';
        $trashed = true;
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('name', function ($model) {
                    return $model->name ?? "-";
                })
                ->addColumn('status', function ($model) {
                    if ($model->status == 1) {
                        return '<span class="badge bg-label-success fw-semibold">Active</span>';
                    } else {
                        return '<span class="badge bg-label-danger fw-semibold">In-Active</span>';
                    }
                })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="javascript:;" data-route="' . route('inventory-category.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect restore-btn" data-id="' . $model->id . '">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['name', 'status', 'date', 'action'])
                ->make(true);
        }

        return view('admin.inventory-categories.index', compact('title', 'trashed'));
    }
    public function restore($id)
    {

        $this->authorize("inventory_category-restore");
        $find = InventoryCategory::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\InventoryCategory";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Inventory Category has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return response()->json([
                    'status' => true,
                ]);
            }
        } else {
            return false;
        }
    }
}
