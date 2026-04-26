<div class="max-w-5xl mx-auto py-8 px-4" x-data="{ tab: @entangle('activeTab') }">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Create New Election</h1>
            <p class="text-gray-500 font-medium">Configure settings, timeline, and custom positions.</p>
        </div>
        <button wire:click="saveElection" class="px-6 py-3 bg-gray-900 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg transition-colors">
            Publish Election
        </button>
    </div>

    {{-- Tabs --}}
    <div class="flex space-x-2 bg-gray-200/50 p-1 rounded-2xl w-max mb-6">
        <button @click="tab = 'details'" :class="tab === 'details' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all">1. Details</button>
        <button @click="tab = 'timeline'" :class="tab === 'timeline' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all">2. Timeline</button>
        <button @click="tab = 'positions'" :class="tab === 'positions' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all">3. Positions</button>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 p-8">
        
        {{-- STEP 1: DETAILS --}}
        <div x-show="tab === 'details'" x-cloak class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Election Title</label>
                <input type="text" wire:model="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3" placeholder="e.g., 2026 General Elections">
                @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Description</label>
                <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Election Type</label>
                    <select wire:model="type" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3 font-semibold text-gray-700">
                        <option value="general">General Election</option>
                        <option value="special">Special Election</option>
                        <option value="runoff">Run-off</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Guest Voting</label>
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl bg-gray-50 cursor-pointer">
                        <input type="checkbox" wire:model="allow_guest_voting" class="w-5 h-5 text-orange-500 rounded border-gray-300">
                        <span class="text-sm font-bold text-gray-700">Allow non-logged-in voters</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- STEP 2: TIMELINE --}}
        <div x-show="tab === 'timeline'" x-cloak class="space-y-6">
            <div class="grid grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-2xl border border-blue-100">
                <div class="col-span-2 text-blue-800 font-black text-sm uppercase">Candidacy Filing Phase</div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Starts</label>
                    <input type="datetime-local" wire:model="application_start" class="w-full rounded-xl border-gray-200 p-3">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Ends</label>
                    <input type="datetime-local" wire:model="application_end" class="w-full rounded-xl border-gray-200 p-3">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 p-6 bg-orange-50/50 rounded-2xl border border-orange-100">
                <div class="col-span-2 text-orange-800 font-black text-sm uppercase">Live Voting Phase</div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Starts</label>
                    <input type="datetime-local" wire:model="voting_start" class="w-full rounded-xl border-gray-200 p-3">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Ends</label>
                    <input type="datetime-local" wire:model="voting_end" class="w-full rounded-xl border-gray-200 p-3">
                </div>
            </div>
        </div>

        {{-- STEP 3: POSITIONS --}}
        <div x-show="tab === 'positions'" x-cloak>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Ballot Configuration</h3>
                <button wire:click="addPosition" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg">+ Add Position</button>
            </div>

            <div class="space-y-3">
                @foreach($positions as $index => $pos)
                    {{-- Notice we use temp_id to prevent the morphdom bug! --}}
                    <div wire:key="pos-{{ $pos['temp_id'] }}" class="flex items-start gap-3 p-4 bg-gray-50 border border-gray-200 rounded-xl relative group">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Position Title</label>
                            <input type="text" wire:model="positions.{{ $index }}.title" class="w-full rounded-lg border-gray-200 text-sm font-bold p-2" placeholder="e.g. Vice President">
                            @error('positions.'.$index.'.title') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-32">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Max Winners</label>
                            <input type="number" wire:model="positions.{{ $index }}.max_winners" min="1" class="w-full rounded-lg border-gray-200 text-sm font-bold p-2 text-center">
                        </div>
                        @if(count($positions) > 1)
                            <button wire:click="removePosition({{ $index }})" class="mt-5 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
