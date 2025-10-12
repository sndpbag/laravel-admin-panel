@extends('admin-panel::layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-lg">
    <div>
        <h1 class="text-2xl font-bold text-center text-gray-800">Set a New Password</h1>
        <p class="mt-2 text-sm text-center text-gray-600">
            Please enter your new password below. Make sure it's a strong one!
        </p>
    </div>

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

    <form class="space-y-6" method="POST" action="{{ route('password.update') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus
                   class="w-full px-4 py-3 mt-1 text-gray-700 bg-gray-100 border border-gray-200 rounded-xl focus:ring-primary focus:ring-2 focus:bg-white focus:border-transparent transition">
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700">New Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full px-4 py-3 mt-1 text-gray-700 bg-gray-100 border border-gray-200 rounded-xl focus:ring-primary focus:ring-2 focus:bg-white focus:border-transparent transition">
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm New Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full px-4 py-3 mt-1 text-gray-700 bg-gray-100 border border-gray-200 rounded-xl focus:ring-primary focus:ring-2 focus:bg-white focus:border-transparent transition">
        </div>

        <div>
            <button type="submit" class="w-full px-6 py-3 text-lg font-semibold text-white rounded-xl hover:shadow-lg transition" style="background-color: var(--primary);">
                Reset Password
            </button>
        </div>
    </form>
</div>
@endsection
