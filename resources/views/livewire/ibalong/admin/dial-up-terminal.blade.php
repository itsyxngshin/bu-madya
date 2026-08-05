<div class="max-w-4xl mx-auto space-y-8 pb-24">

    {{-- Header Section --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 relative overflow-hidden">
        <div class="absolute top-4 right-4 bg-white text-iba-black font-black text-[10px] uppercase tracking-widest px-3 py-1 border-2 border-iba-black animate-pulse">DIAL-UP ACTIVE</div>

        <h1 class="text-2xl font-black text-white uppercase tracking-wider relative z-10">Communications Terminal</h1>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1 relative z-10">Broadcast signals to cohort leaders.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-6 shadow-[4px_4px_0_0_#131011]">
            <p class="text-sm font-black text-iba-teal uppercase tracking-widest flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    {{-- Transmission Form --}}
    <form wire:submit.prevent="transmit" class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] p-8 space-y-6">

        {{-- Target Selection --}}
        <div class="space-y-3">
            <label class="text-sm font-black uppercase text-iba-black block">Signal Destination <span class="text-iba-red">*</span></label>
            <div class="flex flex-col sm:flex-row gap-4">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="radio" wire:model.live="target" value="all" class="w-5 h-5 text-iba-orange border-2 border-iba-black focus:ring-0">
                    <span class="text-xs font-bold uppercase group-hover:text-iba-orange transition-colors">Global Broadcast (All Cohorts)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="radio" wire:model.live="target" value="specific" class="w-5 h-5 text-iba-teal border-2 border-iba-black focus:ring-0">
                    <span class="text-xs font-bold uppercase group-hover:text-iba-teal transition-colors">Targeted Transmit (Selected Cohorts)</span>
                </label>
            </div>
        </div>

        {{-- Multi-Select Checkbox Roster (Conditional) --}}
        @if($target === 'specific')
            <div class="space-y-2 animate-fade-in-up">
                <div class="flex justify-between items-end">
                    <label class="text-xs font-black uppercase text-gray-500 block">Select Target Cohorts</label>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ count($selectedTeamIds) }} Selected</span>
                </div>

                {{-- Scrollable Roster Box --}}
                <div class="border-2 border-iba-black bg-gray-50 max-h-60 overflow-y-auto divide-y-2 divide-gray-200 shadow-inner">
                    @forelse($teams as $team)
                        <label class="flex items-center gap-4 p-3 cursor-pointer hover:bg-orange-50 transition-colors {{ in_array($team->id, $selectedTeamIds) ? 'bg-orange-50/50' : '' }}">
                            <input type="checkbox" wire:model.live="selectedTeamIds" value="{{ $team->id }}" class="w-5 h-5 text-iba-orange border-2 border-iba-black focus:ring-0">
                            <div class="flex flex-col">
                                <span class="text-xs font-black uppercase text-iba-black">{{ $team->team_name }}</span>
                                <span class="text-[9px] font-bold uppercase text-gray-500">{{ $team->user->email ?? 'No Email Attached' }}</span>
                            </div>
                        </label>
                    @empty
                        <div class="p-4 text-center">
                            <span class="text-xs font-bold text-gray-400 uppercase">No active cohorts available.</span>
                        </div>
                    @endforelse
                </div>
                @error('selectedTeamIds') <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
            </div>
        @endif

        {{-- Subject Line --}}
        <div class="space-y-2">
            <label class="text-sm font-black uppercase text-iba-black block">Transmission Subject <span class="text-iba-red">*</span></label>
            <input type="text" wire:model="subject" placeholder="e.g. URGENT: Phase 2 Objectives" class="w-full border-2 border-iba-black p-4 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
            @error('subject') <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
        </div>

        {{-- Message Body (Rich Text Editor) --}}
        <div class="space-y-2">
            <label class="text-sm font-black uppercase text-iba-black block">Message Payload <span class="text-iba-red">*</span></label>

            {{-- Quill.js Assets --}}
            <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
            <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

            {{-- Brutalist Editor Styling --}}
            <style>
                .quill-wrapper .ql-toolbar.ql-snow {
                    border: 2px solid #131011;
                    background-color: #f9fafb;
                    font-family: 'Courier New', Courier, monospace;
                    border-bottom: 2px dashed #131011;
                }
                .quill-wrapper .ql-container.ql-snow {
                    border: 2px solid #131011;
                    border-top: none;
                    background-color: #f9fafb;
                    font-family: inherit;
                    font-size: 14px;
                    font-weight: 700;
                    min-height: 200px;
                }
                /* Focus state border color change */
                .quill-wrapper:focus-within .ql-toolbar.ql-snow,
                .quill-wrapper:focus-within .ql-container.ql-snow {
                    border-color: #FF8623;
                }
                .quill-wrapper:focus-within .ql-toolbar.ql-snow {
                    border-bottom: 2px dashed #FF8623;
                }
            </style>

            {{-- Alpine.js + Livewire Editor Bridge --}}
            <div class="quill-wrapper" wire:ignore
                 x-data="{
                     initQuill() {
                         let quill = new Quill(this.$refs.quillEditor, {
                             theme: 'snow',
                             placeholder: 'Type your formatted directive here...',
                             modules: {
                                 toolbar: [
                                     ['bold', 'italic', 'underline'],
                                     [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                     ['link', 'clean']
                                 ]
                             }
                         });

                         // Bind Quill to Livewire
                         quill.on('text-change', () => {
                             $wire.set('messageBody', quill.root.innerHTML);
                         });

                         // Reset editor when transmission is sent successfully
                         window.addEventListener('messageBody-reset', () => {
                             quill.setContents([]);
                         });
                     }
                 }"
                 x-init="initQuill()">

                <div x-ref="quillEditor"></div>
            </div>

            @error('messageBody') <span class="text-[10px] font-black text-iba-red uppercase block mt-1">⚠ {{ $message }}</span> @enderror
        </div>

        {{-- Action Button --}}
        <div class="pt-6 border-t-4 border-dashed border-gray-300 flex justify-end">
            <button type="submit" class="bg-iba-orange text-iba-black text-sm font-black uppercase tracking-widest px-12 py-4 border-4 border-iba-black shadow-[6px_6px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all relative" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="transmit">Execute Transmission &rarr;</span>
                <span wire:loading wire:target="transmit" class="animate-pulse">Routing Signal...</span>
            </button>
        </div>
    </form>
</div>
