@extends('layouts.app')

@section('title', $author->name . ' - Barangay Bulletin - Purok Serra')

@section('content')
<div class="space-y-6">

    <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-1 text-sm text-emerald-700 hover:text-emerald-900">
        <x-icon name="arrow_back" />
        Back to all bulletins
    </a>

    <div class="bg-white rounded-lg border border-stone-200 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <x-icon name="account_circle" :size="56" class="text-emerald-700" />
            <div>
                <h1 class="text-2xl font-bold text-stone-900">{{ $author->name }}</h1>
                <p class="mt-0.5 flex flex-wrap items-center gap-x-3 text-sm text-stone-500">
                    <span class="inline-flex items-center gap-1">
                        <x-icon name="badge" :size="16" />
                        {{ $author->position }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <x-icon name="mail" :size="16" />
                        {{ $author->email }}
                    </span>
                </p>
            </div>
        </div>

        <div class="flex gap-6 text-sm text-stone-600">
            <div class="inline-flex items-center gap-2">
                <x-icon name="article" :size="22" class="text-emerald-700" />
                <span><strong class="text-lg text-stone-900">{{ $posts->total() }}</strong> {{ Str::plural('post', $posts->total()) }}</span>
            </div>
            <div class="inline-flex items-center gap-2">
                <x-icon name="chat_bubble" :size="22" class="text-emerald-700" />
                <span><strong class="text-lg text-stone-900">{{ $commentsCount }}</strong> {{ Str::plural('comment', $commentsCount) }} received</span>
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-stone-900 mb-3">Published bulletins</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($posts as $post)
                @include('posts._card', ['post' => $post, 'showAuthor' => false])
            @empty
                <div class="col-span-full rounded-lg border border-dashed border-stone-300 p-12 text-center text-stone-500">
                    This author has not published any bulletins yet.
                </div>
            @endforelse
        </div>

        @if ($posts->hasPages())
            <div class="mt-6">{{ $posts->links() }}</div>
        @endif
    </div>

</div>
@endsection
