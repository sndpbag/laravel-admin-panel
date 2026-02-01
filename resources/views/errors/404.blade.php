@extends('admin-panel::dashboard.layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center space-y-8">
            <div class="relative">
                <!-- Custom Image for 404 -->
                <img src="{{ asset('images/404-baby.png') }}" alt="404 Page Not Found"
                    class="mx-auto h-64 object-contain rounded-xl shadow-lg animate-float">
            </div>

            <div class="fade-in-up">
                <h2 class="mt-6 text-4xl font-extrabold text-gray-900 tracking-tight">
                    Oops! Desired Route Not Found
                </h2>
                <p class="mt-2 text-lg text-gray-600">
                    The page you are looking for disappeared.
                </p>
                <p class="mt-1 text-sm text-gray-400">
                    It seems the link you clicked is broken or the page has been moved.
                </p>
            </div>

            <div class="mt-8">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Go Back Home
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
    </style>
@endsection