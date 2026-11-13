<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::all();

        Post::factory()->count(40)
            ->sequence(fn ($sequence) => ['author_id' => $authors->random()->id])
            ->create();

        Post::factory()->count(10)->draft()
            ->sequence(fn ($sequence) => ['author_id' => $authors->random()->id])
            ->create();

        $kagawad = Author::where('email', 'kagawad.serra@bulletin.test')->firstOrFail();

        Post::create([
            'author_id' => $kagawad->id,
            'title' => 'Purok Serra Clean-up Drive',
            'category' => 'Announcement',
            'body' => 'Join us for the Purok Serra clean-up drive. Let us keep our barangay clean and safe.',
            'is_published' => true,
            'is_pinned' => true,
            'published_at' => '2026-11-16 08:00:00',
            'views' => 0,
        ]);
    }
}
