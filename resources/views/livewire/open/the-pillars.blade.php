<div wire:poll.5s class="min-h-screen bg-stone-50 font-sans text-gray-900 pb-20 relative overflow-x-hidden">

    {{-- 1. ATMOSPHERE: SIGNATURE BLOBS (Unchanged) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-stone-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. HERO HEADER (Unchanged) --}}
    <div class="relative z-10 pt-16 pb-12 px-6 text-center max-w-2xl mx-auto">
        @if(Auth::check() && Auth::user()->role->role_name === 'director') 
            <div class="mb-6 animate-fade-in-down">
                <a href="{{ route('director.pillars.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg hover:bg-red-600 hover:scale-105 transition-all">
                    Manage Pillars
                </a>
            </div>
        @endif

        <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md border border-white/50 px-4 py-1.5 rounded-full mb-6 shadow-sm">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Decision Center</span>
        </div>
        
        <h1 class="font-heading font-black text-4xl md:text-6xl mb-4 tracking-tighter text-gray-900 drop-shadow-sm">
            The <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Pillars</span>
        </h1>
        
        <p class="text-gray-500 text-sm md:text-base leading-relaxed font-medium">
            Your voice shapes our advocacy. Vote on the core resolutions below.
        </p>
    </div>

    {{-- 3. PILLARS FEED --}}
    <div class="relative z-10 max-w-xl mx-auto px-4 space-y-12">
        @forelse($pillars as $pillar)
        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-white/60 overflow-hidden transform transition hover:-translate-y-1 hover:shadow-2xl">
            
            {{-- Cover Image --}}
            @if($pillar->image_path)
            <div class="h-64 w-full relative group overflow-hidden">
                <img src="{{ asset('storage/'.$pillar->image_path) }}" 
                     class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-8 right-8">
                    <h2 class="font-heading font-black text-2xl md:text-3xl text-white leading-tight drop-shadow-lg">
                        {{ $pillar->title }}
                    </h2>
                </div>
            </div>
            <div class="p-8 bg-white border-b border-gray-100">
                <div class="prose prose-stone text-gray-600 text-sm md:text-base leading-relaxed font-medium">
                    {{ $pillar->description }}
                </div>
            </div>
            @else
            <div class="p-8 bg-white border-b border-gray-100">
                <h2 class="font-heading font-black text-2xl md:text-3xl text-gray-900 mb-4 leading-tight">
                    {{ $pillar->title }}
                </h2>
                <div class="prose prose-stone text-gray-600 text-sm md:text-base leading-relaxed font-medium">
                    {{ $pillar->description }}
                </div>
            </div>
            @endif

            {{-- Questions Loop --}}
            <div class="p-6 md:p-8 space-y-8 md:space-y-10"> {{-- Reduced padding/spacing on mobile --}}
                @foreach($pillar->mapped_questions as $q)
                
                <div wire:key="question-{{ $q['id'] }}" class="relative">
                    
                    {{-- UPDATED: Question Text (Smaller on mobile) --}}
                    <h3 class="font-bold text-gray-900 text-sm md:text-lg mb-3 md:mb-4 flex items-start gap-3 leading-snug">
                        <span class="bg-red-100 text-red-600 text-[10px] font-black uppercase px-2 py-1 rounded-md mt-0.5 shrink-0">Q{{ $loop->iteration }}</span>
                        {{ $q['text'] }}
                    </h3>

                    {{-- Logic: Results OR Voting --}}
                    @if($q['has_voted'])

                        {{-- RESULTS AREA --}}
                        <div x-data="{ 
                                counts: @js($q['options']->pluck('count')),
                                labels: @js($q['options']->pluck('label')),
                                colors: @js($q['options']->map(fn($o) => match($o['color']) { 
                                    'green'=>'#22c55e', 'red'=>'#ef4444', 'yellow'=>'#eab308', 'blue'=>'#3b82f6', 
                                    'purple'=>'#a855f7', 'orange'=>'#f97316', 'teal'=>'#14b8a6', 'pink'=>'#ec4899', 
                                    default=>'#374151' 
                                })),
                                chartInstance: null,
                                initChart() {
                                    setTimeout(() => {
                                        let data = this.counts;
                                        let palette = this.colors;
                                        let total = data.reduce((a, b) => a + b, 0);

                                        if (total === 0) {
                                            data = [1]; 
                                            palette = ['#e5e7eb'];
                                        }

                                        let options = {
                                            series: data,
                                            labels: this.labels,
                                            colors: palette,
                                            chart: { 
                                                type: 'donut', 
                                                height: 250, 
                                                fontFamily: 'Inter, sans-serif',
                                                animations: { enabled: false },
                                                width: '100%'
                                            },
                                            legend: { position: 'bottom', show: total > 0 },
                                            dataLabels: { enabled: total > 0 },
                                            tooltip: { enabled: total > 0 },
                                            plotOptions: { pie: { donut: { size: '65%' } } },
                                            stroke: { show: true, colors: ['#ffffff'], width: 2 }
                                        };

                                        if (this.$refs.chart) {
                                            this.chartInstance = new ApexCharts(this.$refs.chart, options);
                                            this.chartInstance.render();
                                        }
                                    }, 50);
                                },
                                updateChart() {
                                    if (!this.chartInstance) return;
                                    let total = this.counts.reduce((a, b) => a + b, 0);
                                    
                                    if (total === 0) {
                                        this.chartInstance.updateOptions({
                                            series: [1], colors: ['#e5e7eb'], legend: { show: false },
                                            dataLabels: { enabled: false }, tooltip: { enabled: false }
                                        });
                                    } else {
                                        this.chartInstance.updateOptions({
                                            series: this.counts, colors: this.colors, legend: { show: true },
                                            dataLabels: { enabled: true }, tooltip: { enabled: true }
                                        });
                                    }
                                }
                            }" 
                            x-init="initChart(); $watch('counts', () => updateChart())" 
                            class="bg-gray-50/50 rounded-2xl p-4 md:p-6 border border-gray-100 relative"> {{-- Smaller padding mobile --}}

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center">
                                <div class="flex justify-center w-full">
                                    <div wire:ignore x-ref="chart" class="w-full max-w-[250px] md:max-w-[300px] min-h-[200px] md:min-h-[250px]"></div>
                                </div>

                                {{-- UPDATED: Results List (Smaller on mobile) --}}
                                <div class="space-y-3 md:space-y-4">
                                    <h4 class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-200 pb-2">Results Breakdown</h4>
                                    
                                    @foreach($q['options'] as $opt)
                                    <div class="flex justify-between items-center group">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full {{ match($opt['color']) { 
                                                'green'=>'bg-green-500', 'red'=>'bg-red-500', 'yellow'=>'bg-yellow-400', 
                                                'blue'=>'bg-blue-500', 'purple'=>'bg-purple-500', 'orange'=>'bg-orange-500', 
                                                'teal'=>'bg-teal-500', 'pink'=>'bg-pink-500', default=>'bg-gray-800' 
                                            } }}"></span>
                                            
                                            {{-- Smaller text on mobile --}}
                                            <span class="text-xs md:text-sm font-bold text-gray-700">{{ $opt['label'] }}</span>
                                            
                                            @if($q['voted_option_id'] == $opt['id'])
                                                <span class="text-[9px] md:text-[10px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded">You</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs md:text-sm font-bold">{{ $opt['percent'] }}%</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        
                        {{-- UPDATED: Voting Buttons (Smaller on mobile) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-{{ count($q['options']) > 2 ? '2' : count($q['options']) }} gap-2 md:gap-3">
                            @foreach($q['options'] as $opt)
                                <button wire:click="vote({{ $q['id'] }}, {{ $opt['id'] }})" 
                                        class="group px-3 py-2 md:px-4 md:py-3 bg-white border-2 border-gray-100 rounded-xl font-bold text-gray-600 text-xs md:text-sm hover:border-gray-300 hover:bg-gray-50 hover:shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                                    {{ $opt['label'] }}
                                </button>
                            @endforeach
                        </div>

                    @endif

                </div>
                @if(!$loop->last) <hr class="border-gray-200 border-dashed"> @endif
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-20 opacity-60">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-gray-500 font-bold">No active pillars at this time.</p>
        </div>
        @endforelse
    </div>
</div>