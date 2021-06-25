<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogPublishTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();
        $this->createAuthUser();
    }

    /** @test*/
    public function user_can_publish_blog()
    {
        $blog  =$this->createBlog(['user_id'=>auth()->id()]);
        
        $response = $this->put('/blog/'.$blog->slug,['published_at'=>now()]);

        $response->assertRedirect('/blog');
        $this->assertNotNull($blog->fresh()->published_at);
    }

    /** @test*/
    public function user_can_unpublish_blog()
    {
        $blog  = $this->createBlog(['user_id'=>auth()->id(),'published_at'=>now()]);
        
        $response = $this->put('/blog/'.$blog->slug,['published_at'=>null]);

        $response->assertRedirect('/blog');
        $this->assertNull($blog->fresh()->published_at);
    }
}
