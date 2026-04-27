<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                {{ $electionRecord ? 'Edit Election' : 'Create Election' }}
            </h1>
            <p class="text-gray-500 font-medium ml-9">
                {{ $electionRecord ? 'Modify settings, timeline, or positions for this event.' : 'Configure a new electoral event from scratch.' }}
            </p>
        </div>
    </div>

    {{-- ALERTS --}}
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-bold flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- MAIN FORM --}}
    <form wire:submit.prevent="saveElection" class="space-y-8">

        {{-- SECTION 1: ELECTION DETAILS --}}
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
            <h2 class="text-xl font-black text-gray-900 mb-6 border-b border-gray-100 pb-4">1. General Details</h2>

            <div class="space-y-6">
                {{-- Cover Photo Upload --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Election Banner / Cover Photo</label>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-full sm:w-48 h-32 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shrink-0 relative">
                            @if ($cover_photo)
                                <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif($electionRecord && $electionRecord->cover_photo_path)
                                <img src="{{ asset('storage/'.$electionRecord->cover_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" wire:model="cover_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer">
                            <div wire:loading wire:target="cover_photo" class="text-[10px] text-blue-500 font-bold mt-2 animate-pulse">Uploading preview...</div>
                            @error('cover_photo') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Title & Type --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Election Title</label>
                        <input wire:model="title" type="text" class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-black text-gray-900 shadow-sm transition-colors" placeholder="e.g. BU USC General Elections 2026">
                        @error('title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Election Type</label>
                        <select wire:model="type" class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 shadow-sm transition-colors">
                            <option value="general">General</option>
                            <option value="special">Special</option>
                            <option value="runoff">Run-off</option>
                        </select>
                        @error('type') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description / Guidelines</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 shadow-sm transition-colors resize-none" placeholder="Provide a brief overview or voting instructions..."></textarea>
                    @error('description') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Settings Toggles --}}
                <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <input wire:model="allow_guest_voting" type="checkbox" id="guest_voting" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    <label for="guest_voting" class="cursor-pointer">
                        <span class="block text-sm font-black text-gray-900">Allow Guest Voting</span>
                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider">Unregistered users can vote by providing their email and college.</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- SECTION 2: TIMELINE --}}
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
            <h2 class="text-xl font-black text-gray-900 mb-6 border-b border-gray-100 pb-4">2. Election Timeline</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-5 bg-blue-50/50 border border-blue-100 rounded-2xl space-y-4">
                    <h3 class="text-xs font-black text-blue-800 uppercase tracking-widest mb-2 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Candidacy Filing Phase</h3>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Filing Opens</label>
                        <input wire:model="application_start" type="datetime-local" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm">
                        @error('application_start') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Filing Closes (Names Locked)</label>
                        <input wire:model="application_end" type="datetime-local" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm">
                        @error('application_end') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="p-5 bg-orange-50/50 border border-orange-100 rounded-2xl space-y-4">
                    <h3 class="text-xs font-black text-orange-800 uppercase tracking-widest mb-2 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Voting & Results Phase</h3>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Voting Opens</label>
                        <input wire:model="voting_start" type="datetime-local" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm">
                        @error('voting_start') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Voting Closes</label>
                        <input wire:model="voting_end" type="datetime-local" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm">
                        @error('voting_end') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Public Results Release</label>
                        <input wire:model="results_release" type="datetime-local" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm">
                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">Leave empty to keep results private.</p>
                        @error('results_release') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: POSITIONS --}}
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-xl font-black text-gray-900">3. Electoral Positions</h2>
                <button type="button" wire:click="addPosition" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">+ Add Position</button>
            </div>

            <div class="space-y-4">
                @foreach($positions as $index => $position)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-200 relative group" wire:key="pos-{{ $position['temp_id'] ?? $index }}">

                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Position Title</label>
                            <input wire:model="positions.{{ $index }}.title" type="text" class="w-full bg-white border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm" placeholder="e.g. President">
                            @error('positions.'.$index.'.title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="w-full sm:w-32 shrink-0">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Winners</label>
                            <input wire:model="positions.{{ $index }}.max_winners" type="number" min="1" class="w-full bg-white border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm text-center">
                            @error('positions.'.$index.'.max_winners') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        @if(count($positions) > 1)
                            <div class="w-full sm:w-auto pt-2 sm:pt-6 flex justify-end">
                                <button type="button" wire:click="removePosition({{ $index }})" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Remove Position">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DANGER ZONE (Only visible when editing an existing election) --}}
        @if($electionRecord)
            <div class="bg-red-50 rounded-[2rem] p-6 md:p-8 border border-red-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-xl font-black text-red-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Danger Zone: Factory Reset
                    </h3>
                    <p class="text-sm font-medium text-red-700 mt-1">Wipe all candidates, votes, and voter logs from this election. This action is irreversible and is primarily used to clear dummy data before going live.</p>
                </div>
                <button type="button" wire:click="confirmWipe" class="shrink-0 px-6 py-3 bg-white border-2 border-red-200 text-red-700 font-black rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-colors shadow-sm">
                    Wipe Election Data
                </button>
            </div>
        @endif

        {{-- SUBMIT BAR --}}
        <div class="bg-gray-900 rounded-[2rem] p-6 shadow-xl relative overflow-hidden mt-8">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-400 font-bold max-w-sm">
                    Double-check your timeline to ensure voting phases do not overlap incorrectly.
                </p>
                <button type="submit" wire:loading.attr="disabled" class="w-full md:w-auto px-10 py-4 bg-blue-600 text-white font-black text-lg rounded-xl shadow-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 transform active:scale-95">
                    <span wire:loading.remove wire:target="saveElection, cover_photo">Save Election Settings</span>
                    <span wire:loading wire:target="saveElection">Saving...</span>
                    <span wire:loading wire:target="cover_photo">Wait for upload...</span>
                </button>
            </div>
        </div>

    </form>

    {{-- PASSWORD VERIFICATION MODAL FOR WIPING DATA --}}
    @if($showWipeModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>

                <h3 class="text-xl font-black text-center text-gray-900 mb-2">Confirm Factory Reset</h3>
                <p class="text-sm text-center text-gray-500 mb-6 leading-relaxed">You are about to permanently delete all candidates and votes associated with this election. To proceed, please enter your admin password.</p>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Administrator Password</label>
                    <input wire:model="adminPassword" type="password" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 rounded-xl px-4 py-3 text-sm font-bold shadow-sm" placeholder="••••••••">
                    @error('adminPassword') <span class="text-[10px] text-red-500 font-bold block mt-1 animate-pulse">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="$set('showWipeModal', false)" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors w-full">Cancel</button>
                    <button type="button" wire:click="executeWipe" class="px-5 py-2.5 text-sm font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg transition-colors w-full flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="executeWipe">Execute Wipe</span>
                        <span wire:loading wire:target="executeWipe">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
