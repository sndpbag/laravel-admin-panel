@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Users - sndp-bag Dashboard')
@section('page-title', 'User Management')

@section('content')
    <!-- Breadcrumb with modern styling -->
    <div class="flex items-center gap-2 mb-6 text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-gray-600 hover:text-indigo-600 transition-colors duration-200 font-medium">
            Home
        </a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-900 font-semibold">Users</span>
    </div>

    <!-- Main Card with gradient border effect -->
    <div class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden fade-in">
        <!-- Decorative gradient line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

        <!-- Header Section -->
        <div class="p-8 border-b border-gray-100 dark:border-gray-800">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">User Management</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage and monitor all system users</p>
                </div>

                <div class="flex flex-col md:flex-row gap-2">
                    @can('users.create')
                        <a href="{{ route('users.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                            style="background: linear-gradient(135deg, var(--primary));">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add New User
                        </a>
                    @endcan

                    {{-- tarsh --}}
                    @can('users.trashed')
                        <a href="{{ route('users.trashed') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-100 font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            View Trash
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Success Message with icon -->
        @if (session('success'))
            <div
                class="mx-8 mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl shadow-sm animate-fade-in">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters Section -->
        <div class="p-8 bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900">
            <form action="{{ route('users.index') }}" method="GET" class="space-y-6">
                <!-- Search and Filters Row -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Search Input with icon -->
                    <div class="md:col-span-6 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Search by name or email..."
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm dark:text-gray-100"
                            value="{{ request('search') }}">
                    </div>

                    <!-- Status Select -->
                    <div class="md:col-span-3 relative">
                        <select name="status"
                            class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm appearance-none cursor-pointer dark:text-gray-100">
                            <option value="all">All Statuses</option>
                            <option value="Active" @if (request('status') == 'Active') selected @endif>Active</option>
                            <option value="Inactive" @if (request('status') == 'Inactive') selected @endif>Inactive</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Role Select -->
                    <div class="md:col-span-3 relative">
                        <select name="role"
                            class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm appearance-none cursor-pointer dark:text-gray-100">
                            <option value="all">All Roles</option>
                            <option value="Admin" @if (request('role') == 'Admin') selected @endif>Admin</option>
                            <option value="User" @if (request('role') == 'User') selected @endif>User</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <!-- Export Options -->
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-semibold text-gray-700 dark:text-gray-300 text-sm">Export:</span>
                        <a href="{{ route('users.export', ['type' => 'pdf'] + request()->query()) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl bg-red-50 text-red-600 hover:bg-red-100 font-medium transition-all duration-200 shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            PDF
                        </a>
                        <a href="{{ route('users.export', ['type' => 'xlsx'] + request()->query()) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl bg-green-50 text-green-600 hover:bg-green-100 font-medium transition-all duration-200 shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Excel
                        </a>
                        <a href="{{ route('users.export', ['type' => 'csv'] + request()->query()) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 font-medium transition-all duration-200 shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            CSV
                        </a>

                        <div class="h-6 w-px bg-gray-300"></div>

                        @can('users.import')
                            <button type="button" id="openImportModalBtn"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium transition-all duration-200 shadow-sm hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import
                            </button>
                        @endcan
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex gap-3">
                        <a href="{{ route('users.index') }}"
                            class="px-6 py-3 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold transition-all duration-200 shadow-sm hover:shadow">
                            Clear Filters
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                            style="background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section with improved design -->
        <div class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 border-y border-gray-200 dark:border-gray-700">
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                User Info</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Contact</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @forelse($users as $user)
                            <tr
                                class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent dark:hover:from-gray-800 dark:hover:to-transparent transition-all duration-200 group">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <img class="w-10 h-10 rounded-xl object-cover ring-2 ring-gray-100 group-hover:ring-indigo-200 transition-all duration-200 shadow-sm"
                                                src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&bold=true' }}"
                                                alt="{{ $user->name }}">
                                            <div
                                                class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white">
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">ID:
                                                #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="space-y-0.5">
                                        <p class="text-gray-700 text-sm font-medium flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $user->email }}
                                        </p>
                                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $user->phone ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-wrap gap-2">
                                        @can('users.role.update')
                                            @foreach ($roles as $role)
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input type="checkbox" value="{{ $role->slug }}"
                                                        onchange="updateUserRole(this, '{{ $user->id }}')"
                                                        class="role-checkbox-{{ $user->id }} form-checkbox w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 transition duration-150 ease-in-out"
                                                        {{ $user->hasRole($role->slug) ? 'checked' : '' }}>
                                                    <span
                                                        class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $role->slug }}</span>
                                                </label>
                                            @endforeach
                                        @else
                                            @foreach ($user->roles as $role)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                    {{ $role->slug }}
                                                </span>
                                            @endforeach
                                        @endcan
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center gap-1.5 w-24 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-300 shadow-sm hover:shadow transform hover:-translate-y-0.5
                                                                                                                                                                                                                    @if ($user->status == 'Active') bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 hover:from-green-100 hover:to-emerald-100 border border-green-200"
                                                                                                                                                                                                                    @else bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 hover:from-gray-200
                                            hover:to-gray-300 border border-gray-300" @endif>
                                            @if ($user->status == 'Active')
                                                <span
                                                    class="w-1.5
                                                                                                                                                                                                                                                                                        h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                            @else
                                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                            @endif
                                            {{ $user->status }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-1.5">
                                        @can('users.edit')
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-all duration-200 font-semibold text-xs shadow-sm hover:shadow transform hover:-translate-y-0.5 border border-blue-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                        @endcan

                                        @can('users.permissions.update')
                                            <button type="button"
                                                onclick="openPermissionsModal('{{ $user->id }}', '{{ $user->name }}', {{ json_encode($user->permissions->pluck('id')) }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-purple-600 bg-purple-50 hover:bg-purple-100 transition-all duration-200 font-semibold text-xs shadow-sm hover:shadow transform hover:-translate-y-0.5 border border-purple-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                                Perms
                                            </button>
                                        @endcan

                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            @can('users.destroy')
                                                <button type="button"
                                                    class="delete-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 transition-all duration-200 font-semibold text-xs shadow-sm hover:shadow transform hover:-translate-y-0.5 border border-red-200"
                                                    data-form-id="delete-form-{{ $user->id }}">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            @endcan
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-xl text-gray-900 mb-1">No Users Found</p>
                                            <p class="text-gray-500">Try adjusting your filters or add a new user to get
                                                started.</p>
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
        <div class="p-8 border-t border-gray-100 bg-gray-50">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

    @include('admin-panel::dashboard.partials.delete_modal')
    @include('admin-panel::dashboard.partials.import_modal')

    <!-- Role Update Confirmation Modal -->
    <div id="roleConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 transform transition-all">
            <div class="p-8">
                <div class="text-center">
                    {{-- Icon --}}
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100">
                        <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                    {{-- Content --}}
                    <h3 class="mt-5 text-2xl font-bold text-gray-900" id="role-modal-title">Confirm Role Change</h3>
                    <div class="mt-2">
                        <p class="text-gray-600">Are you sure you want to update the roles for this user?</p>
                    </div>
                </div>
            </div>
            {{-- Action Buttons --}}
            <div class="bg-gray-50 rounded-b-2xl px-6 py-4 flex justify-end gap-3">
                <button type="button" id="cancelRoleModalBtn"
                    class="px-6 py-3 rounded-xl text-gray-700 bg-gray-200 hover:bg-gray-300 font-semibold transition">
                    No, Cancel
                </button>
                <button type="button" id="confirmRoleBtn"
                    class="px-6 py-3 rounded-xl text-white font-semibold hover:shadow-lg transition bg-indigo-600 hover:bg-indigo-700">
                    Yes, Update
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="roleSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 transform transition-all">
            <div class="p-8">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-2xl font-bold text-gray-900">Success!</h3>
                    <div class="mt-2">
                        <p class="text-gray-600" id="roleSuccessMessage">Roles updated successfully.</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-b-2xl px-6 py-4 flex justify-center">
                <button type="button" onclick="document.getElementById('roleSuccessModal').classList.add('hidden')"
                    class="px-6 py-3 rounded-xl text-white font-semibold hover:shadow-lg transition bg-green-600 hover:bg-green-700 w-full">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Permissions Modal -->
    <div id="permissionsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"
                onclick="closePermissionsModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 dark:bg-gray-800">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-gray-100" id="modal-title">Manage
                        Permissions</h3>
                    <button type="button" onclick="closePermissionsModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Managing Direct Permissions
                    for: <span id="permissionUserName" class="font-bold text-gray-900 dark:text-gray-200"></span></p>
                <form id="permissionsForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-96 overflow-y-auto p-2">
                        @foreach($permissions as $permission)
                            <label
                                class="flex items-center space-x-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700 transition cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    class="permission-checkbox form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 select-none">
                                    {{ $permission->name }}
                                    <span class="block text-xs text-gray-400 font-normal">{{ $permission->slug }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-6 sm:mt-8 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all">Save
                            Changes</button>
                        <button type="button" onclick="closePermissionsModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openPermissionsModal(userId, userName, userPermissions) {
                const modal = document.getElementById('permissionsModal');
                const form = document.getElementById('permissionsForm');
                const userNameSpan = document.getElementById('permissionUserName');
                const checkboxes = document.querySelectorAll('.permission-checkbox');

                userNameSpan.textContent = userName;
                // Correctly construct the URL using JS string interpolation with the base route
                // We use a base URL and append the ID
                const baseUrl = "{{ route('users.index') }}";
                // Remove any query params if present in base url (though route() usually doesn't add them unless specified)
                // A safer way is using a named route pattern or replacing a placeholder
                // Let's assume standard resource structure: /users/{id}/permissions
                form.action = `${baseUrl}/${userId}/permissions`;

                checkboxes.forEach(cb => cb.checked = false);
                if (userPermissions && Array.isArray(userPermissions)) {
                    userPermissions.forEach(permId => {
                        const checkbox = document.querySelector(`.permission-checkbox[value="${permId}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }
                modal.classList.remove('hidden');
            }

            function closePermissionsModal() {
                document.getElementById('permissionsModal').classList.add('hidden');
            }

            let pendingRoleUpdate = null;

            function updateUserRole(checkboxElement, userId) {
                // Store state for confirmation
                pendingRoleUpdate = {
                    checkboxElement: checkboxElement,
                    userId: userId,
                    wasChecked: checkboxElement.checked
                };

                // Show confirmation modal
                document.getElementById('roleConfirmModal').classList.remove('hidden');
            }

            document.addEventListener('DOMContentLoaded', function () {
                const roleModal = document.getElementById('roleConfirmModal');
                const cancelRoleBtn = document.getElementById('cancelRoleModalBtn');
                const confirmRoleBtn = document.getElementById('confirmRoleBtn');

                // Cancel button
                cancelRoleBtn.addEventListener('click', function () {
                    roleModal.classList.add('hidden');
                    // Revert checkbox state
                    if (pendingRoleUpdate) {
                        pendingRoleUpdate.checkboxElement.checked = !pendingRoleUpdate.wasChecked;
                        pendingRoleUpdate = null;
                    }
                });

                // Confirm button
                if (confirmRoleBtn) {
                    confirmRoleBtn.addEventListener('click', async function () {
                        if (!pendingRoleUpdate) return;

                        const userId = pendingRoleUpdate.userId;
                        const checkboxes = document.querySelectorAll(`.role-checkbox-${userId}:checked`);
                        const selectedRoles = Array.from(checkboxes).map(cb => cb.value);
                        const allCheckboxes = document.querySelectorAll(`.role-checkbox-${userId}`);

                        roleModal.classList.add('hidden');

                        // Disable checkboxes during update
                        allCheckboxes.forEach(cb => cb.disabled = true);

                        try {
                            const url = "{{ route('users.role.update', ':id') }}".replace(':id', userId);
                            const response = await fetch(url, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ roles: selectedRoles })
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Show Success Modal
                                document.getElementById('roleSuccessMessage').textContent = data.message;
                                document.getElementById('roleSuccessModal').classList.remove('hidden');
                            } else {
                                throw new Error(data.message || 'Failed to update roles');
                            }
                        } catch (error) {
                            console.error('Error updating roles:', error);
                            alert('Failed to update: ' + error.message);
                            // Revert on error
                            if (pendingRoleUpdate) {
                                pendingRoleUpdate.checkboxElement.checked = !pendingRoleUpdate.wasChecked;
                            }
                        } finally {
                            allCheckboxes.forEach(cb => cb.disabled = false);
                            pendingRoleUpdate = null;
                        }
                    });
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Get all the necessary elements from the modal
                const deleteModal = document.getElementById('deleteModal');
                const cancelModalBtn = document.getElementById('cancelModalBtn');
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

                // Get all buttons with the .delete-btn class
                const deleteButtons = document.querySelectorAll('.delete-btn');

                // This variable will hold the ID of the form to be submitted
                let formToSubmit = null;

                // Add a click listener to each delete button on the page
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        // Get the form ID from the button's data-form-id attribute
                        formToSubmit = this.getAttribute('data-form-id');
                        // Show the modal
                        deleteModal.classList.remove('hidden');
                    });
                });

                // When the "Yes, Delete" button inside the modal is clicked
                confirmDeleteBtn.addEventListener('click', function () {
                    if (formToSubmit) {
                        // Find the form with that ID and submit it
                        document.getElementById(formToSubmit).submit();
                    }
                });

                // When the "Cancel" button is clicked, just hide the modal
                cancelModalBtn.addEventListener('click', function () {
                    deleteModal.classList.add('hidden');
                });

                // Also allow closing the modal by clicking on the background
                deleteModal.addEventListener('click', function (event) {
                    if (event.target === deleteModal) {
                        deleteModal.classList.add('hidden');
                    }
                });


                // --- Import Modal Logic ---
                const importModal = document.getElementById('importModal');
                const openImportModalBtn = document.getElementById('openImportModalBtn');
                const cancelImportBtn = document.getElementById('cancelImportBtn');

                if (openImportModalBtn) {
                    openImportModalBtn.addEventListener('click', () => {
                        importModal.classList.remove('hidden');
                    });
                }

                if (cancelImportBtn) {
                    cancelImportBtn.addEventListener('click', () => {
                        importModal.classList.add('hidden');
                    });
                }

                if (importModal) {
                    importModal.addEventListener('click', (event) => {
                        if (event.target === importModal) {
                            importModal.classList.add('hidden');
                        }
                    });
                }
            });
        </script>

        @if (session('show_import_modal') && $errors->import->any())
            <script>
                // When the page reloads with errors, find the import modal and show it.
                document.addEventListener('DOMContentLoaded', function () {
                    const importModal = document.getElementById('importModal');
                    if (importModal) {
                        importModal.classList.remove('hidden');
                    }
                });
            </script>
        @endif
    @endpush
@endsection