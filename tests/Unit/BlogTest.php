<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function blog_can_upload_image()
    {
        $blog = new Blog();
        $image = UploadedFile::fake()->image('photo1.jpg');
        
        $blog->uploadImage($image);

        Storage::assertExists('photo1.jpg');
    }

    /** @test */
    public function blog_belongs_to_user(){

        $user = $this->createUser();
        $blog = $this->createBlog(['user_id'=>$user->id]);
        // dd($blog->user);
        $this->assertInstanceOf(User::class,$blog->user);

    }

    /** @test */
    public function blog_has_many_tags(){
        $user = $this->createUser();

        $tag = $this->createTag();
        $blog = $this->createBlog(['user_id'=>$user->id]);
        $blog->tags()->attach($tag->id);

        // dd($blog->user);
        $this->assertInstanceOf(Tag::class,$blog->tags[0]);

    }
}
