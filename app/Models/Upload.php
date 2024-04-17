<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'sid', 'original_name'];

    protected $with = ['media'];

    public function media(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Media::class, 'sid', 'sid');
    }


}
