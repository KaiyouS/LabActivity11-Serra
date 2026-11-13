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

        <!-- Badges -->
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $post->category }}
            </span>

            @if ($post->is_pinned)
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                    📌 Pinned
                </span>
            @endif
        </div>

        <!-- Post Title -->
        <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
            {{ $post->title }}
        </h1>

        <!-- Post Metadata Bar -->
        <div class="mt-4 pt-4 border-t border-b border-gray-100 py-3 flex flex-wrap items-center justify-between gap-y-2 text-xs sm:text-sm text-gray-500">
            <div class="flex items-center gap-4">
                <span>By <strong>Author #{{ $post->author_id }}</strong></span>
                <span>&bull;</span>
                <span>
                    {{ $post->published_at ? $post->published_at->format('F j, Y \a\t g:i A') : 'Unpublished' }}
                </span>
            </div>

            <div class="flex items-center gap-2 text-gray-700 font-medium">
                <span>👁️</span>
                <span>{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
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

</div>
@endsection
