<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AttendanceAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasEmployee(){
        return $this->hasOne(User::class, 'id', 'employee_id');
    }

    public function hasAttendance(){
        return $this->hasOne(Attendance::class, 'id', 'attendance_id');
    }
}
