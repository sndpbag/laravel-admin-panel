@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Dashboard - sndp-bag')
@section('page-title', 'Your Dashboard')

@section('content')
<div class="breadcrumb-nav">
    <a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Dashboard</span>
</div>

<div id="skeletonLoader" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @for($i = 0; $i < 4; $i++)
    <div class="skeleton p-6 h-[188px] space-y-4">
        <div class="flex items-center justify-between">
            <div class="skeleton skeleton-icon"></div>
        </div>
        <div class="skeleton skeleton-title"></div>
        <div class="skeleton skeleton-text w-1/2"></div>
        <div class="skeleton skeleton-bar"></div>
    </div>
    @endfor
</div>

<div id="actualContent" class="hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="card-hover bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-icon" style="background: rgba(26, 104, 91, 0.1);">
                    <span style="color: var(--primary);">👥</span>
                </div>
                <span class="text-sm font-semibold px-3 py-1 rounded-full" style="background: rgba(26, 104, 91, 0.1); color: var(--primary);">+{{ $stats['users_growth'] }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['total_users']) }}</h3>
            <p class="text-gray-600">Total Users</p>
            <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width: 75%; background: var(--primary);"></div>
            </div>
        </div>
        
        <!-- Total Views -->
        <div class="card-hover bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-icon" style="background: rgba(255, 85, 40, 0.1);">
                    <span style="color: var(--secondary);">📊</span>
                </div>
                <span class="text-sm font-semibold px-3 py-1 rounded-full" style="background: rgba(255, 85, 40, 0.1); color: var(--secondary);">+{{ $stats['views_growth'] }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['total_views']) }}</h3>
            <p class="text-gray-600">Total Views</p>
            <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width: 60%; background: var(--secondary);"></div>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="card-hover bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-icon" style="background: rgba(255, 172, 0, 0.1);">
                    <span style="color: var(--accent);">💰</span>
                </div>
                <span class="text-sm font-semibold px-3 py-1 rounded-full" style="background: rgba(255, 172, 0, 0.1); color: var(--accent);">+{{ $stats['revenue_growth'] }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">Rs.{{ number_format($stats['total_revenue']) }}</h3>
            <p class="text-gray-600">Total Revenue</p>
            <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width: 85%; background: var(--accent);"></div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="card-hover bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-icon" style="background: rgba(26, 104, 91, 0.1);">
                    <span style="color: var(--primary);">🛍️</span>
                </div>
                <span class="text-sm font-semibold px-3 py-1 rounded-full" style="background: rgba(26, 104, 91, 0.1); color: var(--primary);">+{{ $stats['orders_growth'] }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['total_orders']) }}</h3>
            <p class="text-gray-600">Total Orders</p>
            <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width: 70%; background: var(--primary);"></div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Recent Activity</h3>
            <div class="space-y-4">
                @foreach($recentActivities as $index => $activity)
                <div class="activity-item flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer" style="animation-delay: {{ ($index + 1) * 100 }}ms;">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl" style="background: {{ $activity['bg_color'] }};">
                        {{ $activity['icon'] }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $activity['time'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('users.create') }}" class="p-6 rounded-xl text-center hover:shadow-lg transition" style="background: rgba(26, 104, 91, 0.1);" tabindex="0">
                    <div class="text-4xl mb-3">➕</div>
                    <p class="font-semibold" style="color: var(--primary);">New User</p>
                </a>
                <button class="p-6 rounded-xl text-center hover:shadow-lg transition" style="background: rgba(255, 85, 40, 0.1);" tabindex="0">
                    <div class="text-4xl mb-3">📝</div>
                    <p class="font-semibold" style="color: var(--secondary);">New Post</p>
                </button>
                <button class="p-6 rounded-xl text-center hover:shadow-lg transition" style="background: rgba(255, 172, 0, 0.1);" tabindex="0">
                    <div class="text-4xl mb-3">📊</div>
                    <p class="font-semibold" style="color: var(--accent);">View Reports</p>
                </button>
                <a href="{{ route('settings.index') }}" class="p-6 rounded-xl text-center hover:shadow-lg transition" style="background: rgba(26, 104, 91, 0.1);" tabindex="0">
                    <div class="text-4xl mb-3">⚙️</div>
                    <p class="font-semibold" style="color: var(--primary);">Settings</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection