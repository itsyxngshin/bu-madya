@php
    // Extract the assigned users from the relations
    $heads = $committee->directorAssignments->map(fn($da) => $da->user)->filter();
    $headCount = $heads->count();
    
    // Dynamic theme colors passed from the parent view
    $borderHover = $theme === 'red' ? 'hover:border-red-500' : 'hover:border-blue-500';
    $borderMain = $theme === 'red' ? 'border-yellow-500' : 'border-green-500';
    $iconBg = $theme === 'red' ? 'bg-yellow-50 text-yellow-600 group-hover:bg-red-50 group-hover:text-red-500' : 'bg-green-50 text-green-600 group-hover:bg-blue-50 group-hover:text-blue-500';
    $imgBorder = $theme === 'red' ? 'border-red-500' : 'border-green-500';
    $textMuted = $theme === 'red' ? 'text-yellow-600 group-hover:text-red-400' : 'text-green-600 group-hover:text-blue-400';
@endphp

<div x-data="{ flipped: false }" 
     @click="flipped = !flipped"
     class="bg-white rounded-xl p-5 shadow-sm border-l-4 {{ $borderMain }} {{ $borderHover }} hover:shadow-md transition-all duration-300 cursor-pointer h-full relative group min-h-[220px] flex flex-col justify-center">
    
    {{-- Front Side (Details) --}}
    <div x-show="!flipped" class="flex flex-col h-full justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors shrink-0 {{ $iconBg }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $committee->svg_path }}"></path></svg>
                </div>
                <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $committee->name }}</h4>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed text-justify line-clamp-4">
                {{ $committee->description }}
            </p>
        </div>
        <div class="text-[10px] text-right mt-3 font-bold uppercase transition-colors {{ $textMuted }}">View Head</div>
    </div>

    {{-- Back Side (Dynamic Hierarchy) --}}
    <div x-show="flipped" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="flex flex-col items-center justify-center h-full text-center w-full">
        
        @if($headCount === 0)
            {{-- 1. VACANT --}}
            <div class="w-16 h-16 bg-gray-100 rounded-full mb-2 flex items-center justify-center text-gray-300 border border-dashed border-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <p class="font-bold text-gray-400 text-xs uppercase tracking-widest">Position Vacant</p>

        @elseif($headCount > 1)
            {{-- 2. CO-HEADS (Grid Layout) --}}
            <div class="grid grid-cols-2 gap-2 w-full px-1 mb-2">
                @foreach($heads as $user)
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 bg-gray-100 rounded-full mb-1 overflow-hidden border {{ $imgBorder }} shadow-sm">
                        <img src="{{ $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff' }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <p class="font-bold text-gray-800 text-[9px] leading-tight line-clamp-2 px-1">
                        {{ $user->name }}
                    </p>
                </div>
                @endforeach
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider mb-2 {{ $textMuted }}">Co-Heads</span>

        @else
            {{-- 3. SINGLE HEAD --}}
            @php $user = $heads->first(); @endphp
            <div class="w-16 h-16 bg-gray-100 rounded-full mb-2 overflow-hidden border-2 {{ $imgBorder }} shadow-md">
                <img src="{{ $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff' }}" 
                     class="w-full h-full object-cover">
            </div>
            <p class="font-bold text-gray-800 text-sm leading-tight px-2 mb-1">{{ $user->name }}</p>
            <span class="text-[10px] font-bold uppercase tracking-wider mb-2 {{ $textMuted }}">Committee Head</span>
        @endif

        <a href="{{ route('open.committees.show', $committee->slug) }}" 
           class="px-4 py-1.5 bg-gray-900 text-white rounded-full text-[10px] font-bold hover:bg-gray-800 transition shadow-sm mt-1" @click.stop>
           View Roster
        </a>
    </div>
</div>