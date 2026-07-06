<div class="max-w-7xl mx-auto space-y-8">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Partner & Enabler Roster</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage the organizations displayed on the public Launchpad.</p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <p class="text-sm font-bold text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Add New Partner Form --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Add New Partner</h3>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="addPartner" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-5 items-start">

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Organization Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                    @error('name') <span class="text-red-500 text-xs mt-1 block font-bold">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Custom Role</label>
                    <input type="text" wire:model="role" placeholder="e.g., Lead Consortium Host" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                    @error('role') <span class="text-red-500 text-xs mt-1 block font-bold">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Logo Emphasis</label>
                    <select wire:model="emphasis" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                        <option value="medium">Medium</option>
                        <option value="small">Small</option>
                    </select>
                    @error('emphasis') <span class="text-red-500 text-xs mt-1 block font-bold">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sort Order</label>
                    <input type="number" wire:model="display_order" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal" min="0">
                </div>

                {{-- DRAG AND DROP UPLOAD ZONE --}}
                <div class="lg:col-span-6 mt-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Transparent Logo (PNG/WEBP)</label>

                    <div x-data="{ isDropping: false }"
                         x-on:dragover.prevent="isDropping = true"
                         x-on:dragleave.prevent="isDropping = false"
                         x-on:drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));"
                         class="relative flex flex-col items-center justify-center w-full p-8 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 ease-in-out"
                         :class="isDropping ? 'border-iba-teal bg-teal-50 dark:bg-teal-900/20 scale-[1.01]' : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700'">

                        {{-- Hidden File Input tied to Livewire --}}
                        <input type="file" x-ref="fileInput" wire:model.live="logo" accept="image/png, image/webp, image/jpeg" class="absolute inset-0 z-50 w-full h-full opacity-0 cursor-pointer">

                        @if ($logo)
                            {{-- Image Preview State --}}
                            <div class="flex flex-col items-center pointer-events-none">
                                <div class="bg-gray-900 p-2 rounded-lg mb-3 shadow-inner border border-gray-700">
                                    <img src="{{ $logo->temporaryUrl() }}" class="h-20 w-auto object-contain">
                                </div>
                                <p class="text-sm font-bold text-iba-teal">Logo staged successfully!</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Click or drag a new file here to replace.</p>
                            </div>
                        @else
                            {{-- Upload Prompt State --}}
                            <div class="text-center pointer-events-none" wire:loading.remove wire:target="logo">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="text-iba-teal underline">Click to upload</span> or drag and drop your file here
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG or WEBP up to 2MB (Transparent Background Recommended)</p>
                            </div>

                            {{-- Processing Upload State --}}
                            <div class="text-center hidden pointer-events-none" wire:loading.class.remove="hidden" wire:target="logo">
                                <svg class="mx-auto h-10 w-10 text-iba-teal animate-spin mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <p class="text-sm font-bold text-iba-teal animate-pulse">Processing Image...</p>
                            </div>
                        @endif
                    </div>
                    @error('logo') <span class="text-red-500 text-xs font-bold mt-2 block">⚠ {{ $message }}</span> @enderror
                </div>

                {{-- Action Button --}}
                <div class="lg:col-span-6 flex justify-end pt-2 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-iba-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iba-teal transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="logo">Publish Partner to Roster</span>
                        <span wire:loading wire:target="logo">Awaiting Upload...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Partners Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        {{-- ... Keep the exact same Partners Table code here ... --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logo & Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assigned Role</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order / Size</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($partners as $partner)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 bg-gray-100 dark:bg-gray-900 rounded flex items-center justify-center p-1 border border-gray-200 dark:border-gray-700">
                                        <img src="{{ Storage::url($partner->logo_path) }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $partner->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-iba-teal">
                                {{ $partner->role }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300 mr-2">#{{ $partner->display_order }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                    {{ $partner->emphasis }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $partner->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                    {{ $partner->is_active ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-3">
                                    <button wire:click="toggleStatus({{ $partner->id }})" class="{{ $partner->is_active ? 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200' : 'text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300' }}">
                                        {{ $partner->is_active ? 'Hide' : 'Show' }}
                                    </button>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <button wire:click="deletePartner({{ $partner->id }})" wire:confirm="Are you sure you want to remove this partner?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No partners added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
