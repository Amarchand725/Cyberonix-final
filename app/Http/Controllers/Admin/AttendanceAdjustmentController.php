<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use App\Models\User;
use App\Helpers\LogActivity;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AttendanceSummary;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\AttendanceAdjustment;
use App\Models\Attendance;
use App\Models\WorkingShiftUser;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ImportantNotification;

class AttendanceAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $user_slug = null)
    {
        $this->authorize('mark_attendance-list');
        $title = 'All Attendance Adjustments';

        $data = [];
        $url = '';
        $data['employees'] = User::where('is_employee', 1)->where('status', 1)->get();

        if (!empty($user_slug)) {
            $user = User::where('slug', $user_slug)->first();
            $url = URL::to('mark_attendance/' . $user->slug);
            // $model = AttendanceAdjustment::where('employee_id', $user->id)->get();

            $model = [];
            AttendanceAdjustment::where('employee_id', $user->id)
                ->latest()
                ->chunk(100, function ($adjustments) use (&$model) {
                    foreach ($adjustments as $adjustment) {
                        $model[] = $adjustment;
                    }
                });
        } else {
            $user = Auth::user();
            // $model = AttendanceAdjustment::latest()->get();

            $model = [];
            AttendanceAdjustment::latest()
                ->chunk(100, function ($adjustments) use (&$model) {
                    foreach ($adjustments as $adjustment) {
                        $model[] = $adjustment;
                    }
                });
        }

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('mark_type', function ($model) {
                    $label = '';

                    switch ($model->mark_type) {
                        case 'absent':
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Absent">Absent</span>';
                            break;
                        case 'halfday':
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Half Day Leave">Half Day</span>';
                            break;
                        case 'lateIn':
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Late In">Late In</span>';
                            break;
                        case 'fullday':
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Full Day">Full Day</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('attendance_id', function ($model) {
                    if (!empty($model->attendance_id)) {
                        $value = "";

                        // if date
                        if (Carbon::hasFormat($model->attendance_id, 'Y-m-d')) {
                            $value = '<span class="fw-semibold">' . date('d M Y', strtotime($model->attendance_id)) . '</span>';
                        }


                        // if not date
                        if (Carbon::hasFormat($model->attendance_id, 'Y-m-d') == false) {
                            $adjustment = AttendanceSummary::whereRaw('CAST(attendance_id AS CHAR) = ?', [$model->attendance_id])->first();
                            if (!empty($adjustment)) {
                                $value = '<span class="fw-semibold">' . date('d M Y', strtotime($adjustment->in_date));
                            } else {
                                // if not exist in attendance summary table
                                $attendance = Attendance::where('id', $model->attendance_id)->first();
                                $value = '<span class="fw-semibold">' . date('d M Y', strtotime($attendance->in_date));
                            }
                        }
                    } else {
                        $value = null;
                    }
                    return $value;
                })
                ->editColumn('employee_id', function ($model) {
                    return view('admin.attendance_adjustments.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.attendance_adjustments.action', ['model' => $model])->render();
                })
                ->rawColumns(['mark_type', 'employee_id', 'attendance_id', 'action'])
                ->make(true);
        }

        return view('admin.attendance_adjustments.index', compact('title', 'user', 'data', 'url'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $model = AttendanceAdjustment::where('attendance_id', $request->attendance_id)->where('employee_id', $request->user_id)->first();

            $mark_type = $request->mark_type;
            if ($mark_type == 'firsthalf') {
                $mark_type = 'halfday';
            }

            if (!empty($model)) {
                $model->created_by = Auth::user()->id;
                $model->employee_id = $request->user_id;
                $model->mark_type = $mark_type;
                $model->save();

                \LogActivity::addToLog('New Attendance Adjustment Mark Added');
            } else {
                $model = AttendanceAdjustment::create([
                    'created_by' => Auth::user()->id,
                    'employee_id' => $request->user_id,
                    'attendance_id' => $request->attendance_id,
                    'mark_type' => $mark_type,
                ]);

                \LogActivity::addToLog('New Attendance Adjustment Mark Added');
            }

            if ($model) {
                $adjustedDate = '';
                $adjustment = AttendanceSummary::where('attendance_id', $model->attendance_id)->first();
                if (!empty($adjustment)) {
                    $adjustedDate = date('d M Y', strtotime($adjustment->in_date));
                } else {
                    $adjustedDate = date('d M Y', strtotime($model->attendance_id));
                }
            }

            DB::commit();

            $login_user = Auth::user();

            $notification_data = [
                'id' => $model->id,
                'date' => $adjustedDate,
                'type' => $mark_type,
                'name' => $login_user->first_name . ' ' . $login_user->last_name,
                'profile' => $login_user->profile->profile,
                'title' => 'Your attendance date "' . $adjustedDate . '" has been adjusted',
                'reason' => 'Adjusted.',
            ];

            if ($login_user->id != $model->hasEmployee->id) {
                if (isset($notification_data) && !empty($notification_data)) {
                    $model->hasEmployee->notify(new ImportantNotification($notification_data));
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            // return response()->json(['error' => $e->getMessage()]);
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('tickets-delete');
        $find = AttendanceAdjustment::where('id', $id)->first();
        if (isset($find) && !empty($find)) {
            $historyArray['model_id'] = $find->id;
            $historyArray['model_name'] = "\App\Models\AttendanceAdjustment";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "Attendance Adjustment has been deleted";
            $model = $find->delete();
            if ($model) {
                LogActivity::addToLog('Attendance Adjustment Deleted');
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

    public function getMarkAttendanceByAdmin(Request $request)
    {

        $title = 'Mark Attendance By Admin';

        $users = User::where('is_employee', 1)->where('status', 1)->get();

        return view('admin.attendance_adjustments.mark-attendance-by-admin', compact('title', 'users'));
    }

    public function markAttendanceByAdmin(Request $request)
    {

        try {
            $createdBy = Auth::user();
            $users = User::whereIn('id', $request->selectedUsers)->get();
            $attAdjustment = new AttendanceAdjustment();
            if (strpos($request->dates, ' to ') !== false) {
                list($startDate, $endDate) = explode(' to ', $request->dates);
                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);
            } else {
                $startDate = $request->dates;
                $endDate = $request->dates;
                $start = Carbon::parse($request->dates);
                $end = Carbon::parse($request->dates);
            }
            foreach ($users as $user) {
                $shift = WorkingShiftUser::where('user_id', $user->id)->where('end_date', NULL)->first();
                if (empty($shift)) {
                    $shift = defaultShift();
                } else {
                    $shift = $shift->workShift;
                }
                $statistics = AttendanceController::getAttandanceCount($user->id,  $start, $end, 'all', $shift);
                $absent_dates =  collect($statistics['absent_dates'])->pluck('date');
                $halfDayDates =  collect($statistics['halfDayDates'])->pluck('date');
                $earlyOutDates =  collect($statistics['earlyOutDates'])->pluck('date');
                $absent_dates = $absent_dates->map(function ($dateString) {
                    $carbonDate = Carbon::createFromFormat('d M, Y', $dateString);
                    return $carbonDate->format('Y-m-d');
                })->toArray();
                $halfDayDates = $halfDayDates->map(function ($dateString) {
                    $carbonDate = Carbon::createFromFormat('d M, Y', $dateString);
                    return $carbonDate->format('Y-m-d');
                })->toArray();
                $earlyOutDates = $earlyOutDates->map(function ($dateString) {
                    $carbonDate = Carbon::createFromFormat('d M, Y', $dateString);
                    return $carbonDate->format('Y-m-d');
                })->toArray();
                $finalArray = array_merge($absent_dates, $halfDayDates, $earlyOutDates);
                sort($finalArray);

                $date = Carbon::parse($startDate); // Use the initial start date for each user
                while ($date->lte($end)) {
                    if (in_array($date->toDateString(), $finalArray)) {
                        $dateforname = Carbon::parse($date);
                        $in_date = $date->toDateString();
                        $dayName = $dateforname->format('D');
                        if ($dayName != 'Sun' && $dayName != 'Sat') {
                            $attAdjustment = AttendanceAdjustment::whereDate("attendance_id", $in_date)->first(); // Create a new instance for each record
                            if (empty($attAdjustment)) {
                                AttendanceAdjustment::create([
                                    "created_by" => $createdBy->id,
                                    "employee_id" => $user->id,
                                    "attendance_id" => $in_date,
                                    "mark_type" => $request->behavior,
                                ]);
                            }
                        }
                    }
                    $date->addDay();
                }
            }

            return response()->json(['success' => true, 'message' => 'record inserted successfully', 'route' => route('mark_attendence_redirect', $createdBy->slug)]);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
