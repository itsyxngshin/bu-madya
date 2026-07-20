<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-[#1A1617] p-6 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <div>
            <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Event Control Center</h1>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Create, manage, and monitor ticketing for all upcoming sessions.</p>
        </div>
        <button wire:click="openModal" class="bg-iba-teal text-white font-bold px-6 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
            + Deploy New Event
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Events List Table --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] overflow-x-auto">
        <table class="min-w-full divide-y-2 divide-iba-black dark:divide-iba-light">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Event Details</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Schedule & Venue</th>
                    <th class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-[#1A1617]">
                @forelse($events as $event)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-iba-black dark:text-white uppercase">{{ $event->title }}</div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                <span class="px-2 py-0.5 border border-gray-300 dark:border-gray-600 rounded bg-gray-50 dark:bg-gray-800 font-bold uppercase text-[9px] tracking-widest">{{ $event->type }}</span>
                                <span class="font-bold">/{{ $event->slug }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800 dark:text-gray-200 font-bold uppercase">
                                {{ $event->start_datetime->format('M d, Y') }} • {{ $event->start_datetime->format('h:i A') }}
                            </div>
                            <div class="text-xs text-gray-500 font-bold uppercase mt-1 truncate max-w-[200px]" title="{{ $event->venue_or_link }}">
                                📍 {{ $event->venue_or_link ?: 'TBA' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleStatus({{ $event->id }})" class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 {{ $event->is_active ? 'border-iba-green text-iba-green hover:bg-iba-green hover:text-white' : 'border-gray-400 text-gray-500 hover:bg-gray-400 hover:text-white' }} transition-colors">
                                {{ $event->is_active ? 'Live' : 'Draft' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex justify-end gap-3 items-center">
                                <button wire:click="viewRegistrants({{ $event->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-bold uppercase text-xs tracking-wider flex items-center gap-1">
                                    Attendees ({{ $event->registrations_count }})
                                </button>
                                <a href="{{ route('ibalong.events.scanner', $event->slug) }}" target="_blank" class="text-iba-orange hover:text-orange-700 font-bold uppercase text-xs tracking-wider flex items-center gap-1">
                                    📷 Scanner
                                </a>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button wire:click="edit({{ $event->id }})" class="text-iba-teal hover:text-teal-700 dark:hover:text-teal-400 font-bold uppercase text-xs tracking-wider">Edit</button>
                                
                                {{-- FACILITATOR RBAC: Hide Delete Event Button --}}
                                @if(auth('ibalong')->user()->role_id != 5)
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <button wire:click="delete({{ $event->id }})" wire:confirm="Are you sure you want to delete this event?" class="text-iba-red hover:text-red-700 dark:hover:text-red-400 font-bold uppercase text-xs tracking-wider">Drop</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center">
                            <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wider">No events found in the database.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- REGISTRANTS ROSTER MODAL --}}
    @if($isRegistrantsModalOpen && $selectedEvent)
        <div class="fixed inset-0 z-40 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeRegistrantsModal"></div>

            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-5xl bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7]">

                    <div class="px-6 py-4 border-b-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Passenger Manifest</h3>
                            <p class="text-xs font-bold text-iba-teal uppercase mt-1">{{ $selectedEvent->title }}</p>
                        </div>
                        <button wire:click="closeRegistrantsModal" class="text-gray-500 hover:text-iba-red transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-0 overflow-y-auto max-h-[60vh]">
                        <table class="min-w-full divide-y-2 divide-iba-black/20 dark:divide-iba-light/20">
                            <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Attendee</th>
                                    <th class="px-4 py-3 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Affiliation / Team</th>
                                    <th class="px-4 py-3 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Ticket Code</th>
                                    <th class="px-4 py-3 text-right text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-200 dark:divide-gray-700 bg-white dark:bg-[#1A1617]">
                                @forelse($registrants as $reg)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-bold text-iba-black dark:text-white uppercase">{{ $reg->name }}</div>
                                            <div class="text-xs text-gray-500 font-bold mt-1">{{ $reg->email }}</div>
                                            <span class="inline-block mt-1 px-2 py-0.5 border border-iba-black dark:border-gray-600 bg-gray-50 dark:bg-gray-800 font-bold uppercase text-[9px] tracking-widest">{{ $reg->role }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($reg->team)
                                                <div class="text-sm font-bold text-iba-orange uppercase">⭐ {{ $reg->team->team_name }}</div>
                                                <div class="text-xs text-gray-500 font-bold mt-1 uppercase">Official Master Team</div>
                                            @else
                                                <div class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">{{ $reg->affiliation ?: 'N/A' }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="font-pixel text-[10px] text-iba-teal">{{ $reg->ticket_code }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button wire:click="showQr('{{ $reg->ticket_code }}', '{{ addslashes($reg->name) }}')" class="bg-iba-black text-white dark:bg-iba-light dark:text-iba-black px-4 py-2 text-[10px] font-bold uppercase border-2 border-transparent hover:translate-y-0.5 transition-all">
                                                View QR
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm font-bold text-gray-500 uppercase">No attendees registered yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- QR CODE GENERATOR MODAL --}}
    @if($isQrModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="closeQrModal"></div>

            <div class="relative w-full max-w-sm bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] p-6 text-center animate-fade-in-up">
                <button wire:click="closeQrModal" class="absolute top-3 right-3 text-gray-400 hover:text-iba-red transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <h4 class="text-sm font-black text-iba-black dark:text-white uppercase mb-4 tracking-widest border-b-2 border-dashed border-gray-300 pb-2">{{ $activeRegistrantName }}</h4>

                <div class="flex justify-center mb-4">
                    <div class="border-4 border-iba-black bg-white p-2">
                        <img src="{{ $activeQrUri }}" alt="Ticket QR Code" class="w-48 h-48 object-contain">
                    </div>
                </div>

                <p class="font-pixel text-xs text-iba-teal tracking-widest">{{ $activeTicketCode }}</p>
                <p class="text-[10px] font-bold text-gray-500 uppercase mt-4">Scan for Attendance</p>
            </div>
        </div>
    @endif

    {{-- CREATE/EDIT EVENT MODAL --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-left">
                <div class="relative w-full max-w-2xl bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7]">

                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">
                            {{ $isEditMode ? 'Update Event Settings' : 'Initialize New Event' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-iba-red transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Modal Body Form --}}
                    <form wire:submit.prevent="store">
                        <div class="p-6 space-y-5">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Event Title <span class="text-iba-red">*</span></label>
                                    <input type="text" wire:model.live.debounce.500ms="title" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                    @error('title') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">URL Slug <span class="text-iba-red">*</span></label>
                                    <input type="text" wire:model="slug" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-gray-100 dark:bg-gray-800 text-iba-black dark:text-white font-bold">
                                    @error('slug') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Event Type <span class="text-iba-red">*</span></label>
                                    <select wire:model="type" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold uppercase">
                                        <option value="Physical">Physical (In-Person)</option>
                                        <option value="Online">Online (Virtual)</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Venue or Meeting Link</label>
                                    <input type="text" wire:model="venue_or_link" placeholder="e.g. Bicol University MPB or Zoom URL" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Start Time <span class="text-iba-red">*</span></label>
                                    <input type="datetime-local" wire:model="start_datetime" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold cursor-pointer">
                                    @error('start_datetime') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">End Time <span class="text-iba-red">*</span></label>
                                    <input type="datetime-local" wire:model="end_datetime" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold cursor-pointer">
                                    @error('end_datetime') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Max Capacity (Leave blank for unlimited)</label>
                                    <input type="number" wire:model="max_capacity" min="1" placeholder="e.g. 500" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                                </div>

                                <div class="md:col-span-2 mt-2">
                                    <label class="flex items-center gap-3 cursor-pointer p-4 border-2 border-dashed border-iba-black dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                                        <input type="checkbox" wire:model="allow_self_checkin" class="w-5 h-5 border-2 border-iba-black text-iba-teal focus:ring-iba-teal cursor-pointer">
                                        <span class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider">
                                            Allow Self Check-In Kiosk
                                            <p class="text-[10px] font-bold text-gray-500 mt-1">If checked, anyone with the scanner link can scan tickets. If unchecked, only logged-in Admins/Facilitators can scan.</p>
                                        </span>
                                    </label>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Internal Notes / Description</label>
                                    <textarea wire:model="description" rows="3" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-teal bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="px-6 py-4 border-t-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 flex justify-end gap-4">
                            <button type="button" wire:click="closeModal" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black dark:text-gray-400 dark:hover:text-white transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="bg-iba-teal text-white font-bold px-8 py-2.5 text-sm uppercase border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                                Save Event Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>