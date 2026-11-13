@extends('layouts.app')

@section('title', $post->title . ' - Barangay Bulletin - Purok Serra')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-1 text-sm text-emerald-700 hover:text-emerald-900">
        <x-icon name="arrow_back" />
        Back to all bulletins
    </a>

    <article class="bg-white rounded-lg border border-stone-200 p-6 sm:p-8">
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="font-semibold uppercase tracking-wide text-emerald-700">{{ $post->category }}</span>
            @if ($post->is_pinned)
                <span class="inline-flex items-center gap-0.5 font-medium text-amber-700">
                    <x-icon name="push_pin" :size="16" />
                    Pinned
                </span>
            @endif
        </div>

        <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-stone-900 leading-tight">{{ $post->title }}</h1>

        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-stone-500">
            <span class="inline-flex items-center gap-1">
                <x-icon name="person" />
                <a href="{{ route('authors.show', $post->author->id) }}" class="font-medium text-emerald-700 hover:underline">{{ $post->author->name }}</a>
                <span>({{ $post->author->position }})</span>
            </span>
            <span class="inline-flex items-center gap-1">
                <x-icon name="calendar_today" />
                {{ $post->published_at?->format('F j, Y \a\t g:i A') ?? 'Unpublished' }}
            </span>
            <span class="inline-flex items-center gap-1">
                <x-icon name="visibility" />
                {{ $post->views }} {{ Str::plural('view', $post->views) }}
            </span>
        </div>

        @if ($post->tags->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($post->tags as $tag)
                    <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                       class="px-2 py-0.5 rounded text-xs bg-stone-100 text-stone-600 hover:bg-emerald-100 hover:text-emerald-800">#{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif

        <div class="mt-6 pt-6 border-t border-stone-100 text-stone-800 leading-relaxed whitespace-pre-line">{{ $post->body }}</div>
    </article>

    <section class="bg-white rounded-lg border border-stone-200 p-6 sm:p-8 space-y-5">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-stone-900">
            <x-icon name="forum" :size="22" class="text-emerald-700" />
            Comments
            <span class="text-sm font-normal text-stone-500">({{ $post->comments->count() }})</span>
        </h2>

        @auth
            <div>
                <h3 class="text-sm text-stone-600 mb-2">Leave a comment as <strong class="text-stone-900">{{ auth()->user()->name }}</strong></h3>

                @if ($errors->any())
                    <div class="mb-3 p-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-700">
                        <p class="flex items-center gap-1 font-medium">
                            <x-icon name="error" />
                            Please fix the following:
                        </p>
                        <ul class="mt-1 ml-6 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('comments.store', $post->id) }}" class="space-y-2">
                    @csrf
                    <label for="body" class="sr-only">Comment</label>
                    <textarea id="body" name="body" rows="3" placeholder="Write a comment..."
                              class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('body') border-red-500 @else border-stone-300 @enderror">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md text-sm font-medium cursor-pointer">
                            <x-icon name="send" />
                            Post Comment
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="rounded-md border border-dashed border-stone-300 p-5 text-center text-sm text-stone-600">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1 font-medium text-emerald-700 hover:underline">
                    <x-icon name="login" />
                    Sign in to comment
                </a>
            </div>
        @endauth

        <ul class="divide-y divide-stone-100">
            @forelse ($post->comments->sortByDesc('id') as $comment)
                <li class="py-4 flex gap-3">
                    <x-icon name="account_circle" :size="28" class="text-stone-400" />
                    <div class="flex-1">
                        <div class="flex flex-wrap items-baseline gap-x-2 text-sm">
                            <span class="font-semibold text-stone-900">{{ $comment->user->name }}</span>
                            <span class="text-xs text-stone-400">{{ $comment->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        <p class="mt-1 text-sm text-stone-700 whitespace-pre-line">{{ $comment->body }}</p>
                    </div>
                </li>
            @empty
                <li class="py-6 text-center text-sm text-stone-400">No comments yet.</li>
            @endforelse
        </ul>
    </section>

</div>
@endsection
