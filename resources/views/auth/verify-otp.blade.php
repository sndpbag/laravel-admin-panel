@extends('admin-panel::layouts.guest')
@section('title', 'Verify OTP')
@section('content')
<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-lg">
    <div>
        <h1 class="text-3xl font-bold text-center text-gray-800">Enter OTP</h1>
        <p class="mt-2 text-sm text-center text-gray-600">
            We've sent a 6-digit code to: <strong>{{ session('otp_email') }}</strong>
        </p>
    </div>

    @if ($errors->any())
        <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form class="space-y-6" method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div>
            <label for="otp" class="block text-sm font-semibold text-gray-700">One-Time Password (OTP)</label>
            <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" required autofocus
                   class="w-full px-4 py-3 mt-1 text-gray-700 bg-gray-100 rounded-xl focus:ring-primary text-center text-2xl tracking-[1rem]">
        </div>
        <div>
            <button type="submit" class="w-full px-6 py-3 text-lg font-semibold text-white rounded-xl" style="background-color: var(--primary);">
                Verify & Login
            </button>
        </div>
    </form>
</div>
@endsection
