<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-heading font-bold text-lg text-gray-800">Manage {{ \Illuminate\Support\Str::plural($title) }}</h2>
        <button wire:click="create" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold uppercase rounded-lg hover:bg-red-600 transition">
            + Add {{ $title }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-100">
            {{ session('message') }}
        </div>
    @endif

    {{-- LIST TABLE --}}
    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            @if($item->color)
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $item->color }}; box-shadow: 0 0 0 1px #e5e7eb;"></div>
                            @endif
                            <span class="text-sm font-bold text-gray-900">{{ $item->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs text-gray-500">{{ Str::limit($item->description, 50) ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button wire:click="edit({{ $item->id }})" class="text-gray-400 hover:text-blue-600 mr-3">Edit</button>
                        {{-- Add a proper delete confirmation here --}}
                        <button wire:click="confirmDelete({{ $item->id }})" class="text-gray-400 hover:text-red-600">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-xs text-gray-400">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $items->links() }}
    </div>

    {{-- CREATE/EDIT MODAL --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg text-gray-900 mb-4">{{ $selectedId ? 'Edit' : 'Create' }} {{ $title }}</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Name</label>
                    <input wire:model="name" type="text" class="w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Description (Optional)</label>
                    <textarea wire:model="description" rows="3" class="w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Color Code (Optional)</label>
                    <div class="flex items-center gap-2">
                        <input wire:model.live="color" type="color" class="h-10 w-10 border-none p-0 bg-transparent cursor-pointer rounded overflow-hidden">
                        <input wire:model.live="color" type="text" placeholder="#000000" class="flex-1 border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500 uppercase">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition">Cancel</button>
                <button wire:click="store" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition">Save</button>
            </div>
        </div>
    </div>
    @endif
</div>