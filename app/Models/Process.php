<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Process extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'media_id', 'media_sid', 'command', 'type'];

    protected $with = ['media'];

    public function media(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Media::class, 'sid', 'media_sid');
    }

    public static function output(Media $media): array
    {
        $file = Storage::disk('public')->path("temp/{$media->sid}.{$media->ext}");
        $command = 'ffprobe -v error -show_format -show_streams -print_format json ' . $file;
        $data = json_decode(shell_exec($command), true);

        if (!isset($data['streams'][0])) {
            return [];
        }

        $compile = [
            'size_kb' => $media->size_kb,
            'streams' => count($data['streams'])
        ];

        $audio_streams = 0;

        foreach ($data['streams'] as $stream) {
            if ($stream['codec_type'] === 'video') {
                $compile['video'] = [
                    'codec_type' => $stream['codec_type'],
                    'codec' => $stream['codec_name'],
                    'profile' => $stream['profile'],
                    'width' => $stream['width'],
                    'height' => $stream['height'],
                    'aspect_ratio' => $stream['display_aspect_ratio'],
                    'duration' => $stream['duration'],
                    'duration_string' => date('H:i:s', \round($stream['duration'])),
                    'bitrate_kbs' => $stream['bit_rate'] / 1000,
                    'framerate' => $stream['r_frame_rate'],
                ];
            } elseif ($stream['codec_type'] === 'audio') {
                $audio_streams++;
                $compile['audio'][] = [
                    'codec_type' => $stream['codec_type'],
                    'codec' => $stream['codec_name'],
                    'profile' => $stream['profile'],
                    'rate' => $stream['sample_rate'],
                    'channels' => $stream['channels'],
                    'layout' => $stream['channel_layout'],
                    'size_kb' => Storage::disk('public')->size("temp/{$media->sid}.{$media->ext}") / 1024,
                    'duration' => $stream['duration'],
                    'duration_string' => date('H:i:s', \round($stream['duration'])),
                    'bitrate_kbps' => $stream['bit_rate'] / 1000,
                    'timebase' => $stream['time_base'],
                    'handler_name' => $stream['tags']['handler_name'] ?? null,
                ];
            }
        }

        return array_merge(['audio_streams' => $audio_streams], $compile);

    }

    public static function createThumbnail(Media $media, int $seconds = 2): false|string|null
    {
        if (!Storage::disk('public')->exists($media->directory->name)) {
            Storage::disk('public')->makeDirectory($media->directory->name);
        }

        $file = Storage::disk('public')->path("temp/{$media->sid}.{$media->ext}");
        $save_as = storage_path("app/public/{$media->directory->name}/{$media->sid}.jpg");

        $command = "ffmpeg -i $file -ss $seconds -vframes 1 $save_as";
        return shell_exec($command);
    }

    public static function ratios169(): array
    {
        return [
            ['width' => 1920, 'height' => 1080],
            ['width' => 1600, 'height' => 900],
            ['width' => 1280, 'height' => 720],
            ['width' => 1024, 'height' => 576],
            ['width' => 768, 'height' => 432],
            ['width' => 640, 'height' => 360],
            ['width' => 512, 'height' => 288],
            ['width' => 256, 'height' => 144],
            ['width' => 192, 'height' => 108]
        ];
    }

}
