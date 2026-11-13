@extends('layouts.app')

@section('title', 'New Post - Barangay Bulletin - Purok Serra')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-1 text-sm text-emerald-700 hover:text-emerald-900">
        <x-icon name="arrow_back" />
        Back to bulletins
    </a>

    <div class="bg-white rounded-lg border border-stone-200 p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-stone-900">Create New Bulletin Post</h1>
        <p class="mt-1 text-sm text-stone-500">
            Posting as <strong class="text-stone-800">{{ auth()->user()->author->name }}</strong> ({{ auth()->user()->author->position }})
        </p>

        @if ($errors->any())
            <div class="mt-5 p-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-700">
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

        <form method="POST" action="{{ route('posts.store') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-stone-700 mb-1">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" maxlength="120"
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('title') border-red-500 @else border-stone-300 @enderror">
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-stone-700 mb-1">Category</label>
                <select id="category" name="category"
                        class="w-full px-3 py-2 border rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('category') border-red-500 @else border-stone-300 @enderror">
                    <option value="">Select a category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <fieldset>
                <legend class="text-sm font-medium text-stone-700 mb-1">Tags <span class="font-normal text-stone-500">(at least one)</span></legend>
                <div class="flex flex-wrap gap-2">
                    @foreach ($tags as $tag)
                        <label class="inline-flex items-center gap-2 px-3 py-1.5 border border-stone-300 rounded-md text-sm cursor-pointer has-checked:bg-emerald-50 has-checked:border-emerald-600">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   @checked(in_array($tag->id, old('tags', [])))
                                   class="accent-emerald-700">
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                @error('tags.*')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </fieldset>

            <div>
                <label for="body" class="block text-sm font-medium text-stone-700 mb-1">Body <span class="font-normal text-stone-500">(at least 20 characters)</span></label>
                <textarea id="body" name="body" rows="7"
                          class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('body') border-red-500 @else border-stone-300 @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="inline-flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" @checked($errors->any() ? old('is_published') : true) class="accent-emerald-700">
                    Publish now
                </label>
                @error('is_published')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-stone-100">
                <a href="{{ route('posts.index') }}" class="px-4 py-2 rounded-md text-sm text-stone-600 hover:bg-stone-100">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md text-sm font-medium cursor-pointer">
                    <x-icon name="publish" />
                    Submit Post
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
