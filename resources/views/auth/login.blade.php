@extends('admin-panel::layouts.guest')
@section('title', 'Login')
@section('page-heading', 'Sign in to your account')

@section('content')
    {{-- Global Error Container --}}
    <div id="global-error"
        class="hidden mb-6 p-4 rounded-xl bg-red-50 border-l-4 border-red-500 shadow-sm transition-all duration-300">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-bold text-red-800">Login Failed</span>
        </div>
        <p id="global-error-text" class="text-sm text-red-600"></p>
    </div>

    {{-- Lockout Overlay/Box --}}
    <div id="lockout-box"
        class="hidden relative overflow-hidden bg-red-600 text-white rounded-2xl shadow-2xl p-6 text-center mb-6 animate-pulse">
        <div class="absolute inset-0 bg-red-700 opacity-50"></div>
        <div class="relative z-10">
            <div class="text-xs uppercase font-bold tracking-widest opacity-90 mb-2">Security Lockout</div>
            <div class="text-5xl font-black tabular-nums tracking-tighter" id="lockout-timer">0</div>
            <div class="text-xs mt-2 font-medium opacity-90">Seconds remaining</div>
            <p class="text-[10px] mt-4 opacity-75">Too many incorrect attempts. Detector triggered.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5 relative">
        @csrf

        {{-- Loading Overlay --}}
        <div id="loading-overlay"
            class="hidden absolute inset-0 bg-white/70 backdrop-blur-[2px] z-50 flex flex-col items-center justify-center rounded-xl">
            <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <p class="mt-3 text-xs font-bold text-primary animate-pulse">Authenticating...</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Email Address</label>
            <input type="email" name="email" id="email" required autofocus
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                placeholder="Enter your email">
            <p class="input-error text-xs text-red-500 mt-1 hidden" id="error-email"></p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Password</label>
            <input type="password" name="password" id="password" required
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                placeholder="Enter code here">
            <p class="input-error text-xs text-red-500 mt-1 hidden" id="error-password"></p>
        </div>

        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 shadow-inner">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Security
                Verification</label>

            <div class="flex flex-col space-y-3">
                <div class="flex items-center gap-2 bg-white p-2 rounded-xl border">
                    <div class="flex-1 flex justify-center py-1">
                        <img src="{{ route('captcha.generate') }}" id="captcha-img" class="h-12 object-contain"
                            style="min-width: 150px;">
                    </div>
                    <button type="button" onclick="refreshCaptcha()"
                        class="p-3 bg-gray-100 hover:bg-primary hover:text-white rounded-lg transition-all shadow-sm"
                        title="Reload Code">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </button>
                </div>

                <input type="text" name="captcha" id="captcha" placeholder="Enter code here" required
                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent text-center text-lg font-mono tracking-widest">
            </div>
            <p class="input-error text-xs text-red-500 mt-1 hidden text-center" id="error-captcha"></p>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-primary hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" id="submit-btn"
            class="w-full py-4 rounded-xl shadow-lg text-sm font-bold text-white gradient-bg hover:opacity-90 transition-all transform active:scale-[0.98]">
            SECURE LOGIN
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-600">
        Not a member?
        <a href="{{ route('register') }}" class="font-bold text-primary hover:underline">
            Register now
        </a>
    </p>

    <script>
        function refreshCaptcha() {
            const img = document.getElementById('captcha-img');
            img.style.opacity = '0.5';
            img.src = '{{ route("captcha.generate") }}?' + Math.random();
            const captchaInput = document.getElementById('captcha');
            if (captchaInput) captchaInput.value = ''; // Clear input on refresh

            img.onload = () => img.style.opacity = '1';
        }

        document.getElementById('login-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Elements
            const form = this;
            const submitBtn = document.getElementById('submit-btn');
            const loadingOverlay = document.getElementById('loading-overlay');
            const globalError = document.getElementById('global-error');
            const globalErrorText = document.getElementById('global-error-text');
            const lockoutBox = document.getElementById('lockout-box');
            const lockoutTimer = document.getElementById('lockout-timer');

            // Reset UI
            loadingOverlay.classList.remove('hidden');
            submitBtn.disabled = true;
            globalError.classList.add('hidden');
            document.querySelectorAll('.input-error').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('input').forEach(el => el.classList.remove('border-red-500'));

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch("{{ route('login') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    // Success (Redirect)
                    window.location.href = result.redirect_url || '/dashboard';
                } else if (response.status === 422) {
                    // Validation Errors
                    loadingOverlay.classList.add('hidden');
                    submitBtn.disabled = false;
                    refreshCaptcha(); // Security best practice

                    // Check for lockout specific error
                    if (result.errors.seconds_left) {
                        startLockout(result.errors.seconds_left[0] || result.errors.seconds_left);
                        return;
                    }

                    // Display Field Errors
                    if (result.errors) {
                        for (const [key, messages] of Object.entries(result.errors)) {
                            const errorEl = document.getElementById(`error-${key}`);
                            const inputEl = document.getElementById(key);
                            if (errorEl) {
                                errorEl.textContent = messages[0]; // Show first error
                                errorEl.classList.remove('hidden');
                            }
                            if (inputEl) {
                                inputEl.classList.add('border-red-500');
                                inputEl.classList.add('shake'); // Add shake animation class if exists
                                setTimeout(() => inputEl.classList.remove('shake'), 500);
                            }
                        }
                    }

                    // Global fallback
                    if (!result.errors || Object.keys(result.errors).length === 0) {
                        showGlobalError(result.message || 'Login failed. Please check your credentials.');
                    }

                } else if (response.status === 429) {
                    // Rate Limited (Too Many Attempts)
                    loadingOverlay.classList.add('hidden');
                    // Try to parse seconds from header or body
                    const retryAfter = response.headers.get('Retry-After');
                    startLockout(retryAfter || 60);

                } else {
                    // Other Errors (500, etc)
                    throw new Error(result.message || 'Something went wrong. Please try again.');
                }
            } catch (error) {
                loadingOverlay.classList.add('hidden');
                submitBtn.disabled = false;
                console.error('Login Error:', error);

                // If it was a 422 that was handled above, we shouldn't get here unless JSON parsing failed
                // But generally catch network errors
                showGlobalError("Unable to connect to the server. Please check your internet connection.");
            }
        });

        function showGlobalError(msg) {
            const globalError = document.getElementById('global-error');
            const globalErrorText = document.getElementById('global-error-text');
            globalErrorText.textContent = msg;
            globalError.classList.remove('hidden');
        }

        function startLockout(seconds) {
            const lockoutBox = document.getElementById('lockout-box');
            const lockoutTimer = document.getElementById('lockout-timer');
            const form = document.getElementById('login-form');
            const submitBtn = document.getElementById('submit-btn');

            // Show lockout, hide form opacity
            lockoutBox.classList.remove('hidden');
            form.classList.add('opacity-50', 'pointer-events-none');
            submitBtn.disabled = true;

            let timeLeft = parseInt(seconds);
            lockoutTimer.innerText = timeLeft;

            const countdown = setInterval(() => {
                timeLeft--;
                lockoutTimer.innerText = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    // Unlock
                    lockoutBox.classList.add('hidden');
                    form.classList.remove('opacity-50', 'pointer-events-none');
                    submitBtn.disabled = false;
                    refreshCaptcha();
                }
            }, 1000);
        }
    </script>

    <style>
        .shake {
            animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
        }

        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
        }
    </style>
@endsection