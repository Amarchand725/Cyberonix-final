<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ProfileCoverImage;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProfileCoverImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('profile_cover_images-list');
        $title = 'All Profile Cover Images';

        $model = ProfileCoverImage::orderby('id', 'desc')->get();
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('image', function ($model) {
                    if(!empty($model->image)){
                        return '<img src="'.asset('public/admin/assets/img/pages').'/'.$model->image.'" style="width:100px; height:40px" class="rounded" alt="">';
                    }else{
                        return '<img src="'.asset('public/admin/default.png').'" style="width:100px" class="rounded" alt="">';
                    }
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
                ->editColumn('created_by', function ($model) {
                    if(!empty($model->createdBy->first_name)){
                        return '<span class="fw-semibold">'.$model->createdBy->first_name .' '. $model->createdBy->last_name.'</span>';
                    }else{
                        return '-';
                    }
                })
                ->addColumn('action', function($model){
                    return view('admin.profile_cover_images.action', ['model' => $model])->render();
                })
                ->rawColumns(['image', 'status', 'created_by', 'action'])
                ->make(true);
        }

        return view('admin.profile_cover_images.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => ['required'],
        ]);

        DB::beginTransaction();

        try{
            $model = new ProfileCoverImage();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/img/pages'), $imageName);

                $model->created_by = Auth::user()->id;
                $model->image = $imageName;
                $model->save();

                DB::commit();
            }

            \LogActivity::addToLog('New Cover Image Added');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfileCoverImage $profileCoverImage)
    {
        $this->authorize('profile_cover_images-delete');
        if(isset($profileCoverImage) && !empty($profileCoverImage)){
            $historyArray['model_id'] = $profileCoverImage->id;
            $historyArray['model_name'] = "\App\Models\ProfileCoverImage";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Profile Cover Image has been deleted";
            $model = $profileCoverImage->delete();
            if($model) {
                LogActivity::addToLog('Profile Cover Image Deleted');
                LogActivity::deleteHistory($historyArray);
                $onlySoftDeleted = ProfileCoverImage::onlyTrashed()->count();
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
        $model = ProfileCoverImage::onlyTrashed()->latest()->get();
        $title = 'All Trashed Cover Images';
        $temp = 'All Trashed Cover Images';

        if($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('image', function ($model) {
                    if(!empty($model->image)){
                        return '<img src="'.asset('public/admin/assets/img/pages').'/'.$model->image.'" style="width:100px; height:40px" alt="">';
                    }else{
                        return '<img src="'.asset('public/admin/default.png').'" style="width:100px" alt="">';
                    }
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
                ->editColumn('created_by', function ($model) {
                    if(!empty($model->createdBy->first_name)){
                        return $model->createdBy->first_name .' '. $model->createdBy->last_name;
                    }else{
                        return '-';
                    }
                })
                ->addColumn('action', function($model){
                    $button = '<a href="'.route('profile_cover_images.restore', $model->id).'" class="btn btn-icon btn-label-info waves-effect">'.
                                    '<span>'.
                                        '<i class="ti ti-refresh ti-sm"></i>'.
                                    '</span>'.
                                '</a>';
                    return $button;
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        return view('admin.profile_cover_images.index', compact('title', 'temp'));
    }
    public function restore($id)
    {
       $find = ProfileCoverImage::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\ProfileCoverImage";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Profile Cover Image has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function status(Request $request, $id)
    {
        $model = ProfileCoverImage::where('id', $id)->first();

        if($model->status==1) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }

        $model->save();

        return true;
    }
}
