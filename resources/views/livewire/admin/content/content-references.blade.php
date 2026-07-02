<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Content Classifications</h1>
        <p class="text-gray-500 mt-2">Manage the categories and themes available for announcements and spotlights.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200 font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg border border-red-200 font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- LEFT PANEL: Lists --}}
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Tabs --}}
            <div class="flex border-b border-gray-200 bg-gray-50">
                <button wire:click="setTab('announcements')" class="flex-1 py-4 px-6 text-center font-bold text-sm uppercase tracking-widest transition {{ $activeTab === 'announcements' ? 'text-red-600 border-b-2 border-red-600 bg-white' : 'text-gray-500 hover:bg-gray-100' }}">
                    Announcement Types
                </button>
                <button wire:click="setTab('spotlights')" class="flex-1 py-4 px-6 text-center font-bold text-sm uppercase tracking-widest transition {{ $activeTab === 'spotlights' ? 'text-blue-600 border-b-2 border-blue-600 bg-white' : 'text-gray-500 hover:bg-gray-100' }}">
                    Spotlight Categories
                </button>
            </div>

            <div class="p-0">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-900 font-bold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Preview / Usage</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($activeTab === 'announcements')
                            @foreach($types as $type)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $type->name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="{{ $type->color_theme }} px-3 py-1 rounded-md text-xs font-bold flex items-center gap-2 w-max">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $type->icon_svg !!}</svg>
                                                Preview
                                            </div>
                                            <span class="text-xs text-gray-400">Used in {{ $type->announcements_count }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <button wire:click="editType({{ $type->id }})" class="text-blue-600 hover:text-blue-900 font-bold">Edit</button>
                                        <button wire:click="deleteType({{ $type->id }})" wire:confirm="Delete this type?" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-400">Used in {{ $category->spotlights_count }} spotlights</td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <button wire:click="editCategory({{ $category->id }})" class="text-blue-600 hover:text-blue-900 font-bold">Edit</button>
                                        <button wire:click="deleteCategory({{ $category->id }})" wire:confirm="Delete this category?" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT PANEL: Editor Form --}}
        <div class="lg:col-span-4">
            <div class="bg-gray-50 rounded-2xl shadow-inner border border-gray-200 p-6 sticky top-24">

                @if($activeTab === 'announcements')
                    <h3 class="text-lg font-black text-gray-900 mb-4">{{ $type_id ? 'Edit Type' : 'Add New Type' }}</h3>
                    <form wire:submit.prevent="saveType" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Display Name</label>
                            <input type="text" wire:model="type_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="e.g. Weather Alert">
                            @error('type_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Color Theme</label>
                            <select wire:model="color_theme" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 text-sm">
                                @foreach($availableColors as $class => $label)
                                    <option value="{{ $class }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('color_theme') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Icon Selection</label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($availableIcons as $key => $svg)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" wire:model="icon_key" value="{{ $key }}" class="peer sr-only">
                                        <div class="p-3 rounded-xl border border-gray-200 text-center text-gray-500 hover:bg-gray-100 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-600 transition flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $svg !!}</svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('icon_key') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Live Preview --}}
                        <div class="pt-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Live Preview</label>
                            <div class="{{ $color_theme }} px-4 py-3 rounded-lg shadow-sm flex items-center gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    {!! $availableIcons[$icon_key] ?? $availableIcons['info'] !!}
                                </svg>
                                <span class="text-sm font-bold">{{ $type_name ?: 'Type Name' }}:</span>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-2">
                            <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white font-bold py-2 px-4 rounded-lg shadow-md transition">Save Type</button>
                            @if($type_id)
                                <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">Cancel</button>
                            @endif
                        </div>
                    </form>

                @else
                    <h3 class="text-lg font-black text-gray-900 mb-4">{{ $category_id ? 'Edit Category' : 'Add New Category' }}</h3>
                    <form wire:submit.prevent="saveCategory" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category Name</label>
                            <input type="text" wire:model="category_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Job Fair">
                            @error('category_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 flex gap-2">
                            <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white font-bold py-2 px-4 rounded-lg shadow-md transition">Save Category</button>
                            @if($category_id)
                                <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">Cancel</button>
                            @endif
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
