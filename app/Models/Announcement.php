<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'announcement_departments');
    }
    public function hasAnnouncementDepartments()
    {
        return $this->hasMany(AnnouncementDepartment::class, 'announcement_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
