@extends('admin-panel::layouts.guest')

@section('title', 'Verify Your Email')

@section('content')
<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-lg">
    <div>
        <h1 class="text-2xl font-bold text-center text-gray-800">Verify Your Email Address</h1>
        <p class="mt-2 text-sm text-center text-gray-600">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="flex items-center justify-between mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full px-6 py-3 text-lg font-semibold text-white rounded-xl hover:shadow-lg transition" style="background-color: var(--primary);">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                Log Out
            </button>
        </form>
    </div>
</div>
@endsection
