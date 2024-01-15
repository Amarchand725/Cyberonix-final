<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\UserLeave;
use App\Models\Attendance;
use App\Models\AttendanceSummary;
use App\Models\Department;
use App\Models\Discrepancy;
use Illuminate\Http\Request;
use App\Models\DepartmentUser;
use App\Models\WorkingShiftUser;
use App\Models\EmploymentStatus;
use App\Models\UserEmploymentStatus;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Jobs\ExportAttendance;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function summary($getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('admin_summary-list');
        $title = 'Attendance Summary';

        $employees = [];

        $user = Auth::user()->load('profile', 'employeeStatus', 'userWorkingShift');
        $employees = User::where('id', '!=', $user->id)->where('status', 1)->where('is_employee', 1)->select(['id', 'slug', 'first_name', 'last_name', 'email'])->get();

        $currentMonth = date('m/Y');
        if (date('d') > 25) {
            $currentMonth = date('m/Y', strtotime('first day of +1 month'));
        }
        if (!empty($getMonth) || !empty($user_slug)) {
            $year = $getYear;
            $month = $getMonth;

            $user = User::where('slug', $user_slug)->first();
        } else {
            $year = date('Y');
            if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
                $month = date('m', strtotime('first day of +1 month'));
            } else {
                $month = date('m');
            }
            if ($month == 01) {
                $year = date('Y', strtotime('first day of +1 month'));
            }

            $user = getUser();
        }

        $shift = WorkingShiftUser::where('user_id', $user->id)->where('end_date', NULL)->first();
        if (empty($shift)) {
            $shift = defaultShift();
        } else {
            $shift = $shift->workShift;
        }

        //User Leave & Discrepancies Reprt
        $leave_report = hasExceededLeaveLimit($user);
        if ($leave_report) {
            $leave_in_balance = $leave_report['leaves_in_balance'];
        } else {
            $leave_in_balance = 0;
        }

        $user_have_used_discrepancies = Discrepancy::where('user_id', $user->id)->where('status', '!=', 2)->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->count();

        $user_joining_date = date('d-m-Y');

        if (isset($user->profile) && !empty($user->profile->joining_date)) {
            $user_joining_date = date('m/Y', strtotime($user->profile->joining_date));
        }

        $leave_types = LeaveType::where('status', 1)->get(['id', 'name']);

        $user_leave_report = hasExceededLeaveLimit($user);
        $remaining_filable_leaves = $user_leave_report['total_remaining_leaves'];

        return view('user.attendance.summary', compact('title', 'user', 'user_joining_date', 'shift', 'month', 'year', 'currentMonth', 'employees', 'leave_types', 'remaining_filable_leaves'));
    }

    public function employeeSummary($getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('employee_summary-list');
        $title = 'Attendance Summary';
        $logined_user = Auth::user()->load('profile', 'employeeStatus', 'userWorkingShift');

        $employees = [];

        if ($logined_user->hasRole('Department Manager')) {
            $employees = getTeamMembers($logined_user);
        }

        $currentMonth = date('m/Y');
        if (date('d') > 25) {
            $currentMonth = date('m/Y', strtotime('first day of +1 month'));
        }
        if (!empty($getMonth) || !empty($user_slug)) {
            $year = $getYear;
            $month = $getMonth;

            $user = User::with('profile', 'employeeStatus', 'userWorkingShift')->where('slug', $user_slug)->select(['id', 'slug', 'first_name', 'last_name','email'])->first();
        } else {
            $year = date('Y');
            if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
                $month = date('m', strtotime('first day of +1 month'));
            } else {
                $month = date('m');
            }

            if ($month == 01) {
                $year = date('Y', strtotime('first day of +1 month'));
            }

            $user = $logined_user;
        }

        $shift = $user->userWorkingShift;
        if (empty($shift)) {
            $shift = defaultShift();
        } else {
            $shift = $shift->workShift;
        }

        //User Leave & Discrepancies Reprt
        $leave_report = hasExceededLeaveLimit($user);
        if ($leave_report) {
            $leave_in_balance = $leave_report['leaves_in_balance'];
            $remaining_filable_leaves = $leave_report['total_remaining_leaves'];
        } else {
            $leave_in_balance = 0;
        }

        $user_have_used_discrepancies = Discrepancy::where('user_id', $user->id)->where('status', '!=', 2)->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->count();

        $user_joining_date = date('d-m-Y');
        if (isset($user->joiningDate->joining_date) && !empty($user->joiningDate->joining_date)) {
            $user_joining_date = date('m/Y', strtotime($user->joiningDate->joining_date));
        }

        $leave_types = LeaveType::where('status', 1)->select(['id', 'name'])->get();

        return view('user.attendance.employee-summary', compact('title', 'user', 'user_joining_date', 'shift', 'month', 'year', 'currentMonth', 'employees', 'leave_types', 'remaining_filable_leaves'));
    }

    public function terminatedEmployeeSummary($getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('terminated_employee_summary-list');
        $data = [];

        $data['title'] = 'Terminated Employee Summary';

        $employees = [];
        $employment_status = EmploymentStatus::where('name', 'Terminated')->first();
        $terminated_employee_ids = UserEmploymentStatus::where('employment_status_id', $employment_status->id)->pluck('id')->toArray();
        $data['employees'] = User::with('profile', 'employeeStatus')->whereIn('id', $terminated_employee_ids)->select(['id', 'first_name', 'last_name', 'slug', 'email'])->get();

        $data['currentMonth'] = date('m/Y');
        if (!empty($getMonth) || !empty($user_slug)) {
            $data['month'] = $getMonth;
            $data['year'] = $getYear;

            $currentMonth = $getMonth . '/' . $getYear;
            $data['currentMonth'] = $currentMonth;

            $user = User::with('profile', 'employeeStatus', 'userWorkingShift')->where('slug', $user_slug)->select(['id', 'first_name', 'last_name', 'slug', 'email'])->first();
            $data['user_slug'] = $user_slug;

            $shift = $user->userWorkingShift;
            if (empty($shift)) {
                $shift = defaultShift();
            } else {
                $shift = $shift->workShift;
            }

            $data['shift'] = $shift;

            //User Leave & Discrepancies Reprt
            $leave_report = hasExceededLeaveLimit($user);
            if ($leave_report) {
                $leave_in_balance = $leave_report['leaves_in_balance'];
            } else {
                $leave_in_balance = 0;
            }

            $data['leave_in_balance'] = $leave_in_balance;

            $user_have_used_discrepancies = Discrepancy::where('user_id', $user->id)->where('status', '!=', 2)->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->count();

            $remaining_filable_discrepancies = settings()->max_discrepancies - $user_have_used_discrepancies;
            if ($remaining_filable_discrepancies > 0) {
                $remaining_filable_discrepancies = $remaining_filable_discrepancies;
            } else {
                $remaining_filable_discrepancies = 0;
            }
            $data['remaining_filable_discrepancies'] = $remaining_filable_discrepancies;
            $data['remaining_filable_leaves'] = $leave_in_balance;
            //User Leave & Discrepancies Reprt

            $user_joining_date = date('d-m-Y');
            if (isset($user->joiningDate->joining_date) && !empty($user->joiningDate->joining_date)) {
                $user_joining_date = date('m/Y', strtotime($user->joiningDate->joining_date));
            }

            $data['user_joining_date'] = $user_joining_date;

            $data['leave_types'] = LeaveType::where('status', 1)->get(['id', 'name']);
            $data['user'] = $user;
        } else {
            $data['user'] = Auth::user();
        }

        return view('user.attendance.terminated_emp_summary', compact('data'));
    }

    public function advanceFilterSummary(Request $request)
    {
        $this->authorize('admin_attendance_filter-list');
        $title = 'Attendance Summary';
        $data = [];

        $user = Auth::user()->load('profile', 'employeeStatus', 'userWorkingShift');

        $employees = [];
        $departments = [];
        $department_id = '';

        $employees = User::where('id', '!=', $user->id)->where('status', 1)->where('is_employee', 1)->get();
        $departments = Department::where('status', 1)->latest()->get();

        if ($request->ajax()) {
            $users = [];

            $filter_date = explode('to', $request->filter_date);
            $from_date = $filter_date[0];
            if (isset($filter_date[1])) {
                $to_date = $filter_date[1];
            } else {
                $to_date = $filter_date[0];
            }

            if (isset($request->filter_behavior) && !empty($request->filter_behavior)) {
                $behavior = $request->filter_behavior;
            }

            $all_employee_ids = [];
            $filter_employees = json_decode($request['employees']);
            if (!empty($filter_employees) && count($filter_employees) > 0) {
                if ($filter_employees[0] == 'All') {
                    foreach ($employees as $employee) {
                        $all_employee_ids[] = $employee->id;
                    }
                } else {
                    $all_employee_ids = $filter_employees;
                }
            }

            $all_department_ids = [];
            $filter_departments = json_decode($request['departments']);
            if (!empty($filter_departments) && count($filter_departments) > 0) {
                if ($filter_departments[0] == 'All') {
                    foreach ($departments as $department) {
                        $all_department_ids[] = $department->id;
                    }
                } else {
                    $all_department_ids = $filter_departments;
                }
            }

            $employees = $all_employee_ids;
            if (!empty($all_department_ids) && count($all_department_ids) > 0) {
                if (!empty($all_employee_ids)) {
                    $department_users = DepartmentUser::whereIn('department_id', $all_department_ids)->whereIn('user_id', $all_employee_ids)->get();
                } else {
                    $department_users = DepartmentUser::whereIn('department_id', $all_department_ids)->get();
                }

                foreach ($department_users as $department_user) {
                    $dep_user = User::where('id', $department_user->user_id)->where('status', 1)->where('is_employee', 1)->first();
                    if (!empty($dep_user)) {
                        $users[] = $dep_user;
                    }
                }
            } else {
                $users = User::whereIn('id', $employees)->where('status', 1)->where('is_employee', 1)->get();
            }

            $data['from_date'] = date('Y-m-d', strtotime($from_date));
            $data['to_date'] = date('Y-m-d', strtotime($to_date));
            $data['behavior'] = $behavior;
            $data['users'] = $users;

            return (string) view('user.attendance.filter-summary-content', compact('data'));
        }

        $data['employees'] = $employees;

        return view('user.attendance.filter-summary', compact('title', 'user', 'data', 'departments'));
    }

    public function employteeAdvanceFilterSummary(Request $request)
    {
        $this->authorize('employee_attendance_filter-list');
        $title = 'Attendance Summary';
        $data = [];
        $user = Auth::user()->load('profile', 'employeeStatus', 'userWorkingShift');

        $employees = [];
        $departments = [];
        $department_ids = [];

        if ($user->hasRole('Department Manager')) {
            $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
            $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
            $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
            if (!empty($child_departments) && count($child_departments) > 0) {
                $department_ids = array_unique(array_merge($department_ids, $child_departments));
            }
            $departments = Department::whereIn('id', $department_ids)->where('status', 1)->latest()->get();

            $employees = getTeamMembers($user);
        }

        if ($request->ajax()) {
            $users = [];

            $filter_date = explode('to', $request->filter_date);
            $from_date = $filter_date[0];
            if (isset($filter_date[1])) {
                $to_date = $filter_date[1];
            } else {
                $to_date = $filter_date[0];
            }

            if (isset($request->filter_behavior) && !empty($request->filter_behavior)) {
                $behavior = $request->filter_behavior;
            }

            $all_employee_ids = [];
            $filter_employees = json_decode($request['employees']);
            if (!empty($request['employees']) && count($filter_employees) > 0) {
                if ($filter_employees[0] == 'All') {
                    foreach ($employees as $employee) {
                        $all_employee_ids[] = $employee->id;
                    }
                } else {
                    $all_employee_ids = $filter_employees;
                }
            }

            $all_department_ids = [];
            $filter_departments = json_decode($request['departments']);
            if (!empty($request['departments']) && count($filter_departments) > 0) {
                if ($filter_departments[0] == 'All') {
                    foreach ($departments as $department) {
                        $all_department_ids[] = $department->id;
                    }
                } else {
                    $all_department_ids = $filter_departments;
                }
            }

            $employees = $all_employee_ids;
            if (isset($request['departments']) && !empty($all_department_ids)) {
                $query = DepartmentUser::orderby('id', 'desc')->where('id', '>', 0);
                $query->whereIn('department_id', $all_department_ids);
                if (!empty($employees) && count($employees) > 0) {
                    $query->whereIn('user_id', $employees);
                }
                $department_users = $query->get();

                foreach ($department_users as $department_user) {
                    $dep_user = User::where('id', $department_user->user_id)->where('status', 1)->where('is_employee', 1)->first();
                    if (!empty($dep_user)) {
                        $users[] = $dep_user;
                    }
                }
            } else {
                $users = User::whereIn('id', $employees)->where('status', 1)->where('is_employee', 1)->get();
            }

            $data['from_date'] = date('Y-m-d', strtotime($from_date));
            $data['to_date'] = date('Y-m-d', strtotime($to_date));
            $data['behavior'] = $behavior;
            $data['users'] = $users;

            return (string) view('user.attendance.filter-summary-content', compact('data'));
        }

        $data['employees'] = $employees;

        return view('user.attendance.employee-filter-summary', compact('title', 'user', 'data', 'departments'));
    }

    public static function getAttandanceCount($userID, $start_date, $end_date, $status, $shiftID)
    {
        $begin = new DateTime($start_date);
        $end   = new DateTime($end_date);
        $totalDays = 0;
        $workDays = 0;
        $lateIn = 0;
        $lateInDates = [];
        $earlyOut = 0;
        $earlyOutDates = [];
        $halfDay = 0;
        $halfDayDates = [];
        $absent = 0;
        $absent_dates = [];
        $discrepancy_late = 0;
        $discrepancy_early = 0;
        $leave_first_half = 0;
        $leave_last_half = 0;
        $leave_single = 0;
        $check_in_out_time = '';
        $user = User::where('id', $userID)->first();
        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $attendance_adjustment = '';
            $end_time = date("Y-m-d", strtotime($i->format("Y-m-d"))) . ' ' . $shiftID->end_time;

            $shiftEndTime = $shiftID->end_time;
            $shiftEndTime = date('H:i', strtotime($shiftEndTime));
            $carbonEndTime = Carbon::createFromFormat('H:i', $shiftEndTime);

            if ($carbonEndTime->hour < 12) {
                $next = date("Y-m-d", strtotime('+1 day ' . $i->format("Y-m-d")));
            } else {
                $next = date('Y-m-d', strtotime($end_time));
            }
            $beginDate = Carbon::parse($begin);
            $start_date = '';
            if ((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))) {
                $start_date = $user->employeeStatus->start_date;
                $start_date = Carbon::parse($start_date);
            }

            $start_time = date('Y-m-d', strtotime($beginDate)) . ' ' . $shiftID->start_time;
            $end_time = date("Y-m-d", strtotime($next)) . ' ' . $shiftID->end_time;
            $shiftStartTime = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
            $shiftEndTime = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));

            $day = date("D", strtotime($i->format("Y-m-d")));

            $checkHoliday = checkHoliday($userID, $i->format("Y-m-d")); //check it is holiday or company off
            if(empty($checkHoliday)){
                if ($day != 'Sat' && $day != 'Sun') {
                    $reponse = self::getAttandanceSingleRecord($userID, $i->format("Y-m-d"), $next, 'all', $shiftID);

                    if ($reponse != null) {
                        $attendance_date = $reponse['attendance_date'];
                        if (isset($reponse['attendance_id']) && !empty($reponse['attendance_id'])) {
                            $check_att = checkAttendanceByID($reponse['attendance_id']);
                            if (!empty($check_att)) {
                                $attendance_date = $check_att;
                            }
                        }

                        $attendance_adjustment = attendanceAdjustment($userID, $reponse['attendance_id'], $i->format("Y-m-d"));

                        if ($reponse['type'] == 'absent' && $i->format("Y-m-d") < date('Y-m-d') && empty($attendance_adjustment) || isset($attendance_adjustment) && !empty($attendance_adjustment) && $attendance_adjustment->mark_type == 'absent' && $i->format("Y-m-d") <= date('Y-m-d')) {
                            $absent++;

                            $applied_date = $reponse['applied_leaves'];
                            $marked_label = '';
                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $absent--;
                                }
                                $absent_dates[] = [
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'status' => $applied_date->status,
                                    'type' => $applied_date->behavior_type,
                                    'applied_at' => $applied_date->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $type = $reponse['type'];
                                $marked_label = '';
                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as Absent';
                                }
                                $absent_dates[] = [
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        }
                        if (isset($attendance_adjustment) && !empty($attendance_adjustment->mark_type) && $attendance_adjustment->mark_type == 'lateIn') {
                            $lateIn++;

                            $applied_date = $reponse['applied_discrepancy'];
                            $marked_label = '';
                            $check_in_out_time = '-';

                            $if_found = AttendanceSummary::where('user_id', $userID)->where('in_date', 'like', $i->format("Y-m-d") . '%')->first();
                            if (!empty($if_found)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            } elseif ($if_found = Attendance::where('behavior', 'I')->where('user_id', $userID)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'asc')->first()) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }

                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $lateIn--;
                                }

                                $lateInDates[] = [
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as Late In';
                                }
                                if (!empty($attendance_date) && !empty($reponse['attendance_id'])) {
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                } else {
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                $lateInDates[] = [
                                    'attendance_id' => $attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'behavior' => $behavior,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        }elseif($reponse['type'] == 'lateIn' && empty($attendance_adjustment)){
                            $lateIn++;

                            $applied_date = $reponse['applied_discrepancy'];
                            $marked_label = '';
                            $check_in_out_time = '-';

                            $if_found = AttendanceSummary::where('user_id', $userID)->where('in_date', 'like', $i->format("Y-m-d").'%')->first();
                            if (!empty($if_found)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }elseif($if_found = Attendance::where('behavior', 'I')->where('user_id', $userID)->whereBetween('in_date',[$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'asc')->first()){
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }

                            if(!empty($applied_date)){
                                if($applied_date->status==1){
                                    $lateIn--;
                                }

                                $lateInDates[] = [
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as '.$type;
                                }
                                if(!empty($attendance_date) && !empty($reponse['attendance_id'])){
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                }else{
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                $lateInDates[] = [
                                    'attendance_id' => $attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'behavior' => $behavior,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        }
                        if (isset($attendance_adjustment) && !empty($attendance_adjustment->mark_type) && $attendance_adjustment->mark_type == 'earlyout') {
                            $earlyOut++;

                            $applied_date = $reponse['applied_discrepancy'];
                            $check_in_out_time = '';

                            $if_found = AttendanceSummary::orderby('id', 'desc')->where('user_id', $userID)->where('in_date', 'like', $i->format("Y-m-d").'%')->first();
                            if (!empty($if_found)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }elseif($if_found = Attendance::where('behavior', 'O')->where('user_id', $userID)->whereBetween('in_date',[$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'desc')->first()){
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }
                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $earlyOut--;
                                }

                                $earlyOutDates[] = [
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as '.$type;
                                }
                                if(!empty($attendance_date) && !empty($reponse['attendance_id'])){
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                }else{
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                if (!empty($attendance_date)) {
                                    $earlyOutDates[] = [
                                        'attendance_id' => $attendance_id,
                                        'time' => $check_in_out_time,
                                        'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                        'behavior' => $behavior,
                                        'status' => '',
                                        'type' => $type,
                                    ];
                                }
                            }
                        }elseif ($reponse['type'] == 'earlyout' && empty($attendance_adjustment)) {
                            $earlyOut++;

                            $applied_date = $reponse['applied_discrepancy'];
                            $check_in_out_time = '';

                            $if_found = AttendanceSummary::orderby('id', 'desc')->where('user_id', $userID)->where('in_date', 'like', $i->format("Y-m-d").'%')->first();
                            if (!empty($if_found)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }elseif($if_found = Attendance::where('behavior', 'O')->where('user_id', $userID)->whereBetween('in_date',[$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'desc')->first()){
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }
                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $earlyOut--;
                                }

                                $earlyOutDates[] = [
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as '.$type;
                                }
                                if(!empty($attendance_date) && !empty($reponse['attendance_id'])){
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                }else{
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                if (!empty($attendance_date)) {
                                    $earlyOutDates[] = [
                                        'attendance_id' => $attendance_id,
                                        'time' => $check_in_out_time,
                                        'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                        'behavior' => $behavior,
                                        'status' => '',
                                        'type' => $type,
                                    ];
                                }
                            }
                        }

                        if ((isset($attendance_adjustment) && !empty($attendance_adjustment->mark_type) &&  ($attendance_adjustment->mark_type == 'halfday'))) {

                            $halfDay++;

                            $halfDayDate = $reponse['applied_leaves'];
                            $marked_label = '';
                            if (!empty($halfDayDate)) {
                                if ($halfDayDate->status == 1) {
                                    $halfDay--;
                                }
                                $halfDayDates[] = [
                                    'date' => date('d M, Y', strtotime($halfDayDate->start_at)),
                                    'status' => $halfDayDate->status,
                                    'type' => $halfDayDate->behavior_type,
                                    'applied_at' => $halfDayDate->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $in_date = '';
                                $behavior = '';
                                $time = '';

                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as Half Day';
                                }

                                if (!empty($attendance_date) && !empty($reponse['attendance_id'])) {
                                    $in_date = date('d M, Y', strtotime($attendance_date->in_date));
                                    $behavior = $attendance_date->behavior;
                                } else {
                                    $in_date = date('d M, Y', strtotime($attendance_date));
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                $halfDayDates[] = [
                                    'date' => $in_date,
                                    'time' => '-',
                                    'behavior' => $behavior,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        } elseif (($reponse['type'] == 'firsthalf' || $reponse['type'] == 'lasthalf') && empty($attendance_adjustment)) {
                            $halfDay++;

                            $halfDayDate = $reponse['applied_leaves'];
                            $marked_label = '';

                            $if_found = AttendanceSummary::where('user_id', $userID)->whereBetween('in_date',[$shiftStartTime, $shiftEndTime])->first();
                            if (!empty($if_found)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }elseif($if_found = Attendance::where('behavior', 'I')->where('user_id', $userID)->whereBetween('in_date',[$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'asc')->first()){
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }

                            if (!empty($halfDayDate)) {
                                if ($halfDayDate->status == 1) {
                                    $halfDay--;
                                }
                                $halfDayDates[] = [
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'status' => $halfDayDate->status,
                                    'type' => $halfDayDate->behavior_type,
                                    'applied_at' => $halfDayDate->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $in_date = '';
                                $behavior = '';
                                $time = '';

                                $type = $reponse['type'];

                                $halfDayDates[] = [
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'behavior' => $type,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        }
                        if ($reponse['punchIn'] != '-') {
                            $workDays++;
                        }
                    }
                    $totalDays++;
                } elseif ($i->format("Y-m-d") <= date('Y-m-d') && isset($user->employeeStatus->employmentStatus) && ($user->employeeStatus->employmentStatus->name == 'Permanent' || $user->employeeStatus->employmentStatus->name == 'Terminated') && $beginDate->greaterThanOrEqualTo($start_date)) {
                    if ($day == 'Sat') {
                        $date = Carbon::createFromFormat('Y-m-d', $i->format("Y-m-d"));
                        $nextDate = $date->copy()->addDays(2);
                        $secondNextDate = $nextDate->copy()->addDay();
                        $previousDate = $date->copy()->subDay();
                    } elseif ($day == 'Sun') {
                        $date = Carbon::createFromFormat('Y-m-d', $i->format("Y-m-d"));
                        $nextDate = $date->copy()->addDay();
                        $secondNextDate = $nextDate->copy()->addDay();

                        $previousDate = $date->copy()->subDays(2);
                    }
                    if ((checkAdjustedAttendance($userID, date('Y-m-d', strtotime($nextDate))) && checkAdjustedAttendance($userID, date('Y-m-d', strtotime($previousDate)))) && checkAttendance($userID, date('Y-m-d', strtotime($nextDate)), date('Y-m-d', strtotime($secondNextDate)), $shiftID) && checkAttendance($userID, date('Y-m-d', strtotime($previousDate)), $i->format("Y-m-d"), $shiftID)) {
                        $absent++;
                        $applied_date = userAppliedLeaveOrDiscrepency($userID, 'absent', date('Y-m-d', strtotime($date)));

                        $marked_label = '';
                        if (!empty($applied_date)) {
                            if ($applied_date->status == 1) {
                                $absent--;
                            }
                            $absent_dates[] = [
                                'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                'status' => $applied_date->status,
                                'type' => $applied_date->behavior_type,
                                'applied_at' => $applied_date->created_at,
                                'label' => $marked_label,
                            ];
                        } else {
                            $type = 'absent';
                            $absent_dates[] = [
                                'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                'status' => '',
                                'type' => $type,
                                'label' => $marked_label,
                            ];
                        }
                    }
                }
            }
        }

        $data = array(
            'totalDays' => $totalDays,
            'workDays' => $workDays,
            'lateIn' => $lateIn,
            'lateInDates' => $lateInDates,
            'earlyOut' => $earlyOut,
            'earlyOutDates' => $earlyOutDates,
            'halfDay' => $halfDay,
            'halfDayDates' => $halfDayDates,
            'absent' => $absent,
            'absent_dates' => $absent_dates,
            'discrepancy_late' => $discrepancy_late,
            'discrepancy_early' => $discrepancy_early,
            'leave_first_half' => $leave_first_half,
            'leave_last_half' => $leave_last_half,
            'leave_single' => $leave_single
        );

        return $data;
    }

    public static function getAttandanceSingleRecord($userID, $current_date, $next_date, $status, $shift)
    {
        $user = User::where('id', $userID)->first();
        $beginDate = Carbon::parse($current_date);
        $start_date = '';
        if ((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))) {
            $start_date = $user->employeeStatus->start_date;
            $start_date = Carbon::parse($start_date);
        }


        if ($shift->type == 'scheduled') {
            $scheduled = '(Flexible)';

            $shiftTiming = date("h:i A", strtotime($shift->start_time)) . ' - ' . date("h:i A", strtotime($shift->end_time)) . $scheduled;

            $start_time = date("Y-m-d H:i:s", strtotime($current_date . ' ' . $shift->start_time));
            $end_time = date("Y-m-d H:i:s", strtotime($next_date . ' ' . $shift->end_time));

            $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
            $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));

            $punchIn = Attendance::where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'I')->orderBy('in_date', 'asc')->first();
            $punchOut = Attendance::where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'O')->orderBy('in_date', 'desc')->first();

            $label = '-';
            $type = '';
            $workingHours = '-';
            $workingMinutes = 0;
            $checkSecond = true;
            $attendance_id = '';
            $checkOut = '';

            if ($punchIn != null) {
                $attendance_id = $punchIn->id;
                $punchInRecord = new DateTime($punchIn->in_date);
                $checkIn = $punchInRecord->format('h:i A');
                $label = '<span class="badge bg-label-success">Regular</span>';
                $type = 'regular';
            } else {
                $checkIn = '-';
            }

            if ($punchIn != null && $punchOut != null) {
                $h1 = new DateTime($punchIn->in_date);
                $h2 = new DateTime($punchOut->in_date);
                $diff = $h2->diff($h1);
                $workingHours = $diff->format('%H:%I');
                $workingMinutes = $diff->h * 60 + $diff->i;
            }
            $requiredMinutes = 8 * 60 + 30;
            $seven_hours = 7 * 60;

            if ($punchOut != null) {
                if ($punchIn == null) {
                    $attendance_id = $punchOut->id;
                }
                $punchOutRecord = new DateTime($punchOut->in_date);
                $checkOut = $punchOutRecord->format('h:i A');

                if ($workingMinutes < $seven_hours) {
                    $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                    $type = 'lasthalf';
                } elseif ($workingMinutes > $seven_hours && $workingMinutes < $requiredMinutes) {
                    $label = '<span class="badge bg-label-warning"><i class="far fa-dot-circle text-warning"></i> Early Out</span>';
                    $type = 'earlyout';
                }
            } else {
                $checkOut = '-';
            }

            if (($punchIn != null && $punchOut == null)) {
                if (date('Y-m-d') > date('Y-m-d', strtotime($end))) {
                    $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                    $type = 'lasthalf';
                } else {
                    $checkOut = 'Not Yet';
                }
            } elseif (($punchIn == null && $punchOut != null)) {
                if (date('Y-m-d') > date('Y-m-d', strtotime($end))) {
                    $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                    $type = 'firsthalf';
                }
            }

            $currentDatecheck = date('Y-m-d'); // Current date in 'Y-m-d' format
            $midnightTimestamp = strtotime($currentDatecheck . ' 00:00:00'); // Midnight timestamp

            if (($punchIn == null && $punchOut == null) && $beginDate->greaterThanOrEqualTo($start_date) && strtotime($current_date) < $midnightTimestamp) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Absent</span>';
                $type = 'absent';
                $attendance_date = $current_date;
                $checkIn = '-';
                $checkOut = '-';
            }

            $discrepancy = '';
            $discrepancyStatus = '';
            $applied_discrepancy = '';
            $attendance_date = '';

            if ($type == 'earlyout' && !empty($punchIn)) {
                $discrepancy_record = Discrepancy::where('attendance_id', $punchIn->log_id)->orWhereDate('date', date('Y-m-d', strtotime($punchIn->in_date)))->where('user_id', $userID)->first();
                if (!empty($discrepancy_record)) {
                    $discrepancy = $discrepancy_record->type;
                    $discrepancyStatus = $discrepancy_record->status;
                    $applied_discrepancy = $discrepancy_record;
                } else {
                    $attendance_date = $punchIn;
                    $attendance_id = $attendance_date->id;
                }
            }

            $leave = '';
            $applied_leaves = '';
            $leaveStatus = '';
            $punch_date = '';

            if ($type == 'absent') {
                $punch_date = date('Y-m-d', strtotime($current_date));
            } elseif ($type == 'lasthalf') {
                $punch_date = date('Y-m-d', strtotime($current_date));
            }

            if (isset($punch_date) && $punch_date != '') {
                if (userLeaveApplied($userID, $punch_date)) {
                    $leaves = userLeaveApplied($userID, $punch_date);
                } else {
                    $leaves = UserLeave::where('behavior_type', $type)->whereDate('start_at', $punch_date)->where('user_id', $userID)->first();
                }
                if (!empty($leaves)) {
                    $leave = $leaves->behavior_type;
                    $leaveStatus = $leaves->status;
                    $applied_leaves = $leaves;
                } else {
                    if ($type == 'absent') {
                        $attendance_date = $current_date;
                    } elseif ($type == "lasthalf" && $punchIn == '') {
                        $attendance_date = $current_date;
                    }
                }
            }
        } else {
            $scheduled = '';

            $shiftTiming = date("h:i A", strtotime($shift->start_time)) . ' - ' . date("h:i A", strtotime($shift->end_time)) . $scheduled;

            $start_time = date("Y-m-d H:i:s", strtotime($current_date . ' ' . $shift->start_time));

            $end_time = date("Y-m-d H:i:s", strtotime($next_date . ' ' . $shift->end_time));

            $shift_start_time = date("Y-m-d h:i A", strtotime('+16 minutes ' . $start_time));

            $shift_end_time = date("Y-m-d h:i A", strtotime('-16 minutes ' . $end_time));

            $shift_start_halfday = date("Y-m-d h:i A", strtotime('+121 minutes ' . $start_time));
            $shift_end_halfday = date("Y-m-d h:i A", strtotime('-121 minutes ' . $end_time));

            $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
            $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));

            $punchIn = Attendance::where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'I')->orderBy('in_date', 'asc')->first();
            $punchOut = Attendance::where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'O')->orderBy('in_date', 'desc')->first();

            $label = '-';
            $type = '';
            $workingHours = '-';
            $workingMinutes = 0;
            $checkSecondDiscrepancy = true;
            $checkSecond = true;
            $attendance_id = '';

            if ($punchIn != null) {
                $attendance_id = $punchIn->id;
                $punchInRecord = new DateTime($punchIn->in_date);
                $checkIn = $punchInRecord->format('h:i A');

                if (strtotime($punchIn->in_date) < strtotime($shift_start_time)) {
                    $label = '<span class="badge bg-label-success">Regular</span>';
                    $type = 'regular';
                } elseif (strtotime($punchIn->in_date) >= strtotime($shift_start_time) && strtotime($punchIn->in_date) <= strtotime($shift_start_halfday)) {
                    $label = '<span class="badge bg-label-warning"><i class="far fa-dot-circle text-warning"></i> Late In</span>';
                    $type = 'lateIn';
                    $checkSecondDiscrepancy = false;
                } else {
                    $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                    $type = 'firsthalf';
                    $checkSecond = false;
                }
            } else {
                $checkIn = '-';
            }

            if ($punchOut != null) {
                if ($punchIn == null) {
                    $attendance_id = $punchOut->id;
                }
                $punchOutRecord = new DateTime($punchOut->in_date);
                $checkOut = $punchOutRecord->format('h:i A');

                if ($checkSecondDiscrepancy && (strtotime($punchOut->in_date) < strtotime($shift_end_time) && strtotime($punchOut->in_date) > strtotime($shift_end_halfday))) {
                    $label = '<span class="badge bg-label-warning"><i class="far fa-dot-circle text-warning"></i> Early Out</span>';
                    $type = 'earlyout';
                } else if ($checkSecond && strtotime($punchOut->in_date) < strtotime($shift_end_halfday)) {
                    $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                    $type = 'lasthalf';
                }
            } else {
                $checkOut = '-';
            }

            if ($punchIn != null && $punchOut != null) {
                $h1 = new DateTime($punchIn->in_date);
                $h2 = new DateTime($punchOut->in_date);
                $diff = $h2->diff($h1);
                $workingHours = $diff->format('%H:%I');
                $workingMinutes = $diff->h * 60 + $diff->i;
            }

            if (($punchIn != null && $punchOut == null)) {
                if (date('Y-m-d') > date('Y-m-d', strtotime($end))) {
                    $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                    $type = 'lasthalf';
                } else {
                    $checkOut = 'Not Yet';
                }
            } elseif (($punchIn == null && $punchOut != null)) {
                if (date('Y-m-d') > date('Y-m-d', strtotime($end))) {
                    $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                    $type = 'firsthalf';
                }
            }

            $current_time = date("H:i:s");
            $date_comparsion = '';
            if (strtotime($current_time) > strtotime("00:00:00") && strtotime($current_time) <= strtotime("01:00:00")) {
                $date_comparsion = $current_date < date('Y-m-d');
            } else {
                $date_comparsion = $current_date <= date('Y-m-d');
            }

            $currentDatecheck = date('Y-m-d'); // Current date in 'Y-m-d' format
            $midnightTimestamp = strtotime($currentDatecheck . '00:00:01'); // Midnight timestamp

            if (($punchIn == null && $punchOut == null) && date('Y-m-d h:i A') > $shift_start_time && $date_comparsion && $beginDate->greaterThanOrEqualTo($start_date) && strtotime($current_date) < $midnightTimestamp) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Absent</span>';
                $type = 'absent';
                $attendance_date = $current_date;
                $checkIn = '-';
            }

            $discrepancy = '';
            $discrepancyStatus = '';
            $applied_discrepancy = '';
            $attendance_date = '';

            if ($type == 'lateIn' || $type == 'late' || $type == 'earlyout' && !empty($punchIn)) {
                $discrepancy_record = Discrepancy::where('attendance_id', $punchIn->log_id)->orWhereDate('date', date('Y-m-d', strtotime($punchIn->in_date)))->where('user_id', $userID)->first();
                if (!empty($discrepancy_record)) {
                    $discrepancy = $discrepancy_record->type;
                    $discrepancyStatus = $discrepancy_record->status;
                    $applied_discrepancy = $discrepancy_record;
                } else {
                    $attendance_date = $punchIn;
                    $attendance_id = $attendance_date->id;
                }
            }

            $leave = '';
            $applied_leaves = '';
            $leaveStatus = '';
            $punch_date = '';

            if ($type == 'absent') {
                $punch_date = date('Y-m-d', strtotime($current_date));
            } else if ($type == 'firsthalf') {
                $punch_date = date('Y-m-d', strtotime($current_date));
            } else if ($type == 'lasthalf') {
                $punch_date = date('Y-m-d', strtotime($current_date));
            }

            if (isset($punch_date) && $punch_date != '') {
                if (userLeaveApplied($userID, $punch_date)) {
                    $leaves = userLeaveApplied($userID, $punch_date);
                } else {
                    $leaves = UserLeave::where('behavior_type', $type)->whereDate('start_at', $punch_date)->where('user_id', $userID)->first();
                }

                if (!empty($leaves)) {
                    $leave = $leaves->behavior_type;
                    $leaveStatus = $leaves->status;
                    $applied_leaves = $leaves;
                } else {
                    if ($type == 'absent') {
                        $attendance_date = $current_date;
                    } elseif ($type == "lasthalf" && $punchIn == '') {
                        $attendance_date = $current_date;
                    } elseif ($type == "lasthalf" && $punchIn != '') {
                        $attendance_date = $punchIn;
                        $attendance_id = $attendance_date->id;
                    } elseif ($type == "firsthalf" && $punchIn == '') {
                        $attendance_date = $current_date;
                    } elseif ($type == "firsthalf" && $punchIn != '') {
                        $attendance_date = $current_date;
                    }
                }
            }
        }

        if ($type == 'regular') {
            $attendance_date = $punchIn;
            $attendance_id = $attendance_date->id;
        }

        $data = array(
            'punchIn' => $checkIn,
            'punchOut' => $checkOut,
            'label' => $label,
            'type' => $type,
            'shiftTiming' => $shiftTiming,
            'shiftType' => $shift->type,
            'workingHours' => $workingHours,
            'workingMinutes' => $workingMinutes,
            'discrepancy' => $discrepancy,
            'discrepancyStatus' => $discrepancyStatus,
            'applied_discrepancy' => $applied_discrepancy,
            'leave' => $leave,
            'leaveStatus' => $leaveStatus,
            'applied_leaves' => $applied_leaves,
            'attendance_date' => $attendance_date,
            'attendance_id' => $attendance_id,
            'user' => $user
        );

        if ($status == 'all') {
            return $data;
        } elseif ($status == 'regular' && $type == 'regular') {
            return $data;
        } elseif ($status == 'absent' && $type == 'absent') {
            return $data;
        } elseif ($status == 'lateIn' && $type == 'lateIn') {
            return $data;
        } elseif ($status == 'earlyout' && $type == 'earlyout') {
            return $data;
        } elseif ($status == 'halfday' && ($type == 'firsthalf' || $type == 'lasthalf')) {
            return $data;
        } else {
            return null;
        }
    }

    public static function getAppliedLeave($leave_date, $user_id)
    {
        return UserLeave::where('user_id', $user_id)->where('start_at', '>=', $leave_date)->where('end_at', '<=', date('Y-m-d'))->first();
    }
    public static function getAppliedDiscrepancy($applied_date, $user_id)
    {
        return Discrepancy::where('user_id', $user_id)->where('date', $applied_date)->first();
    }

    public function discrepancies(Request $request,  $getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('employee_discrepancies-list');
        $title = 'Discrepancies';
        $user = Auth::user();

        $model = [];
        Discrepancy::where('user_id', $user->id)
            ->latest()
            ->chunk(100, function ($discrepancies) use (&$model) {
                foreach ($discrepancies as $discrepancy) {
                    $model[] = $discrepancy;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Approved</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Pending</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('is_additional', function ($model) {
                    $label = '';

                    switch ($model->is_additional) {
                        case 1:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Additional</span>';
                            break;
                        case 0:
                            $label = '-';
                            break;
                    }

                    return $label;
                })
                ->editColumn('date', function ($model) {
                    return Carbon::parse($model->date)->format('d, M Y');
                })
                ->editColumn('type', function ($model) {
                    $label = '-';
                    if ($model->type == 'early') {
                        $label = '<span class="badge bg-label-warning"> Early Out</span>';
                    } elseif ($model->type == 'late' || $model->type == 'lateIn') {
                        $label = '<span class="badge bg-label-info">Late In</span>';
                    }

                    return $label;
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('user_id', function ($model) {
                    return view('user.attendance.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('user.attendance.discrepancy-action', ['model' => $model])->render();
                })
                ->rawColumns(['user_id', 'status', 'type', 'is_additional', 'action'])
                ->make(true);
        }

        return view('user.attendance.discrepancies', compact('title', 'user'));
    }

    public function teamDiscrepancies(Request $request, $user_slug = null)
    {
        $this->authorize('team_discrepancies-list');
        $title = 'Team Discrepancies';
        $record = new Discrepancy();
        if (getUser()->hasRole("Department Manager")) {
            $record = $record->whereIn('user_id', getUsersList()->pluck("id")->toArray());
        }
        $records = $record->orderby('status', 'asc')->select("*");
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('select', function ($model) {
                    return view('user.attendance.discrepancy_check', ['model' => $model])->render();
                })
                ->editColumn('status', function ($model) {
                    $label = '';
                    $name = "N/A";
                    $class = "info";
                    if ($model->status == 1 || $model->status == 2 || $model->status == 0) {
                        $name = getDiscrepencyStatus($model)->name ?? '';
                        $class = getDiscrepencyStatus($model)->class ?? 'info';
                    }
                    $label = '<span class="badge bg-label-' . $class . '" text-capitalized="">' . $name . '</span>';
                    return $label;
                })
                ->editColumn('department', function ($model) {
                    $label = '';
                    $name = "N/A";
                    $class = "info";
                    $department = DepartmentUser::where("user_id", $model->user_id)->first();
                    $name =   $department->department->name ?? "-";
                    $class = "success";
                    $label = '<span class="badge bg-label-' . $class . '" text-capitalized="">' . $name . '</span>';
                    return $label;
                })
                ->editColumn('is_additional', function ($model) {
                    if ($model->is_additional == 1) {
                        return '<span class="badge bg-label-danger" text-capitalized="">Additional</span>';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('type', function ($model) {
                    $label = '';
                    if ($model->type == "lateIn" || $model->type == "late") {
                        $label = '<span class="badge bg-label-primary" text-capitalized="">';
                        $label .= Str::ucfirst('Late In');
                        $label .= '</span>';
                    } else {
                        $label = '<span class="badge bg-label-warning" text-capitalized="">';
                        $label .= Str::ucfirst($model->type);
                        $label .= '</span>';
                    }

                    return $label;
                })
                ->editColumn('date', function ($model) {
                    if (!empty($model->date)) {
                        return '<span class="fw-semibold">' . date('d M Y', strtotime($model->date)) . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('user_id', function ($model) {
                    return  !empty($model->hasEmployee) ? userWithHtml($model->hasEmployee) : "-";
                })
                ->addColumn('action', function ($model) {
                    return view('user.attendance.discrepancy_action', ['model' => $model])->render();
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance = $instance->whereHas("hasEmployee", function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->where('first_name', 'LIKE', "%$search%")
                                ->orWhere('last_name', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%");
                        });
                    }
                    if (empty($request->team) || $request->team == "all") {
                        if ($request->department != "all" && !empty($request->department)) {
                            $department = getDepartmentFromID($request->department);
                            $myDpartUsers = getDepartmentUsers($department);
                            if (!empty($myDpartUsers)) {
                                $array = $myDpartUsers->pluck("user_id")->toArray();
                                $userArray1 =  $array;
                                $instance->whereIn("user_id",  $userArray1);
                            }
                        }
                    }
                    if ($request->team != "all" && !empty($request->team)) {
                        $instance->where("user_id",  $request->team);
                    }
                    if (isset($request->month) && !empty($request->month) && $request->month != null) {
                        $existingDate = $request->month;
                        $carbonDate = Carbon::createFromFormat('Y-m', $existingDate);

                        // Get the date for the previous month
                        $previousMonth = $carbonDate->subMonth();
                        $previousMonth = $previousMonth->format('Y-m');

                        $startDate = $previousMonth.'-26';
                        $endDate = $request->month.'-25';

                        if (!empty($startDate)) {
                            $instance = $instance->whereBetween('start_at', [$startDate, $endDate]);
                        }
                        $instance = $instance->orderby("start_at", "asc");
                    }
                    if ($request->dStatus != "all") {
                        $instance = $instance->where('status', $request->dStatus);
                    }
                    if ($request->additional != "all") {
                        $instance = $instance->where('is_additional', $request->additional);
                    }
                })
                ->rawColumns(['select', 'user_id', 'type', 'is_additional', 'date', 'status', 'action', 'department'])
                ->make(true);
        }

        return view('user.attendance.team_discrepancies', compact('title'));
    }

    public function showDiscrepancy($id)
    {
        $model = Discrepancy::where('id', $id)->first();
        return (string) view('user.attendance.show_content', compact('model'));
    }

    public function managerTeamDiscrepancies(Request $request, $user_slug = null)
    {
        $this->authorize('manager_team_discrepancies-list');
        $title = 'Team Discrepancies';
        $logined_user = Auth::user();

        $employees = [];
        $departments = [];
        $url = '';
        $department_ids = [];

        if ($logined_user->hasRole('Department Manager')) {
            $manager_dept_ids = Department::where('manager_id', $logined_user->id)->where('status', 1)->pluck('id')->toArray();
            $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
            $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
            if (!empty($child_departments) && count($child_departments) > 0) {
                $department_ids = array_unique(array_merge($department_ids, $child_departments));
            }
            $employees = getTeamMembers($logined_user);
        }

        if (!empty($user_slug) && $user_slug != 'All') {
            $user = User::where('slug', $user_slug)->first();
            $url = URL::to('manager/team/discrepancies/' . $user_slug);

            $model = [];
            Discrepancy::where('user_id', $user->id)
                ->latest()
                ->chunk(100, function ($discrepancies) use (&$model) {
                    foreach ($discrepancies as $discrepancy) {
                        $model[] = $discrepancy;
                    }
                });
        } else {
            $user = $logined_user;

            $model = [];
            $employees_ids = getTeamMemberIds($user);
            Discrepancy::with('hasEmployee', 'hasEmployee.profile')->whereIn('user_id', $employees_ids)
                ->latest()
                ->chunk(100, function ($discrepancies) use (&$model) {
                    foreach ($discrepancies as $discrepancy) {
                        $model[] = $discrepancy;
                    }
                });
        }

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('select', function ($model) {
                    return view('user.attendance.discrepancy_check', ['model' => $model])->render();
                })
                ->editColumn('status', function ($model) {
                    if ($model->status == 1) {
                        return '<span class="badge bg-label-success" text-capitalized="">Approved</span>';
                    } elseif ($model->status == 2) {
                        return '<span class="badge bg-label-danger" text-capitalized="">Rejected</span>';
                    } else {
                        return '<span class="badge bg-label-warning" text-capitalized="">Pending</span>';
                    }
                })
                ->editColumn('is_additional', function ($model) {
                    if ($model->is_additional == 1) {
                        return '<span class="badge bg-label-danger" text-capitalized="">Additional</span>';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('type', function ($model) {
                    $label = '';
                    if ($model->type == "lateIn" || $model->type == "late") {
                        $label = '<span data-toggle="tooltip" data-placement="top" class="badge bg-label-primary" text-capitalized="">';
                        $label .= Str::ucfirst('Late In');
                        $label .= '</span>';
                    } else {
                        $label = '<span data-toggle="tooltip" data-placement="top" class="badge bg-label-warning" text-capitalized="">';
                        $label .= Str::ucfirst($model->type);
                        $label .= '</span>';
                    }

                    return $label;
                })
                ->editColumn('date', function ($model) {
                    if (!empty($model->date)) {
                        return '<span class="fw-semibold">' . date('d M Y', strtotime($model->date)) . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('user_id', function ($model) {
                    return view('user.attendance.employee-profile', ['model' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('user.attendance.discrepancy_action', ['model' => $model])->render();
                })
                ->rawColumns(['select', 'user_id', 'type', 'is_additional', 'date', 'status', 'action'])
                ->make(true);
        }

        return view('user.attendance.manager-team_discrepancies', compact('title', 'user', 'employees', 'departments', 'url'));
    }

    public function dailyLog(Request $request, $getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('admin_attendance_daily_log-list');
        $title = 'Daily Log';

        if (isset($request->user_slug)) {
            $user_slug = $request->user_slug;
        }

        $user = Auth::user();
        $url = '';

        $departmentUserIds = DepartmentUser::select('id')->where('end_date',  NULL)->pluck('id')->toArray();
        $employees = User::whereIn('id', $departmentUserIds)->where('id', '!=', $user->id)->where('is_employee', 1)->where('status', 1)->select(['id', 'first_name', 'last_name', 'slug', 'email'])->get();

        $currentMonth = date('m');
        if (date('d') > 25) {
            $currentMonth = date('m', strtotime('first day of +1 month'));
        }
        if (!empty($getMonth) || !empty($user_slug)) {
            $year = $getYear;
            $month = $getMonth;

            $user = User::where('slug', $user_slug)->first();
            $url = URL::to('user/attendance/daily-log/' . $month . '/' . $year . '/' . $user_slug);
        } else {
            $year = date('Y');
            if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
                $month = date('m', strtotime('first day of +1 month'));
            } else {
                $month = date('m');
            }
        }

        $model = [];
        Attendance::with('hasEmployee', 'hasEmployee.profile')->where('user_id', $user->id)
            ->latest()
            ->chunk(100, function ($logs) use (&$model) {
                foreach ($logs as $log) {
                    $model[] = $log;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('behavior', function ($model) {
                    $label = '';

                    switch ($model->behavior) {
                        case 'I':
                            $label = '<span class="badge bg-label-success" text-capitalized="">Punched In</span>';
                            break;
                        case 'O':
                            $label = '<span class="badge bg-label-info" text-capitalized="">Punched Out</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('in_date', function ($model) {
                    return '<span class="text-primary fw-semibold">' . Carbon::parse($model->in_date)->format('d, M Y') . '</span>';
                })
                ->addColumn('time', function ($model) {
                    return '<span class="fw-semibold">' . Carbon::parse($model->in_date)->format('h:i A') . '</span>';
                })
                ->editColumn('user_id', function ($model) {
                    return view('user.attendance.employee-profile', ['model' => $model])->render();
                })
                ->rawColumns(['user_id', 'behavior', 'in_date', 'time'])
                ->make(true);
        }

        return view('user.attendance.daily-log', compact('title', 'user', 'month', 'year', 'currentMonth', 'employees', 'url'));
    }

    public function employeeDailyLog(Request $request, $getMonth = null, $getYear = null, $user_slug = null)
    {
        $this->authorize('employee_attendance_daily_log-list');
        $title = 'Daily Log';

        if (isset($request->user_slug)) {
            $user_slug = $request->user_slug;
        }

        $logined_user = Auth::user();
        $employees = [];
        $url = '';
        if ($logined_user->hasRole('Department Manager')) {
            $employees = getTeamMembers($logined_user);
        }

        $currentMonth = date('m');
        if (date('d') > 25) {
            $currentMonth = date('m', strtotime('first day of +1 month'));
        }
        if (!empty($getMonth) || !empty($user_slug)) {
            $year = $getYear;
            $month = $getMonth;

            $user = User::where('slug', $user_slug)->first();
            $url = URL::to('employee/attendance/daily-log/' . $month . '/' . $year . '/' . $user_slug);
        } else {
            $year = date('Y');
            if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
                $month = date('m', strtotime('first day of +1 month'));
            } else {
                $month = date('m');
            }

            $user = $logined_user;
        }

        $model = [];
        Attendance::with('hasEmployee', 'hasEmployee.profile')->where('user_id', $user->id)
            ->latest()
            ->chunk(100, function ($logs) use (&$model) {
                foreach ($logs as $log) {
                    $model[] = $log;
                }
            });

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('behavior', function ($model) {
                    $label = '';

                    switch ($model->behavior) {
                        case 'I':
                            $label = '<span class="badge bg-label-success" text-capitalized="">Punched In</span>';
                            break;
                        case 'O':
                            $label = '<span class="badge bg-label-info" text-capitalized="">Punched Out</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('in_date', function ($model) {
                    return '<span class="text-primary fw-semibold">' . Carbon::parse($model->in_date)->format('d, M Y') . '</span>';
                })
                ->addColumn('time', function ($model) {
                    return '<span class="fw-semibold">' . Carbon::parse($model->in_date)->format('h:i A') . '</span>';
                })
                ->editColumn('user_id', function ($model) {
                    return view('user.attendance.employee-profile', ['model' => $model])->render();
                })
                ->rawColumns(['user_id', 'behavior', 'in_date', 'time'])
                ->make(true);
        }

        return view('user.attendance.employee-daily-log', compact('title', 'user', 'month', 'year', 'currentMonth', 'employees', 'url'));
    }

    //Team
    public function getDiscrepancies()
    {
        $user = Auth::user();
        if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
            $data['month'] = date('m', strtotime('first day of +1 month'));
        } else {
            $data['month'] = date('m');
        }

        if (date('m') == $data['month']) {
            $currentMonthStart = Carbon::now()->subMonth()->startOfMonth()->addDays(25);
            $currentMonthEnd = Carbon::now()->startOfMonth()->addDays(24);
        } else {
            $currentMonthStart = Carbon::now()->startOfMonth()->addDays(25);
            $currentMonthEnd = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        }

        $department_ids = [];

        if ($user->hasRole('Admin')) {
            $current_month_discrepancies = Discrepancy::orderby('status', 'asc')->where('user_id', '!=', $user->id)->whereBetween('date', [$currentMonthStart, $currentMonthEnd])->get();
        } else {
            $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
            $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
            $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
            if (!empty($child_departments) && count($child_departments) > 0) {
                $department_ids = array_unique(array_merge($department_ids, $child_departments));
            }

            $team_members_ids = DepartmentUser::orderby('id', 'desc')->whereIn('department_id',  $department_ids)->where('end_date', null)->pluck('user_id')->toArray();

            $current_month_discrepancies = Discrepancy::orderby('status', 'asc')->whereBetween('date', [$currentMonthStart, $currentMonthEnd])->whereIn('user_id', $team_members_ids)->get();
        }

        return (string) view('user.attendance.get-discrepancies', compact('current_month_discrepancies'));
    }

    public function getLeaves()
    {
        $user = Auth::user();
        if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
            $data['month'] = date('m', strtotime('first day of +1 month'));
        } else {
            $data['month'] = date('m');
        }

        if (date('m') == $data['month']) {
            $currentMonthStart = Carbon::now()->subMonth()->startOfMonth()->addDays(25);
            $currentMonthEnd = Carbon::now()->startOfMonth()->addDays(24);
        } else {
            $currentMonthStart = Carbon::now()->startOfMonth()->addDays(25);
            $currentMonthEnd = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        }

        $department_ids = [];

        if ($user->hasRole('Admin')) {
            $current_month_leaves = UserLeave::orderby('status', 'ASC')->where('user_id', '!=', $user->id)->whereBetween('start_at', [$currentMonthStart, $currentMonthEnd])->get();
        } else {
            $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
            $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
            $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
            if (!empty($child_departments) && count($child_departments) > 0) {
                $department_ids = array_unique(array_merge($department_ids, $child_departments));
            }

            $team_members_ids = DepartmentUser::orderby('id', 'desc')->whereIn('department_id',  $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
            $current_month_leaves = UserLeave::orderby('status', 'ASC')->whereBetween('start_at', [$currentMonthStart, $currentMonthEnd])->whereIn('user_id', $team_members_ids)->get();
        }

        return (string) view('user.attendance.get-leaves', compact('current_month_leaves'));
    }
    public function ApproveOrRejectDiscrepancy(Request $request, $discrepancy_id = null, $status = null)
    {
        if ($discrepancy_id != null) {
            $discrepancy = Discrepancy::where('id', $discrepancy_id)->first();
            if (!empty($discrepancy) && $status == 'approve') {
                $discrepancy->status = 1; //approve
            } else {
                $discrepancy->status = 2; //reject
            }

            $discrepancy->approved_by = Auth::user()->id;
            $discrepancy->save();

            if ($discrepancy) {
                return true;
            } else {
                return false;
            }
        } else {
            $data = json_decode($request->data);

            foreach ($data as $value) {
                if ($value->type == 'lateIn' || $value->type == 'earlyout') {
                    $model = Discrepancy::where('id', $value->id)->first();
                    if ($model) {
                        $model->approved_by = Auth::user()->id;
                        $model->status = 1;
                        $model->save();
                    }
                } else {
                    $model = UserLeave::where('id', $value->id)->first();
                    if ($model) {
                        $model->approved_by = Auth::user()->id;
                        $model->status = 1;
                        $model->save();
                    }
                }
            }

            return true;
        }
    }

    public function ApproveOrRejectTeamDiscrepancies(Request $request, $status)
    {
        $data = json_decode($request->data);

        foreach ($data as $value) {
            $model = Discrepancy::where('id', $value->id)->first();
            if ($model) {
                $model->approved_by = Auth::user()->id;
                if ($status == 'approve') {
                    $model->status = 1;
                } else {
                    $model->status = 2;
                }
                $model->save();
            }
        }

        if ($model) {
            return 'true';
        } else {
            return false;
        }
    }

    public function monthlyAttendanceReport($getMonth = null, $getYear = null)
    {
        $this->authorize('attendance_monthly_report-list');
        $title = 'Monthly Attendance Report';
        $behavior = 'all';
        $user = Auth::user();

        $data = [];
        $employees = [];
        $data['employees'] = User::where('status', 1)->where('is_employee', 1)->get();
        $data['users'] = User::where('status', 1)->where('is_employee', 1)->paginate(10);

        $year = date('Y');
        $month = date('m');
        if (!empty($getMonth)) {
            $year = $getYear;
            $month = $getMonth;

            // Calculate the start date (26th of the previous month)
            $from_date = Carbon::create($year, $month, 26, 0, 0, 0)->subMonth();

            // Calculate the end date (25th of the current month) year,month, date, hour, minute, second.
            $to_date = Carbon::create($year, $month, 25, 23, 59, 59);
        } else {
            if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
                $from_date = Carbon::now()->startOfMonth()->addDays(25);
                $to_date = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
            } else {
                $from_date = Carbon::now()->subMonth()->startOfMonth()->addDays(25);
                $to_date = Carbon::now()->startOfMonth()->addDays(24);
            }
        }

        $fullMonthName = \Carbon\Carbon::create(null, $month, 1)->format('F');

        $data['from_date'] = date('Y-m-d', strtotime($from_date));
        $data['to_date'] = date('Y-m-d', strtotime($to_date));
        $data['behavior'] = $behavior;

        return view('user.attendance.monthly-attendance-report', compact('title', 'user', 'data', 'year', 'month', 'fullMonthName'));
    }
    // currently not in used
    public function attendanceList(Request $request)
    {
        if ($request->ajax() && $request->loaddata == "yes") {
            $year = Carbon::now()->format("Y");
            $month = Carbon::now()->format("m");
            if (!empty($request->month)) {
                $explode = explode("-", $request->month);
                $year = isset($explode[0]) && !empty($explode[0]) ? $explode[0] : "";
                $month = isset($explode[1]) && !empty($explode[1]) ? $explode[1] : "";
            }
            $monthData = (object) [
                "month" => $month,
                "year" => $year,
            ];
            $model = [];
            $users = User::with("userWorkingShift")->where("status", 1)->select("*");
            if (!empty($users)) {
                return DataTables::of($users)
                    ->setRowAttr([
                        'data-id' => function ($user) {
                            return 'row-' . $user->id;
                        },
                    ])
                    ->addIndexColumn()
                    ->addColumn('user', function ($model) {
                        $data = !empty($model) ?  userWithHtml($model) :  "-";
                        return $data;
                    })
                    ->addColumn('shift', function ($model) {
                        $data = !empty($model->userWorkingShift->workShift) ?  $model->userWorkingShift->workShift->name :  "-";
                        return $data;
                    })
                    ->addColumn('working_days', function ($model)  use ($monthData) {
                        $daysData = getMonthDaysForSalary($monthData->year, $monthData->month);
                        $getAttendance = $daysData->total_days_without_weekends ?? 0;
                        return  $getAttendance  ?? 0;
                    })
                    ->addColumn('regular_days', function ($model)  use ($monthData) {
                        return 0;
                        $daysData = getMonthDaysForSalary($monthData->year, $monthData->month);
                        $getAttendance = attendanceCount($daysData->first_date, $daysData->last_date,  $monthData->month,  $monthData->year,  $model, "regular");
                        return 0;
                        return  $getAttendance ?? 0;
                    })
                    ->addColumn('late_in', function ($model)   use ($monthData) {
                        $daysData = getMonthDaysForSalary($monthData->year, $monthData->month);
                        $getAttendance = attendanceCount($daysData->first_date, $daysData->last_date,  $monthData->month,  $monthData->year,  $model, "late");
                        return 0;
                        return  $getAttendance ?? 0;
                    })
                    ->addColumn('early_out', function ($model)   use ($monthData) {
                        return 0;
                        $daysData = getMonthDaysForSalary($monthData->year, $monthData->month);
                        $getAttendance = attendanceCount($daysData->first_date, $daysData->last_date,  $monthData->month,  $monthData->year,  $model, "earlyout");
                        return  $getAttendance ?? 0;
                    })
                    ->addColumn('absents', function ($model)  use ($monthData) {
                        return 0;
                        $daysData = getMonthDaysForSalary($monthData->year, $monthData->month);
                        $getAttendance = attendanceCount($daysData->first_date, $daysData->last_date,  $monthData->month,  $monthData->year,  $model, "late");
                        return  $getAttendance ?? 0;
                    })
                    ->addColumn('half_days', function ($model)   use ($monthData) {
                        return 0;
                        $daysData = getMonthDaysForSalary($monthData->year, $monthData->month);
                        $getAttendance = attendanceCount($daysData->first_date, $daysData->last_date,  $monthData->month,  $monthData->year,  $model, "halfday");
                        return 0;
                        return  $getAttendance ?? 0;
                    })

                    ->addColumn('actions', function ($model) {
                        return "-";
                    })
                    ->filter(function ($instance) use ($request) {
                        if (isset($request->users) && !empty($request->users)) {
                            $instance = $instance->whereIn('id', $request->users);
                        }
                    })
                    ->rawColumns([
                        'user',
                        'shift',
                        'working_days',
                        'regular_days',
                        'late_in',
                        'early_out',
                        'absents',
                        'half_days',
                        'actions',
                    ])
                    ->make(true);
            }
        }
    }
    // currently not in used
    public function monthlyAttendanceReportFilter(Request $request)
    {
        $title = 'Attendance Summary';
        $data = [];

        $employees = [];
        $employees = User::where('status', 1)->where('is_employee', 1)->get();

        if ($request->ajax()) {
            $users = [];

            if (!empty($request->year) && !empty($request->month)) {
                $year = $request->year;
                $month = $request->month;

                // Calculate the start date (26th of the previous month)
                $from_date = Carbon::create($year, $month, 26, 0, 0, 0)->subMonth();

                // Calculate the end date (25th of the current month)
                $to_date = Carbon::create($year, $month, 25, 23, 59, 59);
            } else {
                if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
                    $from_date = Carbon::now()->startOfMonth()->addDays(25);
                    $to_date = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
                } else {
                    $from_date = Carbon::now()->subMonth()->startOfMonth()->addDays(25);
                    $to_date = Carbon::now()->startOfMonth()->addDays(24);
                }
            }

            $data['employees'] = $employees;
            $behavior = 'all';

            $all_employee_ids = [];
            $filter_employees = json_decode($request['employees']);
            if (!empty($filter_employees) && count($filter_employees) > 0) {
                if ($filter_employees[0] == 'All') {
                    foreach ($employees as $employee) {
                        $all_employee_ids[] = $employee->id;
                    }
                } else {
                    $all_employee_ids = $filter_employees;
                }
            }

            $employees = $all_employee_ids;
            $users = User::whereIn('id', $employees)->where('status', 1)->where('is_employee', 1)->get();

            $data['from_date'] = date('Y-m-d', strtotime($from_date));
            $data['to_date'] = date('Y-m-d', strtotime($to_date));
            $data['behavior'] = $behavior;
            $data['users'] = $users;

            return (string) view('user.attendance.monthly-attendance-report-filter', compact('data'));
        }
    }


    public function monthlyAttendanceReportExport(Request $request)
    {
        try {
            $reportName = "Monthly-Salary-Report-" . time() . ".csv";

            // Dispatch the job to export data
            dispatch(new ExportAttendance(User::all(), $reportName));

            $response = new StreamedResponse(function () {
                // Open output stream
                $handle = fopen('php://output', 'w');

                // Add CSV headers
                fputcsv($handle, [
                    'S.NO#',
                    'MONTH',
                    'FROM',
                    'TO',
                    'EMPLOYEE',
                    'WORKING DAYS',
                    'REGULAR DAYS',
                    'LATE IN',
                    'EARLY OUTS',
                    'HALF DAYS',
                    'ABSENTS',
                    'SHIFT',
                ]);

                // Get all users
                User::chunk(500, function ($users) use ($handle) {
                    foreach ($users as $user) {
                        Log::info("USERID : " . json_encode($user->id));
                        $total_days = 0;
                        $regulars = 0;
                        $late_ins = 0;
                        $early_outs = 0;
                        $half_days = 0;
                        $absents = 0;
                        if (!empty($user->userWorkingShift)) {
                            $shift =  $user->userWorkingShift->workShift;
                        } else {
                            if (isset($user->departmentBridge->department->departmentWorkShift->workShift) && !empty($user->departmentBridge->department->departmentWorkShift->workShift->id)) {
                                $shift =  $user->departmentBridge->department->departmentWorkShift->workShift;
                            }
                        }

                        $daysData = getMonthDaysForSalary(2023, 10);
                        $month = "2023/10";
                        // Add a new row with data
                        fputcsv($handle, [
                            'sno' => $user->id,
                            'month' =>  $month,
                            'from' => $daysData->first_date ?? "-",
                            'to' => $daysData->last_date ?? "-",
                            'name' => getUserName($user),
                            'working_days' => $daysData->total_days ?? 0,
                            'regular' => rand(1, 30)  ?? 0,
                            'late_in' => rand(1, 30) ?? 0,
                            'early_out' => rand(1, 30)  ?? 0,
                            'half_day' => rand(1, 30)  ?? 0,
                            'absents' => rand(1, 30) ?? 0,
                        ]);
                    }
                });

                // Close the output stream
                fclose($handle);
            }, 200, [

                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $reportName,
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Export error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to dispatch export job']);
        }
    }
}
