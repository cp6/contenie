<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;

    protected $fillable = ['media_id', 'index', 'codec', 'profile', 'rate', 'channels', 'layout', 'size_kb', 'duration', 'bitrate_kbs', 'timebase', 'name', 'description'];

}
