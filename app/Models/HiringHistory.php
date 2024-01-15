<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class HiringHistory extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';
    public function createdBy() {
        return $this->belongsTo(User::class , 'created_by' , 'id');
    }
    public function user() {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

    public function employeyStatus() {
        return $this->belongsTo(EmploymentStatus::class , 'employee_status' , 'id')->orderBy('id', 'DESC');
    }

}
