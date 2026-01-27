<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-900">

    {{-- 1. STICKY HEADER (Context) --}}
    <div class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-3xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('evaluations.index') }}" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-red-50 hover:text-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider hidden sm:block">Evaluation Portal</span>
            </div>
            {{-- Simple Progress Bar --}}
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-500">Form Progress</span>
                <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-red-500 to-orange-500 w-1/3"></div> {{-- Dynamic width based on answers --}}
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN FORM CARD --}}
    <div class="max-w-3xl mx-auto px-4 mt-8">
        
        {{-- Header Card --}}
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative mb-8">
            {{-- Top Decorative Gradient --}}
            <div class="h-32 bg-gradient-to-r from-red-600 via-orange-500 to-green-600 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M20 38v-2h2v2h-2zm0-36V0h2v2h-2zM0 20v-2h2v2H0zm38 0v-2h2v2h-2z\'/%3E%3Cpath d=\'M19 19h2v2h-2z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>

            <div class="px-8 pb-8 pt-0 relative">
                {{-- Floating Title Badge --}}
                <div class="absolute -top-10 left-8 w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center border-4 border-white">
                    <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>

                <div class="mt-14 pl-2">
                    <h1 class="text-3xl font-black text-gray-900 mb-2 leading-tight">{{ $evaluation->title }}</h1>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-xl">
                        {{ $evaluation->description }}
                    </p>
                </div>
            </div>
        </div>

        {{-- QUESTIONS LIST --}}
        <div class="space-y-6">
            @foreach($evaluation->questions as $index => $question)
                
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    
                    {{-- Question Label --}}
                    <label class="block mb-6">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 block">Question {{ $index + 1 }}</span>
                        <span class="text-xl font-bold text-gray-900 block leading-tight">
                            {{ $question->question_text }}
                            @if($question->is_required) <span class="text-red-500 text-sm align-top">*</span> @endif
                        </span>
                    </label>

                    {{-- Dynamic Input Rendering --}}
                    
                    {{-- A. TEXT INPUT --}}
                    @if($question->type === 'text')
                        <input type="text" wire:model="answers.{{ $question->id }}" 
                               class="w-full border-0 border-b-2 border-gray-200 bg-transparent py-3 text-lg focus:border-orange-500 focus:ring-0 transition placeholder-gray-300"
                               placeholder="Type your answer...">

                    {{-- B. TEXT AREA --}}
                    @elseif($question->type === 'textarea')
                        <textarea wire:model="answers.{{ $question->id }}" rows="4" 
                                  class="w-full rounded-2xl border-gray-200 bg-gray-50 p-4 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition resize-none text-gray-700"
                                  placeholder="Please share your thoughts..."></textarea>

                    {{-- C. RADIO OPTIONS --}}
                    @elseif($question->type === 'radio')
                        <div class="space-y-3">
                            @foreach($question->options as $option)
                                <label class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 cursor-pointer hover:bg-orange-50 hover:border-orange-200 transition group">
                                    <div class="relative flex items-center">
                                        <input type="radio" wire:model="answers.{{ $question->id }}" value="{{ $option }}" class="peer sr-only">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:bg-orange-500 transition"></div>
                                    </div>
                                    <span class="font-medium text-gray-700 group-hover:text-gray-900">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>

                    {{-- D. LIKERT SCALE --}}
                    @elseif($question->type === 'likert')
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                            <div class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-widest px-2 mb-3">
                                <span>Disagree</span>
                                <span>Agree</span>
                            </div>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($question->options as $idx => $label)
                                    <label class="cursor-pointer group text-center">
                                        <input type="radio" wire:model="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only">
                                        {{-- Number Circle --}}
                                        <div class="w-full aspect-square md:aspect-auto md:h-12 rounded-xl border-2 border-white bg-white shadow-sm flex items-center justify-center font-bold text-gray-400 group-hover:border-orange-200 peer-checked:border-orange-500 peer-checked:bg-orange-500 peer-checked:text-white transition">
                                            {{ $idx + 1 }}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-400 mt-2 block group-hover:text-gray-600 peer-checked:text-orange-600">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @error("answers.{$question->id}") 
                        <p class="mt-3 text-sm font-bold text-red-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            This field is required.
                        </p>
                    @enderror
                </div>
            @endforeach

            {{-- Submit Button --}}
            <div class="pt-8 pb-20">
                <button wire:click="submit" class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl shadow-xl hover:bg-gradient-to-r hover:from-red-600 hover:to-orange-500 hover:-translate-y-1 transition-all duration-300 uppercase tracking-widest text-sm">
                    Submit Evaluation
                </button>
            </div>
        </div>
    </div>
</div>