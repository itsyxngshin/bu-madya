<div class="min-h-screen bg-gray-50 p-4 md:p-6 font-sans text-gray-900 pb-20"
     x-data="{
        previewOpen: false,
        previewUrl: '',
        previewType: '',
        openPreview(url) {
            this.previewUrl = url;
            let ext = url.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) {
                this.previewType = 'image';
            } else if (ext === 'pdf') {
                this.previewType = 'pdf';
            } else {
                this.previewType = 'other';
            }
            this.previewOpen = true;
        }
     }">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-6">
            <a href="{{ route('admin.evaluations.index') }}" class="flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-orange-600 mb-2 uppercase tracking-widest transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
            <div class="flex items-center flex-wrap gap-3">
                <h1 class="text-2xl md:text-3xl font-black text-gray-900">{{ $evaluation->title }}</h1>

                @if(auth()->user()->role?->role_name === 'administrator' && $evaluation->creator)
                    <span class="px-2 py-1 bg-orange-50 border border-orange-200 text-orange-700 text-[10px] uppercase tracking-widest font-bold rounded-full shadow-sm flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ $evaluation->creator->name }}
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-2">
                Analysis of <strong class="text-gray-900">{{ $totalResponsesCount }}</strong> total responses.
            </p>
        </div>

        {{-- TABS --}}
        <div class="flex gap-4 border-b border-gray-200 mb-8 overflow-x-auto whitespace-nowrap custom-scrollbar">
            <button wire:click="setTab('summary')" class="pb-3 text-sm font-bold uppercase tracking-widest transition border-b-2 {{ $tab === 'summary' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Summary
            </button>
            <button wire:click="setTab('individual')" class="pb-3 text-sm font-bold uppercase tracking-widest transition border-b-2 {{ $tab === 'individual' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Individual Responses
            </button>
        </div>

        {{-- [NEW] Flash Messages for Manual Certificate Generation --}}
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 text-green-700 px-4 py-3 rounded-lg text-xs font-bold border border-green-200 animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 text-red-700 px-4 py-3 rounded-lg text-xs font-bold border border-red-200 animate-fade-in-up">
                {{ session('error') }}
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- TAB 1: SUMMARY WITH CHARTS --}}
        {{-- ========================================== --}}
        @if($tab === 'summary')

            {{-- GEMINI AI INSIGHTS ENGINE --}}
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-100 rounded-2xl p-6 shadow-sm mb-8 relative overflow-hidden animate-fade-in-up">
                <div class="absolute -right-6 -top-6 text-purple-500/10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 14H7v-2h4v2zm4-4H7v-2h8v2zm0-4H7V7h8v2z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-sm font-black text-purple-900 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Gemini AI Executive Summary
                            </h3>
                            <p class="text-xs text-purple-700 font-medium mt-1">Deep analysis of quantitative scores and qualitative written feedback.</p>
                        </div>

                        @if(!$aiReport)
                            <button wire:click="generateAIInsights" wire:loading.attr="disabled" class="shrink-0 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-md transition flex items-center gap-2 group">
                                <span wire:loading.remove wire:target="generateAIInsights">
                                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </span>
                                <span wire:loading wire:target="generateAIInsights">
                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </span>
                                <span wire:loading.remove wire:target="generateAIInsights">Generate Insights</span>
                                <span wire:loading wire:target="generateAIInsights">Analyzing Data...</span>
                            </button>
                        @endif
                    </div>

                    @if (session()->has('ai_error'))
                        <div class="mt-4 bg-red-50 text-red-600 px-4 py-3 rounded-lg text-xs font-bold border border-red-100">
                            {{ session('ai_error') }}
                        </div>
                    @endif

                    @if($aiReport)
                        <div class="mt-6 bg-white/60 p-5 rounded-xl border border-purple-100">
                            <div class="prose prose-sm prose-purple max-w-none text-purple-900 leading-relaxed font-medium prose-p:mb-4 last:prose-p:mb-0">
                                {!! \Illuminate\Support\Str::markdown($aiReport) !!}
                            </div>
                        </div>
                        <div class="mt-4 text-right">
                            <button wire:click="generateAIInsights" class="text-[10px] font-bold uppercase tracking-widest text-purple-500 hover:text-purple-700 transition flex items-center gap-1 ml-auto">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Regenerate Report
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ALGORITHMIC SYNTHESIS BOX --}}
            @if($synthesisReport)
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 rounded-2xl p-6 shadow-sm mb-8 relative overflow-hidden animate-fade-in-up">
                    <div class="absolute -right-6 -top-6 text-indigo-500/10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19.92 12.38c-.3-.22-.61-.41-.93-.57l1.42-1.42c.58-.58.58-1.54 0-2.12l-1.42-1.42c-.58-.58-1.54-.58-2.12 0l-1.42 1.42a8.04 8.04 0 00-1.07-1.07l1.42-1.42c.58-.58.58-1.54 0-2.12l-1.42-1.42c-.58-.58-1.54-.58-2.12 0l-1.42 1.42A7.9 7.9 0 0012 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10c0-1.28-.24-2.5-.68-3.62zm-7.92 7.62A8 8 0 1120 12a8.01 8.01 0 01-8 8zm4-9h-3V8c0-.55-.45-1-1-1s-1 .45-1 1v3H8c-.55 0-1 .45-1 1s.45 1 1 1h3v3c0 .55.45 1 1 1s1-.45 1-1v-3h3c.55 0 1-.45 1-1s-.45-1-1-1z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-sm font-black text-indigo-900 uppercase tracking-widest flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Automated Data Synthesis
                        </h3>
                        <div class="text-indigo-900 leading-relaxed text-sm md:text-base font-medium prose prose-strong:text-indigo-700 max-w-none">
                            {!! \Illuminate\Support\Str::markdown($synthesisReport) !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- LIKERT HTML TABLE WITH SUBTOTALS --}}
            @php
                $likertQuestions = $evaluation->questions->where('type', 'likert')->sortBy('order');
                $firstLikert = $likertQuestions->first();
                $likertOptions = $firstLikert ? collect($firstLikert->options)->map(fn($opt) => is_array($opt) ? ($opt['text'] ?? '') : $opt)->toArray() : [];

                $groupedSections = [];
                $currentSectionTitle = 'General Evaluation';
                $currentSectionIndex = 0;

                $groupedSections[$currentSectionIndex] = [
                    'title' => $currentSectionTitle,
                    'questions' => [],
                    'totals' => array_fill(0, count($likertOptions), 0),
                    'sum_averages' => 0,
                    'count_averages' => 0,
                ];

                foreach($evaluation->questions->sortBy('order') as $question) {
                    if ($question->type === 'section') {
                        $currentSectionIndex++;
                        $groupedSections[$currentSectionIndex] = [
                            'title' => strip_tags(\Illuminate\Support\Str::markdown($question->question_text ?? '')),
                            'questions' => [],
                            'totals' => array_fill(0, count($likertOptions), 0),
                            'sum_averages' => 0,
                            'count_averages' => 0,
                        ];
                    } elseif ($question->type === 'likert') {
                        $groupedSections[$currentSectionIndex]['questions'][] = $question;

                        $stat = $stats[$question->id] ?? null;
                        if ($stat) {
                            foreach($likertOptions as $idx => $lbl) {
                                $groupedSections[$currentSectionIndex]['totals'][$idx] += ($stat['breakdown'][$idx] ?? 0);
                            }
                            if (($stat['average'] ?? 0) > 0) {
                                $groupedSections[$currentSectionIndex]['sum_averages'] += $stat['average'];
                                $groupedSections[$currentSectionIndex]['count_averages']++;
                            }
                        }
                    }
                }
                $groupedSections = array_filter($groupedSections, fn($sec) => count($sec['questions']) > 0);
            @endphp

            @if(count($groupedSections) > 0 && count($likertOptions) > 0)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8 animate-fade-in-up">
                    <div class="p-5 border-b border-gray-200 bg-gray-50/80">
                        <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Overall Likert Results
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Aggregated sentiment analysis with section sub-tabulations.</p>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white border-b border-gray-200 text-[10px] uppercase tracking-widest text-gray-500 font-bold">
                                    <th class="p-4 whitespace-nowrap min-w-[250px]">Evaluation Criteria</th>
                                    @foreach($likertOptions as $optionLabel)
                                        <th class="p-4 text-center whitespace-nowrap">{{ $optionLabel }}</th>
                                    @endforeach
                                    <th class="p-4 text-center">Score</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($groupedSections as $section)
                                    <tr class="bg-orange-50/50">
                                        <td colspan="{{ count($likertOptions) + 2 }}" class="p-4 text-sm font-black text-orange-700 uppercase tracking-widest border-y border-orange-100">
                                            {{ $section['title'] }}
                                        </td>
                                    </tr>

                                    @foreach($section['questions'] as $question)
                                        @php
                                            $stat = $stats[$question->id] ?? null;
                                            $avg = $stat['average'] ?? 0;

                                            if ($avg >= 4.5) $colorClass = 'bg-green-50 text-green-700 border-green-200';
                                            elseif ($avg >= 3.5) $colorClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                            elseif ($avg >= 2.5) $colorClass = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                            else $colorClass = 'bg-red-50 text-red-700 border-red-200';

                                            $qText = strip_tags(\Illuminate\Support\Str::markdown($question->question_text ?? ''));
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 text-sm text-gray-900 font-medium pl-6">
                                                {{ $qText }}
                                            </td>
                                            @foreach($likertOptions as $index => $optionLabel)
                                                <td class="p-4 text-center font-bold text-sm text-gray-600">
                                                    {{ $stat['breakdown'][$index] ?? 0 }}
                                                </td>
                                            @endforeach
                                            <td class="p-4 text-center">
                                                <span class="px-2.5 py-1 rounded-lg border text-xs font-black {{ $colorClass }} shadow-sm">
                                                    {{ number_format($avg, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @php
                                        $secAvg = $section['count_averages'] > 0 ? ($section['sum_averages'] / $section['count_averages']) : 0;
                                        if ($secAvg >= 4.5) $secColorClass = 'bg-green-100 text-green-800 border-green-300';
                                        elseif ($secAvg >= 3.5) $secColorClass = 'bg-blue-100 text-blue-800 border-blue-300';
                                        elseif ($secAvg >= 2.5) $secColorClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                        else $secColorClass = 'bg-red-100 text-red-800 border-red-300';
                                    @endphp
                                    <tr class="bg-gray-50/80 border-t-2 border-gray-200">
                                        <td class="p-4 text-[10px] font-black text-gray-700 text-right uppercase tracking-widest">
                                            Section Subtotal
                                        </td>
                                        @foreach($section['totals'] as $total)
                                            <td class="p-4 text-center font-black text-sm text-gray-800">
                                                {{ $total }}
                                            </td>
                                        @endforeach
                                        <td class="p-4 text-center">
                                            <span class="px-2.5 py-1 rounded-lg border text-sm font-black {{ $secColorClass }} shadow-sm">
                                                {{ number_format($secAvg, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- INDIVIDUAL QUESTION CHARTS --}}
            <div class="space-y-6">
                @foreach($evaluation->questions->sortBy('order') as $index => $question)

                    @if($question->type === 'page_break') @continue @endif

                    @php
                        $stat = $stats[$question->id] ?? null;
                        $chartLabels = [];
                        $chartData = [];
                        $chartColors = [];

                        if($stat && isset($stat['count']) && $stat['count'] > 0 && is_array($question->options)) {
                            $colors = ['#f97316', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6', '#eab308', '#ec4899', '#06b6d4'];
                            $colorIndex = 0;

                            foreach($question->options as $optIndex => $opt) {
                                $lbl = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                                $chartLabels[] = \Illuminate\Support\Str::limit($lbl, 25);

                                if ($question->type === 'likert') {
                                    $chartData[] = $stat['breakdown'][$optIndex] ?? 0;
                                    $chartColors[] = '#f97316';
                                } else {
                                    $chartData[] = $stat['breakdown'][$lbl] ?? 0;
                                    if($question->type === 'checkbox') {
                                         $chartColors[] = '#3b82f6';
                                    } else {
                                         $chartColors[] = $colors[$colorIndex % count($colors)];
                                         $colorIndex++;
                                    }
                                }
                            }
                        }
                    @endphp

                    @if($question->type === 'section')
                        <div class="pt-8 pb-2 border-b-2 border-orange-100">
                            <div class="text-lg font-black text-orange-600 uppercase tracking-tight prose prose-sm max-w-none prose-p:my-0 prose-a:text-orange-600">
                                {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                            </div>
                        </div>

                    @elseif($question->type === 'likert')
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="font-bold text-gray-900 mb-6 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                            </div>
                            <div class="flex flex-col lg:flex-row gap-8">
                                <div class="lg:w-1/3 flex flex-col justify-center items-center bg-gray-50 rounded-xl p-6 border border-gray-100 text-center">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Average Rating</span>
                                    <div class="text-5xl font-black text-gray-900 mb-1">{{ $stat['average'] ?? '0.0' }}</div>
                                    <div class="flex gap-1 text-orange-400 mb-2">
                                        @for($i=1; $i<=5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= round($stat['average'] ?? 0) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $stat['count'] ?? 0 }} Responses</span>
                                </div>
                                <div class="lg:w-2/3">
                                    @if(isset($stat['count']) && $stat['count'] > 0)
                                        <div class="h-56 w-full relative" wire:ignore>
                                            <canvas x-data="{
                                                init() {
                                                    new Chart(this.$el, {
                                                        type: 'bar',
                                                        data: {
                                                            labels: {{ json_encode(array_values($chartLabels)) }},
                                                            datasets: [{
                                                                data: {{ json_encode(array_values($chartData)) }},
                                                                backgroundColor: '#f97316',
                                                                borderRadius: 4
                                                            }]
                                                        },
                                                        options: {
                                                            responsive: true,
                                                            maintainAspectRatio: false,
                                                            plugins: { legend: { display: false } },
                                                            scales: {
                                                                y: { beginAtZero: true, ticks: { precision: 0 } },
                                                                x: { grid: { display: false } }
                                                            }
                                                        }
                                                    });
                                                }
                                            }"></canvas>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @elseif(in_array($question->type, ['radio', 'dropdown']))
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="flex justify-between items-center mb-6">
                                <div class="font-bold text-gray-900 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-0 prose-a:text-orange-600">
                                    {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                </div>
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase shrink-0 ml-4">
                                    {{ $question->type === 'dropdown' ? 'Dropdown Menu' : 'Single Choice' }}
                                </span>
                            </div>
                            @if(isset($stat['count']) && $stat['count'] > 0)
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
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
                                    <div class="h-64 w-full relative" wire:ignore>
                                        <canvas x-data="{
                                            init() {
                                                new Chart(this.$el, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: {{ json_encode(array_values($chartLabels)) }},
                                                        datasets: [{
                                                            data: {{ json_encode(array_values($chartData)) }},
                                                            backgroundColor: {{ json_encode($chartColors) }},
                                                            borderRadius: 4
                                                        }]
                                                    },
                                                    options: {
                                                        indexAxis: 'y',
                                                        responsive: true,
                                                        maintainAspectRatio: false,
                                                        plugins: { legend: { display: false } },
                                                        scales: {
                                                            x: { beginAtZero: true, ticks: { precision: 0 } },
                                                            y: { grid: { display: false } }
                                                        }
                                                    }
                                                });
                                            }
                                        }"></canvas>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @elseif($question->type === 'checkbox')
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="flex justify-between items-center mb-6">
                                <div class="font-bold text-gray-900 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-0 prose-a:text-orange-600">
                                    {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                </div>
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded uppercase shrink-0 ml-4">Multi-Select</span>
                            </div>
                            @if(isset($stat['count']) && $stat['count'] > 0 && isset($stat['breakdown']))
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                    <div class="space-y-4">
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
                                    </div>
                                    <div class="h-64 w-full relative" wire:ignore>
                                        <canvas x-data="{
                                            init() {
                                                new Chart(this.$el, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: {{ json_encode(array_values($chartLabels)) }},
                                                        datasets: [{
                                                            data: {{ json_encode(array_values($chartData)) }},
                                                            backgroundColor: '#3b82f6',
                                                            borderRadius: 4
                                                        }]
                                                    },
                                                    options: {
                                                        indexAxis: 'y',
                                                        responsive: true,
                                                        maintainAspectRatio: false,
                                                        plugins: { legend: { display: false } },
                                                        scales: {
                                                            x: { beginAtZero: true, ticks: { precision: 0 } },
                                                            y: { grid: { display: false } }
                                                        }
                                                    }
                                                });
                                            }
                                        }"></canvas>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @else
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                            <div class="flex justify-between items-start mb-4">
                                <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600">
                                    {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                                </div>
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase shrink-0 ml-4">
                                    {{ $question->type === 'file' ? 'File Uploads' : 'Text Input' }}
                                </span>
                            </div>
                            @if($question->answers->count() > 0)
                                <div class="max-h-48 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                    @foreach($question->answers->take(10) as $answer)
                                        <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100 break-words">
                                            @if($question->type == 'file')
                                                <button type="button" @click="openPreview('{{ asset('storage/'.$answer->answer_value) }}')" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1.5 font-bold text-xs transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Preview Uploaded File
                                                </button>
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

                    {{-- ONLY SHOW IF A TEMPLATE EXISTS --}}
                    @if($evaluation->certificate_template)
                        <button wire:click="openIssueModal({{ $currentResponse->id }})" class="px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition flex items-center gap-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Issue Certificate
                        </button>
                    @endif

                    <div class="text-left md:text-right border-t border-gray-700 pt-3 md:border-t-0 md:pt-0 w-full md:w-auto">
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Date & Time</span>
                        <p class="text-sm mt-1">{{ $currentResponse->created_at->format('F d, Y - h:i A') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @foreach($evaluation->questions->sortBy('order') as $question)
                        @if($question->type === 'page_break') @continue @endif

                        @if($question->type === 'section')
                            <div class="font-bold text-gray-900 mb-4 prose prose-sm max-w-none prose-p:mt-0 prose-p:mb-2 prose-a:text-orange-600 px-6 pt-6">
                                {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                            </div>
                        @else
                            @php
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
                                    <button type="button" @click="openPreview('{{ asset('storage/'.$val) }}')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white font-bold text-xs rounded-xl hover:bg-gray-800 shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Preview Uploaded File
                                    </button>
                                @elseif($question->type === 'checkbox')
                                    @php $decoded = json_decode($val, true); @endphp
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
                                    <div class="text-sm text-gray-900 font-medium bg-gray-50/50 p-3 rounded-lg border border-gray-100 break-words">{{ $val }}</div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- ALPINE.JS FILE PREVIEW MODAL               --}}
    {{-- ========================================== --}}
    <div x-show="previewOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-2 sm:p-6" x-cloak>
        <div x-show="previewOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm"
             @click="previewOpen = false"></div>

        <div x-show="previewOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden"
             :class="previewType === 'pdf' ? 'w-[95vw] h-[90vh]' : 'w-auto max-w-[95vw] max-h-[95vh]'">

            <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-white z-10 shrink-0">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2 pr-6">
                    <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span class="truncate">File Preview</span>
                </h3>
                <div class="flex items-center gap-2 shrink-0">
                    <a :href="previewUrl" target="_blank" download class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[10px] font-bold uppercase tracking-widest rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span class="hidden sm:inline">Download</span>
                    </a>
                    <button @click="previewOpen = false" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <div class="overflow-hidden bg-gray-100/80 flex items-center justify-center p-2 sm:p-4 relative"
                 :class="previewType === 'pdf' ? 'flex-1' : ''">

                <template x-if="previewType === 'image'">
                    <img :src="previewUrl" class="max-w-full max-h-[80vh] w-auto h-auto object-contain rounded-lg drop-shadow-sm">
                </template>

                <template x-if="previewType === 'pdf'">
                    <iframe :src="previewUrl" class="w-full h-full rounded-lg border border-gray-200 shadow-sm bg-white"></iframe>
                </template>

                <template x-if="previewType === 'other'">
                    <div class="text-center p-8 max-w-sm">
                        <div class="w-20 h-20 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-gray-900 font-bold text-lg mb-1">Preview Not Available</h4>
                        <p class="text-gray-500 text-sm mb-6 leading-relaxed">This file format cannot be displayed natively in the browser. You must download it to view.</p>
                        <a :href="previewUrl" download class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl transition shadow-lg text-xs uppercase tracking-widest inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download File
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
    {{-- ========================================== --}}
    {{-- REVIEW & ISSUE CERTIFICATE MODAL --}}
    {{-- ========================================== --}}
    @if($issueModalOpen)
        <div class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6 bg-gray-900/80 backdrop-blur-sm transition-opacity" x-data="{ tab: 'details' }">

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl flex flex-col overflow-hidden transform transition-all">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 leading-tight">Review & Issue Certificate</h3>
                        <p class="text-xs font-bold text-gray-400 mt-1">Verify details before sending.</p>
                    </div>
                    <button wire:click="closeIssueModal" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 bg-gray-50 flex-1 overflow-y-auto">
                    <div class="space-y-4">
                        {{-- Editable Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Participant Name (On Cert)</label>
                                <input type="text" wire:model="issueName" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                                @error('issueName') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Recipient Email</label>
                                <input type="email" wire:model="issueEmail" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm" placeholder="Leave blank to skip email">
                                @error('issueEmail') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Editable Email Message --}}
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email Subject</label>
                            <input type="text" wire:model="issueSubject" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold focus:ring-orange-500 focus:border-orange-500 shadow-sm mb-3">

                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email Body</label>
                            <textarea wire:model="issueBody" rows="5" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-orange-500 focus:border-orange-500 resize-y shadow-sm"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer Actions --}}
                <div class="p-6 bg-white border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <button wire:click="downloadCertificate" class="w-full sm:w-auto px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs uppercase tracking-widest transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Only
                    </button>

                    <button wire:click="sendCertificateEmail" class="w-full sm:w-auto px-6 py-3 bg-orange-600 hover:bg-orange-500 text-white font-bold rounded-xl text-xs uppercase tracking-widest shadow-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Send Email
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
