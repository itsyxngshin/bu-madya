<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32" x-data="{ tab: @entangle('activeTab') }">
    
    {{-- PAGE HEADER & GLOBAL SAVE BUTTON --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Edit Election
            </h1>
            <p class="text-gray-500 font-medium ml-9">Modifying: <span class="font-bold">{{ $election->title }}</span></p>
        </div>
        
        <button type="button" wire:click="saveElection" wire:loading.attr="disabled" class="hidden md:flex px-6 py-3 bg-gray-900 text-white font-black rounded-xl shadow-lg hover:bg-orange-600 transition-colors items-center gap-2">
            <span wire:loading.remove wire:target="saveElection">Save Changes</span>
            <span wire:loading wire:target="saveElection">Saving...</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-bold flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- TAB NAVIGATION --}}
    <div class="flex space-x-2 bg-gray-200/50 p-1 rounded-2xl w-full md:w-max mb-6 overflow-x-auto">
        <button @click="tab = 'details'" :class="tab === 'details' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all whitespace-nowrap">1. Details</button>
        <button @click="tab = 'timeline'" :class="tab === 'timeline' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all whitespace-nowrap">2. Timeline</button>
        <button @click="tab = 'positions'" :class="tab === 'positions' ? 'bg-white text-gray-900 shadow-sm font-black' : 'text-gray-500 font-bold'" class="px-6 py-2.5 rounded-xl text-sm transition-all whitespace-nowrap">3. Positions</button>
    </div>

    {{-- MAIN FORM ENCLOSURE --}}
    <form wire:submit.prevent="saveElection" class="space-y-8">
        
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 p-6 md:p-8">
            
            {{-- ================================================================= --}}
            {{-- STEP 1: DETAILS --}}
            {{-- ================================================================= --}}
            <div x-show="tab === 'details'" x-cloak class="space-y-6 animate-fade-in-up">
                
                <h2 class="text-xl font-black text-gray-900 mb-6 border-b border-gray-100 pb-4">General Configuration</h2>
                
                {{-- Photo Upload Preview --}}
                <div class="mb-6">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Cover Photo</label>
                    @if($cover_photo)
                        <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-40 object-cover rounded-2xl mb-3 shadow-sm border border-gray-200">
                    @elseif(!empty($existing_cover_photo))
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
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Election Title</label>
                    <input type="text" wire:model="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3 shadow-sm font-bold text-gray-900">
                    @error('title') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Custom URL Slug Editor --}}
                <div x-data="{ editSlug: false }">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Public URL Slug</label>
                    <div class="flex rounded-xl shadow-sm relative transition-all" :class="editSlug ? 'ring-2 ring-orange-500' : ''">
                        <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-200 bg-gray-100 text-gray-500 sm:text-sm font-bold">/elections/</span>
                        <input type="text" wire:model="slug" :readonly="!editSlug" 
                               class="flex-1 min-w-0 block w-full px-4 py-3 border border-gray-200 focus:ring-0 font-bold text-gray-900 shadow-sm transition-colors" 
                               :class="editSlug ? 'bg-white border-orange-500' : 'bg-gray-50 text-gray-500 cursor-not-allowed'" 
                               placeholder="e.g. 2026-general-elections">
                        <button type="button" @click="editSlug = !editSlug" 
                                class="inline-flex items-center px-4 rounded-r-xl border border-l-0 border-gray-200 font-bold text-xs transition-colors"
                                :class="editSlug ? 'bg-orange-100 text-orange-700 hover:bg-orange-200 border-orange-500' : 'bg-white text-gray-600 hover:bg-gray-50'">
                            <span x-show="!editSlug">Edit</span>
                            <span x-show="editSlug" x-cloak>Lock</span>
                        </button>
                    </div>
                    <div x-show="editSlug" style="display: none;" class="mt-2 text-[10px] text-orange-600 font-black uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Warning: Changing this will break links already shared with students!
                    </div>
                    @error('slug') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3 shadow-sm resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Election Type</label>
                        <select wire:model="type" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-orange-500 p-3 font-semibold text-gray-700 shadow-sm cursor-pointer">
                            <option value="general">General Election</option>
                            <option value="special">Special Election</option>
                            <option value="runoff">Run-off</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Guest Access</label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors shadow-sm mt-1">
                            <input type="checkbox" wire:model="allow_guest_voting" class="w-5 h-5 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                            <span class="text-sm font-bold text-gray-700">Allow Guest Voting</span>
                        </label>
                    </div>
                </div>

                {{-- DANGER ZONE (Factory Reset) --}}
                <div class="mt-10 bg-red-50 rounded-2xl p-6 md:p-8 border border-red-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-lg md:text-xl font-black text-red-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Danger Zone: Factory Reset
                        </h3>
                        <p class="text-sm font-medium text-red-700 mt-1">Wipe all candidates, votes, and voter logs from this election. This action is irreversible.</p>
                    </div>
                    <button type="button" wire:click="confirmWipe" class="shrink-0 w-full md:w-auto px-6 py-3 bg-white border-2 border-red-200 text-red-700 font-black rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-colors shadow-sm">
                        Wipe Election Data
                    </button>
                </div>

            </div>

            {{-- ================================================================= --}}
            {{-- STEP 2: TIMELINE --}}
            {{-- ================================================================= --}}
            <div x-show="tab === 'timeline'" x-cloak class="space-y-6 animate-fade-in-up">
                <h2 class="text-xl font-black text-gray-900 mb-6 border-b border-gray-100 pb-4">Schedule & Deadlines</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-2xl border border-blue-100">
                    <div class="md:col-span-2 text-blue-800 font-black text-sm uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Application Phase
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Candidacy Opens</label>
                        <input type="datetime-local" wire:model="application_start" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold focus:ring-blue-500">
                        @error('application_start') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Candidacy Closes</label>
                        <input type="datetime-local" wire:model="application_end" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold focus:ring-blue-500">
                        @error('application_end') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-orange-50/50 rounded-2xl border border-orange-100">
                    <div class="md:col-span-2 text-orange-800 font-black text-sm uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Live Voting Phase
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Ballots Open</label>
                        <input type="datetime-local" wire:model="voting_start" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold focus:ring-orange-500">
                        @error('voting_start') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Ballots Close</label>
                        <input type="datetime-local" wire:model="voting_end" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold focus:ring-orange-500">
                        @error('voting_end') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-green-50/50 rounded-2xl border border-green-100">
                    <div class="md:col-span-2 text-green-800 font-black text-sm uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        Results Phase
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Automated Results Release</label>
                        <input type="datetime-local" wire:model="results_release" class="w-full rounded-xl border-gray-200 p-3 bg-white shadow-sm font-semibold focus:ring-green-500">
                        <p class="text-[10px] text-gray-500 font-bold mt-1">Leave blank to keep results hidden manually.</p>
                        @error('results_release') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ================================================================= --}}
            {{-- STEP 3: POSITIONS --}}
            {{-- ================================================================= --}}
            <div x-show="tab === 'positions'" x-cloak class="animate-fade-in-up">
                
                @if (session()->has('position_error'))
                    <div class="mb-4 bg-red-50 text-red-700 p-3 rounded-xl border border-red-200 font-bold text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ session('position_error') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Ballot Configuration</h3>
                    <button wire:click="addPosition" type="button" class="text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition-colors shadow-sm flex items-center gap-1">
                        <span>+</span> Add Position
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($positions as $index => $pos)
                        @php 
                            $hasCandidates = isset($pos['candidate_count']) && $pos['candidate_count'] > 0;
                            $rowKey = isset($pos['id']) ? 'db-'.$pos['id'] : 'temp-'.($pos['temp_id'] ?? $index);
                        @endphp

                        {{-- CSS Grid guarantees the inputs never stretch out of bounds --}}
                        <div wire:key="pos-{{ $rowKey }}" class="bg-gray-50 p-4 md:p-5 rounded-2xl border border-gray-200 relative group shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                                
                                {{-- Title Block (8 columns) --}}
                                <div class="md:col-span-8">
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

                                {{-- Max Winners Block (3 columns) --}}
                                <div class="md:col-span-3">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 md:text-center text-left">Max Winners</label>
                                    <input type="number" wire:model="positions.{{ $index }}.max_winners" min="1" class="w-full rounded-xl border-gray-200 text-sm font-bold p-3 text-center shadow-sm focus:ring-orange-500">
                                    @error('positions.'.$index.'.max_winners') <span class="text-[10px] text-red-500 font-bold mt-1 block md:text-center">{{ $message }}</span> @enderror
                                </div>

                                {{-- Action Buttons Block (1 column) --}}
                                <div class="md:col-span-1 flex justify-end md:justify-center pt-0 md:pt-6">
                                    @if($hasCandidates)
                                        <button type="button" disabled class="text-gray-300 cursor-not-allowed p-2" title="Cannot delete: Candidates exist">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </button>
                                    @else
                                        @if(count($positions) > 1)
                                            <button type="button" wire:click="removePosition({{ $index }})" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors bg-white border border-gray-200 shadow-sm" title="Remove Position">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        @else
                                            <div class="w-10 h-10"></div> {{-- Spacer to keep alignment intact when only 1 position exists --}}
                                        @endif
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- MAIN SUBMIT BAR (STAYS AT THE BOTTOM) --}}
        <div class="bg-gray-900 rounded-[2rem] p-6 shadow-xl relative overflow-hidden mt-8">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-400 font-bold max-w-sm">
                    Double-check your timeline to ensure voting phases do not overlap incorrectly.
                </p>
                <button type="submit" wire:loading.attr="disabled" class="w-full md:w-auto px-10 py-4 bg-orange-600 text-white font-black text-lg rounded-xl shadow-lg hover:bg-orange-700 transition-colors flex items-center justify-center gap-2 transform active:scale-95">
                    <span wire:loading.remove wire:target="saveElection, cover_photo">Save Election Settings</span>
                    <span wire:loading wire:target="saveElection">Saving...</span>
                    <span wire:loading wire:target="cover_photo">Wait for upload...</span>
                </button>
            </div>
        </div>

    </form>

    {{-- ================================================================= --}}
    {{-- PASSWORD VERIFICATION MODAL FOR WIPING DATA --}}
    {{-- ================================================================= --}}
    @if($showWipeModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
            <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl animate-fade-in-up">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                
                <h3 class="text-xl font-black text-center text-gray-900 mb-2">Confirm Factory Reset</h3>
                <p class="text-sm text-center text-gray-500 mb-6 leading-relaxed">You are about to permanently delete all candidates and votes associated with this election. Enter your admin password to proceed.</p>
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Administrator Password</label>
                    <input wire:model="adminPassword" type="password" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 rounded-xl px-4 py-3 text-sm font-black shadow-sm transition-colors" placeholder="••••••••">
                    @error('adminPassword') <span class="text-[10px] text-red-500 font-bold block mt-1 animate-pulse">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="$set('showWipeModal', false)" class="px-5 py-3 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors w-full">Cancel</button>
                    <button type="button" wire:click="executeWipe" class="px-5 py-3 text-sm font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg transition-colors w-full flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="executeWipe">Execute Wipe</span>
                        <span wire:loading wire:target="executeWipe">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>