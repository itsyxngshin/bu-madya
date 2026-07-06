<div class="max-w-4xl mx-auto space-y-8">
    
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">My Profile</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage your Community Center identity and security settings.</p>
    </div>

    {{-- Update Profile Information --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Profile Information</h3>
        </div>
        <div class="p-6 sm:p-8">
            @if (session()->has('profile_success'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-sm rounded-md border border-green-200 dark:border-green-800">
                    {{ session('profile_success') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address (Login ID)</label>
                    <input type="text" value="{{ $email }}" disabled class="w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 sm:text-sm cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Email addresses cannot be changed.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Display Name</label>
                        <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title / Designation</label>
                        <input type="text" wire:model="designation" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                        @error('designation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-iba-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iba-teal transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Update Password</h3>
        </div>
        <div class="p-6 sm:p-8">
            @if (session()->has('password_success'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-sm rounded-md border border-green-200 dark:border-green-800">
                    {{ session('password_success') }}
                </div>
            @endif

            <form wire:submit.prevent="updatePassword" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                    <input type="password" wire:model="current_password" class="w-full md:w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                    @error('current_password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-4 pt-4"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                        <input type="password" wire:model="new_password" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                        @error('new_password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-iba-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iba-teal transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>