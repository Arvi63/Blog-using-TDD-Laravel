<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogImageUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_image_along_with_blog()
    {
        Storage::fake();
        $this->createAuthUser();
        // $blog = Blog::factory()->raw();
        $blog = $this->createBlog(['user_id'=>auth()->id()])->toArray();

        // act
        $response = $this->post('blog',$blog);
        
        //assert
        $this->assertDatabaseHas('blogs',['image'=>$blog['image']->name]);
        Storage::assertExists('photo1.jpg');
    }
}
