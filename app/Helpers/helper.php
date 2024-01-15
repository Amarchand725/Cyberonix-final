<?php

use App\Mail\Email;
use App\Models\Otp;
use App\Models\User;
use App\Models\Asset;
use GuzzleHttp\Client;
use App\Models\Holiday;
use App\Models\Setting;
use App\Models\Currency;
use Carbon\CarbonPeriod;
use App\Models\SystemLog;
use App\Models\UserLeave;
use App\Models\WorkShift;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\AssetDetail;
use App\Models\BankAccount;
use App\Models\Discrepancy;
use App\Models\OtpResponse;
use App\Models\VehicleUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;
use App\Models\AssetHistory;
use App\Models\TicketReason;
use App\Models\DeleteHistory;
use App\Models\AuthorizeEmail;
use App\Models\DepartmentUser;
use App\Models\TicketCategory;
use Illuminate\Support\Carbon;
use App\Models\EmploymentStatus;
use App\Models\UserVerification;
use App\Models\WorkingShiftUser;
use App\Models\AttendanceSummary;
use App\Models\InventoryCategory;
use App\Models\MonthlySalaryReport;
use Illuminate\Support\Facades\Log;
use App\Models\AttendanceAdjustment;
use App\Models\UserEmploymentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\HolidayCustomizeEmployee;
use Spatie\Permission\Models\Permission;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Attributes\Depends;
use App\Http\Controllers\AttendanceController;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use GuzzleHttp\RequestOptions;

function SubPermissions($label)
{
    return Permission::where('label', $label)->get();
}
function bankDetail()
{
    return BankAccount::where('user_id', Auth::user()->id)->first();
}
function settings()
{
    return Setting::first();
}

function defaultShift()
{
    return WorkShift::where('is_default', 1)->where('status', 1)->first();
}

function appName()
{
    $setting = Setting::first();
    if (isset($setting) && !empty($setting->name)) {
        $app_name = $setting->name;
    } else {
        $app_name = '-';
    }

    return $app_name;
}

function isOnProbation($user)
{
    if (isset($user->employeeStatus) && !empty($user->employeeStatus->end_date)) {
        $probation_end_date = $user->employeeStatus->end_date;
        return Carbon::today()->lte($probation_end_date);
    }
}

function hasExceededLeaveLimit($user)
{
    // $probation = UserEmploymentStatus::where('user_id', $user->id)->first();
    $probation = $user->employeeStatus;

    if (!empty($probation) && $probation->employment_status_id == 1) {
        $leave_report = [
            'total_leaves' => 0,
            'total_remaining_leaves' => 0,
            'total_leaves_in_account' => 0,
            'total_used_leaves' => 0,
            'leaves_in_balance' => 0,
        ];

        return $leave_report;
    } else {
        // Calculate the start and end dates of the current leave year
        // $currentYear = Carbon::now()->year;
        // $leaveYearStart = Carbon::createFromDate($currentYear, 6, 26); // June 26th of the current year
        // $leaveYearEnd = Carbon::createFromDate($currentYear + 1, 7, 25); // June 25th of the next year

        // Calculate the total used leaves within the leave year
        $total_used_leaves = UserLeave::where('user_id', $user->id)
            ->where('status', 1)
            ->whereBetween('start_at', [yearPeriod()['yearStart'], yearPeriod()['yearEnd']])
            ->sum('duration');

        // Calculate the number of months from the start date to the current date
        $currentDate = Carbon::now();
        $leaveYearStart = Carbon::createFromDate(yearPeriod()['yearStart']); // June 26th of the current year
        $monthsElapsed = $leaveYearStart->diffInMonths($currentDate) + 1;
        $leaveYearDate = Carbon::createFromDate(yearPeriod()['yearEnd']); // June 26th of the current year

        // Check if the user joined after the leave year started
        $joiningDate = Carbon::createFromDate($user->employeeStatus->start_date); // Replace with the actual joining date
        if ($joiningDate > $leaveYearStart) {
            $monthsElapsed = max(0, $joiningDate->diffInMonths($currentDate)) + 1;

            $interval = $joiningDate->diff($leaveYearDate);
            $monthsDifference = ($interval->y * 12) + $interval->m;
            $total_leaves = $monthsDifference * 2;
        } else {
            $interval = $leaveYearStart->diff($leaveYearDate);
            $monthsDifference = ($interval->y * 12) + $interval->m;
            $total_leaves = $monthsDifference * 2;
        }

        $total_leaves_in_account = $monthsElapsed * 2;

        // Calculate the leave balance
        $leaves_in_balance = $total_leaves - $total_used_leaves;
        if ((float) $total_used_leaves >= (float) $total_leaves) {
            $leaves_in_balance = 0;
            $total_used_leaves = $total_leaves;
        }
        // if ((float) $total_used_leaves >=  (float) $total_leaves) {
        //     $total_used_leaves =  $total_leaves;
        // }
        $leave_report = [
            'total_leaves' => $total_leaves,
            'total_remaining_leaves' => $total_leaves - $total_used_leaves,
            'total_leaves_in_account' => $total_leaves_in_account,
            'total_used_leaves' => $total_used_leaves,
            'leaves_in_balance' => $leaves_in_balance,
        ];

        return $leave_report;
    }
}

function yearPeriod()
{
    $currentDate = Carbon::now();

    // Determine the leave start date based on the current date
    if ($currentDate->gte(Carbon::createFromDate($currentDate->year, 6, 26))) {
        // Leave tenure starts from June 26 of the current year
        $leaveStartDate = Carbon::createFromDate($currentDate->year, 6, 26);
    } else {
        // Leave tenure starts from June 26 of the previous year
        $leaveStartDate = Carbon::createFromDate($currentDate->year - 1, 6, 26);
    }

    $yearPeriod = [];
    // Add one year to get the end date
    $leaveEndDate = $leaveStartDate->copy()->addYear()->subDay()->addMonth(); // Adjusted for July 25
    $leaveYearStart = $leaveStartDate->toDateString();
    $leaveYearEnd = $leaveEndDate->toDateString();

    $yearPeriod['yearStart'] = $leaveYearStart;
    $yearPeriod['yearEnd'] = $leaveYearEnd;

    return $yearPeriod;
}

function getAttandanceCount($user_id, $year_month_pre, $year_month_post, $behavior, $shift)
{
    return AttendanceController::getAttandanceCount($user_id, $year_month_pre, $year_month_post, $behavior, $shift);
}

function getAttandanceSingleRecord($userID, $current_date, $next_date, $status, $shiftID)
{
    return AttendanceController::getAttandanceSingleRecord($userID, $current_date, $next_date, $status, $shiftID);
}
function userAppliedLeaveOrDiscrepency($user_id, $type, $start_at)
{
    if ($type == 'absent' || $type == 'firsthalf' || $type == "lasthalf") {
        // $user_leave =  UserLeave::where('is_applied', 1)->where('user_id', $user_id)->first();
        $user_leave = UserLeave::where('user_id', $user_id)
            ->where('is_applied', 1)
            ->whereDate('start_at', '<=', $start_at)
            ->whereDate('end_at', '>=', $start_at)
            ->first();

        if (!empty($user_leave)) {
            $check_date = Carbon::parse($start_at);
            $shift_start_at = Carbon::parse($user_leave->start_at);
            $shift_end_at = Carbon::parse($user_leave->end_at);

            if ($check_date->between($shift_start_at, $shift_end_at)) {
                return $user_leave;
            } else {
                return UserLeave::where('user_id', $user_id)->where('behavior_type', $type)->where('start_at', $start_at)->first();
            }
        } else {
            return UserLeave::where('user_id', $user_id)->where('behavior_type', $type)->where('start_at', $start_at)->first();
        }
    } elseif ($type == 'lateIn' || $type = "earlyout") {
        return Discrepancy::where('user_id', $user_id)->where('type', $type)->where('date', $start_at)->first();
    }
}

function formatLetterTitle($text)
{
    // Remove underscores and replace with spaces
    $textWithoutUnderscores = str_replace('_', ' ', $text);

    // Capitalize the first character of each word
    $formattedText = ucwords($textWithoutUnderscores);

    return $formattedText;
}

function notifyBy($created_by)
{
    return User::where('id', $created_by)->first();
}

function chatSupportData()
{
    //new code
    $department_names = ['Main Department', 'Accounts & Finance', 'IT Department'];
    $departments = Department::where('status', 1)->whereIn('name', $department_names)->get();

    $adminUsers = [];
    $financeUsers = [];
    $itUsers = [];
    foreach ($departments as $department) {
        $dept_users = DepartmentUser::where('department_id', $department->id)->where('end_date', NULL)->pluck('user_id')->toArray();
        if ($department->name == 'Main Department') {
            $adminUsers = $dept_users;
        } elseif ($department->name == 'Accounts & Finance') {
            $financeUsers = $dept_users;
        } elseif ($department->name == 'IT Department') {
            $itUsers = $dept_users;
        }
    }
    //new code

    $adminUsers = array_values(array_unique($adminUsers));
    $financeUsers = array_values(array_unique($financeUsers));
    $itUsers = array_values(array_unique($itUsers));
    $data['adminUsersID'] = $adminUsers;
    $data['financeUsersID'] = $financeUsers;
    $data['itUsersID'] = $itUsers;

    $data['adminUsers'] = User::with('profile', 'profile.coverImage', 'jobHistory', 'jobHistory.designation')->whereIn('id', $adminUsers)->get();
    $data['financeUsers'] = User::with('profile', 'profile.coverImage', 'jobHistory', 'jobHistory.designation')->whereIn('id', $financeUsers)->get();
    $data['itUsers'] = User::with('profile', 'profile.coverImage', 'jobHistory', 'jobHistory.designation')->whereIn('id', $itUsers)->get();

    $team_members_ids = [];

    $user = Auth::user();
    $data['authUser'] = $user;

    if ($user->hasRole('Admin')) {
        $data['team_members'] = User::with('profile', 'profile.coverImage', 'jobHistory', 'jobHistory.designation')->where('is_employee', 1)->where('status', 1)->get();
    } else {
        if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
            $user_department = $user->departmentBridge->department;
        }

        $team_member_ids = [];
        if (isset($user_department) && !empty($user_department)) {
            $team_member_ids = DepartmentUser::where('department_id', $user_department->id)->where('user_id', '!=', $user->id)->where('end_date', null)->get(['user_id']);
        }

        if (sizeof($team_member_ids) > 0) {
            foreach ($team_member_ids as $team_member_id) {
                $team_members_ids[] = $team_member_id->user_id;
            }
        }

        $data['team_members'] = User::with('profile', 'profile.coverImage', 'jobHistory', 'jobHistory.designation')->whereIn('id', $team_members_ids)->get();
    }
    return $data;
}

function attendanceAdjustment($employee_id, $attendance_id, $date)
{
    $adjustment = AttendanceAdjustment::where('employee_id', $employee_id)
        ->where(function ($query) use ($attendance_id, $date) {
            $query->where('attendance_id', $attendance_id)
                ->orWhere('attendance_id', $date);
        })
        ->first();
    if (!empty($adjustment)) {
        return $adjustment;
    } else {
        return NULL;
    }
}

function sendEmailTo($user, $title)
{
    $authorize_email = AuthorizeEmail::where('status', 1)->where('email_title', $title)->first();

    $shoot_email = [];
    if (!empty($authorize_email)) {
        if (!empty($authorize_email->to_emails)) {
            $to_email_data = json_decode($authorize_email->to_emails);
            $to_emails = [];
            foreach ($to_email_data as $to_email) {
                if ($to_email == 'to_employee') {
                    $to_emails[] = $user->email;
                } else if ($to_email == 'to_ra') {
                    if ($user->hasRole('Department Manager')) {
                        $parent_department = Department::where('manager_id', $user->id)->first();
                        $manager = $parent_department->parentDepartment->manager;
                        $to_emails[] = $manager->email;
                    } else {
                        $manager = $user->departmentBridge->department->manager;
                        $to_emails[] = $manager->email;
                    }
                } else {
                    $user_email = getUser($to_email);
                    $to_emails[] = $user_email->email;
                }
            }
            $shoot_email['to_emails'] = $to_emails;
        }

        if (!empty($authorize_email->cc_emails)) {
            $cc_email_data = json_decode($authorize_email->cc_emails);
            $cc_emails = [];
            foreach ($cc_email_data as $cc_email) {
                if ($cc_email == 'to_employee') {
                    $cc_emails[] = $user->email;
                } else if ($cc_email == 'to_ra') {
                    if ($user->hasRole('Department Manager')) {
                        $parent_department = Department::where('manager_id', $user->id)->first();
                        $manager = $parent_department->parentDepartment->manager;
                        $cc_emails[] = $manager->email;
                    } else {
                        $manager = $user->departmentBridge->department->manager;
                        $cc_emails[] = $manager->email;
                    }
                } else {
                    $user_email = getUser($cc_email);
                    $cc_emails[] = $user_email->email;
                }
            }
            $shoot_email['cc_emails'] = $cc_emails;
        }
    }

    return $shoot_email;
}
function sendEmailforOfficeBoy($pre_employee, $user, $title, $mailData)
{
    try {
        $cc_emails = [];
        $authorize_email = AuthorizeEmail::where('status', 1)->where('email_title', $title)->first();
        if (!empty($authorize_email) && !empty($authorize_email->cc_emails)) {
            if (!empty($pre_employee->form_type) && $pre_employee->form_type == 2) {
                $cc_email_data = json_decode($authorize_email->cc_emails);
                foreach ($cc_email_data as $cc_email) {
                    if ($cc_email == 'to_ra') {
                        if ($user->hasRole('Department Manager')) {
                            $parent_department = Department::where('manager_id', $user->id)->first();
                            $manager = $parent_department->parentDepartment->manager;
                            $cc_emails[] = $manager->email;
                        } else {
                            $manager = $user->departmentBridge->department->manager;
                            $cc_emails[] = $manager->email;
                        }
                    } else if ($cc_email != 'to_employee') {
                        $cc_emails[] = $cc_email;
                    }
                }
                $shoot_email['cc_emails'] = $cc_emails;
            }
            $managerEmail = $user->departmentBridge->department->manager->email;
            Mail::to($managerEmail)->cc($cc_emails)->send(new Email($mailData));
            Log::info("EMail has been sent for Office Boy");
        }
    } catch (Exception $e) {
        Log::info("ERROR WHILE SENDING EMAIL TO OFFICE BOYS TEAM : " . json_encode($e->getMessage()));
    }
}
function insuranceEligibility()
{
    $setting = Setting::first();
    $user = User::where('id', Auth::user()->id)->first();
    $user_joining_date = $user->joiningDate->joining_date;

    // Your joining date in a variable (replace this with your actual date)
    $joiningDate = Carbon::create($user_joining_date);

    // Add 6 months to the joining date
    $newDate = $joiningDate->addMonths($setting->insurance_eligibility);
    $today = date('d-m-Y');

    if (Auth::user()->hasRole('Admin')) {
        return true;
    }

    if (strtotime($today) >= strtotime($newDate)) {
        return true;
    } else {
        return false;
    }
}
function getCars()
{
    $vehicle_user = VehicleUser::where('user_id', Auth::user()->id)->get();
    if (count($vehicle_user) > 0) {
        return true;
    } else {
        return false;
    }
}
function hrName()
{
    $department = Department::where('name', 'like', '%Admin%')->where('manager_id', '!=', NULL)->where('status', 1)->first();
    if (!empty($department) && !empty($department->manager)) {
        $manager_full_name = $department->manager->first_name . ' ' . $department->manager->last_name;
    } else {
        $manager_full_name = 'N/A';
    }

    return $manager_full_name;
}
function checkAttendance($userID, $current_date, $next_date, $shift)
{
    // $user = User::where('id', $userID)->first();

    $start_time = date("Y-m-d H:i:s", strtotime($current_date . ' ' . $shift->start_time));
    $end_time = date("Y-m-d H:i:s", strtotime($next_date . ' ' . $shift->end_time));

    $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
    $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));

    $punchIn = Attendance::where('user_id', $userID)->whereBetween('in_date', [$start, $end])->orderBy('in_date', 'asc')->first();
    if (empty($punchIn)) {
        return true;
    } else {
        return false;
    }
}

function checkAdjustedAttendance($userID, $current_date)
{
    $punchIn = AttendanceAdjustment::where('employee_id', $userID)->where('attendance_id', $current_date)->first();
    if (empty($punchIn) || $punchIn->mark_type == 'absent') {
        return true;
    } else {
        return false;
    }
}

function getAttendanceCount($employee_id, $current_date, $next_date, $shift)
{
    $start_time = date('Y-m-d', strtotime($current_date)) . ' ' . $shift->start_time;
    $end_time = date("Y-m-d", strtotime($next_date)) . ' ' . $shift->end_time;

    $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
    $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));
    return AttendanceSummary::where('user_id', $employee_id)->whereBetween('in_date', [$start, $end])->first();
}

function getEmployeesAttendanceCount($employees, $current_date, $next_date)
{
    $data = [];

    $attendanceSummaries = AttendanceSummary::whereIn('user_id', $employees)
        ->whereBetween('in_date', [$current_date, $next_date])
        ->get();

    $lateInCount = 0;
    $halfDayCount = 0;

    foreach ($attendanceSummaries as $attendanceSummary) {
        if ($attendanceSummary->attendance_type === 'lateIn') {
            $lateInCount++;
        } elseif ($attendanceSummary->attendance_type === 'firsthalf' || $attendanceSummary->attendance_type === 'lasthalf') {
            $halfDayCount++;
        }
    }

    $data['total_late_in'] = $lateInCount;
    $data['total_half_days'] = $halfDayCount;
    $data['total_absent'] = count($employees) - count($attendanceSummaries);

    return $data;
}

// function getEmployeesAttendanceCount($employees, $current_date)
// {
//     $data = [];
//     $attendanceSummaries = [];
//     $absent = [];
//     foreach($employees as $employee){
//         $shift = '';
//         if(!empty($employee->userWorkingShift)){
//             $shift = $employee->userWorkingShift->workShift;
//         }else{
//             $shift = defaultShift();
//         }
//         if(!empty($shift)){
//             $end_time = date("Y-m-d", strtotime($current_date)).' '.$shift->end_time;
//             $carbonDateTime = \Carbon\Carbon::parse($end_time);
//             if ($carbonDateTime->hour < 12) {
//                 $next_date = date("Y-m-d", strtotime('+1 day '.$current_date));
//             } else {
//                 $next_date = date('Y-m-d', strtotime($end_time));
//             }
//         }

//         $employeeSummary = AttendanceSummary::where('user_id', $employee)
//         ->whereBetween('in_date', [$current_date, $next_date])
//         ->first();

//         if(!empty($employeeSummary)){
//             $attendanceSummaries[] = $employeeSummary;
//         }else{
//             $check_date = Carbon::parse($current_date);
//             $start_at = Carbon::parse($current_date);
//             $end_at = Carbon::parse($next_date);

//             if ($check_date->between($start_at, $end_at)) {
//                 $absent[] = $employee;
//             }
//         }
//     }

//     $lateInCount = 0;
//     $halfDayCount = 0;

//     foreach ($attendanceSummaries as $attendanceSummary) {
//         if ($attendanceSummary->attendance_type === 'lateIn') {
//             $lateInCount++;
//         } elseif ($attendanceSummary->attendance_type === 'firsthalf' || $attendanceSummary->attendance_type === 'lasthalf') {
//             $halfDayCount++;
//         }
//     }

//     $data['total_late_in'] = $lateInCount;
//     $data['total_half_days'] = $halfDayCount;
//     $data['total_absent'] = count($absent);

//     return $data;
// }

function checkAttendanceByID($attendance_id)
{
    $att = Attendance::where('id', $attendance_id)->first();
    $data = '';
    if (!empty($att)) {
        $data = $att;
    }

    return $data;
}

function checkRocketFlareUser()
{
    $array = [
        'developer@cyberonix.org',
    ];

    if (in_array(Auth::user()->email, $array)) {
        // rocket flare user matched
        return 1;
    } else {
        // cyberonix user matched
        return 2;
    }
}

function userLeaveApplied($user_id, $punch_date)
{
    // $user_leave =  UserLeave::where('is_applied', 1)->where('user_id', $user_id)->first();
    $user_leave =  UserLeave::where('user_id', $user_id)
        ->where('is_applied', 1)
        ->whereDate('start_at', '<=', $punch_date)
        ->whereDate('end_at', '>=', $punch_date)
        ->first();

    if (!empty($user_leave)) {
        $check_date = Carbon::parse($punch_date);
        $start_at = Carbon::parse($user_leave->start_at);
        $end_at = Carbon::parse($user_leave->end_at);

        if ($check_date->between($start_at, $end_at)) {
            return $user_leave;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function checkFileType($name)
{
    $explode = explode(".", $name);
    $explode = last($explode);
    if ($explode == "png" || $explode == "jpg" || $explode == "jpeg" || $explode == "bmp" || $explode == "gif") {
        return "image";
    } else if ($explode == "doc" || $explode == "docx") {
        return "word";
    } else if ($explode == "pdf") {
        return "pdf";
    } else if ($explode == "xls") {
        return "xls";
    }
}




function currencyList()
{
    return Currency::all();
    // return [
    //     ['code' => 'USD', 'name' => 'United States Dollar ($)'],
    //     ['code' => 'PKR', 'name' => 'Pakistani Rupees (Rs)'],
    // ];
}

function getAppMode()
{

    return config("app.mode");
}

function conversionApi($baseCurrency = null)
{
    try {
        $error = [];
        $token = config("currency.token");
        if (!empty($token)) {
            $action = "https://v6.exchangerate-api.com/v6/" . $token . "/latest/" . $baseCurrency;
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json', // Adjust the content type as needed
            ];
            $data = [];
            $response = $client->request('GET', $action, [
                'headers' => $headers,
                'json' => $data,
            ]);
            $status = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $response = json_decode($body, true);
            return ['success' => true, 'data' => $response];
        } else {
            return apiResponse(false, "API TOKEN NOT FOUND!", null, 200);
        }
    } catch (\GuzzleHttp\Exception\BadResponseException $e) {
        $error = json_decode($e->getResponse()->getBody());
        return apiResponse(false, $error, null, 200);
    } catch (\Exception $e) {
        $error = $e->getMessage();
        return apiResponse(false, $error, null, 200);
    } catch (ClientException  $e) {
        $error = $e->getResponse();
        $response = json_decode($error->getBody()->getContents());
        if (isset($response->message) && !empty($response->message)) {
            $error = $response->message;
            return apiResponse(false, $error, null, 200);
        }
        return $error;
    }
}

function currencyConverter($baseCurrency = null, $conversionCurrency = null, $amount = null)
{
    $response = conversionApi($baseCurrency);
    if (!empty($response) && $response['success'] == true) {

        $data['from_currency'] =  $response['data']['base_code'] ?? null;
        $data['to_currency'] = $conversionCurrency ?? null;
        $data['conversionRate'] = $response['data']['conversion_rates'][$conversionCurrency] ?? null;
        if (isset($amount) && !empty($amount)) {
            $data['givenAmount'] = (float) $amount;
            $data['convertedAmount'] = $data['givenAmount'] * $data['conversionRate'];
            $data['convertedAmountWithSymbol'] = number_format($data['convertedAmount'], 2) . " " . $conversionCurrency;
        }
        $message = $baseCurrency . " to " . $conversionCurrency;
        return apiResponse(true, $message, $data, 200);
    } else {
        return apiResponse(false, "Failed to get response from api", null, 200);
    }
}
function checkSalaryInUSD($salaryHistory)
{
    if (!empty($salaryHistory->currency_code) && $salaryHistory->currency_code == "USD") {
        return true;
    } else {
        return false;
    }
}

function apiResponse($success, $message, $data, $code)
{
    if (!empty($data)) {

        return response()->json(['success' => $success, 'message' => $message, 'data' => $data], $code);
    }
    return response()->json(['success' => $success, 'message' => $message], $code);
}

function categoryList()
{
    return InventoryCategory::where("status", 1)->get();
}

function generateAssetUID($name)
{
    $uid  = "";
    $uid .= config("project.initial");
    $name =  explode(' ', trim($name));
    $name = substr($name[0] ?? '-', 0, 3);
    $uid .= strtoupper($name);
    $latest = AssetDetail::orderby("id", "desc")->first();
    if (!empty($latest)) {
        $increment =  $latest->id + 1;
        $uid .= "-00" .  $increment;
    } else {
        $uid .= "-001";
    }
    return $uid;
}
function getUserName($user)
{
    $name = "";
    if (!empty($user)) {
        if (!empty($user->first_name)) {
            $name = $user->first_name;
        }
        if (!empty($user->last_name)) {
            $name  = $name . " " . $user->last_name;
        }
    }
    return $name ?? null;
}

function getUser($user_id = null)
{

    if (isset($user_id) && !empty($user_id)) {
        $user_id = $user_id;
    } else {
        $user_id = Auth::user()->id;
    }
    $user = User::with("profile")->where('id', $user_id)->where('status', 1)->with('roles')->first();
    if (!empty($user)) {
        return $user;
    }
}

function getAuthorizeUserName($user_id)
{
    $user = User::where('id', $user_id)->first();
    if ($user) {
        return $user->first_name . ' ' . $user->last_name;
    }
}

function formatDate($date)
{
    $format = Carbon::parse($date);
    $format = $format->format('M d,Y');
    return $format;
}
function formatDateTime($timeStamp)
{
    $timestamp = Carbon::parse($timeStamp)->format("M d,Y / h:i A");
    return $timestamp;
}
function formatTime($timeStamp)
{
    $timestamp = Carbon::parse($timeStamp)->format("h:i A");
    return $timestamp;
}

function resize($image = null, $array = null)
{

    if (!isset($array) || empty($array)) {
        $array = ['w' => 256, 'h' => 256];
    }

    if (config("app.mode") == "live") {
        $basePath = "://cbnslgndba.cloudimg.io/";
        $make_path = "";
        if (isset($image) && !empty($image)) {
            $image = explode("://", $image);
            $first = reset($image);
            $last = end($image);
            $make_path = $first . $basePath . $last;

            if (isset($array) && !empty($array)) {
                $make_path = $first . $basePath . $last . "?" . http_build_query($array);
            }
        }
        return $make_path;
    } else {
        return $image;
    }
}
function userWithHtml($user)
{
    $resizeImage = resize(asset('public/admin/assets/img/avatars') . '/' .  $user->profile->profile, [
        "w" => 256,
        "h" => 256,
    ]);
    if (isset($user->profile->profile) && !empty($user->profile->profile)) {
        $image = '<img src="' . $resizeImage . '" alt="Avatar" class="rounded-circle img-avatar">';
    } else {
        $image = '<img src="' . asset('public/admin/default.png') .  '" alt="Avatar" class="rounded-circle img-avatar">';
    }
    $html = "";
    $html .= '<div class="d-flex justify-content-start align-items-center user-name"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">';
    $html .= $image ?? null;
    $html .= '</div></div><div class="d-flex flex-column">';
    $html .= '<a href="' . route('employees.show', $user->slug) . '" class="text-body text-truncate">';
    $html .= '<span class="fw-semibold"> ' . getUserName($user) . '  (' . $user->profile->employment_id  . ')</span>';
    $html .= '</a><small class="emp_post text-truncate text-muted">';
    $html .= !empty($user->jobHistory->designation->title) ? $user->jobHistory->designation->title : "-";
    $html .= '</small></div></div>';

    return $html;
}

function checkassignedAssets($asset)
{
    $assetDetail = AssetDetail::where("asset_id", $asset->id)->where("assignee", '!=', null)->count();
    return $assetDetail;
}


function updateAssetHistory($asset_id = null, $quantity = null, $type = null, $msg = null)
{

    $saveHistory = AssetHistory::create([
        "created_by" => Auth::user()->id ?? '',
        "asset_id" => $asset_id,
        "quantity" => $quantity,
        "type" => $type, //deduction
        "remarks" => $msg,
    ]);
}


function generateOTP()
{

    if (isset($user_id) && !empty($user_id)) {
        $user_id = $user_id;
    } else {
        $user_id = Auth::user()->id;
    }
    $otp = random_int(100000, 999999);
    $user = User::where('id', Auth::user()->id)->first();
    if (isset($user) && !empty($user)) {
        $old_otps = Otp::where('user_id', $user->id)->where('status', 0)->get();
        if (!empty($old_otps) && count($old_otps) > 0) {
            foreach ($old_otps as $value) {
                $value->update([
                    'status' => 1
                ]);
            }
        }

        $store = Otp::create([
            'user_id' => $user->id ?? null,
            'otp'     => $otp ?? null,
            'status'  => 0,
        ]);

        if (isset($store->id)) {
            $sendOtpResult = sendOtpOnSms($store, "+923113193651");
            if (isset($sendOtpResult) && !empty($sendOtpResult) && $sendOtpResult['success'] == true) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => $sendOtpResult['message']];
            }
        } else {
            return ['success' => false, 'message' => 'Otp not genrated.'];
        }
    } else {
        return ['success' => false, 'message' => 'In-valid user login.'];
    }
    return ['success' => false, 'message' => 'Some thingwent wrong.'];
}

function createUserVerification($user_id)
{
    if (isset($user_id) && !empty($user_id)) {
        // $old_user_verification = UserVerification::where('user_id', $user_id)->where('status', 1)->get();
        // if(isset($old_user_verification) && count($old_user_verification) > 0) {
        //     foreach($old_user_verification as $value) {
        //         $value->update([
        //             'status' => 0
        //         ]);
        //     }
        // }
        $user_verification_key = Str::random(60) . $user_id . time();
        $store = UserVerification::create([
            'user_id'               => $user_id,
            'verified_at'           => Carbon::now()->toDateString(),
            'browser'               => request()->userAgent(),
            'user_verification_key' => $user_verification_key ?? null,
            'status' => 1,

        ]);
        if (isset($store) && !empty($store)) {
            return ['success' => true, 'user_verification_key' => $store->user_verification_key];
        } else {
            return ['success' => false];
        }
    } else {
        return ['success' => false];
    }
}

function getAllDepartments()
{
    if (getUser()->hasRole('Admin')) {
        return Department::where("status", 1)->get();
    }
    if (getUser()->hasRole("Department Manager")) {
        return Department::where("manager_id", getUser()->id)->where("status", 1)->get();
    }
}
function getDepartmentUsers($department)
{
    if (empty($department)) {
        $department = getMyDepartment();
    }
    return DepartmentUser::where("department_id", $department->id)->whereNull("end_date")->whereHas("employee", function ($query) {
        $query->where("status", 1);
    })->get();
}
function getMyDepartment($user = null)
{
    if (empty($user)) {
        $user = getUser();
    }
    return Department::where("manager_id", getUser()->id)->where("status", 1)->first();
}
function getWorkShifts()
{
    return WorkShift::where("status", 1)->get();
}
function getDepartmentFromID($id)
{

    return Department::where("id", $id)->where("status", 1)->first();
}
function getShiftFromId($shift_id)
{
    return WorkShift::where("id", $shift_id)->where('status', 1)->first();
}
function getShiftUsers($shift)
{
    return WorkingShiftUser::where("working_shift_id", $shift->id)->whereNull("end_date")->pluck("user_id")->toArray();
}
function getEmploymentStatus()
{
    return EmploymentStatus::get();
}

function getLeaveStatuses()
{
    return [
        (object) ["id" => 1, "name" => "Approved"],
        (object) ["id" => 2, "name" => "Disapproved"],
        (object) ["id" => 0, "name" => "Pending"],
    ];
}



function getDiscrepencyStatuses()
{
    return [
        (object) ["id" => 1, "name" => "Approved"],
        (object) ["id" => 2, "name" => "Disapproved"],
        (object) ["id" => 0, "name" => "Pending"],
    ];
}


function getDiscrepencyStatus($record = null)
{
    if ($record->status == 1) {
        $name = "Approved";
        $class = "success";
    } elseif ($record->status == 2) {
        $name = "Disapproved";
        $class = "danger";
    } elseif ($record->status == 0) {
        $name = "Pending";
        $class = "warning";
    }
    return (object) [
        "name" => $name,
        "class" => $class,
    ];
}
function getLeaveStatus($record = null)
{
    if ($record->status == 1) {
        $name = "Approved";
        $class = "success";
    } elseif ($record->status == 2) {
        $name = "Disapproved";
        $class = "danger";
    } elseif ($record->status == 0) {
        $name = "Pending";
        $class = "warning";
    }
    return (object) [
        "name" => $name,
        "class" => $class,
    ];
}

function sendOtpOnSms($otp = null, $number = null)
{
    try {
        if (isset($otp) && !empty($otp) && isset($number) && !empty($number)) {
            $twilioMessage = "Hi there! Just sharing a quick verification code to ensure it's really you, Code: [$otp->otp]. Thanks for keeping things secure!";

            $twilio = new Twilio\Rest\Client(config("twilio.twilio_sid"), config("twilio.twilio_token"));
            $message = $twilio->messages
                ->create(
                    $number, // to
                    [
                        "body" => $twilioMessage,
                        "from" => config("twilio.twilio_from")
                    ]
                );
            Log::info("MESSAGE FOR SMS --- " . json_encode($twilioMessage));
            if (isset($message) && isset($message->sid)) {
                $array = [
                    "body"  => $message->body ?? null,
                    "numSegments"  => $message->numSegments ?? null,
                    "direction"  => $message->direction ?? null,
                    "from"  => $message->from ?? null,
                    "to"  => $message->to ?? null,
                    "status"  => $message->status ?? null,
                    "messagingServiceSid"  => $message->messagingServiceSid ?? null,
                ];

                $createOtpResponse = OtpResponse::create([
                    'otp_id' => $otp->id ?? null,
                    'user_id' => $otp->user_id ?? null,
                    'response' => json_encode($array),
                    'status' => $message->status ?? null
                ]);

                if (isset($createOtpResponse) && !empty($createOtpResponse)) {
                    return ['success' => true];
                } else {
                    return ['success' => false, "message" => "something went wrong."];
                }
            }
        } else {
            return ['success' => false, 'message' => "Please update your phone number on your profile for sending verification otp."];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}



function getSecretKeyForStorage($user_id = null)
{
    if (isset($user_id) && !empty($user_id)) {
        $user_id = $user_id;
    } else {
        $user_id = Auth::user()->id ?? null;
    }

    $key = "";
    $key .= strtolower(config("project.initial"));
    $key .= 'skey-';
    $key .= $user_id ?? null;
    return $key;
}

function uploadSingleFileWithoutPath($file = null, $folder_name = null, $prefix = null)
{
    $folder = public_path($folder_name);

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }
    $name = $prefix . "-" .  Str::random(6) . time() . "." . $file->getClientOriginalExtension();
    // $filename = $folder_name . "/" . $name;
    $file->move($folder, $name);
    return $name;
}

function getModelName($model = null, $slugExtract = null)
{
    $data = "";
    if (isset($model)) {
        $explode = explode("-", $model);
        if (isset($slugExtract) && $slugExtract == true) {
            $data = ucfirst(implode(" ", $explode));
        } else {
            if (isset($explode) && count($explode) > 1) {
                foreach ($explode as $single) {
                    $data .= ucfirst(Str::singular($single));
                }
            } else {
                $data = ucfirst(Str::singular($explode[0]));
            }
        }
    }

    // if(isset($model_name) && isset($id)) {
    //     $newModel = 'App\\Models\\' . $model_name;
    //     $data = $newModel::where('id', $id)->first();
    // }

    return $data;
}

function getMonthDaysForSalary($year = null, $month = null)
{
    if (empty($year)) {
        $year = Carbon::now()->year;
    }
    if (empty($month)) {
        $month = Carbon::now()->month - 1;
    } else {
        $month  = $month - 1;
    }
    $firstDayOfMonth = Carbon::createFromDate($year, $month, 26); //26 to till 25 of next month in between days of these two dates.
    $lastDayOfMonth = $firstDayOfMonth->copy()->addMonth()->subDay();

    $totalDays = $firstDayOfMonth->diffInDays($lastDayOfMonth) + 1;
    // Initialize an array to store the result
    $filteredDays = [];
    // Loop through each day between the start and end dates
    $currentDay = $firstDayOfMonth->copy();
    while ($currentDay <= $lastDayOfMonth) {
        // Check if the current day is not a weekend (Saturday or Sunday)
        if (!$currentDay->isWeekend()) {
            // Add the current day to the result array
            $filteredDays[] = $currentDay->toDateString();
        }
        // Move to the next day
        $currentDay->addDay();
    }
    $totalDaysWithoutWeekends = count($filteredDays);
    $array = (object) [
        "first_date" => $firstDayOfMonth->toDateString(),
        "last_date" => $lastDayOfMonth->toDateString(),
        "fifth_date" => getFifthDateOfMonth($year, $month + 1),
        "total_days" => $totalDays,
        "total_days_without_weekends" => $totalDaysWithoutWeekends,
        "month" => $month + 1,
        "year" => $year,
        "monthYear" => $month . "/" . $year,
    ];
    return $array;
}
function getFifthDateOfMonth($year, $month)
{
    // Set the month and day to 1 to get the first day of the month
    $firstDayOfMonth = Carbon::create($year, $month, 1);

    // Add 4 days to get the 5th day of the month
    $fifthDate = $firstDayOfMonth->addDays(4);

    return $fifthDate->toDateString();
}

function getUserLeave($user, $month, $year, $behavior_type, $status)
{
    $leaves = UserLeave::where('user_id', $user->id)
        ->where('status', $status)
        ->whereMonth('start_at', $month)
        ->whereYear('start_at', $year)
        ->where('behavior_type', $behavior_type)
        ->get();

    return $leaves;
}


function getAttendanceSummaryOfDate($date, $user_id)
{

    $get = AttendanceSummary::whereDate("in_date", $date)->where("user_id", $user_id)->first();
    if (!empty($get)) {
        return $get;
    } else {
        return null;
    }
}

function getUserJoiningDate($user_id)
{
    $get = UserEmploymentStatus::where("user_id", $user_id)->whereNotNull("start_date")->orderby("id", "asc")->first();
    return $get->start_date;
}


function checkSalarySlipGenerationDate($data)
{
    if ($data->first_date <  Carbon::now()->toDateString()  && $data->fifth_date >  Carbon::now()->toDateString()) {
        return true;
    } else {
        return false;
    }
}
function getTotalWorkingDaysAvailableInAMonth($from_date, $to_date)
{
    // Set your start and end dates
    $startDate = Carbon::parse($from_date);
    $endDate = Carbon::parse($to_date)->addDay();


    // Initialize an array to store the result
    $filteredDays = [];

    // Loop through each day between the start and end dates
    while ($startDate = $endDate) {
        // Check if the current day is not a weekend (Saturday or Sunday)
        if (!$startDate->isWeekend()) {
            // Add the current day to the result array
            $filteredDays[] = $startDate->toDateString();
        }
        // Move to the next day
        $startDate->addDay();
    }
    return $filteredDays;
    // $filteredDays now contains all days except weekends between the start and end dates
}

function attendanceCount($from_date, $to_date, $month, $year,  $user,  $type)
{

    // $dateList = dateListOfMonth($from_date, $to_date);
    try {
        $shift = $user->userWorkingShift->workShift;
        $from_date = Carbon::parse($from_date)->startOfDay()->toDateTimeString();
        $to_date = Carbon::parse($to_date)->endOfDay()->toDateTimeString();
        $get = AttendanceSummary::whereDate("in_date", ">", $from_date)->whereDate("in_date", "<", $to_date)->where("user_id", $user->id);
        // dd(count($get->get()) , count($dateList));
        if (!empty($type)) {
            if ($type == "halfday") {
                if (!empty($shift)) {
                    $from_time = Carbon::parse($shift->start_time);
                    $end_time = Carbon::parse($shift->end_time);
                    $firstHalfTime =  $from_time->copy()->addHours(2);
                    $lastHalfTime =  $end_time->copy()->subHours(2);
                    $halfDayCount = 0;
                    foreach ($get->get() as $i => $v) {
                        $startingTime = Carbon::parse($v->in_date);
                        $finishTime = Carbon::parse($v->out_date);
                        $totalDuration = $finishTime->diffInHours($startingTime);
                        if ($totalDuration <= 7) {
                            $halfDayCount = (int)  $halfDayCount  + 1;
                        }
                    }
                    Log::info("HALF DAYS . " . json_encode($halfDayCount));
                }
            }

            //     if ($type == "late") {
            //         if (!empty($shift)) {
            //             $dateArray = [];
            //             // $end_time = Carbon::parse($shift->end_time);
            //             // $earlyOut =  $end_time->copy()->subMinutes(15);
            //             // Log::info("First Half TIme : ". json_encode($firstHalfTime->toDateTimeString()) . "  SHIFT : " . json_encode($shift->start_time) . " -- " . json_encode($shift->end_time));
            //             // Log::info("Last Half TIME : " . json_encode($lastHalfTime->toDateTimeString()) . "  SHIFT : " . json_encode($shift->start_time) . " -- " . json_encode($shift->end_time));
            //             $lateInCount = 0;
            //             // foreach ($get->get() as $i => $v) {

            //             //     // $shift_time = Carbon::parse($shift->start_time);
            //             //     // $shift_time =  $shift_time->addMinutes(16)->toDateTimeString();
            //             //     // $lateCount = checkAttendanceStatus($v,   $dateList, $shift , $from_date , $to_date);
            //             //     // $shiftDate = Carbon::parse($v)->startOfDay();
            //             //     // $shift_time = date("Y-m-d H:i:s", strtotime(Carbon::parse($shiftDate)->toDateString() . ' ' . $shift->start_time));
            //             //     // $dateArray[] = $shift_time . "  " . $v->hasUser->email;
            //             //     // $shift_time = Carbon::parse($shift_time);
            //             //     // $add15Minutes = $shift_time->copy()->addMinutes(16);
            //             //     // $checkInTime = Carbon::parse($v->in_date);
            //             //     // dd($checkInTime <= $add15Minutes, $checkInTime  > $add15Minutes, $add15Minutes, $checkInTime);

            //             //     // Log::info("Shift Time : " . json_encode($shift_time));
            //             //     // Log::info("Checkin  Time : " . json_encode($checkInTime));
            //             //     // dd($checkInTime  > $shift_time );
            //             //     // Log::info("DETAILS : " . json_encode($checkInTime > $add15Minutes) . " CHECKIN TIME " . json_encode($checkInTime) . " SHIFT TIME " . json_encode($shift_time));
            //             //     // if ($checkInTime > $add15Minutes) {
            //             //     //     $lateInCount  = (int) +$lateInCount;
            //             //     // }
            //             // }
            //             // dd($lateCount);
            //             // Log::info("Late In Found : " . json_encode($dateArray));

            //             foreach ($dateList as $dateIndex => $dateValue) {
            //                 if (!empty($first)) {
            //                     $dateArray[] = $first->in_date . " - " . Carbon::parse($dateValue)->toDateTimeString();
            //                 }
            //             }
            //         }
            //     }
            //     dd($dateArray);

            //     $get->where("attendance_type", $type);
        }

        return  $get->count();
    } catch (Exception $e) {
        Log::info("ERROR : " . $e->getMessage());
        return (object) [];
    }
}
function getUsersList()
{
    if (getUser()->hasRole("Department Manager")) {
        $departUsers = getDepartmentUsers(getMyDepartment());
        return User::whereIn("id", $departUsers->pluck("user_id")->toArray())->where("status", 1)->get();
    } else {
        return User::where("status", 1)->where('is_employee', 1)->get();
    }
}

function dateListOfMonth($from, $to_date)
{

    $startDate = Carbon::parse($from);
    $endDate = Carbon::parse($to_date);

    // Generate a date range between $startDate and $endDate
    $dateRange = CarbonPeriod::create($startDate, $endDate);

    // Iterate through the date range and exclude weekends
    $dateList = [];
    foreach ($dateRange as $date) {
        if ($date->isWeekday()) {
            $dateList[] = $date->toDateString();
        }
    }

    return $dateList;
}

function getModel($model = null, $model_name_prefix = false)
{
    $model_name = "";
    if (isset($model)) {
        $explode = explode("\\", $model);
        $model_name = last($explode);
        if ($model_name_prefix == true) {
            switch ($model_name) {
                case 'User':
                    $model_name = "Employee";
                    break;
                case 'UserContact':
                    $model_name = "My Profile";
                    break;
                case 'VehicleOwner':
                    $model_name = "Vehicle Venders";
                    break;
                case 'VehicleInspection':
                    $model_name = "Inspection List";
                    break;
                case 'DocumentAttachments':
                    $model_name = "Document";
                    break;
                default:
                    $model_name = $model_name;
                    break;
            }
        }
    }
    return  $model_name;
}

function getLogTypeClass($record = null)
{
    if ($record->type == 1) {
        $name = "Delete";
        $class = "danger";
    } elseif ($record->type == 2) {
        $name = "Restore";
        $class = "warning";
    } elseif ($record->type == 3) {
        $name = "Create";
        $class = "success";
    } elseif ($record->type == 4) {
        $name = "Update";
        $class = "info";
    }


    return (object) [
        "name" => $name,
        "class" => $class,
    ];
}


function getTicketStatuses()
{
    return [
        (object) ["id" => 0, "name" => "Pending"],
        (object) ["id" => 1, "name" => "Approved By RA"],
        (object) ["id" => 2, "name" => "Approved By Admin"],
        (object) ["id" => 3, "name" => "Completed"],
    ];
}

function getTicketStatus($record = null)
{

    if ($record->status == 1) {

        $name = "Approved By RA";
        $class = "badge bg-label-info";
    } elseif ($record->status == 2) {
        $name = "Approved By Admin";
        $class = "badge bg-label-info";
    } elseif ($record->status == 3) {
        $name = "Completed";
        $class = "badge bg-label-success";
    } elseif ($record->status == 0) {
        $name = "Pending";
        $class = "badge bg-label-warning";
    }
    return (object) [
        "name" => $name,
        "class" => $class,
    ];
}

function getLogType()
{
    return [
        (object) ["id" => 3, "name" => "Create"],
        (object) ["id" => 4, "name" => "Update"],
        (object) ["id" => 1, "name" => "Delete"],
        (object) ["id" => 2, "name" => "Restore"],
    ];
}

function getLogModel()
{
    $model = SystemLog::groupBy('model_name')->get();
    return $model;
}

function getPreEmployeeStatus()
{
    return [
        (object) ["id" => 0, "name" => "Pending"],
        (object) ["id" => 1, "name" => "Approved"],
        (object) ["id" => 2, "name" => "Rejected"],
    ];
}

function getManagers($manager_id = null)
{
    $managers = User::role('Department Manager')->where('status', 1);
    if (!empty(Auth::user()->roles) && !in_array("Admin", Auth::user()->roles->pluck("name")->toArray()) && isset($manager_id) && !empty($manager_id)) {
        $manager_id = isset($manager_id) && !empty($manager_id) ? $manager_id : null;
        $managers = $managers->where('id', $manager_id);
    }
    $managers = $managers->get();
    return $managers;
}

function checkOwnRa()
{
    $status = false;
    $user = getUser();
    if (!empty($user->departmentBridge) && !empty($user->departmentBridge->department)) {
        if (!empty($user->departmentBridge->department->manager_id) && $user->departmentBridge->department->manager_id == $user->id) {
            $status = true;
        }
    }
    return $status;
}


function getCurrencyCodeForSalary($user)
{

    if (!empty($user->salaryHistory) && !empty($user->salaryHistory->getCurrency)) {
        return $user->salaryHistory->getCurrency->symbol;
    } else {
        return "Rs.";
    }
}

function getModelTitleName($object)
{
    $model_name = isset($object->model_name) && !empty($object->model_name) ? getModel($object->model_name) : null;

    $title = "";
    if (isset($model_name) && !empty($model_name)) {
        $model = "App\\Models\\" . $model_name;
        $data = $model::where('id', $object->model_id)->withTrashed()->first();

        if (isset($data) && !empty($data)) {
            if ($model_name == "Announcement" || $model_name == "Designation" || $model_name == "LetterTemplate" || $model_name == "Position") {
                $title = isset($data->title) && !empty($data->title) ? getWordInitial(Str::title($data->title)) : "-";
            }
            if ($model_name == "AttendanceAdjustment" || $model_name == "WFHEmployee" || $model_name == "Insurance" || $model_name == "UserLeave") {
                $title = isset($data->hasEmployee) && !empty($data->hasEmployee) ? userEmployeeWithHtml($data->hasEmployee) : "-";
            }
            if ($model_name == "AuthorizeEmail") {
                $title = isset($data->email_title) && !empty($data->email_title) ? getWordInitial(Str::title($data->email_title)) : "-";
            }
            if ($model_name == "Department" || $model_name == "EmploymentStatus" || $model_name == "InventoryCategory" || $model_name == "LeaveType" || $model_name == "VehicleOwner" || $model_name == "WorkShift" || $model_name == "Asset") {
                $title = isset($data->name) && !empty($data->name) ? getWordInitial(Str::title($data->name))   : "-";
            }

            if ($model_name == "User") {
                $title = isset($data->id) && !empty($data->id) ? userEmployeeWithHtml($data) : "-";
            }
            if ($model_name == "EmployeeLetter") {
                $title = isset($data->hasEmployee) && !empty($data->hasEmployee) ? userEmployeeWithHtml($data->hasEmployee) : "-";
                $title .= '<small class="emp_post text-truncate text-muted" style="float: right;">';
                $title .= isset($data->title) && !empty($data->title) ? Str::title(str_replace('_', ' ', $data->title)) : "";
                $title .= '</small>';
            }
            if ($model_name == "IpManagement") {
                $title = isset($data->ip_address) && !empty($data->ip_address) ? $data->ip_address : "-";
            }

            if ($model_name == "ProfileCoverImage") {
                $image_path = isset($data->image) && !empty($data->image) && file_exists(public_path('admin/assets/img/pages') . '/' . $data->image) ? asset('public/admin/assets/img/pages') . '/' . $data->image : asset('public/admin/default.png');
                $title = '<img src="' . $image_path . '" style="width:100px; height:40px" class="rounded" alt="">';
            }

            if ($model_name == "Resignation") {
                $title = isset($data->reason_for_resignation) && !empty($data->reason_for_resignation) ? $data->reason_for_resignation : "-";
            }

            if ($model_name == "StationaryCategory") {
                $title = isset($data->stationary_category) && !empty($data->stationary_category) ? getWordInitial($data->stationary_category) : "-";
            }

            if ($model_name == "Stationary") {
                $title = isset($data->stationartCategory->stationary_category) && !empty($data->stationartCategory->stationary_category) ? getWordInitial($data->stationartCategory->stationary_category) : "-";
            }

            if ($model_name == "UserContact") {
                $title = isset($data->key) && !empty($data->key) ? getWordInitial(Str::title(str_replace("_", " ", $data->key))) : "-";
            }

            if ($model_name == "VehicleAllowance" || $model_name == "Insurance") {
                $title = isset($data->hasUser) && !empty($data->hasUser) ? userEmployeeWithHtml($data->hasUser) : "-";
                $title .= '<small class="emp_post text-truncate text-muted" style="float: right;">';
                $title .= isset($data->vehicle) && !empty($data->vehicle) ? Str::title($data->vehicle) : "";
                $title .= '</small>';
            }

            if ($model_name == "Vehicle") {
                $html = view('admin.fleet.vehicles.vehicle_profile', ['model' => $data])->render();
                $title = isset($html) && !empty($html) ? $html : "-";
            }

            if ($model_name == "VehicleInspection" || $model_name == "VehicleUser") {
                $title = isset($data->hasUser) && !empty($data->hasUser) ? userEmployeeWithHtml($data->hasUser) : "-";
                $title .= '<small class="emp_post text-truncate text-muted" style="float: right;">';
                $title .= isset($data->hasVehicle->name) && !empty($data->hasVehicle->name) ? Str::title($data->hasVehicle->name) : "";
                $title .= '</small>';
            }

            if ($model_name == "VehicleRent") {
                $title  = view('admin.fleet.vehicles.vehicle_profile', ['model' => $data->hasVehicle])->render();
                $title .= '<small class="emp_post text-truncate text-muted" style="float: right;"> Rent (PKR) : ';
                $title .= isset($data->rent) && !empty($data->rent) ? Str::title($data->rent) : "";
                $title .= '</small>';
            }

            if ($model_name == "PreEmployee") {
                $html = view('admin.pre_employees.pre_employee-profile', ['employee' => $data])->render();
                $title = isset($html) && !empty($html) ? $html : "-";
                if (isset($data) && !empty($data) && $data->form_type == 2) {
                    $title .= '<small class="emp_post text-truncate text-muted" style="float: right;"> (Office Boy)';
                    $title .= '</small>';
                }
            }

            if ($model_name == "Ticket") {
                $title = isset($data->hasEmployee) && !empty($data->hasEmployee) ? userEmployeeWithHtml($data->hasEmployee) : "-";
                $title .= '<small class="emp_post text-truncate text-muted" style="float: right;">';
                $title .= isset($data->hasReason->name) && !empty($data->hasReason->name) ? Str::title(str_replace('_', ' ', $data->hasReason->name)) : "";
                $title .= '</small>';
            }

            // if($model_name == "Document") {
            //     $title = "";
            //     $title .= '<div class="dropdown-item show" tabindex="0" aria-controls="DataTables_Table_0" type="button"
            //     data-bs-toggle="modal" data-bs-target="#details-modal" data-toggle="tooltip" data-placement="top"
            //     title="View Documents" data-show-url="'.route('logs.viewDocuments', $data->id).'"  style="cursor: pointer;">';
            //     $title .= '<img src="' . asset('public/admin/assets/img/fileicon.png') . '" style="width:30px" alt="">';
            //     $title .= '<span class="ms-2 d-inline-block fw-bold" style="font-size:13px;">';
            //     $title .= !empty($data->hasAttachmentsWithTrashed)  ? $data->hasAttachmentsWithTrashed->count() : 0;
            //     $title .= '</span>';
            //     $title .= '</div>';

            //     return $title;
            // }

            if ($model_name == "DocumentAttachments") {
                $title = "";
                if (checkFileType($data->attachment) == 'word') {
                    $title .= '<a href="' . asset('public/admin/assets/document_attachments/' . $data->attachment ?? '') . '" target="_blank">';
                    $title .= '<img src="' . asset('public/admin/assets/img/doc.png') . '" style="width:50px" alt="">';
                    $title .= '</a>';
                } elseif (checkFileType($data->attachment) == 'pdf') {
                    $title .= '<a href="' . asset('public/admin/assets/document_attachments/' . $data->attachment ?? '') . '" target="_blank">';
                    $title .= '<img src="' . asset('public/admin/assets/img/pdf.png') . '" style="width:50px" alt="">';
                    $title .= '</a>';
                } elseif (checkFileType($data->attachment) == 'xls') {
                    $title .= '<a href="' . asset('public/admin/assets/document_attachments/' . $data->attachment ?? '') . '" target="_blank">';
                    $title .= '<img src="' . asset('public/admin/assets/img/xls.png') . '" style="width:50px" alt="">';
                    $title .= '</a>';
                } elseif (checkFileType($data->attachment) == 'image') {
                    $title .= '<img src="' . asset('public/admin/assets/document_attachments/' . $data->attachment ?? '') . '" style="width:50px" alt="">';
                } else {
                    $title .= '<a href="' . asset('public/admin/assets/document_attachments/' . $data->attachment ?? '') . '" target="_blank">';
                    $title .= '<img src="' . asset('public/admin/assets/img/fileicon.png') . '" style="width:50px" alt="">';
                    $title .= '</a>';
                }
                return $title;
            }
        }
    }

    return $title;
}

function getWordInitial($word)
{
    $wordStr = !empty($word) ? substr($word, 0, 1)  : "-";
    $initial = '<p style="width: 32px;height: 32px;border-radius:100% ;background:#' . random_color() . ';display: flex;align-items: center;justify-content: center;color:white;text-transform: uppercase;font-size: 12px;">' . $wordStr . '</p>';
    $html = "";
    $html .= '<div class="d-flex justify-content-start align-items-center user-name">';
    $html .=     '<div class="avatar-wrapper">';
    $html .=     ' <div class="avatar avatar-sm me-3">';
    $html .=             $initial;
    $html .=     '  </div>';
    $html .=         '</div><div class="d-flex flex-column">';
    $html .=              '<span class="fw-semibold">  ' . $word . '</span>';

    $html .=     '</div>';
    $html .= '</div>';
    return $html;
}

function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}
function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}



function userEmployeeWithHtml($model)
{
    $html = "";
    if (isset($model) && !empty($model)) {
        if (isset($model->profile->profile) && !empty($model->profile->profile)) {
            $resizeImage = resize(asset('public/admin/assets/img/avatars') . '/' .  $model->profile->profile, [
                "w" => 256,
                "h" => 256,
            ]);
            $image = '<img src="' . $resizeImage . '" alt="Avatar" class="rounded-circle img-avatar">';
        } else {
            $image = '<img src="' . asset('public/admin/default.png') .  '" alt="Avatar" class="rounded-circle img-avatar">';
        }

        $html .= '<div class="d-flex justify-content-start align-items-center user-name"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">';
        $html .= $image ?? null;
        $html .= '</div></div><div class="d-flex flex-column">';
        $route = "javascrip:void(0);";
        if (isset($model->slug) && !empty($model->slug)) {
            $route = route('employees.show', $model->slug);
        }
        $html .= '<a href="' . $route . '" class="text-body text-truncate">';
        $userEmployeementId = isset($model->profile->employment_id) && !empty($model->profile->employment_id) ? $model->profile->employment_id : null;
        $html .= '<span class="fw-semibold"> ' . getUserName($model) . '  (' . $userEmployeementId  . ')</span>';
        $html .= '</a><small class="emp_post text-truncate text-muted">';
        $html .= !empty($model->jobHistory->designation->title) ? $model->jobHistory->designation->title : "";
        $html .= '</small>';
        $html .= '<small class="emp_post text-truncate text-muted">';
        $html .= !empty($model->email) ? $model->email : "";
        $html .= '</small></div></div>';
    }

    return $html;
}

function getUpdatedData($oldData = null, $newData = null, $prefixKey = null, $event = 'update', $oldRelationNameArray = null, $newRelationNameArray = null, $seprateColumn = null)
{

    if (isset($newData) && !empty($newData)) {
        $updatedData = $newData->getAttributes() ?? null;
        if (isset($updatedData['updated_at']) && !empty($updatedData['updated_at'])) {
            $remove_array[] = $updatedData['updated_at'] ?? null;
            if ($event !== 'create') {
                $updatedData = array_diff_assoc($updatedData, $oldData);
                $updatedDataArray = array_diff($updatedData, $remove_array);
                if ($prefixKey == 'salary_history' && !empty($oldData['raise_salary']) && !empty($newData->raise_salary) && $oldData['raise_salary'] == $newData->raise_salary) {
                    $oldSalaryArray['raise_salary'] = $oldData['raise_salary'] ?? null;
                    $newSalaryArray['raise_salary'] = $newData['raise_salary'] ?? null;
                    $oldData = array_merge($oldData, $oldSalaryArray);
                    $updatedDataArray = array_merge($updatedDataArray, $newSalaryArray);
                }
                $columnsToExtract = array_keys($updatedDataArray);


                $oldData = Arr::only($oldData, $columnsToExtract);


                if (isset($oldRelationNameArray) && !empty($oldRelationNameArray)) {
                    $oldData = array_merge($oldData, $oldRelationNameArray);
                }

                if (isset($seprateColumn) && !empty($seprateColumn)) {
                    $oldData = array_merge($oldData, $seprateColumn);
                }

                $checkUpdateChanges = !empty($newData->getChanges()) || $event == "both" ? true : false;
                if (!empty($checkUpdateChanges) && isset($newRelationNameArray) && !empty($newRelationNameArray)) {
                    $updatedDataArray = array_merge($updatedDataArray, $newRelationNameArray);
                }

                return ['old' =>  [$prefixKey => $oldData], 'updated' => [$prefixKey => $updatedDataArray]];
            } else {
                $remove_array[] = $updatedData['created_at'] ?? null;
                $updatedDataArray = array_diff($updatedData, $remove_array);
                if (isset($newRelationNameArray) && !empty($newRelationNameArray)) {
                    $updatedDataArray = array_merge($updatedDataArray, $newRelationNameArray);
                }

                if (isset($seprateColumn) && !empty($seprateColumn)) {
                    $updatedDataArray = array_merge($updatedDataArray, $seprateColumn);
                }

                return ['old' =>  [$prefixKey => null], 'updated' => [$prefixKey => $updatedDataArray]];
            }
        }
    }

    return ['old' =>  [$prefixKey => null], 'updated' => [$prefixKey => null]];
}

function userLoggedIn()
{
    $array['model_id'] = getUser()->id;
    $array['model_name'] = "\App\Models\User";
    $array['type'] = "5";
    $array['remarks'] = "User Logged In Successfuly";
    LogActivity::saveLog($array);
}

function saveLogs($updatedDataArrayHirtory = null, $model_name = null, $model_id = null, $type = null, $msg = null, $event_id = null)
{
    $updatedDataArrayHirtoryNew = [];
    if (isset($updatedDataArrayHirtory) && !empty($updatedDataArrayHirtory)) {
        $updatedDataArrayHirtoryNew = array_reduce(
            $updatedDataArrayHirtory,
            function ($carry, $item) {
                return array_merge_recursive($carry, $item);
            },
            []
        );

        $message = "";
        if (!empty($type) && $type == 3) {
            $message = $msg . " has been created";
        } elseif (!empty($type) && $type == 4) {
            $message = $msg . " has been updated";
        }

        if (isset($updatedDataArrayHirtoryNew) && !empty($updatedDataArrayHirtoryNew)) {
            $deleteArray['model_id'] = $model_id;
            $deleteArray['model_name'] = "\App\Models\\" . $model_name;
            $deleteArray['type'] = $type;
            $deleteArray['event_id'] = isset($event_id) && !empty($event_id) ? $event_id : null;
            $deleteArray['old_data'] = $updatedDataArrayHirtoryNew['old'];
            $deleteArray['new_data'] = $updatedDataArrayHirtoryNew['updated'];
            $deleteArray['remarks'] = $message;
            LogActivity::deleteHistory($deleteArray);
        }

        return true;
    } else {
        return false;
    }
}
function getGraphValues($user)
{
    if (isset($user->userWorkingShift->workShift) && !empty($user->userWorkingShift->workShift->start_time)) {
        $userShift = $user->userWorkingShift->workShift;
    } else {
        $userShift = defaultShift();
    }
    $joining_date = $user->profile->joining_date;
    $tensur_start_date = yearPeriod()['yearStart'];
    if (date('Y-m-d', strtotime($joining_date)) > $tensur_start_date) {
        $tensur_start_date = $joining_date;
    }

    $late_in_summary = [];
    $half_day_summary = [];
    $absent_summary = [];

    // Set the start and end dates
    $start_date = new DateTime($tensur_start_date);
    $current_date = new DateTime(); // current date
    $end_date = new DateTime(yearPeriod()['yearEnd']);

    // Iterate over months
    while ($current_date >= $start_date && $current_date <= $end_date) {
        // Your code here: Perform actions for each month
        $formatted_month = $current_date->format('Y-m');
        $summary_statistics = getAttandanceCount($user->id, $tensur_start_date, "$formatted_month-25", 'all', $userShift);
        $late_in_summary[] = $summary_statistics['lateIn'];
        $half_day_summary[] = $summary_statistics['halfDay'];
        $absent_summary[] = $summary_statistics['absent'];

        // Move to the next month
        $current_date->sub(new DateInterval('P1M'));
    }

    return [
        'late_in_summary' => $late_in_summary,
        'half_day_summary' => $half_day_summary,
        'absent_summary' => $absent_summary
    ];
}

function getLastMonthAttendanceReport($resignation_date, $employee_id, $type)
{
    $monthYear = date('m/Y', strtotime($resignation_date));
    $lastMonthlySalaryReport = MonthlySalaryReport::where('employee_id', $employee_id)->where('month_year', $monthYear)->orderby('id', 'desc')->first();
    $count = 0;
    if (!empty($lastMonthlySalaryReport)) {
        if ($type == "absent") {
            $count = $lastMonthlySalaryReport->absent_days ?? 0;
        } elseif ($type == 'half_days') {
            $count = $lastMonthlySalaryReport->half_days ?? 0;
        } elseif ($type == 'lateIn') {
            $count = $lastMonthlySalaryReport->late_in_days ?? 0;
        }
    }
    return $count;
}

function getFirstObject($model = null , $id = null)
{
    $model = "App\Models\\" . $model;
    return $model::where("id", $id)->first();
}
function getIpRestriction()
{

    return config("app.ip_restrict");
}

function getTeamMembers($user)
{
    $department_ids = [];
    if ($user->hasRole('Admin')) {
        $department_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
    } elseif ($user->hasRole('Department Manager')) {
        $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
        $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
        $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
        if (!empty($child_departments) && count($child_departments) > 0) {
            $department_ids = array_unique(array_merge($department_ids, $child_departments));
        }
    } elseif ($user->hasRole('Employee')) {
        if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
            $department_ids[] = $user->departmentBridge->department_id;
        }
    }

    $team_members = DepartmentUser::whereIn('department_id', $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
    return User::whereIn('id', $team_members)->where('id', '!=', $user->id)->where('is_employee', 1)->where('status', 1)->select(['id', 'slug', 'first_name', 'last_name', 'email'])->get();
}
function getTeamMemberIds($user)
{
    $department_ids = [];
    if ($user->hasRole('Admin')) {
        $department_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
    } elseif ($user->hasRole('Department Manager')) {
        $manager_dept_ids = Department::where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
        $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
        $child_departments = Department::where('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
        if (!empty($child_departments) && count($child_departments) > 0) {
            $department_ids = array_unique(array_merge($department_ids, $child_departments));
        }
    } elseif ($user->hasRole('Employee')) {
        if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
            $department_ids[] = $user->departmentBridge->department_id;
        }
    }

    $team_members = DepartmentUser::whereIn('department_id', $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
    return User::whereIn('id', $team_members)->where('id', '!=', $user->id)->where('is_employee', 1)->where('status', 1)->pluck('id')->toArray();
}

function getTicketCategoriesAndReasons()
{
    $data = [];
    $data['reasons'] = TicketReason::orderby('id', 'desc')->where('status', 1)->select(['id', 'name', 'description', 'status'])->get();
    $data['ticket_categories'] = TicketCategory::where('status', 1)->select(['id', 'name', 'description', 'status'])->get();
    return $data;
}

function checkcondition($new = null, $old = null)
{
    if ((isset($new) && !empty($new)) || (isset($old) && !empty($old))) {
        return true;
    } else {
        return false;
    }
}

function checkWebMail($desiredEmail)
{
    $cpanelUsername = config("project.cpanelUsername");
    $cpanelToken = config("project.cpanelToken");
    $cpanelDomain =  config("project.cpanelDomain");
    $params = array(
        'cpanel_jsonapi_version' => 2,
        'cpanel_jsonapi_module' => 'Email',
        'cpanel_jsonapi_func' => 'list_pops',
        'quota' => 'unlimited'
    );
    $queryString = http_build_query($params);
    $cpanelApiUrl = "https://{$cpanelDomain}:2083/execute/Email/list_pops?{$queryString}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $cpanelApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: cpanel {$cpanelUsername}:{$cpanelToken}"
    ]);
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    if (isset($result['error'])) {
        return ['success' => false, 'error' => $result['error']];
    } else {
        $message = "";
        if (isset($result['data']) && !empty($result['data'])) {
            $emailList = $result['data'];
            if (isset($emailList) && !empty($emailList)) {
                $emailList = collect($emailList)->pluck("email")->toArray();
                $emailExists = false;
                if (in_array($desiredEmail, $emailList)) {
                    $emailExists = true;
                    $message = $desiredEmail;
                }
                if ($emailExists) {
                    return ['success' => true, 'message' => 'Email : ' .  $message . ' already exist on webmail'];
                } else {
                    return ['success' => false, 'message' => 'email does not exist'];
                }
            }
        }
    }
}


function getDataForAbsentReport()
{
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
    $users = User::where('is_employee', 1)->where('status', 1)->select(['id', 'first_name', 'last_name'])->limit(10)->orderby("id", "desc")->get();
    $data = [];
    foreach ($users as $user) {
        $shift = $user->userWorkingShift;
        if (empty($shift)) {
            $shift = defaultShift();
        } else {
            $shift = $shift->workShift;
        }
        $statistics = AttendanceController::getAttandanceCount($user->id, $year . "-" . ((int)$month - 1) . "-26", $year . "-" . (int)$month . "-25", 'all', $shift);
        if (count($statistics['absent_dates']) > 1) {
            $absentDates = array_slice($statistics['absent_dates'], 0, -1);

            $designation = '-';
            if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title)) {
                $designation = $user->jobHistory->designation->title;
            }

            $manager = '-';
            if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->manager_id)) {
                $manager = getAuthorizeUserName($user->departmentBridge->department->manager_id);
            }

            $employeeData = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'designation' => $designation,
                'shift' => date('h:i A', strtotime($shift->start_time)) . ' - ' . date('h:i A', strtotime($shift->end_time)),
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
}
function sendWhatsapp($data)
{
    try {
        $base_url = config("project.whatsapp.base_url");
        $channel_id = config("project.whatsapp.channel_id");
        $access_token = config("project.whatsapp.access_token");
        $apiEndpoint = $base_url . $channel_id . "/sendFile?token=" . $access_token;
        $payload = [
            'phone' => $data['number'],
            'filename' => $data['filename'],
            'caption' => $data['caption'],
            'body' => "data:application/pdf;base64,{$data['file']}",
        ];
        $client = new Client();
        $response = $client->post($apiEndpoint, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);
        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();
        // $historyArray['model_id'] = 0;
        // $historyArray['model_name'] = "\App\Models\User";
        // $historyArray['type'] = "3";
        // $historyArray['event_id'] = 12;
        // if ($statusCode == 200) {
        //     $historyArray['remarks'] = "Employee absent report has been sent through whatsapp";
        // } else {
        //     $historyArray['remarks'] = "Failed to send employee absent report through whatsapp";
        // }
        // LogActivity::deleteHistory($historyArray);
        Log::info("RESPONSE FROM WHATSAPP API SENDING ABSENT REPORT : " . json_encode($responseBody));
        return response()->json(['status' => 'success', 'response' => $responseBody, 'statusCode' => $statusCode]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function checkHoliday($employee_id, $holiday_date){
    $holiday = Holiday::whereDate('start_at', '<=', $holiday_date)
    ->whereDate('end_at', '>=', $holiday_date)
    ->first();

    if(!empty($holiday)){
        if($holiday->type=="universal"){
            return $holiday;
        }elseif($holiday->type=="customizable"){
            $holidayCustomize = HolidayCustomizeEmployee::where('holiday_id', $holiday->id)->where('employee_id', $employee_id)->first();
            if(!empty($holidayCustomize)){
                return $holiday;
            }
        }else{
            return '';
        }
    }else{
        return '';
    }
}
