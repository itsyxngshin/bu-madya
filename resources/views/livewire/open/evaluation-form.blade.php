<div class="min-h-screen bg-purple-50/30 pb-20 font-sans text-gray-900 selection:bg-orange-100 selection:text-orange-600">

    {{-- STATE: FORM CLOSED --}}
    @if(!$evaluation->is_active)
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="max-w-md w-full bg-white rounded-xl p-8 shadow-md border-t-8 border-red-500 text-center">
                <h1 class="text-2xl font-sans text-gray-900 mb-2">Form Closed</h1>
                <p class="text-sm text-gray-600 mb-6">This form is no longer accepting responses.</p>
                <a href="{{ route('open.home') }}" class="text-sm font-bold text-blue-600 hover:underline">
                    Resume Evaluation
                </a>
            </div>
        </div>
    
    {{-- STATE: FORM OPEN --}}
    @else
        
        {{-- 1. COMPACT STICKY HEADER --}}
        <div class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-2xl mx-auto px-4 h-12 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-500 truncate max-w-[200px]">{{ $evaluation->title }}</span>
                </div>

                {{-- Compact Progress --}}
                <div class="flex items-center gap-3">
                    <div class="w-32 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-green-600 transition-all duration-500 ease-out" 
                             style="width: {{ $this->progress }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-gray-500">{{ $this->progress }}%</span>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA --}}
        <div class="max-w-2xl mx-auto px-4 mt-6">
            
            {{-- HERO CARD (Google Forms Style) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-t-[10px] border-t-orange-600 mb-4 relative overflow-hidden">
                <div class="p-6">
                    <h1 class="text-3xl font-sans text-gray-900 mb-2">{{ $evaluation->title }}</h1>
                    @if($evaluation->description)
                        <div class="prose prose-sm text-gray-600 max-w-none text-sm leading-relaxed">
                            <p>{{ $evaluation->description }}</p>
                        </div>
                    @endif
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-red-600 font-medium">* Indicates required question</p>
                    </div>
                </div>
            </div>

            {{-- QUESTIONS LOOP --}}
            <div class="space-y-4">
                @foreach($evaluation->questions as $index => $question)
                    
                    {{-- TYPE: SECTION HEADER --}}
                    @if($question->type === 'section')
                        <div class="mt-8 mb-2 bg-orange-600 text-white px-4 py-2 rounded shadow-sm inline-block text-sm font-bold uppercase tracking-wider">
                            {{ $question->question_text }}
                        </div>

                    {{-- TYPE: STANDARD QUESTION --}}
                    @else
                        {{-- 
                             NOTE: "focus-within:border-l-4" creates that 
                             signature blue/orange line on the left when you click inside 
                        --}}
                        <div class="group bg-white rounded-xl p-6 shadow-sm border border-gray-200 transition-all duration-200 focus-within:shadow-md focus-within:border-l-4 focus-within:border-l-orange-600 relative">
                            
                            <label class="block mb-4">
                                <span class="text-base font-medium text-gray-900 block mb-1">
                                    {{ $question->question_text }}
                                    @if($question->is_required)
                                        <span class="text-red-500 ml-0.5">*</span>
                                    @endif
                                </span>
                            </label>

                            {{-- INPUT TYPES --}}
                            <div class="relative">
                                
                                {{-- A. TEXT INPUT --}}
                                @if($question->type === 'text')
                                    <input type="text" wire:model.live="answers.{{ $question->id }}" 
                                        class="w-full md:w-1/2 border-0 border-b border-gray-300 bg-transparent py-2 text-sm focus:border-orange-600 focus:ring-0 transition-colors placeholder-gray-400"
                                        placeholder="Your answer">

                                {{-- B. TEXT AREA --}}
                                @elseif($question->type === 'textarea')
                                    <textarea wire:model.live="answers.{{ $question->id }}" rows="1" 
                                        class="w-full border-0 border-b border-gray-300 bg-transparent py-2 text-sm focus:border-orange-600 focus:ring-0 transition-colors placeholder-gray-400 resize-y min-h-[40px]"
                                        placeholder="Your answer"></textarea>

                                {{-- C. RADIO OPTIONS --}}
                                @elseif($question->type === 'radio')
                                    <div class="space-y-2.5">
                                        @foreach($question->options as $option)
                                            <label class="flex items-center gap-3 cursor-pointer group/option">
                                                <div class="relative flex items-center justify-center">
                                                    <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $option }}" 
                                                           class="peer appearance-none w-5 h-5 rounded-full border-2 border-gray-400 checked:border-orange-600 transition-all">
                                                    <div class="absolute w-2.5 h-2.5 bg-orange-600 rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                                </div>
                                                <span class="text-sm text-gray-700 peer-checked:text-gray-900">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                {{-- D. LIKERT SCALE --}}
                                @elseif($question->type === 'likert')
                                    <div class="mt-2">
                                        <div class="flex justify-between items-end mb-2 px-1">
                                            <span class="text-xs text-gray-500">Strongly Disagree</span>
                                            <span class="text-xs text-gray-500">Strongly Agree</span>
                                        </div>
                                        <div class="flex justify-between items-center bg-gray-50 rounded-lg p-2 md:px-4">
                                            @foreach($question->options as $idx => $label)
                                                <label class="flex flex-col items-center gap-2 cursor-pointer group/likert">
                                                    <span class="text-xs font-bold text-gray-400 group-hover/likert:text-orange-500">{{ $idx + 1 }}</span>
                                                    <div class="relative flex items-center justify-center">
                                                        <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" 
                                                               class="peer appearance-none w-5 h-5 rounded-full border-2 border-gray-300 checked:border-orange-600 transition-all">
                                                        <div class="absolute w-2.5 h-2.5 bg-orange-600 rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Validation Error --}}
                                @error("answers.{$question->id}") 
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-xs font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>This is a required question</span>
                                    </div>
                                @enderror

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- SUBMIT FOOTER --}}
            <div class="mt-6 pb-20 flex justify-between items-center">
                <button wire:click="submit" wire:loading.attr="disabled" class="px-6 py-2.5 bg-orange-600 text-white font-medium text-sm rounded hover:bg-orange-700 shadow hover:shadow-md transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                    <span wire:loading.remove>Submit</span>
                    <span wire:loading>Processing...</span>
                </button>
                
                <button wire:click="answers = []" class="text-xs text-gray-500 hover:text-gray-800 font-medium">
                    Clear form
                </button>
            </div>
            
        </div>
    @endif
</div>