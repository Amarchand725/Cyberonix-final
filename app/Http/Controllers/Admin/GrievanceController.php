<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Grievance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GrievanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("grievance-list");
        $title = 'All Grievances';
        $users = User::where('status', 1)->get();
        $model = [];
        Grievance::latest()
            ->chunk(100, function ($grievances) use (&$model) {
                foreach ($grievances as $grievance) {
                    $model[] = $grievance;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('Creator', function ($model) {
                    $creator = isset($model->creator_id) && !empty($model->creator_id) ? userWithHtml(getUser($model->creator_id)) : null;
                    return $creator ?? "-";
                })
                ->addColumn('User', function ($model) {
                    $user = isset($model->user_id) && !empty($model->user_id) ? userWithHtml(getUser($model->user_id)) : null;
                    return $user ?? "-";
                })

                ->addColumn('Anonymous', function ($model) {
                    if (isset($model->anonymous) && $model->anonymous == 1) {
                        return '<span class="badge bg-label-info fw-semibold">No</span>';
                    } else {
                        return '<span class="badge bg-label-danger fw-semibold">Yes</span>';
                    }
                })
                // ->addColumn('status', function ($model) {
                //     if ($model->status == 1) {
                //         return '<span class="badge bg-label-success fw-semibold">Active</span>';
                //     } else {
                //         return '<span class="badge bg-label-danger fw-semibold">In Active</span>';
                //     }
                // })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('action', function ($model) {
                    return view('admin.grievances.action', ['model' => $model])->render();
                })
                ->rawColumns(['Creator', 'User', 'Anonymous', 'date', 'action'])
                ->make(true);
        }

        return view('admin.grievances.index', compact('title', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("grievance-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("grievance-create");
        $this->validate($request, [
            'user_id'     => 'required',
            'description' => 'required',
            // 'status'      => 'required',
            // 'anonymous'   => 'required',
        ], ['user_id.required' => 'The user field is required.']);
        DB::beginTransaction();
        try {
            $model = Grievance::create([
                'creator_id'  => Auth::user()->id ?? null,
                'user_id'     => $request->user_id ?? null,
                'description' => $request->description ?? null,
                'anonymous'   => $request->anonymous ?? 1,  // annonymous no
                // 'status'      => $request->status ?? 1, // default active
            ]);
            if ($model) {
                DB::commit();
                LogActivity::addToLog('New Grievance Added');
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize("grievance-edit");
        $model = Grievance::where('id', $id)->first();
        $users = User::where('status', 1)->get();
        return (string) view('admin.grievances.edit_content', compact('model', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("grievance-edit");
        $this->validate($request, [
            'user_id'     => 'required',
            'description' => 'required',
            // 'status'      => 'required',
            // 'anonymous'   => 'required',
        ], ['user_id.required' => 'The user field is required.']);
        DB::beginTransaction();
        try {
            $find = Grievance::where('id', $id)->first();
            if (isset($find) && !empty($find)) {
                $update = $find->update([
                    'user_id'     => $request->user_id ?? null,
                    'description' => $request->description ?? null,
                    'anonymous'   => $request->anonymous ?? 1, // annonymous no
                    // 'status'      => $request->status ?? 1, // default active
                ]);
                if ($update > 0) {
                    DB::commit();
                    LogActivity::addToLog('Grievance Updated');
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }
            } else {
                return response()->json(['error' => 'Data Not Found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize("grievance-delete");
        $find = Grievance::where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $deleteArray['model_id'] = $find->id;
            $deleteArray['model_name'] = "\App\Models\Grievance";
            $deleteArray['type'] = "1";
            $deleteArray['remarks'] = "Grievance has been deleted";
            $model = $find->delete();
            if ($model) {
                LogActivity::addToLog('Grievance Deleted');
                LogActivity::deleteHistory($deleteArray);
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

    public function myGrievance(Request $request)
    {

        $this->authorize("my_grievance-list");
        $title = 'My Grievance';
        $model = [];
        $users = User::where('status', 1)->get();
        Grievance::latest()->where('creator_id', Auth::user()->id)
            ->chunk(100, function ($grievances) use (&$model) {
                foreach ($grievances as $grievance) {
                    $model[] = $grievance;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('Anonymous', function ($model) {
                    if (isset($model->anonymous) && $model->anonymous == 1) {
                        return '<span class="badge bg-label-info fw-semibold">No</span>';
                    } else {
                        return '<span class="badge bg-label-danger fw-semibold">Yes</span>';
                    }
                })

                // ->addColumn('Status', function ($model) {
                //     if ($model->status == 1) {
                //         return '<span class="badge bg-label-success fw-semibold">Active</span>';
                //     } else {
                //         return '<span class="badge bg-label-danger fw-semibold">In Active</span>';
                //     }
                // })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('Action', function ($model) {
                    return view('admin.grievances.action', ['model' => $model])->render();
                })
                ->rawColumns(['Anonymous', 'Description', 'date', 'Action'])
                ->make(true);
        }

        return view('admin.grievances.my-grievance', compact('title', 'users'));
    }

    public function myTrashed(Request $request)
    {
        $this->authorize("my_grievance-trashed");
        $title = 'My Trashed Grievance';
        $model = [];
        $trashed = true;
        if ($request->ajax() && $request->loaddata == "yes") {
            $model = Grievance::where('Creator_id', Auth::user()->id)->onlyTrashed();
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('Anonymous', function ($model) {
                    if (isset($model->anonymous) && $model->anonymous == 1) {
                        return '<span class="badge bg-label-info fw-semibold">No</span>';
                    } else {
                        return '<span class="badge bg-label-danger fw-semibold">Yes</span>';
                    }
                })
                ->addColumn('Description', function ($model) {
                    $description = $model->description ?? null;
                    $view = '<a href="javascript:;" class="btn btn-label-info waves-effect viewDetail" data-description="' . $description . '">' .
                        '<span>' .
                        'View' .
                        '</span>' .
                        '</a>';
                    return $view;
                })
                // ->addColumn('Status', function ($model) {
                //     if ($model->status == 1) {
                //         return '<span class="badge bg-label-success fw-semibold">Active</span>';
                //     } else {
                //         return '<span class="badge bg-label-danger fw-semibold">In Active</span>';
                //     }
                // })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('Action', function ($model) {
                    $button = '<a href="javascript:;" class="btn btn-icon btn-label-info waves-effect restoreBtn" data-route="' . route('grievances.restore', $model->id) . '">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['Anonymous', 'Description', 'date', 'Action'])
                ->make(true);
        }

        return view('admin.grievances.my-grievance', compact('title', 'trashed'));
    }
    public function trashed(Request $request)
    {
        $this->authorize("grievance-trashed");
        $title = 'All Trashed Grievance';
        $trashed = true;
        $users = User::where('status', 1)->get();
        if ($request->ajax()) {
            $model = Grievance::onlyTrashed();
            return DataTables::of($model)
                ->addIndexColumn()

                ->addColumn('Creator', function ($model) {
                    $creator = isset($model->creator_id) && !empty($model->creator_id) ? userWithHtml(getUser($model->creator_id)) : null;
                    return $creator ?? "-";
                })
                ->addColumn('User', function ($model) {
                    $user = isset($model->user_id) && !empty($model->user_id) ? userWithHtml(getUser($model->user_id)) : null;
                    return $user ?? "-";
                })
                ->addColumn('Description', function ($model) {
                    $description = $model->description ?? null;
                    $view = '<a href="javascript:;" class="btn btn-icon btn-label-info waves-effect viewDetail" data-description="' . $description . '">' .
                        '<span>' .
                        '<i class="ti ti-eye ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $view;
                })
                ->addColumn('Anonymous', function ($model) {
                    if (isset($model->anonymous) && $model->anonymous == 1) {
                        return '<span class="badge bg-label-info fw-semibold">No</span>';
                    } else {
                        return '<span class="badge bg-label-danger fw-semibold">Yes</span>';
                    }
                })
                // ->addColumn('status', function ($model) {
                //     if ($model->status == 1) {
                //         return '<span class="badge bg-label-success fw-semibold">Active</span>';
                //     } else {
                //         return '<span class="badge bg-label-danger fw-semibold">Deactive</span>';
                //     }
                // })
                ->addColumn('date', function ($model) {
                    return $model->created_at->format("M d,Y / h:i A");
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="javascript:;" class="btn btn-icon btn-label-info waves-effect restoreBtn" data-route="' . route('grievances.restore', $model->id) . '">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['Creator', 'User', 'Description', 'Anonymous', 'date', 'action'])
                ->make(true);
        }
        return view('admin.grievances.index', compact('title', 'trashed', 'users'));
    }

    public function restore($id)
    {
        $this->authorize("my_grievance-restore");
        $check = Grievance::onlyTrashed()->where('id', $id)->first();
        if (!empty($check)) {
            $deleteArray['model_id'] = $check->id;
            $deleteArray['model_name'] = "\App\Models\Grievance";
            $deleteArray['type'] = "2";
            $deleteArray['remarks'] = "Grievance has been restored";
            $restore = $check->restore();
            if (!empty($restore)) {
                LogActivity::deleteHistory($deleteArray);
                return response()->json(['success' =>  true, 'message' => 'Record Restored Successfully']);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
