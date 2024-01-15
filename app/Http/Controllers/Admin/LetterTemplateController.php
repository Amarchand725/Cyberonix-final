<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LetterTemplate;
use DB;
use Auth;

class LetterTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('letter_templates-list');
        $title = 'All Templates';
        if($request->ajax()){
            $query = LetterTemplate::orderby('id', 'desc')->where('id', '>', 0);
            if($request['search'] != ""){
                $query->where('title', 'like', '%'. $request['search'] .'%');
                $query->orWhere('start_date', 'like', '%'. $request['search'] .'%');
                $query->orWhere('end_date', 'like', '%'. $request['search'] .'%');
            }
            if($request['status'] != "All"){
                $query->where('status', $request['status']);
            }
            $models = $query->paginate(10);
            return (string) view('admin.letter_templates.search', compact('models'));
        }

        $models = LetterTemplate::orderby('id', 'desc')->paginate(10);
        $onlySoftDeleted = LetterTemplate::onlyTrashed()->count();
        return view('admin.letter_templates.index', compact('title', 'models', 'onlySoftDeleted'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        DB::beginTransaction();

        try{
            $model = LetterTemplate::create([
                'title' => $request->title,
                'template' => $request->description,
            ]);

            DB::commit();

            if($model){
                \LogActivity::addToLog('New letter template Added');

                return response()->json(['success' => true]);
            }else{
                return response()->json(['failed' => false]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($model_id)
    {
        $model = LetterTemplate::findOrFail($model_id);
        return (string) view('admin.letter_templates.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('letter_templates-edit');
        $model = LetterTemplate::where('id', $id)->first();
        return (string) view('admin.letter_templates.edit_content', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $model = LetterTemplate::where('id', $id)->first();

        DB::beginTransaction();

        try{
            $model->title = $request->title;
            $model->template = $request->description;
            $model->save();

            if($model){
                DB::commit();
                \LogActivity::addToLog('Letter Template Updated');

                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterTemplate $announcement)
    {
        $this->authorize('letter_templates-delete');
        if(isset($announcement) && !empty($announcement)){
            $historyArray['model_id'] = $announcement->id;
            $historyArray['model_name'] = "\App\Models\LetterTemplate";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Letter Template has been deleted";
            $model = $announcement->delete();
            if($model) {
                LogActivity::addToLog('Letter Template Deleted');
                LogActivity::deleteHistory($historyArray);
                $onlySoftDeleted = LetterTemplate::onlyTrashed()->count();
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

    public function trashed()
    {
        $data = [];
        $data['models'] = LetterTemplate::onlyTrashed()->get();
        $title = 'All Trashed Records';
        return view('admin.letter_templates.trashed-index', compact('title', 'data'));
    }
    public function restore($id)
    {
        $find = LetterTemplate::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\LetterTemplate";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Letter Template has been restored";
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
