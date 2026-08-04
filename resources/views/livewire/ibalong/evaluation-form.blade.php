<div class="max-w-4xl mx-auto space-y-8 pb-24 relative">

    @if($isSubmitted)
        <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-12 text-center relative overflow-hidden animate-fade-in">
            <div class="absolute inset-0 bg-iba-teal/10 animate-pulse"></div>
            <svg class="w-20 h-20 text-iba-teal mx-auto mb-4 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h1 class="text-3xl font-black text-iba-black uppercase tracking-widest relative z-10 mb-2">Transmission Received</h1>
            <p class="text-sm font-bold text-gray-600 uppercase relative z-10 mb-8">Your data has been successfully routed to the Command Center.</p>
            <a href="{{ route('evaluations.index') }}" class="inline-block bg-iba-black text-white text-sm font-black uppercase tracking-widest px-8 py-4 border-4 border-transparent shadow-[4px_4px_0_0_#FF8623] hover:translate-y-1 hover:shadow-none transition-all relative z-10">Return to Directory</a>
        </div>
    @else

        {{-- Form Header --}}
        <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 relative">
            <div class="absolute top-0 left-0 w-full h-2" style="background-color: {{ $evaluation->theme_color }}"></div>

            <div class="flex items-center justify-between mt-2 mb-2">
                <h1 class="text-2xl font-black text-iba-black uppercase tracking-wider whitespace-pre-line">{{ $evaluation->title }}</h1>
                @if($totalPages > 1)
                    <span class="bg-gray-100 border-2 border-iba-black text-iba-black text-[10px] font-black uppercase px-3 py-1 shrink-0 ml-4">
                        Sector {{ $currentPage + 1 }} of {{ $totalPages }}
                    </span>
                @endif
            </div>

            <p class="text-sm font-bold text-gray-700 leading-relaxed whitespace-pre-wrap border-t-2 border-dashed border-gray-300 pt-4">{{ $evaluation->description }}</p>
        </div>

        {{-- Dynamic Page Renderer --}}
        <form wire:submit.prevent="nextPage" class="space-y-6">

            @php
                $currentQIds = $pageMap[$currentPage] ?? [];
                $visibleQuestions = $evaluation->questions->whereIn('id', $currentQIds);
            @endphp

            @foreach($visibleQuestions as $task)

                {{-- Section Headers --}}
                @if($task->type === 'section')
                    <div class="bg-iba-black text-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 mt-12 animate-fade-in-up" wire:key="task-{{ $task->id }}">
                        {{-- Added whitespace-pre-line to preserve text breaks --}}
                        <h2 class="text-xl font-black uppercase tracking-widest whitespace-pre-line">{{ $task->question_text }}</h2>
                        @if($task->description)
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2 whitespace-pre-wrap">{{ $task->description }}</p>
                        @endif
                    </div>

                {{-- Interactive Inputs --}}
                @else
                    <div class="bg-white border-4 border-iba-black shadow-[4px_4px_0_0_#131011] p-6 flex flex-col gap-3 animate-fade-in-up" wire:key="task-{{ $task->id }}">

                        {{-- Added whitespace-pre-line to labels --}}
                        <label class="text-sm font-black uppercase text-iba-black whitespace-pre-line">
                            {{ $task->question_text }}
                            @if($task->is_required) <span class="text-iba-red">*</span> @endif
                        </label>

                        {{-- Added whitespace-pre-wrap to descriptions --}}
                        @if($task->description)
                            <p class="text-xs font-bold text-gray-500 whitespace-pre-wrap">{{ $task->description }}</p>
                        @endif

                        {{-- Text / Short Answer --}}
                        @if($task->type === 'text')
                            <input type="text" wire:model="answers.{{ $task->id }}" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">

                        {{-- Long Text --}}
                        @elseif($task->type === 'textarea')
                            <textarea wire:model="answers.{{ $task->id }}" rows="4" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 resize-none"></textarea>

                        {{-- Dropdown --}}
                        @elseif($task->type === 'dropdown')
                            <select wire:model="answers.{{ $task->id }}" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 cursor-pointer">
                                <option value="">Select an option...</option>
                                @foreach($task->options as $option)
                                    @php $optText = is_array($option) ? $option['text'] : $option; @endphp
                                    <option value="{{ trim($optText) }}">{{ trim($optText) }}</option>
                                @endforeach
                            </select>

                        {{-- Radio / Single Choice --}}
                        @elseif($task->type === 'radio')
                            <div class="flex flex-col gap-3 mt-2">
                                @foreach($task->options as $option)
                                    @php $optText = is_array($option) ? $option['text'] : $option; @endphp
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="radio" wire:model="answers.{{ $task->id }}" value="{{ trim($optText) }}" class="w-5 h-5 mt-0.5 text-iba-orange border-2 border-iba-black focus:ring-0 shrink-0">
                                        <span class="text-xs font-bold uppercase group-hover:text-iba-orange transition-colors whitespace-pre-line">{{ trim($optText) }}</span>
                                    </label>
                                @endforeach
                            </div>

                        {{-- Checkbox / Multiple Choice --}}
                        @elseif($task->type === 'checkbox')
                            <div class="flex flex-col gap-3 mt-2">
                                @foreach($task->options as $option)
                                    @php $optText = is_array($option) ? $option['text'] : $option; @endphp
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" wire:model="answers.{{ $task->id }}" value="{{ trim($optText) }}" class="w-5 h-5 mt-0.5 text-iba-teal border-2 border-iba-black focus:ring-0 shrink-0">
                                        <span class="text-xs font-bold uppercase group-hover:text-iba-teal transition-colors whitespace-pre-line">{{ trim($optText) }}</span>
                                    </label>
                                @endforeach
                            </div>

                        {{-- Likert / Rating Scale --}}
                        @elseif($task->type === 'likert')
                            <div class="overflow-x-auto mt-2">
                                <div class="flex gap-4 min-w-max border-2 border-iba-black bg-gray-50 p-4">
                                    @foreach($task->options as $option)
                                        <label class="flex flex-col items-center gap-2 cursor-pointer group w-24">
                                            <span class="text-[9px] font-black uppercase text-center text-gray-500 group-hover:text-iba-black h-8 flex items-end justify-center whitespace-pre-line">{{ is_array($option) ? $option['text'] ?? '' : $option }}</span>
                                            <input type="radio" wire:model="answers.{{ $task->id }}" value="{{ is_array($option) ? $option['text'] ?? '' : $option }}" class="w-6 h-6 text-iba-orange border-2 border-iba-black focus:ring-0">
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                        {{-- File Upload --}}
                        @elseif($task->type === 'file')
                            <div class="border-2 border-dashed border-gray-400 p-6 text-center bg-gray-50 hover:bg-gray-100 transition-colors relative mt-2">
                                <input type="file" wire:model="files.{{ $task->id }}" id="file_{{ $task->id }}" class="hidden">
                                <label for="file_{{ $task->id }}" class="cursor-pointer flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <span class="text-xs font-black text-iba-black uppercase border-b-2 border-iba-orange">Click to attach file</span>
                                    <span class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-widest">(Max size: 5MB)</span>
                                </label>

                                @if(isset($files[$task->id]))
                                    <p class="text-[10px] font-black text-iba-green mt-3 uppercase">File ready: {{ $files[$task->id]->getClientOriginalName() }}</p>
                                @endif
                                <div wire:loading wire:target="files.{{ $task->id }}" class="text-[10px] font-black text-iba-orange mt-2 uppercase animate-pulse">Processing...</div>
                            </div>
                        @endif

                        @error("answers.{$task->id}") <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
                        @error("files.{$task->id}") <span class="text-[10px] font-black text-iba-red uppercase">⚠ {{ $message }}</span> @enderror
                    </div>
                @endif
            @endforeach

            {{-- Terminal Navigation Protocol --}}
            <div class="pt-8 flex items-center justify-between border-t-4 border-dashed border-gray-300 mt-12 gap-4">

                @if(count($history) > 0)
                    <button type="button" wire:click="previousPage" class="bg-gray-100 text-iba-black text-sm font-black uppercase tracking-widest px-8 py-4 border-4 border-iba-black hover:bg-gray-200 transition-colors">
                        &larr; Reverse
                    </button>
                @else
                    <div></div> {{-- Empty div for flexbox spacing --}}
                @endif

                <button type="submit" class="bg-iba-orange text-iba-black text-sm font-black uppercase tracking-widest px-12 py-4 border-4 border-iba-black shadow-[6px_6px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all relative" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="nextPage, submit">
                        @if($currentPage >= ($totalPages - 1) || (isset($pageBreaks[$currentPage]) && $pageBreaks[$currentPage] === 'submit'))
                            Transmit Data
                        @else
                            Proceed &rarr;
                        @endif
                    </span>
                    <span wire:loading wire:target="nextPage, submit" class="animate-pulse">Verifying...</span>
                </button>

            </div>
        </form>
    @endif
</div>
