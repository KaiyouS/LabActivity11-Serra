<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $lola = User::where('email', 'lola.serra@bulletin.test')->firstOrFail();

        // Seed comments for the pinned post (at least 3 comments, one of them by Lola Serra)
        $pinnedPost = Post::where('is_pinned', true)->first();
        if ($pinnedPost) {
            Comment::create([
                'post_id' => $pinnedPost->id,
                'user_id' => $lola->id,
                'body' => 'Salamat kaayo Kagawad sa pag-organize ani nga clean-up drive! Magdala ko og silhig ug sako.',
            ]);

            Comment::create([
                'post_id' => $pinnedPost->id,
                'user_id' => $users->where('id', '!=', $lola->id)->random()->id,
                'body' => fake()->sentence(),
            ]);

            Comment::create([
                'post_id' => $pinnedPost->id,
                'user_id' => $users->where('id', '!=', $lola->id)->random()->id,
                'body' => fake()->sentence(),
            ]);
        }

        // Give other published posts 0-5 comments by random users
        $otherPublishedPosts = Post::where('is_published', true)
            ->where('is_pinned', false)
            ->get();

        foreach ($otherPublishedPosts as $post) {
            $numComments = rand(0, 5);
            for ($i = 0; $i < $numComments; $i++) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'body' => fake()->sentence(),
                ]);
            }
        }
    }
}
