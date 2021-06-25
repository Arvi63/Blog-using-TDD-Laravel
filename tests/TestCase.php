<?php

namespace Tests;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp():void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }


    protected function createBlog($args=[],$num=null){
        return Blog::factory()->count($num)->create($args);
        // $title = $title ?? 'simple ever blog';
        // $body = $body ?? 'simple body of the  blog';
    
        // return Blog::create(['title'=>$title,'body'=>$body]);
    }

    protected function createTag($args=[],$num=null){
        return Tag::factory()->count($num)->create($args);
    }

    protected function createUser($args=[],$num=null){
        return User::factory()->count($num)->create($args);
        
    }

    protected function createAuthUser($args=[]){
        $user = $this->createUser();
        $this->actingAs($user);
        return $user;
    }
}
