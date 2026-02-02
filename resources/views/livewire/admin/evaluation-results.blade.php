<div class="min-h-screen bg-gray-50 p-6 font-sans text-gray-900">
    
    <div class="max-w-5xl mx-auto">
        
        {{-- HEADER --}}
        <div class="mb-8">
            <a href="{{ route('admin.evaluations.index') }}" class="flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-orange-600 mb-2 uppercase tracking-widest transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
            <h1 class="text-3xl font-black text-gray-900">{{ $evaluation->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Analysis of <strong class="text-gray-900">{{ $evaluation->responses()->count() }}</strong> total responses.
            </p>
        </div>

        {{-- QUESTIONS LOOP --}}
        <div class="space-y-6">
            @foreach($evaluation->questions as $index => $question)
                
                @php $stat = $stats[$question->id] ?? null; @endphp

                {{-- Skip Sections --}}
                @if($question->type === 'section')
                    <div class="pt-8 pb-2 border-b-2 border-orange-100">
                        <h2 class="text-lg font-black text-orange-600 uppercase tracking-tight">{{ $question->question_text }}</h2>
                    </div>
                
                {{-- LIKERT SCALE RESULT --}}
                @elseif($question->type === 'likert')
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="flex flex-col md:flex-row gap-8">
                            
                            {{-- Left: The Score --}}
                            <div class="md:w-1/3 flex flex-col justify-center items-center bg-gray-50 rounded-xl p-6 border border-gray-100 text-center">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Average Rating</span>
                                <div class="text-5xl font-black text-gray-900 mb-1">{{ $stat['average'] ?? '0.0' }}</div>
                                <div class="flex gap-1 text-orange-400 mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($stat['average']) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $stat['count'] }} Responses</span>
                            </div>

                            {{-- Right: The Breakdown --}}
                            <div class="md:w-2/3">
                                <h3 class="font-bold text-gray-900 mb-4">{{ $question->question_text }}</h3>
                                <div class="space-y-3">
                                    @foreach(array_reverse($question->options, true) as $optIndex => $label)
                                        @php 
                                            $count = $stat['breakdown'][$optIndex] ?? 0;
                                            $percent = $stat['count'] > 0 ? ($count / $stat['count']) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center gap-3 text-sm">
                                            <span class="w-8 font-bold text-gray-400 text-right">{{ $optIndex + 1 }}</span>
                                            <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-orange-500 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="w-12 text-right font-bold text-gray-700">{{ $count }}</span>
                                            <span class="w-32 text-xs text-gray-400 truncate text-right">{{ $label }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- RADIO (Single Choice) RESULT --}}
                @elseif($question->type === 'radio')
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-900">{{ $question->question_text }}</h3>
                            <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase">Single Choice</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($question->options as $label)
                                @php 
                                    $count = $stat['breakdown'][$label] ?? 0;
                                    $percent = $stat['count'] > 0 ? ($count / $stat['count']) * 100 : 0;
                                @endphp
                                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 relative overflow-hidden">
                                    <div class="absolute bottom-0 left-0 h-1 bg-green-500 transition-all duration-500" style="width: {{ $percent }}%"></div>
                                    <div class="flex justify-between items-center relative z-10">
                                        <span class="font-bold text-gray-700 text-sm">{{ $label }}</span>
                                        <span class="font-black text-gray-900">{{ $count }}</span>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">{{ round($percent) }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                {{-- [NEW] CHECKBOX (Multi Choice) RESULT --}}
                @elseif($question->type === 'checkbox')
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-900">{{ $question->question_text }}</h3>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded uppercase">Multi-Select</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($question->options as $label)
                                @php 
                                    $count = $stat['breakdown'][$label] ?? 0;
                                    // Percentage is based on total respondents, not total selections (so it can sum > 100%)
                                    $percent = $stat['count'] > 0 ? ($count / $stat['count']) * 100 : 0;
                                @endphp
                                <div class="relative">
                                    <div class="flex justify-between items-center mb-1 text-sm">
                                        <span class="font-bold text-gray-700">{{ $label }}</span>
                                        <span class="font-bold text-gray-900">{{ $count }} <span class="text-gray-400 text-xs font-normal">({{ round($percent) }}%)</span></span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-gray-400 mt-4 italic">* Percentages may exceed 100% because respondents can select multiple options.</p>
                    </div>

                {{-- TEXT / FILE (List View) --}}
                @else
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-bold text-gray-900">{{ $question->question_text }}</h3>
                            <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase">Text / File</span>
                        </div>
                        
                        <div class="max-h-48 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                            @foreach($question->answers->take(10) as $answer)
                                <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    @if($question->type == 'file')
                                        <a href="{{ asset('storage/'.$answer->answer_value) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1 font-bold text-xs">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            View File
                                        </a>
                                    @else
                                        "{{ $answer->answer_value }}"
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($question->answers->count() > 10)
                            <button class="w-full mt-2 text-center text-xs font-bold text-orange-500 hover:text-orange-600 uppercase tracking-wide">
                                Load All {{ $question->answers->count() }} Responses
                            </button>
                        @endif
                    </div>
                @endif

            @endforeach
        </div>

    </div>
</div>