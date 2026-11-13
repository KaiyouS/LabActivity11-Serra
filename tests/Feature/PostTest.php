<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_url_redirects_to_posts(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/posts');
    }

    public function test_posts_index_only_displays_published_posts(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $published = Post::create([
            'author_id' => $author->id,
            'title' => 'Published Barangay Post',
            'category' => 'Announcement',
            'body' => 'This is a published post.',
            'is_published' => true,
            'is_pinned' => false,
            'published_at' => now(),
            'views' => 0,
        ]);

        $draft = Post::create([
            'author_id' => $author->id,
            'title' => 'Secret Draft Post',
            'category' => 'Health',
            'body' => 'This is still a draft.',
            'is_published' => false,
            'is_pinned' => false,
            'published_at' => null,
            'views' => 0,
        ]);

        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSee('Published Barangay Post');
        $response->assertDontSee('Secret Draft Post');
    }

    public function test_posts_index_lists_pinned_posts_first(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $newerRegular = Post::create([
            'author_id' => $author->id,
            'title' => 'Newer Regular Post',
            'category' => 'Announcement',
            'body' => 'Body text',
            'is_published' => true,
            'is_pinned' => false,
            'published_at' => now(),
            'views' => 0,
        ]);

        $olderPinned = Post::create([
            'author_id' => $author->id,
            'title' => 'Older Pinned Post',
            'category' => 'Announcement',
            'body' => 'Body text',
            'is_published' => true,
            'is_pinned' => true,
            'published_at' => now()->subDays(5),
            'views' => 0,
        ]);

        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Older Pinned Post', 'Newer Regular Post']);
    }

    public function test_posts_index_paginates_by_six(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        Post::factory()->count(10)->create([
            'author_id' => $author->id,
            'is_published' => true,
        ]);

        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSee('Showing 1–6 of 10 posts');
    }

    public function test_posts_can_be_filtered_by_search_query_and_category(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        Post::create([
            'author_id' => $author->id,
            'title' => 'Vaccination Schedule Tomorrow',
            'category' => 'Health',
            'body' => 'Health advisory',
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);

        Post::create([
            'author_id' => $author->id,
            'title' => 'Ayuda Distribution Schedule',
            'category' => 'Ayuda',
            'body' => 'Ayuda details',
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);

        // Search by title 'Vaccination'
        $response = $this->get('/posts?q=Vaccination');
        $response->assertStatus(200);
        $response->assertSee('Vaccination Schedule Tomorrow');
        $response->assertDontSee('Ayuda Distribution Schedule');

        // Filter by category 'Ayuda'
        $response = $this->get('/posts?category=Ayuda');
        $response->assertStatus(200);
        $response->assertSee('Ayuda Distribution Schedule');
        $response->assertDontSee('Vaccination Schedule Tomorrow');

        // No matches
        $response = $this->get('/posts?q=NonExistentTitle');
        $response->assertStatus(200);
        $response->assertSee('No posts match your search.');
    }

    public function test_post_show_increments_views(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $post = Post::create([
            'author_id' => $author->id,
            'title' => 'Purok Serra Clean-up Drive',
            'category' => 'Announcement',
            'body' => 'Community clean up event.',
            'is_published' => true,
            'is_pinned' => true,
            'published_at' => now(),
            'views' => 0,
        ]);

        $response = $this->get("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertSee('1 view');
        $this->assertEquals(1, $post->fresh()->views);

        // Second view
        $this->get("/posts/{$post->id}");
        $this->assertEquals(2, $post->fresh()->views);
    }

    public function test_post_show_returns_404_for_drafts_and_missing_posts(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $draft = Post::create([
            'author_id' => $author->id,
            'title' => 'Unpublished Post',
            'category' => 'Peace & Order',
            'body' => 'Draft body',
            'is_published' => false,
            'published_at' => null,
            'views' => 0,
        ]);

        $response = $this->get("/posts/{$draft->id}");
        $response->assertStatus(404);

        $missingResponse = $this->get('/posts/99999');
        $missingResponse->assertStatus(404);
    }
}
