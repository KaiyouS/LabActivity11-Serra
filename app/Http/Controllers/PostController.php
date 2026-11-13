<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::query()
            ->where('is_published', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->input('q').'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $posts = $query
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(6) // Serra has 5 letters; minimum 6 per page
            ->withQueryString();

        $categories = Post::query()->distinct()->orderBy('category')->pluck('category');

        return view('posts.index', compact('posts', 'categories'));
    }

    public function show(int|string $id): View
    {
        $post = Post::findOrFail($id);

        if (! $post->is_published) {
            abort(404);
        }

        $post->views += 1;
        $post->save();

        return view('posts.show', compact('post'));
    }
}
