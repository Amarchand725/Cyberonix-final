<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Auth;
use DB;
use Carbon\Carbon;
use App\Rules\CnicFormat;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Insurance;
use App\Models\InsuranceMeta;
use Yajra\DataTables\DataTables;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('insurances-list');
        $title = 'All Insurances';
        $employees = User::where('is_employee', 1)->where('status', 1)->get();

        $data = [];
        Insurance::latest()
            ->chunk(100, function ($insurances) use (&$data) {
                foreach ($insurances as $insurance) {
                    $data[] = $insurance;
                }
        });

        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('sex', function ($data) {
                    $label = '';

                    switch ($data->sex) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Male</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Female</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('date_of_birth', function ($data) {
                    return Carbon::parse($data->date_of_birth)->format('d, M Y');
                })
                ->editColumn('marital_status', function ($data) {
                    $label = '';

                    switch ($data->marital_status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Married</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Single</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('name_as_per_cnic', function ($data) {
                    return view('admin.insurances.employee-profile', ['employee' => $data])->render();
                })
                ->addColumn('action', function($data){
                    return view('admin.insurances.action', ['data' => $data])->render();
                })
                ->rawColumns(['sex', 'marital_status', 'name_as_per_cnic', 'action'])
                ->make(true);
        }

        return view('admin.insurances.index', compact('title', 'employees', 'data'));
    }

    public function create(){
        $title = 'Insurance Form';
        $insurance = Insurance::where('user_id', Auth::user()->id)->first();
        if(!empty($insurance)){
            return redirect()->route('insurances.edit', Auth::user()->slug);
        }else{
            return view('admin.insurances.create', compact('title'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Auth::user()->hasRole('Admin')){
            $request['user_id'] = Auth::user()->id;
        }

        $this->validate($request, [
            'user_id' => ['required', 'unique:insurances'],
            'name_as_per_cnic' => 'required|max:255',
            'date_of_birth' => 'required',
            'cnic_number' => ['required', new CnicFormat],
            'sex' => 'required',
            'marital_status' => 'required'
        ]);

        DB::beginTransaction();

        try{
            $model = Insurance::create([
                'user_id' => $request->user_id,
                'name_as_per_cnic' => $request->name_as_per_cnic,
                'date_of_birth' => $request->date_of_birth,
                'cnic_number' => $request->cnic_number,
                'sex' => $request->sex,
                'marital_status' => $request->marital_status,
            ]);
            if($model){
                if(isset($request->relationships) && $request->relationships[0] != '' && isset($request->family_rel_names) && $request->family_rel_names[0] != ''){
                    foreach($request->relationships as $key=>$relationship){
                        $father_cnic = NULL;
                        if($relationship=='father'){
                            $father_cnic = $request->father_cnic_number;
                        }

                        $sex = 0;
                        if($relationship=='father' || $relationship=='husband' || $relationship=='son'){
                            $sex = 1;
                        }

                        $insurance_meta = new InsuranceMeta();
                        $insurance_meta->insurance_id = $model->id;
                        $insurance_meta->relationship = $relationship;
                        $insurance_meta->name = $request->family_rel_names[$key];
                        $insurance_meta->sex = $sex;
                        $insurance_meta->cnic_number = $father_cnic;
                        $insurance_meta->date_of_birth = $request->family_rel_dobs[$key];
                        $insurance_meta->save();
                    }
                }

                DB::commit();
            }

            \LogActivity::addToLog('New Insurance Added');

            if(isset($request->user)){
                return redirect()->route('insurances.edit', Auth::user()->slug)->with('You have submitted insurance data successfully.');
            }else{
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $model = Insurance::findOrFail($id);
        return (string) view('admin.insurances.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('insurances-edit');
        $title = 'Edit Insurance Details';
        if(Auth::user()->hasRole('Admin')){
            $model = Insurance::where('id', $id)->first();
            return view('admin.insurances.edit', compact('model', 'title'));
        }else{
            $model = Insurance::where('user_id', Auth::user()->id)->first();
            return view('admin.insurances.edit', compact('model', 'title'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'name_as_per_cnic' => 'required|max:255',
            'date_of_birth' => 'required',
            'cnic_number' => ['required', new CnicFormat],
            'sex' => 'required',
            'marital_status' => 'required'
        ]);

        $model = Insurance::where('id', $id)->first();
        $model->user_id = $request->user_id;
        $model->name_as_per_cnic = $request->name_as_per_cnic;
        $model->date_of_birth = $request->date_of_birth;
        $model->cnic_number = $request->cnic_number;
        $model->sex = $request->sex;
        $model->marital_status = $request->marital_status;
        $model->save();

        if($model){
            InsuranceMeta::where('insurance_id', $model->id)->delete();

            if(isset($request->relationships) && count($request->relationships) >= 1 && isset($request->family_rel_names) && count($request->family_rel_names) >= 1){
                foreach($request->relationships as $key=>$relationship){
                    if(!empty($relationship)){
                        $father_cnic = NULL;
                        if($relationship=='father'){
                            $father_cnic = $request->father_cnic_number;
                        }

                        $sex = 0;
                        if($relationship=='father' || $relationship=='husband' || $relationship=='son'){
                            $sex = 1;
                        }

                        $insurance_meta = new InsuranceMeta();
                        $insurance_meta->insurance_id = $model->id;
                        $insurance_meta->relationship = $relationship;
                        $insurance_meta->name = $request->family_rel_names[$key];
                        $insurance_meta->sex = $sex;
                        $insurance_meta->cnic_number = $father_cnic;
                        $insurance_meta->date_of_birth = $request->family_rel_dobs[$key];
                        $insurance_meta->save();
                    }
                }
            }
        }

        \LogActivity::addToLog('Insurance Record Updated');
        if(Auth::user()->hasRole('Admin')){
            return redirect()->route('insurances.index')->with('You have updated insurance data successfully.');
        }else{
            return redirect()->back()->with('You have updated insurance data successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('insurances-delete');
        $find = Insurance::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Insurance";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Insurance has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Insurance Deleted');
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
        $title = 'All Insurance Trashed Records';
        if($request->ajax()) {
            $data = Insurance::onlyTrashed();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('sex', function ($data) {
                    $label = '';

                    switch ($data->sex) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Male</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Female</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('date_of_birth', function ($data) {
                    return Carbon::parse($data->date_of_birth)->format('d, M Y');
                })
                ->editColumn('marital_status', function ($data) {
                    $label = '';

                    switch ($data->marital_status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Married</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Single</span>';
                            break;
                    }

                    return $label;
                })
                ->addColumn('action', function($data){
                    $button = '<a href="'.route('insurances.restore', $data->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['sex', 'marital_status', 'action'])
                ->make(true);
        }

        return view('admin.insurances.index', compact('title'));
    }
    public function restore($id)
    {
        $find = Insurance::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Insurance";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Insurance has been restored";
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
