<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagAssigned extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id', 'media_id'];

    protected $with = ['tag'];

    public function tag(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }

}
