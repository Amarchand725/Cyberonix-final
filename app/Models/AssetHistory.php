<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = '';
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}