<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                {{ $electionRecord ? 'Edit Election' : 'Create New Election' }}
            </h1>
            <p class="text-gray-500 font-medium ml-9 text-sm md:text-base">Configure ballot settings, timeline, positions, and parties.</p>
        </div>

        @if($electionRecord)
            <button wire:click="confirmWipe" class="shrink-0 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl border border-red-200 transition-colors flex items-center gap-2 outline-none focus:ring-2 focus:ring-red-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Factory Reset
            </button>
        @endif
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-bold flex items-center gap-2 text-sm animate-fade-in">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- TAB NAVIGATION --}}
    <div class="flex items-center gap-4 md:gap-8 border-b border-gray-200 mb-8 overflow-x-auto no-scrollbar pb-2">
        <button wire:click="$set('activeTab', 'details')" class="px-2 py-2 font-black text-sm whitespace-nowrap transition-colors outline-none focus:ring-2 focus:ring-blue-500/30 rounded-lg {{ $activeTab === 'details' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">General Details</button>
        <button wire:click="$set('activeTab', 'timeline')" class="px-2 py-2 font-black text-sm whitespace-nowrap transition-colors outline-none focus:ring-2 focus:ring-blue-500/30 rounded-lg {{ $activeTab === 'timeline' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">Timeline</button>
        <button wire:click="$set('activeTab', 'positions')" class="px-2 py-2 font-black text-sm whitespace-nowrap transition-colors outline-none focus:ring-2 focus:ring-blue-500/30 rounded-lg {{ $activeTab === 'positions' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">Positions</button>
        <button wire:click="$set('activeTab', 'parties')" class="px-2 py-2 font-black text-sm whitespace-nowrap transition-colors outline-none focus:ring-2 focus:ring-blue-500/30 rounded-lg {{ $activeTab === 'parties' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">Parties & Slates</button>
    </div>

    {{-- TAB 1: GENERAL DETAILS --}}
    @if($activeTab === 'details')
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 animate-fade-in">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-6 border-b border-gray-100 pb-4">Basic Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Cover Photo --}}
                <div class="md:col-span-2 mb-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Election Banner / Cover Photo</label>
                    <div class="w-full h-48 md:h-64 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-300 flex flex-col items-center justify-center overflow-hidden relative group hover:border-blue-400 transition-colors cursor-pointer">
                        @if ($cover_photo)
                            <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif ($electionRecord && $electionRecord->cover_photo_path)
                            <img src="{{ asset('storage/'.$electionRecord->cover_photo_path) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-gray-300 mb-2 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold text-gray-400 group-hover:text-blue-500 transition-colors">Click to upload banner (16:9 recommended)</span>
                        @endif
                        <input type="file" wire:model="cover_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 focus:outline-none focus:ring-4 focus:ring-blue-500/30 rounded-2xl">
                        <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center transition-all">
                            <span class="text-white font-bold text-sm tracking-wider uppercase">Change Image</span>
                        </div>
                    </div>
                    <div wire:loading wire:target="cover_photo" class="text-[10px] text-blue-500 font-bold mt-2 uppercase tracking-widest animate-pulse">Uploading preview...</div>
                    @error('cover_photo') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Election Title</label>
                    <input wire:model="title" type="text" placeholder="e.g. 2026 Supreme Student Council Elections" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    @error('title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                    <textarea wire:model="description" rows="3" placeholder="Provide context or instructions for the voters..." class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm resize-none shadow-sm"></textarea>
                    @error('description') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Election Type</label>
                    <select wire:model="type" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                        <option value="general">General Election</option>
                        <option value="special">Special Election</option>
                        <option value="runoff">Runoff Election</option>
                    </select>
                    @error('type') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- THE FIX: Guest Voting Toggle Switch --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Voting Access</label>
                    <div class="flex items-center h-11 bg-gray-50 border border-gray-200 rounded-xl px-4 shadow-sm hover:border-blue-300 transition-colors cursor-pointer group">
                        <label class="relative inline-flex items-center cursor-pointer w-full">
                            <input type="checkbox" wire:model="allow_guest_voting" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">Allow Guest / Unauthenticated Voting</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- TAB 2: TIMELINE --}}
    @if($activeTab === 'timeline')
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 animate-fade-in">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-6 border-b border-gray-100 pb-4">Election Timeline</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Candidacy Filing Start</label>
                    <input wire:model="application_start" type="datetime-local" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    @error('application_start') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Candidacy Filing Deadline</label>
                    <input wire:model="application_end" type="datetime-local" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    @error('application_end') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Voting Period Start</label>
                    <input wire:model="voting_start" type="datetime-local" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    @error('voting_start') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Voting Period End (Closure)</label>
                    <input wire:model="voting_end" type="datetime-local" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    @error('voting_end') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 mt-4 p-6 bg-orange-50 border border-orange-200 rounded-[1.5rem] shadow-sm">
                    <label class="block text-[10px] font-bold text-orange-700 uppercase tracking-wider mb-1.5">Public Results Release Date</label>
                    <p class="text-xs text-orange-600 font-medium mb-3">When should the official results be visible to the public? Set a future date to build anticipation.</p>
                    <input wire:model="results_release" type="datetime-local" class="w-full md:w-1/2 bg-white border border-orange-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/30 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm text-orange-900">
                    @error('results_release') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    @endif

    {{-- TAB 3: POSITIONS --}}
    @if($activeTab === 'positions')
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 border-b border-gray-100 pb-4 gap-4">
                <div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Electoral Positions</h3>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Define the seats available for this election.</p>
                </div>
                <button type="button" wire:click="addPosition" class="shrink-0 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-5 py-2.5 rounded-xl transition-colors border border-blue-200 shadow-sm outline-none focus:ring-2 focus:ring-blue-500/30">
                    + Add Position
                </button>
            </div>

            <div class="space-y-4">
                @foreach($positions as $index => $pos)
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-200 relative group transition-colors hover:border-blue-300">

                        <div class="flex-1 w-full min-w-0">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Position Title</label>
                            <input wire:model="positions.{{ $index }}.title" type="text" placeholder="e.g. President, Senator" class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                            @error('positions.'.$index.'.title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="w-full md:w-32 shrink-0">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Max Winners</label>
                            <input wire:model="positions.{{ $index }}.max_winners" type="number" min="1" class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm text-center">
                            @error('positions.'.$index.'.max_winners') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="button" wire:click="removePosition({{ $index }})" class="absolute top-4 right-4 md:relative md:top-auto md:right-auto md:mt-5 text-gray-400 hover:text-red-500 bg-white p-2.5 rounded-xl border border-gray-200 shadow-sm transition-colors shrink-0 group-hover:border-red-200 outline-none focus:ring-2 focus:ring-red-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- TAB 4: PARTIES & SLATES --}}
    @if($activeTab === 'parties')
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 border-b border-gray-100 pb-4 gap-4">
                <div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Political Parties & Slates</h3>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Define participating groups, colors, and logos.</p>
                </div>
                <button type="button" wire:click="addParty" class="shrink-0 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-5 py-2.5 rounded-xl transition-colors border border-blue-200 shadow-sm outline-none focus:ring-2 focus:ring-blue-500/30">
                    + Add Party
                </button>
            </div>

            <div class="space-y-4">
                @forelse($electionParties as $index => $party)
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-200 relative group transition-colors hover:border-blue-300">

                        {{-- Logo Upload --}}
                        <div class="shrink-0 relative">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 text-center">Party Logo</label>
                            <div class="w-16 h-16 rounded-xl border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group-hover:border-blue-400 transition-colors">
                                @if(isset($party['new_logo']) && $party['new_logo'])
                                    <img src="{{ $party['new_logo']->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif(isset($party['existing_logo']) && $party['existing_logo'])
                                    <img src="{{ asset('storage/'.$party['existing_logo']) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                                <input wire:model="electionParties.{{ $index }}.new_logo" type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 focus:outline-none focus:ring-4 focus:ring-blue-500/30 rounded-xl">
                            </div>
                            <div wire:loading wire:target="electionParties.{{ $index }}.new_logo" class="absolute -bottom-5 left-0 w-full text-center text-[9px] font-bold text-blue-500 uppercase tracking-widest animate-pulse">Loading</div>
                        </div>

                        {{-- Party Name --}}
                        <div class="flex-1 w-full min-w-0">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Party / Slate Name</label>
                            <input wire:model="electionParties.{{ $index }}.name" type="text" placeholder="e.g. Student Alliance" class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                            @error('electionParties.'.$index.'.name') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Party Color --}}
                        <div class="shrink-0 w-full md:w-auto">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Brand Color</label>
                            <div class="flex items-center gap-3">
                                <div class="relative w-12 h-12 rounded-xl overflow-hidden shadow-sm border border-gray-200 shrink-0 hover:border-blue-400 transition-colors focus-within:ring-2 focus-within:ring-blue-500/30">
                                    <input wire:model="electionParties.{{ $index }}.color" type="color" class="absolute -top-4 -left-4 w-24 h-24 cursor-pointer p-0 border-0 outline-none">
                                </div>
                                <span class="font-mono text-xs font-bold text-gray-500 uppercase">{{ $party['color'] ?? '#000000' }}</span>
                            </div>
                            @error('electionParties.'.$index.'.color') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Remove --}}
                        <button type="button" wire:click="removeParty({{ $index }})" class="absolute top-4 right-4 md:relative md:top-auto md:right-auto md:mt-5 text-gray-400 hover:text-red-500 bg-white p-2.5 rounded-xl border border-gray-200 shadow-sm transition-colors shrink-0 group-hover:border-red-200 outline-none focus:ring-2 focus:ring-red-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <p class="text-sm font-bold text-gray-400">No parties registered. Candidates will default to Independent.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- FIXED GLOBAL SAVE BUTTON --}}
    <div class="fixed bottom-0 left-0 right-0 z-50 p-4 md:p-6 bg-gradient-to-t from-white via-white/90 to-transparent pointer-events-none">
        <div class="max-w-5xl mx-auto flex justify-end pointer-events-auto">
            <button wire:click="saveElection" wire:loading.attr="disabled" class="px-8 py-4 bg-gray-900 hover:bg-blue-600 text-white font-black rounded-[1.5rem] shadow-xl transition-all transform active:scale-95 flex items-center gap-3 outline-none focus:ring-4 focus:ring-blue-500/50">
                <span wire:loading.remove wire:target="saveElection">Save Complete Election</span>
                <span wire:loading wire:target="saveElection">Saving Database...</span>
                <span wire:loading wire:target="electionParties.*.new_logo">Uploading Logo...</span>
                <span wire:loading wire:target="cover_photo">Uploading Cover...</span>
                <svg wire:loading.remove wire:target="saveElection, cover_photo, electionParties.*.new_logo" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </div>

    {{-- FACTORY RESET / WIPE MODAL --}}
    @if($showWipeModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-[2rem] p-8 max-w-lg w-full shadow-2xl animate-fade-in-up">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-red-600">Factory Reset Election</h3>
                </div>

                <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                    This action is <span class="font-bold text-gray-900">irreversible</span>. It will permanently delete all:
                    <ul class="list-disc list-inside mt-2 ml-2 font-bold text-gray-800 text-xs space-y-1">
                        <li>Registered Candidates</li>
                        <li>Cast Votes</li>
                        <li>Voter Authentication Logs</li>
                    </ul>
                </p>

                <div class="mb-6">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Confirm Admin Password</label>
                    <input wire:model="adminPassword" type="password" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    @error('adminPassword') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showWipeModal', false)" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                    <button wire:click="executeWipe" class="px-5 py-2.5 text-sm font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg transition-colors outline-none focus:ring-4 focus:ring-red-500/50">Confirm & Wipe Data</button>
                </div>
            </div>
        </div>
    @endif
</div>
