<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmploymentStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employmentStatus()
    {
        return $this->hasOne(EmploymentStatus::class, 'id', 'employment_status_id');
    }
}
