<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8 animate-fade-in-up">

    @if(!$activeMeetingId)
        {{-- ========================================================== --}}
        {{-- VIEW 1: MEETING DASHBOARD (List of all meetings)           --}}
        {{-- ========================================================== --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Meeting Proceedings</h1>
                <p class="text-sm text-gray-500 mt-1">Manage agendas, minutes, and track attendance.</p>
            </div>
            <button wire:click="$set('isCreateModalOpen', true)" class="px-6 py-3 bg-gray-900 text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-black transition active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Schedule Meeting
            </button>
        </div>

        @if(session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($meetings as $meeting)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col h-full relative overflow-hidden group">
                    
                    {{-- Status Ribbon --}}
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
                            {{-- ACADEMIC YEAR BADGE --}}
                            <span class="text-[9px] font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded uppercase tracking-wider">
                                AY {{ $meeting->academicYear->name ?? $meeting->academicYear->year ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-black text-lg text-gray-900 leading-tight mb-2">{{ $meeting->title }}</h3>
                    <p class="text-xs text-gray-500 mb-4 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $meeting->start_time->format('h:i A') }} • {{ $meeting->location ?? 'TBA' }}
                    </p>

                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <button wire:click="openMeeting({{ $meeting->id }})" class="w-full py-2.5 bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 text-xs font-black uppercase tracking-widest rounded-xl transition-colors">
                            Enter Meeting Room
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
            <button wire:click="closeMeeting" class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Back to Dashboard
            </button>
            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg {{ $activeMeeting->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                Status: {{ $activeMeeting->status }}
            </span>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'minutes' }">
            
            {{-- Meeting Header --}}
            <div class="p-6 md:p-8 bg-gray-900 text-white">
                <h2 class="text-2xl md:text-3xl font-black mb-2">{{ $activeMeeting->title }}</h2>
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-gray-400">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $activeMeeting->meeting_date->format('F d, Y') }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $activeMeeting->start_time->format('h:i A') }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $activeMeeting->location ?? 'TBA' }}</span>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'minutes'" :class="tab === 'minutes' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="flex-1 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                    Agenda & Minutes
                </button>
                <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'border-orange-500 text-orange-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="flex-1 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                    Attendance ({{ $activeMeeting->attendees->count() }})
                </button>
            </div>

            <div class="p-6 md:p-8">
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
                        <textarea wire:model="minutes" rows="12" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-5 text-sm focus:bg-white focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Start typing the minutes here..."></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="saveMinutes" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-sm transition">Save Minutes</button>
                        @if($activeMeeting->status !== 'completed')
                            <button wire:click="markCompleted" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-sm transition">Mark as Completed</button>
                        @endif
                    </div>
                </div>

                {{-- TAB 2: ATTENDANCE & QR SCANNER --}}
                <div x-show="tab === 'attendance'" x-cloak class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    {{-- Left Side: The HTML5 QR Scanner --}}
                    <div class="space-y-6">
                        <div class="bg-gray-900 p-1 rounded-3xl shadow-lg relative overflow-hidden">
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-500 via-orange-500 to-blue-500"></div>
                            
                            {{-- Container where HTML5-QRCode injects the camera feed --}}
                            <div id="reader" class="w-full h-auto min-h-[300px] bg-black rounded-[1.3rem] overflow-hidden"></div>
                            
                            <div class="p-4 text-center">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Live QR Scanner Active</p>
                                <p class="text-[10px] text-gray-500 mt-1">Hold the student's ID barcode/QR code up to the camera.</p>
                            </div>
                        </div>

                        {{-- Manual Entry Fallback --}}
                        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200">
                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Manual Entry Fallback</h4>
                            <div class="flex gap-2">
                                <input type="text" wire:model="manualStudentId" placeholder="Student ID" class="w-1/3 text-xs rounded-lg border-gray-200">
                                <input type="text" wire:model="manualName" placeholder="Full Name" class="w-1/2 text-xs rounded-lg border-gray-200">
                                <button wire:click="recordAttendance(manualStudentId, manualName)" class="w-1/6 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-black">Add</button>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: The Live Attendance List --}}
                    <div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center justify-between">
                            Present Attendees 
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-md text-[10px]">{{ $activeMeeting->attendees->count() }} Scanned</span>
                        </h3>
                        
                        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden h-[450px] flex flex-col">
                            
                            {{-- Success/Error Alerts (Triggered via JS Events) --}}
                            <div id="scan-alert" style="display: none;" class="px-4 py-2 text-center text-xs font-bold uppercase tracking-widest text-white transition-colors duration-300"></div>

                            <ul class="divide-y divide-gray-50 overflow-y-auto flex-1 p-2">
                                @forelse($activeMeeting->attendees->sortByDesc('time_in') as $attendee)
                                    <li class="p-3 hover:bg-gray-50 rounded-xl transition flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ substr($attendee->name ?? $attendee->student_id, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900 leading-none">{{ $attendee->name ?? 'Unknown' }}</p>
                                                <p class="text-[10px] text-gray-500 mt-1 font-mono">{{ $attendee->student_id }}</p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-400 shrink-0">{{ $attendee->time_in->format('h:i A') }}</span>
                                    </li>
                                @empty
                                    <li class="text-center py-12 text-gray-400">
                                        <p class="text-xs font-bold uppercase tracking-widest">No attendees scanned yet.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- CREATE MEETING MODAL                                       --}}
    {{-- ========================================================== --}}
    <div x-show="isModalOpen" style="display: none;" x-data="{ isModalOpen: @entangle('isCreateModalOpen') }" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" @click="isModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden animate-fade-in-up">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-black text-gray-900">Schedule Meeting</h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-6 space-y-4">
                
                {{-- ACADEMIC YEAR DROPDOWN --}}
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
                
                <button wire:click="createMeeting" class="w-full mt-2 py-3 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-blue-700 transition">Save Schedule</button>
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
        
        if (readerElement && !html5QrcodeScanner) {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
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
            alertBox.textContent = `Scanned: ${event.detail[0].student}`;
            alertBox.className = 'px-4 py-2 text-center text-xs font-bold uppercase tracking-widest text-white transition-colors duration-300 bg-green-500';
            alertBox.style.display = 'block';
            setTimeout(() => { alertBox.style.display = 'none'; }, 2000);
        }
    });

    window.addEventListener('attendance-duplicate', event => {
        let alertBox = document.getElementById('scan-alert');
        if(alertBox) {
            alertBox.textContent = `Already Logged: ${event.detail[0].student}`;
            alertBox.className = 'px-4 py-2 text-center text-xs font-bold uppercase tracking-widest text-white transition-colors duration-300 bg-red-500';
            alertBox.style.display = 'block';
            setTimeout(() => { alertBox.style.display = 'none'; }, 2000);
        }
    });
</script>
@endpush