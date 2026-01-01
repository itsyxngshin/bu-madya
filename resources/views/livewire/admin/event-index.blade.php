<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    
    {{-- Header & Search --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-bold text-2xl text-gray-800">Manage Events</h2>
        <a href="{{ route('admin.events.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-md transition">
            + Create New Event
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Search Bar --}}
        <div class="p-4 border-b border-gray-100">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search events..." class="w-full md:w-64 rounded-lg border-gray-300 text-sm focus:ring-red-500">
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">Event</th>
                        <th class="p-4">Schedule</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($events as $event)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($event->cover_image)
                                    <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400 font-bold text-xs">IMG</div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900">{{ $event->title }}</div>
                                    <div class="text-xs text-gray-400">{{ Str::limit(strip_tags($event->description), 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 whitespace-nowrap">
                            @if($event->start_date)
                                <div>{{ $event->start_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $event->start_date->format('h:i A') }}</div>
                            @else
                                <span class="text-gray-400 italic">TBA</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($event->is_active)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold uppercase">Published</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs font-bold uppercase">Draft</span>
                            @endif
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="text-blue-500 hover:underline text-xs font-bold">View</a>
                            
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="text-gray-600 hover:text-gray-900 text-xs font-bold border border-gray-300 px-3 py-1 rounded hover:bg-gray-50 transition">
                                Edit
                            </a>
                            
                            <button wire:click="delete({{ $event->id }})" 
                                    wire:confirm="Are you sure you want to delete this event?"
                                    class="text-red-600 hover:text-red-900 text-xs font-bold border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400">No events found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $events->links() }}
        </div>
    </div>
</div>