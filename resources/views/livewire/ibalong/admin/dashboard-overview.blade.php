<div class="max-w-7xl mx-auto space-y-8 pb-24">

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between animate-pulse">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-iba-red/10 border-l-4 border-iba-red p-4 flex items-center justify-between animate-pulse">
            <p class="text-sm font-bold text-iba-red uppercase tracking-wider">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- ADMIN VIEW --}}
    {{-- ========================================== --}}
    @if($isAdmin)
        <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7]">
            <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">System Overview</h1>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Live metrics and rapid action center.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#0095AC]">
                <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Total Intake</dt>
                <dd class="text-4xl font-pixel text-iba-black dark:text-white">{{ $stats['total'] }}</dd>
            </div>
            <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#FF8623]">
                <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Awaiting Review</dt>
                <dd class="text-4xl font-pixel text-iba-orange dark:text-iba-orange">{{ $stats['pending'] }}</dd>
            </div>
            <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#10B981]">
                <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Approved</dt>
                <dd class="text-4xl font-pixel text-iba-green">{{ $stats['approved'] }}</dd>
            </div>
            <div class="bg-white dark:bg-[#1A1617] p-5 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#EF4444]">
                <dt class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 truncate tracking-wider mb-2">Rejected</dt>
                <dd class="text-4xl font-pixel text-iba-red">{{ $stats['rejected'] }}</dd>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-8 shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7]">
            <h2 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-6">Quick Directives & Controls</h2>

            <div class="flex flex-col sm:flex-row flex-wrap gap-4 items-start sm:items-center justify-between border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-8 mb-8">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('ibalong.admin.registrants') ?? '#' }}" class="bg-iba-teal text-white font-bold px-6 py-2.5 text-sm uppercase border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                        Manage Registrants
                    </a>
                    <a href="#" class="bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold px-6 py-2.5 text-sm uppercase border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                        Export Data (CSV)
                    </a>
                </div>

                <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-900 p-4 border-4 border-iba-black dark:border-iba-light w-full sm:w-auto mt-4 sm:mt-0">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">Portal Status</p>
                        <p class="text-sm font-black uppercase tracking-wider {{ $isRegistrationOpen ? 'text-iba-green' : 'text-iba-red' }}">
                            {{ $isRegistrationOpen ? 'ACCEPTING APPLICANTS' : 'INTAKE LOCKED' }}
                        </p>
                    </div>

                    <button wire:click="toggleRegistration" class="ml-auto font-bold px-6 py-2 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1 {{ $isRegistrationOpen ? 'bg-iba-red text-white' : 'bg-iba-green text-white dark:border-iba-light' }}">
                        {{ $isRegistrationOpen ? 'LOCK PORTAL' : 'OPEN PORTAL' }}
                    </button>
                </div>
            </div>
        </div>

    {{-- ========================================== --}}
    {{-- TEAM VIEW --}}
    {{-- ========================================== --}}
    @else
        {{-- Profile Completion Check --}}
        @php
            $hasMissingPhotos = !$team->logo_path || ($team->members && $team->members->contains(function($m) { return empty($m->photo_path); }));
        @endphp

        @if($hasMissingPhotos)
            <div class="bg-iba-orange/10 border-l-4 border-iba-orange p-4 flex items-center shadow-sm">
                <svg class="w-6 h-6 text-iba-orange mr-3 shrink-0 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-xs font-black text-iba-black dark:text-white uppercase tracking-wider">
                    Action Required: Please complete your team profile by uploading your <span class="text-iba-orange">team logo</span> and <span class="text-iba-orange">member photos</span>. Max 10MB per file.
                </p>
            </div>
        @endif

        {{-- Welcome & Overview --}}
        <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] overflow-hidden">
            <div class="bg-iba-orange px-6 py-3 border-b-4 border-iba-black">
                <h1 class="text-sm font-black text-iba-black uppercase tracking-widest">Cohort Command Center</h1>
            </div>
            
            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                
                {{-- Team Logo Upload Section --}}
                <div class="relative group shrink-0">
                    <div class="w-32 h-32 md:w-40 md:h-40 border-4 {{ !$team->logo_path ? 'border-dashed border-iba-red' : 'border-iba-black' }} shadow-[4px_4px_0_0_#131011] overflow-hidden bg-[#1A1617] flex items-center justify-center relative">
                        @if($team && $team->logo_path)
                            <img src="{{ Storage::url($team->logo_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-4xl mb-2">🚀</span>
                                <span class="text-[9px] font-black text-gray-400 uppercase">Team Logo</span>
                            </div>
                            {{-- Notification Dot --}}
                            <div class="absolute top-2 right-2 w-3 h-3 bg-iba-red rounded-full animate-ping"></div>
                            <div class="absolute top-2 right-2 w-3 h-3 bg-iba-red rounded-full"></div>
                        @endif
                        
                        {{-- Upload Overlay --}}
                        <label class="absolute inset-0 bg-black/80 hidden group-hover:flex flex-col items-center justify-center cursor-pointer transition-all">
                            <span class="text-[10px] font-black text-white uppercase tracking-widest text-center px-2">Update<br>Logo</span>
                            <span class="text-[8px] font-bold text-gray-400 mt-1 uppercase">Max 10MB</span>
                            <input type="file" wire:model.live="teamLogo" accept="image/*" class="hidden">
                        </label>
                    </div>
                    @error('teamLogo') <span class="absolute -bottom-6 left-0 right-0 text-center text-[10px] font-black text-iba-red block mt-1">{{ $message }}</span> @enderror
                    <div wire:loading wire:target="teamLogo" class="absolute -bottom-6 left-0 right-0 text-center text-[10px] font-bold text-iba-orange animate-pulse">Uploading...</div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-4">
                    <h2 class="text-3xl md:text-5xl font-black font-pixel text-iba-black dark:text-white uppercase">{{ $team->team_name ?? 'Your Team' }}</h2>
                    <p class="text-sm font-bold text-gray-600 dark:text-gray-400 max-w-2xl">
                        Welcome to the Ibalong Festival Hackathon 2026. This is your staging area. Connect with the community, review challenge files, and prepare your pitch.
                    </p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 pt-2">
                        <a href="{{ route('ibalong.resources') }}" class="inline-flex items-center gap-2 bg-iba-teal text-white font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            Access Resources
                        </a>
                        <a href="{{ route('ibalong.community-logs') }}" class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 text-iba-black dark:text-white font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">
                            Join Discussion
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Team Manifesto & Capabilities --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
                <h3 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-4 border-b-4 border-iba-black dark:border-iba-light pb-2">Cohort Manifesto</h3>
                <p class="text-sm font-bold text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $team->team_about ?? 'No manifesto provided.' }}</p>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light p-6 shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
                <h3 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-4 border-b-4 border-iba-black dark:border-iba-light pb-2">Team Capabilities</h3>
                @if($team && $team->skills->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($team->skills as $skill)
                            <span class="bg-iba-black dark:bg-gray-900 text-white dark:text-gray-300 text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 border border-iba-black dark:border-gray-700 shadow-sm">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm font-bold text-gray-500 italic">No team skills registered.</p>
                @endif
            </div>
        </div>

        {{-- Event Participation & Tracking Monitor --}}
        <div>
            <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider mb-6 border-l-4 border-iba-teal pl-3">Event Participation Monitor</h3>
            
            <div class="bg-white dark:bg-gray-900 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#0095AC] p-6">
                @if(count($teamEvents) > 0)
                    <div class="space-y-4">
                        @foreach($teamEvents as $eventReg)
                            <div class="flex flex-col md:flex-row md:items-center justify-between border-2 border-dashed border-gray-300 dark:border-gray-700 p-4 gap-4 bg-gray-50 dark:bg-gray-800">
                                <div class="flex-1">
                                    <h4 class="text-sm font-black text-iba-black dark:text-white uppercase">{{ $eventReg->event->title ?? 'Unknown Event' }}</h4>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">
                                        {{ $eventReg->event->type ?? 'General' }} 
                                        @if($eventReg->event && $eventReg->event->start_datetime)
                                            • {{ $eventReg->event->start_datetime->format('M d, Y h:i A') }}
                                        @endif
                                    </p>
                                </div>

                                <div class="flex items-center gap-6 md:shrink-0">
                                    <div class="text-center">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Ticket Code</p>
                                        <p class="text-xs font-black font-pixel text-iba-black dark:text-white bg-gray-200 dark:bg-gray-700 px-2 py-1 mt-1 border border-iba-black">{{ $eventReg->ticket_code }}</p>
                                    </div>

                                    <div class="text-center min-w-[90px]">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</p>
                                        @if($eventReg->attendances->count() > 0)
                                            <p class="text-xs font-black text-iba-green uppercase mt-1 flex items-center justify-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Verified
                                            </p>
                                        @else
                                            <p class="text-xs font-black text-iba-orange uppercase mt-1 flex items-center justify-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pending
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center border-4 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <p class="text-xs font-black uppercase tracking-widest text-gray-500">Your team has not registered for any upcoming events yet.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Team Roster --}}
        <div>
            <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider mb-6 border-l-4 border-iba-orange pl-3">Team Roster</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($team && $team->members)
                    @foreach($team->members as $member)
                        <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black flex items-start p-4 shadow-[4px_4px_0_0_#131011] relative group overflow-hidden {{ !$member->photo_path ? 'border-dashed border-iba-red' : '' }}">
                            
                            {{-- Cleaner Member Avatar Placeholder --}}
                            <div class="w-16 h-16 shrink-0 border-2 {{ !$member->photo_path ? 'border-iba-red' : 'border-iba-black' }} overflow-hidden bg-[#1A1617] flex items-center justify-center relative mt-1">
                                @if($member->photo_path)
                                    <img src="{{ Storage::url($member->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-black text-2xl text-gray-400 uppercase">{{ substr($member->full_name, 0, 1) }}</span>
                                    {{-- Notification Dot --}}
                                    <div class="absolute top-1 right-1 w-2 h-2 bg-iba-red rounded-full animate-ping"></div>
                                    <div class="absolute top-1 right-1 w-2 h-2 bg-iba-red rounded-full"></div>
                                @endif

                                <label class="absolute inset-0 bg-black/80 hidden group-hover:flex flex-col items-center justify-center cursor-pointer transition-all">
                                    <svg class="w-4 h-4 text-white mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <span class="text-[7px] font-bold text-gray-400 uppercase">Max 10MB</span>
                                    <input type="file" wire:model.live="memberPhotos.{{ $member->id }}" accept="image/*" class="hidden">
                                </label>
                            </div>

                            <div class="ml-4 flex-1">
                                <h4 class="font-black text-sm text-iba-black dark:text-white uppercase truncate" title="{{ $member->full_name }}">{{ $member->full_name }}</h4>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">{{ $member->team_role }}</p>
                                
                                @if($member->skills && $member->skills->count() > 0)
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach($member->skills as $skill)
                                            <span class="bg-iba-teal/10 text-iba-teal border border-iba-teal/30 text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 truncate max-w-full" title="{{ $skill->name }}">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @error('memberPhotos.'.$member->id) <span class="text-[9px] font-black text-iba-red block mt-1 truncate">{{ $message }}</span> @enderror
                            </div>

                            <div wire:loading wire:target="memberPhotos.{{ $member->id }}" class="absolute bottom-0 left-0 right-0 h-1 bg-iba-orange animate-pulse"></div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>