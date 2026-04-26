<div class="max-w-5xl mx-auto py-8 px-4 font-sans pb-32" x-data="{ tab: @entangle('activeTab') }">
    
    <div class="mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.manage') }}" class="text-gray-400 hover:text-orange-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Edit Election
            </h1>
            <p class="text-gray-500 font-medium ml-9">Modifying: <span class="font-bold">{{ $election->title }}</span></p>
        </div>
        <button wire:click="saveElection" class="px-8 py-3 bg-gray-900 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg transition-colors w-full md:w-auto">
            Save Changes
        </button>
    </div>

    {{-- Tabs --}}
    <div class="flex space-x-2 bg-gray-200/50 p-1 rounded-2xl w-full md:w-max mb-6 overflow-x-auto">
        <button @click="tab = 'details'" :class="tab === 'details' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all whitespace-nowrap">1. Details</button>
        <button @click="tab = 'timeline'" :class="tab === 'timeline' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all whitespace-nowrap">2. Timeline</button>
        <button @click="tab = 'positions'" :class="tab === 'positions' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all whitespace-nowrap">3. Positions</button>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 p-6 md:p-8">
        
        {{-- STEP 1: DETAILS --}}
        <div x-show="tab === 'details'" x-cloak class="space-y-6">
            
            {{-- Photo Upload Preview --}}
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cover Photo</label>
                @if($cover_photo)
                    <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-40 object-cover rounded-2xl mb-3 shadow-sm border border-gray-200">
                @elseif($existing_cover_photo)
                    <img src="{{ asset('storage/'.$existing_cover_photo) }}" class="w-full h-40 object-cover rounded-2xl mb-3 shadow-sm border border-gray-200">
                @else
                    <div class="w-full h-24 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-3">No Cover Photo</div>
                @endif
                <label class="cursor-pointer bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-sm border border-gray-200 inline-block">
                    Change Cover Photo
                    <input type="file" wire:model="cover_photo" class="hidden" accept="image/*">
                </label>
                <div wire:loading wire:target="cover_photo" class="text-[10px] text-orange-500 font-bold ml-3 animate-pulse">Uploading...</div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Election Title</label>
                <input type="text" wire:model="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3 shadow-sm font-bold text-gray-900">
                @error('title') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3 shadow-sm resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Election Type</label>
                    <select wire:model="type" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3 font-semibold text-gray-700 shadow-sm cursor-pointer">
                        <option value="general">General Election</option>
                        <option value="special">Special Election</option>
                        <option value="runoff">Run-off</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guest Access</label>
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors shadow-sm">
                        <input type="checkbox" wire:model="allow_guest_voting" class="w-5 h-5 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                        <span class="text-sm font-bold text-gray-700">Allow Guest Voting (Non-authenticated)</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- STEP 2: TIMELINE --}}
        <div x-show="tab === 'timeline'" x-cloak class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-2xl border border-blue-100">
                <div class="md:col-span-2 text-blue-800 font-black text-sm uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Application Phase
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Candidacy Opens</label>
                    <input type="datetime-local" wire:model="application_start" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Candidacy Closes</label>
                    <input type="datetime-local" wire:model="application_end" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-orange-50/50 rounded-2xl border border-orange-100">
                <div class="md:col-span-2 text-orange-800 font-black text-sm uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Live Voting Phase
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Ballots Open</label>
                    <input type="datetime-local" wire:model="voting_start" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Ballots Close</label>
                    <input type="datetime-local" wire:model="voting_end" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-green-50/50 rounded-2xl border border-green-100">
                <div class="md:col-span-2 text-green-800 font-black text-sm uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    Results Phase
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Automated Results Release</label>
                    <input type="datetime-local" wire:model="results_release" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold">
                    <p class="text-[10px] text-gray-500 font-bold mt-1">Leave blank to keep results hidden manually.</p>
                </div>
            </div>
        </div>

        {{-- STEP 3: POSITIONS --}}
        <div x-show="tab === 'positions'" x-cloak>
            
            @if (session()->has('position_error'))
                <div class="mb-4 bg-red-50 text-red-700 p-3 rounded-xl border border-red-200 font-bold text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    {{ session('position_error') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Ballot Configuration</h3>
                <button wire:click="addPosition" class="text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition-colors shadow-sm flex items-center gap-1">
                    <span>+</span> Add Position
                </button>
            </div>

            <div class="space-y-4">
                @foreach($positions as $index => $pos)
                    @php 
                        $hasCandidates = $pos['candidate_count'] > 0;
                        $rowKey = $pos['id'] ? 'db-'.$pos['id'] : 'temp-'.$pos['temp_id'];
                    @endphp

                    <div wire:key="pos-{{ $rowKey }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 bg-gray-50 border border-gray-200 rounded-2xl relative shadow-sm">
                        
                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Position Title</label>
                            <input type="text" wire:model="positions.{{ $index }}.title" class="w-full rounded-xl border-gray-200 text-sm font-bold p-3 shadow-sm focus:ring-orange-500" placeholder="e.g. Vice President">
                            @error('positions.'.$index.'.title') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            
                            @if($hasCandidates)
                                <p class="text-[10px] text-orange-600 font-black uppercase tracking-wider mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                    {{ $pos['candidate_count'] }} Active Candidate(s)
                                </p>
                            @endif
                        </div>

                        <div class="w-full sm:w-32 shrink-0">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Max Winners</label>
                            <input type="number" wire:model="positions.{{ $index }}.max_winners" min="1" class="w-full rounded-xl border-gray-200 text-sm font-bold p-3 text-center shadow-sm focus:ring-orange-500">
                        </div>

                        <div class="absolute top-4 right-4 sm:relative sm:top-0 sm:right-0 sm:mt-5">
                            @if($hasCandidates)
                                <button disabled class="text-gray-300 cursor-not-allowed p-2" title="Cannot delete: Candidates exist">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </button>
                            @else
                                <button wire:click="removePosition({{ $index }})" class="text-gray-400 hover:text-red-500 transition-colors p-2 bg-white rounded-lg border border-gray-200 hover:border-red-200 shadow-sm" title="Remove Position">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>