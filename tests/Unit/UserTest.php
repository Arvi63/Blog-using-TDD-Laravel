<?php

namespace Tests\Unit;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_have_many_blogs()
    {
        //prepare
        $user = $this->createUser();
        $blog = $this->createBlog(['user_id'=>$user->id]);

        //assert
        $this->assertInstanceOf(Blog::class,$user->blogs[0]);
    }
}
