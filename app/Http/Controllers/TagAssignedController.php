<?php

namespace App\Http\Controllers;

use App\Models\TagAssigned;
use Illuminate\Http\Request;

class TagAssignedController extends Controller
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
        $request->validate([
            'tag_id' => 'integer|required',
            'media_id' => 'integer|required'
        ]);

    }

    public function show(TagAssigned $tagAssigned)
    {
        //
    }

    public function edit(TagAssigned $tagAssigned)
    {
        //
    }

    public function update(Request $request, TagAssigned $tagAssigned)
    {
        $request->validate([
            'tag_id' => 'integer|required',
            'media_id' => 'integer|required'
        ]);


    }

    public function destroy(TagAssigned $tagAssigned)
    {
        $tagAssigned->delete();
    }
}
