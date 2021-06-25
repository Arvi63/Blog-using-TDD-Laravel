<?php

namespace Tests\Unit;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tags_belongs_to_many_tags()
    {
        $tag = $this->createTag();
        $user = $this->createUser();
        $blog = $this->createBlog(['user_id'=>$user->id]);
        $tag->blogs()->attach($blog->id);
        

        $this->assertInstanceOf(Blog::class,$tag->blogs->first());

    }
}
