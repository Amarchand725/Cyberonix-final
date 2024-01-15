<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasOwner(){
        return $this->hasOne(VehicleOwner::class, 'id', 'owner_id');
    }
    public function hasRent(){
        return $this->hasOne(VehicleRent::class, 'vehicle_id', 'id')->where('end_date', NULL)->orderby('id', 'desc');
    }
    public function hasImage(){
        return $this->hasOne(VehicleImage::class, 'vehicle_id', 'id');
    }
    public function hasImages(){
        return $this->hasMany(VehicleImage::class, 'vehicle_id', 'id');
    }
    public function hasBodyType(){
        return $this->hasOne(BodyType::class, 'id', 'body_type');
    }
}
