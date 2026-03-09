
@section('meta_title', $evaluation->title)
@section('meta_description', $evaluation->description ? \Illuminate\Support\Str::limit(strip_tags($evaluation->description), 150) : 'Please participate in this evaluation.')
@if($evaluation->header_image)
    @section('meta_image', asset('storage/'.$evaluation->header_image))
@endif

<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-900 selection:bg-orange-100 selection:text-orange-600">

    {{-- STATE: FORM SUBMITTED --}}
    @if($isSubmitted)
        <div class="min-h-screen flex items-center justify-center p-4" x-data x-init="window.scrollTo({top: 0, behavior: 'smooth'})">
            <div class="max-w-md w-full bg-white rounded-2xl p-10 text-center shadow-2xl shadow-green-500/10 border border-green-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-green-400 to-emerald-600"></div>
                <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="text-3xl font-black text-gray-900 mb-2">Thank You!</h1>
                <p class="text-gray-500 mb-8 leading-relaxed text-sm">Your response has been successfully recorded.</p>
                <div class="space-y-3">
                    <a href="{{ route('open.home') }}" class="block w-full py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 transition-transform hover:-translate-y-1 text-xs uppercase tracking-widest">Return to Home</a>
                    @if(Auth::guest())
                        <button wire:click="$set('isSubmitted', false); $set('answers', [])" class="block w-full py-3 bg-white text-gray-500 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition-colors text-xs uppercase tracking-widest">Submit Another Response</button>
                    @endif
                </div>
            </div>
        </div>

    {{-- STATE: FORM CLOSED --}}
    @elseif(!$evaluation->is_active)
        <div class="min-h-screen flex items-center justify-center p-4">
             <div class="max-w-md w-full bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                 <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                 </div>
                 <h1 class="text-2xl font-bold text-gray-900 mb-2">Form Closed</h1>
                 <p class="text-sm text-gray-500 mb-6">This evaluation is no longer accepting responses.</p>
                 <a href="{{ route('open.home') }}" class="text-blue-600 font-bold text-xs uppercase tracking-widest hover:underline">Return Home</a>
             </div>
        </div>

    {{-- STATE: FORM OPEN --}}
    @else

        {{-- STICKY HEADER --}}
        <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
            <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-900 truncate max-w-[200px]">{{ $evaluation->title }}</span>
                <div class="flex items-center gap-3">
                    <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 to-orange-500 transition-all duration-500" style="width: {{ $progress }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500">{{ $currentPage + 1 }} / {{ $totalPages }}</span>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto px-4 mt-6">

            {{-- HERO CARD (Only show on the first page) --}}
            @if($currentPage === 0)
                <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-white overflow-hidden relative mb-8">
                    @if($evaluation->header_image)
                        <div class="h-32 md:h-48 w-full relative">
                            <img src="{{ asset('storage/'.$evaluation->header_image) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        </div>
                    @else
                        <div class="h-32 w-full relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-600 via-orange-500 to-green-600 opacity-90"></div>
                            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                        </div>
                    @endif

                    <div class="p-6 {{ $evaluation->header_image ? '-mt-12 relative z-10' : '' }}">
                        <h1 class="text-2xl font-black {{ $evaluation->header_image ? 'text-white drop-shadow-md' : 'text-gray-900' }} mb-2 leading-tight">{{ $evaluation->title }}</h1>
                        @if($evaluation->description)
                            <div class="prose prose-sm {{ $evaluation->header_image ? 'text-gray-100' : 'text-gray-500' }} max-w-none text-sm whitespace-pre-line">
                                {{ $evaluation->description }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- QUESTIONS LOOP (For Current Page Only) --}}
            <div class="space-y-4">
                @if(isset($pages[$currentPage]))
                    @foreach($pages[$currentPage] as $question)

                        @if($question->type === 'section')
                            {{-- SECTION HEADER --}}
                            <div class="pt-6 pb-2" wire:key="section-{{ $question->id }}">
                                <div class="flex items-center gap-4">
                                    <div class="h-px bg-gray-200 flex-1"></div>
                                    <h2 class="text-sm font-black text-gray-800 uppercase tracking-tight px-2">{{ $question->question_text }}</h2>
                                    <div class="h-px bg-gray-200 flex-1"></div>
                                </div>
                                <div class="w-full h-1 bg-orange-600 rounded-r-full mb-2"></div>
                                @if($question->description)
                                    <p class="text-sm text-gray-600 italic ml-1 max-w-xl whitespace-pre-line">{{ $question->description }}</p>
                                @endif
                            </div>

                        @else
                            {{-- QUESTION CARD --}}
                            <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-orange-100 transition-all duration-300 relative overflow-hidden" wire:key="question-card-{{ $question->id }}">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 opacity-0 group-focus-within:opacity-100 transition-opacity"></div>

                                <label class="block mb-4 relative z-10">
                                    <div class="flex justify-end items-start mb-1">
                                        @if($question->is_required)
                                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded">Required</span>
                                        @endif
                                    </div>
                                    <span class="text-base font-bold text-gray-900 block leading-snug">{{ $question->question_text }}</span>
                                    @if($question->description)
                                        <span class="text-xs text-gray-500 block mt-1 leading-relaxed whitespace-pre-line">{{ $question->description }}</span>
                                    @endif
                                </label>

                                @if($question->image_path)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/'.$question->image_path) }}" class="rounded-lg border border-gray-100 max-h-64 object-contain w-full bg-gray-50">
                                    </div>
                                @endif

                                <div class="relative z-10">
                                    {{-- INPUTS --}}
                                    @if($question->type === 'text')
                                        <input type="text" wire:model.live="answers.{{ $question->id }}" class="w-full border-0 border-b-2 border-gray-200 bg-transparent py-2 text-sm focus:border-orange-500 focus:ring-0 placeholder-gray-300" placeholder="Type your answer...">

                                    @elseif($question->type === 'textarea')
                                        <textarea wire:model.live="answers.{{ $question->id }}" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm" placeholder="Share your thoughts..."></textarea>

                                    {{-- [NEW] DROPDOWN --}}
                                    @elseif($question->type === 'dropdown')
                                        <select wire:model.live="answers.{{ $question->id }}" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm text-gray-700 cursor-pointer">
                                            <option value="">-- Select an option --</option>
                                            @foreach($question->options as $opt)
                                                @php $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt; @endphp
                                                <option value="{{ $optText }}">{{ $optText }}</option>
                                            @endforeach
                                        </select>

                                    @elseif($question->type === 'radio')
                                        <div class="space-y-2">
                                            @foreach($question->options as $optIndex => $option)
                                                @php
                                                    $label = is_array($option) ? ($option['text'] ?? '') : $option;
                                                    $isSelected = isset($answers[$question->id]) && $answers[$question->id] == $label;
                                                @endphp
                                                <label class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all group/option {{ $isSelected ? 'bg-orange-50 border-orange-500' : 'border-gray-200 hover:bg-orange-50 hover:border-orange-200' }}">
                                                    <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only" wire:key="q-{{ $question->id }}-opt-{{ $optIndex }}">
                                                    <div class="w-4 h-4 rounded-full border-2 mr-3 flex items-center justify-center transition-all {{ $isSelected ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                                                        <div class="w-1.5 h-1.5 bg-white rounded-full {{ $isSelected ? 'opacity-100' : 'opacity-0' }}"></div>
                                                    </div>
                                                    <span class="text-sm font-medium {{ $isSelected ? 'text-gray-900 font-bold' : 'text-gray-600' }}">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    @elseif($question->type === 'checkbox')
                                        <div class="space-y-2">
                                            @foreach($question->options as $optIndex => $option)
                                                @php
                                                    $label = is_array($option) ? ($option['text'] ?? '') : $option;
                                                    $currentVal = $answers[$question->id];
                                                    if (!is_array($currentVal)) $currentVal = [];
                                                    $isChecked = in_array($label, $currentVal);
                                                @endphp
                                                <label class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all group/option {{ $isChecked ? 'bg-orange-50 border-orange-500 shadow-sm' : 'border-gray-200 hover:bg-orange-50 hover:border-orange-200' }}">
                                                    <input type="checkbox" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only" wire:key="q-{{ $question->id }}-chk-{{ $optIndex }}">
                                                    <div class="w-5 h-5 rounded border-2 mr-3 flex items-center justify-center transition-all {{ $isChecked ? 'border-orange-500 bg-orange-500' : 'border-gray-300 bg-white' }}">
                                                        <svg class="w-3 h-3 text-white {{ $isChecked ? 'block' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                    <span class="text-sm font-medium {{ $isChecked ? 'text-gray-900 font-bold' : 'text-gray-600' }}">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    @elseif($question->type === 'likert')
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">
                                                <span>{{ $question->options[0] ?? 'Disagree' }}</span>
                                                <span>{{ last($question->options) ?? 'Agree' }}</span>
                                            </div>
                                            <div class="flex justify-between gap-1">
                                                @foreach($question->options as $idx => $label)
                                                    @php $isSelected = isset($answers[$question->id]) && $answers[$question->id] == $label; @endphp
                                                    <label class="cursor-pointer group/likert text-center relative flex-1">
                                                        <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only" wire:key="q-{{ $question->id }}-likert-{{ $idx }}">
                                                        <div class="w-full aspect-square rounded-lg shadow-sm border-2 flex flex-col items-center justify-center gap-1 transition-all duration-200 {{ $isSelected ? 'bg-orange-500 border-orange-500 text-white' : 'bg-white border-transparent text-gray-300 group-hover/likert:border-orange-200' }}">
                                                            <span class="text-sm font-black {{ $isSelected ? 'text-white' : '' }}">{{ $idx + 1 }}</span>
                                                        </div>
                                                        <span class="hidden md:block text-[9px] mt-1 truncate px-1 {{ $isSelected ? 'text-orange-600 font-bold' : 'text-gray-400' }}">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                    @elseif($question->type === 'file')
                                        <div class="mt-2">
                                            <label class="block w-full cursor-pointer bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 hover:bg-orange-50 transition-colors group/file">
                                                <input type="file" wire:model.live="answers.{{ $question->id }}" class="hidden">
                                                <div class="flex flex-col items-center gap-2">
                                                    <svg class="w-8 h-8 text-gray-400 group-hover/file:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                    @if(isset($answers[$question->id]) && $answers[$question->id] !== '')
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
                @endif
            </div>

            {{-- PAGINATION & SUBMIT BUTTONS --}}
            <div class="mt-8 pb-20 flex flex-col sm:flex-row gap-4 justify-between">

                {{-- Back Button --}}
                @if($currentPage > 0)
                    <button wire:click="previousPage" type="button" class="w-full sm:w-auto py-3 px-8 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-all text-xs uppercase tracking-widest text-center">
                        &larr; Back
                    </button>
                @else
                    <div class="hidden sm:block"></div> {{-- Empty space to push 'Next' to the right --}}
                @endif

                {{-- Next / Submit Button --}}
                @if($currentPage < ($totalPages - 1))
                    <button wire:click="nextPage" type="button" class="w-full sm:w-auto py-3 px-10 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 transition-all text-xs uppercase tracking-widest text-center">
                        Next Step &rarr;
                    </button>
                @else
                    <button wire:click="submit" wire:loading.attr="disabled" type="button" class="w-full sm:w-auto py-3 px-10 bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold rounded-xl shadow-lg hover:shadow-orange-500/30 transition-all text-xs uppercase tracking-widest text-center disabled:opacity-50">
                        <span wire:loading.remove wire:target="submit">Submit Evaluation</span>
                        <span wire:loading wire:target="submit">Processing...</span>
                    </button>
                @endif

            </div>

        </div>
    @endif
</div>
