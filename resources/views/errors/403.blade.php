<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .lock-animation {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-lg w-full text-center">
        <div class="mb-8 relative z-10">
            <div
                class="w-40 h-40 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 lock-animation shadow-xl ring-8 ring-red-50">
                <svg class="w-20 h-20 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-red-400 opacity-20 filter blur-3xl -z-10 rounded-full">
            </div>
        </div>

        <h1 class="text-6xl font-bold text-gray-900 mb-2">403</h1>
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Access Restricted</h2>
        <p class="text-gray-600 mb-8 text-lg">
            Sorry, you don't have permission to access this area.<br>
            Please contact your administrator if you believe this is a mistake.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/') }}"
                class="w-full sm:w-auto px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-200 transform hover:-translate-y-0.5">
                Go Home
            </a>
            <button onclick="history.back()"
                class="w-full sm:w-auto px-8 py-3 bg-white text-gray-700 border border-gray-200 font-semibold rounded-xl hover:bg-gray-50 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                Go Back
            </button>
        </div>

        <div class="mt-12 text-sm text-gray-400">
            Error Code: 403 FORBIDDEN
        </div>
    </div>
</body>

</html>