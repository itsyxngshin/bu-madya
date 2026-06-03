<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8 animate-fade-in-up">

    @if(!$activeMeetingId)
        {{-- ========================================================== --}}
        {{-- VIEW 1: MEETING DASHBOARD (List of all meetings)           --}}
        {{-- ========================================================== --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Meeting Proceedings</h1>
                <p class="text-sm text-gray-500 mt-1">Manage agendas, minutes, and track attendance.</p>
            </div>
            {{-- CHANGED: Now calls openCreateModal --}}
            <button wire:click="openCreateModal" class="w-full sm:w-auto px-6 py-3 bg-gray-900 text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-black transition active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Schedule Meeting
            </button>
        </div>

        @if(session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mb-6 shadow-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($meetings as $meeting)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-5 sm:p-6 flex flex-col h-full relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 {{ $meeting->status === 'completed' ? 'bg-green-500' : 'bg-blue-500' }}"></div>

                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-gray-50 rounded-xl px-3 py-2 text-center border border-gray-100">
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $meeting->meeting_date->format('M') }}</span>
                            <span class="block text-xl font-black text-gray-900 leading-none">{{ $meeting->meeting_date->format('d') }}</span>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-md {{ $meeting->status === 'completed' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ $meeting->status }}
                            </span>
                            <span class="text-[9px] font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded uppercase tracking-wider">
                                AY {{ $meeting->academicYear->name ?? $meeting->academicYear->year ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-black text-lg text-gray-900 leading-tight mb-2">{{ $meeting->title }}</h3>
                    <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="truncate">{{ $meeting->start_time->format('h:i A') }} • {{ $meeting->location ?? 'TBA' }}</span>
                    </p>

                    {{-- CHANGED: Added Edit & Delete to Action Bar --}}
                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center gap-2">
                        <button wire:click="openMeeting({{ $meeting->id }})" class="flex-1 py-2.5 bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 text-xs font-black uppercase tracking-widest rounded-xl transition-colors">
                            Enter Meeting
                        </button>

                        <button wire:click="openEditModal({{ $meeting->id }})" class="p-2.5 bg-gray-50 hover:bg-orange-50 text-gray-500 hover:text-orange-600 rounded-xl transition-colors" title="Edit Meeting">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>

                        <button wire:click="deleteMeeting({{ $meeting->id }})" wire:confirm="Are you sure you want to permanently delete this meeting? All attendance records and minutes will be lost." class="p-2.5 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 rounded-xl transition-colors" title="Delete Meeting">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No meetings scheduled yet.</p>
                </div>
            @endforelse
        </div>

    @else
        {{-- ========================================================== --}}
        {{-- VIEW 2: THE MEETING ROOM (Minutes & Attendance)            --}}
        {{-- ========================================================== --}}
        <div class="mb-6 flex items-center justify-between">
            <button wire:click="closeMeeting" class="flex items-center gap-1.5 sm:gap-2 text-[10px] sm:text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Dashboard
            </button>
            <span class="px-2 sm:px-3 py-1 text-[9px] sm:text-[10px] font-black uppercase tracking-widest rounded-lg {{ $activeMeeting->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                Status: {{ $activeMeeting->status }}
            </span>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'attendance' }">

            {{-- Meeting Header --}}
            <div class="p-5 sm:p-6 md:p-8 bg-gray-900 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-black mb-3 relative z-10">{{ $activeMeeting->title }}</h2>
                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-4 text-xs font-medium text-gray-400 relative z-10">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $activeMeeting->meeting_date->format('F d, Y') }}</span>
                    <span class="hidden sm:inline">•</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $activeMeeting->start_time->format('h:i A') }}</span>
                    <span class="hidden sm:inline">•</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> <span class="truncate max-w-[200px]">{{ $activeMeeting->location ?? 'TBA' }}</span></span>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'border-orange-500 text-orange-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="flex-1 py-4 text-[10px] sm:text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                    Attendance ({{ $activeMeeting->attendees->count() }})
                </button>
                <button @click="tab = 'minutes'" :class="tab === 'minutes' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="flex-1 py-4 text-[10px] sm:text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                    Agenda & Minutes
                </button>
            </div>

            <div class="p-4 sm:p-6 md:p-8">

                {{-- TAB 1: MINUTES --}}
                <div x-show="tab === 'minutes'" x-cloak class="space-y-6">
                    @if(session()->has('minutes_success'))
                        <div class="p-3 bg-green-50 text-green-700 text-xs font-bold rounded-lg">{{ session('minutes_success') }}</div>
                    @endif

                    @if($activeMeeting->agenda)
                        <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                            <h4 class="text-[10px] font-black text-blue-800 uppercase tracking-widest mb-2">Meeting Agenda</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $activeMeeting->agenda }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-3">Official Minutes of the Meeting</label>
                        <textarea wire:model="minutes" rows="12" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 sm:p-5 text-sm focus:bg-white focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Start typing the minutes here..."></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button wire:click="saveMinutes" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-sm transition">Save Minutes</button>
                        @if($activeMeeting->status !== 'completed')
                            <button wire:click="markCompleted" class="w-full sm:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-sm transition">Mark as Completed</button>
                        @endif
                    </div>
                </div>

                {{-- TAB 2: ATTENDANCE & QR SCANNER --}}
                <div x-show="tab === 'attendance'" x-cloak class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">

                    {{-- Left Side: Control Panel (Scanner & Search) --}}
                    <div class="space-y-6 order-2 lg:order-1">

                        {{-- 1. Live Search Dropdown --}}
                        <div class="bg-gray-50 p-4 sm:p-5 rounded-3xl border border-gray-200 relative">
                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Add Attendee Manually
                            </h4>

                            <div class="relative w-full">
                                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search Name or ID..." class="w-full bg-white border border-gray-200 text-xs sm:text-sm font-semibold rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">

                                <div wire:loading wire:target="searchQuery" class="absolute right-4 top-3.5">
                                    <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>

                            @if(strlen($searchQuery) >= 2)
                                <div class="absolute z-50 left-4 right-4 sm:left-5 sm:right-5 mt-2 bg-white border border-gray-100 shadow-xl rounded-2xl overflow-hidden max-h-60 overflow-y-auto custom-scrollbar">
                                    @forelse($searchResults as $user)
                                        @php
                                            $path = $user['profile_photo_path'] ?? null;
                                            $avatar = $path ? (Str::startsWith($path, ['http', 'images/']) ? asset($path) : asset('storage/' . $path)) : 'https://ui-avatars.com/api/?name='.urlencode($user['name']).'&background=eff6ff&color=2563eb';
                                        @endphp
                                        <div class="flex items-center justify-between p-3 border-b border-gray-50 hover:bg-blue-50 transition group">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $avatar }}" class="w-8 h-8 rounded-full object-cover shadow-sm shrink-0">
                                                <div>
                                                    <p class="text-[11px] sm:text-xs font-bold text-gray-900 leading-none group-hover:text-blue-700">{{ $user['name'] }}</p>
                                                    <p class="text-[9px] font-mono text-gray-500 mt-0.5">ID: {{ $user['id'] ?? $user['username'] }}</p>
                                                </div>
                                            </div>
                                            <button wire:click="addManualAttendee({{ $user['id'] }})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-900 text-gray-700 hover:text-white text-[10px] font-bold uppercase tracking-widest rounded-lg transition">Add</button>
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">No members found.</div>
                                    @endforelse
                                </div>
                            @endif
                        </div>

                        {{-- 2. HTML5 QR Scanner --}}
                        <div class="bg-gray-900 p-1 rounded-3xl shadow-lg relative overflow-hidden hidden sm:block">
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-500 via-orange-500 to-blue-500"></div>
                            <div id="reader" class="w-full h-auto min-h-[250px] sm:min-h-[300px] bg-black rounded-[1.3rem] overflow-hidden border-none"></div>

                            <div class="p-3 sm:p-4 text-center">
                                <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span></span>
                                    Live QR Scanner Active
                                </p>
                                <p class="text-[9px] sm:text-[10px] text-gray-500 mt-1">Hold the student's QR ID up to the camera.</p>
                            </div>
                        </div>

                        <div class="sm:hidden text-center p-4 bg-orange-50 rounded-2xl border border-orange-100">
                             <p class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">Scanner Optimization</p>
                             <p class="text-xs text-gray-600 mt-1">For optimal scanning, please use a tablet or laptop device.</p>
                        </div>

                    </div>

                    {{-- Right Side: The Live Attendance Directory --}}
                    <div class="flex flex-col h-[400px] sm:h-[500px] lg:h-[600px] order-1 lg:order-2">
                        <h3 class="text-xs sm:text-sm font-black text-gray-900 uppercase tracking-widest mb-3 sm:mb-4 flex items-center justify-between shrink-0">
                            Present Attendees
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-md text-[10px]">{{ $activeMeeting->attendees->count() }} Scanned</span>
                        </h3>

                        <div class="bg-gray-50 border border-gray-100 rounded-3xl shadow-inner overflow-hidden flex-1 flex flex-col relative">

                            {{-- Success/Error Alerts (Triggered via JS Events) --}}
                            <div id="scan-alert" style="display: none;" class="absolute top-0 inset-x-0 z-10 px-4 py-2 text-center text-[10px] sm:text-xs font-bold uppercase tracking-widest text-white transition-colors duration-300 shadow-md"></div>

                            <div class="overflow-y-auto custom-scrollbar p-3 sm:p-4 flex-1">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                                    @forelse($activeMeeting->attendees->sortByDesc('time_in') as $attendee)
                                        @php
                                            $user = \App\Models\User::with(['currentAssignment.director', 'currentAssignment.committee', 'committeeMember'])
                                                ->where('id', $attendee->student_id)
                                                ->orWhere('username', $attendee->student_id)
                                                ->first();

                                            $path = $user?->profile_photo_path;
                                            $avatarUrl = $path ? (Str::startsWith($path, 'http') ? $path : (Str::startsWith($path, 'images/') ? asset($path) : asset('storage/' . $path))) : 'https://ui-avatars.com/api/?name='.urlencode($attendee->name ?? $attendee->student_id).'&background=eff6ff&color=2563eb&bold=true';

                                            $roleName = 'Guest / Unregistered';
                                            $roleColor = 'bg-gray-100 text-gray-500';

                                            if ($user) {
                                                if ($user->isDirectorGeneral()) {
                                                    $roleName = $user->currentAssignment->director->name ?? 'Director-General';
                                                    $roleColor = 'bg-red-50 text-red-600 border border-red-100';
                                                } elseif ($user->isCommitteeDirector()) {
                                                    $dirTitle = $user->currentAssignment->director->name ?? 'Director';
                                                    $comTitle = $user->currentAssignment->committee->name ?? '';
                                                    $roleName = $comTitle ? "$dirTitle - $comTitle" : $dirTitle;
                                                    $roleColor = 'bg-orange-50 text-orange-600 border border-orange-100';
                                                } elseif ($user->committeeMember) {
                                                    $roleName = 'Committee Member';
                                                    $roleColor = 'bg-blue-50 text-blue-600 border border-blue-100';
                                                } else {
                                                    $roleName = 'Member';
                                                    $roleColor = 'bg-gray-50 text-gray-600 border border-gray-200';
                                                }
                                            }
                                        @endphp

                                        {{-- Directory Card --}}
                                        <div class="bg-white border border-gray-100 rounded-2xl p-3 sm:p-4 flex flex-col items-center text-center shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group relative">

                                            {{-- Remove Button --}}
                                            <button wire:click="removeAttendee({{ $attendee->id }})"
                                                    wire:confirm="Are you sure you want to remove {{ $attendee->name ?? 'this attendee' }} from the meeting?"
                                                    class="absolute top-1.5 left-1.5 text-gray-300 hover:text-red-600 hover:bg-red-50 p-1 rounded-full transition-colors z-10" title="Remove Attendee">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>

                                            <div class="absolute top-2 right-2 text-[8px] font-black text-gray-400 tracking-wider">
                                                {{ $attendee->time_in->format('h:i A') }}
                                            </div>
                                            <div class="w-12 h-12 sm:w-14 sm:h-14 mt-3 mb-2 sm:mb-3 rounded-full overflow-hidden border-2 border-white shadow-sm ring-2 ring-gray-50 group-hover:ring-blue-100 transition-colors shrink-0">
                                                <img src="{{ $avatarUrl }}" class="w-full h-full object-cover" alt="{{ $attendee->name }}">
                                            </div>
                                            <h4 class="text-[10px] sm:text-xs font-black text-gray-900 leading-tight mb-1.5 line-clamp-2">
                                                {{ $attendee->name ?? 'Unknown' }}
                                            </h4>
                                            <span class="text-[8px] sm:text-[9px] font-bold uppercase tracking-widest px-1.5 sm:px-2 py-1 rounded-md mt-auto w-full truncate {{ $roleColor }}" title="{{ $roleName }}">
                                                {{ $roleName }}
                                            </span>
                                            <span class="text-[8px] sm:text-[9px] font-medium text-gray-400 font-mono mt-2 pt-2 border-t border-gray-50 w-full block">
                                                ID: {{ $attendee->student_id }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="col-span-full flex flex-col items-center justify-center py-12 sm:py-16 text-gray-400">
                                            <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest">No attendees logged yet.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- MODAL (CREATE / EDIT)                                      --}}
    {{-- ========================================================== --}}
    <div x-show="isModalOpen" style="display: none;" x-data="{ isModalOpen: @entangle('isCreateModalOpen') }" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" @click="isModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden animate-fade-in-up max-h-[90vh]">
            <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                <h3 class="font-black text-gray-900">{{ $isEditMode ? 'Edit Meeting Details' : 'Schedule Meeting' }}</h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-5 sm:p-6 space-y-4 overflow-y-auto custom-scrollbar">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Academic Year <span class="text-red-500">*</span></label>
                    <select wire:model="academic_year_id" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                        <option value="">Select A.Y...</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}">{{ $ay->name ?? $ay->year }}</option>
                        @endforeach
                    </select>
                    @error('academic_year_id') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Meeting Title</label>
                    <input type="text" wire:model="title" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                    @error('title') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Date</label>
                        <input type="date" wire:model="meeting_date" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                        @error('meeting_date') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Time</label>
                        <input type="time" wire:model="start_time" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                        @error('start_time') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Location</label>
                    <input type="text" wire:model="location" placeholder="e.g. BU CS Room 2" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Agenda Summary</label>
                    <textarea wire:model="agenda" rows="2" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 resize-none"></textarea>
                </div>

                {{-- CHANGED: Calls saveMeeting instead of createMeeting --}}
                <button wire:click="saveMeeting" class="w-full mt-2 py-3 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-blue-700 transition">
                    {{ $isEditMode ? 'Update Meeting' : 'Save Schedule' }}
                </button>
            </div>
        </div>
    </div>

</div>

{{-- INCLUDE HTML5-QRCODE LIBRARY & INITIALIZATION SCRIPT --}}
@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner = null;

    document.addEventListener('livewire:navigated', initScanner);
    document.addEventListener('livewire:initialized', initScanner);

    function initScanner() {
        const readerElement = document.getElementById('reader');

        const qrSize = window.innerWidth < 640 ? 150 : 250;

        if (readerElement && !html5QrcodeScanner) {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: {width: qrSize, height: qrSize},
                aspectRatio: 1.0
            }, false);

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        @this.call('recordAttendance', decodedText);
    }

    function onScanFailure(error) {}

    window.addEventListener('attendance-recorded', event => {
        let alertBox = document.getElementById('scan-alert');
        if(alertBox) {
            alertBox.textContent = `Added: ${event.detail[0].student}`;
            alertBox.className = 'absolute top-0 inset-x-0 z-10 px-4 py-2 text-center text-[10px] sm:text-xs font-bold uppercase tracking-widest text-white transition-colors duration-300 shadow-md bg-green-500';
            alertBox.style.display = 'block';
            setTimeout(() => { alertBox.style.display = 'none'; }, 2000);
        }
    });

    window.addEventListener('attendance-duplicate', event => {
        let alertBox = document.getElementById('scan-alert');
        if(alertBox) {
            alertBox.textContent = `Already Logged: ${event.detail[0].student}`;
            alertBox.className = 'absolute top-0 inset-x-0 z-10 px-4 py-2 text-center text-[10px] sm:text-xs font-bold uppercase tracking-widest text-white transition-colors duration-300 shadow-md bg-orange-500';
            alertBox.style.display = 'block';
            setTimeout(() => { alertBox.style.display = 'none'; }, 2000);
        }
    });

    window.addEventListener('attendance-removed', event => {
        let alertBox = document.getElementById('scan-alert');
        if(alertBox) {
            alertBox.textContent = `Removed: ${event.detail[0].student}`;
            alertBox.className = 'absolute top-0 inset-x-0 z-10 px-4 py-2 text-center text-[10px] sm:text-xs font-bold uppercase tracking-widest text-white transition-colors duration-300 shadow-md bg-red-600';
            alertBox.style.display = 'block';
            setTimeout(() => { alertBox.style.display = 'none'; }, 2000);
        }
    });
</script>
@endpush
