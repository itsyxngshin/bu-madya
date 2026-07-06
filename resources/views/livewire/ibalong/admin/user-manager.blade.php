<div class="max-w-7xl mx-auto space-y-8 relative">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Personnel Management</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Mint credentials and manage access for Judges, Facilitators, and Community Center Staff.</p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <p class="text-sm font-bold text-green-800 dark:text-green-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
            <p class="text-sm font-bold text-red-800 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Create User Card --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Generate New Account</h3>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="createUser" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-start">

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-iba-teal focus:ring-iba-teal">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-iba-teal focus:ring-iba-teal">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">System Role</label>
                    <select wire:model="role_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-iba-teal focus:ring-iba-teal">
                        <option value="">Select Role...</option>
                        <option value="2">System Admin</option>
                        <option value="4">Judge</option>
                        <option value="5">Facilitator / Mentor</option>
                    </select>
                    @error('role_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Designation / Title</label>
                    <input type="text" wire:model="designation" placeholder="e.g. Lead Pitch Judge" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-iba-teal focus:ring-iba-teal">
                    @error('designation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1 flex items-end h-full pt-5">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-iba-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iba-teal transition-colors">
                        Mint Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Personnel Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Personnel</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Designation</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $user->designation }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    @if($user->role_id == 1) Super Admin
                                    @elseif($user->role_id == 2) Admin
                                    @elseif($user->role_id == 4) Judge
                                    @elseif($user->role_id == 5) Facilitator
                                    @else Unknown @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $user->is_active ? 'Active' : 'Locked' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-3">
                                    <button wire:click="openEditModal({{ $user->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Edit User">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmPasswordReset({{ $user->id }})" class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300" title="Reset Password">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    </button>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <button wire:click="toggleStatus({{ $user->id }})" class="{{ $user->is_active ? 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300' : 'text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300' }}">
                                        {{ $user->is_active ? 'Deactivate' : 'Reactivate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $users->links() }}
    </div>

    {{-- MODAL: EDIT USER --}}
    @if($editModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-800/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-xl flex flex-col bg-white dark:bg-gray-800 rounded-xl text-left shadow-2xl transform transition-all border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit User Profile</h3>
                        <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <form wire:submit.prevent="updateUser">
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                <input type="text" wire:model="edit_name" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                                @error('edit_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                <input type="email" wire:model="edit_email" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                                @error('edit_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">System Role</label>
                                <select wire:model="edit_role_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                                    <option value="2">System Admin</option>
                                    <option value="4">Judge</option>
                                    <option value="5">Facilitator / Mentor</option>
                                </select>
                                @error('edit_role_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Designation</label>
                                <input type="text" wire:model="edit_designation" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                                @error('edit_designation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-iba-teal text-white rounded-md text-sm font-bold hover:bg-teal-700">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: RESET PASSWORD --}}
    @if($passwordModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-800/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-md flex flex-col bg-white dark:bg-gray-800 rounded-xl text-left shadow-2xl transform transition-all border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Admin Authorization Required</h3>
                        <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    <div class="p-6">
                        @if(!$generated_password)
                            <form wire:submit.prevent="executePasswordReset" class="space-y-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">To securely reset this user's password, please enter your own Administrator password to authorize the action.</p>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Your Admin Password</label>
                                    <input type="password" wire:model="admin_password" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-orange focus:ring-iba-orange">
                                    @error('admin_password') <span class="text-red-500 text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div class="pt-2 flex gap-3">
                                    <button type="button" wire:click="closeModals" class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                                    <button type="submit" class="w-full px-4 py-2 bg-iba-orange text-gray-900 rounded-md text-sm font-bold hover:bg-orange-600">Authorize Reset</button>
                                </div>
                            </form>
                        @else
                            {{-- Success State --}}
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Password Regenerated</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Please securely copy and provide this new password to the user. It will not be shown again.</p>

                                <div class="bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 p-4 rounded-md text-center mb-6">
                                    <span class="font-mono text-xl font-bold text-iba-teal tracking-widest">{{ $generated_password }}</span>
                                </div>

                                <button wire:click="closeModals" class="w-full px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-bold hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600">Close Window</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
