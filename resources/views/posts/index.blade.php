@extends('layouts.app')

@section('title', 'Barangay Bulletin - Purok Serra')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 sm:p-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
            Purok Serra Community Bulletin
        </h1>
        <p class="text-gray-600 mt-2 text-sm sm:text-base">
            Official announcements, health advisories, peace &amp; order updates, and ayuda schedules for Barangay Matina Crossing residents.
        </p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-4 sm:p-6 space-y-4">
        <form method="GET" action="{{ route('posts.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <div class="flex-1">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                >
            </div>

            <div class="w-full sm:w-48">
                <select
                    name="category"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                >
                    <option value="">All Categories</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c }}" {{ request('category') === $c ? 'selected' : '' }}>
                            {{ $c }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-48">
                <select
                    name="tag"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                >
                    <option value="">All Tags</option>
                    @foreach ($tags as $t)
                        <option value="{{ $t->name }}" {{ request('tag') === $t->name ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="w-full sm:w-auto px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition cursor-pointer"
                >
                    Filter
                </button>

                @if (request()->filled('q') || request()->filled('category') || request()->filled('tag'))
                    <a
                        href="{{ route('posts.index') }}"
                        class="w-full sm:w-auto px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium text-center transition"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </form>

        @if (request()->filled('tag'))
            <div class="flex items-center gap-2 pt-2 border-t border-gray-100 text-xs text-gray-600">
                <span>Filtering by tag:</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold">
                    #{{ request('tag') }}
                    <a href="{{ route('posts.index', request()->except('tag', 'page')) }}" class="text-blue-600 hover:text-blue-900 font-bold ml-1" title="Remove tag filter">&times;</a>
                </span>
            </div>
        @endif
    </div>

    <!-- Posts Grid -->
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
                    <h2 class="text-lg font-bold text-gray-900 hover:text-blue-600 transition">
                        <a href="{{ route('posts.show', $post->id) }}">
                            {{ $post->title }}
                        </a>
                    </h2>

                    <!-- Tags Badges -->
                    @if ($post->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach ($post->tags as $tag)
                                <a
                                    href="{{ route('posts.index', array_merge(request()->query(), ['tag' => $tag->name, 'page' => 1])) }}"
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
                        <span>
                            By <a href="{{ route('authors.show', $post->author->id) }}" class="font-medium text-gray-800 hover:text-blue-600 underline">
                                {{ $post->author->name }}
                            </a>
                        </span>
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
                <p class="text-gray-500 text-base">No posts match your search.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination & Counter -->
    <div class="space-y-3">
        <div>
            {{ $posts->links() }}
        </div>
        <p class="text-xs sm:text-sm text-gray-500 text-center">
            Showing {{ $posts->firstItem() ?? 0 }}–{{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} posts
        </p>
    </div>

</div>
@endsection
