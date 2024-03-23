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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Process $process)
    {
        dd($process);
    }

    /**
     * Show the form for editing the specified resource.
     */
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

            foreach ($format_details['audio'] as $stream) {

                try {
                    Audio::create([
                        
                    ]);
                } catch (\Exception $exception) {
                    Log::debug($exception->getMessage());
                }
            }

        }


        return $format_details;
        //$file = Storage::disk('public')->path("temp/{$process->media_sid}.mp4");
        //$command = 'ffprobe -v error -show_format -show_streams -print_format json ' . $file;
        //return json_decode(shell_exec($command), true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Process $process)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Process $process)
    {
        //
    }
}
