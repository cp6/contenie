<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['sid', 'user_id', 'type', 'visibility', 'ext', 'has_versions', 'size_kb', 'duration_seconds', 'bitrate_kbs', 'framerate', 'height', 'width', 'has_audio', 'original_name', 'title'];

    protected static function booted(): void
    {
        static::creating(function (Media $media) {
            $media->sid = Str::random(8);
        });

    }

}
