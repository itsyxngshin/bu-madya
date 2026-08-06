<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- Mentor Dashboard Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-widest text-white">Mentor Command Terminal</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Review your scheduled blocks and submit cohort assessments.</p>
        </div>
        <span class="bg-white text-iba-black text-xs font-black uppercase px-4 py-2 border-2 border-transparent">
            {{ $assignedHubs->count() }} Active Hub(s)
        </span>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Hub and Timetable Display --}}
    <div class="space-y-12">
        @forelse($assignedHubs as $hub)
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-0 animate-fade-in-up">

                {{-- Hub Banner --}}
                <div class="bg-gray-100 p-6 border-b-4 border-iba-black">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#131011]">{{ $hub->activity->title }}</span>
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $hub->location ?? 'No Location Set' }}</span>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-widest text-iba-black">{{ $hub->name }}</h2>
                </div>

                {{-- Time Blocks --}}
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($hub->slots as $slot)
                        <div class="border-2 border-iba-black flex flex-col h-full bg-gray-50">
                            {{-- Slot Time Header --}}
                            <div class="bg-iba-black text-white px-4 py-2 border-b-2 border-iba-black flex justify-between items-center">
                                <span class="text-sm font-black uppercase tracking-widest">{{ $slot->start_time->format('h:i A') }} - {{ $slot->end_time->format('h:i A') }}</span>
                                <span class="text-[9px] font-bold uppercase text-gray-400">{{ $slot->appointments->count() }}/{{ $slot->capacity }} Booked</span>
                            </div>

                            {{-- Cohort Appointments --}}
                            <div class="p-4 flex-1 flex flex-col gap-3">
                                @forelse($slot->appointments as $appointment)
                                    @php
                                        $statusColor = match($appointment->status) {
                                            'attended' => 'bg-iba-green text-white',
                                            'no_show' => 'bg-iba-red text-white',
                                            'cancelled' => 'bg-gray-300 text-gray-600',
                                            default => 'bg-white text-iba-black border-2 border-iba-black'
                                        };
                                    @endphp

                                    <div class="border-l-4 border-iba-orange pl-3 py-1 flex flex-col gap-2">
                                        <div>
                                            <h4 class="text-sm font-black uppercase text-iba-black">{{ $appointment->team->team_name ?? 'Unknown Cohort' }}</h4>
                                            <span class="inline-block mt-1 text-[9px] font-black uppercase px-2 py-0.5 {{ $statusColor }} shadow-[2px_2px_0_0_#131011]">
                                                {{ str_replace('_', '-', $appointment->status) }}
                                            </span>
                                        </div>

                                        <button wire:click="openAssessment({{ $appointment->id }})" class="mt-2 bg-gray-200 text-iba-black text-[10px] font-black uppercase tracking-widest py-2 hover:bg-iba-teal hover:text-white transition-colors border-2 border-iba-black">
                                            {{ $appointment->notes ? 'Update Assessment' : 'Evaluate Cohort' }}
                                        </button>
                                    </div>
                                @empty
                                    <div class="flex-1 flex items-center justify-center text-center py-4">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Time Block is Empty</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-gray-500 text-xs font-bold uppercase tracking-widest">
                            No time blocks generated for this hub yet.
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">You have not been assigned to any active hubs.</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL: Assessment & Feedback Terminal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] flex flex-col text-left">

                    <div class="px-6 py-4 border-b-4 border-iba-black bg-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-black text-iba-black uppercase tracking-wider">Cohort Assessment</h3>
                            <p class="text-[10px] font-bold text-iba-teal mt-1 uppercase">{{ $teamName }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-iba-red"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <form wire:submit.prevent="saveAssessment" class="p-6 space-y-6">

                        {{-- Attendance Toggle --}}
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Attendance Status</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach(['booked' => 'Pending', 'attended' => 'Attended', 'no_show' => 'No-Show', 'cancelled' => 'Cancelled'] as $val => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="status" value="{{ $val }}" class="peer sr-only">
                                        <div class="text-center px-2 py-3 border-2 border-gray-300 text-[10px] font-black uppercase text-gray-500 peer-checked:border-iba-black peer-checked:bg-iba-black peer-checked:text-white transition-all shadow-none peer-checked:shadow-[3px_3px_0_0_#FF8623]">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Feedback Box --}}
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Mentor Notes / Feedback</label>
                            <textarea wire:model="notes" rows="6" placeholder="Enter clinical notes, technical feedback, or action items for the cohort..." class="w-full border-2 border-iba-black p-3 font-bold text-sm focus:outline-none focus:border-iba-orange bg-gray-50 resize-none"></textarea>
                            <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase">These notes can be made visible to the command center and the cohort.</p>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" wire:click="closeModal" class="w-full px-6 py-3 border-2 border-iba-black bg-gray-100 text-xs font-black uppercase tracking-widest text-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase text-xs tracking-widest py-3 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">Save Logs</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
