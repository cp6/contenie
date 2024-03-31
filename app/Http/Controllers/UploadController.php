<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Meta;
use App\Models\Process;
use App\Models\Upload;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('upload.create');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();

            $sid = Str::random(8);

            $store_temp = $file->storeAs('public/temp', $sid . '.' . $ext);

            if ($store_temp) {
                //Storing file was a success
                try {
                    $upload = new Upload();
                    $upload->user_id = Auth::id();
                    $upload->original_name = $file->getClientOriginalName();
                    $upload->save();

                    $mime = $file->getClientMimeType();

                    if (str_starts_with($mime, 'video/')) {
                        $type = 1;
                    } elseif (str_starts_with($mime, 'audio/')) {
                        $type = 2;
                    } elseif (str_starts_with($mime, 'image/')) {
                        $type = 3;
                    } else {
                        $type = 0;
                    }

                    $media = new Media();
                    $media->upload_id = $upload->user_id;
                    $media->directory_id = \App\Models\Directory::cachedDirectories()->random()->id;
                    $media->sid = $sid;
                    $media->user_id = Auth::id();
                    $media->parent_id = null;
                    $media->type = $type;
                    $media->visibility = 0;
                    $media->ext = $ext;
                    $media->mime = $mime;
                    $media->size_kb = $file->getSize() / 1024;
                    $media->save();

                    $meta = new Meta();
                    $meta->media_id = $media->id;
                    $meta->title = Factory::create()->sentence(4);
                    $meta->save();

                    $process = new Process();
                    $process->media_id = $media->id;
                    $process->media_sid = $media->sid;
                    $process->type = 0;
                    $process->save();

                } catch (\Exception $exception) {
                    Log::debug($exception->getMessage());
                    //Delete file if the DB write didnt work
                    if (Storage::disk('public')->exists('temp/' . $sid . '.' . $ext)) {
                        Storage::disk('public')->delete('temp/' . $sid . '.' . $ext);
                    }
                    return response()->json(['id' => null]);
                }


                return response()->json(['id' => $process->id]);

            }

            return response()->json(['id' => null]);
            //return redirect()->route('dashboard')->with('success', 'Created media');
        }
        //Doesnt have file in request
        return response()->json(['id' => null]);

    }

    public function show(Upload $upload)
    {
        //
    }

    public function edit(Upload $upload)
    {
        //
    }

    public function update(Request $request, Upload $upload)
    {
        //
    }

    public function destroy(Upload $upload)
    {
        //
    }
}
