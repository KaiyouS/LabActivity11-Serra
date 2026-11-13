@extends('layouts.app')

@section('title', 'Barangay Bulletin - Purok Serra')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-stone-900">Purok Serra Community Bulletin</h1>
        <p class="mt-1 text-sm text-stone-600">
            Announcements, health advisories, peace &amp; order updates, and ayuda schedules for Barangay Matina Crossing.
        </p>
    </div>

    <form method="GET" action="{{ route('posts.index') }}" class="bg-white rounded-lg border border-stone-200 p-3 flex flex-col sm:flex-row gap-2">
        <label class="flex-1 flex items-center gap-2 px-3 border border-stone-300 rounded-md focus-within:ring-2 focus-within:ring-emerald-600">
            <x-icon name="search" class="text-stone-400" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title"
                   class="w-full py-2 text-sm focus:outline-none">
        </label>

        <select name="category" class="sm:w-44 px-3 py-2 border border-stone-300 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600">
            <option value="">All categories</option>
            @foreach ($categories as $c)
                <option value="{{ $c }}" @selected(request('category') === $c)>{{ $c }}</option>
            @endforeach
        </select>

        <select name="tag" class="sm:w-40 px-3 py-2 border border-stone-300 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600">
            <option value="">All tags</option>
            @foreach ($tags as $t)
                <option value="{{ $t->name }}" @selected(request('tag') === $t->name)>{{ $t->name }}</option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md text-sm font-medium cursor-pointer">
                <x-icon name="filter_list" />
                Filter
            </button>
            @if (request()->filled('q') || request()->filled('category') || request()->filled('tag'))
                <a href="{{ route('posts.index') }}" class="inline-flex items-center px-3 py-2 rounded-md text-sm text-stone-600 hover:bg-stone-100">Clear</a>
            @endif
        </div>
    </form>

    @if (request()->filled('tag'))
        <p class="flex items-center gap-2 text-sm text-stone-600">
            Showing posts tagged
            <span class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded bg-emerald-100 text-emerald-800 font-medium">
                #{{ request('tag') }}
                <a href="{{ route('posts.index', request()->except('tag', 'page')) }}" title="Remove tag filter" class="inline-flex hover:text-emerald-950">
                    <x-icon name="close" :size="16" />
                </a>
            </span>
        </p>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($posts as $post)
            @include('posts._card', ['post' => $post])
        @empty
            <div class="col-span-full rounded-lg border border-dashed border-stone-300 p-12 text-center text-stone-500">
                <x-icon name="search_off" :size="32" class="text-stone-400" />
                <p class="mt-2">No posts match your search.</p>
            </div>
        @endforelse
    </div>

    <div class="space-y-2">
        {{ $posts->links() }}
        <p class="text-xs text-stone-500 text-center">
            Showing {{ $posts->firstItem() ?? 0 }}–{{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} posts
        </p>
    </div>

</div>
@endsection
