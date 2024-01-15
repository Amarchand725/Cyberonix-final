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
use App\Models\Stationary;
use App\Models\User;

class StationaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('stationary-list');
        $title = 'Stationary';
        $temp = 'Stationary Status';

        $stationaryCategories = StationaryCategory::where('status', 1)->get();
        $model = [];
        Stationary::latest()
            ->chunk(100, function ($stationaries) use (&$model) {
                foreach ($stationaries as $stationary) {
                    $model[] = $stationary;
                }
        });

        if($request->ajax() && $request->loaddata == "yes"){
            return DataTables::of($model)
                ->addIndexColumn()
                // ->editColumn('status', function ($model) {
                //     $label = '';

                //     switch ($model->status) {
                //         case 1:
                //             $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                //             break;
                //         case 0:
                //             $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                //             break;
                //     }

                //     return $label;
                // })
                // ->editColumn('created_at', function ($model) {
                //     return Carbon::parse($model->created_at)->format('d, M Y');
                // })
                ->editColumn('stationary_category_id', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->stationartCategory->stationary_category.'</span>';
                })
                ->editColumn('quantity', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->quantity.'</span>';
                })
                ->editColumn('price', function ($model) {
                    return '<span class="text-primary fw-semibold">'.'$'.number_format($model->price,2).'</span>';
                })
                ->addColumn('action', function($model){
                    return view('admin.stationary.action', ['model' => $model])->render();
                })
                ->rawColumns(['stationary_category_id', 'quantity', 'price', 'status', 'action'])
                ->make(true);
        }
        return view('admin.stationary.index', compact('title', 'model','temp','stationaryCategories'));
    }

    public function categoryStationary(Request $request, $category_id){
        $this->authorize('stationary-list');
        $title = 'Stationary';
        $temp = 'Stationary Status';

        $url = route('stationary.list', $category_id);
        $model = [];
        Stationary::where('stationary_category_id', $category_id)->latest()
            ->chunk(100, function ($stationaries) use (&$model) {
                foreach ($stationaries as $stationary) {
                    $model[] = $stationary;
                }
        });

        $stationary_category = StationaryCategory::where('id', $category_id)->first();
        $stationaryCategories = StationaryCategory::where('status', 1)->get();
        if($request->ajax() && $request->loaddata == "yes"){
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('quantity', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->quantity.'</span>';
                })
                ->editColumn('price', function ($model) {
                    return '<span class="text-primary fw-semibold">'.'$'.number_format($model->price,2).'</span>';
                })
                ->addColumn('action', function($model){
                    return view('admin.stationary.action', ['model' => $model])->render();
                })
                ->rawColumns(['stationary_category_id', 'quantity', 'price', 'status', 'action'])
                ->make(true);
        }

        return view('admin.stationary.cat_stationary_list', compact('title', 'stationary_category', 'temp','stationaryCategories', 'url'));
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
            'stationary_quantity' => ['required', 'numeric', 'min:1'],
            'stationary_price' => ['required', 'numeric', 'min:1'],
            'stationary_category' => ['required'],
        ]);

        DB::beginTransaction();

        try{
            $model = Stationary::create([
                'user_id' => $user_id,
                'stationary_category_id' => $request->stationary_category,
                'quantity' => $request->stationary_quantity,
                'price' => $request->stationary_price,
            ]);


            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('Stationary Added successfully');
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
        $model = Stationary::findOrFail($id);
        return (string) view('admin.stationary.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('stationary-edit');
        $model = Stationary::findOrFail($id);
        $stationaryCategories = StationaryCategory::get();
        return (string) view('admin.stationary.edit', compact('model','stationaryCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stationary $stationary)
    {
        $this->validate($request, [
            'stationary_quantity' => ['required', 'numeric', 'min:1'],
            'stationary_price' => ['required', 'numeric', 'min:1'],
            'stationary_category' => ['required'],
            'user_id' => ['required'],
        ]);

        DB::beginTransaction();

        try{
            $model = $stationary->update([
                'user_id' => $request->user_id,
                'stationary_category_id' => $request->stationary_category,
                'quantity' => $request->stationary_quantity,
                'price' => $request->stationary_price,
            ]);

            if($model){
                DB::commit();
            }

            \LogActivity::addToLog('Stationary Updated successfully');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stationary $stationary)
    {
        $this->authorize('stationary-delete');
        if(isset($stationary) && !empty($stationary)){
            $historyArray['model_id'] = $stationary->id;
            $historyArray['model_name'] = "\App\Models\Stationary";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Stationary has been deleted";
            $model = $stationary->delete();
            if($model) {
                LogActivity::addToLog('Stationary Deleted');
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
        $stationaryCategories = StationaryCategory::get();
        if($request->ajax()) {
            $model = Stationary::onlyTrashed();
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
            ->editColumn('stationary_category_id', function ($model) {
                return '<span class="text-primary fw-semibold">'.$model->stationartCategory->stationary_category.'</span>';
            })
            ->editColumn('quantity', function ($model) {
                return '<span class="text-primary fw-semibold">'.$model->quantity.'</span>';
            })
            ->editColumn('price', function ($model) {
                return '<span class="text-primary fw-semibold">'.'$'.number_format($model->price,2).'</span>';
            })
            ->addColumn('action', function($model){
                $button = '<a href="'.route('stationary.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                '<span>'.
                                    '<i class="ti ti-refresh ti-sm"></i>'.
                                '</span>'.
                            '</a>';
                return $button;
            })
            ->rawColumns(['stationary_category_id', 'quantity', 'price', 'status', 'action'])
            ->make(true);
        }
        return view('admin.stationary.index', compact('title','stationaryCategories'));
    }


    public function restore($id)
    {
        $find = Stationary::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Stationary";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Stationary has been restored";
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
