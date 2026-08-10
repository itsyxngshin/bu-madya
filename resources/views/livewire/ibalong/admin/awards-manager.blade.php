<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- System Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-black uppercase tracking-widest text-white">Awards & Citations</h1>
                <span class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-2 py-1">SYS: {{ $activeHackathon->name }}</span>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Forge final awards, designate cohort winners, and manage public broadcast.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Award Forge Form --}}
        <div class="lg:col-span-1">
            <form wire:submit.prevent="createAward" class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 space-y-4 sticky top-6">
                <h2 class="text-lg font-black uppercase tracking-widest border-b-2 border-iba-black pb-2 mb-4">Forge New Award</h2>

                <div>
                    <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Award Title <span class="text-iba-red">*</span></label>
                    <input type="text" wire:model="title" placeholder="e.g. Grand Champion" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                    @error('title') <span class="text-[9px] font-bold text-iba-red uppercase">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Classification <span class="text-iba-red">*</span></label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" wire:model="type" value="ranking" class="w-4 h-4 text-iba-orange border-2 border-iba-black focus:ring-0">
                            <span class="text-[10px] font-black uppercase text-gray-600 group-hover:text-iba-orange transition-colors">Main Ranking</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" wire:model="type" value="special" class="w-4 h-4 text-iba-teal border-2 border-iba-black focus:ring-0">
                            <span class="text-[10px] font-black uppercase text-gray-600 group-hover:text-iba-teal transition-colors">Special Citation</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Remarks / Prize Details (Optional)</label>
                    <textarea wire:model="remarks" rows="2" placeholder="e.g. Sponsored by AWS, includes $1k credits" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 resize-none text-xs"></textarea>
                </div>

                <button type="submit" class="w-full bg-iba-black text-white font-black uppercase tracking-widest py-3 border-2 border-transparent hover:bg-iba-orange hover:text-iba-black transition-colors mt-2">Establish Award</button>
            </form>
        </div>

        {{-- Awards Ledger --}}
        <div class="lg:col-span-2">

            {{-- Main Rankings --}}
            <div class="mb-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-8 bg-iba-black"></div>
                    <h3 class="text-xl font-black uppercase tracking-widest text-iba-black">Podium Rankings</h3>
                    <div class="h-1 flex-1 bg-iba-black"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($awards->where('type', 'ranking') as $award)
                        @include('livewire.ibalong.admin.partials.award-card', ['award' => $award, 'color' => 'iba-orange'])
                    @empty
                        <div class="col-span-full border-4 border-dashed border-gray-300 p-8 text-center text-xs font-black uppercase text-gray-400 tracking-widest">No rankings forged yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Special Citations --}}
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-8 bg-iba-black"></div>
                    <h3 class="text-xl font-black uppercase tracking-widest text-iba-black">Special Citations</h3>
                    <div class="h-1 flex-1 bg-iba-black"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($awards->where('type', 'special') as $award)
                        @include('livewire.ibalong.admin.partials.award-card', ['award' => $award, 'color' => 'iba-teal'])
                    @empty
                        <div class="col-span-full border-4 border-dashed border-gray-300 p-8 text-center text-xs font-black uppercase text-gray-400 tracking-widest">No special citations forged yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL: Assign Cohort --}}
    @if($assigningAwardId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('assigningAwardId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Designate Winner</h3>

                    <form wire:submit.prevent="assignTeamToAward" class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500 block mb-2">Select Cohort</label>
                            <select wire:model="selectedTeamId" class="w-full border-2 border-iba-black p-3 font-black uppercase focus:outline-none focus:border-iba-orange bg-gray-50 cursor-pointer">
                                <option value="">-- No Winner Assigned --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('assigningAwardId', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-3 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-orange text-iba-black font-black uppercase py-3 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Lock Selection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
