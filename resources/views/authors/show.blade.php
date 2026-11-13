@extends('layouts.app')

@section('title', $author->name . ' - Barangay Bulletin - Purok Serra')

@section('content')
<div class="space-y-6">

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

    <!-- Author Profile Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-2xl shadow-inner">
                    {{ substr($author->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                        {{ $author->name }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-gray-600">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                            {{ $author->position }}
                        </span>
                        <span>&bull;</span>
                        <span class="text-gray-500">{{ $author->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Badge -->
            <div class="flex items-center gap-3">
                <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-center">
                    <span class="block text-2xl font-black text-blue-700">{{ $posts->total() }}</span>
                    <span class="text-xs font-medium text-blue-600 uppercase tracking-wider">Posts</span>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 text-center">
                    <span class="block text-2xl font-black text-indigo-700">{{ $commentsCount }}</span>
                    <span class="text-xs font-medium text-indigo-600 uppercase tracking-wider">
                        {{ Str::plural('Comment', $commentsCount) }} Received
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Author's Published Posts -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            Published Bulletins by {{ $author->name }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($posts as $post)
                <article class="bg-white rounded-xl border border-gray-200 shadow-xs hover:shadow-md transition flex flex-col justify-between p-6">
                    <div>
                        <!-- Category & Pinned Badges -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $post->category }}
                            </span>

                            @if ($post->is_pinned)
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                    📌 Pinned
                                </span>
                            @endif
                        </div>

                        <!-- Post Title -->
                        <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600 transition">
                            <a href="{{ route('posts.show', $post->id) }}">
                                {{ $post->title }}
                            </a>
                        </h3>

                        <!-- Tags Badges -->
                        @if ($post->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @foreach ($post->tags as $tag)
                                    <a
                                        href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                                        class="inline-block px-2 py-0.5 text-xs font-medium rounded-md bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 transition"
                                    >
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Body Snippet -->
                        <p class="text-gray-600 text-sm mt-3 line-clamp-3 leading-relaxed">
                            {{ Str::limit($post->body, 130) }}
                        </p>
                    </div>

                    <!-- Card Metadata Footer -->
                    <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-500 space-y-2">
                        <div class="flex items-center justify-between">
                            <span>Published</span>
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Unpublished' }}</span>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center gap-1 font-medium text-gray-600">
                                    👁️ {{ $post->views }} {{ Str::plural('view', $post->views) }}
                                </span>
                                <span class="inline-flex items-center gap-1 font-medium text-gray-600">
                                    💬 {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                                </span>
                            </div>

                            <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs inline-flex items-center gap-1">
                                Read full &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                    <p class="text-gray-500 text-base">This author has not published any bulletins yet.</p>
                </div>
            @endforelse
        </div>

        @if ($posts->hasPages())
            <div class="mt-6 space-y-3">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
