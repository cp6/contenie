<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
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

    public function show(Media $media)
    {
        if ($media->type === 1) {//Video

        } elseif ($media->type === 2) {//Audio

        } elseif ($media->type === 3) {//Image

        } else {//Other

        }
    }

    public function edit(Media $media)
    {
        //
    }

    public function update(Request $request, Media $media)
    {
        //
    }

    public function destroy(Media $media)
    {
        //
    }
}
