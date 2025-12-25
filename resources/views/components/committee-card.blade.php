@props(['comm'])

<div x-data="{ flipped: false }" 
     @click="flipped = !flipped"
     class="bg-white/60 backdrop-blur-sm border border-white/60 p-6 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition duration-500 cursor-pointer group relative min-h-[240px] flex flex-col justify-center">
    
    {{-- ======================== --}}
    {{-- FRONT SIDE (Description) --}}
    {{-- ======================== --}}
    <div x-show="!flipped" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100">
        
        <div class="flex items-center gap-4 mb-4">
            {{-- Icon Container --}}
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600 shadow-sm shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $comm['path'] }}"></path>
                </svg>
            </div>
            {{-- Committee Name --}}
            <h3 class="font-heading font-bold text-lg text-gray-800 leading-tight group-hover:text-red-700 transition-colors">
                {{ $comm['name'] }}
            </h3>
        </div>

        {{-- Description --}}
        <p class="text-sm text-gray-600 leading-relaxed">
            {{ $comm['desc'] }}
        </p>

        {{-- Flip Hint --}}
        <div class="mt-4 flex justify-end">
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1 group-hover:text-red-500 transition-colors">
                View Head 
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </span>
        </div>
    </div>

    {{-- ======================== --}}
    {{-- BACK SIDE (Heads Info)   --}}
    {{-- ======================== --}}
    <div x-show="flipped" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         class="flex flex-col items-center justify-center h-full text-center w-full">
        
        @if(empty($comm['heads']))
            {{-- CASE 1: VACANT --}}
            <div class="w-16 h-16 bg-gray-100 rounded-full mb-3 flex items-center justify-center text-gray-300 border border-dashed border-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <p class="font-bold text-gray-400 text-sm uppercase tracking-widest">Position Vacant</p>

        @elseif(count($comm['heads']) > 1)
            {{-- CASE 2: MULTIPLE HEADS (Co-Heads) --}}
            <div class="grid grid-cols-2 gap-2 w-full px-1">
                @foreach($comm['heads'] as $head)
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 bg-gray-100 rounded-full mb-1 overflow-hidden border border-green-500 shadow-sm">
                        <img src="{{ $head['img'] ?? 'https://ui-avatars.com/api/?name='.urlencode($head['name']).'&background=random&color=fff' }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <p class="font-bold text-gray-800 text-[10px] leading-tight line-clamp-2 px-1">
                        {{ $head['name'] }}
                    </p>
                </div>
                @endforeach
            </div>
            <span class="text-[10px] text-green-600 font-bold uppercase tracking-wider mt-3 mb-2">Co-Heads</span>

        @else
            {{-- CASE 3: SINGLE HEAD --}}
            @php $head = $comm['heads'][0]; @endphp
            <div class="w-20 h-20 bg-gray-100 rounded-full mb-2 overflow-hidden border-2 border-green-500 shadow-md">
                <img src="{{ $head['img'] ?? 'https://ui-avatars.com/api/?name='.urlencode($head['name']).'&background=random&color=fff' }}" 
                     class="w-full h-full object-cover">
            </div>
            <p class="font-bold text-gray-800 text-sm leading-tight px-2">{{ $head['name'] }}</p>
            <span class="text-[10px] text-green-600 font-bold uppercase tracking-wider mb-2 mt-1">Committee Head</span>
        @endif

        {{-- View Members Link --}}
        <a href="{{ route('open.committees.show', Str::slug($comm['name'])) }}" 
        class="mt-1 px-3 py-1 bg-green-600 text-white rounded-full text-[10px] font-bold hover:bg-green-700 transition shadow-sm">
            View Members
        </a>
        
        {{-- Back Button (Optional UX) --}}
        <button class="mt-2 text-[10px] text-gray-400 hover:text-gray-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
            Flip Back
        </button>
    </div>
</div>