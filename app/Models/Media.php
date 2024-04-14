<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['sid', 'user_id', 'upload_id', 'parent_id', 'type', 'visibility', 'ext', 'audio_streams', 'directory_id', 'size_kb', 'duration', 'bitrate_kbs', 'framerate', 'height', 'width', 'has_audio', 'aspect_ratio', 'mime', 'codec', 'rate', 'channels', 'layout', 'timebase'];

    protected $with = ['directory', 'meta'];

    protected static function booted(): void
    {
        static::creating(function (Media $media) {
            //$media->sid = Str::random(8);
        });
    }

    public function upload(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Upload::class, 'id', 'upload_id');
    }

    public function directory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Directory::class, 'id', 'directory_id');
    }

    public function meta(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Meta::class, 'media_id', 'id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TagAssigned::class, 'media_id', 'id');
    }

    public function versions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function moments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Moment::class, 'media_id', 'id');
    }


}
