<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="bg-white dark:bg-[#1A1617] p-6 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Working Committee Roster</h1>
        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Manage the internal teams, heads, and members powering the Launchpad.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between mb-6">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Add New Member Form --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] mb-8">
        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-2 border-iba-black dark:border-iba-light">
            <h3 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider">Add Committee Member</h3>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="addMember" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-start">
                
                {{-- Left Column: Info --}}
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned Committee <span class="text-iba-red">*</span></label>
                            <button type="button" wire:click="$set('createCommitteeModalOpen', true)" class="text-xs font-bold text-iba-teal hover:underline uppercase">+ New Committee</button>
                        </div>
                        <select wire:model="committee_id" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                            <option value="">-- Select Committee --</option>
                            @foreach($committees as $committee)
                                <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                            @endforeach
                        </select>
                        @error('committee_id') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Full Name <span class="text-iba-red">*</span></label>
                        <input type="text" wire:model="name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                        @error('name') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Affiliation / Org</label>
                        <input type="text" wire:model="affiliation" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Community Title</label>
                        <input type="text" wire:model="designation" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Committee Role <span class="text-iba-red">*</span></label>
                        <select wire:model="role" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                            <option value="Head">Committee Head</option>
                            <option value="Member">Committee Member</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Sort Order</label>
                        <input type="number" wire:model="display_order" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold" min="0">
                    </div>
                </div>

                {{-- Right Column: Avatar Upload --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Member Photo (Optional)</label>
                    <div x-data="{ isDropping: false }"
                         x-on:dragover.prevent="isDropping = true"
                         x-on:dragleave.prevent="isDropping = false"
                         x-on:drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));"
                         class="relative flex flex-col items-center justify-center w-full p-4 border-4 border-dashed cursor-pointer transition-all h-full min-h-[200px]"
                         :class="isDropping ? 'border-iba-teal bg-teal-50 dark:bg-teal-900/20' : 'border-iba-black dark:border-gray-600 bg-gray-50 dark:bg-gray-800'">

                        <input type="file" x-ref="fileInput" wire:model.live="photo" accept="image/png, image/webp, image/jpeg" class="absolute inset-0 z-50 w-full h-full opacity-0 cursor-pointer">

                        @if ($photo)
                            <div class="flex flex-col items-center pointer-events-none">
                                <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-20 object-cover border-4 border-iba-black shadow-[2px_2px_0_0_#131011] mb-2">
                                <p class="text-xs font-black uppercase text-iba-teal tracking-wider">Photo staged!</p>
                            </div>
                        @else
                            <div class="text-center pointer-events-none" wire:loading.remove wire:target="photo">
                                <p class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest leading-relaxed">
                                    <span class="text-iba-teal underline">CLICK TO UPLOAD</span><br>OR DRAG & DROP
                                </p>
                            </div>
                            <div class="text-center hidden pointer-events-none" wire:loading.class.remove="hidden" wire:target="photo">
                                <p class="text-xs font-black text-iba-teal uppercase tracking-widest animate-pulse">UPLOADING...</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-5 flex justify-end pt-4 mt-2 border-t-4 border-dashed border-iba-black dark:border-gray-700">
                    <button type="submit" class="bg-iba-teal text-white font-bold px-8 py-3 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1" wire:loading.attr="disabled">
                        Publish to Roster
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Committees Display Loop --}}
    @forelse($committees as $committee)
        <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] mb-8 overflow-hidden">
            
            {{-- Header with Edit Committee Button --}}
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-2 border-iba-black dark:border-iba-light border-l-8 border-l-iba-teal flex justify-between items-center">
                <h3 class="text-base font-black text-iba-black dark:text-white uppercase tracking-wider">{{ $committee->name }}</h3>
                <button wire:click="openEditCommitteeModal({{ $committee->id }})" class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 flex items-center gap-1 uppercase tracking-wider">
                    Edit Committee
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-2 divide-iba-black dark:divide-iba-light">
                    <thead class="bg-white dark:bg-[#1A1617]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider w-1/3">Profile & Role</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider w-1/3">Affiliation / Title</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-[#1A1617]">
                        @forelse($committee->members as $member)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-900 border-2 border-iba-black dark:border-iba-light flex shrink-0 items-center justify-center shadow-[2px_2px_0_0_#131011] dark:shadow-none">
                                            @if($member->photo_path)
                                                <img src="{{ Storage::url($member->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-iba-black dark:text-white font-black text-lg uppercase">{{ substr($member->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-iba-black dark:text-white uppercase">{{ $member->name }}</div>
                                            <div class="text-[10px] font-bold uppercase tracking-wider mt-1 {{ $member->role == 'Head' ? 'text-iba-orange' : 'text-gray-500 dark:text-gray-400' }}">
                                                {{ $member->role == 'Head' ? '★ Committee Head' : 'Committee Member' }}
                                                
                                                @if(!$member->is_active && $member->motivation)
                                                    <span class="ml-2 text-[9px] border-2 border-iba-red text-iba-red px-1.5 py-0.5">NEW VOLUNTEER</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-iba-black dark:text-white font-bold uppercase">{{ $member->affiliation ?: 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">{{ $member->designation ?: 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 {{ $member->is_active ? 'border-iba-green text-iba-green bg-green-50 dark:bg-green-900/30' : 'border-gray-500 text-gray-600 bg-gray-100 dark:bg-gray-800' }}">
                                        {{ $member->is_active ? 'Visible' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-3">
                                        @if(!$member->is_active && $member->email)
                                            <button wire:click="approveAndNotify({{ $member->id }})" wire:loading.attr="disabled" class="text-teal-600 dark:text-teal-400 font-bold hover:underline flex items-center gap-1 uppercase text-xs tracking-wider">
                                                <span wire:loading.remove wire:target="approveAndNotify({{ $member->id }})">✉ Approve & Email</span>
                                                <span wire:loading wire:target="approveAndNotify({{ $member->id }})" class="animate-pulse">Sending...</span>
                                            </button>
                                            <span class="text-gray-300 dark:text-gray-600">|</span>
                                        @endif

                                        <button wire:click="openEditModal({{ $member->id }})" class="text-blue-600 hover:text-blue-900 font-bold uppercase text-xs tracking-wider">Edit</button>
                                        <span class="text-gray-300 dark:text-gray-600">|</span>
                                        <button wire:click="toggleStatus({{ $member->id }})" class="font-bold uppercase text-xs tracking-wider {{ $member->is_active ? 'text-gray-500 hover:text-gray-900' : 'text-iba-green hover:text-green-900' }}">
                                            {{ $member->is_active ? 'Hide' : 'Show' }}
                                        </button>
                                        <span class="text-gray-300 dark:text-gray-600">|</span>
                                        <button wire:click="deleteMember({{ $member->id }})" wire:confirm="Remove this member?" class="text-iba-red hover:text-red-900 font-bold uppercase text-xs tracking-wider">Drop</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">No members assigned to this committee yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black shadow-[4px_4px_0_0_#131011] p-10 text-center">
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No committees created yet.</p>
        </div>
    @endforelse

    {{-- MODAL: ADD NEW COMMITTEE --}}
    @if($createCommitteeModalOpen)
        <div class="fixed inset-0 z-[110] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-md flex flex-col bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#0095AC] text-left overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-4 border-iba-black dark:border-iba-light">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Create New Committee</h3>
                    </div>
                    <form wire:submit.prevent="saveNewCommittee">
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Committee Name</label>
                                <input type="text" wire:model="new_committee_name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                @error('new_committee_name') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sort Order</label>
                                <input type="number" wire:model="new_committee_order" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t-4 border-iba-black dark:border-iba-light flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black transition-colors">Cancel</button>
                            <button type="submit" class="bg-iba-teal text-white font-bold px-6 py-2 text-sm uppercase border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: EDIT COMMITTEE --}}
    @if($editCommitteeModalOpen)
        <div class="fixed inset-0 z-[110] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-md flex flex-col bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] text-left overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-4 border-iba-black dark:border-iba-light">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Edit Committee</h3>
                    </div>
                    <form wire:submit.prevent="updateCommittee">
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Committee Name</label>
                                <input type="text" wire:model="edit_committee_name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                @error('edit_committee_name') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sort Order</label>
                                <input type="number" wire:model="edit_committee_order" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t-4 border-iba-black dark:border-iba-light flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black transition-colors">Cancel</button>
                            <button type="submit" class="bg-iba-teal text-white font-bold px-6 py-2 text-sm uppercase border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: EDIT COMMITTEE MEMBER --}}
    @if($editModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full sm:max-w-3xl bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7] overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-4 border-iba-black dark:border-iba-light">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Edit Member Profile</h3>
                    </div>
                    <form wire:submit.prevent="updateMember">
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1 block">Committee</label>
                                <select wire:model="edit_committee_id" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                    @foreach($committees as $committee)
                                        <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1 block">Full Name</label>
                                <input type="text" wire:model="edit_name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1 block">Affiliation</label>
                                <input type="text" wire:model="edit_affiliation" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1 block">Designation</label>
                                <input type="text" wire:model="edit_designation" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1 block">Role</label>
                                <select wire:model="edit_role" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                    <option value="Head">Head</option>
                                    <option value="Member">Member</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1 block">Sort Order</label>
                                <input type="number" wire:model="edit_display_order" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                            </div>

                            {{-- Contact & Volunteer Data (Editable) --}}
                            <div class="md:col-span-2 border-4 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6 my-2">
                                <h4 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-widest mb-4">Contact & Volunteer Details</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Email Address</label>
                                        <input type="email" wire:model="edit_email" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Mobile Number</label>
                                        <input type="text" wire:model="edit_mobile_number" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold">
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Motivation / Notes</label>
                                    <textarea wire:model="edit_motivation" rows="3" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold"></textarea>
                                </div>

                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:model.boolean="edit_devcon_consent" class="w-5 h-5 border-2 border-iba-black text-iba-teal focus:ring-iba-teal">
                                        <span class="text-xs font-bold uppercase text-gray-700 dark:text-gray-300">Privacy & Media Consent Accepted</span>
                                    </label>
                                </div>
                            </div>

                            <div class="md:col-span-2 pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                                <label class="text-xs font-bold uppercase text-gray-500 mb-2 block">Replace Avatar</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 border-2 border-iba-black overflow-hidden shrink-0 shadow-[2px_2px_0_0_#131011]">
                                        @if ($new_photo)
                                            <img src="{{ $new_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @elseif ($existing_photo_path)
                                            <img src="{{ Storage::url($existing_photo_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <input type="file" wire:model.live="new_photo" accept="image/png, image/webp, image/jpeg" class="w-full text-sm font-bold uppercase text-gray-500 cursor-pointer">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t-4 border-iba-black dark:border-iba-light flex justify-end gap-4">
                            <button type="button" wire:click="closeModals" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black transition-colors">Cancel</button>
                            <button type="submit" class="bg-iba-teal text-white font-bold px-8 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Save Updates</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>