<div wire:poll.5s class="min-h-screen bg-stone-50 font-sans text-gray-900 pb-20 relative overflow-x-hidden">

    {{-- 1. ATMOSPHERE: SIGNATURE BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        {{-- Base Overlay --}}
        <div class="absolute top-0 left-0 w-full h-full bg-stone-50/80"></div>
        
        {{-- Animated Orbs --}}
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. HERO HEADER --}}
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
            <div class="h-56 w-full relative">
                <img src="{{ asset('storage/'.$pillar->image_path) }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute bottom-6 left-8 right-8">
                    <h2 class="font-heading font-black text-2xl md:text-3xl text-white mb-2 leading-tight drop-shadow-md">{{ $pillar->title }}</h2>
                    <p class="text-white/90 text-sm line-clamp-2 font-medium">{{ $pillar->description }}</p>
                </div>
            </div>
            @else
            <div class="p-8 pb-0">
                <h2 class="font-heading font-black text-2xl md:text-3xl text-gray-900 mb-2">{{ $pillar->title }}</h2>
                <p class="text-gray-500 text-sm font-medium">{{ $pillar->description }}</p>
            </div>
            @endif

            {{-- Questions Loop --}}
            <div class="p-8 space-y-10">
                @foreach($pillar->mapped_questions as $q)
                <div class="relative">
                    
                    {{-- Question Text --}}
                    <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-start gap-3 leading-snug">
                        <span class="bg-red-100 text-red-600 text-[10px] font-black uppercase px-2 py-1 rounded-md mt-1 shrink-0">Q{{ $loop->iteration }}</span>
                        {{ $q['text'] }}
                    </h3>

                    {{-- Logic: Results OR Voting --}}
                    @if($q['has_voted'])

                        {{-- RESULTS AREA (Chart + List) --}}
                        <div x-data="{ 
                                {{-- 1. DATA: Holds Chart AND Modal state --}}
                                counts: @js($q['options']->pluck('count')),
                                labels: @js($q['options']->pluck('label')),
                                colors: @js($q['options']->map(fn($o) => match($o['color']) { 
                                    'green'=>'#22c55e', 'red'=>'#ef4444', 'yellow'=>'#eab308', 'blue'=>'#3b82f6', 
                                    'purple'=>'#a855f7', 'orange'=>'#f97316', 'teal'=>'#14b8a6', 'pink'=>'#ec4899', 
                                    default=>'#374151' 
                                })),
                                chartInstance: null,
                                showVotersModal: false,  // <--- RESTORED THIS
                                activeOption: null,      // <--- RESTORED THIS

                                {{-- 2. CHART FUNCTIONS --}}
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
                                },

                                {{-- 3. MODAL HELPER --}}
                                openModal(option) {
                                    this.activeOption = option;
                                    this.showVotersModal = true;
                                }
                            }" 
                            x-init="initChart(); $watch('counts', () => updateChart())" 
                            class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100 relative">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                
                                {{-- THE CHART --}}
                                <div class="flex justify-center w-full">
                                    <div wire:ignore x-ref="chart" class="w-full max-w-[300px] min-h-[250px]"></div>
                                </div>

                                {{-- BREAKDOWN LIST --}}
                                <div class="space-y-4">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-200 pb-2">Results Breakdown</h4>
                                    
                                    @foreach($q['options'] as $opt)
                                    <div class="flex justify-between items-center group">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full {{ match($opt['color']) { 
                                                'green'=>'bg-green-500', 'red'=>'bg-red-500', 'yellow'=>'bg-yellow-400', 
                                                'blue'=>'bg-blue-500', 'purple'=>'bg-purple-500', 'orange'=>'bg-orange-500', 
                                                'teal'=>'bg-teal-500', 'pink'=>'bg-pink-500', default=>'bg-gray-800' 
                                            } }}"></span>
                                            <span class="text-sm font-bold text-gray-700">{{ $opt['label'] }}</span>
                                            @if($q['voted_option_id'] == $opt['id'])
                                                <span class="text-[10px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded">You</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-bold">{{ $opt['percent'] }}%</span>
                                            
                                            {{-- SEE WHO BUTTON --}}
                                            @if(Auth::check() && Auth::user()->role->role_name === 'director' && count($opt['voters']) > 0)
                                                <button @click="openModal(@js($opt))" 
                                                        class="text-[10px] text-blue-500 font-bold hover:underline cursor-pointer opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                                    See who
                                                </button>
                                            @endif 
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- MODAL MARKUP --}}
                            <template x-teleport="body">
        
                                <div x-show="showVotersModal" 
                                    class="fixed inset-0 z-[999] flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm"
                                    x-transition.opacity
                                    style="display: none;">
                                    
                                    {{-- Modal Content --}}
                                    <div @click.away="showVotersModal = false" 
                                        class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh] animate-scale-in">
                                        
                                        {{-- Header --}}
                                        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                            <div>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase">Voters for</p>
                                                <h3 class="font-bold text-gray-900 text-lg" x-text="activeOption?.label"></h3>
                                            </div>
                                            <button @click="showVotersModal = false" class="text-gray-400 hover:text-red-500">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>

                                        {{-- List --}}
                                        <div class="overflow-y-auto p-4 space-y-3">
                                            <template x-for="voter in activeOption?.voters" :key="voter.name">
                                                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition">
                                                    <img :src="voter.avatar" class="w-8 h-8 rounded-full bg-gray-200 object-cover border border-gray-100">
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-900" x-text="voter.name"></p>
                                                        <p class="text-[10px] text-gray-400" x-text="voter.date"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                    </div>
                                </div>

                            </template>

                        </div>
                    @else
                        
                        {{-- VOTING BUTTONS --}}
                        <div class="grid grid-cols-1 sm:grid-cols-{{ count($q['options']) > 2 ? '2' : count($q['options']) }} gap-3">
                            @foreach($q['options'] as $opt)
                                <button wire:click="vote({{ $q['id'] }}, {{ $opt['id'] }})" 
                                        class="group px-4 py-3 bg-white border-2 border-gray-100 rounded-xl font-bold text-gray-600 text-sm hover:border-gray-300 hover:bg-gray-50 hover:shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
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