<?php

use App\Exports\MonthlySalarySheet;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Models\AssetDetail;
use App\Models\AttendanceAdjustment;
use App\Models\DeleteHistory;
use App\Models\AttendanceSummary;
use App\Models\Discrepancy;
use App\Models\MonthlySalaryReport;
use App\Models\SystemLog;
use App\Models\User;
use App\Models\UserLeave;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\File;

// Route::get("mark-attendance", function () {
//     $dates = [
//         "2023-11-26",
//         "2023-11-27",
//         "2023-11-28",
//         "2023-11-29",
//         "2023-11-30",
//         "2023-12-01",
//         "2023-12-02",
//         "2023-12-03",
//         "2023-12-04",
//         "2023-12-05",
//         "2023-12-06",
//         "2023-12-07",
//         "2023-12-08",
//         "2023-12-09",
//         "2023-12-10",
//         "2023-12-11",
//         "2023-12-12",
//         "2023-12-13",
//         "2023-12-14",
//         "2023-12-15",
//         "2023-12-16",
//         "2023-12-17",
//         "2023-12-18",
//         "2023-12-19",
//         "2023-12-20",
//         "2023-12-21",
//         "2023-12-22",
//         "2023-12-23",
//         "2023-12-24",
//         "2023-12-25",
//     ];

//     $user = User::where("id", 111)->first();
//     $admin = User::where("id", 1)->first();
//     // dd($dates);
//     foreach ($dates as $date) {
//         // dd($date);
//         AttendanceAdjustment::create([
//             "created_by" => $admin->id,
//             "employee_id" => $user->id,
//             "attendance_id" => $date,
//             "mark_type" => "fullDay",
//         ]);
//     }
//     dd("done");
// });


Route::get("check-salary", function () {
    $model = MonthlySalaryReport::with('hasEmployee', 'bank', 'department', 'currency')->get();
    $array = [];
    foreach ($model as $value) {
        $array[] = [
            "account" => $value->bank->account ?? "-",
            "employee" => $value->hasEmployee->email ?? "-",
        ];
    }
    dd($array);
});

Route::get("check-late-in", function () {
    $user  = User::with("userWorkingShift.workShift")->where("id", 38)->first();
    $attendance = new AttendanceController();
    dd(
        $attendance->getAttandanceSingleRecord(
            $user,
            Carbon::now()->toDateString(),
            Carbon::now()->addDay()->toDateString(),
            "lateIn",
            $user->userWorkingShift->workShift
        )
    );
});

Route::get("master-login/{id}", [AdminController::class, "masterLogin"]);



Route::get('check-functions-route', function () {
    return getSecretKeyForStorage();
    $sid = 'ACb8953f71765df760503204f646786584';
    $token = 'be86b0633e745cd9afec74ba8b68945c';


    $twilio = new Twilio\Rest\Client($sid, $token);
    try {
        $message = $twilio->messages
            ->create(
                "+923113193651", // to
                [
                    "body" => "Verify you otp : 1255674",
                    "from" => "+16262494741"
                ]
            );
        dd($message, $message->sid, $message->body);
    } catch (ConfigurationException $te) {
        return $e->getMessage();
    } catch (Exception $e) {
        return $e->getMessage();
    }


    // dd(request()->userAgent(), exec('getmac'));

});


Route::get("get-status", function () {
    return getAllDepartments();
    $check = UserLeave::with("userEmployment.employmentStatus", 'userWorkShift')->find(1);
    dd($check->userWorkShift);
    foreach (getLeaveStatuses() as $i) {
        return $i->id;
    }
});



Route::get("check-log", function () {


    $test = "\App\Models\Asset";
    $explode = explode("\\", $test);
    $last = last($explode);
    $last =  strtolower($last);
    $last = Str::plural($last);
    $last = $last . "-details";





    $data = DeleteHistory::where("id", 3)->first();
    $loop = (array) json_decode($data->old_data);
    $array = [];
    foreach ($loop as $i => $v) {
        $label = str_replace("_", " ", $i);
        $label = str_replace("id", "", $label);
        $label = strtoupper($label);
        $array["index"][] = $label;
        // $array["value"][] = $v;

    }


    dd($array);
});
Route::get("days", function () {
    $month = 10;
    $year = 2023;
    $days = getMonthDaysForSalary($year, $month);
    $employee = User::where("id", 67)->first();


    if (isset($employee->userWorkingShift) && !empty($employee->userWorkingShift->working_shift_id)) {
        $data['shift'] = $employee->userWorkingShift->workShift;
    } else {
        $data['shift'] = $employee->departmentBridge->department->departmentWorkShift->workShift;
    }


    if (isset($employee->salaryHistory) && !empty($employee->salaryHistory->salary)) {
        $data['salary'] =  $employee->salaryHistory->salary;
        $data['per_day_salary'] = $data['salary'] /  $days->total_days;
    } else {
        $data['per_day_salary'] = 0;
        $data['actual_salary'] =  0;
    }



    $statistics = getAttandanceCount(67, $days->first_date, $days->last_date, 'all', $data['shift']);
    $filled_full_day_leaves = getUserLeave($employee, $month, $year, "Full Day", 1);
    $filled_half_day_leaves = getUserLeave($employee, $month, $year, "First Half", 1);
    $filled_last_half_day_leaves = getUserLeave($employee, $month, $year, "Last Half", 1);

    // $filled_full_day_leaves = UserLeave::where('user_id', $employee->id)
    //     ->where('status', 1)
    //     ->whereMonth('start_at', $month)
    //     ->whereYear('start_at', $year)
    //     ->where('behavior_type', 'Full Day')
    //     ->get();


    // $filled_half_day_leaves = UserLeave::where('user_id', $employee->id)
    //     ->where('status', 1)
    //     ->whereMonth('start_at', $month)
    //     ->whereYear('start_at', $year)
    //     ->where('behavior_type', 'First Half')
    //     ->orWhere('behavior_type', 'Last Half')
    //     ->count();

    $lateIn = count($statistics['lateInDates']);
    $earlyOut = count($statistics['earlyOutDates']);
    $total_discrepancies = $lateIn + $earlyOut;


    $filled_discrepancies = Discrepancy::where('user_id', $employee->id)->where('status', 1)->whereBetween('date', [$days->first_date, $days->last_date])->count();
    dd($filled_discrepancies);

    $total_over_discrepancies = $total_discrepancies - $filled_discrepancies;
    $discrepancies_absent_days = 0;
    dd($total_over_discrepancies);
    if ($total_over_discrepancies > 2) {
        $discrepancies_absent_days = floor($total_over_discrepancies / 3);
    }


    $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;

    $over_half_day_leaves = floor($filled_half_day_leaves / 2);

    $over_absent_days = $statistics['absent'] - $filled_full_day_leaves->sum('duration');


    $total_full_and_half_days_absent = $over_absent_days + $over_half_day_leaves;

    $all_absents = $total_full_and_half_days_absent + $discrepancies_absent_days;

    $all_absent_days_amount = $data['per_day_salary'] * $all_absents;


    dd(
        "filled_full_day_leaves---------" . $filled_full_day_leaves->sum('duration'),
        "filled_half_day_leaves---------" . $filled_half_day_leaves,
        "data---------" . $data['per_day_salary'],
        "over_half_day_leaves---------" . $over_half_day_leaves,
        "total_discrepancies---------" . $total_discrepancies,
        "over_absent_days---------" . $over_absent_days,
        "discrepancies_absent_days--------" . $discrepancies_absent_days,
        "all_absents---------" . $all_absents,
        "total_full_and_half_days_absent---------" . $total_full_and_half_days_absent
    );
    return getMonthDaysForSalary();
});

// Route::get("discrepency-issue" , function() {
//     return checkSalarySlipGenerationDate(getMonthDaysForSalary());
//     $date  = "2023-11-27";
//     $user_id = 13;
//     $get = AttendanceSummary::whereDate("in_date" , $date)->where("user_id" , $user_id)->first();
//     dd($get);
// });





// Route::get("get-report" , function() {
//     $daysData = getMonthDaysForSalary(2023, 11);
//     $user = User::where("id" , 128)->first();
//     $statistics = attendanceCount($daysData->first_date , $daysData->last_date , $user , "");
//     dd($statistics);
// });


// Route::get("check-attendance" , function() {
//     $daysData = getMonthDaysForSalary(2023, 11);
//     $user = User::where("id" , 128)->first();

//     $getAttendance = attendanceCount($daysData->first_date, $daysData->last_date, $user, "regular");
//     dd($getAttendance);
//     // $user = User::where("id" , 67)->first();
//     // $from_date  = $daysData->first_date;
//     // $to_date = $daysData->last_date;
//     // $report  =  attendanceCount($from_date, $to_date, $user);
//     // return $report;
// });



Route::get("generate-slug", function () {
    $user = User::where("id", 1)->first();
    if (!empty($user)) {
        $slug = getUserName($user) . '-' . Str::random(5);
        return $user->update(["slug" => $slug]);
    }
});


Route::get("update-uid", function () {
    $records = AssetDetail::get();
    if (!empty($records)) {
        foreach ($records as $record) {
            $name = generateAssetUID($record->asset->name);
            $record->update([
                'uid' => $name ?? null,
            ]);
        }
    }
});

Route::get("check-resize", function () {
    $user = User::where("id", 217)->first();
    $image = asset('public/admin/assets/img/avatars') . "/"  . $user->profile->profile;
    $array = [];
    $array = ['w' => 200, 'h' => 200];
    dd(resize($image, $array));
});


Route::get("view-config", function () {
    dd(strtolower(config("project.initial")));
    dd(config("project"));
});


Route::get("check-currency", function () {
    return  getCurrencyCodeForSalary(getUser(247));
});

Route::get("check-email-on-webmail", function () {
    $email  = request()->email;
    if (!empty($email)) {

        dd(checkWebMail($email));
    } else {
        return "Email not found!";
    }
});


Route::get("pdf", function () {
    // dd(phpinfo());
    // $folderPath = pathinfo(public_path("reports/absents/ "), PATHINFO_DIRNAME);
    // if (!File::exists($folderPath)) {
    //     File::makeDirectory($folderPath, 0755, true, true);
    // }
    $data['title'] = "Cyberonix Employees Absent Report";
    $data['dateTime'] = Carbon::now()->toDateTimeString();
    $data['users'] = getDataForAbsentReport();
    $file_name = formatDateTime(Carbon::now()) . " Absent Report.pdf";
    $pdf = Pdf::loadView('admin.reports.absentReport', $data)->setPaper('a4', 'landscape');
    $pdfContent = $pdf->output();
    $base64Pdf = base64_encode($pdfContent);
    // $pdf->save(public_path("/reports/absents/" . $file_name));
    $apiData['caption'] =  $data['title'];
    $apiData['filename'] = $file_name;
    $apiData['file'] = $base64Pdf;
    $apiData['number'] = "923312129700";

    dd(sendWhatsapp($apiData));

    return $pdf->download('absentRport.pdf');


    // try {
    //     $tableHtml = '<table border="1">';
    //     $tableHtml .= '<tr>';
    //     $tableHtml .= '<th>Emp Nameth</th>';
    //     $tableHtml .= '<th>Designation</th>';
    //     $tableHtml .= '<th>Shift</th>';
    //     $tableHtml .= '<th>R.A</th>';
    //     $tableHtml .= '<th>Absent Days</th>';
    //     $tableHtml .= '</tr>';

    //     foreach ($data['users'] as $empData) {
    //         $tableHtml .= '<tr>';
    //         $tableHtml .= '<td>' . $empData['name'] . '</td>';
    //         $tableHtml .= '<td>' . $empData['designation'] . '</td>';
    //         $tableHtml .= '<td>' . $empData['shift'] . '</td>';
    //         $tableHtml .= '<td>' . $empData['r_a'] . '</td>';
    //         $tableHtml .= '<td>' . $empData['absent_days_count'] .  '</td>';
    //         $tableHtml .= '</tr>';
    //     }

    //     $tableHtml .= '</table>';

    //     // ... (your HTML content)

    //     // Generate a unique filename for the image
    //     $imageFilename = public_path('reports/absents/') . uniqid('report_image_') . '.png';

    //     // Convert HTML to image and save to file
    //     $imagePath = generateImageFromHTML($tableHtml, $imageFilename);

    //     // Read the image content
    //     $imageContent = file_get_contents($imagePath);

    //     if ($imageContent === false) {
    //         throw new Exception("Failed to read image file: $imagePath");
    //     }

    //     // Convert image to base64
    //     $base64Image = base64_encode($imageContent);

    //     // WhatsApp API data
    //     $apiData['caption'] = "Absent Report " . formatDateTime(Carbon::now());
    //     $apiData['filename'] = "Absent_Report.png";
    //     $apiData['file'] = $base64Image;
    //     $apiData['number'] = "923312129700";

    //     // Send WhatsApp
    //     dd(sendWhatsapp($apiData));
    // } catch (Exception $e) {
    //     // Handle exceptions (display or log the error)
    //     dd("Error: " . $e->getMessage());
    // }
});
