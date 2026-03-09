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
                    {{-- [NEW] Autosave Indicator (Controlled by Alpine & Livewire Event) --}}

                    <div x-data="{ saved: false }"
                         @draft-autosaved.window="saved = true; setTimeout(() => saved = false, 2500)"
                         class="flex items-center">
                        <span x-show="saved"
                              x-transition.opacity.duration.300ms
                              class="text-[9px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full flex items-center gap-1 uppercase tracking-widest shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Saved
                        </span>
                    </div>

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
                        <h1 class="text-2xl font-black {{ $evaluation->header_image ? 'text-gray drop-shadow-md' : 'text-gray-900' }} mb-2 leading-tight">{{ $evaluation->title }}</h1>
                        @if($evaluation->description)
                            <div class="prose prose-sm {{ $evaluation->header_image ? 'prose-invert text-gray-800' : 'text-gray-500' }} max-w-none text-sm">
                                {!! \Illuminate\Support\Str::markdown($evaluation->description ?? '') !!}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- [MOVED] OPT-IN / ACCOUNT LINKING (Now on Page 1) --}}
                <div class="mb-8 bg-gray-900 rounded-2xl p-6 md:p-8 shadow-xl relative overflow-hidden border border-gray-800">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-red-600/20 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="flex-1">
                            <h4 class="text-white font-bold text-lg mb-1 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Privacy & Account Linking
                            </h4>
                            @if(Auth::check())
                                <p class="text-gray-400 text-sm leading-relaxed max-w-lg">
                                    Would you like to attach this evaluation to your BU MADYA profile <strong>({{ Auth::user()->name }})</strong>? If left unchecked, your response will be completely anonymous.
                                </p>
                            @else
                                <p class="text-gray-400 text-sm leading-relaxed max-w-lg">
                                    You are currently submitting this form anonymously. To link this response to your member profile, please log in first.
                                </p>
                            @endif
                        </div>
                        <div class="shrink-0 w-full md:w-auto">
                            @if(Auth::check())
                                <label class="relative flex items-center justify-between md:justify-start cursor-pointer bg-gray-800 p-2 md:pr-4 rounded-xl border border-gray-700 hover:border-gray-600 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="relative inline-flex items-center">
                                            <input type="checkbox" wire:model="connect_account" class="sr-only peer">
                                            <div class="w-12 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:bg-red-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                        </div>
                                        <span class="text-xs font-bold uppercase tracking-widest transition-colors duration-300 {{ $connect_account ? 'text-red-400' : 'text-gray-400' }}">
                                            {{ $connect_account ? 'Linked' : 'Anonymous' }}
                                        </span>
                                    </div>
                                </label>
                            @else
                                <a href="{{ route('login') }}" class="block text-center px-6 py-3 bg-white text-gray-900 font-bold rounded-xl text-xs uppercase tracking-widest hover:bg-gray-100 hover:scale-105 transition shadow-lg">
                                    Log In to Link
                                </a>
                            @endif
                        </div>
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
                                    <div class="text-sm font-black text-gray-800 uppercase tracking-tight px-2 prose prose-sm max-w-none prose-p:my-0 prose-a:text-[var(--theme)] hover:prose-a:opacity-80">
                                        {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                    </div>
                                    <div class="h-px bg-gray-200 flex-1"></div>
                                </div>
                                <div class="w-full h-1 bg-[var(--theme)] rounded-r-full mb-2"></div>
                                @if($question->description)
                                    <div class="text-sm text-gray-500 ml-1 max-w-xl prose prose-sm max-w-none prose-p:my-1 prose-a:text-[var(--theme)] hover:prose-a:opacity-80">
                                        {!! \Illuminate\Support\Str::markdown($question->description ?? '') !!}
                                    </div>
                                @endif
                            </div>

                        @else
                            {{-- QUESTION CARD --}}
                            <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-[var(--theme-light)] transition-all duration-300 relative" wire:key="question-card-{{ $question->id }}" style="z-index: {{ 100 - $loop->index }}">
                                {{-- Theme Active Stripe --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[var(--theme)] opacity-0 group-focus-within:opacity-100 transition-opacity rounded-l-2xl"></div>

                                <label class="block mb-4 relative z-10">
                                    <div class="flex justify-end items-start mb-1">
                                        @if($question->is_required)
                                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded">Required</span>
                                        @endif
                                    </div>

                                    {{-- Markdown Question Text --}}
                                    <div class="text-base font-bold text-gray-900 block leading-snug prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-[var(--theme)] hover:prose-a:opacity-80">
                                        {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                    </div>

                                    {{-- Markdown Description --}}
                                    @if($question->description)
                                        <div class="text-xs text-gray-500 block mt-1 leading-relaxed prose prose-sm max-w-none prose-p:my-1 prose-a:text-[var(--theme)] hover:prose-a:opacity-80">
                                            {!! \Illuminate\Support\Str::markdown($question->description ?? '') !!}
                                        </div>
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
                                        <input type="text" wire:model.live="answers.{{ $question->id }}" class="w-full border-0 border-b-2 border-gray-200 bg-transparent py-2 text-sm focus:border-[var(--theme)] focus:ring-0 placeholder-gray-300 transition-colors" placeholder="Type your answer...">

                                    @elseif($question->type === 'textarea')
                                        <textarea wire:model.live="answers.{{ $question->id }}" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 focus:bg-white focus:border-[var(--theme)] focus:ring-4 focus:ring-[var(--theme-light)] text-sm transition-colors" placeholder="Share your thoughts..."></textarea>

                                    @elseif($question->type === 'dropdown')
                                        <div x-data="{
                                                open: false,
                                                selected: @entangle('answers.' . $question->id).live
                                             }"
                                             class="relative z-20">

                                            {{-- Trigger Button --}}
                                            <button @click="open = !open" @click.outside="open = false" type="button"
                                                class="w-full text-left rounded-xl border border-gray-200 bg-gray-50 p-3 focus:outline-none focus:bg-white focus:border-[var(--theme)] focus:ring-4 focus:ring-[var(--theme-light)] text-sm transition-all flex justify-between items-center shadow-sm"
                                                :class="open ? 'border-[var(--theme)] bg-white ring-4 ring-[var(--theme-light)] shadow-md' : 'hover:border-[var(--theme)]'">

                                                <span x-text="selected ? selected : '-- Select an option --'"
                                                      :class="selected ? 'text-gray-900 font-bold' : 'text-gray-500'">
                                                </span>

                                                {{-- Animated Chevron --}}
                                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180 text-[var(--theme)]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>

                                            {{-- Dropdown Menu --}}
                                            <div x-show="open"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto"
                                                 style="display: none;">

                                                <div class="p-1 space-y-0.5">
                                                    <button @click="selected = ''; open = false" type="button" class="w-full text-left px-3 py-2.5 text-sm text-gray-500 hover:bg-gray-50 rounded-lg transition-colors flex items-center gap-2">
                                                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        Clear selection
                                                    </button>
                                                    <div class="h-px bg-gray-100 mx-2 my-1"></div>

                                                    @foreach($question->options as $opt)
                                                        @php $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt; @endphp
                                                        <button @click="selected = '{{ addslashes($optText) }}'; open = false"
                                                                type="button"
                                                                class="w-full text-left px-3 py-2.5 text-sm rounded-lg transition-all flex items-center justify-between group"
                                                                :class="selected === '{{ addslashes($optText) }}' ? 'bg-[var(--theme-light)] text-[var(--theme)] font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                                                            <span>{{ $optText }}</span>
                                                            <svg x-show="selected === '{{ addslashes($optText) }}'" class="w-4 h-4 text-[var(--theme)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($question->type === 'radio')
                                        <div class="space-y-2">
                                            @foreach($question->options as $optIndex => $option)
                                                @php
                                                    $label = is_array($option) ? ($option['text'] ?? '') : $option;
                                                    $isSelected = isset($answers[$question->id]) && $answers[$question->id] == $label;
                                                @endphp
                                                <label class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all group/option {{ $isSelected ? 'bg-[var(--theme-light)] border-[var(--theme)]' : 'border-gray-200 hover:bg-[var(--theme-light)] hover:border-[var(--theme-light)]' }}">
                                                    <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only" wire:key="q-{{ $question->id }}-opt-{{ $optIndex }}">
                                                    <div class="w-4 h-4 rounded-full border-2 mr-3 flex items-center justify-center transition-all {{ $isSelected ? 'border-[var(--theme)] bg-[var(--theme)]' : 'border-gray-300' }}">
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
                                                <label class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all group/option {{ $isChecked ? 'bg-[var(--theme-light)] border-[var(--theme)] shadow-sm' : 'border-gray-200 hover:bg-[var(--theme-light)] hover:border-[var(--theme-light)]' }}">
                                                    <input type="checkbox" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only" wire:key="q-{{ $question->id }}-chk-{{ $optIndex }}">
                                                    <div class="w-5 h-5 rounded border-2 mr-3 flex items-center justify-center transition-all {{ $isChecked ? 'border-[var(--theme)] bg-[var(--theme)]' : 'border-gray-300 bg-white' }}">
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
                                                        <div class="w-full aspect-square rounded-lg shadow-sm border-2 flex flex-col items-center justify-center gap-1 transition-all duration-200 {{ $isSelected ? 'bg-[var(--theme)] border-[var(--theme)] text-white' : 'bg-white border-transparent text-gray-300 group-hover/likert:border-[var(--theme-light)]' }}">
                                                            <span class="text-sm font-black {{ $isSelected ? 'text-white' : '' }}">{{ $idx + 1 }}</span>
                                                        </div>
                                                        <span class="hidden md:block text-[9px] mt-1 truncate px-1 {{ $isSelected ? 'text-[var(--theme)] font-bold' : 'text-gray-400' }}">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                    @elseif($question->type === 'file')
                                        <div class="mt-2">
                                            <label class="block w-full cursor-pointer bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[var(--theme)] hover:bg-[var(--theme-light)] transition-colors group/file">
                                                <input type="file" wire:model.live="answers.{{ $question->id }}" class="hidden">
                                                <div class="flex flex-col items-center gap-2">
                                                    <svg class="w-8 h-8 text-gray-400 group-hover/file:text-[var(--theme)] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                    @if(isset($answers[$question->id]) && $answers[$question->id] !== '')
                                                        <span class="text-sm font-bold text-[var(--theme)]">File Selected</span>
                                                    @else
                                                        <span class="text-sm font-bold text-gray-500">Click to upload file</span>
                                                    @endif
                                                </div>
                                            </label>
                                            <div wire:loading wire:target="answers.{{ $question->id }}" class="text-xs text-[var(--theme)] font-bold mt-2 text-center">Uploading...</div>
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
                    <div class="hidden sm:block"></div>
                @endif

                {{-- Next / Submit Button --}}
                @if($currentPage < ($totalPages - 1))
                    <button wire:click="nextPage" type="button" class="w-full sm:w-auto py-3 px-10 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 transition-all text-xs uppercase tracking-widest text-center">
                        Next Step &rarr;
                    </button>
                @else
                    <button wire:click="submit" wire:loading.attr="disabled" type="button" class="w-full sm:w-auto py-3 px-10 bg-[var(--theme)] text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition-all text-xs uppercase tracking-widest text-center disabled:opacity-50">
                        <span wire:loading.remove wire:target="submit">Submit Evaluation</span>
                        <span wire:loading wire:target="submit">Processing...</span>
                    </button>
                @endif

            </div>

        </div>
    @endif
</div>
