<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use App\Models\DepartmentUser;
use App\Models\Department;

class SendBirthdayEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday emails to employees';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now();

        // $users = Profile::whereMonth('date_of_birth', $today->month)
        //     ->whereDay('date_of_birth', $today->day)
        //     ->get();

        $users = User::whereHas('profile', function($query) use ($today) {
            $query->whereMonth('date_of_birth', $today->month)
                  ->whereDay('date_of_birth', $today->day);
        })->get();

        foreach($users as $user){
            $team_members = [];
            // if($user->hasRole('Admin')){
            //     $departs = Department::where('manager_id', $user->id)->get();
            //     $depart_ids = [];
            //     foreach($departs as $depart){
            //         if(!empty($depart)){
            //             $depart_ids[] = $depart->id;
            //         }
            //     }

            //     $team_employees = DepartmentUser::whereIn('department_id', $depart_ids)->get();
            //     foreach($team_employees as $team_employee){
            //         $dep_user = User::where('id', $team_employee->user_id)->where('status', 1)->where('is_employee')->first();
            //         if(!empty($dep_user)){
            //             $team_members[] = $dep_user->email;
            //         }
            //     }
            // }
            // if($user->hasRole('Department Manager')){
            //     if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
            //         $user_department = $user->departmentBridge->department;
            //     }

            //     $dept_ids = [];
            //     if(isset($user_department) && !empty($user_department)){
            //         $sub_dep = Department::where('parent_department_id', $user_department->id)->where('manager_id', $user->id)->first();
            //         if(!empty($sub_dep)){
            //             $dept_ids[] = $sub_dep->id;
            //             $dept_ids[] = $sub_dep->parent_department_id;
            //             $sub_deps = Department::where('parent_department_id', $sub_dep->id)->get();
            //             if(!empty($sub_deps)){
            //                 foreach($sub_deps as $sub_department){
            //                     $dept_ids[] = $sub_department->id;
            //                 }
            //             }
            //         }else{
            //             $sub_deps = Department::where('manager_id', $user->id)->get();
            //             $dept_ids[] = $user_department->manager_id;
            //             if(!empty($sub_deps) && count($sub_deps)){
            //                 foreach($sub_deps as $sub_dept){
            //                     $dept_ids[] = $sub_dept->id;
            //                 }
            //             }
            //         }

            //         $team_employees = DepartmentUser::whereIn('department_id', $dept_ids)->get();
            //         if(!empty($sub_dep->parentDepartment->manager_id)){
            //             $team_employees[] = (object)['user_id' => $sub_dep->parentDepartment->manager_id];
            //         }

            //         foreach($team_employees as $team_employee){
            //             $dep_user = User::where('id', $team_employee->user_id)->where('status', 1)->where('is_employee', 1)->first();
            //             if(!empty($dep_user)){
            //                 $team_members[] = $dep_user->email;
            //             }
            //         }
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

            Mail::to($user->email)->cc($team_members)->send(new Email($mailData));
        }
    }
}
