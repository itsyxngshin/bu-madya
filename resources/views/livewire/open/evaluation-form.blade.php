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
            
            {{-- HERO CARD WITH HEADER IMAGE --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-white overflow-hidden relative mb-8">
                
                {{-- HEADER IMAGE LOGIC --}}
                @if($evaluation->header_image)
                    <div class="h-32 md:h-48 w-full relative">
                        <img src="{{ asset('storage/'.$evaluation->header_image) }}" class="w-full h-full object-cover">
                        {{-- Overlay for text legibility if needed --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                @else
                    {{-- Default Gradient --}}
                    <div class="h-24 bg-gradient-to-r from-gray-900 to-gray-800 relative">
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#ffffff33_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    </div>
                @endif

                <div class="p-6 {{ $evaluation->header_image ? '-mt-12 relative z-10' : '' }}">
                    <h1 class="text-2xl font-black {{ $evaluation->header_image ? 'text-white drop-shadow-md' : 'text-gray-900' }} mb-2 leading-tight">{{ $evaluation->title }}</h1>
                    @if($evaluation->description)
                        <div class="prose prose-sm {{ $evaluation->header_image ? 'text-gray-100' : 'text-gray-500' }} max-w-none text-sm">
                            <p>{{ $evaluation->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- QUESTIONS LOOP --}}
            <div class="space-y-4">
                @foreach($evaluation->questions as $index => $question)
                    
                    @if($question->type === 'section')
                        <div class="pt-6 pb-2">
                            <div class="flex items-center gap-4">
                                <div class="h-px bg-gray-200 flex-1"></div>
                                <h2 class="text-sm font-black text-gray-800 uppercase tracking-tight px-2">{{ $question->question_text }}</h2>
                                <div class="h-px bg-gray-200 flex-1"></div>
                            </div>
                        </div>
                    @else
                        <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-orange-100 transition-all duration-300 relative overflow-hidden">
                            
                            {{-- Focus Line --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 opacity-0 group-focus-within:opacity-100 transition-opacity"></div>

                            <label class="block mb-4 relative z-10">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded">Q{{ $index + 1 }}</span>
                                    @if($question->is_required) <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded">Required</span> @endif
                                </div>
                                <span class="text-base font-bold text-gray-900 block leading-snug">{{ $question->question_text }}</span>
                            </label>

                            {{-- QUESTION IMAGE DISPLAY --}}
                            @if($question->image_path)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/'.$question->image_path) }}" class="rounded-lg border border-gray-100 max-h-64 object-contain w-full bg-gray-50">
                                </div>
                            @endif

                            {{-- INPUT TYPES --}}
                            <div class="relative z-10">
                                
                                @if($question->type === 'text')
                                    <input type="text" wire:model.live="answers.{{ $question->id }}" class="w-full border-0 border-b-2 border-gray-200 bg-transparent py-2 text-sm focus:border-orange-500 focus:ring-0 placeholder-gray-300" placeholder="Type your answer...">
                                
                                @elseif($question->type === 'textarea')
                                    <textarea wire:model.live="answers.{{ $question->id }}" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm" placeholder="Share your thoughts..."></textarea>
                                
                                @elseif($question->type === 'radio')
                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <label class="relative flex items-center p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-orange-50 hover:border-orange-200 transition-all group/option">
                                                <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $option }}" class="peer sr-only">
                                                <div class="w-4 h-4 rounded-full border-2 border-gray-300 mr-3 flex items-center justify-center peer-checked:border-orange-500 peer-checked:bg-orange-500"><div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div></div>
                                                <span class="text-sm font-medium text-gray-600 peer-checked:text-gray-900 peer-checked:font-bold">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                
                                {{-- NEW FILE UPLOAD BIN --}}
                                @elseif($question->type === 'file')
                                    <div class="mt-2">
                                        <label class="block w-full cursor-pointer bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 hover:bg-orange-50 transition-colors group/file">
                                            <input type="file" wire:model.live="answers.{{ $question->id }}" class="hidden">
                                            
                                            <div class="flex flex-col items-center gap-2">
                                                <svg class="w-8 h-8 text-gray-400 group-hover/file:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                
                                                @if(isset($answers[$question->id]))
                                                    <span class="text-sm font-bold text-orange-600">File Selected (Ready to submit)</span>
                                                @else
                                                    <span class="text-sm font-bold text-gray-500 group-hover/file:text-gray-700">Click to upload file</span>
                                                    <span class="text-[10px] text-gray-400">Supports PDF, DOC, ZIP, Images</span>
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