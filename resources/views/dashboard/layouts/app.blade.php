<!DOCTYPE html>
@php
    $themeSettings = auth()->user()?->theme_settings ?? [];
    $primary = $themeSettings['primary_color'] ?? '#1A685B';
    $secondary = $themeSettings['secondary_color'] ?? '#FF5528';
    $accent = $themeSettings['accent_color'] ?? '#FFAC00';
    $fontFamily = $themeSettings['font_family'] ?? "'Poppins', sans-serif";
    $fontSize = $themeSettings['font_size'] ?? 'md';
    $isDark = $themeSettings['dark_mode'] ?? false;
@endphp
<html lang="en" class="font-size-{{ $fontSize }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'sndp-bag Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family:
                {!! $fontFamily !!}
            ;
            transition: all 0.3s ease;
        }

        /* Prevent flash of unstyled content during theme switch */
        .preload * {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -ms-transition: none !important;
            -o-transition: none !important;
            transition: none !important;
        }

        :root {
            --primary:
                {{ $primary }}
            ;
            --secondary:
                {{ $secondary }}
            ;
            --accent:
                {{ $accent }}
            ;
            font-size: 16px;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #1A685B 0%, #2a8875 100%);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .sidebar {
            transition: transform 0.3s ease;
        }

        .sidebar-open {
            transform: translateX(0);
        }

        .sidebar-closed {
            transform: translateX(-100%);
        }

        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }

        .nav-item {
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            transform: translateX(0);
        }

        .nav-item:hover {
            background: rgba(26, 104, 91, 0.05);
        }

        .nav-item.active {
            background: rgba(26, 104, 91, 0.1);
            color: var(--primary);
            font-weight: 600;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .dark .glass-effect {
            background: rgba(30, 41, 59, 0.95);
        }

        body.dark {
            background: #0f172a;
            color: #e2e8f0;
        }

        .dark .bg-white {
            background: #1e293b !important;
        }

        .dark .text-gray-600 {
            color: #94a3b8 !important;
        }

        .dark .text-gray-800 {
            color: #e2e8f0 !important;
        }

        .dark .border-gray-200 {
            border-color: #334155 !important;
        }

        .pulse-animate {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .color-preview {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            cursor: pointer;
            border: 3px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .color-preview:hover {
            transform: scale(1.1);
            border-color: var(--primary);
        }

        .toggle-switch {
            width: 60px;
            height: 30px;
            background: #cbd5e1;
            border-radius: 15px;
            position: relative;
            cursor: pointer;
            transition: background 0.3s;
        }

        .toggle-switch.active {
            background: var(--primary);
        }

        .toggle-slider {
            width: 26px;
            height: 26px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch.active .toggle-slider {
            transform: translateX(30px);
        }

        .breadcrumb-nav {
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #4b5563;
        }

        .dark .breadcrumb-nav {
            color: #9ca3af;
        }

        .breadcrumb-nav a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-nav a:hover {
            color: #124e44;
        }

        .dark .breadcrumb-nav a {
            color: #6ee7b7;
        }

        .breadcrumb-nav span {
            margin: 0 0.5rem;
        }

        .skeleton {
            background-color: #e5e7eb;
            border-radius: 1rem;
            position: relative;
            overflow: hidden;
        }

        .dark .skeleton {
            background-color: #334155;
        }

        .skeleton::after {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 150%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 1.5s infinite;
        }

        .dark .skeleton::after {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        }

        @keyframes shimmer {
            100% {
                left: 150%;
            }
        }

        .skeleton-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
        }

        .skeleton-text {
            height: 20px;
            border-radius: 8px;
        }

        .skeleton-title {
            height: 36px;
            border-radius: 8px;
            width: 60%;
        }

        .skeleton-bar {
            height: 8px;
            border-radius: 4px;
        }

        .font-size-sm {
            font-size: 14px;
        }

        .font-size-md {
            font-size: 16px;
        }

        .font-size-lg {
            font-size: 18px;
        }

        .font-size-btn {
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .font-size-btn.active {
            border-color: var(--primary);
            background-color: rgba(26, 104, 91, 0.1);
            color: var(--primary);
            font-weight: bold;
        }

        .activity-item {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-image-container {
            position: relative;
            width: 128px;
            height: 128px;
        }

        .profile-image-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .profile-image-container:hover .profile-image-overlay {
            opacity: 1;
        }

        #imageToCrop {
            display: block;
            max-width: 100%;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 preload">
    <script>
        (function() {
            const isDark = {!! json_encode($isDark) !!};
            const applyDark = () => document.body.classList.add('dark');
            const removeDark = () => document.body.classList.remove('dark');
            
            if (isDark === 'system') {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    applyDark();
                } else {
                    removeDark();
                }
            } else if (isDark) {
                applyDark();
            } else {
                removeDark();
            }
            
            // Allow transitions after initial paint
            setTimeout(() => {
                document.body.classList.remove('preload');
            }, 100);
        })();
    </script>
    
    @include('admin-panel::dashboard.partials.sidebar')

    <div class="md:ml-72 min-h-screen flex flex-col">
        @include('admin-panel::dashboard.partials.header')

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        @include('admin-panel::dashboard.partials.footer')
    </div>

    @include('admin-panel::dashboard.partials.cropper-modal')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="{{ asset('vendor/admin-panel/js/dashboard.js') }}"></script>

    @stack('scripts')
</body>

</html>