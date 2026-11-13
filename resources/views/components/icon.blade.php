@props(['name', 'size' => 18])

<span {{ $attributes->merge(['class' => 'material-symbols-outlined']) }} style="font-size: {{ $size }}px" aria-hidden="true">{{ $name }}</span>
