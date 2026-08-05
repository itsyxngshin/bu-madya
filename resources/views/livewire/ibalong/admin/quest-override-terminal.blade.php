<div class="max-w-4xl mx-auto space-y-8 pb-24">

    {{-- Admin Header --}}
    <div class="bg-iba-red border-4 border-iba-black shadow-[8px_8px_0_0_#131011] p-6 relative">
        <div class="absolute top-4 right-4 bg-white text-iba-red font-black text-[10px] uppercase tracking-widest px-3 py-1 border-2 border-iba-black animate-pulse">ADMIN OVERRIDE</div>
        
        <h1 class="text-2xl font-black text-white uppercase tracking-wider">Protocol Override</h1>
        <p class="text-sm font-bold text-red-100 uppercase tracking-widest mt-1">Target Cohort: {{ $submission->team->team_name ?? 'Unknown' }} | Quest: {{ $quest->title }}</p>
    </div>

    {{-- Security Gate --}}
    @if(!$isVerified)
        <div class="bg-white border-4 border-iba-red shadow-[8px_8px_0_0_#D93B3B] p-8 mt-8 text-center max-w-md mx-auto">
            <svg class="w-16 h-16 text-iba-red mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <h2 class="text-lg font-black uppercase text-iba-black mb-2">Authentication Required</h2>
            <p class="text-xs font-bold text-gray-500 mb-6 uppercase">Confirm your administrative clearance to unlock and mutate this cohort's submission data.</p>
            
            <form wire:submit.prevent="verifyPassword" class="space-y-4 text-left">
                <div>
                    <input type="password" wire:model="adminPassword" placeholder="Enter your Admin Password" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-red text-center">
                    @error('adminPassword') <span class="text-[10px] font-black text-iba-red uppercase block text-center mt-1">⚠ {{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full bg-iba-red text-white text-sm font-black uppercase tracking-widest py-3 border-2 border-iba-black hover:bg-red-800 transition-colors">Unlock Terminal</button>
            </form>
        </div>
    @else

        {{-- Dynamic Override Form --}}
        <form wire:submit.prevent="executeOverride" class="space-y-6 animate-fade-in">
            @foreach($quest->tasks as $task)
                <div class="bg-white border-4 border-iba-black shadow-[4px_4px_0_0_#D93B3B] p-6 flex flex-col gap-3 relative">
                    
                    <label class="text-sm font-black uppercase text-iba-red">
                        {{ $task->question }}
                        @if($task->is_required) <span class="text-iba-black">*</span> @endif
                    </label>

                    {{-- Short Text --}}
                    @if($task->type === 'short_text')
                        <input type="text" wire:model="answers.{{ $task->id }}" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-red bg-red-50">
                    
                    {{-- Long Text --}}
                    @elseif($task->type === 'long_text')
                        <textarea wire:model="answers.{{ $task->id }}" rows="4" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-red bg-red-50 resize-none"></textarea>
                    
                    {{-- Dropdown --}}
                    @elseif($task->type === 'dropdown')
                        <select wire:model="answers.{{ $task->id }}" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-red bg-red-50">
                            <option value="">Select an option...</option>
                            @foreach($task->options as $option)
                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                            @endforeach
                        </select>

                    {{-- Checklist --}}
                    @elseif($task->type === 'checklist')
                        <div class="flex flex-col gap-2">
                            @foreach($task->options as $option)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model="answers.{{ $task->id }}" value="{{ trim($option) }}" class="w-5 h-5 text-iba-red border-2 border-iba-black focus:ring-0">
                                    <span class="text-xs font-bold uppercase">{{ trim($option) }}</span>
                                </label>
                            @endforeach
                        </div>

                    {{-- File Upload --}}
                    @elseif($task->type === 'file')
                        <div class="border-2 border-dashed border-iba-red p-6 text-center bg-red-50 hover:bg-red-100 transition-colors relative">
                            @if(isset($existingFiles[$task->id]))
                                <div class="mb-4 p-3 bg-white border-2 border-iba-red inline-block">
                                    <p class="text-xs font-black text-iba-red uppercase">✓ File Attached</p>
                                    <a href="{{ Storage::url($existingFiles[$task->id]) }}" target="_blank" class="text-[10px] font-bold text-gray-600 hover:text-iba-black underline">View Current File</a>
                                </div>
                            @endif

                            <input type="file" wire:model="files.{{ $task->id }}" id="file_{{ $task->id }}" class="hidden">
                            <label for="file_{{ $task->id }}" class="cursor-pointer flex flex-col items-center text-iba-red">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span class="text-xs font-black uppercase border-b-2 border-iba-red">
                                    {{ isset($existingFiles[$task->id]) ? 'Force Override File' : 'Click to attach file' }}
                                </span>
                            </label>
                            
                            @if(isset($files[$task->id]))
                                <p class="text-[10px] font-black text-iba-black mt-3 uppercase">File ready for forceful save: {{ $files[$task->id]->getClientOriginalName() }}</p>
                            @endif
                            <div wire:loading wire:target="files.{{ $task->id }}" class="text-[10px] font-black text-iba-red mt-2 uppercase animate-pulse">Processing File...</div>
                        </div>
                    @endif
                    
                    @error("answers.{$task->id}") <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
                    @error("files.{$task->id}") <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
                </div>
            @endforeach

            <div class="pt-6 flex justify-end gap-4">
                <a href="{{ route('ibalong.admin.quests.submissions', $quest->id) }}" class="bg-gray-100 text-iba-black text-sm font-black uppercase tracking-widest px-8 py-4 border-4 border-iba-black hover:bg-gray-200 transition-colors">
                    Cancel Override
                </a>
                <button type="submit" class="bg-iba-red text-white text-sm font-black uppercase tracking-widest px-8 py-4 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">
                    Execute Override Mutation
                </button>
            </div>
        </form>
    @endif
</div>