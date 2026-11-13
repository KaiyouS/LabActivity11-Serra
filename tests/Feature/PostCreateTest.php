<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_create_post_page(): void
    {
        $response = $this->get('/posts/create');

        $response->assertRedirect('/login');
    }

    public function test_resident_user_without_author_profile_gets_403_forbidden_on_create_page(): void
    {
        $resident = User::factory()->create();

        $response = $this->actingAs($resident)->get('/posts/create');

        $response->assertStatus(403);
    }

    public function test_resident_user_without_author_profile_gets_403_forbidden_on_post_store(): void
    {
        $resident = User::factory()->create();
        $tag = Tag::create(['name' => 'Purok Serra']);

        $response = $this->actingAs($resident)->post('/posts', [
            'title' => 'Resident Unauthorized Post',
            'body' => 'This is a long enough body text for testing.',
            'category' => 'Announcement',
            'tags' => [$tag->id],
            'is_published' => '1',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_author_user_can_view_create_post_page(): void
    {
        $user = User::factory()->create();
        Author::create([
            'user_id' => $user->id,
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        Tag::create(['name' => 'Purok Serra']);

        $response = $this->actingAs($user)->get('/posts/create');

        $response->assertStatus(200);
        $response->assertSee('Create New Bulletin Post');
        $response->assertSee('Kagawad Kaiyou Serra');
        $response->assertSee('Purok Serra');
    }

    public function test_author_user_can_create_published_post_with_synced_tags(): void
    {
        $user = User::factory()->create();
        $author = Author::create([
            'user_id' => $user->id,
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $tag1 = Tag::create(['name' => 'Purok Serra']);
        $tag2 = Tag::create(['name' => 'Urgent']);

        $postData = [
            'title' => 'Emergency General Assembly Tomorrow',
            'body' => 'All household leaders of Purok Serra are requested to attend.',
            'category' => 'Announcement',
            'tags' => [$tag1->id, $tag2->id],
            'is_published' => '1',
        ];

        $response = $this->actingAs($user)->post('/posts', $postData);

        $post = Post::where('title', 'Emergency General Assembly Tomorrow')->first();
        $this->assertNotNull($post);

        $response->assertRedirect(route('posts.show', $post));
        $response->assertSessionHas('status', 'Post created successfully.');

        $this->assertEquals($author->id, $post->author_id);
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);
        $this->assertEquals(2, $post->tags()->count());
        $this->assertTrue($post->tags->contains($tag1));
        $this->assertTrue($post->tags->contains($tag2));
    }

    public function test_validation_fails_for_invalid_post_input(): void
    {
        $user = User::factory()->create();
        Author::create([
            'user_id' => $user->id,
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $response = $this->actingAs($user)->post('/posts', [
            'title' => '',
            'body' => 'Short',
            'category' => 'InvalidCategory',
            'tags' => [],
        ]);

        $response->assertSessionHasErrors(['title', 'body', 'category', 'tags']);
    }
}
