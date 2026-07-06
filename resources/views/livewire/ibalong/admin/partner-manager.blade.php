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
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Custom Role</label>
                    <input type="text" wire:model="role" placeholder="e.g., Lead Consortium Host" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                    @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Logo Emphasis</label>
                    <select wire:model="emphasis" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal">
                        <option value="medium">Medium (Standard)</option>
                        <option value="small">Small</option>
                    </select>
                    @error('emphasis') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sort Order</label>
                    <input type="number" wire:model="display_order" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm focus:border-iba-teal focus:ring-iba-teal" min="0">
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Upload Transparent Logo (PNG/WEBP)</label>
                    <input type="file" wire:model="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-300">
                    @error('logo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="lg:col-span-2 flex items-end h-full">
                    <button type="submit" class="w-full mt-2 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-iba-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iba-teal transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="logo">Save Partner</span>
                        <span wire:loading wire:target="logo">Uploading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Partners Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
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
