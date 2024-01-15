<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use App\Models\Designation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('designations-list');
        $title = 'All Designations';
        $temp = 'All Designation';

        if($request->ajax() && $request->loaddata == "yes") {
            $model = Designation::latest();

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('no_of_employee', function($model){
                    $employee_counter = '<span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2">'.
                                count($model->hasUsers).
                            '</span>';
                    return $employee_counter;
                })
                ->editColumn('description', function ($data) {
                    return '<span class="fw-semibold">'.Str::limit(strip_tags($data->description), 50).'</span>';
                })
                ->editColumn('title', function ($data) {
                    return '<span class="text-primary fw-semibold">'.$data->title.'</span>';
                })
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
                ->addColumn('action', function($model){
                    return view('admin.designations.designation-action', ['model' => $model])->render();
                })
                ->rawColumns(['no_of_employee', 'status', 'title', 'description', 'action'])
                ->make(true);
        }

        return view('admin.designations.index', compact('title', 'temp'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:designations,title',
            'description' => 'max:500',
        ]);

        DB::beginTransaction();

        try{
            Designation::create([
                'title' => $request->title,
                'description' => $request->description,
                // 'status' => $request->status,
            ]);

            DB::commit();

            \LogActivity::addToLog('New Designation Added');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->authorize('designations-edit');
        $model = Designation::where('id', $id)->first();
        return (string) view('admin.designations.edit_content', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        $this->validate($request, [
            'title' => 'required|max:200|unique:designations,id,'.$designation->id,
            'description' => 'max:500',
        ]);

        DB::beginTransaction();

        try{
            $designation->update($request->all());

            DB::commit();

            \LogActivity::addToLog('Designation Updated');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $this->authorize('designations-delete');

        if(isset($designation) && !empty($designation)){
            $historyArray['model_id'] = $designation->id;
            $historyArray['model_name'] = "\App\Models\Designation";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Designation has been deleted";
            $model = $designation->delete();
            if($model) {
                LogActivity::addToLog('Designation Deleted');
                LogActivity::deleteHistory($historyArray);
                $onlySoftDeleted = Designation::onlyTrashed()->count();
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
        $title = 'All Trashed Designations Records';

        if($request->ajax()) {
            $model = Designation::onlyTrashed();

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('no_of_employee', function($model){
                    $employee_counter = '<span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2">'.
                                count($model->hasUsers).
                            '</span>';
                    return $employee_counter;
                })
                ->editColumn('description', function ($data) {
                    return Str::limit(strip_tags($data->description), 50);
                })
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
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('designations.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['no_of_employee', 'status', 'action'])
                ->make(true);
        }

        return view('admin.designations.index', compact('title'));
    }
    public function restore($id)
    {
       $find = Designation::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Designation";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Designation has been restored";
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
