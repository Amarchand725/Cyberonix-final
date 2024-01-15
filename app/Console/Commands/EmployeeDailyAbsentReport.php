<?php

namespace App\Console\Commands;

use App\Mail\Email;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AttendanceController;

class EmployeeDailyAbsentReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee-daily-absent-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
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

        $mailData = [
            'from' => 'absent_email',
            'title' => 'Employee Absence Report',
            'adminName' => $admin['first_name'].' '.$admin['last_name'],
            'employees' => $data,
        ];

        if(count($data) > 0){
            $defaultEmail = config("project.daily_absence_report_email");
            Mail::to($defaultEmail)->send(new Email($mailData));
        }
    }
}
