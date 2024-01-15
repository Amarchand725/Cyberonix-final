<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('positions-list');
        $title = 'All Positions';
        if($request->ajax() && $request->loaddata == "yes") {
            $data = Position::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d F Y');
                })
                ->editColumn('status', function ($data) {
                    $label = '';

                    switch ($data->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-Active</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('description', function ($data) {
                    $lable = strip_tags($data->description);
                    return '<span class="fw-semibold">'.Str::limit($lable, 50).'</span>';
                })
                ->editColumn('title', function ($data) {
                    return '<span class="text-primary fw-semibold">'.$data->description.'</span>';
                })
                ->addColumn('action', function($data){
                    return view('admin.positions.action', ['data' => $data])->render();
                })
                ->rawColumns(['status', 'description', 'title', 'action'])
                ->make(true);
        }

        return view('admin.positions.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'unique:positions', 'max:255'],
            'description' => ['max:500'],
        ]);

        DB::beginTransaction();

        try{
            $model = Position::create($request->all());
            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('New Position Inserted');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->authorize('positions-edit');
        $model = Position::where('id', $id)->first();
        return (string) view('admin.positions.edit_content', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        $request->validate([
            'title' => 'required|max:255|unique:positions,id,'.$position->id,
            'description' => ['max:500'],
        ]);

        DB::beginTransaction();

        try{
            $model = $position->update($request->all());
            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('Position Updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $this->authorize('positions-delete');
        if(isset($position) && !empty($position)){
            $historyArray['model_id'] = $position->id;
            $historyArray['model_name'] = "\App\Models\Position";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Position has been deleted";
            $model = $position->delete();
            if($model) {
                LogActivity::addToLog('Position Deleted');
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
        $title = 'All Trashed Positions';
        $temp = 'All Trashed Positions';

        if($request->ajax()) {
            $data = Position::onlyTrashed()->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d F Y');
                })
                ->editColumn('status', function ($data) {
                    $label = '';

                    switch ($data->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-Active</span>';
                            break;
                    }

                    return strip_tags($label);
                })
                ->editColumn('description', function ($data) {
                    return Str::limit(strip_tags($data->description), 50);
                })
                ->addColumn('action', function($data){
                    $button = '<a href="'.route('positions.restore', $data->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })->make(true);
        }

        return view('admin.positions.index', compact('title', 'temp'));
    }
    public function restore($id)
    {
        $find = Position::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Position";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Position has been restored";
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
