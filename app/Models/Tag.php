<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['sid', 'user_id', 'name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (Tag $tag) {
            $tag->sid = Str::random(8);
        });
    }

    public function assigned(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TagAssigned::class, 'tag_id', 'id');
    }


}
