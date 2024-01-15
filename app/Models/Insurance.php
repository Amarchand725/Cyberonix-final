<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function hasUser(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function hasInsuranceMeta(){
        return $this->hasMany(InsuranceMeta::class, 'insurance_id', 'id');
    }
}
