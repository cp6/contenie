<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Moment extends Model
{
    use HasFactory;

    protected $fillable = ['sid', 'media_id', 'name', 'seconds'];

    protected static function booted(): void
    {
        static::creating(function (Moment $moment) {
            $moment->sid = Str::random(8);
        });
    }


}
