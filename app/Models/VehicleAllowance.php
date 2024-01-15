<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleAllowance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function getCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}
