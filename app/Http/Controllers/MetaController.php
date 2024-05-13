<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;

class MetaController extends Controller
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
            'title' => 'string|required|min:2|max:64',
            'description' => 'string|nullable|max:64',
            'visibility' => 'integer|min:0|max:4',
        ]);


    }

    public function edit(Meta $meta)
    {
        //
    }

    public function update(Request $request, Meta $meta)
    {
        //
    }

}
