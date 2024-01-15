<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmploymentStatus;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class EmploymentStatusController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('employment_status-list');
        $title = 'Employment Status';
        $temp = 'Employment Status';

        if($request->ajax() && $request->loaddata == "yes") {
            $model = EmploymentStatus::latest();

            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('class', function ($model) {
                    $label = '<span class="badge bg-label-'.$model->class.'" text-capitalized="">'.$model->name.'</span>';
                    return $label;
                })
                ->editColumn('description', function ($data) {
                    return '<span class="fw-semibold">'.Str::limit(strip_tags($data->description), 50).'</span>';
                })
                ->editColumn('name', function ($data) {
                    return '<span class="text-primary fw-semibold">'.$data->name.'</span>';
                })
                ->addColumn('action', function($model){
                    return view('admin.employment_status.employment_status-action', ['model' => $model])->render();
                })
                ->rawColumns(['class', 'name', 'description', 'action'])
                ->make(true);
        }

        return view('admin.employment_status.index', compact('title', 'temp'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:employment_statuses', 'max:255'],
            'class' => 'required',
            'description' => 'max:255',
        ]);

        DB::beginTransaction();

        try{
            $model = EmploymentStatus::create([
                'name' => $request->name,
                'class' => $request->class,
                'description' => $request->details,
            ]);

            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('New Employment Status Added');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->authorize('employment_status-edit');
        $model = EmploymentStatus::where('id', $id)->first();
        return (string) view('admin.employment_status.edit_content', compact('model'));
    }

    public function update(Request $request, EmploymentStatus $employment_status)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:employment_statuses,id,'.$employment_status->id,
            'class' => 'required',
            'details' => 'max:255',
        ]);

        DB::beginTransaction();

        try{
            $model = $employment_status->update([
                'name' => $request->name,
                'class' => $request->class,
                'description' => $request->details,
            ]);
            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('Employment Status Updated');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy(EmploymentStatus $employment_status)
    {
        $this->authorize('employment_status-delete');
        if(isset($employment_status) && !empty($employment_status)){
            $historyArray['model_id'] = $employment_status->id;
            $historyArray['model_name'] = "\App\Models\EmploymentStatus";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Employment Status has been deleted";
            $model = $employment_status->delete();
            if($model) {
                LogActivity::addToLog('Employment Status Deleted');
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
            $model = EmploymentStatus::onlyTrashed();

            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('class', function ($model) {
                    $label = '<span class="badge bg-label-'.$model->class.'" text-capitalized="">'.$model->name.'</span>';
                    return $label;
                })
                ->editColumn('description', function ($data) {
                    return Str::limit(strip_tags($data->description), 50);
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('employment_status.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['class', 'action'])
                ->make(true);
        }

        return view('admin.employment_status.index', compact('title'));
    }
    public function restore($id)
    {
        $find = EmploymentStatus::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\EmploymentStatus";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Employment Status has been restored";
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
