<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $this->authorize('logs-list');
        $data['title'] = 'All Logs';
        // $data['trashed'] = false;

        $records = SystemLog::latest()->select("*");
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('user', function ($model) {
                    $user = !empty($model->user) ? userWithHtml($model->user) : "-";
                    return $user;
                })
                ->addColumn('data', function ($model) {
                    $title_name = getModelTitleName($model) ?? null;
                    return $title_name;
                })
                // ->addColumn('model', function ($model) {
                //     $model_name = isset($model->model_name) && !empty($model->model_name) ? getModel($model->model_name, true) : null;
                //     return $model_name;
                // })
                ->addColumn('type', function ($model) {
                    $label = '';
                    $name = "N/A";
                    $class = "info";
                    if ($model->type == 1 || $model->type == 2 || $model->type == 3 || $model->type == 4) {
                        $name = getLogTypeClass($model)->name ?? '';
                        $class = getLogTypeClass($model)->class ?? 'info';
                    }
                    $label = '<span class="badge bg-label-' . $class . '" text-capitalized="">' . $name . '</span>';
                    return $label;
                })
                ->addColumn('event_id', function ($model) {
                    $event = isset($model->modelEvent->event_name) && !empty($model->modelEvent->event_name)  ? $model->modelEvent->event_name : "-";
                    return $event;
                })
                ->addColumn('remarks', function ($model) {
                    $remarks = !empty($model->remarks)  ? $model->remarks : "-";
                    return $remarks;
                })
                ->addColumn('ip', function ($model) {
                    $ip = !empty($model->ip)  ? $model->ip : "-";
                    return $ip;
                })
                ->addColumn('date', function ($model) {
                    $date = !empty($model->created_at)  ? formatDate($model->created_at) : "-";
                    return $date;
                })
                ->addColumn('action', function ($model) {
                    if ($model->type != 1 && $model->type != 2) {
                        return view('admin.logs.action', ['model' => $model])->render();
                    } else {
                        return "-";
                    }
                })
                ->filter(function ($query) use ($request) {
                    if (!empty($request->get('search'))) {
                        $search = $request->get('search');
                        $query->where('model_name', 'LIKE', "%$search%");
                    }

                    if (!empty($request->model_name) && $request->model_name != "all") {
                        $model_name = $request->get('model_name');
                        $query->where('model_name', 'LIKE', "%$model_name%");
                    }

                    if (!empty($request->log_type)  && $request->log_type != "all") {
                        $query->where('type', $request->log_type);
                    }



                    // if (!empty($request->log_type)) {
                    //     $instance = $instance->where('agent_id', $request->created_by);
                    // }
                })
                ->rawColumns(['user', 'data', 'type', 'event_id', 'remarks', 'ip', 'date', 'action'])
                ->make(true);
        }

        // $title_name = "";
        // if(isset($request->title) && !empty($request->title)) {
        //     $title_name = getModelName($request->title,true) ?? null;
        // }
        // $data['title'] = 'Delete Logs : '.$title_name;
        return view('admin.logs.index', $data);
    }

    public function showJsonData(Request $request)
    {
        $model = [];
        if (isset($request->id) && !empty($request->id) && isset($request->model) && !empty($request->model)) {
            $getModel = getModelName($request->model) ?? null;
            SystemLog::where('model_id', $request->id)->where("model_name", 'like', "%$getModel%")
                ->chunk(100, function ($histories) use (&$model) {
                    foreach ($histories as $history) {
                        $model[] = $history;
                    }
                });
        }

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('user', function ($model) {
                    $user = !empty($model->user) ? userWithHtml($model->user) : "-";
                    return $user;
                })
                ->addColumn('type', function ($model) {
                    if (!empty($model->type) && $model->type == 1) {
                        $type = '<span class="badge bg-label-danger">Deleted</span>';
                    } else {
                        $type = '<span class="badge bg-label-success">Restored</span>';
                    }
                    return $type;
                })
                ->addColumn('remarks', function ($model) {
                    $remarks = !empty($model->remarks)  ? $model->remarks : "-";
                    return $remarks;
                })
                ->addColumn('ip', function ($model) {
                    $ip = !empty($model->ip)  ? $model->ip : "-";
                    return $ip;
                })
                ->addColumn('action date', function ($model) {
                    $date = !empty($model->created_at)  ? formatDate($model->created_at) : "-";
                    return $date;
                })
                ->rawColumns(['user', 'type', 'remarks', 'ip', 'action date'])
                ->make(true);
        }

        $title_name = "";
        if (isset($request->title) && !empty($request->title)) {
            $title_name = getModelName($request->title, true) ?? null;
        }
        $data['title'] = 'Delete Logs : ' . $title_name;
        return view('components.delete-log-history-list', $data);
    }


    public function viewDocuments($id)
    {
        $model = Document::where('id', $id)->withTrashed()->first();
        return view('admin.logs.documents_show_content', compact('model'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['title'] = 'Log Details';
        $data['record'] = SystemLog::where('id', $id)->first();
        $data['old'] = isset($data['record']->old_data) && !empty($data['record']->old_data) ? json_decode($data['record']->old_data) : null;
        $data['new'] = isset($data['record']->new_data) && !empty($data['record']->new_data) ? json_decode($data['record']->new_data) : null;
        // dd("in");
        if (isset($data['record']->model_name) && !empty($data['record']->model_name)) {
            $model_name = getModel($data['record']->model_name, true);
            $model_name =  strtolower($model_name);
            $model_name = Str::plural($model_name);

            if (isset($data['record']->modelEvent) && !empty($data['record']->modelEvent) && $data['record']->model_name == $data['record']->modelEvent->model_name) {
                $file_name = $model_name . "." . $data['record']->modelEvent->slug . "-details";
            } else {
                $file_name = $model_name . "." . $model_name . "-details";
            }
            $data['file_name'] = $file_name ?? "";
            if (view()->exists('admin.logs.' . $file_name)) {
                return view('admin.logs.show')->with($data);
            } else {
                abort(404);
            }
        }

        return redirect()->back()->with('error', 'Log record not Found');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
