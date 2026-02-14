@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Roles & Permissions')

@section('content')
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">All Roles</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage user roles and their permissions</p>
            </div>
            <a href="{{ route('roles.create') }}"
                class="bg-red-500 hover:bg-green-400 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-lg shadow-primary/30 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Create New Role
            </a>
        </div>

        <div class="p-6">
            <!-- Search -->
            <div class="mb-6 max-w-md">
                <form action="{{ route('roles.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles..."
                        class="w-full bg-white dark:bg-gray-800 pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-primary focus:ring focus:ring-primary/20 transition-all">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-100 dark:border-gray-700 text-sm">
                            <th class="py-3 px-4 font-medium">Name</th>
                            <th class="py-3 px-4 font-medium">Description</th>
                            <th class="py-3 px-4 font-medium">Users Count</th>
                            <th class="py-3 px-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                                <td class="py-3 px-4">
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $role->name }}</span>
                                    <span class="block text-xs text-gray-400">{{ $role->slug }}</span>
                                </td>
                                <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $role->description ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $role->users_count }}
                                        Users</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2 text-sm">
                                        <a href="{{ route('roles.edit', $role) }}"
                                            class="text-gray-500 hover:text-primary transition-colors font-medium">Edit</a>
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-600 transition-colors font-medium ml-2">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
@endsection