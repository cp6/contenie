<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['sid', 'user_id', 'upload_id', 'parent_id', 'type', 'visibility', 'ext', 'directory', 'size_kb', 'duration', 'bitrate_kbs', 'framerate', 'height', 'width', 'has_audio', 'title'];

    protected static function booted(): void
    {
        static::creating(function (Media $media) {
            $media->sid = Str::random(8);
        });

    }

}
