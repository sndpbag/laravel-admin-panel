@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Security Check')
@section('page-title', 'Roles Management - Security Check')

@section('content')
    <div class="flex items-center justify-center py-8">
        <div class="w-full max-w-md">
            <!-- Security Check Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border-2 border-gray-100 dark:border-gray-700 overflow-hidden backdrop-blur-xl">

                <!-- Header with Icon -->
                <div
                    class="relative bg-gradient-to-br from-emerald-500 to-teal-600 dark:from-emerald-600 dark:to-teal-700 p-6 text-center">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative">
                        <!-- Lock Icon with Glow Effect -->
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-white/20 backdrop-blur-sm shadow-xl border-2 border-white/30">
                            <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-1 drop-shadow-md">Security Check</h2>
                        <p class="text-emerald-50 text-sm">Please enter your security key to access this area.</p>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="p-6">
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg animate-shake">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="font-semibold text-red-800 dark:text-red-300">Invalid Security Key</p>
                                    <p class="text-sm text-red-700 dark:text-red-400 mt-1">
                                        {{ $errors->first('security_password') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Security Password Form -->
                    <form action="{{ route('roles.security.verify') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="security_password"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Security Key
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <input type="password" name="security_password" id="security_password" required autofocus
                                    placeholder="Enter your security key"
                                    class="w-full bg-white dark:bg-gray-800 pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/20 transition-all duration-200 font-mono">
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                This key is required to manage roles and permissions
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                            <span>Unlock Access</span>
                        </button>
                    </form>

                    <!-- Back to Dashboard Link -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group">
                            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Note -->
            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Protected Area</p>
                        <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">This security layer adds an extra level of
                            protection for sensitive role management operations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>
@endsection