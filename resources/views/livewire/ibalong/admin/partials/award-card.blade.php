<div class="bg-white border-4 border-iba-black shadow-[4px_4px_0_0_#131011] flex flex-col relative group">

    {{-- Delete Button (Hover) --}}
    <button wire:click="deleteAward({{ $award->id }})" wire:confirm="Purge this award?" class="absolute -top-3 -right-3 bg-iba-red text-white p-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] opacity-0 group-hover:opacity-100 transition-opacity hover:scale-110 z-10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
    </button>

    {{-- Award Title Area --}}
    <div class="p-4 border-b-2 border-iba-black bg-gray-50 flex justify-between items-start gap-2">
        <h4 class="font-black text-sm uppercase text-iba-black leading-tight">{{ $award->title }}</h4>
        <button wire:click="togglePublish({{ $award->id }})" class="shrink-0 text-[8px] font-black uppercase px-2 py-1 border-2 border-iba-black {{ $award->is_published ? 'bg-iba-green text-white shadow-[2px_2px_0_0_#131011]' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}">
            {{ $award->is_published ? 'Live' : 'Hidden' }}
        </button>
    </div>

    {{-- Winner Area --}}
    <div class="p-4 flex-1 flex flex-col justify-center min-h-[100px] {{ $award->team ? "bg-{$color}" : 'bg-white' }} transition-colors relative">
        @if($award->team)
            <div class="text-center">
                <p class="text-[9px] font-black uppercase {{ $award->team ? 'text-black/60' : 'text-gray-400' }} tracking-widest mb-1">Designated Winner</p>
                <p class="text-lg font-black uppercase text-iba-black break-words leading-none">{{ $award->team->team_name }}</p>
            </div>

            <button wire:click="removeWinner({{ $award->id }})" class="absolute bottom-2 right-2 text-iba-black hover:text-white opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </button>
        @else
            <div class="text-center">
                <p class="text-xs font-bold uppercase text-gray-400 tracking-widest">No Winner Designated</p>
            </div>
        @endif
    </div>

    {{-- Assign Button & Remarks --}}
    <div class="p-3 border-t-2 border-iba-black bg-white flex flex-col gap-2">
        @if($award->remarks)
            <p class="text-[10px] font-bold text-gray-500 uppercase truncate" title="{{ $award->remarks }}">INFO: {{ $award->remarks }}</p>
        @endif
        <button wire:click="openAssignModal({{ $award->id }})" class="w-full border-2 border-dashed border-gray-300 hover:border-iba-black text-gray-500 hover:text-iba-black text-[10px] font-black uppercase py-1.5 transition-colors">
            {{ $award->team ? 'Reassign Cohort' : '+ Assign Winner' }}
        </button>
    </div>
</div>
