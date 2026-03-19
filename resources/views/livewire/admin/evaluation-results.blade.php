{{-- [NEW] Load Chart.js for Beautiful Graphs --}}
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
    
    {{-- [FIXED] Moved the script inside the root element so Livewire doesn't panic! --}}
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
        <div class="flex gap-4 border-b border-gray-200 mb-8 overflow-x-auto whitespace-nowrap">
            <button wire:click="setTab('summary')" class="pb-3 text-sm font-bold uppercase tracking-widest transition border-b-2 {{ $tab === 'summary' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Summary
            </button>
            <button wire:click="setTab('individual')" class="pb-3 text-sm font-bold uppercase tracking-widest transition border-b-2 {{ $tab === 'individual' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Individual Responses
            </button>
        </div>

        {{-- ========================================== --}}
        {{-- TAB 1: SUMMARY WITH CHARTS --}}
        {{-- ========================================== --}}
        @if($tab === 'summary')
            <div class="space-y-6">
                @foreach($evaluation->questions->sortBy('order') as $index => $question)
                    
                    @if($question->type === 'page_break') @continue @endif

                    @php 
                        $stat = $stats[$question->id] ?? null; 
                        
                        // [NEW] Dynamically Build Chart Data Array
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

                    {{-- SECTIONS --}}
                    @if($question->type === 'section')
                        <div class="pt-8 pb-2 border-b-2 border-orange-100">
                            <div class="text-lg font-black text-orange-600 uppercase tracking-tight prose prose-sm max-w-none prose-p:my-0 prose-a:text-orange-600">
                                {!! \Illuminate\Support\Str::markdown($question->question_text ?? '') !!}
                            </div>
                        </div>
                    
                    {{-- LIKERT SCALE (Vertical Bar Chart) --}}
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
                                                                label: 'Responses',
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

                    {{-- RADIO & DROPDOWN (Horizontal Chart) --}}
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

                    {{-- CHECKBOX (Horizontal Chart) --}}
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

                    {{-- TEXT / FILE --}}
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
                                        <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
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
                    <div class="text-left md:text-right">
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
                                    <div class="text-sm text-gray-900 font-medium bg-gray-50/50 p-3 rounded-lg border border-gray-100">{{ $val }}</div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- ALPINE.JS FILE PREVIEW MODAL (Smart Fit) --}}
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
</div>