<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogValidationTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        // prepare 
        $this->withExceptionHandling();
        $this->createAuthUser();

        
    }

    /** @test */
    public function while_storing_blog_fields_is_required()
    {
        // act 
        $this->get('/blog/create');

        $response = $this->post('/blog')->assertRedirect('/blog/create');

        //assert
        $response->assertSessionHasErrors(['title','body','image']);

    }

    /** @test */
    public function while_storing_blog_image_field_must_be_image()
    {
        // act 
        $this->get('/blog/create');

        $response = $this->post('/blog',['image'=>'This is image'])->assertRedirect('/blog/create');

        //assert
        $response->assertSessionHasErrors('image');

    }

    // /** @test */
    // public function while_updating_blog_fields_is_required()
    // {
    //     $blog = $this->createBlog();

    //     // act 
    //     $this->get('/blog/edit');

    //     $response = $this->put('/blog/'.$blog->id)->assertRedirect('/blog/edit');

    //     //assert
    //     $response->assertSessionHasErrors(['title','body','image']);

    // }
}
