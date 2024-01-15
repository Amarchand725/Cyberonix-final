<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasEmployee(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasCategory(){
        return $this->hasOne(TicketCategory::class, 'id', 'ticket_category_id');
    }
    public function hasReason(){
        return $this->hasOne(TicketReason::class, 'id', 'reason_id');
    }
}
