@extends('admin-panel::dashboard.layouts.app')

@section('title', 'User Activity Logs - sndp-bag')
@section('page-title', 'User Activity Logs')

@section('content')
    <!-- Breadcrumb with modern styling -->
    <div class="flex items-center gap-2 mb-6 text-sm">
        <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 font-medium">
            Home
        </a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 dark:text-gray-100 font-semibold">User Activity Logs</span>
    </div>

    <!-- Main Card -->
    <div class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden fade-in">
        <!-- Decorative gradient line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-500"></div>
        
        <!-- Header Section -->
        <div class="p-8 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl  flex items-center justify-center shadow-lg" style="background-color: var(--primary);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Login History</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detailed login activity from all users</p>
                </div>
            </div>
        </div>

        <!-- Stats Overview (Optional) -->
        <div class="p-8 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border-2 border-gray-200 dark:border-gray-700 hover:border-green-300 dark:hover:border-green-600 transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Successful Logins</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $logs->where('success', true)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border-2 border-gray-200 dark:border-gray-700 hover:border-red-300 dark:hover:border-red-600 transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Failed Attempts</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $logs->where('success', false)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border-2 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Activities</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $logs->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white dark:bg-gray-800  border-y border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Location & IP</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Device Details</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Login Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent dark:hover:from-blue-900/10 dark:hover:to-transparent transition-all duration-200 group">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <img src="{{ $log->user && $log->user->profile_image
                                                ? asset('storage/' . $log->user->profile_image)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($log->user ? $log->user->name : $log->email) . '&background=random&bold=true' }}"
                                                alt="Profile Photo"
                                                class="w-10 h-10 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-700 group-hover:border-blue-300 dark:group-hover:border-blue-600 transition-all duration-200 shadow-sm" />
                                            @if($log->success)
                                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-900"></div>
                                            @else
                                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-gray-900"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-500 dark:text-gray-400 text-sm">{{ $log->user->name ?? $log->email }}</p>
                                            @if($log->user)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-1">
                                        <p class="font-semibold text-gray-900 dark:text-green-500 text-sm flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $log->city ?? 'Unknown' }}, {{ $log->country ?? 'Unknown' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-mono flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            {{ $log->ip_address }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-1 text-sm">
                                        <p class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium">{{ $log->device }}</span>
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2 pl-6">
                                            OS: {{ $log->platform }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2 pl-6">
                                            Browser: {{ $log->browser }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($log->success)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Success
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->login_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->login_at->format('h:i A') }} • {{ $log->login_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-full flex items-center justify-center border-4 border-blue-50 dark:border-blue-900/30">
                                            <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-1">No Activity Yet</p>
                                            <p class="text-gray-500 dark:text-gray-400">No login activity recorded yet.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Section -->
        @if ($logs->hasPages())
            <div class="p-8 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection