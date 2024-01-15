<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkShift extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasWorkShiftDetail()
    {
        return $this->hasOne(WorkShiftDetail::class, 'working_shift_id', 'id');
    }

    public function haveWorkShiftDetails()
    {
        return $this->hasMany(WorkShiftDetail::class, 'working_shift_id', 'id');
    }
}
