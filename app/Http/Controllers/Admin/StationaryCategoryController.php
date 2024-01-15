<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\StationaryCategory;
use App\Models\User;


class StationaryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('stationary_category-list');
        $title = 'Stationary Pricing (US)';
        $temp = 'Stationary Categories Status';

        try{
            $models = StationaryCategory::where('status', 1)->get();
            return view('admin.stationary_category.index', compact('title', 'models','temp'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();

        $this->validate($request, [
            'stationary_category' => ['required', 'string', 'max:200'],
        ]);

        DB::beginTransaction();

        try{
            $model = StationaryCategory::create([
                'user_id' => $user_id,
                'stationary_category' => $request->stationary_category,
            ]);

            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('Stationary Category Added successfully');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $model = StationaryCategory::findOrFail($id);
        return (string) view('admin.stationary_category.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('stationary_category-edit');
        $model = StationaryCategory::where('id', $id)->first();
        return view('admin.stationary_category.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StationaryCategory $stationary_category)
    {
        $user_id = Auth::id();
        $this->validate($request, [
            'stationary_category' => ['required', 'string', 'max:200'],
            'status' => ['required'],
        ]);

        DB::beginTransaction();

        try{
            $model = $stationary_category->update([
                'user_id' => $user_id,
                'stationary_category' => $request->stationary_category,
                'status' => $request->status,
            ]);
            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('IP Address Updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StationaryCategory $stationary_category)
    {
        $this->authorize('stationary_category-delete');
        if(isset($stationary_category) && !empty($stationary_category)){
            $historyArray['model_id'] = $stationary_category->id;
            $historyArray['model_name'] = "\App\Models\StationaryCategory";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Stationary Category has been deleted";
            $model = $stationary_category->delete();
            if($model) {
                LogActivity::addToLog('Stationary Category Deleted');
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
        $title = 'All Trashed Records';

        if($request->ajax()) {
            $model = StationaryCategory::onlyTrashed();
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
                    // ->editColumn('user_id', function ($model) {
                    //     return view('admin.stationary_category.employee-profile', ['model' => $model])->render();
                    // })
                    ->editColumn('stationary_category', function ($model) {
                        return '<span class="text-primary fw-semibold">'.$model->stationary_category.'</span>';
                    })
                    ->addColumn('action', function($model){
                        $button = '<a href="'.route('stationary_categories.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                        '<span>'.
                                            '<i class="ti ti-refresh ti-sm"></i>'.
                                        '</span>'.
                                    '</a>';
                        return $button;
                    })
                    ->rawColumns(['user_id', 'stationary_category', 'status', 'action'])
                    ->make(true);
        }

        return view('admin.stationary_category.index', compact('title'));
    }

    public function restore($id)
    {
        $find = StationaryCategory::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\StationaryCategory";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Stationary Category has been restored";
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
