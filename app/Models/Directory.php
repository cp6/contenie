<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Directory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'files', 'size'];

    public function media(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Media::class, 'directory_id', 'id');
    }

    public static function cachedDirectories()
    {
        return Cache::remember("directories", now()->addWeek(), function () {
            return self::get();
        });
    }

}
