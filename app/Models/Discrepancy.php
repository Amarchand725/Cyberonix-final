<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discrepancy extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hasAttendance()
    {
        return $this->hasOne(Attendance::class,'id', 'attendance_id');
    }
    public function hasEmployee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function employee() {
        return $this->belongsTo(User::class);
    }
}
