@extends('layouts.app')

@section('title', 'Sign In - Barangay Bulletin - Purok Serra')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-sm bg-white p-7 rounded-lg border border-stone-200">

        <div class="text-center mb-6">
            <x-icon name="campaign" :size="36" class="text-emerald-700" />
            <h1 class="mt-1 text-xl font-bold text-stone-900">Sign In</h1>
            <p class="text-sm text-stone-500">Barangay Bulletin &middot; Purok Serra</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-700">
                <ul class="ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('email') border-red-500 @else border-stone-300 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('password') border-red-500 @else border-stone-300 @enderror">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-1 bg-emerald-700 hover:bg-emerald-800 text-white font-medium py-2 rounded-md text-sm cursor-pointer">
                <x-icon name="login" />
                Sign In
            </button>
        </form>

        <p class="mt-5 pt-4 border-t border-stone-100 text-xs text-stone-500 leading-relaxed">
            Test accounts (password <code>bulletin2026</code>):<br>
            <code>kagawad.serra@bulletin.test</code><br>
            <code>lola.serra@bulletin.test</code>
        </p>

    </div>
</div>
@endsection
