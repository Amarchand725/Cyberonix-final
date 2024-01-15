<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\TicketReason;
use Illuminate\Http\Request;
use App\Models\DepartmentUser;
use App\Models\TicketCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\URL;
use App\Notifications\ImportantNotification;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $user_slug = null)
    {
        $data = [];
        $this->authorize('tickets-list');
        $logined_user = Auth::user();
        $user = $logined_user;

        $title = 'My Tickets';

        $data['reasons'] = TicketReason::orderby('id', 'desc')->where('status', 1)->get();
        $data['ticket_categories'] = TicketCategory::where('status', 1)->get();

        // $model = Ticket::orderby('id', 'desc')->where('user_id', $logined_user->id)->get();
        $model = [];
        Ticket::where('user_id', $logined_user->id)
            ->latest()
            ->chunk(100, function ($tickets) use (&$model) {
                foreach ($tickets as $ticket) {
                    $model[] = $ticket;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved By Manager: ' . date('d M Y h:i', strtotime($model->is_manager_approved)) . '">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin: ' . date('d M Y h:i', strtotime($model->is_concerned_approved)) . '">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Completed Ticket: ' . date('d M Y h:i', strtotime($model->updated_at)) . '">Completed</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('ticket_category_id', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->hasCategory->name . '</span>' ?? '-';
                })
                ->editColumn('reason_id', function ($model) {
                    return $model->hasReason->name ?? '-';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.tickets.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.tickets.action', ['data' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'ticket_category_id', 'action'])
                ->make(true);
        }

        return view('admin.tickets.index', compact('title', 'user', 'data'));
    }

    public function teamTickets(Request $request, $user_slug = null)
    {
        $data = [];
        $this->authorize('team_tickets-list');
        $logined_user = Auth::user();

        $user = $logined_user;

        $title = 'Team Tickets';

        $employees = [];
        $employees_ids = [];
        $url = '';
        $dept_ids = [];
        $department = Department::where('manager_id', $logined_user->id)->first();

        if (isset($department) && !empty($department->id)) {
            $department_id = $department->id;

            $dept_ids[] = $department->id;
            $sub_dep = Department::where('parent_department_id', $department->id)->where('manager_id', Auth::user()->id)->first();
            if (!empty($sub_dep)) {
                $dept_ids[] = $sub_dep->id;
            } else {
                $sub_deps = Department::where('parent_department_id', $department->id)->get();
                if (!empty($sub_deps) && count($sub_deps)) {
                    foreach ($sub_deps as $sub_dept) {
                        $dept_ids[] = $sub_dept->id;
                    }
                }
            }
        }
        $department_users = DepartmentUser::whereIn('department_id', $dept_ids)->where('end_date', null)->get();
        foreach ($department_users as $department_user) {
            $employee = User::where('id', $department_user->user_id)->where('status', 1)->where('is_employee', 1)->first(['id', 'first_name', 'last_name', 'slug']);
            if (!empty($employee) && $employee->id != Auth::user()->id) {
                $employees[] = $employee;
                $employees_ids[] = $employee->id;
            }
        }

        $data['employees'] = $employees;

        if (!empty($user_slug) && $user_slug != 'All') {
            $user = User::where('slug', $user_slug)->first();
            $url = URL::to('team/tickets/' . $user_slug);
            // $model = Ticket::orderby('id', 'desc')->where('user_id', $user->id)->get();

            $model = [];
            Ticket::where('user_id', $user->id)
                ->latest()
                ->chunk(100, function ($tickets) use (&$model) {
                    foreach ($tickets as $ticket) {
                        $model[] = $ticket;
                    }
                });
        } else {
            // $model = Ticket::orderby('id', 'desc')->whereIn('user_id', $employees_ids)->get();
            $model = [];
            Ticket::whereIn('user_id', $employees_ids)
                ->latest()
                ->chunk(100, function ($tickets) use (&$model) {
                    foreach ($tickets as $ticket) {
                        $model[] = $ticket;
                    }
                });
        }

        $data['reasons'] = TicketReason::orderby('id', 'desc')->where('status', 1)->get();
        $data['ticket_categories'] = TicketCategory::where('status', 1)->get();

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved By Manager: ' . date('d M Y h:i', strtotime($model->is_manager_approved)) . '">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin: ' . date('d M Y h:i', strtotime($model->is_concerned_approved)) . '">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Completed Ticket: ' . date('d M Y h:i', strtotime($model->updated_at)) . '">Completed</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('ticket_category_id', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->hasCategory->name . '</span>' ?? '-';
                })
                ->editColumn('reason_id', function ($model) {
                    return $model->hasReason->name ?? '-';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.tickets.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.tickets.action', ['data' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'ticket_category_id', 'action'])
                ->make(true);
        }

        return view('admin.tickets.team-tickets', compact('title', 'user', 'data', 'url'));
    }

    public function adminTeamTickets(Request $request, $user_slug = null)
    {
        $data = [];
        $this->authorize('admin_team_tickets-list');
        $userWithAdminRole = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin'); // Replace 'admin' with the actual role name.
        })->first();

        $user = $userWithAdminRole;

        $title = 'Team Tickets';

        $employees = [];
        $employees_ids = [];
        $url = '';

        $department_users = Department::where('parent_department_id', 1)->where('manager_id', '!=', $user->id)->get();
        foreach ($department_users as $department_user) {
            $emp_data = User::where('id', $department_user->manager_id)->where('status', 1)->where('is_employee', 1)->first(['id', 'first_name', 'last_name', 'slug']);
            if (!empty($emp_data)) {
                $employees[] = $emp_data;
                $employees_ids[] = $emp_data->id;
            }
        }
        $data['employees'] = $employees;

        if (!empty($user_slug) && $user_slug != 'All') {
            $user = User::where('slug', $user_slug)->first();
            $url = URL::to('admin/team/tickets/' . $user_slug);
            // $model = Ticket::orderby('id', 'desc')->where('user_id', $user->id)->get();

            $model = [];
            Ticket::where('user_id', $user->id)
                ->latest()
                ->chunk(100, function ($tickets) use (&$model) {
                    foreach ($tickets as $ticket) {
                        $model[] = $ticket;
                    }
                });
        } else {
            // $model = Ticket::orderby('id', 'desc')->whereIn('user_id', $employees_ids)->get();
            $model = [];
            Ticket::whereIn('user_id', $employees_ids)
                ->latest()
                ->chunk(100, function ($tickets) use (&$model) {
                    foreach ($tickets as $ticket) {
                        $model[] = $ticket;
                    }
                });
        }

        $data['reasons'] = TicketReason::orderby('id', 'desc')->where('status', 1)->get();
        $data['ticket_categories'] = TicketCategory::where('status', 1)->get();

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved By Manager: ' . date('d M Y h:i', strtotime($model->is_manager_approved)) . '">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin: ' . date('d M Y h:i', strtotime($model->is_concerned_approved)) . '">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Completed Ticket: ' . date('d M Y h:i', strtotime($model->updated_at)) . '">Completed</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('ticket_category_id', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->hasCategory->name . '</span>' ?? '-';
                })
                ->editColumn('reason_id', function ($model) {
                    return $model->hasReason->name ?? '-';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.tickets.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.tickets.action', ['data' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'ticket_category_id', 'action'])
                ->make(true);
        }

        return view('admin.tickets.admin_team_tickets', compact('title', 'user', 'data', 'url'));
    }

    public function allTickets(Request $request)
    {
        $title = 'All Tickets';
        $data = [];
        $employees_ids = [];

        $this->authorize('all_tickets-list');

        $logined_user = Auth::user();

        $department = Department::where('manager_id', $logined_user->id)->where('status', 1)->first();
        if ($department->name == "IT Department") {
            $all_models = Ticket::orderby('id', 'desc')->where('ticket_category_id', 1)->orWhere('ticket_category_id', 2)->get();
            $it_tickets = [];
            foreach ($all_models as $al_model) {
                if ($al_model->ticket_category_id == 1 && !empty($al_model->is_concerned_approved) && !empty($al_model->is_manager_approved)) {
                    $it_tickets[] = $al_model;
                } elseif ($al_model->ticket_category_id == 2 && !empty($al_model->is_manager_approved)) {
                    $it_tickets[] = $al_model;
                }
            }
            $model = $it_tickets;
        } elseif ($department->name == "Admin") {
            $all_models = Ticket::orderby('id', 'desc')->where('ticket_category_id', 3)->get();
            $it_tickets = [];
            foreach ($all_models as $al_model) {
                if (!empty($al_model->is_manager_approved)) {
                    $it_tickets[] = $al_model;
                }
            }
            $model = $it_tickets;
        } else {
            // $model = Ticket::orderby('id', 'desc')->where('status', 0)->get();
            $model = [];
            Ticket::latest()
                ->chunk(100, function ($tickets) use (&$model) {
                    foreach ($tickets as $ticket) {
                        $model[] = $ticket;
                    }
                });
        }

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved By Manager: ' . date('d M Y h:i', strtotime($model->is_manager_approved)) . '">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin: ' . date('d M Y h:i', strtotime($model->is_concerned_approved)) . '">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Completed Ticket: ' . date('d M Y h:i', strtotime($model->updated_at)) . '">Completed</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('ticket_category_id', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->hasCategory->name . '</span>' ?? '-';
                })
                ->editColumn('reason_id', function ($model) {
                    return $model->hasReason->name ?? '-';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.tickets.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.tickets.action', ['data' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'ticket_category_id', 'action'])
                ->make(true);
        }

        return view('admin.tickets.all_tickets', compact('title', 'data'));
    }

    public function adminAllTickets(Request $request)
    {
        $title = 'All Tickets';
        $data = [];
        $employees_ids = [];

        $this->authorize('admin_all_tickets-list');

        $logined_user = Auth::user();
        $userWithAdminRole = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin'); // Replace 'admin' with the actual role name.
        })->first();

        $department_users = DepartmentUser::where('end_date',  NULL)->get();

        foreach ($department_users as $department_user) {
            $emp_data = User::where('id', $department_user->user_id)->where('id', '!=', $userWithAdminRole->id)->first(['id', 'first_name', 'last_name', 'slug']);
            if (!empty($emp_data)  && $emp_data->id != Auth::user()->id) {
                $employees[] = $emp_data;
                $employees_ids[] = $emp_data->id;
            }
        }
        // $model = Ticket::orderby('id', 'desc')->whereIn('user_id', $employees_ids)->get();

        $model = [];
        Ticket::whereIn('user_id', $employees_ids)
            ->latest()
            ->chunk(100, function ($tickets) use (&$model) {
                foreach ($tickets as $ticket) {
                    $model[] = $ticket;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved By Manager: ' . date('d M Y h:i', strtotime($model->is_manager_approved)) . '">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin: ' . date('d M Y h:i', strtotime($model->is_concerned_approved)) . '">Approved By Admin</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Completed Ticket: ' . date('d M Y h:i', strtotime($model->updated_at)) . '">Completed</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('ticket_category_id', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->hasCategory->name . '</span>' ?? '-';
                })
                ->editColumn('reason_id', function ($model) {
                    return $model->hasReason->name ?? '-';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.tickets.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.tickets.action', ['data' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'ticket_category_id', 'action'])
                ->make(true);
        }

        return view('admin.tickets.admin_all_tickets', compact('title', 'data'));
    }

    public function waitingApprovalTickets(Request $request)
    {
        $title = 'Waiting for Approval Tickets';
        $data = [];
        $employees_ids = [];

        $this->authorize('waiting_for_approval_tickets-list');

        $userWithAdminRole = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin'); // Replace 'admin' with the actual role name.
        })->first();

        $department_users = Department::where('parent_department_id', 1)->where('manager_id', '!=', $userWithAdminRole->id)->get();
        foreach ($department_users as $department_user) {
            $emp_data = User::where('id', $department_user->manager_id)->where('status', 1)->where('is_employee', 1)->first(['id', 'first_name', 'last_name', 'slug']);
            if (!empty($emp_data)) {
                $employees_ids[] = $emp_data->id;
            }
        }
        $all_it_tickets = Ticket::orderby('id', 'desc')->whereHas('hasEmployee')->where('ticket_category_id', 1)->get(); //IT Equipment
        $model = [];

        foreach ($all_it_tickets as $it_ticket) {
            if (in_array($it_ticket->user_id, $employees_ids)) {
                $model[] = $it_ticket;
            } elseif (!empty($it_ticket->is_manager_approved)) {
                $model[] = $it_ticket;
            }
        }

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved By Manager: ' . date('d M Y h:i', strtotime($model->is_manager_approved)) . '">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin: ' . date('d M Y h:i', strtotime($model->is_concerned_approved)) . '">Approved By Admin</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('ticket_category_id', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->hasCategory->name . '</span>' ?? '-';
                })
                ->editColumn('reason_id', function ($model) {
                    return $model->hasReason->name ?? '-';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.tickets.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.tickets.action', ['data' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'ticket_category_id', 'action'])
                ->make(true);
        }

        return view('admin.tickets.waiting_for_approval_ticekts', compact('title', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ticket_category_id' => 'required',
            'subject' => 'required|max:255',
            'note' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $attachment = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment_file = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('admin/assets/ticket_attachments'), $attachment_file);
                $attachment = $attachment_file;
            }

            $model = Ticket::create([
                'user_id' => Auth::user()->id,
                'ticket_category_id' => $request->ticket_category_id,
                'reason_id' => $request->reason_id,
                'subject' => $request->subject,
                'note' => $request->note,
                'attachment' => $attachment,
            ]);
            if ($model) {

                $user = Auth::user();
                $role = $user->getRoleNames()->first();
                foreach ($user->getRoleNames() as $user_role) {
                    if ($user_role == 'Admin') {
                        $role = $user_role;
                    } elseif ($user_role == 'Department Manager') {
                        $role = $user_role;
                    }
                }
                if ($role == 'Department Manager') {
                    $parent_department = Department::where('manager_id', $user->id)->first();
                    $manager = $parent_department->parentDepartment->manager;
                } elseif ($role == 'Employee') {
                    $manager = $user->departmentBridge->department->manager;
                }

                $notification_data = [
                    'id' => $model->id,
                    'date' => date('d-m-Y', strtotime($model->created_at)),
                    'type' => $request->subject,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'profile' => $user->profile->profile,
                    'title' => 'has applied for ' . $model->hasCategory->name . ' ticket',
                    'reason' => $request->note,
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $manager->notify(new ImportantNotification($notification_data));
                }
                if ($request->ticket_category_id == "3" || $request->ticket_category_id == "2") {
                    $model->update([
                        'status' => 2,
                        'is_manager_approved' => Carbon::now()->toDateTimeString(),
                        'is_concerned_approved' => Carbon::now()->toDateTimeString(),
                    ]);
                }
                \LogActivity::addToLog('New Ticket Added');
                DB::commit();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            // return response()->json(['error' => $e->getMessage()]);
            return $e->getMessage();
        }
    }

    public function show($model_id)
    {
        $model = Ticket::findOrFail($model_id);
        return (string) view('admin.tickets.show_content', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data  = [];
        $this->authorize('tickets-edit');
        $model = Ticket::where('id', $id)->first();
        $data['reasons'] = TicketReason::orderby('id', 'desc')->where('status', 1)->get();
        $data['ticket_categories'] = TicketCategory::where('status', 1)->get();
        return (string) view('admin.tickets.edit_content', compact('model', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'ticket_category_id' => 'required',
            'subject' => 'required|max:255',
            'note' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $model = Ticket::where('id', $request->id)->first();

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment_file = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('admin/assets/ticket_attachments'), $attachment_file);

                if (!empty($model->attachment)) {
                    $filePath = public_path('admin/assets/ticket_attachments/' . $model->attachment);
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete the file using unlink
                    }
                }

                $model->attachment = $attachment_file;
            }

            $model->user_id = Auth::user()->id;
            $model->ticket_category_id = $request->ticket_category_id;
            $model->reason_id = $request->reason_id;
            $model->subject = $request->subject;
            $model->note = $request->note;
            $model->save();

            if ($model) {
                $user = Auth::user();
                $role = $user->getRoleNames()->first();
                foreach ($user->getRoleNames() as $user_role) {
                    if ($user_role == 'Admin') {
                        $role = $user_role;
                    } elseif ($user_role == 'Department Manager') {
                        $role = $user_role;
                    }
                }
                if ($role == 'Department Manager') {
                    $parent_department = Department::where('manager_id', $user->id)->first();
                    $manager = $parent_department->parentDepartment->manager;
                } elseif ($role == 'Employee') {
                    $manager = $user->departmentBridge->department->manager;
                }

                $notification_data = [
                    'id' => $model->id,
                    'date' => date('d-m-Y', strtotime($model->created_at)),
                    'type' => $request->subject,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'profile' => $user->profile->profile,
                    'title' => 'has updated ticket applied for ' . $model->hasCategory->name,
                    'reason' => $request->note,
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $manager->notify(new ImportantNotification($notification_data));
                }

                \LogActivity::addToLog('Ticket Updated');

                DB::commit();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('tickets-delete');
        if(isset($ticket) && !empty($ticket)){
            $historyArray['model_id'] = $ticket->id;
            $historyArray['model_name'] = "\App\Models\Ticket";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Ticket has been deleted";
            $model = $ticket->delete();
            if($model) {
                LogActivity::addToLog('Ticket Deleted');
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
        $title = 'All Trashed Tickets';
        $user = Auth::user();
        if ($request->ajax()) {
            $model = Ticket::onlyTrashed()->where('user_id', Auth::user()->id)->orderby('id', 'desc')->get();
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Pending">Pending</span>';
                            break;
                        case 1:
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Approved By Manager: ' . date('d M Y h:i', strtotime($model->is_manager_approved)) . '">Approved By RA</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Approved By Admin: ' . date('d M Y h:i', strtotime($model->is_concerned_approved)) . '">Approved By Admin</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('ticket_category_id', function ($model) {
                    return $model->hasCategory->name ?? '-';
                })
                ->editColumn('reason_id', function ($model) {
                    return $model->hasReason->name ?? '-';
                })
                ->editColumn('user_id', function ($model) {
                    return view('admin.tickets.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    $button = '<a href="' . route('tickets.restore', $model->id) . '" class="btn btn-icon btn-label-info waves-effect">' .
                        '<span>' .
                        '<i class="ti ti-refresh ti-sm"></i>' .
                        '</span>' .
                        '</a>';
                    return $button;
                })
                ->rawColumns(['user_id', 'status', 'action'])
                ->make(true);
        }

        return view('admin.tickets.index', compact('title', 'user'));
    }
    public function restore($id)
    {
        $find = Ticket::onlyTrashed()->where('id', $id)->first();
        if(isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\Ticket";
            $historyArray['type'] = "2";
            $historyArray['remarks'] = "Ticket has been restored";
            $restore = $find->restore();
            if(!empty($restore)) {
                LogActivity::deleteHistory($historyArray);
                return redirect()->back()->with('message', 'Record Restored Successfully.');
            }
        } else {
            return false;
        }
    }

    public function status($id)
    {
        $logined_user = Auth::user();

        $role = $logined_user->getRoleNames()->first();
        foreach ($logined_user->getRoleNames() as $user_role) {
            if ($user_role == 'Department Manager') {
                $role = $user_role;
            }
        }

        $ticket = Ticket::where('id', $id)->first();
        if ($ticket->user_id == $logined_user->id && ($ticket->status == 2 || $ticket->status == 1)) {
            $ticket->status = 3;
            $ticket->save();

            $notification_data = [
                'id' => $ticket->id,
                'date' => date('d-m-Y', strtotime($ticket->created_at)),
                'type' => $ticket->subject,
                'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                'profile' => $logined_user->profile->profile,
                'title' => 'Ticket of ' . $ticket->hasCategory->name . ' has been completed.',
                'reason' => 'Completed.',
            ];

            if (isset($notification_data) && !empty($notification_data)) {
                if (isset($ticket->hasEmployee->departmentBridge->department->manager) && !empty($ticket->hasEmployee->departmentBridge->department->manager)) {
                    $ticket->hasEmployee->departmentBridge->department->manager->notify(new ImportantNotification($notification_data));
                }
                if (isset($ticket->hasEmployee->departmentBridge->department->manager) && !empty($ticket->hasEmployee->departmentBridge->department->manager) && !$ticket->hasEmployee->departmentBridge->department->manager->hasRole('Admin')) {
                    $admin_user = User::whereHas('roles', function ($query) {
                        $query->where('name', 'Admin');
                    })->first();
                    $admin_user->notify(new ImportantNotification($notification_data));
                }
            }
            return response()->json(['success' => true]);
        }
        if ($role == 'Department Manager') {
            $ticket->status = 1;
            $ticket->is_manager_approved = now();
            $ticket->save();

            $ticket_cat = TicketCategory::where('id', $ticket->ticket_category_id)->first();
            if ($ticket_cat->name == 'IT Rapid Support') {
                $department = Department::where('name', 'IT Department')->where('status', 1)->first();
                if (!empty($department)) {
                    $it_manager = $department->manager;

                    $notification_data = [
                        'id' => $ticket->id,
                        'date' => date('d-m-Y', strtotime($ticket->created_at)),
                        'type' => $ticket->subject,
                        'name' => $it_manager->first_name . ' ' . $it_manager->last_name,
                        'profile' => $it_manager->profile->profile,
                        'title' => 'Manager has approved ticket.',
                        'reason' => 'Approved.',
                    ];

                    if (isset($notification_data) && !empty($notification_data)) {
                        $it_manager->notify(new ImportantNotification($notification_data));
                    }
                }
            } elseif ($ticket_cat->name == 'Finance' || $ticket_cat->name == 'Fleet') {
                $department = Department::where('name', 'Admin')->where('status', 1)->first(); //Admin is sub department of Account & finance
                if (!empty($department)) {
                    $finance_manager = $department->manager;

                    $notification_data = [
                        'id' => $ticket->id,
                        'date' => date('d-m-Y', strtotime($ticket->created_at)),
                        'type' => $ticket->subject,
                        'name' => $finance_manager->first_name . ' ' . $finance_manager->last_name,
                        'profile' => $finance_manager->profile->profile,
                        'title' => 'Manager has approved ticket.',
                        'reason' => 'Approved.',
                    ];

                    if (isset($notification_data) && !empty($notification_data)) {
                        $finance_manager->notify(new ImportantNotification($notification_data));
                    }
                }
            } elseif ($ticket_cat->name == 'IT Equipment') {
                $userWithAdminRole = User::whereHas('roles', function ($query) {
                    $query->where('name', 'Admin'); // Replace 'admin' with the actual role name.
                })->first();

                $notification_data = [
                    'id' => $ticket->id,
                    'date' => date('d-m-Y', strtotime($ticket->created_at)),
                    'type' => $ticket->subject,
                    'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                    'profile' => $logined_user->profile->profile,
                    'title' => 'Manager has approved ticket.',
                    'reason' => 'Approved.',
                ];

                if (isset($notification_data) && !empty($notification_data)) {
                    $userWithAdminRole->notify(new ImportantNotification($notification_data));
                }
            }

            $notification_data = [
                'id' => $ticket->id,
                'date' => date('d-m-Y', strtotime($ticket->created_at)),
                'type' => $ticket->subject,
                'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                'profile' => $logined_user->profile->profile,
                'title' => 'Your request for ' . $ticket->hasCategory->name . ' ticket has been approved by manager.',
                'reason' => 'Approved.',
            ];

            if (isset($notification_data) && !empty($notification_data)) {
                $ticket->hasEmployee->notify(new ImportantNotification($notification_data));
            }

            \LogActivity::addToLog('Approved Ticket by Manager');
        } else {
            $ticket->status = 2;
            if ($ticket->is_manager_approved == null) {
                $ticket->is_manager_approved = now();
            }

            $ticket->is_concerned_approved = now();
            $ticket->save();

            $ticket_cat = TicketCategory::where('id', $ticket->ticket_category_id)->first();
            if ($ticket_cat->name == 'IT Equipment') {
                $department = Department::where('name', 'IT Department')->where('status', 1)->first();
                if (!empty($department)) {
                    $it_manager = $department->manager;

                    $notification_data = [
                        'id' => $ticket->id,
                        'date' => date('d-m-Y', strtotime($ticket->created_at)),
                        'type' => $ticket->subject,
                        'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                        'profile' => $logined_user->profile->profile,
                        'title' => 'has approved this ticket.',
                        'reason' => 'Approved.',
                    ];

                    if (isset($notification_data) && !empty($notification_data)) {
                        $it_manager->notify(new ImportantNotification($notification_data));
                    }
                }
            }

            $notification_data = [
                'id' => $ticket->id,
                'date' => date('d-m-Y', strtotime($ticket->created_at)),
                'type' => $ticket->subject,
                'name' => $logined_user->first_name . ' ' . $logined_user->last_name,
                'profile' => $logined_user->profile->profile,
                'title' => 'Your request for ' . $ticket->hasCategory->name . ' ticket has been approved by admin.',
                'reason' => 'Approved.',
            ];

            if (isset($notification_data) && !empty($notification_data)) {
                $ticket->hasEmployee->notify(new ImportantNotification($notification_data));
            }

            \LogActivity::addToLog('Approved Ticket by Admin');
        }

        return response()->json(['success' => true]);
    }
}
