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

                {{-- File Upload (Upgraded with Drag & Drop + Progress Bar) --}}
                @elseif($task->type === 'file')

                    {{-- Alpine Component for Upload & Drag Tracking --}}
                    <div x-data="{ isUploading: false, progress: 0, isDragging: false }"
                         x-on:livewire-upload-start="isUploading = true"
                         x-on:livewire-upload-finish="isUploading = false"
                         x-on:livewire-upload-error="isUploading = false"
                         x-on:livewire-upload-progress="progress = $event.detail.progress"
                         x-on:dragover.prevent="isDragging = true"
                         x-on:dragleave.prevent="isDragging = false"
                         x-on:drop.prevent="isDragging = false; if($event.dataTransfer.files.length > 0) { $wire.upload('files.{{ $task->id }}', $event.dataTransfer.files[0]) }"
                         class="border-4 border-dashed p-6 text-center transition-all duration-200 relative"
                         :class="isDragging ? 'border-iba-orange bg-orange-50 shadow-[inset_0_0_15px_rgba(255,134,35,0.2)]' : 'border-gray-400 bg-gray-50 hover:bg-gray-100'"
                         >

                        {{-- Existing Attached File Display --}}
                        @if(isset($existingFiles[$task->id]))
                            @php
                                $existingExt = strtoupper(pathinfo($existingFiles[$task->id], PATHINFO_EXTENSION));
                            @endphp
                            <div class="mb-6 p-3 bg-white border-2 border-iba-teal shadow-[3px_3px_0_0_#0095AC] flex flex-col sm:flex-row items-center justify-between gap-3 relative z-10">
                                <div class="flex items-center gap-3">
                                    <span class="bg-iba-teal text-white text-[10px] font-black px-2 py-1 border-2 border-iba-black">{{ $existingExt }}</span>
                                    <div class="text-left">
                                        <p class="text-xs font-black text-iba-black uppercase leading-tight">File Attached</p>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase">Saved to server</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($existingFiles[$task->id]) }}" target="_blank" class="bg-gray-100 text-[10px] font-black text-iba-black px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:bg-iba-teal hover:text-white hover:translate-y-0.5 hover:shadow-none transition-all uppercase tracking-widest shrink-0">
                                    Download
                                </a>
                            </div>
                        @endif

                        @if(!$isLocked)
                            {{-- Hidden Input for Manual Clicks --}}
                            <input type="file" wire:model="files.{{ $task->id }}" id="file_{{ $task->id }}" class="hidden">

                            {{-- Upload Dropzone UI --}}
                            <label for="file_{{ $task->id }}" class="cursor-pointer flex flex-col items-center group relative z-10">
                                <div class="w-12 h-12 bg-white border-2 border-iba-black flex items-center justify-center transition-all mb-3"
                                     :class="isDragging ? 'bg-iba-orange shadow-[3px_3px_0_0_#131011] scale-110' : 'group-hover:bg-iba-orange group-hover:shadow-[3px_3px_0_0_#131011]'">
                                    <svg class="w-6 h-6 text-iba-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <span class="text-xs font-black text-iba-black uppercase border-b-2 border-iba-orange transition-colors"
                                      :class="isDragging ? 'text-iba-orange' : 'group-hover:text-iba-orange'">
                                    {{ isset($existingFiles[$task->id]) ? 'Drag & Drop or Click to Replace' : 'Drag & Drop or Click to Attach' }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-500 mt-2 uppercase">Max Size: {{ $task->max_file_size_mb }}MB</span>
                                <span class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-widest">(Special characters in filenames will be auto-formatted)</span>
                            </label>

                            {{-- Drag Overlay Indicator --}}
                            <div x-show="isDragging" class="absolute inset-0 z-0 bg-orange-50/50 backdrop-blur-[1px] flex items-center justify-center pointer-events-none" style="display: none;"></div>

                            {{-- Livewire / Alpine Upload Progress Bar --}}
                            <div x-show="isUploading" class="w-full max-w-sm mx-auto mt-6 relative z-10" style="display: none;">
                                <div class="flex justify-between mb-1">
                                    <span class="text-[10px] font-black uppercase text-iba-orange tracking-widest">Transmitting...</span>
                                    <span class="text-[10px] font-black text-iba-orange" x-text="progress + '%'"></span>
                                </div>
                                <div class="w-full bg-white border-2 border-iba-black h-4 shadow-[2px_2px_0_0_#131011]">
                                    <div class="bg-iba-orange h-full border-r-2 border-iba-black transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                </div>
                            </div>

                            {{-- Pending File Display (Before Submission) --}}
                            @if(isset($files[$task->id]))
                                @php
                                    $newExt = strtoupper($files[$task->id]->getClientOriginalExtension());
                                @endphp
                                <div class="mt-6 inline-flex items-center gap-3 bg-iba-green/10 border-2 border-iba-green p-2 text-left relative z-10">
                                    <span class="bg-iba-green text-white text-[9px] font-black px-2 py-1 border-2 border-iba-black shrink-0">{{ $newExt }}</span>
                                    <p class="text-[10px] font-black text-iba-green uppercase truncate max-w-[200px]">{{ $files[$task->id]->getClientOriginalName() }}</p>
                                </div>
                            @endif

                        @else
                            <p class="text-xs font-black text-iba-teal uppercase tracking-widest mt-4">File locked for evaluation.</p>
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
