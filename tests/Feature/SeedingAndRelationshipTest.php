<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeedingAndRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_all_required_records_and_relationships(): void
    {
        $this->seed(DatabaseSeeder::class);

        // 1. Lola Serra seeded user
        $lola = User::where('email', 'lola.serra@bulletin.test')->first();
        $this->assertNotNull($lola);
        $this->assertEquals('Lola Serra', $lola->name);
        $this->assertTrue(Hash::check('bulletin2026', $lola->password));

        // 2. Kagawad seeded user linked to author
        $kagawadUser = User::where('email', 'kagawad.serra@bulletin.test')->first();
        $this->assertNotNull($kagawadUser);
        $this->assertEquals('Kagawad Kaiyou Serra', $kagawadUser->name);
        $this->assertTrue(Hash::check('bulletin2026', $kagawadUser->password));

        $author = Author::where('email', 'kagawad.serra@bulletin.test')->first();
        $this->assertNotNull($author);
        $this->assertEquals($kagawadUser->id, $author->user_id);
        $this->assertEquals($author->id, $kagawadUser->author->id);

        // 3. Total 10 users (2 named + 8 factory)
        $this->assertEquals(10, User::count());

        // 4. Exact 5 tags
        $expectedTags = ['Purok Serra', 'Urgent', 'Libre', 'Schedule', 'Advisory'];
        $actualTags = Tag::pluck('name')->all();
        sort($expectedTags);
        sort($actualTags);
        $this->assertEquals($expectedTags, $actualTags);

        // 5. Pinned post has exactly Purok Serra and Urgent tags
        $pinnedPost = Post::where('is_pinned', true)->first();
        $this->assertNotNull($pinnedPost);
        $pinnedTagNames = $pinnedPost->tags->pluck('name')->sort()->values()->all();
        $this->assertEquals(['Purok Serra', 'Urgent'], $pinnedTagNames);

        // 6. Pinned post has at least 3 seeded comments, one by Lola Serra
        $this->assertGreaterThanOrEqual(3, $pinnedPost->comments()->count());
        $this->assertTrue(
            $pinnedPost->comments->contains(fn ($c) => $c->user_id === $lola->id)
        );

        // 7. Verify hasManyThrough
        $authorCommentsCount = $author->comments()->count();
        $this->assertGreaterThan(0, $authorCommentsCount);
    }
}
