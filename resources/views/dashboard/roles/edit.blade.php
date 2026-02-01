@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role: ' . $role->name)

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Edit Role Details</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Update role information and permissions</p>
            </div>

            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Role Name -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role Name</label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-primary focus:ring focus:ring-primary/20 transition-all">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-primary focus:ring focus:ring-primary/20 transition-all">{{ old('description', $role->description) }}</textarea>
                </div>

                <!-- Permissions -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Permissions</h3>
                        <button type="button" onclick="toggleAllPermissions()"
                            class="text-sm text-primary hover:underline">Select All</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($permissionGroups as $group => $permissions)
                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50">
                                <div
                                    class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                                    <h4 class="font-bold text-gray-700 dark:text-gray-300 capitalize">{{ $group }}</h4>
                                    <input type="checkbox" onchange="toggleGroup(this)"
                                        class="rounded border-gray-300 text-primary focus:ring-primary">
                                </div>
                                <div class="space-y-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                @if(in_array($permission->id, $rolePermissions)) checked @endif
                                                class="permission-checkbox rounded border-gray-300 text-primary focus:ring-primary transition-colors">
                                            <span
                                                class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200 transition-colors">
                                                {{ $permission->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('roles.index') }}"
                        class="px-5 py-2.5 rounded-xl font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                    <button type="submit"
                        class="bg-primary hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-medium transition-colors shadow-lg shadow-primary/30">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAllPermissions() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const allChecked = Array.from(checkboxes).every(c => c.checked);
            checkboxes.forEach(c => c.checked = !allChecked);
        }

        function toggleGroup(groupCheckbox) {
            const groupContainer = groupCheckbox.closest('div').parentElement;
            const checkboxes = groupContainer.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(c => c.checked = groupCheckbox.checked);
        }
    </script>
@endsection