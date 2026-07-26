<div class="max-w-7xl mx-auto space-y-6 pb-24">

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-iba-red/10 border-l-4 border-iba-red p-4 flex items-center justify-between">
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
        {{-- Welcome & Overview --}}
        <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] overflow-hidden">
            <div class="bg-iba-orange px-6 py-3 border-b-4 border-iba-black">
                <h1 class="text-sm font-black text-iba-black uppercase tracking-widest">Cohort Command Center</h1>
            </div>
            
            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                
                {{-- Team Logo Upload Section --}}
                <div class="relative group shrink-0">
                    <div class="w-32 h-32 md:w-40 md:h-40 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] overflow-hidden bg-gray-100 flex items-center justify-center relative">
                        @if($team && $team->logo_path)
                            <img src="{{ Storage::url($team->logo_path) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">🚀</span>
                        @endif
                        
                        {{-- Upload Overlay --}}
                        <label class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center cursor-pointer transition-all">
                            <span class="text-[10px] font-black text-white uppercase tracking-widest text-center px-2">Update<br>Logo</span>
                            <input type="file" wire:model.live="teamLogo" accept="image/*" class="hidden">
                        </label>
                    </div>
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

        {{-- Team Roster --}}
        <div>
            <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider mb-6 border-l-4 border-iba-orange pl-3">Team Roster</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($team && $team->members)
                    @foreach($team->members as $member)
                        <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black flex items-center p-4 shadow-[4px_4px_0_0_#131011] relative group overflow-hidden">
                            
                            {{-- Member Avatar & Upload --}}
                            <div class="w-16 h-16 shrink-0 border-2 border-iba-black overflow-hidden bg-gray-100 flex items-center justify-center relative">
                                @if($member->photo_path)
                                    <img src="{{ Storage::url($member->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-black text-lg text-gray-400">{{ substr($member->full_name, 0, 1) }}</span>
                                @endif

                                <label class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center cursor-pointer transition-all">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <input type="file" wire:model.live="memberPhotos.{{ $member->id }}" accept="image/*" class="hidden">
                                </label>
                            </div>

                            <div class="ml-4 truncate">
                                <h4 class="font-black text-sm text-iba-black dark:text-white uppercase truncate">{{ $member->full_name }}</h4>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">{{ $member->team_role }}</p>
                            </div>

                            <div wire:loading wire:target="memberPhotos.{{ $member->id }}" class="absolute bottom-0 left-0 right-0 h-1 bg-iba-orange animate-pulse"></div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>