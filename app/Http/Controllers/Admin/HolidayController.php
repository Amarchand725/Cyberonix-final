<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use App\Models\User;
use App\Models\Holiday;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\HolidayCustomizeEmployee;
use Yajra\DataTables\Facades\DataTables;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('holidays-list');
        $title = 'All Holidays';
        $user = Auth::user();

        $employees = User::where('status', 1)->where('is_employee', 1)->select(['id', 'slug', 'first_name', 'last_name'])->get();
        $model = [];
        Holiday::where('status',1)
            ->latest()
            ->chunk(100, function ($holidays) use (&$model) {
                foreach ($holidays as $holiday) {
                    $model[] = $holiday;
                }
        });

        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('name', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->name.'</span>';
                })
                ->editColumn('type', function ($model) {
                    return '<span class="text-info fw-semibold">'.Str::ucfirst($model->type).'</span>';
                })
                ->editColumn('start_at', function ($model) {
                    return '<span class="fw-semibold"><b>'.Carbon::parse($model->start_at)->format('d-M-Y').'</span>';
                })
                ->editColumn('end_at', function ($model) {
                    return '<span class="fw-semibold"><b>'.Carbon::parse($model->end_at)->format('d-M-Y').'</span>';
                })
                ->editColumn('off_days', function ($model) {
                    return '<span class="fw-semibold  bg-label-info"><b>'.$model->off_days.'</span>';
                })
                ->editColumn('created_by', function ($model) {
                    return getAuthorizeUserName($model->created_by);
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->addColumn('action', function($model){
                    return view('admin.holidays.action', ['model' => $model])->render();
                })
                ->rawColumns(['status', 'start_at', 'end_at', 'name', 'type', 'off_days', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.holidays.index', compact('title', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'start_at' => ['required'],
            'end_at' => ['required'],
            'description' => ['required', 'max:255'],
        ]);

        DB::beginTransaction();

        try{
            $numberOfDays = 0;
            $startDate = Carbon::parse($request->start_at);
            $endDate = Carbon::parse($request->end_at);

            // Calculate the difference in days
            $numberOfDays = $endDate->diffInDays($startDate) + 1;

            $model = Holiday::create([
                'created_by' => Auth::user()->id,
                'name' => $request->name,
                'description' => $request->description,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'off_days' => $numberOfDays,
                'type' => $request->type,
            ]);

            if($request->type=="customizable" && count($request->employees) > 0){
                foreach ($request->employees as $employeeId) {
                    HolidayCustomizeEmployee::create([
                        'holiday_id' => $model->id,
                        'employee_id' => $employeeId,
                    ]);
                }
            }

            DB::commit();

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
        $title = 'Show Details';
        $model = Holiday::where('id', $id)->first();
        return view('admin.holidays.show', compact('model', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('holidays-edit');
        $employees = User::where('status', 1)->where('is_employee', 1)->select(['id', 'slug', 'first_name', 'last_name'])->get();
        $model = Holiday::where('id', $id)->first();
        return (string) view('admin.holidays.edit_content', compact('model', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required'],
            'start_at' => ['required'],
            'end_at' => ['required'],
            'description' => ['required', 'max:255'],
        ]);

        $model = Holiday::findOrFail($id);

        DB::beginTransaction();

        try{
            $numberOfDays = 0;
            $startDate = Carbon::parse($request->start_at);
            $endDate = Carbon::parse($request->end_at);

            // Calculate the difference in days
            $numberOfDays = $endDate->diffInDays($startDate) + 1;
            $model->created_by = Auth::user()->id;
            $model->name = $request->name;
            $model->type = $request->type;
            $model->description = $request->description;
            $model->start_at = $request->start_at;
            $model->end_at = $request->end_at;
            $model->off_days = $numberOfDays;
            $model->save();

            if($request->type=="customizable" && count($request->employees) > 0){
                HolidayCustomizeEmployee::where('holiday_id', $id)->delete();
                foreach ($request->employees as $employeeId) {
                    HolidayCustomizeEmployee::create([
                        'holiday_id' => $model->id,
                        'employee_id' => $employeeId,
                    ]);
                }
            }else{
                HolidayCustomizeEmployee::where('holiday_id', $id)->delete();
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('holidays-delete');
        $find = Holiday::where('id', $id)->first();
        if(isset($find) && !empty($find)){
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\UserLeave";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Holiday has been deleted";
            $model = $find->delete();
            if($model) {
                LogActivity::addToLog('Holiday Deleted');
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
        $model = Holiday::onlyTrashed()->latest()->get();
        $title = 'All Trashed Holidays';

        if($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('name', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->name.'</span>';
                })
                ->editColumn('start_at', function ($model) {
                    return '<span class="fw-semibold"><b>'.Carbon::parse($model->start_at)->format('d-M-Y').'</span>';
                })
                ->editColumn('end_at', function ($model) {
                    return '<span class="fw-semibold"><b>'.Carbon::parse($model->end_at)->format('d-M-Y').'</span>';
                })
                ->editColumn('off_days', function ($model) {
                    return '<span class="fw-semibold  bg-label-info"><b>'.$model->off_days.'</span>';
                })
                ->editColumn('created_by', function ($model) {
                    return getAuthorizeUserName($model->created_by);
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('holidays.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['status', 'start_at', 'end_at', 'name', 'off_days', 'created_at', 'action'])
                ->make(true);
        }

        return view('admin.holidays.index', compact('title'));
    }
    public function restore($id)
    {
       $find = Holiday::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Department";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Holiday has been restored";
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
