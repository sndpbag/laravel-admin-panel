<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - sndp-bag</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --primary: {{ session('theme.primary_color', '#1A685B') }};
            --secondary: {{ session('theme.secondary_color', '#FF5528') }};
            --accent: {{ session('theme.accent_color', '#FFAC00') }};
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--primary) 0%, #2a8875 100%);
        }

        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 104, 91, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold" style="color: var(--primary);">sndp-bag</h1>
            <p class="text-gray-600">@yield('page-heading')</p>
        </div>

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            @yield('content')
        </div>

    </div>

</body>
</html>
