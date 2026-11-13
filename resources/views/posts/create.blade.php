@extends('layouts.app')

@section('title', 'Create New Bulletin - Barangay Bulletin - Purok Serra')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Navigation Back Link -->
    <div>
        <a
            href="{{ route('posts.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition"
        >
            <span>&larr;</span>
            <span>Back to bulletins</span>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 sm:p-10">

        <!-- Heading -->
        <div class="border-b border-gray-100 pb-5 mb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                Create New Bulletin Post
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Posting as <strong class="text-gray-800">{{ auth()->user()->author->name }}</strong> ({{ auth()->user()->author->position }})
            </p>
        </div>

        <!-- Validation Error Summary -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">
                <p class="font-semibold mb-2">Please correct the following errors:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-6">
            @csrf

            <!-- Title Field -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    maxlength="120"
                    placeholder="Enter bulletin title (max 120 characters)"
                    class="w-full px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @else border-gray-300 @enderror"
                    required
                >
                @error('title')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Field -->
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">
                    Category <span class="text-red-500">*</span>
                </label>
                <select
                    id="category"
                    name="category"
                    class="w-full px-4 py-2 border rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @else border-gray-300 @enderror"
                    required
                >
                    <option value="">Select a category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags Checkboxes -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tags <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-500">(select at least one)</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3 bg-gray-50 rounded-lg border @error('tags') border-red-500 @else border-gray-200 @enderror">
                    @foreach ($tags as $tag)
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                            <input
                                type="checkbox"
                                name="tags[]"
                                value="{{ $tag->id }}"
                                @checked(in_array($tag->id, old('tags', [])))
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span>{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
                @error('tags.*')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Body Field -->
            <div>
                <label for="body" class="block text-sm font-semibold text-gray-700 mb-1">
                    Bulletin Content <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-500">(min 20 characters)</span>
                </label>
                <textarea
                    id="body"
                    name="body"
                    rows="6"
                    placeholder="Provide full announcement details for Purok Serra residents..."
                    class="w-full px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('body') border-red-500 @else border-gray-300 @enderror"
                    required
                >{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published Checkbox -->
            <div class="flex items-center space-x-2 pt-2">
                <input
                    type="checkbox"
                    id="is_published"
                    name="is_published"
                    value="1"
                    @checked(old('is_published', true))
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <label for="is_published" class="text-sm font-medium text-gray-700 cursor-pointer">
                    Publish immediately to the community bulletin
                </label>
                @error('is_published')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a
                    href="{{ route('posts.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition cursor-pointer shadow-xs"
                >
                    Submit Post
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
