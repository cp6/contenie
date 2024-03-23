<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Media;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Process $process)
    {
        dd($process);
    }

    public function edit(Process $process)
    {
        $media = Media::where('sid', $process->media_sid)->firstOrFail();
        $thumbnail = Process::createThumbnail($media);
        $format_details = Process::output($media);
        if (isset($format_details['video'])) {
            $media->update([
                'audio_streams' => $format_details['audio_streams'],
                'bitrate_kbs' => $format_details['video']['bitrate_kbs'],
                'framerate' => (int)$format_details['video']['framerate'],
                'codec' => $format_details['video']['codec'],
                'height' => $format_details['video']['height'],
                'width' => $format_details['video']['width'],
                'aspect_ratio' => $format_details['video']['aspect_ratio'],
                'duration' => $format_details['video']['duration'],
                'has_audio' => ($format_details['audio_streams'] > 0) ? 1 : 0
            ]);
        }

        if (isset($format_details['audio'])) {

            $index = 0;
            foreach ($format_details['audio'] as $stream) {

                try {
                    Audio::create([
                        'media_id' => $media->id,
                        'index' => $index,
                        'codec' => $stream['codec'],
                        'profile' => $stream['profile'],
                        'channels' => $stream['channels'],
                        'rate' => $stream['rate'],
                        'layout' => $stream['layout'],
                        'size_kb' => $stream['size_kb'],
                        'duration' => $stream['duration'],
                        'bitrate_kbs' => $stream['bitrate_kbps'],
                        'timebase' => $stream['timebase'],
                        'name' => $stream['handler_name'] ?? null,
                    ]);
                } catch (\Exception $exception) {
                    Log::debug($exception->getMessage());
                }
                $index++;
            }

        }

        return view('process.edit', ['media' => $media]);
        //$file = Storage::disk('public')->path("temp/{$process->media_sid}.mp4");
        //$command = 'ffprobe -v error -show_format -show_streams -print_format json ' . $file;
        //return json_decode(shell_exec($command), true);
    }

    public function update(Request $request, Process $process)
    {
        //
    }

    public function destroy(Process $process)
    {
        //
    }
}
