@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Settings - sndp-bag Dashboard')
@section('page-title', 'Settings')

@section('content')
    <div class="breadcrumb-nav">
        <a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Settings</span>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade-in">
        <!-- Profile Settings -->
        @can('settings.profile.update')
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Profile Settings</h3>

                <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-col items-center space-y-3 mb-6">
                        @can('settings.profile-image.update')
                            <label for="profileImageInput" class="cursor-pointer profile-image-container">
                                <img id="profileImagePreview"
                                    src="{{ $user->profile_image ? Storage::url($user->profile_image) : '...' }}" alt="Profile"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                                <div class="profile-image-overlay">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </label>
                            <input type="file" id="profileImageInput" name="profile_image" class="hidden"
                                accept="image/png, image/jpeg, image/gif">
                            <p class="text-sm bg-white dark:bg-gray-800 text-gray-500">Click image to change</p>
                        @else
                            <img id="profileImagePreview"
                                src="{{ $user->profile_image ? Storage::url($user->profile_image) : '...' }}" alt="Profile"
                                class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                        @endcan
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full px-6 py-3 rounded-xl text-white font-semibold hover:shadow-lg transition"
                            style="background: var(--primary);">Save Changes</button>
                    </div>
                </form>
            </div>
        @endcan

        <!-- Change Password -->
        @can('settings.password.update')
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Change Password</h3>

                <form action="{{ route('settings.password.update') }}" method="POST">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" placeholder="••••••••"
                                class="w-full bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition"
                                required>
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                            <input type="password" name="new_password" placeholder="••••••••"
                                class="w-full bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition"
                                required>
                            @error('new_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" placeholder="••••••••"
                                class="w-full bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition"
                                required>
                        </div>

                        <button type="submit"
                            class="w-full px-6 py-3 rounded-xl text-white font-semibold hover:shadow-lg transition"
                            style="background: var(--secondary);">Update Password</button>
                    </div>
                </form>
            </div>
        @endcan

        <!-- Theme Customization -->
        @can('settings.theme.update')
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Theme Customization</h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Primary Color</label>
                        <div class="flex items-center gap-4">
                            <input type="color" id="primaryColor" value="{{ $settings['primary_color'] }}"
                                class="color-preview">
                            <input type="text" id="primaryText" value="{{ $settings['primary_color'] }}"
                                class="flex-1 bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Secondary Color</label>
                        <div class="flex items-center gap-4">
                            <input type="color" id="secondaryColor" value="{{ $settings['secondary_color'] }}"
                                class="color-preview bg-white dark:bg-gray-800">
                            <input type="text" id="secondaryText" value="{{ $settings['secondary_color'] }}"
                                class="flex-1 bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Accent Color</label>
                        <div class="flex items-center gap-4">
                            <input type="color" id="accentColor" value="{{ $settings['accent_color'] }}"
                                class="color-preview bg-white dark:bg-gray-800">
                            <input type="text" id="accentText" value="{{ $settings['accent_color'] }}"
                                class="flex-1 bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Font Family</label>
                        <select id="fontFamily"
                            class="w-full bg-white dark:bg-gray-800 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition">
                            <option value="'Poppins', sans-serif" {{ $settings['font_family'] == "'Poppins', sans-serif" ? 'selected' : '' }}>Poppins (Default)</option>
                            <option value="'Inter', sans-serif" {{ $settings['font_family'] == "'Inter', sans-serif" ? 'selected' : '' }}>Inter</option>
                            <option value="'Roboto', sans-serif" {{ $settings['font_family'] == "'Roboto', sans-serif" ? 'selected' : '' }}>Roboto</option>
                            <option value="'Open Sans', sans-serif" {{ $settings['font_family'] == "'Open Sans', sans-serif" ? 'selected' : '' }}>Open Sans</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Font Size</label>
                        <div id="fontSizeControls" class="grid grid-cols-3 gap-2">
                            <button type="button"
                                class="font-size-btn p-3 rounded-xl {{ $settings['font_size'] == 'sm' ? 'active' : '' }}"
                                data-size="sm">Small</button>
                            <button type="button"
                                class="font-size-btn p-3 rounded-xl {{ $settings['font_size'] == 'md' ? 'active' : '' }}"
                                data-size="md">Medium</button>
                            <button type="button"
                                class="font-size-btn p-3 rounded-xl {{ $settings['font_size'] == 'lg' ? 'active' : '' }}"
                                data-size="lg">Large</button>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" id="applyTheme"
                            class="flex-1 px-6 py-3 rounded-xl text-white font-semibold hover:shadow-lg transition"
                            style="background: var(--accent);">Apply Theme</button>
                        <button type="button" id="resetTheme"
                            class="px-6 py-3 rounded-xl border-2 border-gray-300 font-semibold hover:bg-gray-50 transition">Reset</button>
                    </div>
                </div>
            </div>
        @endcan

        <!-- Notification Settings -->
        @can('settings.notifications.update')
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Notification Settings</h3>

                <div class="space-y-5">
                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">Email Notifications</p>
                            <p class="text-sm text-gray-600 mt-1">Receive important updates</p>
                        </div>
                        <div class="toggle-switch {{ $notifications['email'] ? 'active' : '' }}" data-toggle="email">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">Push Notifications</p>
                            <p class="text-sm text-gray-600 mt-1 dark:text-gray-400">Notifications in browser</p>
                        </div>
                        <div class="toggle-switch {{ $notifications['push'] ? 'active' : '' }}" data-toggle="push">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">SMS Notifications</p>
                            <p class="text-sm text-gray-600 mt-1 dark:text-gray-400">Receive messages on phone</p>
                        </div>
                        <div class="toggle-switch {{ $notifications['sms'] ? 'active' : '' }}" data-toggle="sms">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">Weekly Reports</p>
                            <p class="text-sm text-gray-600 mt-1 dark:text-gray-400">Get weekly summaries</p>
                        </div>
                        <div class="toggle-switch {{ $notifications['weekly'] ? 'active' : '' }}" data-toggle="weekly">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <!-- Database Management -->
        @can('settings.backup.database')
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Database Management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Backup your database securely</p>

                @if ($errors->has('backup'))
                    <div
                        class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 rounded-xl">
                        {{ $errors->first('backup') }}
                    </div>
                @endif

                <div class="space-y-5">
                    <div class="p-5 bg-white dark:bg-gray-800   rounded-xl border-2 border-blue-100 dark:border-blue-800">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Database Backup</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Create a complete SQL backup of your database. The backup file will be downloaded to your
                                    device automatically.
                                </p>
                                <form action="{{ route('settings.backup.database') }}" method="POST" id="backupForm">
                                    @csrf
                                    <button type="submit" id="backupButton"
                                        class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5" id="backupIcon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span id="backupText">Download Database Backup</span>
                                    </button>
                                </form>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-3 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Backup includes all tables and data. Keep it safe and secure.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan



    </div>

    <script>
        // Database Backup Form Handler
        document.getElementById('backupForm')?.addEventListener('submit', function (e) {
            const button = document.getElementById('backupButton');
            const icon = document.getElementById('backupIcon');
            const text = document.getElementById('backupText');

            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            text.textContent = 'Creating Backup...';

            // Show loading spinner
            icon.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
            icon.classList.add('animate-spin');

            // Re-enable button after 5 seconds
            setTimeout(() => {
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
                text.textContent = 'Download Database Backup';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />';
                icon.classList.remove('animate-spin');
            }, 5000);
        });
    </script>
@endsection