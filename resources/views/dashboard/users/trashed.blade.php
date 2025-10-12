@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Deleted Users - sndp-bag Dashboard')
@section('page-title', 'Deleted Users Management')

@section('content')
    <!-- Breadcrumb with modern styling -->
    <div class="flex items-center gap-2 mb-6 text-sm">
        <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 font-medium">
            Home
        </a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('users.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 font-medium">
            Users
        </a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 dark:text-gray-100 font-semibold">Deleted Users</span>
    </div>

    <!-- Main Card with gradient border effect -->
    <div class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden fade-in">
        <!-- Decorative gradient line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 via-orange-500 to-amber-500"></div>
        
        <!-- Header Section -->
        <div class="p-8 border-b border-gray-100 dark:border-gray-800">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Deleted Users</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage and restore deleted users</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message with icon -->
        @if (session('success'))
            <div class="mx-8 mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-l-4 border-green-500 rounded-xl shadow-sm animate-fade-in">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Info Banner -->
        <div class="mx-8 mt-6 p-5 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border-l-4 border-amber-500 rounded-xl shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-amber-800 dark:text-amber-300 font-semibold mb-1">Soft Deleted Records</h4>
                    <p class="text-sm text-amber-700 dark:text-amber-400">These users have been soft deleted. You can restore them or permanently delete them.</p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="p-8 bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900">
            <form action="{{ route('users.trashed') }}" method="GET" class="space-y-6">
                <!-- Search and Filters Row -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Search Input with icon -->
                    <div class="md:col-span-8 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Search deleted users by name or email..."
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm dark:text-gray-100"
                            value="{{ request('search') }}">
                    </div>

                    <!-- Filter Button -->
                    <div class="md:col-span-4 flex gap-3">
                        <a href="{{ route('users.trashed') }}"
                            class="flex-1 px-6 py-3 rounded-xl text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 font-semibold transition-all duration-200 shadow-sm hover:shadow text-center">
                            Clear
                        </a>
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Search
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
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 border-y border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">User Info</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Deleted At</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @forelse($users as $user)
                            <tr class="hover:bg-gradient-to-r hover:from-red-50 hover:to-transparent dark:hover:from-red-900/10 dark:hover:to-transparent transition-all duration-200 group">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <img class="w-10 h-10 rounded-xl object-cover ring-2 ring-gray-100 dark:ring-gray-700 group-hover:ring-red-200 dark:group-hover:ring-red-800 transition-all duration-200 shadow-sm opacity-60"
                                                src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&bold=true' }}"
                                                alt="{{ $user->name }}">
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-gray-900"></div>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-0.5">
                                        <p class="text-gray-700 dark:text-gray-300 text-sm font-medium flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $user->email }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $user->phone ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                        @if ($user->role == 'Admin') 
                                            text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800
                                        @else
                                            text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                        @endif"
                                        >
                                        @if ($user->role == 'Admin')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        @endif
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->deleted_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->deleted_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <!-- Restore Button -->
                                        <form action="{{ route('users.restore', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-all duration-200 font-semibold text-xs shadow-sm hover:shadow transform hover:-translate-y-0.5 border border-green-200 dark:border-green-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Restore
                                            </button>
                                        </form>

                                        <!-- Permanent Delete Button -->
                                        <form id="force-delete-form-{{ $user->id }}"
                                            action="{{ route('users.forceDelete', $user->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="force-delete-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition-all duration-200 font-semibold text-xs shadow-sm hover:shadow transform hover:-translate-y-0.5 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400"
                                                data-form-id="force-delete-form-{{ $user->id }}">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Delete Forever
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 rounded-full flex items-center justify-center border-4 border-green-50 dark:border-green-900/30">
                                            <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-1">No Deleted Users</p>
                                            <p class="text-gray-500 dark:text-gray-400">There are no soft deleted users at the moment.</p>
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
        <div class="p-8 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

    {{-- @include('dashboard.partials.force_delete_modal') --}}
     @include('admin-panel::dashboard.partials.delete_modal')

    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all the necessary elements from the modal
        const forceDeleteModal = document.getElementById('deleteModal');
        const cancelModalBtn = document.getElementById('cancelModalBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        
        // Get all buttons with the .force-delete-btn class
        const deleteButtons = document.querySelectorAll('.force-delete-btn');

        // This variable will hold the ID of the form to be submitted
        let formToSubmit = null;

        // Add a click listener to each delete button on the page
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get the form ID from the button's data-form-id attribute
                formToSubmit = this.getAttribute('data-form-id');
                // Show the modal
                forceDeleteModal.classList.remove('hidden');
            });
        });

        // When the "Yes, Delete Permanently" button inside the modal is clicked
        confirmDeleteBtn.addEventListener('click', function () {
            if (formToSubmit) {
                // Find the form with that ID and submit it
                document.getElementById(formToSubmit).submit();
            }
        });

        // When the "Cancel" button is clicked, just hide the modal
        cancelModalBtn.addEventListener('click', function () {
            forceDeleteModal.classList.add('hidden');
        });

        // Also allow closing the modal by clicking on the background
        forceDeleteModal.addEventListener('click', function (event) {
            if (event.target === forceDeleteModal) {
                forceDeleteModal.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection