<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertNotCount;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private $post;

    public function setUp():void
    {
        parent::setUp();
        $this->post = $this->createPost(['title' => 'Land Rover']);
    }


    // Get all posts
    public function test_fetch_all_posts()
    {
        $response = $this->getJson(route('post.index'));

        //dd($response->json());
        $this->assertEquals(1,count($response->json()));
    }


    // Get single post
    public function test_fetch_single_post()
    {
        $response = $this->getJson(route('post.show',$this->post->id))
                    ->assertOk()
                    ->json();

        $this->assertEquals($response['title'] ,$this->post->title);
    }


    // Store new post
    public function test_store_new_post()
    {
        $post = Post::factory()->make();

        $response = $this->postJson(route('post.store'),['title' => $post->title])
            ->assertCreated()
            ->json();

        $this ->assertEquals($post->title,$response['title']);
        $this->assertDatabaseHas('posts',['title' => $post->title]);
    }


    // Validation of title field
    public function test_while_storing_post_title_field_is_required()
    {
        $this->withExceptionHandling();

        $this->postJson(route('post.store'))
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['title']);
    }


    // Delete a post
    public function test_delete_post()
    {
        $this->deleteJson(route('post.destroy', $this->post->id))
                    ->assertNoContent();

        $this->assertDatabaseMissing('posts', ['title' => $this->post->title ]);
    }



    // Update a post
    public function test_update_post()
    {
        $this->patchJson(route('post.update', $this->post->id, ['title' => 'updated title']))
                    ->assertOk();

        $this->assertDatabaseHas('posts',['id' => $this->post->id, 'title' => 'updated title']);

    }



    // Validation of title field when updating
    public function test_while_updating_post_title_field_is_required()
    {
        $this->withExceptionHandling();

        $this->patchJson(route('post.update',$this->post->id))
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['title']);
    }
}
