<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\DepartmentUser;
use App\Models\JobHistory;
use App\Models\Designation;
use App\Models\Profile;
use Illuminate\Http\Request;
use Auth;

class ChatController extends Controller
{
    public function index(){
        $user = Auth::user();
        $data = [];
        $title = 'Chat';

        $role = $user->getRoleNames()->first();
        foreach($user->getRoleNames() as $user_role){
            if($user_role=='Admin'){
                $role = $user_role;
            }elseif($user_role=='Department Manager'){
                $role = $user_role;
            }
        }

        $team_members = [];
        $team_members_ids = [];

        if($role == 'Admin'){
            $data['team_members'] = User::where('is_employee', 1)->where('status', 1)->get();
        }elseif($role == 'Department Manager'){
            if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
                $user_department = $user->departmentBridge->department;
            }
            if($user_department->name=='Support Team 3' || $user_department->name=='IT Department'){
                $data['team_members'] = User::where('is_employee', 1)->where('status', 1)->get();
            }else{
                $team_member_ids = [];
                $dept_ids = [];
                if(isset($user_department) && !empty($user_department)){
                    $sub_dep = Department::where('parent_department_id', $user_department->id)->where('manager_id', Auth::user()->id)->first();
                    if(!empty($sub_dep)){
                        $dept_ids[] = $sub_dep->id;
                        $dept_ids[] = $user_department->id;
                        $sub_deps = Department::where('parent_department_id', $sub_dep->id)->get();
                        if(!empty($sub_deps)){
                            foreach($sub_deps as $sub_department){
                                $dept_ids[] = $sub_department->id;
                            }
                        }
                    }else{
                        $sub_deps = Department::where('parent_department_id', $user_department->id)->get();
                        if(!empty($sub_deps) && count($sub_deps)){
                            foreach($sub_deps as $sub_dept){
                                $dept_ids[] = $sub_dept->id;
                            }
                        }
                    }

                    $team_member_ids = DepartmentUser::whereIn('department_id', $dept_ids)->where('user_id', '!=', $user->id)->where('end_date', null)->get(['user_id']);
                }

                if(sizeof($team_member_ids) > 0) {
                    foreach($team_member_ids as $team_member_id) {
                        $team_members_ids[] = $team_member_id->user_id;
                    }
                }
                $team_members_ids[] = $user_department->manager_id;
                $data['team_members'] = User::whereIn('id', $team_members_ids)->where('status', 1)->where('is_employee', 1)->get();
            }
        }else{
            if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
                $user_department = $user->departmentBridge->department;
            }

            if($user_department->name=='Support Team 3' || $user_department->name=='IT Department'){
                $data['team_members'] = User::where('is_employee', 1)->where('status', 1)->get();
            }else{
                $team_member_ids = [];
                if(isset($user_department) && !empty($user_department)){
                    $team_members_ids[] = $user_department->manager_id;

                    $parent_department = Department::where('id', $user_department->parent_department_id)->where('status', 1)->first();
                    if(!empty($parent_department)){
                        $team_members_ids[] = $parent_department->manager_id;
                    }

                    $team_member_ids = DepartmentUser::where('department_id', $user_department->id)->where('user_id', '!=', $user->id)->where('end_date', null)->get(['user_id']);
                }

                if(sizeof($team_member_ids) > 0) {
                    foreach($team_member_ids as $team_member_id) {
                        $team_members_ids[] = $team_member_id->user_id;
                    }
                }

                $data['team_members'] = User::whereIn('id', $team_members_ids)->where('status', 1)->where('is_employee', 1)->get();
            }
        }

        $data['authUser']= $user;
        $model = Auth::user();
        $data['authProfile']=Profile::where('user_id', $user->id)->first();

        $data['jobHistories'] = JobHistory::orderby('id', 'desc')->where('end_date', NULL)->get();
        $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();

        $adminDepart = Department::where('name', 'Main Department')->where('status', 1)->pluck('id')->toArray();
        $data['adminUsers'] = User::where('id', $adminDepart)->get();
        $data['all_users'] = User::where('status', 1)->get();

        $financeDepart = Department::where('name', 'Admin')->where('status', 1)->pluck('manager_id')->toArray();

        $itDepart = Department::where('name', 'IT Department')->where('status', 1)->first();

        $itUsers = DepartmentUser::where('department_id', $itDepart->id)->where('end_date', NULL)->pluck('user_id')->toArray();
        $itUsers[] = $itDepart->manager_id;

        $itUsers = array_values(array_unique($itUsers));
        $data['itUsersID'] = $itUsers;

        $data['financeUsers'] = User::where('id', '!=', Auth::user()->id)->where('id', $financeDepart)->get();

        $data['itUsers'] = User::where('id', '!=', Auth::user()->id)->whereIn('id', $itUsers)->get();

        return view('user.chat.chat', compact('title', 'data', 'model'));
    }

    public function store(Request $request)
    {
        $all_images=array();
        if($files=$request->file('file')){
            foreach($files as $file){
                $image = $file;
                $input['imagename'] = uniqid().time().'.'.$image->getClientOriginalExtension();
                $image_name=$input['imagename'];
                $image->move(public_path('upload/chat'), $input['imagename']);
                array_push($all_images,$image_name);
            }
        }
        $final=implode(",",$all_images);
        return $final;
    }
}
