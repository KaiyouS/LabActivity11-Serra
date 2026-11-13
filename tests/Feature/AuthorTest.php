<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_page_displays_author_info_and_has_many_through_comments_count(): void
    {
        $user = User::factory()->create();
        $author = Author::create([
            'user_id' => $user->id,
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $post1 = Post::create([
            'author_id' => $author->id,
            'title' => 'First Published Post',
            'category' => 'Announcement',
            'body' => 'Body of the first post for testing.',
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);

        $post2 = Post::create([
            'author_id' => $author->id,
            'title' => 'Second Published Post',
            'category' => 'Health',
            'body' => 'Body of the second post for testing.',
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);

        $commenter = User::factory()->create();
        Comment::create([
            'post_id' => $post1->id,
            'user_id' => $commenter->id,
            'body' => 'First comment on post 1.',
        ]);
        Comment::create([
            'post_id' => $post1->id,
            'user_id' => $commenter->id,
            'body' => 'Second comment on post 1.',
        ]);
        Comment::create([
            'post_id' => $post2->id,
            'user_id' => $commenter->id,
            'body' => 'Third comment on post 2.',
        ]);

        $this->assertEquals(3, $author->comments()->count());

        $response = $this->get("/authors/{$author->id}");

        $response->assertStatus(200);
        $response->assertSee('Kagawad Kaiyou Serra');
        $response->assertSee('Kagawad');
        $response->assertSee('kagawad.serra@bulletin.test');
        $response->assertSee('3');
        $response->assertSee('comments received');
        $response->assertSee('First Published Post');
        $response->assertSee('Second Published Post');
    }

    public function test_post_card_links_author_name_to_author_page(): void
    {
        $author = Author::create([
            'name' => 'Kagawad Kaiyou Serra',
            'position' => 'Kagawad',
            'email' => 'kagawad.serra@bulletin.test',
        ]);

        $post = Post::create([
            'author_id' => $author->id,
            'title' => 'Bulletin With Author Link',
            'category' => 'Announcement',
            'body' => 'Testing author page linkage from card.',
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);

        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSee(route('authors.show', $author->id));
        $response->assertSee('Kagawad Kaiyou Serra');
    }
}
