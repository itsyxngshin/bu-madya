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
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-bold text-2xl tracking-tight">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-6 text-sm">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                </p>
                
                {{-- Social Media Links --}}
                <div class="flex space-x-4">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>

                    {{-- X (Twitter) --}}
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-6 text-red-500 uppercase tracking-widest text-xs">Quick Links</h4>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                    <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                    <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500 uppercase tracking-widest text-xs">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-inner">
                    <span class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>