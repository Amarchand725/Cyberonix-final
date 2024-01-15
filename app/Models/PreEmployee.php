<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreEmployee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasAppliedPosition()
    {
        return $this->hasOne(AppliedPosition::class, 'pre_employee_id', 'id');
    }
    public function hasResume()
    {
        return $this->hasOne(Resume::class, 'pre_employee_id', 'id');
    }
    public function hasAcademic()
    {
        return $this->hasOne(Academic::class, 'pre_employee_id', 'id');
    }
    public function haveReferences()
    {
        return $this->hasMany(Reference::class, 'pre_employee_id', 'id');
    }
    public function haveEmploymentHistories()
    {
        return $this->hasMany(EmploymentHistory::class, 'pre_employee_id', 'id');
    }
    public function hasManager(){
        return $this->hasOne(User::class, 'id', 'manager_id');
    }
    public function user(){
        return $this->hasOne(User::class, 'pre_emp_id', 'id');
    }
}
