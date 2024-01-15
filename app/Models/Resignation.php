<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resignation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasEmployee(){
        return $this->hasOne(User::class, 'id', 'employee_id');
    }

    public function hasProcessedBy(){
        return $this->hasOne(User::class, 'id', 'process_id');
    }
    
    public function hasEmploymentStatus(){
        return $this->hasOne(EmploymentStatus::class, 'id', 'employment_status_id');
    }
}
