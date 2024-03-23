<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Process extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'media_id', 'media_sid', 'size_1', 'bitrate_1', 'size_2', 'bitrate_2', 'size_3', 'bitrate_3', 'size_4', 'bitrate_4'];

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

}
