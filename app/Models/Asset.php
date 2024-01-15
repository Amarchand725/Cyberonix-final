<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = '';
    public function detail()
    {
        return $this->hasMany(AssetDetail::class);
    }
    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id', 'id');
    }
    public function assetHistories()
    {
        return $this->hasMany(AssetHistory::class, 'asset_id', 'id');
    }
    public function assetHistory()
    {
        return $this->hasOne(AssetHistory::class, 'asset_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    public function lastDetail()
    {
        return $this->hasOne(AssetDetail::class, 'asset_id', 'id');
    }
}
