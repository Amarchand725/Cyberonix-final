<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HolidayCustomizeEmployee extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function hasEmployee(){
        return $this->hasOne(User::class, 'id', 'employee_id');
    }
}
