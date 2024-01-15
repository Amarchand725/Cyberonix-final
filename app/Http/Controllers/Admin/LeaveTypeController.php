<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('leave_types-list');
        $title = 'All Leave Types';

        $model = LeaveType::orderby('id', 'desc')->get();
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
                ->editColumn('name', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->name.'</span>';
                })
                ->editColumn('type', function ($model) {
                    $label = '';

                    switch ($model->type) {
                        case 'paid':
                            $label = '<span class="badge bg-label-success" text-capitalized="">Paid</span>';
                            break;
                        case 'unpaid':
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Un-Paid</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('amount', function ($model) {
                    return '<span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30">'.$model->amount.'</span>';
                })
                ->addColumn('action', function($model){
                    return view('admin.leave_types.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'name', 'type', 'amount', 'action'])
                ->make(true);
        }

        return view('admin.leave_types.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:leave_types', 'max:255'],
            'type' => ['required'],
        ]);

        DB::beginTransaction();

        try{
            $model = LeaveType::create($request->all());
            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('New Leave Type Added');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType)
    {
        $this->authorize('leave_types-edit');
        $model = $leaveType;
        return (string) view('admin.leave_types.edit_content', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:leave_types,id,'.$leaveType->id,
            'type' => ['required'],
        ]);

        DB::beginTransaction();

        try{
            $model = $leaveType->update($request->all());
            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('Leave Type Updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        $this->authorize('leave_types-delete');
        if(isset($leaveType) && !empty($leaveType)){
            $historyArray['model_id'] = $leaveType->id;
            $historyArray['model_name'] = "\App\Models\LeaveType";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Leave Type has been deleted";
            $model = $leaveType->delete();
            if($model) {
                LogActivity::addToLog('Leave Type Deleted');
                LogActivity::deleteHistory($historyArray);
                $onlySoftDeleted = LeaveType::onlyTrashed()->count();
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

    public function trashed(Request $request)
    {
        $model = LeaveType::onlyTrashed()->latest()->get();
        $title = 'All Trashed Leave Types';
        $temp = 'All Trashed Leave Types';

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
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('leave_types.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.leave_types.index', compact('title', 'temp'));
    }
    public function restore($id)
    {
        $find = LeaveType::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\LeaveType";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Leave Type has been restored";
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
