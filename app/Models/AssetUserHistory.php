<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AssetUserHistory extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';
    public function assetDetail() {
        return $this->belongsTo(AssetDetail::class ,'asset_detail_id' , 'id');
    }
    public function employee() {
        return $this->belongsTo(User::class ,'employee_id' , 'id');
    }
    public function performer() {
        return $this->belongsTo(User::class ,'creator_id' , 'id');
    }
}
