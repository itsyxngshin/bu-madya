<div class="min-h-screen bg-stone-50 font-sans text-gray-900 pb-20 relative overflow-x-hidden">

    {{-- 1. BACKGROUND ATMOSPHERE --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-gray-200/50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[400px] h-[400px] bg-red-100/40 rounded-full blur-3xl"></div>
    </div>

    {{-- 2. HERO HEADER --}}
    <div class="relative z-10 pt-16 pb-12 px-6 text-center max-w-2xl mx-auto">
        
        {{-- ADMIN SHORTCUT BUTTON --}}
        @if(Auth::check() && Auth::user()->role->role_name === 'director') {{-- Adjust condition based on your role logic --}}
            <div class="mb-6 animate-fade-in-down">
                <a href="{{ route('director.pillars.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg hover:bg-red-600 hover:scale-105 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Manage Pillars
                </a>
            </div>
        @endif

        <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md border border-white/50 px-4 py-1.5 rounded-full mb-6 shadow-sm">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Decision Center</span>
        </div>
        
        <h1 class="font-heading font-black text-4xl md:text-6xl mb-4 tracking-tighter text-gray-900">
            The <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Pillars</span>
        </h1>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed">
            Your voice shapes our advocacy. Vote on the core resolutions that define our movement.
        </p>
    </div>

    {{-- 3. POLLS FEED --}}
    <div class="relative z-10 max-w-xl mx-auto px-4 space-y-8">
        
        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold text-center mb-6 shadow-sm animate-fade-in-up">
                {{ session('message') }}
            </div>
        @endif

        @forelse($polls as $poll)
        <div class="group bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-white overflow-hidden transition-all hover:shadow-2xl hover:-translate-y-1">
            
            {{-- A. Cover Image (Optional) --}}
            @if($poll['image'])
            <div class="h-48 w-full relative overflow-hidden">
                <img src="{{ asset('storage/'.$poll['image']) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-6">
                    <span class="px-2 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase rounded border border-white/30">
                        {{ $poll['title'] ?? 'Official Resolution' }}
                    </span>
                </div>
            </div>
            @endif

            <div class="p-6 md:p-8">
                
                {{-- B. Question & Context --}}
                <div class="mb-8">
                    @if(!$poll['image'])
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-2">{{ $poll['title'] ?? 'Resolution' }}</p>
                    @endif
                    <h2 class="font-heading font-black text-2xl leading-tight text-gray-900 mb-3">
                        {{ $poll['question'] }}
                    </h2>
                    @if($poll['description'])
                        <p class="text-sm text-gray-500 leading-relaxed font-medium border-l-2 border-gray-100 pl-4">
                            {{ $poll['description'] }}
                        </p>
                    @endif
                </div>

                {{-- C. Interaction Area --}}
                <div class="space-y-3">
                    @foreach($poll['options'] as $option)
                        
                        {{-- STATE: HAS VOTED (Show Results Bar) --}}
                        @if($poll['has_voted'])
                            <div class="relative w-full rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 h-14 shadow-inner">
                                {{-- Background Progress Bar --}}
                                <div style="width: {{ $option['percent'] }}%" 
                                     class="absolute top-0 left-0 h-full transition-all duration-1000 ease-out opacity-20
                                     {{ match($option['color']) {
                                         'green' => 'bg-green-500',
                                         'red' => 'bg-red-500',
                                         'yellow' => 'bg-yellow-400',
                                         default => 'bg-gray-900'
                                     } }}">
                                </div>

                                {{-- Content Layer --}}
                                <div class="absolute inset-0 px-5 flex items-center justify-between z-10">
                                    <div class="flex items-center gap-3">
                                        {{-- Checkmark Icon if selected --}}
                                        @if($poll['voted_option_id'] == $option['id'])
                                            <div class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @endif
                                        
                                        <span class="text-sm font-bold {{ $poll['voted_option_id'] == $option['id'] ? 'text-gray-900' : 'text-gray-500' }}">
                                            {{ $option['label'] }}
                                        </span>
                                    </div>
                                    <span class="font-mono font-black text-sm text-gray-900">{{ $option['percent'] }}%</span>
                                </div>
                            </div>

                        {{-- STATE: NOT VOTED (Show Voting Buttons) --}}
                        @else
                            <button wire:click="vote({{ $poll['id'] }}, {{ $option['id'] }})"
                                    class="w-full relative group/btn overflow-hidden rounded-2xl border-2 border-gray-100 bg-white p-4 transition-all active:scale-[0.98] hover:border-gray-200 hover:shadow-lg text-left">
                                <div class="flex items-center justify-between relative z-10">
                                    <span class="font-bold text-gray-700 group-hover/btn:text-gray-900 transition-colors">
                                        {{ $option['label'] }}
                                    </span>
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-200 group-hover/btn:border-red-500 group-hover/btn:bg-red-500 transition-all"></div>
                                </div>
                            </button>
                        @endif

                    @endforeach
                </div>

                {{-- Footer Stats --}}
                <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span>{{ number_format($poll['total_votes']) }} Votes Cast</span>
                    @if($poll['has_voted'])
                        <span class="text-green-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Voted
                        </span>
                    @else
                        <span class="text-red-500 animate-pulse">Open</span>
                    @endif
                </div>

            </div>
        </div>
        @empty
        <div class="text-center py-20 opacity-60">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-gray-500 font-bold">No active pillars at this time.</p>
        </div>
        @endforelse

    </div>
</div>