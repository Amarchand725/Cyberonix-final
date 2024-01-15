<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasUsers()
    {
        return $this->hasMany(JobHistory::class, 'designation_id', 'id')->where('end_date', null);
    }
}
