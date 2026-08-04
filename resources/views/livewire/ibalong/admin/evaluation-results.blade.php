<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- Header Section --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-white">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-black uppercase tracking-widest text-white">Telemetry & Results</h1>
                <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-2 py-1 border-2 border-white">{{ $evaluation->responses->count() }} Responses Logged</span>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ $evaluation->title }}</p>
        </div>

        <a href="{{ route('ibalong.admin.evaluations.index') }}" class="bg-transparent text-white text-xs font-black uppercase px-6 py-3 border-2 border-white hover:bg-white hover:text-iba-black transition-colors">
            &larr; Return to Matrix
        </a>
    </div>

    @if($evaluation->responses->count() === 0)
        <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center">
            <p class="text-sm font-black text-gray-500 uppercase tracking-widest">Awaiting Data Transmission. No responses recorded yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8">
            @foreach($evaluation->questions as $question)
                @if(in_array($question->type, ['section', 'page_break'])) @continue @endif

                <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
                    <div class="flex items-start justify-between border-b-4 border-iba-black pb-3 mb-4 gap-4">
                        <h3 class="text-lg font-black uppercase text-iba-black">{{ $question->question_text }}</h3>
                        <span class="bg-gray-200 border-2 border-iba-black px-2 py-1 text-[9px] font-black uppercase tracking-widest text-iba-black">{{ $question->type }}</span>
                    </div>

                    {{-- Data Visualization for Choices --}}
                    @if(in_array($question->type, ['radio', 'dropdown', 'checkbox', 'likert']))
                        <div class="space-y-3">
                            @php
                                $totalAnswers = array_sum($tallies[$question->id] ?? []);
                            @endphp

                            @if($totalAnswers > 0)
                                @foreach($question->options as $option)
                                    @php
                                        $optText = is_array($option) ? ($option['text'] ?? '') : $option;
                                        $count = $tallies[$question->id][$optText] ?? 0;
                                        $percentage = $totalAnswers > 0 ? round(($count / $totalAnswers) * 100) : 0;
                                    @endphp
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                        <div class="w-full sm:w-1/3 text-xs font-bold uppercase text-gray-700 truncate" title="{{ $optText }}">{{ $optText }}</div>
                                        <div class="w-full sm:w-2/3 flex items-center gap-3">
                                            <div class="flex-1 h-6 bg-gray-100 border-2 border-iba-black relative">
                                                <div class="absolute top-0 left-0 h-full bg-iba-orange border-r-2 border-iba-black transition-all" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <div class="w-16 text-right text-xs font-black">{{ $count }} ({{ $percentage }}%)</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs font-bold text-gray-400 italic">No selection data available.</p>
                            @endif
                        </div>

                    {{-- Data Ledger for Text & Files --}}
                    @else
                        <div class="bg-gray-50 border-2 border-iba-black max-h-[300px] overflow-y-auto">
                            @forelse($textResponses[$question->id] ?? [] as $response)
                                <div class="p-4 border-b-2 border-dashed border-gray-300 last:border-0 hover:bg-white transition-colors">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black uppercase text-iba-teal">{{ $response['user'] }} <span class="text-gray-400">|</span> {{ $response['team'] }}</span>
                                        <span class="text-[9px] font-bold text-gray-400">{{ $response['date'] }}</span>
                                    </div>

                                    @if($question->type === 'file')
                                        <a href="{{ Storage::url($response['value']) }}" target="_blank" class="inline-flex items-center gap-2 bg-iba-black text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 border-iba-black hover:bg-gray-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            View Uploaded File
                                        </a>
                                    @else
                                        <p class="text-sm font-bold text-iba-black whitespace-pre-wrap">{{ $response['value'] }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="p-4 text-center">
                                    <p class="text-xs font-bold text-gray-400 italic">No responses available.</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
