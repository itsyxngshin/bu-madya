<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-white dark:bg-[#1A1617] p-6 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Partner & Enabler Roster</h1>
        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Manage the organizations displayed on the public Launchpad.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Add New Partner Form --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-2 border-iba-black dark:border-iba-light">
            <h3 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider">Add New Partner</h3>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="addPartner" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-5 items-start">

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Organization Name</label>
                    <input type="text" wire:model="name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                    @error('name') <span class="text-iba-red text-xs font-bold mt-1 block uppercase">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Custom Role</label>
                    <input type="text" wire:model="role" placeholder="e.g. Lead Consortium Host" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                    @error('role') <span class="text-iba-red text-xs font-bold mt-1 block uppercase">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Logo Emphasis</label>
                    <select wire:model="emphasis" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                        <option value="medium">Medium</option>
                        <option value="small">Small</option>
                    </select>
                    @error('emphasis') <span class="text-iba-red text-xs font-bold mt-1 block uppercase">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sort Order</label>
                    <input type="number" wire:model="display_order" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold" min="0">
                </div>

                {{-- DRAG AND DROP UPLOAD ZONE --}}
                <div class="lg:col-span-6 mt-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Transparent Logo (PNG/WEBP)</label>

                    <div x-data="{ isDropping: false }"
                         x-on:dragover.prevent="isDropping = true"
                         x-on:dragleave.prevent="isDropping = false"
                         x-on:drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));"
                         class="relative flex flex-col items-center justify-center w-full p-8 border-4 border-dashed cursor-pointer transition-all duration-200"
                         :class="isDropping ? 'border-iba-teal bg-teal-50 dark:bg-teal-900/20' : 'border-iba-black dark:border-gray-600 bg-gray-50 dark:bg-gray-800'">

                        <input type="file" x-ref="fileInput" wire:model.live="logo" accept="image/png, image/webp, image/jpeg" class="absolute inset-0 z-50 w-full h-full opacity-0 cursor-pointer">

                        @if ($logo)
                            <div class="flex flex-col items-center pointer-events-none">
                                <div class="bg-gray-900 p-2 mb-3 border-4 border-iba-black shadow-[4px_4px_0_0_#131011]">
                                    <img src="{{ $logo->temporaryUrl() }}" class="h-20 w-auto object-contain">
                                </div>
                                <p class="text-sm font-black uppercase tracking-wider text-iba-teal">Logo staged successfully!</p>
                            </div>
                        @else
                            <div class="text-center pointer-events-none" wire:loading.remove wire:target="logo">
                                <p class="text-sm font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest leading-relaxed">
                                    <span class="text-iba-teal underline">CLICK TO UPLOAD</span><br>OR DRAG & DROP
                                </p>
                            </div>
                        @endif
                    </div>
                    @error('logo') <span class="text-iba-red text-xs font-bold mt-2 block uppercase">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-6 flex justify-end pt-4 mt-2 border-t-4 border-dashed border-iba-black dark:border-gray-700">
                    <button type="submit" class="bg-iba-teal text-white font-bold px-8 py-3 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="logo">Publish Partner</span>
                        <span wire:loading wire:target="logo">Awaiting Upload...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Partners Table --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] overflow-x-auto">
        <table class="min-w-full divide-y-2 divide-iba-black dark:divide-iba-light">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Logo & Name</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Assigned Role</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Order / Size</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-[#1A1617]">
                @forelse($partners as $partner)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-12 bg-gray-900 border-2 border-iba-black flex items-center justify-center p-1 shadow-[2px_2px_0_0_#131011]">
                                    <img src="{{ Storage::url($partner->logo_path) }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                                <div class="text-sm font-bold text-iba-black dark:text-white uppercase">{{ $partner->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-iba-teal uppercase">
                            {{ $partner->role }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 mr-2">#{{ $partner->display_order }}</span>
                            <span class="px-2 py-1 border-2 border-iba-black text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-iba-black">
                                {{ $partner->emphasis }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 {{ $partner->is_active ? 'border-iba-green text-iba-green bg-green-50 dark:bg-green-900/30' : 'border-gray-500 text-gray-600 bg-gray-100 dark:bg-gray-800' }}">
                                {{ $partner->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-3">
                                <button wire:click="openEditModal({{ $partner->id }})" class="text-blue-600 hover:text-blue-900 font-bold uppercase text-xs tracking-wider">Edit</button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button wire:click="toggleStatus({{ $partner->id }})" class="font-bold uppercase text-xs tracking-wider {{ $partner->is_active ? 'text-gray-500 hover:text-gray-900' : 'text-iba-green hover:text-green-900' }}">
                                    {{ $partner->is_active ? 'Hide' : 'Show' }}
                                </button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button wire:click="deletePartner({{ $partner->id }})" wire:confirm="Are you sure you want to completely remove this partner?" class="text-iba-red hover:text-red-900 font-bold uppercase text-xs tracking-wider">
                                    Drop
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            No partners added yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    

    {{-- MODAL: EDIT PARTNER --}}
    @if($editModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModals"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6 text-left">
                <div class="relative w-full sm:max-w-2xl flex flex-col bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#0095AC] overflow-hidden">

                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-4 border-iba-black dark:border-iba-light flex justify-between items-center">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Edit Partner Details</h3>
                        <button wire:click="closeModals" class="text-gray-400 hover:text-iba-red"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    <form wire:submit.prevent="updatePartner">
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Info Fields --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Organization Name</label>
                                <input type="text" wire:model="edit_name" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                @error('edit_name') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Assigned Role</label>
                                <input type="text" wire:model="edit_role" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                @error('edit_role') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Logo Emphasis</label>
                                <select wire:model="edit_emphasis" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                    <option value="medium">Medium</option>
                                    <option value="small">Small</option>
                                </select>
                                @error('edit_emphasis') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sort Order</label>
                                <input type="number" wire:model="edit_display_order" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                            </div>

                            {{-- Optional Image Replacement --}}
                            <div class="md:col-span-2 mt-2 pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Partner Logo</label>
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">

                                    {{-- Preview --}}
                                    <div class="w-24 h-16 bg-gray-900 border-2 border-iba-black flex items-center justify-center p-2 shadow-[2px_2px_0_0_#131011] shrink-0">
                                        @if ($new_logo)
                                            <img src="{{ $new_logo->temporaryUrl() }}" class="max-h-full max-w-full object-contain">
                                        @else
                                            <img src="{{ Storage::url($existing_logo_path) }}" class="max-h-full max-w-full object-contain">
                                        @endif
                                    </div>

                                    {{-- Upload Input --}}
                                    <div class="flex-1">
                                        <input type="file" wire:model.live="new_logo" accept="image/png, image/webp, image/jpeg" class="w-full text-sm font-bold uppercase text-gray-500 cursor-pointer">
                                        <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">Leave empty to keep the existing logo.</p>
                                        @error('new_logo') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror

                                        <div wire:loading wire:target="new_logo" class="text-xs font-bold text-iba-teal mt-1 animate-pulse uppercase tracking-widest">Processing...</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t-4 border-iba-black dark:border-iba-light flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black transition-colors">Cancel</button>
                            <button type="submit" class="bg-iba-teal text-white font-bold px-8 py-2.5 text-sm uppercase border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1" wire:loading.attr="disabled">Save Updates</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>