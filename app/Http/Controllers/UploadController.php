<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

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
            $file->store('public'); // Save the file to the storage/uploads directory

            // Handle the file upload

            return response()->json(['success' => 'File uploaded successfully.']);
        }

        dd(1);

        return response()->json(['error' => 'File not found.'], 404);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $original_name = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $mime_type = $file->getClientMimeType();
            $size_kb = $file->getSize() / 1024;
            dd($file->getClientMimeType());
        } else {
            return redirect()->route('dashboard')->with('failed', 'globals.xml file was not uploaded CODE:2');
        }

        return redirect()->route('dashboard')->with('success', "globals.xml uploaded successfully.");
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
