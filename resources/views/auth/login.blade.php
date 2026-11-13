@extends('layouts.app')

@section('title', 'Sign In - Barangay Bulletin - Purok Serra')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-200">

        <!-- Header -->
        <div class="text-center mb-6">
            <span class="text-3xl">📢</span>
            <h2 class="text-2xl font-bold text-gray-900 mt-2">Sign In</h2>
            <p class="text-sm text-gray-500 mt-1">Barangay Bulletin &bull; Purok Serra</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                <p class="font-semibold mb-1">Please correct the following errors:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" class="space-y-4">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="kagawad.serra@bulletin.test"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @else border-gray-300 @enderror"
                    required
                    autofocus
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @else border-gray-300 @enderror"
                    required
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-150 cursor-pointer shadow-sm text-sm"
            >
                Sign In
            </button>
        </form>

        <!-- Credentials Helper Box -->
        <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-500 bg-gray-50 p-3 rounded-lg space-y-1">
            <p class="font-semibold text-gray-700">Demo Accounts (Password: <code class="text-blue-600">bulletin2026</code>):</p>
            <p>&bull; <strong>Kagawad:</strong> <code class="text-gray-800">kagawad.serra@bulletin.test</code></p>
            <p>&bull; <strong>Resident:</strong> <code class="text-gray-800">lola.serra@bulletin.test</code></p>
        </div>

    </div>
</div>
@endsection
