<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Media;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                'has_audio' => ($format_details['audio_streams'] > 0) ? 1 : 0,
            ]);
        }

        return view('process.edit', ['media' => $media, 'ratios' => Process::ratios169()]);
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

    public function thumbnailForTrim(Request $request, Process $process)
    {
        $type = $request->type;
        Log::debug($request->time_string);
        $seconds = array_reduce(explode(':', $request->time_string), function ($carry, $item) {
            return $carry * 60 + $item;
        });
        Log::debug($seconds);
        $file = Storage::disk('public')->path("temp/{$request->sid}.{$request->ext}");
        $save_as = storage_path("app/public/temp/{$request->sid}_{$type}.jpg");

        $command = "ffmpeg -y -i $file -ss $seconds -vframes 1 $save_as";
        Log::debug($command);
        return shell_exec($command);
    }

    public function doTrim(Request $request, Process $process)
    {
        //dd($process);
        $start = $request->start_value;
        $finish = $request->finish_value;
        $file = Storage::disk('public')->path("temp/{$process->media_sid}.{$process->media->ext}");
        $save_as = storage_path("app/public/temp/{$process->media_sid}_RENAME.{$process->media->ext}");

        $command = "ffmpeg -y -ss $start -i $file -t $finish -c copy $save_as";
        Log::debug($command);
        Log::debug("temp/{$process->media_sid}_RENAME.{$process->media->ext}");
        Log::debug("temp/{$process->media_sid}.{$process->media->ext}");

        $trim = shell_exec($command);

        Storage::disk('public')->move("temp/{$process->media_sid}_RENAME.{$process->media->ext}", "temp/{$process->media_sid}.{$process->media->ext}");
        return $trim;
    }

}
