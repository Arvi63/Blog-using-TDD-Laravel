<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test*/
    public function user_can_see_all_tags()
    {
        $tag = $this->createTag();
        
        $response = $this->get(route('tag.index'));

        $response->assertStatus(200);
        $response->assertSee($tag->name);

    }

    /** @test*/
    public function user_can_create_tag()
    {
        //prepare
        $tag = Tag::factory()->raw();
        
        //act
        $response = $this->post(route('tag.store'),$tag);

        //assert
        // $response->assertStatus(200);
        $response->assertRedirect(route('tag.index'));
    }

    /** @test*/
    public function user_can_delete_a_tag_and_blog_link_also_deleted()
    {
        //prepare
        $tag = $this->createTag();
        $this->createAuthUser();
        $blog = $this->createBlog(['user_id'=>auth()->id()]);
        $blog->tags()->attach($tag->id);
        
        //act
        $response = $this->delete(route('tag.destroy',$tag->slug));

        //assert
        $response->assertRedirect(route('tag.index'));
        $this->assertDatabaseMissing('blog_tag',[
            'blog_id'=> $blog->id,
            'tag_id' => $tag->id
        ]);

        $this->assertDatabaseHas('blogs',['id'=>$blog->id]);
    }



    /** @test*/
    public function user_can_delete_a_tag()
    {
        //prepare
        $tag = $this->createTag();
       
        //act
        $response = $this->delete(route('tag.destroy',$tag->slug));

        //assert
        $this->assertDatabaseMissing('tags',$tag->toArray());
        $response->assertRedirect(route('tag.index'));
    }

    /** @test*/
    public function user_can_update_a_tag()
    {
        //prepare
        $tag = $this->createTag();
       
        //act
        $response = $this->patch(route('tag.update',$tag->slug),['name'=>'Laravel']);

        //assert
        $this->assertDatabaseHas('tags',['id'=>$tag->id,'name'=>'Laravel']);
        $response->assertRedirect(route('tag.index'));
    }

    /** @test*/
    public function user_can_visit_form_to_store_a_tag()
    {
        //prepare
       
        //act
        $response = $this->get(route('tag.create'));

        //assert
        $response->assertStatus(200);
        $response->assertSee("Create a New Tag");
    }

    /** @test*/
    public function user_can_visit_edit_form_to_update_a_tag()
    {
        //prepare
        $tag = $this->createTag();

        //act
        $response = $this->get(route('tag.edit',$tag->slug));

        //assert
        $response->assertStatus(200);
        $response->assertSee('Update a Tag',['name'=>$tag->name]);
    }




}
