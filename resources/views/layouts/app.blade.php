<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Barangay Bulletin - Purok Serra')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col font-sans antialiased">

<!-- Top Navigation Bar -->
<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">

        <!-- App Brand & Navigation Links -->
        <div class="flex items-center space-x-6">
            <a href="{{ route('posts.index') }}" class="text-xl font-bold text-blue-600 hover:text-blue-700 flex items-center gap-2">
                <span>📢</span>
                <span>Barangay Bulletin &ndash; Purok Serra</span>
            </a>
        </div>

        <!-- Auth Status & Links -->
        <div class="flex items-center space-x-4 text-sm">
            @auth
                @if (auth()->user()->author)
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-medium transition shadow-xs text-xs sm:text-sm">
                        <span>+</span>
                        <span>New Post</span>
                    </a>
                @endif

                <span class="text-gray-600">
                    Hi, <strong class="text-gray-900">{{ auth()->user()->name }}</strong>
                </span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium underline cursor-pointer">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-medium transition shadow-xs">
                    Sign in
                </a>
            @endauth
        </div>

    </div>
</nav>

<!-- Page Content Container -->
<main class="max-w-6xl mx-auto px-4 py-8 flex-1 w-full">
    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 flex items-center justify-between text-sm shadow-xs">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<!-- Footer -->
<footer class="mt-auto border-t border-gray-200 bg-white py-6 text-center text-sm text-gray-500">
    <div class="max-w-6xl mx-auto px-4">
        <p>&copy; {{ date('Y') }} Barangay Matina Crossing &mdash; Davao City. All rights reserved.</p>
        <p class="text-xs text-gray-400 mt-1">Barangay Bulletin &bull; Purok Serra Online Information Desk</p>
    </div>
</footer>

</body>
</html>
