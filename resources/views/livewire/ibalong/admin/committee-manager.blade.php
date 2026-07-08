<div class="max-w-7xl mx-auto space-y-8 relative">
    
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Working Committee Roster</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage the internal teams, heads, and members powering the Heroes of Innovation.</p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <p class="text-sm font-bold text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Add New Member Form --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Add Committee Member</h3>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="addMember" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-start">
                
                {{-- Left Column: Info --}}
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase">Assigned Committee <span class="text-iba-red">*</span></label>
                            <button type="button" wire:click="$set('createCommitteeModalOpen', true)" class="text-xs font-bold text-iba-teal hover:underline">+ New Committee</button>
                        </div>
                        <select wire:model="committee_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                            <option value="">-- Select Committee --</option>
                            @foreach($committees as $committee)
                                <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                            @endforeach
                        </select>
                        @error('committee_id') <span class="text-red-500 text-xs font-bold mt-1 block">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name <span class="text-iba-red">*</span></label>
                        <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                        @error('name') <span class="text-red-500 text-xs font-bold mt-1 block">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Affiliation / Org</label>
                        <input type="text" wire:model="affiliation" placeholder="e.g. BU MADYA" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Community Title</label>
                        <input type="text" wire:model="designation" placeholder="e.g. Director-General" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Committee Role <span class="text-iba-red">*</span></label>
                        <select wire:model="role" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                            <option value="Head">Committee Head</option>
                            <option value="Member">Committee Member</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sort Order</label>
                        <input type="number" wire:model="display_order" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal" min="0">
                    </div>
                </div>

                {{-- Right Column: Avatar Upload --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Member Photo (Optional)</label>
                    <div x-data="{ isDropping: false }"
                         x-on:dragover.prevent="isDropping = true"
                         x-on:dragleave.prevent="isDropping = false"
                         x-on:drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));"
                         class="relative flex flex-col items-center justify-center w-full p-4 border-2 border-dashed rounded-xl cursor-pointer transition-all h-full min-h-[200px]"
                         :class="isDropping ? 'border-iba-teal bg-teal-50 dark:bg-teal-900/20' : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700'">

                        <input type="file" x-ref="fileInput" wire:model.live="photo" accept="image/png, image/webp, image/jpeg" class="absolute inset-0 z-50 w-full h-full opacity-0 cursor-pointer">

                        @if ($photo)
                            <div class="flex flex-col items-center pointer-events-none">
                                <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-20 rounded-full object-cover shadow-md border-4 border-white dark:border-gray-700 mb-2">
                                <p class="text-xs font-bold text-iba-teal">Photo staged!</p>
                            </div>
                        @else
                            <div class="text-center pointer-events-none" wire:loading.remove wire:target="photo">
                                <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Drag & Drop or <span class="text-iba-teal underline">Click to upload</span></p>
                            </div>
                            <div class="text-center hidden pointer-events-none" wire:loading.class.remove="hidden" wire:target="photo">
                                <svg class="mx-auto h-8 w-8 text-iba-teal animate-spin mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <p class="text-xs font-bold text-iba-teal animate-pulse">Uploading...</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-5 flex justify-end pt-2 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-iba-teal hover:bg-teal-700 transition-colors" wire:loading.attr="disabled">
                        Publish to Roster
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Committees Display Loop --}}
    @forelse($committees as $committee)
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mb-8">
            
            {{-- Header with Edit Committee Button --}}
            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700 border-l-4 border-l-iba-teal flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wider">{{ $committee->name }}</h3>
                <button wire:click="openEditCommitteeModal({{ $committee->id }})" class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Committee
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-white dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Profile & Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Affiliation / Title</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($committee->members as $member)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex shrink-0 items-center justify-center border border-gray-300 dark:border-gray-600">
                                            @if($member->photo_path)
                                                <img src="{{ Storage::url($member->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400 font-bold text-sm">{{ substr($member->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $member->name }}</div>
                                            <div class="text-[10px] font-bold uppercase tracking-wider mt-0.5 {{ $member->role == 'Head' ? 'text-iba-orange' : 'text-gray-500 dark:text-gray-400' }}">
                                                {{ $member->role == 'Head' ? '★ Committee Head' : 'Committee Member' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $member->affiliation ?: 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $member->designation ?: 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                        {{ $member->is_active ? 'Visible' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-3">
                                        <button wire:click="openEditModal({{ $member->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <span class="text-gray-300 dark:text-gray-600">|</span>
                                        <button wire:click="toggleStatus({{ $member->id }})" class="{{ $member->is_active ? 'text-gray-500' : 'text-green-600' }}">
                                            {{ $member->is_active ? 'Hide' : 'Show' }}
                                        </button>
                                        <span class="text-gray-300 dark:text-gray-600">|</span>
                                        <button wire:click="deleteMember({{ $member->id }})" wire:confirm="Remove this member?" class="text-red-600">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No members assigned to this committee yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl p-10 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">No committees created yet.</p>
        </div>
    @endforelse


    {{-- MODAL: ADD NEW COMMITTEE --}}
    @if($createCommitteeModalOpen)
        <div class="fixed inset-0 z-[110] overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-md flex flex-col bg-white dark:bg-gray-800 rounded-xl text-left shadow-2xl border-2 border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase">Create New Committee</h3>
                    </div>
                    <form wire:submit.prevent="saveNewCommittee">
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Committee Name</label>
                                <input type="text" wire:model="new_committee_name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                @error('new_committee_name') <span class="text-red-500 text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sort Order</label>
                                <input type="number" wire:model="new_committee_order" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-t flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-4 py-2 bg-white dark:bg-gray-800 border rounded-md text-sm font-bold text-gray-700 dark:text-gray-300">Cancel</button>
                            <button type="submit" class="px-6 py-2 bg-iba-teal text-white rounded-md text-sm font-bold">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: EDIT COMMITTEE --}}
    @if($editCommitteeModalOpen)
        <div class="fixed inset-0 z-[110] overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-md flex flex-col bg-white dark:bg-gray-800 rounded-xl text-left shadow-2xl border-2 border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase">Edit Committee</h3>
                    </div>
                    <form wire:submit.prevent="updateCommittee">
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Committee Name</label>
                                <input type="text" wire:model="edit_committee_name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                @error('edit_committee_name') <span class="text-red-500 text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sort Order</label>
                                <input type="number" wire:model="edit_committee_order" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-t flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-4 py-2 bg-white dark:bg-gray-800 border rounded-md text-sm font-bold text-gray-700 dark:text-gray-300">Cancel</button>
                            <button type="submit" class="px-6 py-2 bg-iba-teal text-white rounded-md text-sm font-bold">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: EDIT COMMITTEE MEMBER --}}
    @if($editModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full sm:max-w-3xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-2 border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase">Edit Member Profile</h3>
                    </div>
                    <form wire:submit.prevent="updateMember">
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Committee</label>
                                <select wire:model="edit_committee_id" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                    @foreach($committees as $committee)
                                        <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Full Name</label>
                                <input type="text" wire:model="edit_name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Affiliation</label>
                                <input type="text" wire:model="edit_affiliation" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Designation</label>
                                <input type="text" wire:model="edit_designation" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Role</label>
                                <select wire:model="edit_role" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                    <option value="Head">Head</option>
                                    <option value="Member">Member</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Sort Order</label>
                                <input type="number" wire:model="edit_display_order" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>

                            @if($edit_motivation)
                                <div class="md:col-span-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-md my-2">
                                    <h4 class="text-[10px] font-bold text-blue-800 dark:text-blue-300 uppercase tracking-widest mb-2">Volunteer Application Info</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Email:</strong> {{ $edit_email }}</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Mobile:</strong> {{ $edit_mobile_number }}</p>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Motivation:</strong> "{{ $edit_motivation }}"</p>
                                </div>
                            @endif

                            <div class="md:col-span-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <label class="text-xs font-bold uppercase text-gray-500 mb-2 block">Replace Avatar</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-300 overflow-hidden shrink-0">
                                        @if ($new_photo)
                                            <img src="{{ $new_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @elseif ($existing_photo_path)
                                            <img src="{{ Storage::url($existing_photo_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <input type="file" wire:model.live="new_photo" accept="image/png, image/webp, image/jpeg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-gray-100 dark:file:bg-gray-700 cursor-pointer">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-t flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-4 py-2 bg-white dark:bg-gray-800 border rounded-md text-sm font-bold text-gray-700 dark:text-gray-300">Cancel</button>
                            <button type="submit" class="px-6 py-2 bg-iba-teal text-white rounded-md text-sm font-bold">Save Updates</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>