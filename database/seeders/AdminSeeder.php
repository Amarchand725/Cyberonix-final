<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\BodyType;
use App\Models\Position;
use App\Models\LeaveType;
use App\Models\WorkShift;
use App\Models\Department;
use App\Models\JobHistory;
use App\Models\Designation;
use App\Models\TicketReason;
use App\Models\AuthorizeEmail;
use App\Models\DepartmentUser;
use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use App\Models\EmploymentStatus;
use App\Models\WorkingShiftUser;
use Spatie\Permission\Models\Role;
use App\Models\UserEmploymentStatus;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'banner' => '1684433621.png',
            'language' => 'English',
            'max_discrepancies' => 6,
            'max_leaves' => 6,
            'insurance_eligibility' => 6,
            'country' => 'Pakistan',
            'currency_symbol' => 'PKR',
        ]);

        $admin = User::create([
            'is_employee' => 0,
            'first_name' => 'Ali Hassan',
            'last_name' => 'Khan',
            'email' => 'admin@hawxtechnologies.com',
            'status' => 1,
            'password' => Hash::make('admin@123'),
        ]);

        $roles = [
            'Admin', 'Department Manager', 'Employee'
        ];

        foreach($roles as $role) {
            Role::create(
                [
                    'name' => $role,
                    'guard_name' => 'web',
                ]
            );
        }
        $admin_role = Role::where('name', 'Admin')->first();

        $permissions = include(config_path('seederData/permissions.php'));

        foreach ($permissions as $permission) {
            $underscoreSeparated = explode('-', $permission);
            $label = str_replace('_', ' ', $underscoreSeparated[0]);
            Permission::create([
                'label' => $label,
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
        //Assign Permissions and Role "Admin User".
        $permissions = Permission::get();
        $admin_role->givePermissionTo($permissions);
        $admin->assignRole($admin_role);
        //Admin Permissions

        //Assign Permissions "Department Manager Role"
        $manager_role = Role::where('name', 'Department Manager')->first();
        $permissions = include(config_path('seederData/department_manager_permissions.php'));
        $permissions = Permission::whereIn('name', $permissions)->get();
        $manager_role->givePermissionTo($permissions);
        //Assign Permissions "Department Manager Role"

        //Assign Permissions "Employee Role".
        $employee_role = Role::where('name', 'Employee')->first();
        $permissions = include(config_path('seederData/employee_permissions.php'));
        $permissions = Permission::whereIn('name', $permissions)->get();
        $employee_role->givePermissionTo($permissions);
        //Assign Permissions "Employee Role".

        $to_emails = ['to_employee'];
        $cc_emails = [$admin->email];
        AuthorizeEmail::create([
            'email_title' => 'new_employee_info',
            'to_emails' => json_encode($to_emails),
            'cc_emails' => json_encode($cc_emails),
        ]);

        $department = Department::create([
            'manager_id' => $admin->id,
            'name' => "Main Department",
            'status' => 1,
        ]);

        DepartmentUser::create([
            'department_id' => $department->id,
            'user_id' => $admin->id,
            'start_date' => date('Y-m-d'),
        ]);

        $designations = include(config_path('seederData/designations.php'));

        foreach($designations as $designation) {
            Designation::create(
                [
                    'title' => $designation,
                    'description' => $designation,
                ]
            );
        }

        $designation = Designation::where('title', 'Vice President - Business Unit Head')->first();

        $employment_statuses = include(config_path('seederData/employment_statuses.php'));

        $is_default = 0;
        foreach($employment_statuses as $employment_status) {
            $underscoreSeparated = explode('-', $employment_status);
            $label = $underscoreSeparated[0];
            $class = $underscoreSeparated[1];

            if($label=='Probation'){
                $is_default = 1;
            }
            EmploymentStatus::create([
                'name' => $label,
                'class' => $class,
                'alias' => $label,
                'description' => $label,
                'is_default' => $is_default,
            ]);
        }

        $employment_status = EmploymentStatus::where('name', 'Permanent')->first();

        JobHistory::create([
            'created_by' => $admin->id,
            'user_id' => $admin->id,
            'designation_id' => $designation->id,
            'employment_status_id' => $employment_status->id,
            'joining_date' => date('Y-m-d'),
            'end_date' => null,
        ]);

        UserEmploymentStatus::create([
            'user_id' => $admin->id,
            'employment_status_id' => $employment_status->id,
            'start_date' => date('Y-m-d'),
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'employment_id' => 1145,
            'joining_date' => date('Y-m-d'),
        ]);

        $work_shift = WorkShift::create([
            'name' => 'Night Shift (9 to 6)',
            'start_date' => date('Y-m-d'),
            'type' => 'regular',
            'status' => 1,
            'is_default' => 1,
        ]);

        WorkingShiftUser::create([
            'working_shift_id' => $work_shift->id,
            'user_id' => $admin->id,
            'start_date' => date('Y-m-d'),
        ]);

        $ticket_categories = include(config_path('seederData/ticket_categories.php'));

        foreach($ticket_categories as $ticket_category) {
            TicketCategory::create(
                [
                    'name' => $ticket_category,
                ]
            );
        }

        $ticket_reasons = include(config_path('seederData/ticket_reasons.php'));

        foreach($ticket_reasons as $ticket_reason) {
            TicketReason::create(
                [
                    'name' => $ticket_reason,
                    'description' => $ticket_reason,
                ]
            );
        }

        $body_types = include(config_path('seederData/vehicle_body_types.php'));

        foreach($body_types as $body_type) {
            BodyType::create(
                [
                    'body_type' => $body_type,
                ]
            );
        }

        $positions = include(config_path('seederData/positions.php'));

        foreach($positions as $position) {
            Position::create(
                [
                    'title' => $position,
                    'description' => $position,
                ]
            );
        }

        $leave_types = include(config_path('seederData/leave_types.php'));

        foreach($leave_types as $leave_type) {
            LeaveType::create(
                [
                    'name' => $leave_type,
                    'type' => 'paid',
                    'amount' => 2,
                ]
            );
        }
    }
}
