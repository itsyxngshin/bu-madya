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
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">

                                @if($event->is_internal_rsvp)
                                    {{-- Registrants --}}
                                    <a href="{{ route('admin.events.registrants', $event->slug) }}" title="Manage Registrants" 
                                       class="p-2 text-gray-400 hover:text-green-600 bg-gray-50 hover:bg-green-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </a>
                                    
                                    {{-- Scanner --}}
                                    <a href="{{ route('admin.events.scan', $event->slug) }}" target="_blank" title="Launch QR Scanner" 
                                       class="p-2 text-gray-400 hover:text-purple-600 bg-gray-50 hover:bg-purple-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    </a>
                                    
                                    {{-- Raffle --}}
                                    <a href="{{ route('admin.events.raffle', $event->slug) }}" target="_blank" title="Launch Live Raffle" 
                                       class="p-2 text-gray-400 hover:text-orange-600 bg-gray-50 hover:bg-orange-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                    </a>
                                    
                                    {{-- Divider --}}
                                    <div class="w-px h-5 bg-gray-200 mx-1"></div>
                                @endif

                                {{-- View Public Page --}}
                                <a href="{{ route('events.show', $event->slug) }}" target="_blank" title="Preview Public Page" 
                                   class="p-2 text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.events.edit', $event->id) }}" title="Edit Event" 
                                   class="p-2 text-gray-400 hover:text-gray-900 bg-gray-50 hover:bg-gray-200 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                {{-- Delete --}}
                                <button wire:click="delete({{ $event->id }})" wire:confirm="Are you sure you want to delete this event?" title="Delete Event" 
                                        class="p-2 text-gray-400 hover:text-red-600 bg-gray-50 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                
                            </div>
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
