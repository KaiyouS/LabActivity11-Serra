<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show(Author $author): View
    {
        $posts = $author->posts()
            ->with(['author', 'tags'])
            ->withCount('comments')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        $commentsCount = $author->comments()->count();

        return view('authors.show', compact('author', 'posts', 'commentsCount'));
    }
}
