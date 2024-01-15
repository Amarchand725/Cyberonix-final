<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\DocumentAttachments;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DocumentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('documents-list');
        $title = 'All Documents';

        $employees = User::where('status', 1)->where('is_employee', 1)->latest()->get();

        $model = [];
        Document::latest()
            ->chunk(100, function ($documents) use (&$model) {
                foreach ($documents as $document) {
                    $model[] = $document;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
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
                ->editColumn('date', function ($model) {
                    return Carbon::parse($model->date)->format('d, M Y / h:i A');
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.documents.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('department', function ($model) {
                    if (!empty($model->hasEmployee->departmentBridge->department->name)) {
                        return $model->hasEmployee->departmentBridge->department->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($model) {
                    return view('admin.documents.action', ['model' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'designation', 'date', 'action'])
                ->make(true);
        }

        return view('admin.documents.index', compact('title', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'selectEmployee' => 'required',
            'titles' => 'required|array|min:1',
            'attachments' => 'required|array|min:1',
        ]);
        try {
            $user = User::where('slug', $request->selectEmployee)->first();
            $model = Document::create([
                'user_id' => $user->id,
                'date' => Carbon::now()->toDateTimeString(),
            ]);
            if ($model && count($request->titles) > 0 && count($request->attachments) > 0) {
                foreach ($request->titles as $key => $title) {
                    $attachment = '';
                    if ($request->attachments[$key]) {
                        $attachment = $request->attachments[$key];
                        $attachmentName = rand() . '.' . $attachment->getClientOriginalExtension();
                        $attachment->move(public_path('admin/assets/document_attachments'), $attachmentName);
                        $attachment = $attachmentName;
                    }
                    DocumentAttachments::create([
                        'document_id' => $model->id,
                        'title' => $title,
                        'attachment' => $attachment,
                    ]);
                }
            }

            \LogActivity::addToLog('New document Added');

            return redirect()->back()->with("message", "Document Created");
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function show($id)
    {
        $model = Document::find($id);
        return (string) view('admin.documents.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('documents-edit');
        $employees = User::where('status', 1)->where('is_employee', 1)->latest()->get();
        $model = Document::where('id', $id)->first();
        return (string) view('admin.documents.edit_content', compact('model', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $this->validate($request, [
            'employee' => 'required',
            'titles' => 'required|array|min:1',
        ]);
        DB::beginTransaction();
        try {
            $user = User::where('slug', $request->employee)->first();
            $model = Document::find($request->document_id);
            if (!empty($model->hasAttachments)) {
                foreach ($model->hasAttachments as $index => $value) {
                    $fileName = $value->attachment ?? null;
                    $updateArray = [];
                    $updateArray = $request->titles[$index] ?? null;
                    if (isset($request->attachments) && !empty($request->attachments)) {
                        if (isset($request->attachments[$index]) && !empty($request->attachments[$index])) {
                            $attachment = '';
                            if ($request->attachments[$index]) {
                                $attachment = $request->attachments[$index];
                                $attachmentName = "DOCUMENT-"  . time() . "-" . rand() . '.' . $attachment->getClientOriginalExtension();
                                $attachment->move(public_path('admin/assets/document_attachments'), $attachmentName);
                                $fileName = $attachmentName;
                            }
                        }
                    }
                    $update = $value->update([

                        "title" => $request->titles[$index] ?? null,
                        "attachment" => $fileName ?? null,
                    ]);
                }
            }
            $model->update(["user_id" => $user->id]);
            DB::commit();

            \LogActivity::addToLog('Documents Updated');
            return redirect()->back()->with("message", "Documents Updated");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function documentAttachmentUpdate(Request $request, $id)
    {
        $model = DocumentAttachments::where('id', $id)->first();
        $model->title = $request->title;
        $model->save();

        if ($model) {
            return response()->json([
                'status' => true,
            ]);
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($document_id)
    {

        $this->authorize('documents-delete');
        $find = Document::where('id', $document_id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Document";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Document has been deleted";
            $model = $find->delete();
            if ($model) {
                LogActivity::addToLog('Document Deleted');
                LogActivity::deleteHistory($historyArray);
                return response()->json([
                    'status' => true,
                ]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function documentAttachmentDestroy($document_attachment_id)
    {


        $model = DocumentAttachments::where('id', $document_attachment_id)->find();
        if ($model) {
            $historyArray['model_id'] = $model->id;
            $historyArray['model_name'] = "\App\Models\DocumentAttachments";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Document has been deleted";
            LogActivity::deleteHistory($historyArray);
            $model->delete();
            return response()->json([
                'status' => true,
            ]);
        } else {
            return false;
        }
    }

    public function trashed(Request $request)
    {
        $model = Document::onlyTrashed()->latest()->get();
        $title = 'All Trashed documents';
        $temp = 'All Trashed documents';

        if ($request->ajax()) {
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
                ->editColumn('date', function ($model) {
                    return Carbon::parse($model->date)->format('d, M Y');
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.documents.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('department', function ($model) {
                    if (!empty($model->hasEmployee->departmentBridge->department->name)) {
                        return $model->hasEmployee->departmentBridge->department->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="' . route('documents.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['user_id', 'status', 'designation', 'date', 'action'])
                ->make(true);
        }

        return view('admin.documents.index', compact('title', 'temp'));
    }
    public function restore($id)
    {
        $find = Document::onlyTrashed()->where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $attachments = DocumentAttachments::onlyTrashed()->where("document_id", $find->id)->get();
            if (isset($attachments) && !empty($attachments)) {
                foreach ($attachments as $value) {
                    $historyArray['model_id'] = $value->id;
                    $historyArray['model_name'] = "\App\Models\DocumentAttachments";
                    $historyArray['type'] = "2";
                    $historyArray['remarks'] = "Documents have been restored";
                    LogActivity::deleteHistory($historyArray);
                    $value->restore();
                }
            }

            $restore = $find->restore();
            if (!empty($restore)) {

                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }


    public function deleteDocuments($id)
    {
        try {

            $attachment = DocumentAttachments::find($id);
            if (!empty($attachment)) {
                $historyArray['model_id'] = $attachment->id;
                $historyArray['model_name'] = "\App\Models\DocumentAttachments";
                $historyArray['type'] = "1";
                $historyArray['remarks'] = "Document Attachment has been deleted";
                $model = $attachment->delete();
                if ($model) {
                    LogActivity::addToLog('Document Attachment Deleted');
                    LogActivity::deleteHistory($historyArray);
                    return response()->json(['success' => true, "message" =>  "Document Deleted!"]);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, "message" => $e->getMessage()]);
        }
    }
    public function downloadSingle($id)
    {
        try {
            $document = DocumentAttachments::where("id", $id)->first();
            if (!empty($document)) {
                $path = public_path('admin/assets/document_attachments/' . $document->attachment);
                $fileName = $document->attachment;
                if (File::exists($path)) {
                    return Response::download($path, $fileName);
                } else {
                    Log::info("FIle Not Found!");
                    return redirect()->back()->with("error", "Attachment not found!");
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    public function deleteDocumentWithAttachments($id)
    {
        try {

            $document = Document::find($id);
            if (!empty($document)) {

                if (!empty($document->hasAttachments)) {
                    foreach ($document->hasAttachments as $attach) {
                        $historyArray['model_id'] = $attach->id;
                        $historyArray['model_name'] = "\App\Models\DocumentAttachments";
                        $historyArray['type'] = "1";
                        $historyArray['remarks'] = "Document has been deleted with Attachments";
                        LogActivity::deleteHistory($historyArray);
                        $attach->delete();
                    }
                }
                $delete = $document->delete();
                if ($delete >  0) {
                    return response()->json(['success' => true, "message" =>  "Document Deleted!"]);
                }
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, "message" => $e->getMessage()]);
        }
    }
}
