<header class="glass-effect sticky top-0 z-40 border-b border-gray-200">
    <div class="flex items-center justify-between p-4">
        <button id="menuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h2 class="text-xl font-bold text-gray-800 hidden md:block">@yield('page-title', 'Your Dashboard')</h2>

        <div class="flex items-center gap-3">
            <button id="darkModeBtn" class="p-2 rounded-lg hover:bg-gray-100 transition">
                <svg id="moonIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg id="sunIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>

            <button class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full pulse-animate"></span>
            </button>

            <div class="hidden sm:flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                  <!-- 👤 Profile Image -->
    <div class="relative">
        {{-- <img 
            src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.jpeg') }}" 
            alt="Profile"
            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 hover:border-indigo-500 cursor-pointer transition"
        > --}}

        @if(auth()->check())
    <img 
        src="{{ auth()->user()->profile_image 
            ? asset('storage/' . auth()->user()->profile_image) 
            : asset('images/default-avatar.jpeg') }}" 
        alt="Profile"
        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 hover:border-indigo-500 cursor-pointer transition"
    >
@else
    <img 
        src="{{ asset('images/default-avatar.jpeg') }}" 
        alt="Default Profile"
        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 hover:border-indigo-500 cursor-pointer transition"
    >
@endif


        <!-- Green dot if active -->
        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
    </div>
                <div>
                    @if (auth()->check())
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->status == "Admin" ? 'Admin' : 'User' }}
                        </p>
                    @else
                        <p class="text-sm font-semibold text-gray-800">Guest User</p>
                        <p class="text-xs text-gray-500">Visitor</p>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                {{-- <form method="POST" action=" " class="inline"> --}}
                @csrf
                <button type="submit" class="p-2 rounded-lg hover:bg-red-50 transition text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>
