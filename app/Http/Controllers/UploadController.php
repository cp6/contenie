<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
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
        return view('upload.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $original_name = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $mime_type = $file->getClientMimeType();

            $sid = Str::random(8);

            $store_temp = $file->storeAs('public/temp', $sid . '.' . $ext);

            if ($store_temp) {
                try {
                    $upload = new Upload();
                    $upload->user_id = Auth::id();
                    $upload->original_name = $original_name;
                    $upload->save();

                    $media = new Media();
                    $media->upload_id = $upload->user_id;
                    $media->directory_id = \App\Models\Directory::cachedDirectories()->random()->id;
                    $media->sid = $sid;
                    $media->user_id = Auth::id();
                    $media->title = $sid;
                    $media->parent_id = null;
                    $media->type = 1;
                    $media->visibility = 0;
                    $media->ext = $ext;
                    $media->size_kb = $file->getSize() / 1024;
                    $media->save();
                } catch (\Exception $exception) {
                    Log::debug($exception->getMessage());
                    //Delete file if the DB write didnt work
                    if (Storage::disk('public')->exists('temp/' . $sid . '.' . $ext)) {
                        Storage::disk('public')->delete('temp/' . $sid . '.' . $ext);
                    }

                }
            }

            return redirect()->route('dashboard')->with('success', 'Created media');
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Upload $upload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upload $upload)
    {
        //
    }
}
