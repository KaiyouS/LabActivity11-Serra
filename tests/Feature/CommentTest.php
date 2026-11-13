<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private function createPost(): Post
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        return Post::create([
            'author_id' => $author->id,
            'title' => 'Purok Serra Clean-up Drive',
            'category' => 'Announcement',
            'body' => 'Community clean up event announcement for everyone.',
            'is_published' => true,
            'is_pinned' => true,
            'published_at' => now(),
            'views' => 0,
        ]);
    }

    public function test_guest_cannot_submit_comment_and_is_redirected_to_login(): void
    {
        $post = $this->createPost();

        $response = $this->post("/posts/{$post->id}/comments", [
            'body' => 'Great initiative!',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_guest_sees_sign_in_to_comment_prompt_on_post_page(): void
    {
        $post = $this->createPost();

        $response = $this->get("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertSee('Sign in to comment');
        $response->assertDontSee('Leave a comment as');
    }

    public function test_authenticated_user_can_submit_valid_comment(): void
    {
        $post = $this->createPost();
        $user = User::factory()->create(['name' => 'Lola Serra']);

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'body' => 'Magdala ko ug silhig ug sako para sa clean-up.',
        ]);

        $response->assertRedirect("/posts/{$post->id}");
        $response->assertSessionHas('status', 'Comment posted.');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'Magdala ko ug silhig ug sako para sa clean-up.',
        ]);
    }

    public function test_comment_too_short_triggers_exact_custom_error_message(): void
    {
        $post = $this->createPost();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'body' => 'Ok',
        ]);

        $response->assertSessionHasErrors([
            'body' => 'Purok Serra comments need at least 3 characters.',
        ]);
    }

    public function test_comments_are_displayed_newest_first_with_commenter_name(): void
    {
        $post = $this->createPost();
        $user1 = User::factory()->create(['name' => 'Lola Serra']);
        $user2 = User::factory()->create(['name' => 'Juan Dela Cruz']);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $user1->id,
            'body' => 'First older comment from Lola.',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $user2->id,
            'body' => 'Second newer comment from Juan.',
        ]);

        $response = $this->get("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'Second newer comment from Juan.',
            'First older comment from Lola.',
        ]);
        $response->assertSee('Lola Serra');
        $response->assertSee('Juan Dela Cruz');
    }
}
