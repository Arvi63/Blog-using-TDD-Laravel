<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->createAuthUser();
    }

    /** @test*/
    public function user_can_see_all_blogs()
    {
        // past/scene/prepare
        $blog = $this->createBlog(['published_at'=>now(),'user_id'=>auth()->id()],2);
        $unPublishedBlog= $this->createBlog(['user_id'=>auth()->id()]);
        
        // present / action / act
        $response = $this->get('/blog');

        // future / assertion / assert
        $response->assertStatus(200);
        $response->assertSee([$blog[0]->title,$blog[1]->title]);
        $response->assertDontSee($unPublishedBlog->title);
    }

    /** @test*/
    public function user_can_visit_a_published_single_blog(){
        //prepare
        
        $blog = $this->createBlog(['published_at'=>now(),'user_id'=>auth()->id()]);
        $tag = $this->createTag();
        $blog->tags()->attach($tag->id);

        //act
        $response = $this->get('/blog/'.$blog->slug);

        //assert
        $response->assertStatus(200);
        $response->assertSee([$blog->title,
                $blog->body,
                $blog->user->name,
                $blog->tags[0]->name]);
    }

    /** @test*/
    public function user_cannot_visit_unpublished_single_blog(){
        $this->withExceptionHandling();
        //prepare
        
        $blog = $this->createBlog(['user_id'=>auth()->id()]);
        //act
        $response = $this->get('/blog/'.$blog->slug);

        //assert
        $response->assertStatus(404);
        $response->assertDontSee([$blog->title,$blog->body]);
    }

    /** @test*/
    public function only_authenticated_user_can_store_blog(){
        //prepare
        // $blog = Blog::factory()->make()->toArray();
        // $blog = ['title'=> 'title for blog to store','body'=>'body for blog to store'];
        
        $blog = Blog::factory()->raw();
        $tags = $this->createTag([],2);
        unset($blog['user_id']);
        $data = array_merge(
                    $blog,
                    ['tag_ids'=>$tags->pluck('id')->toArray()]
                );

        // act
        $response = $this->post('blog',$data);
        
        //assert
        $response->assertRedirect('/blog');
        $this->assertDatabaseHas('blogs',[
            'image'=>$blog['image']->name,
            'user_id'=>auth()->id()
            ]);

        $this->assertDatabaseHas('blog_tag',[
            'tag_id' => $tags[0]->id
        ]);

    }

    /** @test */
    public function only_authenticated_user_can_delete_blog(){
        $blog = $this->createBlog(['user_id'=>auth()->id()]);
        $tag  = $this->createTag();
        $blog->tags()->attach($tag->id);


        $response = $this->delete('/blog/'.$blog->slug);

        $response->assertRedirect('/blog');
        $this->assertDatabaseMissing('blogs',$blog->toArray());
        $this->assertDatabaseMissing('blog_tag',['blog_id'=>$blog->id]);
    }

  

    /** @test */
    public function only_authenticated_user_can_update_blog(){
        
        $blog = $this->createBlog(['title'=>'This is first title of blog','user_id'=>auth()->id()]);
        $tags = $this->createTag([],2);
        $blog->tags()->attach($tags->pluck('id'));

        $response = $this->put('/blog/'.$blog->slug,[
            'title'=>'Updated Title',
            'tag_ids'=>$tags[0]->id
            ]);
        
        $response->assertRedirect('/blog');
        $this->assertDatabaseHas('blogs',['id'=>$blog->id,'title'=>'Updated Title']);
        $this->assertDatabaseMissing('blog_tag',[
            'blog_id'=>$blog->id,
            'tag_id'=>$tags[1]->id,
            ]);

    }

    /** @test */
    public function user_can_visit_form_to_store_blog(){
        
        $response = $this->get('/blog/create');

        $response->assertStatus(200);
        $response->assertSee('Create New Blog');
    }

    /** @test */
    public function user_can_visit_form_to_update_blog(){
        
        $user = $this->createUser();
        $this->actingAs($user);
        $blog = $this->createBlog(['user_id'=>auth()->id()]);

        $response = $this->get('/blog/'.$blog->slug.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Update Blog');
        $response->assertSee($blog->title);
    }

    
}
