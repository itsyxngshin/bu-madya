<div class="max-w-4xl mx-auto space-y-8 pb-24">

    {{-- Quest Header --}}
    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 relative overflow-hidden">
        @if($submission->status === 'submitted')
            <div class="absolute top-4 right-4 bg-iba-green text-white font-black text-[10px] uppercase tracking-widest px-3 py-1 border-2 border-iba-black animate-pulse">SUBMITTED</div>
        @endif

        <h1 class="text-2xl font-black text-iba-black uppercase tracking-wider">{{ $quest->title }}</h1>
        <div class="flex items-center gap-4 mt-2 mb-4 border-b-2 border-dashed border-gray-300 pb-4">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Deadline: {{ $quest->deadline->format('M d, Y - h:i A') }}
            </span>
        </div>
        <p class="text-sm font-bold text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $quest->description }}</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center shadow-[4px_4px_0_0_#131011]">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-iba-red/10 border-l-4 border-iba-red p-4 flex items-center shadow-[4px_4px_0_0_#131011]">
            <p class="text-sm font-bold text-iba-red uppercase tracking-wider">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Dynamic Submission Form --}}
    <form wire:submit.prevent="submitQuest" class="space-y-6">
        @foreach($quest->tasks as $task)
            <div class="bg-white border-4 border-iba-black shadow-[4px_4px_0_0_#131011] p-6 flex flex-col gap-3">
                <label class="text-sm font-black uppercase text-iba-black">
                    {{ $task->question }}
                    @if($task->is_required) <span class="text-iba-red">*</span> @endif
                </label>

                {{-- Short Text --}}
                @if($task->type === 'short_text')
                    <input type="text" wire:model="answers.{{ $task->id }}" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-orange bg-gray-50" {{ $submission->status === 'submitted' ? 'disabled' : '' }}>

                {{-- Long Text --}}
                @elseif($task->type === 'long_text')
                    <textarea wire:model="answers.{{ $task->id }}" rows="4" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 resize-none" {{ $submission->status === 'submitted' ? 'disabled' : '' }}></textarea>

                {{-- Dropdown --}}
                @elseif($task->type === 'dropdown')
                    <select wire:model="answers.{{ $task->id }}" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-orange bg-gray-50" {{ $submission->status === 'submitted' ? 'disabled' : '' }}>
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
                                <input type="checkbox" wire:model="answers.{{ $task->id }}" value="{{ trim($option) }}" class="w-5 h-5 text-iba-teal border-2 border-iba-black focus:ring-0" {{ $submission->status === 'submitted' ? 'disabled' : '' }}>
                                <span class="text-xs font-bold uppercase">{{ trim($option) }}</span>
                            </label>
                        @endforeach
                    </div>

                {{-- File Upload --}}
                @elseif($task->type === 'file')
                    <div class="border-2 border-dashed border-gray-400 p-6 text-center bg-gray-50 hover:bg-gray-100 transition-colors relative">
                        @if(isset($existingFiles[$task->id]))
                            <div class="mb-4 p-3 bg-iba-teal/10 border-2 border-iba-teal inline-block">
                                <p class="text-xs font-black text-iba-teal uppercase">✓ File Attached to Draft</p>
                                <a href="{{ Storage::url($existingFiles[$task->id]) }}" target="_blank" class="text-[10px] font-bold text-gray-600 hover:text-iba-black underline">View Current File</a>
                            </div>
                        @endif

                        @if($submission->status !== 'submitted')
                            <input type="file" wire:model="files.{{ $task->id }}" id="file_{{ $task->id }}" class="hidden">
                            <label for="file_{{ $task->id }}" class="cursor-pointer flex flex-col items-center">
                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span class="text-xs font-black text-iba-black uppercase border-b-2 border-iba-orange">
                                    {{ isset($existingFiles[$task->id]) ? 'Upload New File to Replace' : 'Click to attach file' }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-500 mt-2 uppercase">Max Size: {{ $task->max_file_size_mb }}MB</span>
                            </label>
                            
                            @if(isset($files[$task->id]))
                                <p class="text-[10px] font-black text-iba-green mt-3 uppercase">File ready for save: {{ $files[$task->id]->getClientOriginalName() }}</p>
                            @endif

                            <div wire:loading wire:target="files.{{ $task->id }}" class="text-[10px] font-black text-iba-orange mt-2 uppercase animate-pulse">Processing File...</div>
                        @endif
                    </div>
                @endif
                
                @error("answers.{$task->id}") <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
                @error("files.{$task->id}") <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
            </div>
        @endforeach

        @if($submission->status !== 'submitted' && !$quest->deadline->isPast())
            <div class="pt-6 flex flex-col sm:flex-row gap-4 justify-end">
                <button type="button" wire:click="saveDraft" class="bg-gray-100 text-iba-black text-sm font-black uppercase tracking-widest px-8 py-4 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">
                    Save Draft
                </button>
                <button type="submit" class="bg-iba-orange text-iba-black text-sm font-black uppercase tracking-widest px-8 py-4 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">
                    Submit to Council
                </button>
            </div>
        @endif
    </form>
</div>
