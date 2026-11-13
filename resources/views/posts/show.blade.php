@extends('layouts.app')

@section('title', $post->title . ' - Barangay Bulletin - Purok Serra')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navigation Back Link -->
    <div>
        <a
            href="{{ route('posts.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition"
        >
            <span>&larr;</span>
            <span>Back to all bulletins</span>
        </a>
    </div>

    <!-- Main Post Content -->
    <article class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 sm:p-10">

        <!-- Badges & Tags -->
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $post->category }}
            </span>

            @if ($post->is_pinned)
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                    📌 Pinned
                </span>
            @endif

            @foreach ($post->tags as $tag)
                <a
                    href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                    class="inline-block px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 transition"
                >
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>

        <!-- Post Title -->
        <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
            {{ $post->title }}
        </h1>

        <!-- Post Metadata Bar -->
        <div class="mt-4 pt-4 border-t border-b border-gray-100 py-3 flex flex-wrap items-center justify-between gap-y-2 text-xs sm:text-sm text-gray-500">
            <div class="flex items-center gap-3">
                <span>
                    By <a href="{{ route('authors.show', $post->author->id) }}" class="font-semibold text-blue-600 hover:text-blue-800 underline">
                        {{ $post->author->name }}
                    </a> ({{ $post->author->position }})
                </span>
                <span>&bull;</span>
                <span>
                    {{ $post->published_at ? $post->published_at->format('F j, Y \a\t g:i A') : 'Unpublished' }}
                </span>
            </div>

            <div class="flex items-center gap-4 text-gray-700 font-medium">
                <span class="inline-flex items-center gap-1">
                    👁️ {{ $post->views }} {{ Str::plural('view', $post->views) }}
                </span>
                <span class="inline-flex items-center gap-1">
                    💬 {{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}
                </span>
            </div>
        </div>

        <!-- Post Body -->
        <div class="mt-8 text-gray-800 text-base sm:text-lg leading-relaxed whitespace-pre-line">
            {{ $post->body }}
        </div>

        <!-- Article Footer / Author Disclaimer -->
        <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs text-gray-400 gap-2">
            <span>Barangay Matina Crossing Official Bulletin &bull; Purok Serra</span>
            <span>ID: #{{ $post->id }}</span>
        </div>

    </article>

    <!-- Comments Section -->
    <section class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 sm:p-8 space-y-6">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <span>💬</span>
            <span>Comments</span>
            <span class="text-sm font-normal text-gray-500">({{ $post->comments->count() }})</span>
        </h2>

        <!-- Comment Posting Form -->
        @auth
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Leave a comment as <span class="text-blue-600">{{ auth()->user()->name }}</span></h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                        <p class="font-semibold mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('comments.store', $post->id) }}" class="space-y-3">
                    @csrf

                    <div>
                        <label for="body" class="sr-only">Comment</label>
                        <textarea
                            id="body"
                            name="body"
                            rows="3"
                            placeholder="Write your comment here (minimum 3 characters)..."
                            class="w-full px-3 py-2 border rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('body') border-red-500 @else border-gray-300 @enderror"
                            required
                        >{{ old('body') }}</textarea>
                        @error('body')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition cursor-pointer shadow-xs"
                        >
                            Post Comment
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-6 text-center text-sm text-gray-600">
                <p>Want to join the discussion?</p>
                <a href="{{ route('login') }}" class="inline-block mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-xs transition">
                    Sign in to comment
                </a>
            </div>
        @endauth

        <!-- Comments List (Newest first) -->
        <div class="space-y-4 pt-2">
            @forelse ($post->comments as $comment)
                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 space-y-1.5">
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="font-semibold text-gray-800 text-sm">
                            {{ $comment->user->name }}
                        </span>
                        <span>
                            {{ $comment->created_at->format('M d, Y \a\t g:i A') }}
                        </span>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                        {{ $comment->body }}
                    </p>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    No comments yet. Be the first to share your thoughts!
                </div>
            @endforelse
        </div>

    </section>

</div>
@endsection
