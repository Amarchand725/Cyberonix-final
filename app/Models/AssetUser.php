<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetUser extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = '';
    public function asset()
    {
        return $this->belongsTo(AssetDetail::class, 'asset_detail_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
    public function assignBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
   
   
}
