<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-[#1A1617] p-6 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <div>
            <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Team Accounts</h1>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Manage portal access and view profiles for approved cohort teams.</p>
        </div>
        
        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search team or email..." class="w-full sm:w-64 border-2 border-iba-black dark:border-iba-light p-2 text-sm bg-white dark:bg-gray-900 focus:outline-none focus:border-iba-teal text-iba-black dark:text-white font-bold uppercase">
            
            {{-- Data Extraction Button (Visible only to Admins) --}}
            @if(in_array(auth('ibalong')->user()->role_id, [1, 2]))
                <button wire:click="exportTeamRoster" wire:loading.attr="disabled" class="bg-iba-teal text-white font-black px-4 py-2 text-xs uppercase tracking-widest border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all flex items-center justify-center gap-2 w-full sm:w-auto cursor-pointer">
                    <svg wire:loading.remove wire:target="exportTeamRoster" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <svg wire:loading wire:target="exportTeamRoster" class="w-4 h-4 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span wire:loading.remove wire:target="exportTeamRoster">Extract Roster</span>
                    <span wire:loading wire:target="exportTeamRoster">Extracting...</span>
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Team Accounts Table --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] overflow-x-auto">
        <table class="min-w-full divide-y-2 divide-iba-black dark:divide-iba-light">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Startup / Team Name</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Account Email</th>
                    <th class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-[#1A1617]">
                @forelse($teams as $team)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-iba-black dark:text-white uppercase">{{ $team->team_name }}</div>
                            <div class="text-xs text-gray-500 font-semibold mt-1 uppercase">{{ $team->affiliation }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600 dark:text-gray-300">
                            {{ $team->user->email ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($team->user)
                                <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 {{ $team->user->is_active ? 'border-iba-green text-iba-green bg-green-50 dark:bg-green-900/30' : 'border-iba-red text-iba-red bg-red-50 dark:bg-red-900/30' }}">
                                    {{ $team->user->is_active ? 'Active Portal' : 'Locked Out' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-3">
                                <button wire:click="viewTeamDetails({{ $team->id }})" class="text-blue-600 hover:text-blue-900 font-bold uppercase text-xs tracking-wider">Profile</button>

                                {{-- RBAC: Only Admins and Super Admins can manage account access --}}
                                @if($team->user && in_array(auth('ibalong')->user()->role_id, [1, 2]))
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <button wire:click="confirmPasswordReset({{ $team->user->id }})" class="text-iba-orange hover:text-orange-700 font-bold uppercase text-xs tracking-wider">Reset Pass</button>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <button wire:click="toggleAccountStatus({{ $team->user->id }})" class="font-bold uppercase text-xs tracking-wider {{ $team->user->is_active ? 'text-iba-red hover:text-red-900' : 'text-iba-green hover:text-green-900' }}">
                                        {{ $team->user->is_active ? 'Lock' : 'Unlock' }}
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center">
                            <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wider">No team accounts match your criteria.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $teams->links() }}
    </div>

    <div class="h-16 sm:h-24 w-full flex-shrink-0"></div>

    {{-- MODAL: FULL TEAM PROFILE --}}
    @if($showModal && $viewingTeam)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-5xl bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] flex flex-col text-left max-h-[90vh]">

                    <div class="px-6 py-4 border-b-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-black text-iba-black dark:text-white uppercase tracking-wider">{{ $viewingTeam->team_name }}</h3>
                            <p class="text-sm font-bold text-iba-teal mt-1 uppercase">{{ $viewingTeam->affiliation }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-iba-red transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <div class="px-6 py-6 overflow-y-auto space-y-8 flex-1">

                        {{-- Brand & Manifesto Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Team Logo Panel --}}
                            <div class="flex flex-col h-full">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Team Identity</h4>
                                <div class="bg-gray-50 dark:bg-gray-900 border-2 border-iba-black dark:border-gray-700 p-4 flex flex-col items-center justify-center flex-1">
                                    @if($viewingTeam->logo_path)
                                        <div class="w-24 h-24 bg-white border-2 border-iba-black shadow-[2px_2px_0_0_#131011] mb-3 p-1">
                                            <img src="{{ Storage::url($viewingTeam->logo_path) }}" class="w-full h-full object-contain">
                                        </div>
                                        <a href="{{ Storage::url($viewingTeam->logo_path) }}" download="{{ \Illuminate\Support\Str::slug($viewingTeam->team_name) }}-logo" class="bg-iba-teal text-white text-[9px] font-black uppercase px-3 py-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all text-center w-full">Download Logo</a>
                                    @else
                                        <span class="text-3xl mb-1">🚀</span>
                                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest text-center">No Logo<br>Uploaded</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Manifesto Panel --}}
                            <div class="md:col-span-2 flex flex-col h-full">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cohort Manifesto</h4>
                                <div class="bg-gray-50 dark:bg-gray-900 border-2 border-iba-black dark:border-gray-700 p-4 font-bold text-sm text-iba-black dark:text-gray-300 leading-relaxed flex-1 whitespace-pre-wrap">"{{ $viewingTeam->team_about }}"</div>
                            </div>
                        </div>

                        {{-- Roster Data --}}
                        <div>
                            <h4 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-4 border-l-4 border-iba-teal pl-3">Team Roster ({{ $viewingTeam->number_of_team_members }})</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($viewingTeam->members as $member)
                                    <div class="border-2 border-iba-black dark:border-iba-light p-4 bg-white dark:bg-gray-800 shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] flex items-start gap-4">

                                        {{-- Member Photo & Download --}}
                                        <div class="flex flex-col items-center gap-2 shrink-0 mt-1">
                                            <div class="w-14 h-14 border-2 {{ $member->photo_path ? 'border-iba-black' : 'border-dashed border-iba-red' }} overflow-hidden bg-gray-100 dark:bg-[#1A1617] flex items-center justify-center">
                                                @if($member->photo_path)
                                                    <img src="{{ Storage::url($member->photo_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="font-black text-xl text-gray-400 uppercase">{{ substr($member->full_name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            @if($member->photo_path)
                                                <a href="{{ Storage::url($member->photo_path) }}" download="{{ \Illuminate\Support\Str::slug($member->full_name) }}-photo" class="text-[8px] font-black uppercase tracking-widest text-iba-teal hover:underline text-center">Download</a>
                                            @endif
                                        </div>

                                        {{-- Member Details --}}
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-bold text-sm text-iba-black dark:text-white uppercase leading-tight break-words mb-1" title="{{ $member->full_name }}">{{ $member->full_name }}</h5>
                                            <span class="inline-block px-2 py-0.5 border border-iba-black text-[8px] font-bold uppercase tracking-wider mb-2 {{ $member->team_role == 'Team Leader' ? 'bg-iba-red text-white' : 'bg-gray-100 text-iba-black' }}">{{ $member->team_role }}</span>

                                            <div class="text-[10px] font-semibold text-gray-600 dark:text-gray-400 space-y-0.5 truncate">
                                                <p class="truncate" title="{{ $member->email_address }}">{{ $member->email_address }}</p>
                                                <p>{{ $member->mobile_number ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t-4 border-iba-black dark:border-iba-light sm:flex sm:flex-row-reverse gap-4 shrink-0">
                        <button wire:click="closeModal" type="button" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black dark:text-gray-400 dark:hover:text-white transition-colors w-full sm:w-auto border-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600">Close Window</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: RESET PASSWORD --}}
    @if($passwordModalOpen && in_array(auth('ibalong')->user()->role_id, [1, 2]))
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full sm:max-w-md flex flex-col bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] text-left transition-all overflow-hidden">

                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-b-4 border-iba-black dark:border-iba-light flex justify-between items-center">
                        <h3 class="text-lg font-black text-iba-black dark:text-white uppercase tracking-wider">Force Account Reset</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-iba-red"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    <div class="p-6">
                        @if(!$generated_password)
                            <form wire:submit.prevent="executePasswordReset" class="space-y-4">
                                <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-4 leading-relaxed uppercase">To securely reset this team's portal access, verify your admin clearance.</p>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">New Custom Password</label>
                                    <input type="text" wire:model="new_password" placeholder="Leave blank to auto-generate" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                                    @error('new_password') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Your Admin Password <span class="text-iba-red">*</span></label>
                                    <input type="password" wire:model="admin_password" class="w-full border-2 border-iba-black dark:border-iba-light p-2 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-900 text-iba-black dark:text-white font-bold">
                                    @error('admin_password') <span class="text-iba-red text-xs font-bold block mt-1 uppercase">⚠ {{ $message }}</span> @enderror
                                </div>

                                <div class="pt-4 flex gap-3">
                                    <button type="button" wire:click="closeModal" class="w-full px-4 py-2 border-2 border-iba-black dark:border-iba-light bg-gray-100 dark:bg-gray-800 text-sm font-bold uppercase text-gray-700 dark:text-gray-300 hover:bg-gray-200">Cancel</button>
                                    <button type="submit" class="w-full bg-iba-orange text-iba-black font-bold px-4 py-2 text-sm uppercase border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Force Reset & Email</button>
                                </div>
                            </form>
                        @else
                            {{-- Success Screen --}}
                            <div class="text-center">
                                <h3 class="text-lg font-black text-iba-black dark:text-white uppercase mb-2 text-iba-green">Keys Regenerated!</h3>
                                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-4">The new credentials have been successfully emailed to the team.</p>

                                <div class="bg-gray-100 dark:bg-gray-900 border-2 border-iba-black dark:border-iba-light p-4 text-center mb-6 shadow-inner">
                                    <span class="font-pixel text-xl font-bold text-iba-teal tracking-widest">{{ $generated_password }}</span>
                                </div>

                                <button wire:click="closeModal" class="w-full bg-iba-black dark:bg-iba-light text-white dark:text-iba-black font-bold px-6 py-2.5 text-sm uppercase border-2 border-transparent shadow-[3px_3px_0_0_#FF8623] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">Close Window</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>