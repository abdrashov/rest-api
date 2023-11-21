<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public string $token;

    /**
     * Sets up a user for authentication in tests.
     * Creates a new user using the factory and generates an authentication token.
     */
    private function setUser()
    {
        // Create a new user using the factory
        $this->user = User::factory()->create();

        // Generate an authentication token for the created user
        $this->token = 'Bearer ' . Auth::guard('api')->login($this->user);
    }

    /**
     * Test to check if it can list posts
     * @test
     */
    public function it_can_list_posts()
    {
        $this->setUser();

        // Creates 5 posts for the authenticated user
        Post::factory(5)->create(['user_id' => $this->user]);

        // Retrieves the list of posts and asserts the structure and count
        $response = $this->getJson('/api/v1/posts');
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * Test to check if it can show a specific post
     * @test
     */
    public function it_can_show_a_post()
    {
        $this->setUser();

        // Creates a post for the authenticated user
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        // Retrieves the specific post and asserts its details
        $response = $this->getJson("/api/v1/posts/$post->id");
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'created_at' => $post->created_at->format('d.m.Y H:i'),
                    'updated_at' => $post->updated_at->format('d.m.Y H:i'),
                ]
            ]);
    }

    /**
     * Test to check if it can create a new post
     * @test
     */
    public function it_can_create_a_post()
    {
        $this->setUser();

        // Data for creating a new post
        $postData = [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
        ];

        // Creates a new post and checks if it exists in the database
        $response = $this->postJson('/api/v1/posts', $postData, [
            'Authorization' => $this->token,
        ]);
        $response->assertStatus(201)
            ->assertJson([
                'data' => $postData
            ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
        ]);
    }

    /**
     * Test to check if it can update an existing post
     * @test
     */
    public function it_can_update_a_post()
    {
        $this->setUser();

        // Creates a post for the authenticated user
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        // Updated data for the post
        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content for the post.',
        ];

        // Updates the post and checks if it reflects in the database
        $response = $this->putJson("/api/v1/posts/$post->id", $updatedData, [
            'Authorization' => $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => $updatedData
            ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Updated Title',
            'content' => 'Updated content for the post.',
        ]);
    }

    /**
     * Test to check if it can delete a post
     * @test
     */
    public function it_can_delete_a_post()
    {
        $this->setUser();

        // Creates a post for the authenticated user
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        // Deletes the post and checks if it is removed from the database
        $response = $this->deleteJson("/api/v1/posts/$post->id", [], [
            'Authorization' => $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'message' => __('success.deleted', ['attribute' => 'Post'])
            ]);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
