<aside id="sidebar"
    class="sidebar flex flex-col sidebar-closed md:sidebar-open fixed left-0 top-0 h-full w-72 bg-white shadow-2xl z-50">
    <div class=" p-6 text-white" style="background-color: var(--primary);">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <button id="closeSidebar" class="md:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-sm opacity-90 mt-1">Welcome to your dashboard</p>
    </div>

    <nav
        class="p-4 flex-1 overflow-y-auto 
            [&::-webkit-scrollbar]:w-2 
            [&::-webkit-scrollbar-track]:bg-gray-100
            [&::-webkit-scrollbar-thumb]:bg-green-800
            [&::-webkit-scrollbar-thumb]:rounded-full
            dark:[&::-webkit-scrollbar-track]:bg-gray-800
            dark:[&::-webkit-scrollbar-thumb]:bg-green-600">



                @foreach (config('admin-panel.sidebar', []) as $item)
        <a href="{{ route($item['route']) }}"
           class="nav-item {{ request()->routeIs($item['active_on']) ? 'active' : '' }} flex items-center gap-4 p-4 rounded-xl mb-2 transition-all"
           tabindex="0">
            {!! $item['icon'] !!}  {{-- SVG আইকন দেখানোর জন্য !! !! ব্যবহার করা হলো --}}
            <span class="font-medium">{{ $item['title'] }}</span>
        </a>
    @endforeach



        {{-- <a href="{{ route('dashboard') }}"
            class="nav-item {{ request()->routeIs('dashboard*') ? 'active' : '' }} flex items-center gap-4 p-4 rounded-xl mb-2 transition-all"
            tabindex="0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('users.index') }}"
            class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }} flex items-center gap-4 p-4 rounded-xl mb-2 transition-all"
            tabindex="0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="font-medium">Users</span>
        </a>

        <a href="{{ route('settings.index') }}"
            class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }} flex items-center gap-4 p-4 rounded-xl mb-2 transition-all"
            tabindex="0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="font-medium">Settings</span>
        </a>


        <a href="{{ route('user-logs.index') }}"
            class="nav-item {{ request()->routeIs('user-logs.*') ? 'active' : '' }} flex items-center gap-4 p-4 rounded-xl mb-2 transition-all"
            tabindex="0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="font-medium">User Logs</span>
        </a> --}}


    </nav>

    <div class="p-4 border-t  border-gray-200">
        <div
            class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 transition cursor-pointer">
            @if (auth()->check())
                <img src="{{ auth()->user()->profile_image
                    ? asset('storage/' . auth()->user()->profile_image)
                    : asset('images/default-avatar.jpeg') }}"
                    alt="Profile"
                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 hover:border-indigo-500 cursor-pointer transition">
            @else
                <img src="{{ asset('images/default-avatar.jpeg') }}" alt="Default Profile"
                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 hover:border-indigo-500 cursor-pointer transition">
            @endif
            <div class="flex-1">
                <p class="font-semibold text-sm text-gray-800">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</aside>
