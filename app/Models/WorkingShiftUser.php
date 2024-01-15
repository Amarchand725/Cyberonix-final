<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingShiftUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function workShift()
    {
        return $this->hasOne(WorkShift::class, 'id', 'working_shift_id');
    }
}
