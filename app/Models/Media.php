<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['sid', 'user_id', 'type', 'visibility', 'has_versions', 'size_kb', 'duration_seconds', 'bitrate_kbs', 'framerate', 'height', 'width', 'has_audio'];

}
