<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hasEmployee(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasAttachments(){
        return $this->hasMany(DocumentAttachments::class, 'document_id', 'id');
    }



    public function hasAttachmentsWithTrashed(){
        return $this->hasMany(DocumentAttachments::class, 'document_id', 'id')->withTrashed();
    }
}
