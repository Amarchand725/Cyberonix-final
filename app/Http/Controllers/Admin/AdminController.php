<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\AttendanceSummary;
use App\Models\WFHEmployee;
use App\Models\UserLeave;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Discrepancy;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\DepartmentUser;
use App\Models\WorkingShiftUser;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Session;
use App\Models\Otp;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function departments()
    {
        $this->authorize('department-list');
        return view('admin.departments');
    }
    public function logOut()
    {
        if (Auth::check()) {
            Session::invalidate(); // Invalidates the user's session
            return redirect()->route('admin.login');
        }
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user()->load('profile', 'profile.coverImage', 'jobHistory', 'jobHistory.designation', 'userWorkingShift', 'userWorkingShift.workShift', 'departmentBridge', 'departmentBridge.department');
        $data = [];

        $data['year'] = date('Y');
        if(date('d')>26 || (date('d')==26 && date('H')>11)){
          $data['month']=date('m',strtotime('first day of +1 month'));
        }else{
          $data['month']=date('m');
        }

        if ($data['month'] == 01) {
            $data['year'] = date('Y', strtotime('first day of +1 month'));
        }

        $startShiftTime = '';
        $endShiftTime = '';
        if(isset($user->userWorkingShift->workShift) && !empty($user->userWorkingShift->workShift->start_time)){
            $data['shift'] = $user->userWorkingShift->workShift;
        }else{
            $data['shift'] = defaultShift();
        }

        $startShiftTime = $data['shift']->start_time;
        $endShiftTime = $data['shift']->end_time;

        $data['announcements'] = Announcement::orderby('id', 'desc')->where('status', 1)->get();

        $team_members = [];
        $department_ids = [];
        $department_manager = '';

        if(date('m')==$data['month']){
            $currentMonthStart = Carbon::now()->subMonth()->startOfMonth()->addDays(25);
            $currentMonthEnd = Carbon::now()->startOfMonth()->addDays(24);
        }else{
            $currentMonthStart = Carbon::now()->startOfMonth()->addDays(25);
            $currentMonthEnd = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        }

        if(isset($user->departmentBridge->department->manager) && !empty($user->departmentBridge->department->manager)){
            $department_manager = $user->departmentBridge->department->manager;
        }

        $data['department_manager'] = $department_manager;

        if($user->hasRole('Admin') || $user->hasRole('Developer')){
            // $dep_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
            // if(!empty($dep_ids)){
            //     $department_ids = array_unique(array_merge($department_ids, $dep_ids));
            // }

            $data['employees'] = User::doesntHave("hasWFHEmployee")->with(
                    'profile', 'profile.coverImage', 'jobHistory', 'jobHistory.designation',
                    'userWorkingShift', 'userWorkingShift.workShift', 'departmentBridge',
                    'departmentBridge.department', 'departmentBridge.department.departmentWorkShift.workShift'
                )->where('status', 1)->where('is_employee', 1)->get();

                $employees_ids = [];
                foreach($data['employees'] as $emp){
                    $employees_ids[] = $emp->id;
                }

                $data['employee_ids'] = $employees_ids;

            $data['current_month_discrepancies'] = Discrepancy::with('hasEmployee', 'hasEmployee.profile', 'hasAttendance')->where('user_id', '!=', NULL)->whereBetween('date', [$currentMonthStart, $currentMonthEnd])->orderby('status', 'asc')->get();
            $data['current_month_leave_requests'] = UserLeave::with('hasEmployee', 'hasEmployee.profile', 'hasLeaveType')->whereBetween('start_at', [$currentMonthStart, $currentMonthEnd])->orderby('status', 'asc')->get();
        }else if($user->hasRole('Department Manager')){
            // $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();

            // $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
            // $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
            // if(!empty($child_departments) && count($child_departments) > 0){
            //     $department_ids = array_unique(array_merge($department_ids, $child_departments));
            // }

            $leave_report = hasExceededLeaveLimit($user);
            $data['remaining_filable_leaves'] = $leave_report['total_remaining_leaves'];

        }elseif($user->hasRole('Employee')){
            $leave_report = hasExceededLeaveLimit($user);
            $data['remaining_filable_leaves'] = $leave_report['total_remaining_leaves'];

            // if(!empty($user->departmentBridge->department_id)){
            //     $department_ids[] = $user->departmentBridge->department_id;
            // }
        }

        // $team_members = DepartmentUser::whereIn('department_id', $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
        // $data['team_members'] = User::with('profile', 'jobHistory', 'jobHistory.designation', 'employeeStatus', 'employeeStatus.employmentStatus')->whereIn('id', $team_members)->where('id', '!=', $user->id)->where('is_employee', 1)->where('status', 1)->get();

        $data['team_members'] = getTeamMembers($user);

        if($user->hasRole('Department Manager')){
            $data['current_month_discrepancies'] = Discrepancy::with('hasEmployee', 'hasEmployee.profile', 'hasAttendance')->where('user_id', '!=', NULL)->whereBetween('date', [$currentMonthStart, $currentMonthEnd])->whereIn('user_id', $team_members)->orderby('status', 'asc')->get();
            $data['current_month_leave_requests'] = UserLeave::with('hasEmployee', 'hasEmployee.profile', 'hasLeaveType')->whereBetween('start_at', [$currentMonthStart, $currentMonthEnd])->whereIn('user_id', $team_members)->orderby('status', 'asc')->get();
        }

        $data['punchedIn_time']='Not yet';
        $data['punchedIn_date']='Not yet';

        $data['punchedOut_time']='Not yet';
        $data['punchedOut_date']='Not yet';

        $todayDate = date("Y-m-d");
        if(date("H")>=8){
            $nextDate = date("Y-m-d", strtotime($todayDate.'+1 day'));
        }else{
            $todayDate = date("Y-m-d", strtotime($todayDate.'-1 day'));
            $nextDate = date("Y-m-d", strtotime($todayDate.'+1 day'));
        }

        $attendances = DB::table('attendances')->where('user_id',Auth::user()->id)->whereBetween('in_date',[$todayDate.' 00:00',$nextDate.' 23:59'])->get();

        if(count($attendances)>0){
            $shiftStart = date("H:i:s", strtotime('-6 hours '.$startShiftTime));
            $shiftEnd = date("H:i:s", strtotime('+10 hours '.$endShiftTime));
            $punchedIn = DB::table('attendances')->where('user_id', Auth::user()->id)->where('behavior','I')->whereBetween('in_date',[$todayDate.' '.$shiftStart,$nextDate.' '.$shiftEnd])->orderBy('id', 'asc')->first();
            $punchedOut = DB::table('attendances')->where('user_id',Auth::user()->id)->where('behavior','O')->whereBetween('in_date',[$todayDate.' '.$shiftStart,$nextDate.' '.$shiftEnd])->orderBy('id', 'desc')->first();
            if($punchedIn!=null){
                $punchedIn_data=new DateTime($punchedIn->in_date);
                $data['punchedIn_date']=$punchedIn_data->format('d M Y');
                $data['punchedIn_time']=$punchedIn_data->format('h:i A');
            }

            if($punchedOut!=null){
                $punchedOut_data=new DateTime($punchedOut->in_date);
                $data['punchedOut_date']=$punchedOut_data->format('d M Y');
                $data['punchedOut_time']=$punchedOut_data->format('h:i A');
            }
        }
        // Step 1: Get the shift start time and current time (in 24-hour format)
        $shiftStartTime = $startShiftTime;
        $currentDateTime = date('H:i:s');

        // Step 2: Calculate the progress percentage
        $shiftStartTimestamp = strtotime($shiftStartTime);
        $currentTimestamp = strtotime($currentDateTime);

        // If the current time is before the shift start time, subtract 24 hours from the current timestamp
        if ($currentTimestamp < $shiftStartTimestamp) {
            $currentTimestamp += 24 * 60 * 60; // Add 24 hours in seconds
        }

        $shiftEndTimestamp = strtotime($endShiftTime) + 24 * 60 * 60; // Add 24 hours to the shift end time
        $totalDuration = $shiftEndTimestamp - $shiftStartTimestamp;
        $elapsedDuration = $currentTimestamp - $shiftStartTimestamp;
        $progressPercentage = ($elapsedDuration / $totalDuration) * 100;

        $data['currentDateTime'] = $currentDateTime;
        $data['endShiftTime'] = $endShiftTime;

        $data['check_in_to_current_duration_of_shift'] = $progressPercentage;
        $data['remaining_duration_shift'] = 100-$progressPercentage;

        $data['user'] = $user;

        if($user->hasRole('Admin')){
            $data['title'] = 'Admin Dashboard';
            return view('admin.dashboards.admin-dashboard', compact('data'));
        }elseif($user->hasRole('Department Manager')){
            $data['title'] = 'Manager Dashboard';
            return view('admin.dashboards.manager-dashboard', compact('data'));
        }else{
            $data['title'] = 'Employee Dashboard';
            return view('admin.dashboards.emp-dashboard', compact('data'));
        }
    }

    public function loginForm()
    {
        $title = 'Login';
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('admin.auth.login', compact('title'));
        }
    }
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
            if ($user->status == 1) {
                return response()->json(['success' => true, 'route' => route('dashboard')]);
            } else {
                Auth::logout(); // Log out the user if they are not active
                return response()->json(['error' => 'Your account is not active.']);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials']);
        }

        // original function
        // $credentials = $request->only('email', 'password');
        // if (isset($credentials) && !empty($credentials)) {
        //     $user = User::where('email', $request->email)->first();
        //     if (isset($user) && !empty($user) && Hash::check($request->password, $user->password)) {
        //         if ($user->status == 1) {
        //             $secretKey = isset($request->secretKey) && !empty($request->secretKey) ? $request->secretKey : null;
        //             $verify_user_secret_key = UserVerification::where(['user_id' => $user->id, 'user_verification_key' => $secretKey, 'status' => 1])->first();
        //             if (isset($verify_user_secret_key) && !empty($verify_user_secret_key)) {
        //                 Auth::attempt($credentials, $request->remember);
        //                 return response()->json(['success' => true, 'route' => route('dashboard')]);
        //             } else {
        //                 // if(isset($user->profile->phone_number) && !empty($user->profile->phone_number)) {
        //                     $otp_result = generateOTP($user->id);
        //                     if ($otp_result['success'] == true) {
        //                         session()->forget('user_id');
        //                         session()->forget('remember');

        //                         $request->session()->put('user_id', $user->id);
        //                         $request->session()->put('remember', $request->remember);

        //                         return response()->json(['success' => true, 'route' => route('admin.otpForm')]);
        //                     } else {
        //                         return response()->json(['success' => false, 'error' => $otp_result['message']]);
        //                     }
        //                 // } else {
        //                 //     return response()->json(['success' => false, 'error' => 'Please update your phone number on your profile for sending verification otp.']);
        //                 // }
        //             }
        //         } else {
        //             Auth::logout(); // Log out the user if they are not active
        //             return response()->json(['error' => 'Your account is not active.']);
        //         }
        //     } else {
        //         Auth::logout(); // Log out the user if they are not active
        //         return response()->json(['error' => 'Invalid credentials.']);
        //     }
        // } else {
        //     return response()->json(['error' => 'Invalid credentials']);
        // }
    }

    //This is for WFH Users check in & out function.
    public function wfhCheckIn()
    {
        $user_work_shift = WorkingShiftUser::where('user_id', Auth::user()->id)->where('end_date', null)->first();
        $checked_in = Attendance::create([
            'user_id' => Auth::user()->id,
            'work_shift_id' => $user_work_shift->working_shift_id,
            'in_date' => date('Y-m-d H:i:s'),
            'behavior' => 'I',
        ]);

        if ($checked_in) {
            return redirect()->back()->with('message', 'You have checked in successfully.');
        }
    }

    public function wfhCheckOut()
    {
        $user_work_shift = WorkingShiftUser::where('user_id', Auth::user()->id)->where('end_date', null)->first();
        $check_out = Attendance::create([
            'user_id' => Auth::user()->id,
            'work_shift_id' => $user_work_shift->working_shift_id,
            'in_date' => date('Y-m-d H:i:s'),
            'behavior' => 'O',
        ]);

        if ($check_out) {
            return redirect()->back()->with('message', 'You have checkedout Successfully.');
        }
    }

    // otp verification function
    public function otpForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            $title = 'Enter Otp';
            $data = [
                'user_id' => session()->get('user_id') ?? null,
                'remember' => session()->get('remember') ?? null,
                'title' => 'Enter Otp',
            ];
            return view('admin.auth.otp-verification')->with($data);
        }
    }

    public function otpVerification(Request $request)
    {
        if (isset($request->user_id) && !empty($request->user_id)) {
            $user = User::where('id', $request->user_id)->first();
            if (isset($user) && !empty($user)) {
                if (isset($request->otp) && !empty($request->otp)) {
                    $checkOtp = Otp::where(['user_id' => $request->user_id, 'otp' => $request->otp])->first();
                    if (isset($checkOtp) && !empty($checkOtp)) {
                        if ($checkOtp->status == 0) {
                            $result = createUserVerification($user->id);
                            if (isset($result) && !empty($result) && $result['success'] == true) {
                                $remember = isset($request->remember) && !empty($request->remember) ? true : false;
                                Auth::login($user, $remember);
                                $checkOtp->update([
                                    'status' => 1
                                ]);
                                session()->forget('user_id');
                                session()->forget('remember');
                                return response()->json([
                                    'success' => true,
                                    'user_verification_key' => $result['user_verification_key'] ?? null,
                                    'secretKeyName' => getSecretKeyForStorage(),
                                ]);
                            } else {
                                return response()->json(['success' => false, 'error' => 'Your verification is failed']);
                            }
                        } else {
                            return response()->json(['success' => false, 'error' => 'This Otp is expired']);
                        }
                    } else {
                        return response()->json(['success' => false, 'error' => 'Invalid Otp']);
                    }
                } else {
                    return response()->json(['success' => false, 'error' => 'The otp field is required.']);
                }
            } else {
                return response()->json(['success' => false, 'error' => 'User not found']);
            }
        } else {
            return response()->json(['success' => false, 'error' => 'Some thing went wrong.']);
        }
    }

    public function verifyUserToken(Request $request)
    {
        if(Auth::check()) {
            if(isset($request->token) && !empty($request->token)) {
                $secretKey = isset($request->token) && !empty($request->token) ? $request->token : null;
                $verify_user_secret_key = UserVerification::where(['user_id' => Auth::user()->id, 'user_verification_key' => $secretKey, 'status' => 1])->first();
                if(isset($verify_user_secret_key) && !empty($verify_user_secret_key)) {
                    return response()->json(['success' => true]);
                } else {
                    Auth::logout(); // Log out the user if they are unverified
                    return response()->json(['success' => false, 'error' => 'Your account is unverified.', 'route' => route('admin.login')]);
                }
            } else {
                Auth::logout(); // Log out the user if they are unverified
                return response()->json(['success' => false, 'error' => 'Your account is unverified.', 'route' => route('admin.login')]);
            }
        } else {
            return response()->json(['success' => false, 'error' => 'Your account is unverified.', 'route' => route('admin.login')]);
        }
    }
    public function masterLogin($id)
    {
        $user = User::whereHas('profile', function ($query) use ($id) {
            $query->where("employment_id", $id);
        })->first();
        if (!empty($user) && !empty($user->profile->employment_id)) {

            if (Auth::user()) {
                Auth::logout();
            }

            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            return redirect()->route("admin.login");
        }
    }

    public function getKeyName(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (isset($user) && !empty($user) && Hash::check($request->pass, $user->password)) {
            return apiResponse(true , "" , getSecretKeyForStorage($user->id)  , 200);
        } else {
            return apiResponse(false, "" ,  null  , 200);
        }
    }
}
