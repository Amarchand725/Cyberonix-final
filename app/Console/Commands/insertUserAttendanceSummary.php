<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Console\Command;
use App\Models\AttendanceSummary;
use Exception;

class insertUserAttendanceSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert-user-attendance-summary';

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

        try {
            $employees = User::doesntHave("hasWFHEmployee")->with('userWorkingShift', 'userWorkingShift.workShift')->where('status', 1)->where('is_employee', 1)->get();
            foreach ($employees as $employee_member) {
                $current_date = date("Y-m-d");
                if (date("H") > 12) {
                    $next_date = date("Y-m-d", strtotime($current_date . '+1 day'));
                } else {
                    $current_date = date("Y-m-d", strtotime($current_date . '-1 day'));
                    $next_date = date("Y-m-d", strtotime($current_date . '+1 day'));
                }
                if (!empty($employee_member->userWorkingShift)) {
                    $shift = $employee_member->userWorkingShift->workShift;
                } else {
                    $shift = defaultShift();
                }
                if (!empty($shift)) {
                    $end_time = date("Y-m-d", strtotime($current_date)) . ' ' . $shift->end_time;
                    // Convert the date string to a Carbon instance
                    $carbonDateTime = Carbon::parse($end_time);
                    if ($carbonDateTime->hour < 12) {
                        $next_date = date("Y-m-d", strtotime('+1 day ' . $current_date));
                    } else {
                        $next_date = date('Y-m-d', strtotime($end_time));
                    }

                    $attendance_single_record  = getAttandanceSingleRecord($employee_member->id, $current_date, $next_date, 'all', $shift);
                    $punchOutTime = '';
                    if ($attendance_single_record['punchOut'] != 'Not Yet' and $attendance_single_record['punchOut'] != '-') {
                        $punchOutTime = date('H:i:s', strtotime($attendance_single_record['punchOut']));
                    }
                    $punchInTime = '';
                    if ($attendance_single_record['punchIn'] != '-') {
                        $punchInTime = date('H:i:s', strtotime($attendance_single_record['punchIn']));
                    }

                    $start_time = date('Y-m-d', strtotime($current_date)) . ' ' . $shift->start_time;
                    $end_time = date("Y-m-d", strtotime($next_date)) . ' ' . $shift->end_time;

                    // $late_in_start = date("Y-m-d h:i A", strtotime('+16 minutes '.$start_time));
                    // $late_in_end = date("Y-m-d h:i A", strtotime('-16 minutes '.$end_time));

                    // $half_day_start = date("Y-m-d h:i A", strtotime('+121 minutes '.$start_time));
                    // $half_day_end = date("Y-m-d h:i A", strtotime('-121 minutes '.$end_time));

                    $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
                    $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));
                    $punch_out_attendance = '';
                    if (!empty($punchOutTime)) {
                        $punchOutDateTime = date('H:i', strtotime($punchOutTime));
                        $punch_out_attendance = Attendance::where('id', $attendance_single_record['attendance_id'])
                            ->orWhereTime('in_date', 'like', $punchOutDateTime . '%')
                            ->where('user_id', $attendance_single_record['user']->id)
                            ->whereBetween('in_date', [$start, $end])
                            ->orderby('id', 'desc')
                            ->first();
                    }

                    $punch_in_attendance = '';
                    if (!empty($punchInTime)) {
                        $punchInDateTime = date('H:i', strtotime($punchInTime));
                        $punch_in_attendance = Attendance::where('id', $attendance_single_record['attendance_id'])
                            ->orWhereTime('in_date', 'like', $punchInDateTime . '%')
                            ->where('user_id', $attendance_single_record['user']->id)
                            ->whereBetween('in_date', [$start, $end])
                            ->orderby('id', 'desc')
                            ->first();
                    }

                    $in_date = '-';
                    if (!empty($punch_in_attendance)) {
                        $in_date = $punch_in_attendance->in_date;
                    }

                    $out_date = '-';
                    if (!empty($punch_out_attendance)) {
                        $out_date = $punch_out_attendance->in_date;
                    }

                    $model = AttendanceSummary::where('user_id', $attendance_single_record['user']->id)
                        ->where('user_shift_id', $shift->id)
                        ->whereBetween('in_date', [$start, $end])
                        ->orderby('id', 'desc')
                        ->first();

                    if (!empty($model) && !empty($punchInTime)) {
                        // $model->attendance_id = $attendance_single_record['attendance_id'];
                        $model->out_date = $out_date;
                        $model->attendance_type = $attendance_single_record['type'];
                        $model->save();
                    } elseif (!empty($punchInTime)) {
                        $model = AttendanceSummary::create([
                            'attendance_id' => $attendance_single_record['attendance_id'],
                            'user_shift_id' => $shift->id,
                            'user_id' => $attendance_single_record['user']->id,
                            'in_date' => $in_date,
                            'out_date' => $out_date,
                            'attendance_type' => $attendance_single_record['type'],
                            // 'behavior_out' =>,
                        ]);
                    }
                }
            }
            Log::info("CRON JOB INSERT USER ATTENDANCE SUMMARY HAS BEEN COMPLETED! ." . json_encode(Carbon::now()->toDateTimeString()));
        } catch (Exception $e) {
            Log::info("ERROR WHILE RUNNING INSERT USER ATTENDANCE SUMMARY CRON JOB . " . json_encode($e->getMessage()));
            dd($e->getMessage());
        }
    }
}
