<?php

namespace App\Http\Controllers;

use App\Http\Requests\blog\BlogStoreRequest;
use App\Http\Requests\blog\BlogUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index','show');
    }

    public function index(){
        $blogs = Blog::published()->get();
        return view('blog.index',compact('blogs'));
    }

    public function create(){
        return view('blog.create');
    }

    public function show($blog){
        $blog = Blog::where('slug',$blog)->published()->firstorFail();
        return view('blog.show',compact('blog'));
    }

    public function store(BlogStoreRequest $request){
        // $request['slug'] = Str::slug($request->title);
        Blog::store($request);

        return redirect('/blog');
    }

    public function edit(Blog $blog){
        return view('blog.edit',compact('blog'));
    }
    
    public function update(Request $request,Blog $blog){
        $blog->update($request->all());
        // $blog->tags()->detach();
        $blog->tags()->sync($request->tag_ids);
        return redirect('/blog');
    }
    
    public function destroy(Blog $blog){
        // $blog->tags()->detach();
        $blog->delete();
        return redirect('/blog');
    }
}
