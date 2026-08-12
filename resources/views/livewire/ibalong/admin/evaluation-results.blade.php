<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- Header Section --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-white relative">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-black uppercase tracking-widest text-white">Telemetry & Results</h1>
                <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-2 py-1 border-2 border-white shadow-[2px_2px_0_0_#FFF]">{{ $evaluation->responses->count() }} Responses</span>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ $evaluation->title }}</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
            {{-- Broadcast Controls (Hidden from Public Users) --}}
            @auth
                @if(auth()->user()->role?->role_name === 'administrator' || $evaluation->created_by === auth()->user()->id)
                    <button wire:click="togglePublicAccess" class="w-full md:w-auto text-[10px] font-black uppercase px-4 py-3 border-2 border-white transition-all shadow-[2px_2px_0_0_#FFF] hover:translate-y-0.5 hover:shadow-none {{ $evaluation->is_public_results ? 'bg-iba-green text-white' : 'bg-transparent text-white hover:bg-white hover:text-iba-black' }}">
                        {{ $evaluation->is_public_results ? 'Public Broadcast Live' : 'Enable Public Access' }}
                    </button>
                @endif
                <a href="{{ route('ibalong.admin.evaluations.index') }}" class="w-full md:w-auto bg-transparent text-white text-center text-[10px] font-black uppercase px-6 py-3 border-2 border-white hover:bg-white hover:text-iba-black transition-colors">
                    &larr; Return
                </a>
            @endauth
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-green uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    @if($evaluation->responses->count() === 0)
        <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center shadow-[6px_6px_0_0_#131011]">
            <p class="text-sm font-black text-gray-500 uppercase tracking-widest">Awaiting Data Transmission. No responses recorded yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8">

            @php $questionCounter = 1; @endphp

            @foreach($evaluation->questions as $question)

                {{-- Dynamic Section Headers --}}
                @if($question->type === 'section')
                    <div class="mt-8 mb-4 border-l-8 border-iba-orange pl-4">
                        <h2 class="text-2xl font-black uppercase text-iba-black tracking-widest">{{ $question->question_text }}</h2>
                        @if($question->help_text)
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">{{ $question->help_text }}</p>
                        @endif
                    </div>
                    @continue
                @endif

                @if($question->type === 'page_break') @continue @endif

                {{-- Data Card --}}
                <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 animate-fade-in-up">
                    <div class="flex items-start justify-between border-b-4 border-iba-black pb-3 mb-6 gap-4">
                        <h3 class="text-lg font-black uppercase text-iba-black leading-tight"><span class="text-iba-orange mr-2">Q{{ $questionCounter }}.</span> {{ $question->question_text }}</h3>
                        <span class="bg-gray-200 border-2 border-iba-black px-2 py-1 text-[9px] font-black uppercase tracking-widest text-iba-black shrink-0">{{ $question->type }}</span>
                    </div>

                    {{-- Data Visualization for Analytics --}}
                    @if(in_array($question->type, ['radio', 'dropdown', 'checkbox', 'likert']))
                        <div class="space-y-4">
                            @php
                                $totalAnswers = $stats[$question->id]['count'] ?? 0;
                                $breakdown = $stats[$question->id]['breakdown'] ?? [];
                            @endphp

                            @if($totalAnswers > 0)
                                @if($question->type === 'likert')
                                    <div class="mb-4 bg-gray-100 border-2 border-dashed border-gray-300 p-3 inline-block">
                                        <span class="text-[10px] font-black uppercase text-gray-500 tracking-widest">Weighted Average:</span>
                                        <span class="text-lg font-black text-iba-teal ml-2">{{ number_format($stats[$question->id]['average'] ?? 0, 2) }}</span>
                                    </div>
                                @endif

                                @foreach($question->options as $index => $option)
                                    @php
                                        $optText = is_array($option) ? ($option['text'] ?? '') : $option;

                                        // Map count correctly whether it's indexed (Likert) or associative (Radio/Checkbox)
                                        $count = $question->type === 'likert' ? ($breakdown[$index] ?? 0) : ($breakdown[$optText] ?? 0);
                                        $percentage = $totalAnswers > 0 ? round(($count / $totalAnswers) * 100) : 0;
                                    @endphp
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 group">
                                        <div class="w-full sm:w-1/3 text-xs font-bold uppercase text-gray-700 truncate group-hover:text-iba-black transition-colors" title="{{ $optText }}">{{ $optText }}</div>
                                        <div class="w-full sm:w-2/3 flex items-center gap-3">
                                            <div class="flex-1 h-8 bg-gray-100 border-2 border-iba-black relative overflow-hidden">
                                                <div class="absolute top-0 left-0 h-full bg-iba-teal border-r-2 border-iba-black transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <div class="w-16 text-right text-xs font-black text-iba-black">{{ $count }} <span class="text-[9px] text-gray-400">({{ $percentage }}%)</span></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs font-bold text-gray-400 italic uppercase">No analytical data available.</p>
                            @endif
                        </div>

                    {{-- Data Ledger for Text & Qualitative Inputs --}}
                    @else
                        <div class="bg-gray-50 border-2 border-iba-black max-h-[400px] overflow-y-auto divide-y-2 divide-dashed divide-gray-300 shadow-inner">
                            @php
                                // Fetch raw textual answers for this specific question
                                $textAnswers = $evaluation->responses->flatMap->answers->where('evaluation_question_id', $question->id)->filter(function($ans) {
                                    return !empty($ans->answer_value);
                                });
                            @endphp

                            @forelse($textAnswers as $answer)
                                <div class="p-4 hover:bg-white transition-colors">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black uppercase text-iba-orange">Response Log #{{ $answer->evaluation_response_id }}</span>
                                        <span class="text-[9px] font-bold text-gray-400">{{ $answer->created_at->format('M d, h:i A') }}</span>
                                    </div>

                                    @if($question->type === 'file')
                                        <a href="{{ Storage::url($answer->answer_value) }}" target="_blank" class="inline-flex items-center gap-2 bg-iba-black text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 border-iba-black hover:bg-gray-800 transition-colors shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Access Target File
                                        </a>
                                    @else
                                        <p class="text-sm font-bold text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $answer->answer_value }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <p class="text-xs font-bold text-gray-400 italic uppercase">No qualitative responses recorded.</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>

                @php $questionCounter++; @endphp
            @endforeach
        </div>
    @endif
</div>
+; @endphp
            @endforeach
        </div>
    @endif
</div>