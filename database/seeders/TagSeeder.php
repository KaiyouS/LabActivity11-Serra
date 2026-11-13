<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagNames = [
            'Purok Serra',
            'Urgent',
            'Libre',
            'Schedule',
            'Advisory',
        ];

        $tags = collect($tagNames)->map(fn ($name) => Tag::create(['name' => $name]));

        $pinnedTagIds = Tag::whereIn('name', ['Purok Serra', 'Urgent'])->pluck('id')->toArray();
        $allTagIds = $tags->pluck('id')->all();

        $posts = Post::all();
        foreach ($posts as $post) {
            if ($post->is_pinned) {
                $post->tags()->sync($pinnedTagIds);
            } else {
                $randomCount = rand(1, 3);
                $randomTagIds = collect($allTagIds)->random($randomCount)->all();
                $post->tags()->sync($randomTagIds);
            }
        }
    }
}
