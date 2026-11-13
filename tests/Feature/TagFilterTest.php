<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_can_be_filtered_by_tag_query_parameter(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $urgentTag = Tag::create(['name' => 'Urgent']);
        $libreTag = Tag::create(['name' => 'Libre']);

        $urgentPost = Post::create([
            'author_id' => $author->id,
            'title' => 'Urgent Water Service Interruption',
            'category' => 'Announcement',
            'body' => 'Water service interruption notice for today.',
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);
        $urgentPost->tags()->sync([$urgentTag->id]);

        $librePost = Post::create([
            'author_id' => $author->id,
            'title' => 'Libre Tuli and Medical Mission',
            'category' => 'Health',
            'body' => 'Free medical mission for Purok Serra families.',
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);
        $librePost->tags()->sync([$libreTag->id]);

        // Filter by tag Urgent
        $response = $this->get('/posts?tag=Urgent');
        $response->assertStatus(200);
        $response->assertSee('Urgent Water Service Interruption');
        $response->assertDontSee('Libre Tuli and Medical Mission');
        $response->assertSee('#Urgent');

        // Filter by tag Libre
        $response = $this->get('/posts?tag=Libre');
        $response->assertStatus(200);
        $response->assertSee('Libre Tuli and Medical Mission');
        $response->assertDontSee('Urgent Water Service Interruption');
        $response->assertSee('#Libre');
    }
}
