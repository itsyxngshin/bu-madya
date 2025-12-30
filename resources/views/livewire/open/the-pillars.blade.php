<div class="min-h-screen bg-stone-50 font-sans text-gray-900 pb-20 relative overflow-x-hidden">

    {{-- 1. ATMOSPHERE: SIGNATURE BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        {{-- Base Overlay --}}
        <div class="absolute top-0 left-0 w-full h-full bg-stone-50/80"></div>
        
        {{-- Animated Orbs --}}
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. HERO HEADER --}}
    <div class="relative z-10 pt-16 pb-12 px-6 text-center max-w-2xl mx-auto">
        
        @if(Auth::check() && Auth::user()->role->role_name === 'director') 
            <div class="mb-6 animate-fade-in-down">
                <a href="{{ route('director.pillars.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg hover:bg-red-600 hover:scale-105 transition-all">
                    Manage Pillars
                </a>
            </div>
        @endif

        <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md border border-white/50 px-4 py-1.5 rounded-full mb-6 shadow-sm">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Decision Center</span>
        </div>
        
        <h1 class="font-heading font-black text-4xl md:text-6xl mb-4 tracking-tighter text-gray-900 drop-shadow-sm">
            The <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Pillars</span>
        </h1>
        
        <p class="text-gray-500 text-sm md:text-base leading-relaxed font-medium">
            Your voice shapes our advocacy. Vote on the core resolutions below.
        </p>
    </div>

    {{-- 3. PILLARS FEED --}}
    <div class="relative z-10 max-w-xl mx-auto px-4 space-y-12">
        @forelse($pillars as $pillar)
        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-white/60 overflow-hidden transform transition hover:-translate-y-1 hover:shadow-2xl">
            
            {{-- Cover Image --}}
            @if($pillar->image_path)
            <div class="h-56 w-full relative">
                <img src="{{ asset('storage/'.$pillar->image_path) }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute bottom-6 left-8 right-8">
                    <h2 class="font-heading font-black text-2xl md:text-3xl text-white mb-2 leading-tight drop-shadow-md">{{ $pillar->title }}</h2>
                    <p class="text-white/90 text-sm line-clamp-2 font-medium">{{ $pillar->description }}</p>
                </div>
            </div>
            @else
            <div class="p-8 pb-0">
                <h2 class="font-heading font-black text-2xl md:text-3xl text-gray-900 mb-2">{{ $pillar->title }}</h2>
                <p class="text-gray-500 text-sm font-medium">{{ $pillar->description }}</p>
            </div>
            @endif

            {{-- Questions Loop --}}
            <div class="p-8 space-y-10">
                @foreach($pillar->mapped_questions as $q)
                <div class="relative">
                    
                    {{-- Question Text --}}
                    <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-start gap-3 leading-snug">
                        <span class="bg-red-100 text-red-600 text-[10px] font-black uppercase px-2 py-1 rounded-md mt-1 shrink-0">Q{{ $loop->iteration }}</span>
                        {{ $q['text'] }}
                    </h3>

                    {{-- Logic: Results OR Voting --}}
                    @if($q['has_voted'])
                        
                        {{-- RESULTS --}}
                        <div class="space-y-3 animate-fade-in">
                            @foreach($q['options'] as $opt)
                                <div class="relative w-full h-12 bg-gray-50/50 rounded-xl overflow-hidden border border-gray-200/60 shadow-inner">
                                    {{-- Bar --}}
                                    <div style="width: {{ $opt['percent'] }}%" 
                                         class="absolute top-0 left-0 h-full opacity-20 transition-all duration-1000 ease-out
                                         {{ match($opt['color']) { 'green'=>'bg-green-500', 'red'=>'bg-red-500', 'yellow'=>'bg-yellow-400', default=>'bg-gray-800' } }}">
                                    </div>
                                    {{-- Content --}}
                                    <div class="absolute inset-0 px-4 flex items-center justify-between text-xs font-bold text-gray-700">
                                        <div class="flex items-center gap-2">
                                            @if($q['voted_option_id'] == $opt['id'])
                                                <svg class="w-4 h-4 text-green-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                            <span>{{ $opt['label'] }}</span>
                                        </div>
                                        <span>{{ $opt['percent'] }}%</span>
                                    </div>
                                </div>
                            @endforeach
                            <p class="text-[10px] text-center text-gray-400 font-bold uppercase tracking-widest mt-2">{{ number_format($q['total_votes']) }} Votes</p>
                        </div>

                    @else
                        
                        {{-- VOTING BUTTONS --}}
                        <div class="grid grid-cols-1 sm:grid-cols-{{ count($q['options']) > 2 ? '2' : count($q['options']) }} gap-3">
                            @foreach($q['options'] as $opt)
                                <button wire:click="vote({{ $q['id'] }}, {{ $opt['id'] }})" 
                                        class="group px-4 py-3 bg-white border-2 border-gray-100 rounded-xl font-bold text-gray-600 text-sm hover:border-gray-300 hover:bg-gray-50 hover:shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                                    {{ $opt['label'] }}
                                </button>
                            @endforeach
                        </div>

                    @endif

                </div>
                @if(!$loop->last) <hr class="border-gray-200 border-dashed"> @endif
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-20 opacity-60">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-gray-500 font-bold">No active pillars at this time.</p>
        </div>
        @endforelse
    </div>
</div>