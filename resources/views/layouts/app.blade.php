<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Barangay Bulletin - Purok Serra')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0..1,0&display=block">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800 min-h-screen flex flex-col font-sans antialiased">

<header class="bg-emerald-800 text-white">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between gap-4">
        <a href="{{ route('posts.index') }}" class="flex items-center gap-2 font-semibold tracking-tight">
            <x-icon name="campaign" :size="22" />
            <span>Barangay Bulletin <span class="font-normal text-emerald-200">/ Purok Serra</span></span>
        </a>

        <nav class="flex items-center gap-3 text-sm">
            @auth
                @if (auth()->user()->author)
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-1 bg-white text-emerald-800 hover:bg-emerald-50 px-3 py-1.5 rounded-md font-medium">
                        <x-icon name="edit_square" />
                        <span>New Post</span>
                    </a>
                @endif

                <span class="hidden sm:inline-flex items-center gap-1 text-emerald-100">
                    <x-icon name="account_circle" />
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white cursor-pointer" title="Logout">
                        <x-icon name="logout" />
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1 bg-white text-emerald-800 hover:bg-emerald-50 px-3 py-1.5 rounded-md font-medium">
                    <x-icon name="login" />
                    <span>Sign in</span>
                </a>
            @endauth
        </nav>
    </div>
</header>

<main class="max-w-5xl mx-auto px-4 py-8 flex-1 w-full">
    @if (session('status'))
        <div class="mb-6 px-4 py-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2 text-sm">
            <x-icon name="check_circle" />
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @yield('content')
</main>

<footer class="border-t border-stone-200 py-5 text-center text-xs text-stone-500">
    Barangay Matina Crossing, Davao City &middot; Purok Serra
</footer>

</body>
</html>
