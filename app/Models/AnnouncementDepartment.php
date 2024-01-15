<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementDepartment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hasDepartment()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}
