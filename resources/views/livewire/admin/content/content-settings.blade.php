<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Content Settings</h1>
            <p class="text-gray-500 mt-2">Manage the live announcements and spotlight banners on the landing page.</p>
        </div>
        <button wire:click="openModal" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Add New
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 bg-gray-50">
            <button wire:click="setTab('announcements')" class="flex-1 py-4 px-6 text-center font-bold text-sm uppercase tracking-widest transition {{ $activeTab === 'announcements' ? 'text-red-600 border-b-2 border-red-600 bg-white' : 'text-gray-500 hover:bg-gray-100' }}">
                Announcements
            </button>
            <button wire:click="setTab('spotlights')" class="flex-1 py-4 px-6 text-center font-bold text-sm uppercase tracking-widest transition {{ $activeTab === 'spotlights' ? 'text-blue-600 border-b-2 border-blue-600 bg-white' : 'text-gray-500 hover:bg-gray-100' }}">
                Spotlights
            </button>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-900 font-bold uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Status / Visibility</th>
                        <th class="px-6 py-4">Details</th>
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($activeTab === 'announcements')
                        @forelse($announcements as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <button wire:click="toggleActive({{ $item->id }}, 'announcement')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $item->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $item->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div class="mt-1 text-xs {{ $item->status === 'approved' ? 'text-green-600' : ($item->status === 'pending' ? 'text-orange-500' : 'text-red-600') }}">
                                        {{ ucfirst($item->status) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $item->type->name ?? 'Unknown' }}</span>
                                    <div class="font-bold text-gray-900 text-base">{{ $item->title }}</div>
                                    <div class="text-gray-500 truncate max-w-md">{{ $item->message }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div><span class="font-bold">Start:</span> {{ $item->start_at ? $item->start_at->format('M d, Y h:i A') : 'Immediately' }}</div>
                                    <div><span class="font-bold">End:</span> {{ $item->end_at ? $item->end_at->format('M d, Y h:i A') : 'Never' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button wire:click="openModal({{ $item->id }})" class="text-blue-600 hover:text-blue-900 font-bold">Edit</button>
                                    <button wire:click="deleteRecord({{ $item->id }}, 'announcement')" wire:confirm="Are you sure you want to delete this?" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No announcements found.</td></tr>
                        @endforelse
                    @else
                        @forelse($spotlights as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <button wire:click="toggleActive({{ $item->id }}, 'spotlight')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $item->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $item->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div class="mt-1 text-xs {{ $item->status === 'approved' ? 'text-green-600' : ($item->status === 'pending' ? 'text-orange-500' : 'text-red-600') }}">
                                        {{ ucfirst($item->status) }}
                                    </div>
                                    <div class="mt-1 text-xs font-mono text-gray-400">Order: {{ $item->sort_order }}</div>
                                </td>
                                <td class="px-6 py-4 flex gap-4 items-center">
                                    <img src="{{ Storage::url($item->image_path) }}" class="w-24 h-12 object-cover rounded shadow-sm border border-gray-200">
                                    <div>
                                        <span class="text-xs font-bold text-gray-500 uppercase">{{ $item->category->name ?? 'Unknown' }}</span>
                                        <div class="font-bold text-gray-900 text-base">{{ $item->title }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div><span class="font-bold">Start:</span> {{ $item->start_at ? $item->start_at->format('M d, Y h:i A') : 'Immediately' }}</div>
                                    <div><span class="font-bold">End:</span> {{ $item->end_at ? $item->end_at->format('M d, Y h:i A') : 'Never' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button wire:click="openModal({{ $item->id }})" class="text-blue-600 hover:text-blue-900 font-bold">Edit</button>
                                    <button wire:click="deleteRecord({{ $item->id }}, 'spotlight')" wire:confirm="Are you sure you want to delete this?" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No spotlights found.</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 md:p-8 shadow-2xl my-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-gray-900">{{ $isEditing ? 'Edit' : 'Create' }} {{ ucfirst(substr($activeTab, 0, -1)) }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-5">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Title</label>
                        <input type="text" wire:model="title" class="w-full border-gray-300 rounded-lg shadow-sm">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($activeTab === 'announcements')
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Type</label>
                            <select wire:model="type_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">Select Type</option>
                                @foreach($announcementTypes as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                            </select>
                            @error('type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                            <textarea wire:model="message" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                            @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>
                                <select wire:model="category_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                                    <option value="">Select Category</option>
                                    @foreach($spotlightCategories as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                                <input type="number" wire:model="sort_order" class="w-full border-gray-300 rounded-lg shadow-sm">
                                @error('sort_order') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Link (Optional)</label>
                            <input type="url" wire:model="link" class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Banner Image</label>
                            @if($existing_image && !$image)
                                <img src="{{ Storage::url($existing_image) }}" class="h-20 object-cover rounded mb-2 border border-gray-200">
                            @endif
                            <input type="file" wire:model="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('image') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Start Date & Time (Optional)</label>
                            <input type="datetime-local" wire:model="start_at" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">End Date & Time (Optional)</label>
                            <input type="datetime-local" wire:model="end_at" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-red-600 focus:ring-red-500 w-5 h-5">
                        <label for="is_active" class="text-sm font-bold text-gray-900">Make this active immediately</label>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
                        <button type="button" wire:click="$set('showModal', false)" class="px-6 py-2 rounded-lg font-bold text-gray-600 hover:bg-gray-100 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2 rounded-lg font-bold text-white bg-gray-900 hover:bg-black shadow-lg transition">
                            <span wire:loading.remove wire:target="save">Save Changes</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>