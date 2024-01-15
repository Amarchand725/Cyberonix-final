<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stationary extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    public function employee(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function stationartCategory(){
        return $this->hasOne(StationaryCategory::class, 'id', 'stationary_category_id');
    }
}
