<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = '';
    protected $dates = ["created_at", "updated_at"];
    public function assigneeUser()
    {
        return $this->belongsTo(User::class, 'assignee', 'id');
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function assignedAssets()
    {
        return $this->hasOne(AssetUser::class, 'asset_id', 'id')->where("status", 1);
    }
    public function assetUsers()
    {
        return $this->hasMany(AssetUser::class, 'asset_detail_id', 'id');
    }
}
