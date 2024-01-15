<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Mail\Email;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserLeave;
use App\Models\WorkShift;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\AssetDetail;
use App\Models\Discrepancy;
use Illuminate\Http\Request;
use App\Models\DepartmentUser;
use App\Models\AttendanceSummary;
use Spatie\Permission\Models\Role;
use App\Models\MonthlySalaryReport;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class DeveloperController extends Controller
{
    public function sendDailyAbsentsEmail(){
        return 'Already Done';
        $year = date('Y');
        if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
            $month = date('m', strtotime('first day of +1 month'));
        } else {
            $month = date('m');
        }

        if ($month == 01) {
            $year = date('Y', strtotime('first day of +1 month'));
        }
        $admin = User::role(['Admin'])->select(['id', 'first_name', 'last_name', 'email'])->take(1)->first()->toArray();
        $users = User::where('is_employee', 1)->where('status', 1)->select(['id', 'first_name', 'last_name'])->take(10)->get();
        $data = [];
        foreach($users as $user){
            $shift = $user->userWorkingShift;
            if (empty($shift)) {
                $shift = defaultShift();
            } else {
                $shift = $shift->workShift;
            }
            $statistics = AttendanceController::getAttandanceCount($user->id, $year."-".((int)$month-1)."-26", $year."-".(int)$month."-25",'all', $shift);
            if(count($statistics['absent_dates']) > 1){
                $absentDates = array_slice($statistics['absent_dates'], 0, -1);

                $shift = '';
                if(isset($user->userWorkingShift->workShift) && !empty($user->userWorkingShift->workShift->start_time)){
                    $shift = $user->userWorkingShift->workShift;
                }else{
                    $shift = defaultShift();
                }

                $designation = '-';
                if(isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title)){
                    $designation = $user->jobHistory->designation->title;
                }

                $manager = '-';
                if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->manager_id)){
                    $manager = getAuthorizeUserName($user->departmentBridge->department->manager_id);
                }

                $employeeData = [
                    'name' => $user->first_name.' '.$user->last_name,
                    'designation' => $designation,
                    'shift' => date('h:i A', strtotime($shift->start_time)).' - '.date('h:i A', strtotime($shift->end_time)),
                    'r_a' => $manager,
                    'absent_days_count' => count($absentDates),
                ];

                $data[] = $employeeData;
            }
        }

        usort($data, function ($a, $b) {
            return $b['absent_days_count'] - $a['absent_days_count'];
        });

        return $data;
        $mailData = [
            'from' => 'absent_email',
            'title' => 'Employee Absence Report',
            'adminName' => $admin['first_name'].' '.$admin['last_name'],
            'employees' => $data,
        ];
    }
    public function sendEmailLastAbsentEmployee(){
        $year = date('Y');
        if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
            $month = date('m', strtotime('first day of +1 month'));
        } else {
            $month = date('m');
        }

        if ($month == 01) {
            $year = date('Y', strtotime('first day of +1 month'));
        }
        $admin = User::role(['Admin'])->select(['id', 'first_name', 'last_name', 'email'])->take(1)->first()->toArray();

        $users = User::where('is_employee', 1)->where('status', 1)->select(['id', 'first_name', 'last_name'])->get();
        $data = [];
        foreach($users as $user){
            $shift = $user->userWorkingShift;
            if (empty($shift)) {
                $shift = defaultShift();
            } else {
                $shift = $shift->workShift;
            }
            $statistics = AttendanceController::getAttandanceCount($user->id, $year."-".((int)$month-1)."-26", $year."-".(int)$month."-25",'all', $shift);
            if(count($statistics['absent_dates']) > 1){
                $absentDates = array_slice($statistics['absent_dates'], 0, -1);

                $employeeData = [
                    'name' => $user->first_name.' '.$user->last_name,
                    'absent_days_count' => count($absentDates),
                    // 'absent_dates' => $absentDates,
                ];

                $data[] = $employeeData;
            }
        }
        $mailData = [
            'from' => 'absent_email',
            'title' => 'Employee Absence Report',
            'adminName' => $admin['first_name'].' '.$admin['last_name'],
            'employees' => $data,
        ];

        // Mail::to($admin['email'])->send(new Email($mailData));
        return view('emails.absent_days_email', compact('mailData'));
    }
    public function getAttendance(){
        $data = [];
        $begin = new DateTime($data['year'] . "-" . ((int)$data['month'] - 1) . "-26");
        $end = new DateTime($data['year'] . "-" . (int)$data['month'] . '-' . date('d'));

        $beginDate = date('Y-m-d');
        $user = User::where('id', 212)->first();
        $shiftID = WorkShift::where('id', $user->userWorkingShift->working_shift_id)->first();
        $start_time = date('Y-m-d', strtotime($beginDate)).' '.$shiftID->start_time;

        $shiftEndTime = $shiftID->end_time;
        $shiftEndTime = date('H:i', strtotime($shiftEndTime));
        $carbonEndTime = Carbon::createFromFormat('H:i', $shiftEndTime);

        $next=date("Y-m-d", strtotime('+1 day '.date('Y-m-d')));

        $end_time = date("Y-m-d", strtotime($next)).' '.$shiftID->end_time;
        $shiftStartTime = date("Y-m-d H:i:s", strtotime('-6 hours '.$start_time));
        $shiftEndTime = date("Y-m-d H:i:s", strtotime('+6 hours '.$end_time));

        $if_found = AttendanceSummary::where('user_id', 212)->where('in_date', 'like', $beginDate.'%')->first();
        dd($if_found);
        if (!empty($if_found)) {
            $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
        }elseif($if_found = Attendance::where('user_id', 212)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->orderby('id', 'desc')->first()){
            $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
        }
    }
    public function checkAssetDetail()
    {
        $asset = AssetDetail::with('assigneeUser')->where("id", 1)->first();
        dd($asset->assigneeUser);
    }
    public function permissions()
    {
        return 'Already done';
        // $permissions = Permission::get();
        // $permissions_data = [];
        // foreach($permissions as $permission){
        //     $permissions_data[] = $permission->name;
        // }

        // return $permissions_data;

        // $role = Role::where('name', 'Department Manager')->first();
        // $role = Role::where('name', 'Employee')->first();
        $role = Role::where('name', 'Developer')->first();
        return $role_permissions = $role->getPermissionNames();
        $models = Permission::orderby('id', 'DESC')->groupBy('label')->get();
    }
    public function dateTest()
    {
        return 'Already Tested';
        $currentTime = strtotime(date("H:i")); // Get the current time in 24-hour format
        $midnight = strtotime("00:00"); // Midnight in 24-hour format

        $current_date = '';
        if ($currentTime < $midnight) {
            return $current_date = date("Y-m-d", strtotime("+1 day"));
        } else {
            return $current_date = date("Y-m-d", strtotime("-1 day"));
        }
    }
    public function generateMonthlySalaryReport()
    {
        return 'Already Done';
        $data = [];

        // $data['month']=date('m');
        // $data['year']=date('Y');

        $employees = User::where('is_employee', 1)->where('status', 1)->get();
        foreach ($employees as $employee) {
            // $currentDate = Carbon::now();
            $currentDate = Carbon::now();
            $currentDate = $currentDate->subMonth();

            $data['month']=date('m', strtotime($currentDate));
            $data['year']=date('Y', strtotime($currentDate));

            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();

            // Today is before the 26th of the month, so calculate from the 26th of the previous month
            $previousMonth = $startOfMonth->subMonth();

            // if (date('G') < 0) { //it check it is less than mid night means before of 12pm
            $total_earning_days = $previousMonth->day(26)->diffInDays($currentDate);
            // } else {
            //     $total_earning_days = $previousMonth->day(26)->diffInDays($currentDate) + 1;
            // }

            $data['total_earning_days'] = $total_earning_days;

            $date = Carbon::createFromFormat('Y-m', $data['year'] . '-' . $data['month']);
            $data['month_year'] = $date->format('m/Y');

            // $date = Carbon::create($data['year'], $data['month']);

            // Create a Carbon instance for the specified month
            $dateForMonth = Carbon::create(null, $data['month'], 1);

            // Calculate the start date (26th of the specified month)
            $startDate = $dateForMonth->copy()->subMonth()->startOfMonth()->addDays(25);
            $endDate = $dateForMonth->copy()->startOfMonth()->addDays(25);

            // Calculate the total days
            $data['totalDays'] = $startDate->diffInDays($endDate);

            $data['salary'] = 0;
            if (isset($employee->salaryHistory) && !empty($employee->salaryHistory->salary)) {
                $data['salary'] =  $employee->salaryHistory->salary;
                $data['per_day_salary'] = $data['salary'] / $data['totalDays'];
            } else {
                $data['per_day_salary'] = 0;
                $data['actual_salary'] =  0;
            }

            if (isset($employee->userWorkingShift) && !empty($employee->userWorkingShift->working_shift_id)) {
                $data['shift'] = $employee->userWorkingShift->workShift;
            } else {
                $data['shift'] = $employee->departmentBridge->department->departmentWorkShift->workShift;
            }
            $statistics = getAttandanceCount($employee->id, $data['year'] . "-" . ((int)$data['month'] - 1) . "-26", $data['year'] . "-" . (int)$data['month'] . "-25", 'all', $data['shift']);

            $lateIn = count($statistics['lateInDates']);
            $earlyOut = count($statistics['earlyOutDates']);

            $total_discrepancies = $lateIn + $earlyOut;

            $filled_discrepancies = Discrepancy::where('user_id', $employee->id)->where('status', 1)->whereBetween('date', [$startDate, $endDate])->count();

            $total_over_discrepancies = $total_discrepancies - $filled_discrepancies;
            $discrepancies_absent_days = 0;
            if ($total_over_discrepancies > 2) {
                $discrepancies_absent_days = floor($total_over_discrepancies / 3);
            }
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];

            $filled_full_day_leaves = UserLeave::where('user_id', $employee->id)
                ->where('status', 1)
                ->whereMonth('start_at', $data['month'])
                ->whereYear('start_at', $data['year'])
                ->where('behavior_type', 'Full Day')
                ->get();

            $filled_full_day_leaves = $filled_full_day_leaves->sum('duration');

            $filled_half_day_leaves = UserLeave::where('user_id', $employee->id)
                ->where('status', 1)
                ->whereMonth('start_at', $data['month'])
                ->whereYear('start_at', $data['year'])
                ->where('behavior_type', 'First Half')
                ->orWhere('behavior_type', 'Last Half')
                ->count();
            $filled_half_day_leaves = $filled_half_day_leaves;
            $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
            $over_half_day_leaves = floor($filled_half_day_leaves / 2);

            $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];

            $over_absent_days = $statistics['absent'] - $filled_full_day_leaves;
            $data['absent_days_amount'] = $over_absent_days * $data['per_day_salary'];

            $total_full_and_half_days_absent = $over_absent_days + $over_half_day_leaves;

            $all_absents = $total_full_and_half_days_absent + $discrepancies_absent_days;
            $all_absent_days_amount = $data['per_day_salary'] * $all_absents;

            $data['earning_days_amount'] =  $data['total_earning_days'] * $data['per_day_salary'];

            if (!empty($employee->hasAllowance)) {
                $data['car_allowance'] = $employee->hasAllowance->allowance;
            } else {
                $data['car_allowance'] = 0;
            }

            $data['total_actual_salary'] = $data['salary'];
            $total_earning_salary = $data['earning_days_amount'];
            $data['total_earning_salary'] = $total_earning_salary;
            $total_earning_and_deduction_amount = $total_earning_salary + $all_absent_days_amount;
            $data['total_leave_discrepancies_approve_salary'] = $data['salary'] - $total_earning_and_deduction_amount;
            $data['net_salary'] = $total_earning_salary + $data['car_allowance'] + $data['total_leave_discrepancies_approve_salary'];

            MonthlySalaryReport::create([
                'employee_id' => $employee->id,
                'month_year' => $data['month_year'],
                'actual_salary' =>  $data['total_actual_salary'],
                'car_allowance' =>  $data['car_allowance'],
                'earning_salary' =>  $data['total_earning_salary'],
                'approved_days_amount' =>  $data['total_leave_discrepancies_approve_salary'],
                'deduction' =>  $all_absent_days_amount, //deduction
                'net_salary' =>  $data['net_salary'],
                'generated_date' =>  date('Y-m-d'),
            ]);
        }
        return 'done';
    }

    public function emailTemplate(){
        // return 'Already Done';
        // $mailData = [
        //             'from' => 'birthday',
        //             'title' => 'Birthday Greeting',
        //             'name' => 'User Name',
        //         ];
        // return view('emails.birthday', compact('mailData'));

        $employee_info = [
            'name' => 'User name',
            'email' => 'user@email',
            'password' => 'user@123',
            'manager' => 'Manager Name',
            'designation' => 'Designation Name',
            'department' => 'Department Name',
            'shift_time' => 'Shift Time',
            'joining_date' => 'joining Date',
        ];

        $mailData = [
            'from' => 'employee_info',
            'title' => 'Employee Approval and Joining Information',
            'employee_info' => $employee_info,
        ];

        return view('emails.employee-info', compact('mailData'));

        // $mailData = [
        //     'from' => 'termination',
        //     'title' => 'Employee Termination Notification',
        //     'employee' => 'User Name',
        // ];

        // return view('emails.temination', compact('mailData'));

        // $employee_info = [
        //     'name' => 'User Name',
        //     'email' => 'user@email',
        //     'password' => 'user@123',
        // ];

        // $mailData = [
        //     'from' => 'welcome',
        //     'title' => 'Welcome to Our Team - Important Onboarding Information',
        //     'employee_info' => $employee_info,
        // ];

        // return view('emails.welcome', compact('mailData'));

        // $body = [
        //     'name' => 'Demo Name',
        //     'effective_date' => 'date',
        //     'current_salary' => 5,
        //     'increased_salary' => 3,
        //     'updated_salary' => 8,
        // ];

        // $mailData = [
        //     'from' => 'salary_increments',
        //     'title' => 'Promotion',
        //     'body' => $body,
        // ];

        // $model = User::where('id', 127)->first();

        // // send email on salary increments.
        // try{
        //     $current_salary = 0;
        //     if(isset($model->salaryHistory) && !empty($model->salaryHistory->salary)){
        //         $current_salary = $model->salaryHistory->salary;
        //     }
        //     $increased = 5;
        //     $updated_salary = $current_salary+$increased;

        //     $body = [
        //         'name' => $model->first_name.' '.$model->last_name,
        //         'effective_date' => date('d M Y'),
        //         'current_salary' => number_format($current_salary),
        //         'increased_salary' => number_format($increased),
        //         'updated_salary' => number_format($updated_salary),
        //     ];

        //     $mailData = [
        //         'from' => 'salary_increments',
        //         'title' => 'Promotion',
        //         'body' => $body,
        //     ];

        //     // return $mailData;

        //     if(!empty(sendEmailTo($model, 'promotion')) && !empty(sendEmailTo($model, 'promotion')['cc_emails'])){
        //         $to_emails = sendEmailTo($model, 'promotion')['to_emails'];
        //         $cc_emails = sendEmailTo($model, 'promotion')['cc_emails'];
        //         Mail::to($to_emails)->cc($cc_emails)->send(new Email($mailData));
        //     }elseif(!empty(sendEmailTo($model, 'promotion')['to_emails'])){
        //         $to_emails = sendEmailTo($model, 'promotion')['to_emails'];
        //         Mail::to($to_emails)->send(new Email($mailData));
        //     }

        //     return response()->json(['success' => true]);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return $e->getMessage();
        // }
        // //send email.


        // return view('emails.email', compact('mailData'));

        // $body = "Dear ".$model->first_name." ". $model->last_name.", <br /><br />".
        //         "I hope this email finds you well. I am writing to inform you about an important update regarding your employment. We are pleased to announce that your hard work, dedication, and valuable contributions to the company have been recognized. <br /><br />".
        //         "After careful consideration, we have decided to permanent. You have been permanent employees in this company regards outstanding performance, commitment, and the value you bring to our organization. <br /><br />".

        $mailData = [
            'from' => 'permanent',
            'title' => 'Permanent',
            'name' => 'Employee Name',
        ];

        return view('emails.permanent', compact('mailData'));
    }

    public function sendEmail(){
        return 'ALready Done';
        $users = User::where('id',101)->get();

        foreach($users as $user){
            $role = $user->getRoleNames()->first();
            foreach ($user->getRoleNames() as $user_role) {
                if ($user_role == 'Admin') {
                    $role = $user_role;
                } elseif ($user_role == 'Department Manager') {
                    $role = $user_role;
                }
            }

            $team_members = [];
            if ($role == 'Admin') {
                $departs = Department::where('manager_id', $user->id)->get();
                $depart_ids = [];
                foreach($departs as $depart){
                    if(!empty($depart)){
                        $depart_ids[] = $depart->id;
                    }
                }

                $team_employees = DepartmentUser::whereIn('department_id', $depart_ids)->get();
                foreach ($team_employees as $team_employee) {
                    $dep_user = User::where('id', $team_employee->user_id)->where('status', 1)->where('is_employee')->first();
                    if (!empty($dep_user)) {
                        $team_members[] = $dep_user->email;
                    }
                }
            }
            if ($role == 'Department Manager') {
                if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
                    $user_department = $user->departmentBridge->department;
                }

                $dept_ids = [];
                if (isset($user_department) && !empty($user_department)) {
                    $sub_dep = Department::where('parent_department_id', $user_department->id)->where('manager_id', $user->id)->first();
                    if (!empty($sub_dep)) {
                        $dept_ids[] = $sub_dep->id;
                        $dept_ids[] = $sub_dep->parent_department_id;
                        $sub_deps = Department::where('parent_department_id', $sub_dep->id)->get();
                        if(!empty($sub_deps)){
                            foreach($sub_deps as $sub_department){
                                $dept_ids[] = $sub_department->id;
                            }
                        }
                    }else{
                        $sub_deps = Department::where('manager_id', $user->id)->get();
                        $dept_ids[] = $user_department->manager_id;
                        if (!empty($sub_deps) && count($sub_deps)) {
                            foreach ($sub_deps as $sub_dept) {
                                $dept_ids[] = $sub_dept->id;
                            }
                        }
                    }

                    $team_employees = DepartmentUser::whereIn('department_id', $dept_ids)->get();
                    if(!empty($sub_dep->parentDepartment->manager_id)){
                        $team_employees[] = (object)['user_id' => $sub_dep->parentDepartment->manager_id];
                    }

                    foreach($team_employees as $team_employee){
                        $dep_user = User::where('id', $team_employee->user_id)->where('status', 1)->where('is_employee', 1)->first();
                        if (!empty($dep_user)) {
                            $team_members[] = $dep_user->email;
                        }
                    }
                }
            } elseif ($role == 'Employee') {
                if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
                    $user_department = $user->departmentBridge->department;
                }

                $team_member_ids = [];
                $parent_dept_teams = [];
                if (isset($user_department) && !empty($user_department)) {
                    $parent_departments = Department::where('parent_department_id', $user_department->parent_department_id)->where('status', 1)->get();
                    if (!empty($parent_departments)) {
                        $parent_dept_ids = [];
                        $team_members[] = $user_department->parentDepartment->manager->email;
                        foreach($parent_departments as $parent_dept){
                            $parent_dept_ids[] = $parent_dept->parent_department_id;
                            $parent_dept_ids[] = $parent_dept->id;
                        }
                        $parent_dept_teams = DepartmentUser::whereIn('department_id', $parent_dept_ids)->where('end_date', null)->get(['user_id']);
                    }else{
                        $team_members[] = $user_department->manager->email;
                    }
                    $team_member_ids = DepartmentUser::where('department_id', $user_department->id)->where('user_id', '!=', $user->id)->where('end_date', null)->get(['user_id']);
                }

                $team_member_ids = collect($parent_dept_teams)->merge($team_member_ids)->all();

                if(sizeof($team_member_ids) > 0) {
                    foreach($team_member_ids as $team_member_id) {
                        $dep_user = User::where('id', $team_member_id->user_id)->where('status', 1)->where('is_employee', 1)->first();
                        if (!empty($dep_user)) {
                            $team_members[] = $dep_user->email;
                        }
                    }
                }
            }

            $mailData = [
                    'from' => 'birthday',
                    'title' => 'Birthday Greeting',
                    'name' => $user->first_name .' '. $user->last_name,
                ];

            Mail::to($user->email)->cc($team_members)->send(new Email($mailData));
        }

        // return 'done';
    }

     public function getOldPortalDiscrepancies(){
        return 'Done Already';
        $jsonString = '';

        $discrepancies = json_decode($jsonString, true);

        $discrepancies_data = [];
        $discrepancy_with_out_data = [];
        foreach ($discrepancies as $discrepancy) {
            $user_profile = Profile::where('employment_id', $discrepancy['employment_id'])->first();
            if(!empty($user_profile)){
                $attendance = DB::table('attendances')->where('in_date', 'like', $discrepancy['date'].'%')->where('user_id', $user_profile->user_id)->first();

                if(!empty($attendance)){
                    Discrepancy::create([
                        'approved_by' => 1,
                        'user_id' => $user_profile->user_id,
                        'attendance_id' => $attendance->id,
                        'date' => $discrepancy['date'],
                        'type' => $discrepancy['type'],
                        'description' => $discrepancy['description'],
                        'status' => $discrepancy['status'],
                        'is_additional' => $discrepancy['is_additional'],
                        'created_at' => $discrepancy['created_at'],
                        'updated_at' => $discrepancy['updated_at'],
                    ]);
                } else {
                    $discrepancy_with_out_data[] = [
                        'attendance' => 'not found',
                        'approved_by' => 1,
                        'employment_id' => $discrepancy['employment_id'],
                        'user_id' => $discrepancy['user_id'],
                        'attendance_id' => NULL,
                        'date' => $discrepancy['date'],
                        'type' => $discrepancy['type'],
                        'description' => $discrepancy['description'],
                        'status' => $discrepancy['status'],
                        'is_additional' => $discrepancy['is_additional'],
                        'created_at' => $discrepancy['created_at'],
                        'updated_at' => $discrepancy['updated_at'],
                    ];
                }
            } else {
                $discrepancy_with_out_data[] = [
                    'approved_by' => 1,
                    'employment_id' => $discrepancy['employment_id'],
                    'user_id' => $discrepancy['user_id'],
                    'attendance_id' => NULL,
                    'date' => $discrepancy['date'],
                    'type' => $discrepancy['type'],
                    'description' => $discrepancy['description'],
                    'status' => $discrepancy['status'],
                    'is_additional' => $discrepancy['is_additional'],
                    'created_at' => $discrepancy['created_at'],
                    'updated_at' => $discrepancy['updated_at'],
                ];
            }
        }

        return $discrepancy_with_out_data;
    }

    public function getOldPortalLeaves(){
        return 'Done Already';
        $jsonString = '';

        $user_leave_data = json_decode($jsonString, true);

        $leaves_with_out_data = [];
        foreach ($user_leave_data as $leave) {
            $user_profile = Profile::where('employment_id', $leave['employment_id'])->first();
            if (!empty($user_profile)) {
                $user = User::where('id', $user_profile->user_id)->first();
                $department_id = '';
                if (isset($user->departmentBridge) && !empty($user->departmentBridge->department_id)) {
                    $department_id = $user->departmentBridge->department_id;
                }

                UserLeave::create([
                    'department_id' => $department_id,
                    'leave_type_id' => $leave['leave_type_id'],
                    'user_id' => $user->id,
                    'start_at' => $leave['start_at'],
                    'end_at' => $leave['end_at'],
                    'duration' => $leave['duration'],
                    'behavior_type' => $leave['behavior_type'],
                    'reason' => $leave['reason'],
                    'status' => $leave['status'],
                    'created_at' => $leave['created_at'],
                    'updated_at' => $leave['updated_at'],
                ]);
            } else {
                $leaves_with_out_data[] = [
                    'employment_id' => $leave['employment_id'],
                    'user_id' => $leave['user_id'],
                    'leave_type_id' => $leave['leave_type_id'],
                    'start_at' => $leave['start_at'],
                    'end_at' => $leave['end_at'],
                    'duration' => $leave['duration'],
                    'behavior_type' => $leave['behavior_type'],
                    'reason' => $leave['reason'],
                    'status' => $leave['status'],
                    'created_at' => $leave['created_at'],
                    'updated_at' => $leave['updated_at'],
                ];
            }
        }

        return $leaves_with_out_data;
    }

    public function changePasswordAllEmployees(){
        // return 'Already changed all employees password';
        $employees = User::where("id" , "!=" , 1)->where('status', 1)->where('is_employee', 1)->get();
        foreach ($employees as $employee) {
            $user_password = 'Cyberonix@2023';
            $employee->password = Hash::make($user_password);
            $employee->save();
        }

        return 'done';
    }

    public function sendEmailTermination(){
        return 'Already done';
        $model = User::where('id', 36)->first();

        $mailData = [
            'from' => 'termination',
            'title' => 'Employee Termination Notification',
            'employee' => $model->first_name . ' ' . $model->last_name,
        ];

        $to_emails = ['amar.chand@cyberonix.org', 'muhammad.umer@cyberonix.org'];
        Mail::to($to_emails)->send(new Email($mailData));

        return 'done';
    }

    public function birthday(){
        return 'Already done';
        $today = now();

        $users = User::whereHas('profile', function($query) use ($today) {
            $query->whereMonth('date_of_birth', $today->month)
                  ->whereDay('date_of_birth', $today->day);
        })->get();

        // $users = User::where('id', 6)->get();
        // $department_ids = [];

        foreach($users as $user){
            $team_members = [];

            // if($user->hasRole('Admin')){
            //     $department_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();

            //     // $departs = Department::where('manager_id', $user->id)->get();
            //     // $depart_ids = [];
            //     // foreach($departs as $depart){
            //     //     if(!empty($depart)){
            //     //         $depart_ids[] = $depart->id;
            //     //     }
            //     // }
            //     // return $depart_ids;
            //     // $team_employees = DepartmentUser::whereIn('department_id', $depart_ids)->get();
            //     // foreach($team_employees as $team_employee){
            //     //     $dep_user = User::where('id', $team_employee->user_id)->where('status', 1)->where('is_employee', 1)->first();
            //     //     if(!empty($dep_user)){
            //     //         $team_members[] = $dep_user->email;
            //     //     }
            //     // }
            // }elseif($user->hasRole('Department Manager')){
            //     // if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
            //     //     $user_department = $user->departmentBridge->department;
            //     // }

            //     // $dept_ids = [];
            //     // if(isset($user_department) && !empty($user_department)){
            //     //     $sub_dep = Department::where('parent_department_id', $user_department->id)->where('manager_id', $user->id)->first();
            //     //     if(!empty($sub_dep)){
            //     //         $dept_ids[] = $sub_dep->id;
            //     //         $dept_ids[] = $sub_dep->parent_department_id;
            //     //         $sub_deps = Department::where('parent_department_id', $sub_dep->id)->get();
            //     //         if(!empty($sub_deps)){
            //     //             foreach($sub_deps as $sub_department){
            //     //                 $dept_ids[] = $sub_department->id;
            //     //             }
            //     //         }
            //     //     }else{
            //     //         $sub_deps = Department::where('manager_id', $user->id)->get();
            //     //         $dept_ids[] = $user_department->manager_id;
            //     //         if(!empty($sub_deps) && count($sub_deps)){
            //     //             foreach($sub_deps as $sub_dept){
            //     //                 $dept_ids[] = $sub_dept->id;
            //     //             }
            //     //         }
            //     //     }
            //     //     // return $dept_ids;

            //     //     $team_employees = DepartmentUser::whereIn('department_id', $dept_ids)->get();
            //     //     if(!empty($sub_dep->parentDepartment->manager_id)){
            //     //         $team_employees[] = (object)['user_id' => $sub_dep->parentDepartment->manager_id];
            //     //     }
            //     //     // return $team_employees;
            //     //     foreach($team_employees as $team_employee){
            //     //         $dep_user = User::where('id', $team_employee->user_id)->where('status', 1)->where('is_employee', 1)->first();
            //     //         if(!empty($dep_user)){
            //     //             $team_members[] = $dep_user->email;
            //     //         }
            //     //     }
            //     //     return $team_members;
            //     // }

            //     $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
            //     $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
            //     $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
            //     if(!empty($child_departments) && count($child_departments) > 0){
            //         $department_ids = array_unique(array_merge($department_ids, $child_departments));
            //     }
            // }elseif($user->hasRole('Employee')){
            //     if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
            //         $user_department = $user->departmentBridge->department;
            //     }

            //     $team_member_ids = [];
            //     $parent_dept_teams = [];
            //     if(isset($user_department) && !empty($user_department)){
            //         $parent_departments = Department::where('parent_department_id', $user_department->parent_department_id)->where('status', 1)->get();
            //         if(!empty($parent_departments)){
            //             $parent_dept_ids = [];
            //             $team_members[] = $user_department->parentDepartment->manager->email;
            //             foreach($parent_departments as $parent_dept){
            //                 $parent_dept_ids[] = $parent_dept->parent_department_id;
            //                 $parent_dept_ids[] = $parent_dept->id;
            //             }
            //             $parent_dept_teams = DepartmentUser::whereIn('department_id', $parent_dept_ids)->where('end_date', null)->get(['user_id']);
            //         }else{
            //             $team_members[] = $user_department->manager->email;
            //         }
            //         $team_member_ids = DepartmentUser::where('department_id', $user_department->id)->where('user_id', '!=', $user->id)->where('end_date', null)->get(['user_id']);
            //     }

            //     $team_member_ids = collect($parent_dept_teams)->merge($team_member_ids)->all();

            //     if(sizeof($team_member_ids) > 0) {
            //         foreach($team_member_ids as $team_member_id) {
            //             $dep_user = User::where('id', $team_member_id->user_id)->where('status', 1)->where('is_employee', 1)->first();
            //             if(!empty($dep_user)){
            //                 $team_members[] = $dep_user->email;
            //             }
            //         }
            //     }
            // }

            $team_members = User::whereIn('id', getTeamMemberIds($user))->pluck('email')->toArray();

            $mailData = [
                    'from' => 'birthday',
                    'title' => 'Birthday Greeting',
                    'name' => $user->first_name .' '. $user->last_name,
                ];

            // Mail::to($user->email)->cc($team_members)->send(new Email($mailData));
        }
    }
}
