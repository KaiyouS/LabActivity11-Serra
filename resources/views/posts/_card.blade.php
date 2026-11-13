{{-- Post card used on the index and author pages. Needs author + tags eager loaded and comments_count. --}}
<article class="bg-white rounded-lg border {{ $post->is_pinned ? 'border-amber-300' : 'border-stone-200' }} flex flex-col p-5">
    <div class="flex items-center justify-between gap-2 text-xs">
        <span class="font-semibold uppercase tracking-wide text-emerald-700">{{ $post->category }}</span>

        @if ($post->is_pinned)
            <span class="inline-flex items-center gap-0.5 font-medium text-amber-700">
                <x-icon name="push_pin" :size="16" />
                Pinned
            </span>
        @endif
    </div>

    <h2 class="mt-2 text-lg font-semibold leading-snug text-stone-900">
        <a href="{{ route('posts.show', $post->id) }}" class="hover:text-emerald-700">{{ $post->title }}</a>
    </h2>

    <p class="mt-2 text-sm text-stone-600 line-clamp-3">{{ Str::limit($post->body, 130) }}</p>

    @if ($post->tags->isNotEmpty())
        <div class="mt-3 flex flex-wrap gap-1.5">
            @foreach ($post->tags as $tag)
                <a href="{{ route('posts.index', array_merge(request()->only('q', 'category'), ['tag' => $tag->name])) }}"
                   class="px-2 py-0.5 rounded text-xs bg-stone-100 text-stone-600 hover:bg-emerald-100 hover:text-emerald-800">#{{ $tag->name }}</a>
            @endforeach
        </div>
    @endif

    <div class="mt-auto pt-4">
        <div class="pt-3 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
            <span class="inline-flex items-center gap-1">
                @if ($showAuthor ?? true)
                    <x-icon name="person" :size="16" />
                    <a href="{{ route('authors.show', $post->author->id) }}" class="text-stone-700 hover:text-emerald-700 hover:underline">{{ $post->author->name }}</a>
                @else
                    <x-icon name="calendar_today" :size="16" />
                @endif
            </span>
            <span>{{ $post->published_at?->format('M d, Y') ?? 'Unpublished' }}</span>
        </div>

        <div class="mt-2 flex items-center gap-4 text-xs text-stone-500">
            <span class="inline-flex items-center gap-1">
                <x-icon name="visibility" :size="16" />
                {{ $post->views }} {{ Str::plural('view', $post->views) }}
            </span>
            <span class="inline-flex items-center gap-1">
                <x-icon name="chat_bubble" :size="16" />
                {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
            </span>
        </div>
    </div>
</article>
