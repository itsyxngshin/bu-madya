<div class="min-h-screen bg-gray-50 p-4 md:p-6 font-sans text-gray-900 pb-20">
    
    <div class="max-w-5xl mx-auto">
        
        {{-- HEADER --}}
        <div class="mb-6">
            <a href="{{ route('admin.evaluations.index') }}" class="flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-orange-600 mb-2 uppercase tracking-widest transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900">{{ $evaluation->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Analysis of <strong class="text-gray-900">{{ $totalResponsesCount }}</strong> total responses.
            </p>
        </div>

        {{-- TABS --}}
        <div class="flex gap-4 border-b border-gray-200 mb-8 overflow-x-auto whitespace-nowrap">
            <button wire:click="setTab('summary')" class="pb-3 text-sm font-bold uppercase tracking-widest transition border-b-2 {{ $tab === 'summary' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Summary
            </button>
            <button wire:click="setTab('individual')" class="pb-3 text-sm font-bold uppercase tracking-widest transition border-b-2 {{ $tab === 'individual' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Individual Responses
            </button>
        </div>

        {{-- ========================================== --}}
        {{-- TAB 1: SUMMARY (Your existing code) --}}
        {{-- ========================================== --}}
        @if($tab === 'summary')
            <div class="space-y-6">
                @foreach($evaluation->questions->sortBy('order') as $index => $question)
                    
                    @if($question->type === 'page_break') @continue @endif

                    @php $stat = $stats[$question->id] ?? null; @endphp

                    {{-- SECTIONS --}}
                    @if($question->type === 'section')
                        <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                            {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                        </div>
                    
                    {{-- LIKERT SCALE RESULT --}}
                    @elseif($question->type === 'likert')
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="flex flex-col md:flex-row gap-8">
                                <div class="md:w-1/3 flex flex-col justify-center items-center bg-gray-50 rounded-xl p-6 border border-gray-100 text-center">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Average Rating</span>
                                    <div class="text-5xl font-black text-gray-900 mb-1">{{ $stat['average'] ?? '0.0' }}</div>
                                    <div class="flex gap-1 text-orange-400 mb-2">
                                        @for($i=1; $i<=5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= round($stat['average']) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $stat['count'] ?? 0 }} Responses</span>
                                </div>
                                <div class="md:w-2/3">
                                    <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                        {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                    </div>
                                    <div class="space-y-3">
                                        @if(isset($stat['count']) && $stat['count'] > 0)
                                            @foreach(array_reverse($question->options, true) as $optIndex => $label)
                                                @php 
                                                    $textLabel = is_array($label) ? ($label['text'] ?? '') : $label;
                                                    $count = $stat['breakdown'][$optIndex] ?? 0;
                                                    $percent = $stat['count'] > 0 ? ($count / $stat['count']) * 100 : 0;
                                                @endphp
                                                <div class="flex items-center gap-3 text-sm">
                                                    <span class="w-8 font-bold text-gray-400 text-right">{{ $optIndex + 1 }}</span>
                                                    <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                                        <div class="h-full bg-orange-500 rounded-full" style="width: {{ $percent }}%"></div>
                                                    </div>
                                                    <span class="w-12 text-right font-bold text-gray-700">{{ $count }}</span>
                                                    <span class="w-32 text-xs text-gray-400 truncate text-right" title="{{ $textLabel }}">{{ $textLabel }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    {{-- RADIO & DROPDOWN (Single Choice) RESULT --}}
                    @elseif(in_array($question->type, ['radio', 'dropdown']))
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                    {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                </div>
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    {{ $question->type === 'dropdown' ? 'Dropdown Menu' : 'Single Choice' }}
                                </span>
                            </div>
                            @if(isset($stat['count']) && $stat['count'] > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($question->options as $option)
                                        @php 
                                            $label = is_array($option) ? ($option['text'] ?? '') : $option;
                                            $count = $stat['breakdown'][$label] ?? 0;
                                            $percent = $stat['count'] > 0 ? ($count / $stat['count']) * 100 : 0;
                                        @endphp
                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 relative overflow-hidden">
                                            <div class="absolute bottom-0 left-0 h-1 bg-green-500 transition-all duration-500" style="width: {{ $percent }}%"></div>
                                            <div class="flex justify-between items-center relative z-10">
                                                <span class="font-bold text-gray-700 text-sm truncate pr-2" title="{{ $label }}">{{ $label }}</span>
                                                <span class="font-black text-gray-900">{{ $count }}</span>
                                            </div>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase">{{ round($percent) }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    {{-- CHECKBOX (Multi Choice) RESULT --}}
                    @elseif($question->type === 'checkbox')
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                    {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                </div>
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded uppercase">Multi-Select</span>
                            </div>
                            <div class="space-y-3">
                                @if(isset($stat['count']) && $stat['count'] > 0 && isset($stat['breakdown']))
                                    @foreach($question->options as $option)
                                        @php 
                                            $label = is_array($option) ? ($option['text'] ?? '') : $option;
                                            $count = $stat['breakdown'][$label] ?? 0;
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
                                @endif
                            </div>
                        </div>

                    {{-- TEXT / FILE (List View) --}}
                    @else
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="flex justify-between items-start mb-4">
                                <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                    {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                </div>
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    {{ $question->type === 'file' ? 'File Upload' : 'Text Input' }}
                                </span>
                            </div>
                            @if($question->answers->count() > 0)
                                <div class="max-h-48 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                    @foreach($question->answers->take(10) as $answer)
                                        <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                            @if($question->type == 'file')
                                                <a href="{{ asset('storage/'.$answer->answer_value) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1 font-bold text-xs">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    View Uploaded File
                                                </a>
                                            @else
                                                "{{ $answer->answer_value }}"
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

        {{-- ========================================== --}}
        {{-- TAB 2: INDIVIDUAL RESPONSES --}}
        {{-- ========================================== --}}
        @elseif($tab === 'individual')
            
            @if($totalResponsesCount === 0)
                <div class="bg-white rounded-2xl p-12 text-center border border-gray-200">
                    <p class="text-gray-400 font-bold">No responses have been submitted yet.</p>
                </div>
            @else
                
                {{-- Pagination Controls --}}
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 mb-6 flex items-center justify-between">
                    <button wire:click="previousResponse" class="p-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition disabled:opacity-30 disabled:cursor-not-allowed" {{ $currentIndex === 0 ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <div class="text-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Response</span>
                        <span class="text-lg font-black text-gray-900">{{ $currentIndex + 1 }} <span class="text-gray-400 font-normal">of</span> {{ $totalResponsesCount }}</span>
                    </div>
                    <button wire:click="nextResponse" class="p-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition disabled:opacity-30 disabled:cursor-not-allowed" {{ $currentIndex === ($totalResponsesCount - 1) ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>

                {{-- Meta Info Card --}}
                <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-md mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Submitted By</span>
                        <h2 class="text-lg font-bold mt-1">
                            @if($currentResponse->user)
                                <span class="flex items-center gap-2 text-green-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ $currentResponse->user->name }}
                                </span>
                            @else
                                <span class="text-gray-300">Anonymous User</span>
                            @endif
                        </h2>
                    </div>
                    <div class="text-left md:text-right">
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Date & Time</span>
                        <p class="text-sm mt-1">{{ $currentResponse->created_at->format('F d, Y - h:i A') }}</p>
                    </div>
                </div>

                {{-- The Form Breakdown --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @foreach($evaluation->questions->sortBy('order') as $question)
                        @if($question->type === 'page_break') @continue @endif

                        @if($question->type === 'section')
                            <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                            </div>
                        @else
                            @php
                                // Find this specific user's answer
                                $answer = $currentResponse->answers->where('evaluation_question_id', $question->id)->first();
                                $val = $answer ? $answer->answer_value : null;
                            @endphp

                            <div class="px-6 py-5 border-b border-gray-100 last:border-0">
                                <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                    {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                </div>
                                
                                @if(!$val)
                                    <span class="text-xs text-gray-400 italic">No answer provided</span>
                                @elseif($question->type === 'file')
                                    <a href="{{ asset('storage/'.$val) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 font-bold text-xs rounded-lg hover:bg-blue-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                                        Download Uploaded File
                                    </a>
                                @elseif($question->type === 'checkbox')
                                    @php 
                                        // Decode JSON array and display as pills
                                        $decoded = json_decode($val, true); 
                                    @endphp
                                    <div class="flex flex-wrap gap-2">
                                        @if(is_array($decoded))
                                            @foreach($decoded as $item)
                                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded">{{ $item }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-sm text-gray-900">{{ $val }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-sm text-gray-900 font-medium bg-gray-50/50 p-3 rounded-lg border border-gray-100">{{ $val }}</div>
                                @endif
                            </div>
                        @endif

                    @endforeach
                </div>
            @endif

        @endif
    </div>
</div>