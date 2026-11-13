<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::query()
            ->with(['author', 'tags'])
            ->withCount('comments')
            ->where('is_published', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->input('q').'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->input('tag'));
            });
        }

        $posts = $query
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(6) // Serra has 5 letters; minimum 6 per page
            ->withQueryString();

        $categories = Post::query()->distinct()->orderBy('category')->pluck('category');
        $tags = Tag::orderBy('name')->get();

        return view('posts.index', compact('posts', 'categories', 'tags'));
    }

    public function create(): View
    {
        $categories = [
            'Announcement',
            'Health',
            'Peace & Order',
            'Ayuda',
            'Entertainment',
        ];
        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tagIds = $validated['tags'];
        unset($validated['tags']);

        $isPublished = $request->boolean('is_published');
        $validated['is_published'] = $isPublished;
        $validated['published_at'] = $isPublished ? now() : null;
        $validated['views'] = 0;

        $post = $request->user()->author->posts()->create($validated);
        $post->tags()->sync($tagIds);

        return redirect()
            ->route('posts.show', $post)
            ->with('status', 'Post created successfully.');
    }

    public function show(int|string $id): View
    {
        $post = Post::findOrFail($id);

        if (! $post->is_published) {
            abort(404);
        }

        $post->views += 1;
        $post->save();

        $post->load([
            'author',
            'tags',
            'comments' => fn ($query) => $query->latest(),
            'comments.user',
        ]);

        return view('posts.show', compact('post'));
    }
}
