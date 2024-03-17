<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = ['sid', 'user_id', 'original_name'];

    protected static function booted(): void
    {
        static::creating(function (Upload $upload) {
            $upload->sid = Str::random(8);
        });

    }
}
