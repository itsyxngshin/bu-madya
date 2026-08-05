<div class="max-w-5xl mx-auto space-y-8 pb-24">

    {{-- Security Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#D93B3B] p-6 text-white relative">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-black uppercase tracking-widest text-white">Clearance Terminal</h1>
            <span class="bg-iba-red text-white text-[10px] font-black uppercase px-3 py-1 animate-pulse">SECURITY OVERRIDE</span>
        </div>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Target Protocol: {{ $quest->title }}</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Master Lock Toggle --}}
    <div class="bg-white border-4 border-iba-black p-8 shadow-[6px_6px_0_0_#131011] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black uppercase tracking-widest text-iba-black">Encrypted Protocol</h2>
            <p class="text-xs font-bold text-gray-500 uppercase mt-1">If engaged, only cohorts with explicit clearance can view or transmit data to this quest.</p>
        </div>
        
        <button wire:click="toggleRestriction" class="px-8 py-4 text-sm font-black uppercase tracking-widest border-4 border-iba-black shadow-[4px_4px_0_0_#131011] transition-all hover:translate-y-1 hover:shadow-none {{ $quest->is_restricted ? 'bg-iba-red text-white' : 'bg-gray-100 text-iba-black' }}">
            {{ $quest->is_restricted ? 'RESTRICTION ENGAGED (LOCKED)' : 'GLOBAL ACCESS (UNLOCKED)' }}
        </button>
    </div>

    {{-- Clearance Roster (Only visible if restricted) --}}
    @if($quest->is_restricted)
        <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] animate-fade-in-up">
            <div class="bg-gray-100 px-6 py-4 border-b-4 border-iba-black flex justify-between items-center">
                <h3 class="text-lg font-black uppercase tracking-widest text-iba-black">Cohort Clearance Roster</h3>
                <span class="text-xs font-black bg-iba-black text-white px-2 py-1">{{ count($clearedTeamIds) }} Cleared</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-0 divide-y-2 md:divide-y-0 md:divide-x-2 divide-gray-200 border-b-2 border-gray-200">
                @foreach($teams as $team)
                    @php $isCleared = in_array($team->id, $clearedTeamIds); @endphp
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors {{ $isCleared ? 'bg-orange-50' : '' }}">
                        <div>
                            <div class="text-sm font-black text-iba-black uppercase">{{ $team->team_name }}</div>
                            <div class="text-[9px] font-bold text-gray-500 uppercase">{{ $team->category ?? 'General' }}</div>
                        </div>
                        
                        <label class="cursor-pointer relative inline-block w-12 h-6">
                            <input type="checkbox" wire:click="toggleClearance({{ $team->id }})" class="sr-only peer" {{ $isCleared ? 'checked' : '' }}>
                            <div class="w-12 h-6 bg-gray-200 border-2 border-iba-black peer-checked:bg-iba-orange transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white border-2 border-iba-black transition-transform peer-checked:translate-x-6"></div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="flex justify-end pt-4">
        <a href="{{ route('ibalong.admin.quests.index') }}" class="bg-gray-100 text-iba-black text-sm font-black uppercase tracking-widest px-8 py-4 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">
            &larr; Return to Quests
        </a>
    </div>
</div>