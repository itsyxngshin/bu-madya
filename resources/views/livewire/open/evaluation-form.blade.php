<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-900 selection:bg-orange-100 selection:text-orange-600">

    {{-- STATE: FORM CLOSED --}}
    @if(!$evaluation->is_active)
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="max-w-md w-full bg-white rounded-2xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h1 class="text-2xl font-black text-gray-900 mb-2">Form Closed</h1>
                <p class="text-sm text-gray-500 mb-6 leading-relaxed">This evaluation form is no longer accepting responses. Thank you for your interest.</p>
                <a href="{{ route('open.home') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-orange-600 hover:-translate-y-1 transition-all duration-300 text-xs uppercase tracking-widest w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Return Home
                </a>
            </div>
        </div>
    
    {{-- STATE: FORM OPEN --}}
    @else
        
        {{-- 1. COMPACT STICKY HEADER --}}
        <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
            <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('open.home') }}" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-900 hover:text-white transition-colors duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div class="hidden sm:block">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Evaluation</span>
                        <span class="text-xs font-bold text-gray-900 truncate max-w-[200px]">{{ $evaluation->title }}</span>
                    </div>
                </div>

                {{-- Compact Progress Bar --}}
                <div class="flex items-center gap-3">
                    <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $this->progress == 100 ? 'bg-green-500' : 'bg-gradient-to-r from-red-500 to-orange-500' }} transition-all duration-500 ease-out" 
                             style="width: {{ $this->progress }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold {{ $this->progress == 100 ? 'text-green-600' : 'text-gray-400' }}">{{ $this->progress }}%</span>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA (Constrained Width) --}}
        <div class="max-w-2xl mx-auto px-4 mt-6">
            
            {{-- Compact Hero Card --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-white overflow-hidden relative mb-8">
                {{-- Gradient Header --}}
                <div class="h-24 bg-gradient-to-r from-gray-900 to-gray-800 relative">
                    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#ffffff33_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    {{-- Floating Icon --}}
                    <div class="absolute -bottom-8 left-6 w-16 h-16 bg-white rounded-xl shadow-lg flex items-center justify-center border-4 border-white z-10">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                <div class="pt-10 pb-6 px-6">
                    <h1 class="text-2xl font-black text-gray-900 mb-2 leading-tight tracking-tight">{{ $evaluation->title }}</h1>
                    @if($evaluation->description)
                        <div class="prose prose-sm text-gray-500 max-w-none text-sm">
                            <p>{{ $evaluation->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Questions Loop --}}
            <div class="space-y-4">
                @foreach($evaluation->questions as $index => $question)
                    
                    {{-- TYPE: SECTION HEADER --}}
                    @if($question->type === 'section')
                        <div class="pt-6 pb-2">
                            <div class="flex items-center gap-4">
                                <div class="h-px bg-gray-200 flex-1"></div>
                                <h2 class="text-sm font-black text-gray-800 uppercase tracking-tight px-2">
                                    {{ $question->question_text }}
                                </h2>
                                <div class="h-px bg-gray-200 flex-1"></div>
                            </div>
                        </div>

                    {{-- TYPE: STANDARD QUESTION --}}
                    @else
                        <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-orange-100 transition-all duration-300 focus-within:ring-2 focus-within:ring-orange-500/10 focus-within:border-orange-500 relative overflow-hidden">
                            
                            {{-- Focus Indicator Bar (Left Side) --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 opacity-0 group-focus-within:opacity-100 transition-opacity"></div>

                            <label class="block mb-4 relative z-10">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded">
                                        Q{{ $index + 1 }}
                                    </span>
                                    @if($question->is_required)
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded">Required</span>
                                    @endif
                                </div>
                                <span class="text-base font-bold text-gray-900 block leading-snug">
                                    {{ $question->question_text }}
                                </span>
                            </label>

                            {{-- INPUT TYPES --}}
                            <div class="relative z-10">
                                
                                {{-- A. TEXT INPUT --}}
                                @if($question->type === 'text')
                                    <input type="text" wire:model.live="answers.{{ $question->id }}" 
                                        class="w-full border-0 border-b-2 border-gray-200 bg-transparent py-2 text-sm focus:border-orange-500 focus:ring-0 transition-colors placeholder-gray-300"
                                        placeholder="Type your answer here...">

                                {{-- B. TEXT AREA --}}
                                @elseif($question->type === 'textarea')
                                    <textarea wire:model.live="answers.{{ $question->id }}" rows="2" 
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all resize-none text-gray-700 text-sm"
                                        placeholder="Share your thoughts..."></textarea>

                                {{-- C. RADIO OPTIONS --}}
                                @elseif($question->type === 'radio')
                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <label class="relative flex items-center p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-orange-50 hover:border-orange-200 transition-all group/option">
                                                <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $option }}" class="peer sr-only">
                                                
                                                {{-- Custom Check Circle --}}
                                                <div class="w-4 h-4 rounded-full border-2 border-gray-300 mr-3 flex items-center justify-center peer-checked:border-orange-500 peer-checked:bg-orange-500 transition-all">
                                                    <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                                </div>
                                                
                                                <span class="text-sm font-medium text-gray-600 peer-checked:text-gray-900 peer-checked:font-bold transition-colors">{{ $option }}</span>
                                                
                                                {{-- Active Border Highlight --}}
                                                <div class="absolute inset-0 rounded-xl border-2 border-orange-500 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                            </label>
                                        @endforeach
                                    </div>

                                {{-- D. LIKERT SCALE --}}
                                @elseif($question->type === 'likert')
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">
                                            <span>Disagree</span>
                                            <span>Agree</span>
                                        </div>
                                        <div class="flex justify-between gap-1">
                                            @foreach($question->options as $idx => $label)
                                                <label class="cursor-pointer group/likert text-center relative flex-1">
                                                    <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only">
                                                    
                                                    {{-- Tile --}}
                                                    <div class="w-full aspect-square rounded-lg bg-white shadow-sm border-2 border-transparent flex flex-col items-center justify-center gap-1 group-hover/likert:border-orange-200 peer-checked:border-orange-500 peer-checked:bg-orange-500 peer-checked:text-white transition-all duration-200">
                                                        <span class="text-sm font-black text-gray-300 group-hover/likert:text-orange-300 peer-checked:text-white/90">{{ $idx + 1 }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Validation Error --}}
                                @error("answers.{$question->id}") 
                                    <div class="mt-2 flex items-center gap-1 text-red-500 text-xs font-bold animate-pulse">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <span>Required</span>
                                    </div>
                                @enderror

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- SUBMIT FOOTER --}}
            <div class="mt-8 pb-20">
                <button wire:click="submit" wire:loading.attr="disabled" class="w-full group relative py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gradient-to-r hover:from-red-600 hover:to-orange-500 hover:-translate-y-1 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed overflow-hidden">
                    
                    {{-- Normal State --}}
                    <span wire:loading.remove class="flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                        Submit Evaluation
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>

                    {{-- Loading State --}}
                    <span wire:loading class="flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Processing...
                    </span>
                </button>
                <div class="flex justify-between items-center mt-4 px-1">
                     <p class="text-[10px] text-gray-400">Secure & Anonymous</p>
                     <button wire:click="$set('answers', [])" class="text-[10px] text-gray-400 hover:text-red-500 transition">Clear Form</button>
                </div>
            </div>
            
        </div>
    @endif
</div>