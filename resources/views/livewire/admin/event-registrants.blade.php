<div class="p-6 max-w-7xl mx-auto min-h-screen">

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest mb-2 inline-block transition">&larr; Back to Events</a>
            <h1 class="text-3xl font-black text-gray-900 leading-tight">Registrants</h1>
            <p class="text-sm font-bold text-gray-500 mt-1">{{ $event->title }}</p>
        </div>

        <div class="flex gap-4">
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 text-center">
                <span class="block text-2xl font-black text-gray-900">{{ $stats['total'] }}</span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total</span>
            </div>
            <div class="bg-green-50 px-6 py-3 rounded-2xl shadow-sm border border-green-100 text-center">
                <span class="block text-2xl font-black text-green-700">{{ $stats['attended'] }}</span>
                <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Attended</span>
            </div>
            {{-- Quick Link to Scanner --}}
            <a href="{{ route('admin.events.scan', $event->slug) }}" target="_blank" class="bg-gray-900 text-white px-6 py-3 rounded-2xl shadow-sm hover:bg-gray-800 transition flex flex-col items-center justify-center">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Scanner</span>
            </a>
        </div>
    </div>

    {{-- Filters & Actions --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
            <div class="relative flex-1 max-w-md">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name, email, or ticket code..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 text-sm focus:ring-red-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select wire:model.live="statusFilter" class="rounded-xl border-gray-200 text-sm py-2.5 focus:ring-red-500 font-bold text-gray-600">
                <option value="">All Statuses</option>
                <option value="Registered">Registered (Not Arrived)</option>
                <option value="Attended">Attended</option>
            </select>
        </div>

        {{-- Export CSV Button --}}
        <button wire:click="export" class="w-full md:w-auto bg-gray-900 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-800 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export to Excel
        </button>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Participant Info</th>
                        <th class="px-6 py-4">Classification</th>
                        <th class="px-6 py-4">Ticket Details</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($registrants as $reg)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $reg->name }}</div>
                                <div class="text-xs text-gray-500">{{ $reg->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 text-[10px] font-bold rounded uppercase">{{ $reg->classification }}</span>

                                {{-- Show Student Details --}}
                                @if($reg->classification === 'BU Student')
                                    <div class="text-xs mt-1 text-gray-500">
                                        {{ $reg->program }} ({{ $reg->year_level }})<br>
                                        <span class="font-bold text-gray-700">{{ $reg->college?->name ?? 'Unknown College' }}</span>
                                    </div>

                                {{-- Show Org Details --}}
                                @elseif(in_array($reg->classification, ['CSO/NGO Representative', 'Partner Representative']))
                                    <div class="text-xs mt-1 text-gray-500">
                                        <span class="font-bold">{{ $reg->organization_name }}</span><br>
                                        Role: {{ $reg->position }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-xs font-bold">{{ $reg->ticket_code }}</div>
                                <div class="text-[10px] text-gray-400 mt-1">{{ $reg->created_at->format('M d, g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($reg->status === 'Attended')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Checked In
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Registered
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-bold">
                                No registrants found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $registrants->links() }}
        </div>
    </div>
</div>
