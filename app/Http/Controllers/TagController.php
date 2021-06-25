<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
   
    public function index()
    {
        $tags = Tag::all();

        return view('tag.index',compact('tags'));
    }

  
    public function create()
    {
        return view('tag.create');
    }

    
    public function store(Request $request)
    {
        Tag::create($request->all());

        return redirect(route('tag.index'));
    }

    
    public function show(Tag $tag)
    {
        //
    }

   
    public function edit(Tag $tag)
    {
        return view('tag.edit',compact('tag'));
    }

   
    public function update(Request $request, Tag $tag)
    {
        $tag->update($request->all());
        return redirect(route('tag.index'));
    }

    
    public function destroy(Tag $tag)
    {
        // $tag->blogs()->detach();
        $tag->delete();
        return redirect(route('tag.index'));
    }
}
