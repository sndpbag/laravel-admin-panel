@extends('admin-panel::layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-lg">
    <div>
        <h1 class="text-2xl font-bold text-center text-gray-800">Forgot Your Password?</h1>
        <p class="mt-2 text-sm text-center text-gray-600">
            No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </p>
    </div>

    <!-- Session Status Message -->
    @if (session('status'))
        <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 mt-1 text-gray-700 bg-gray-100 border border-gray-200 rounded-xl focus:ring-primary focus:ring-2 focus:bg-white focus:border-transparent transition">
        </div>

        <div>
            <button type="submit" class="w-full px-6 py-3 text-lg font-semibold text-white rounded-xl hover:shadow-lg transition" style="background-color: var(--primary);">
                Email Password Reset Link
            </button>
        </div>

        <div class="text-sm text-center">
            <p class="text-gray-600">Remembered your password?
                <a href="{{ route('login') }}" class="font-semibold" style="color: var(--primary);">
                    Back to Login
                </a>
            </p>
        </div>
    </form>
</div>
@endsection
