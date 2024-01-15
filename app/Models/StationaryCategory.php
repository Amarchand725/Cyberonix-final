<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StationaryCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    public function employee(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function qPprice(){
        return $this->hasMany(Stationary::class, 'stationary_category_id', 'id');
    }
}
