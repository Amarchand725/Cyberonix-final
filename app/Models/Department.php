<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_departments');
    }
    public function parentDepartment()
    {
        return $this->belongsTo(Department::class, 'parent_department_id');
    }

    public function childDepartments()
    {
        return $this->hasMany(Department::class, 'parent_department_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function departmentWorkShift()
    {
        return $this->hasOne(DepartmentWorkShift::class, 'department_id');
    }
}
