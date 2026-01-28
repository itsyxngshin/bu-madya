<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-900 selection:bg-orange-100 selection:text-orange-600">

    @if(!$evaluation->is_active)
        {{-- CLOSED STATE CODE --}}
        <div class="min-h-screen flex items-center justify-center p-4">
             <div class="max-w-md w-full bg-white rounded-2xl p-8 text-center shadow-lg">
                 <h1 class="text-2xl font-bold">Form Closed</h1>
                 <a href="{{ route('open.home') }}" class="text-blue-600 underline text-sm mt-4 inline-block">Return Home</a>
             </div>
        </div>
    @else
        
        {{-- STICKY HEADER --}}
        <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
            <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-900 truncate max-w-[200px]">{{ $evaluation->title }}</span>
                <div class="flex items-center gap-3">
                    <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 to-orange-500 transition-all duration-500" style="width: {{ $this->progress }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500">{{ $this->progress }}%</span>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto px-4 mt-6">
            
            {{-- HERO CARD --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-white overflow-hidden relative mb-8">
                @if($evaluation->header_image)
                    <div class="h-32 md:h-48 w-full relative">
                        <img src="{{ asset('storage/'.$evaluation->header_image) }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                @else
                    <div class="h-24 bg-gradient-to-r from-gray-900 to-gray-800 relative">
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#ffffff33_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    </div>
                @endif

                <div class="p-6 {{ $evaluation->header_image ? '-mt-12 relative z-10' : '' }}">
                    <h1 class="text-2xl font-black {{ $evaluation->header_image ? 'text-black drop-shadow-md' : 'text-gray-900' }} mb-2 leading-tight">{{ $evaluation->title }}</h1>
                    @if($evaluation->description)
                        <div class="prose prose-sm {{ $evaluation->header_image ? 'text-gray-900' : 'text-gray-500' }} max-w-none text-sm">
                            <p>{{ $evaluation->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- QUESTIONS LOOP --}}
            <div class="space-y-4">
                @foreach($evaluation->questions as $index => $question)
                    
                    {{-- SECTION HEADER --}}
                    @if($question->type === 'section')
                        <div class="pt-6 pb-2">
                            <div class="flex items-center gap-4">
                                <div class="h-px bg-gray-200 flex-1"></div>
                                <h2 class="text-sm font-black text-gray-800 uppercase tracking-tight px-2">{{ $question->question_text }}</h2>
                                <div class="h-px bg-gray-200 flex-1"></div>
                            </div>
                            <div class="w-full h-1 bg-orange-600 rounded-r-full mb-2"></div>
                            @if($question->description)
                                <p class="text-sm text-gray-600 italic ml-1 max-w-xl">{{ $question->description }}</p>
                            @endif
                        </div>

                    {{-- STANDARD QUESTION --}}
                    @else
                        <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-orange-100 transition-all duration-300 relative overflow-hidden" wire:key="question-card-{{ $question->id }}">
                            
                            {{-- Focus Line --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 opacity-0 group-focus-within:opacity-100 transition-opacity"></div>

                            <label class="block mb-4 relative z-10">
                                <div class="flex justify-end items-start mb-1">
                                    @if($question->is_required) 
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded">Required</span> 
                                    @endif
                                </div>
                                <span class="text-base font-bold text-gray-900 block leading-snug">{{ $question->question_text }}</span>
                                @if($question->description)
                                    <span class="text-xs text-gray-500 block mt-1 leading-relaxed">{{ $question->description }}</span>
                                @endif
                            </label>

                            {{-- QUESTION IMAGE --}}
                            @if($question->image_path)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/'.$question->image_path) }}" class="rounded-lg border border-gray-100 max-h-64 object-contain w-full bg-gray-50">
                                </div>
                            @endif

                            <div class="relative z-10">
                                
                                {{-- 1. TEXT --}}
                                @if($question->type === 'text')
                                    <input type="text" wire:model.live="answers.{{ $question->id }}" class="w-full border-0 border-b-2 border-gray-200 bg-transparent py-2 text-sm focus:border-orange-500 focus:ring-0 placeholder-gray-300" placeholder="Type your answer...">
                                
                                {{-- 2. TEXTAREA --}}
                                @elseif($question->type === 'textarea')
                                    <textarea wire:model.live="answers.{{ $question->id }}" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm" placeholder="Share your thoughts..."></textarea>
                                
                                {{-- 3. RADIO (Fixed Highlight) --}}
                                @elseif($question->type === 'radio')
                                    <div class="space-y-2">
                                        @foreach($question->options as $optIndex => $option)
                                            @php
                                                // Check if this specific option is selected
                                                $isSelected = isset($answers[$question->id]) && $answers[$question->id] == $option;
                                            @endphp
                                            <label class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all group/option 
                                                {{ $isSelected ? 'bg-orange-50 border-orange-500' : 'border-gray-200 hover:bg-orange-50 hover:border-orange-200' }}">
                                                
                                                <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $option }}" class="peer sr-only" wire:key="q-{{ $question->id }}-opt-{{ $optIndex }}">
                                                
                                                {{-- Circle Indicator --}}
                                                <div class="w-4 h-4 rounded-full border-2 mr-3 flex items-center justify-center transition-all
                                                    {{ $isSelected ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                                                    <div class="w-1.5 h-1.5 bg-white rounded-full {{ $isSelected ? 'opacity-100' : 'opacity-0' }}"></div>
                                                </div>
                                                
                                                {{-- Label Text --}}
                                                <span class="text-sm font-medium {{ $isSelected ? 'text-gray-900 font-bold' : 'text-gray-600' }}">
                                                    {{ $option }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                
                                {{-- 4. LIKERT SCALE (Fixed Highlight) --}}
                                @elseif($question->type === 'likert')
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">
                                            <span>{{ $question->options[0] ?? 'Disagree' }}</span>
                                            <span>{{ last($question->options) ?? 'Agree' }}</span>
                                        </div>
                                        <div class="flex justify-between gap-1">
                                            @foreach($question->options as $idx => $label)
                                                @php
                                                    $isSelected = isset($answers[$question->id]) && $answers[$question->id] == $label;
                                                @endphp
                                                <label class="cursor-pointer group/likert text-center relative flex-1">
                                                    <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only" wire:key="q-{{ $question->id }}-likert-{{ $idx }}">
                                                    
                                                    {{-- Tile --}}
                                                    <div class="w-full aspect-square rounded-lg shadow-sm border-2 flex flex-col items-center justify-center gap-1 transition-all duration-200
                                                        {{ $isSelected 
                                                            ? 'bg-orange-500 border-orange-500 text-white' 
                                                            : 'bg-white border-transparent text-gray-300 group-hover/likert:border-orange-200' 
                                                        }}">
                                                        <span class="text-sm font-black {{ $isSelected ? 'text-white' : '' }}">{{ $idx + 1 }}</span>
                                                    </div>
                                                    
                                                    {{-- Mobile Label --}}
                                                    <span class="hidden md:block text-[9px] mt-1 truncate px-1 {{ $isSelected ? 'text-orange-600 font-bold' : 'text-gray-400' }}">
                                                        {{ $label }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                {{-- 5. FILE UPLOAD --}}
                                @elseif($question->type === 'file')
                                    <div class="mt-2">
                                        <label class="block w-full cursor-pointer bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 hover:bg-orange-50 transition-colors group/file">
                                            <input type="file" wire:model.live="answers.{{ $question->id }}" class="hidden">
                                            <div class="flex flex-col items-center gap-2">
                                                <svg class="w-8 h-8 text-gray-400 group-hover/file:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                @if(isset($answers[$question->id]))
                                                    <span class="text-sm font-bold text-orange-600">File Selected</span>
                                                @else
                                                    <span class="text-sm font-bold text-gray-500">Click to upload file</span>
                                                @endif
                                            </div>
                                        </label>
                                        <div wire:loading wire:target="answers.{{ $question->id }}" class="text-xs text-orange-500 font-bold mt-2 text-center">Uploading...</div>
                                    </div>

                                @endif

                                @error("answers.{$question->id}") <div class="mt-2 text-red-500 text-xs font-bold">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-8 pb-20">
                <button wire:click="submit" wire:loading.attr="disabled" class="w-full py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gradient-to-r hover:from-red-600 hover:to-orange-500 transition-all text-xs uppercase tracking-widest disabled:opacity-50">
                    <span wire:loading.remove>Submit Evaluation</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
            
        </div>
    @endif
</div>