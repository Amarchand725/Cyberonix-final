<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lab404\Impersonate\Models\Impersonate;
use App\Models\UserLeave;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* Joining */
    public function joiningDepartmentBridge()
    {
        return $this->hasOne(DepartmentUser::class, 'user_id', 'id');
    }
    public function joiningDesignation()
    {
        return $this->hasOne(JobHistory::class, 'user_id', 'id');
    }
    public function joiningSalary()
    {
        return $this->hasOne(SalaryHistory::class, 'user_id')->orderby('id', 'asc');
    }
    public function joiningDate()
    {
        return $this->hasOne(JobHistory::class, 'user_id');
    }
    /* Joining */

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }
    public function departmentBridge()
    {
        return $this->hasOne(DepartmentUser::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function departmentBridgeTerminate()
    {
        return $this->hasOne(DepartmentUser::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function jobHistory()
    {
        return $this->hasOne(JobHistory::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function jobHistoryTerminate()
    {
        return $this->hasOne(JobHistory::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function salaryHistory()
    {
        return $this->hasOne(SalaryHistory::class, 'user_id')->orderby('id', 'desc');
    }
    public function employeeStatus()
    {
        return $this->hasOne(UserEmploymentStatus::class, 'user_id', 'id')->orderby('id', 'desc');
    }
    public function leaves()
    {
        return $this->hasMany(UserLeave::class);
    }

    public function userWorkingShift()
    {
        return $this->hasOne(WorkingShiftUser::class, 'user_id', 'id')->orderby('id', 'desc');
    }

    public function userWorkingShiftTerminate()
    {
        return $this->hasOne(WorkingShiftUser::class, 'user_id', 'id')->orderby('id', 'desc');
    }

    public function hasBankDetails()
    {
        return $this->hasOne(BankAccount::class, 'user_id', 'id');
    }

    public function hasManagerDepartment(){
        return $this->hasOne(Department::class, 'manager_id', 'id');
    }
    public function hasPreEmployee(){
        return $this->hasOne(PreEmployee::class, 'id', 'pre_emp_id');
    }
    public function hasVehicle(){
        return $this->hasOne(VehicleUser::class, 'user_id', 'id')->where('end_date', NULL);
    }
    public function hasAllowance(){
        return $this->hasOne(VehicleAllowance::class, 'user_id', 'id')->where('end_date', NULL);
    }
    public function hasAttendance(){
        return $this->hasOne(Attendance::class, 'user_id', 'id')->orderby('id', 'desc');
    }

    public function hasWFHEmployee(){
        return $this->hasOne(WFHEmployee::class, 'user_id', 'id');
    }

    public function hasTerminated(){
        return $this->hasOne(Resignation::class, 'employee_id', 'id')->orderby('id', 'desc');
    }

    //can access only admin impersonate user.
    public function canImpersonate(): bool
    {
        // Check if the user has the 'admin' role or any other criteria
        return $this->hasRole('Admin');
    }


    public function bank() {
        return $this->hasOne(BankAccount::class);
    }

    public function employeeStatusEndDateNull()
    {
        return $this->hasOne(UserEmploymentStatus::class, 'user_id', 'id')->where('end_date', null)->orderby('id', 'desc');
    }
}
