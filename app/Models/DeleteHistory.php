<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DeleteHistory extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = '';

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function modelEvent() {
        return $this->belongsTo(LogEvent::class , 'event_id' , 'id');
    }
}
