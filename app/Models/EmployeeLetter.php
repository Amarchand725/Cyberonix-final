<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLetter extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasEmployee(){
        return $this->hasOne(User::class, 'id', 'employee_id');
    }

    public function hasCreatedBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function hasTemplate(){
        return $this->hasOne(LetterTemplate::class, 'id', 'template_id');
    }

    public function hasUserVehicle(){
        return $this->hasOne(VehicleUser::class, 'id', 'vehicle_user_id');
    }
}
