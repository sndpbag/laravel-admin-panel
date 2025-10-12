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
                <a href="{{ route('users.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                    style="background: linear-gradient(135deg, var(--primary));">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New User
                </a>

                {{-- tarsh --}}
                <a href="{{ route('users.trashed') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-100 font-medium transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    View Trash
                </a>
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

                        <button type="button" id="openImportModalBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium transition-all duration-200 shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Import
                        </button>
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
                                                {{ $user->name }}</p>
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
                                    <form action="{{ route('users.toggleRole', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-300 shadow-sm hover:shadow transform hover:-translate-y-0.5
                                            @if ($user->role == 'Admin') text-white hover:opacity-90" style="background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);"
                                            @else
                                                text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200" @endif>
                                            @if ($user->role == 'Admin')
<svg class="w-3
                                            h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                        @endif
                        {{ $user->role }}
                        </button>
                        </form>
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 w-24 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-300 shadow-sm hover:shadow transform hover:-translate-y-0.5
                                            @if ($user->status == 'Active') bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 hover:from-green-100 hover:to-emerald-100 border border-green-200"
                                            @else
                                                bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 hover:from-gray-200 hover:to-gray-300 border border-gray-300" @endif>
                                            @if ($user->status == 'Active')
<span class="w-1.5
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
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-all duration-200 font-semibold text-xs shadow-sm hover:shadow transform hover:-translate-y-0.5 border border-blue-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>

                                <form id="delete-form-{{ $user->id }}"
                                    action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 transition-all duration-200 font-semibold text-xs shadow-sm hover:shadow transform hover:-translate-y-0.5 border border-red-200"
                                        style="color: var(--secondary);" data-form-id="delete-form-{{ $user->id }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
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

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
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
                        button.addEventListener('click', function() {
                            // Get the form ID from the button's data-form-id attribute
                            formToSubmit = this.getAttribute('data-form-id');
                            // Show the modal
                            deleteModal.classList.remove('hidden');
                        });
                    });

                    // When the "Yes, Delete" button inside the modal is clicked
                    confirmDeleteBtn.addEventListener('click', function() {
                        if (formToSubmit) {
                            // Find the form with that ID and submit it
                            document.getElementById(formToSubmit).submit();
                        }
                    });

                    // When the "Cancel" button is clicked, just hide the modal
                    cancelModalBtn.addEventListener('click', function() {
                        deleteModal.classList.add('hidden');
                    });

                    // Also allow closing the modal by clicking on the background
                    deleteModal.addEventListener('click', function(event) {
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
                    document.addEventListener('DOMContentLoaded', function() {
                        const importModal = document.getElementById('importModal');
                        if (importModal) {
                            importModal.classList.remove('hidden');
                        }
                    });
                </script>
            @endif
        @endpush
    @endsection
