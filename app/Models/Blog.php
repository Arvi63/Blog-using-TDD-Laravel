<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','body','published_at','image','user_id'];
    // protected $guarded = [];


    public static function boot(){
        parent::boot();
        static::creating(function($blog){
            $blog->slug = Str::slug($blog->title);
        });
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function scopePublished($query){
        $query->whereNotNull('published_at');
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope('published', function (Builder $builder) {
    //         $builder->whereNotNull('published_at');
    //     });
    // }


    public function uploadImage($image){
        Storage::put($image->name,file_get_contents($image));
        $this->update(['image'=>$image->name]);

    }

    public static  function store($request){
        $blog = auth()->user()->blogs()->create($request->except('image'));
        // attach tag id 
        if ($request->hasFile('image')) {
            $blog->uploadImage($request->image);
        }
        $blog->tags()->attach($request->tag_ids);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    
}
