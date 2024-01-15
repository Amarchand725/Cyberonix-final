<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentWorkShift extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class, 'work_shift_id');
    }
}
