<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-white dark:bg-[#1A1617] p-6 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Personnel Management</h1>
        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Mint credentials and manage access for Judges, Facilitators, and Community Center Staff.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-iba-red/10 border-l-4 border-iba-red p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-red uppercase tracking-wider">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Create User Card --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-2 border-iba-black dark:border-iba-light">
            <h3 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider">Generate New Account</h3>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="createUser" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-start">

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name</label>
                    <input type="text" wire:model="name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                    @error('name') <span class="text-iba-red text-xs font-bold mt-1 block">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                    @error('email') <span class="text-iba-red text-xs font-bold mt-1 block">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">System Role</label>
                    <select wire:model="role_id" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                        <option value="">Select Role...</option>
                        <option value="2">System Admin</option>
                        <option value="4">Judge</option>
                        <option value="5">Facilitator / Mentor</option>
                    </select>
                    @error('role_id') <span class="text-iba-red text-xs font-bold mt-1 block">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Designation / Title</label>
                    <input type="text" wire:model="designation" placeholder="e.g. Lead Pitch Judge" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                    @error('designation') <span class="text-iba-red text-xs font-bold mt-1 block">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1 flex items-end h-full pt-5">
                    <button type="submit" class="w-full bg-iba-teal text-white font-bold px-4 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                        Mint Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Personnel Table --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] overflow-x-auto">
        <table class="min-w-full divide-y-2 divide-iba-black dark:divide-iba-light">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Personnel</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Designation</th>
                    <th class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-[#1A1617]">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-iba-black dark:text-white uppercase">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 font-semibold mt-1">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600 dark:text-gray-300 uppercase">
                            {{ $user->designation }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 border-2 border-iba-black dark:border-iba-light text-[10px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-800 text-iba-black dark:text-white">
                                @if($user->role_id == 1) Super Admin
                                @elseif($user->role_id == 2) Admin
                                @elseif($user->role_id == 4) Judge
                                @elseif($user->role_id == 5) Facilitator
                                @else Unknown @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 {{ $user->is_active ? 'border-iba-green text-iba-green bg-green-50 dark:bg-green-900/30' : 'border-iba-red text-iba-red bg-red-50 dark:bg-red-900/30' }}">
                                {{ $user->is_active ? 'Active' : 'Locked' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-3">
                                <button wire:click="openEditModal({{ $user->id }})" class="text-blue-600 hover:text-blue-900 font-bold uppercase text-xs tracking-wider">Edit</button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button wire:click="confirmPasswordReset({{ $user->id }})" class="text-iba-orange hover:text-orange-700 font-bold uppercase text-xs tracking-wider">Reset Pass</button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button wire:click="toggleStatus({{ $user->id }})" class="font-bold uppercase text-xs tracking-wider {{ $user->is_active ? 'text-iba-red hover:text-red-900' : 'text-iba-green hover:text-green-900' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Reactivate' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>

    {{-- MODAL: EDIT USER --}}
    @if($editModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-xl flex flex-col bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#0095AC] text-left transition-all overflow-hidden">
                    
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-4 border-iba-black dark:border-iba-light flex justify-between items-center">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Edit User Profile</h3>
                        <button wire:click="closeModals" class="text-gray-400 hover:text-iba-red"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    
                    <form wire:submit.prevent="updateUser">
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Full Name</label>
                                <input type="text" wire:model="edit_name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                @error('edit_name') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email Address</label>
                                <input type="email" wire:model="edit_email" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                                @error('edit_email') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">System Role</label>
                                <select wire:model="edit_role_id" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                    <option value="2">System Admin</option>
                                    <option value="4">Judge</option>
                                    <option value="5">Facilitator / Mentor</option>
                                </select>
                                @error('edit_role_id') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Designation</label>
                                <input type="text" wire:model="edit_designation" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                @error('edit_designation') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t-4 border-iba-black dark:border-iba-light flex justify-end gap-4">
                            <button type="button" wire:click="closeModals" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black dark:text-gray-400 dark:hover:text-white transition-colors">Cancel</button>
                            <button type="submit" class="bg-iba-teal text-white font-bold px-6 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: RESET PASSWORD --}}
    @if($passwordModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-md flex flex-col bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] text-left transition-all overflow-hidden">
                    
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-4 border-iba-black dark:border-iba-light flex justify-between items-center">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Admin Authorization Required</h3>
                        <button wire:click="closeModals" class="text-gray-400 hover:text-iba-red"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    <div class="p-6">
                        @if(!$generated_password)
                            <form wire:submit.prevent="executePasswordReset" class="space-y-4">
                                <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-4 leading-relaxed uppercase">To securely reset this user's password, verify your admin clearance.</p>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">New Custom Password</label>
                                    <input type="text" wire:model="new_password" placeholder="Leave blank to auto-generate" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                                    @error('new_password') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Your Admin Password <span class="text-iba-red">*</span></label>
                                    <input type="password" wire:model="admin_password" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                                    @error('admin_password') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div class="pt-4 flex gap-3">
                                    <button type="button" wire:click="closeModals" class="w-full px-4 py-2 border-2 border-iba-black dark:border-iba-light bg-gray-100 dark:bg-gray-800 text-sm font-bold uppercase text-gray-700 dark:text-gray-300 hover:bg-gray-200">Cancel</button>
                                    <button type="submit" class="w-full bg-iba-orange text-iba-black font-bold px-4 py-2 text-sm uppercase border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Force Reset</button>
                                </div>
                            </form>
                        @else
                            {{-- Success State --}}
                            <div class="text-center">
                                <h3 class="text-lg font-black text-iba-black dark:text-white uppercase mb-2">Password Regenerated</h3>
                                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-4">Please securely copy and provide this new password to the user. It will not be shown again.</p>

                                <div class="bg-gray-100 dark:bg-gray-900 border-2 border-iba-black dark:border-iba-light p-4 text-center mb-6 shadow-inner">
                                    <span class="font-pixel text-xl font-bold text-iba-teal tracking-widest">{{ $generated_password }}</span>
                                </div>

                                <button wire:click="closeModals" class="w-full bg-iba-black dark:bg-iba-light text-white dark:text-iba-black font-bold px-6 py-2.5 text-sm uppercase border-2 border-transparent shadow-[3px_3px_0_0_#FF8623] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Close Window</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>