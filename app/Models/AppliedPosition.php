<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliedPosition extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hasPosition()
    {
        return $this->hasOne(Position::class, 'id', 'applied_for_position');
    }
}
