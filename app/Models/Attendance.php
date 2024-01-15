<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hasEmployee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userShift()
    {
        return $this->hasOne(WorkShift::class, 'id', 'work_shift_id');
    }
}
