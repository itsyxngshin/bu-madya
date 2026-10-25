@push('adsense')
    @if(env('APP_ENV') === 'production')
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6467581285062853" crossorigin="anonymous"></script>
    @endif
@endpush

@section('meta_title', '[EVENT] ' . $event->title)
@section('meta_description', $event->description ? Str::limit(strip_tags($event->description), 160) : 'Join us for an unforgettable experience at our upcoming event! Discover inspiring speakers, engaging activities, and valuable networking opportunities. Don\'t miss out on this chance to connect and grow. Register now!')
@php
    // Determine the image URL using PHP logic
    $ogImage = $event->cover_image
        ? (Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/' . $event->cover_image))
        : asset('images/MADYA Web Logo1.png');
@endphp

@section('meta_image', $ogImage)

<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative overflow-x-hidden">
    {{-- BACKGROUND BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="relative z-10">

        {{-- HERO SECTION --}}
        <header class="relative pt-24 md:pt-32 pb-8 md:pb-12 px-5 md:px-6 max-w-7xl mx-auto">

            {{-- Back Button --}}
            <div class="mb-6 md:mb-8 relative z-20">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition">
                    &larr; Back to Events
                </a>
            </div>

            {{-- The 2-Column Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center relative z-20">

                {{-- LEFT COLUMN: Title, Dates, and Button --}}
                <div class="order-2 lg:order-1 text-center lg:text-left">

                    <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-[1.15] md:leading-[1.1] mb-6 md:mb-8 drop-shadow-sm">
                        {{ $event->title }}
                    </h1>

                    {{-- Dates and Times --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 md:gap-4 mb-8 md:mb-10">
                        {{-- STARTS --}}
                        <div class="flex items-center gap-3 md:gap-4 bg-white/80 backdrop-blur px-4 py-3 md:px-5 md:py-4 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 w-full sm:w-auto">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="text-left">
                                <p class="text-[9px] md:text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-0.5">Starts</p>
                                <span class="font-bold text-gray-900 text-sm md:text-base block leading-tight">
                                    {{ $event->start_date ? $event->start_date->format('F d, Y') : 'TBA' }}
                                </span>
                                <span class="text-[11px] md:text-xs font-medium text-gray-500 block">
                                    {{ $event->start_date ? $event->start_date->format('h:i A') : '' }}
                                </span>
                            </div>
                        </div>

                        {{-- ENDS --}}
                        @if($event->end_date)
                        <div class="flex items-center gap-3 md:gap-4 bg-white/80 backdrop-blur px-4 py-3 md:px-5 md:py-4 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 w-full sm:w-auto">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="text-left">
                                <p class="text-[9px] md:text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-0.5">Ends</p>
                                <span class="font-bold text-gray-900 text-sm md:text-base block leading-tight">
                                    {{ $event->end_date->format('F d, Y') }}
                                </span>
                                <span class="text-[11px] md:text-xs font-medium text-gray-500 block">
                                    {{ $event->end_date->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- CTA Button --}}
                    @if($event->isOpen())
                        @if($event->is_internal_rsvp)
                            <a href="{{ route('events.rsvp', $event->slug) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 md:px-8 md:py-4 bg-red-600 hover:bg-red-700 text-white text-sm md:text-base font-black uppercase tracking-wider rounded-xl shadow-xl hover:shadow-red-500/30 transition-all transform hover:-translate-y-1">
                                <span>Get Ticket</span>
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </a>
                        @elseif($event->registration_link)
                            <a href="{{ $event->registration_link }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 md:px-8 md:py-4 bg-red-600 hover:bg-red-700 text-white text-sm md:text-base font-black uppercase tracking-wider rounded-xl shadow-xl hover:shadow-red-500/30 transition-all transform hover:-translate-y-1">
                                <span>{{ $event->registration_button_text ?? 'Register Now' }}</span>
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        @endif
                    @endif
                </div>

                {{-- RIGHT COLUMN: The Cover Image --}}
                <div class="order-1 lg:order-2 w-full">
                    <div class="relative w-full aspect-video lg:aspect-[4/3] rounded-2xl md:rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white bg-gray-100 group">
                        @if($event->cover_image)
                            <img src="{{ Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/'.$event->cover_image) }}"
                                 class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition duration-1000">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 font-bold bg-gray-100">
                                <span class="text-4xl md:text-6xl mb-2 md:mb-4">📅</span>
                                <span class="tracking-widest opacity-50 text-[10px] md:text-sm">BU MADYA EVENT</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

                        {{-- Responsive Status Badge --}}
                        <div class="absolute top-4 right-4 md:top-6 md:right-6 z-10">
                            @if($event->isOpen())
                                <span class="px-3 py-1.5 md:px-4 md:py-2 bg-white/95 backdrop-blur text-green-700 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-1.5 md:gap-2 shadow-lg">
                                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-green-500 rounded-full animate-pulse"></span> Open
                                </span>
                            @else
                                <span class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900/95 backdrop-blur text-white text-[10px] md:text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-1.5 md:gap-2 shadow-lg">
                                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-gray-500 rounded-full"></span> Closed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- MAIN CONTENT GRID --}}
        <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-8 md:gap-12">

            {{-- LEFT COLUMN: Sidebar (QR Code) --}}
            <aside class="lg:col-span-4 space-y-8 order-2">
                @if($event->isOpen() && ($event->registration_link || $event->is_internal_rsvp))

                @php
                    $targetUrl = $event->is_internal_rsvp
                        ? route('events.rsvp', $event->slug)
                        : $event->registration_link;
                @endphp

                <div x-data="{
                        showQr: false,
                        targetUrl: '{{ $targetUrl }}',
                        generate() {
                            this.showQr = true;
                            this.$nextTick(() => {
                                const container = this.$refs.qrTarget;
                                if (container.innerHTML === '') {
                                    try {
                                        new QRCode(container, {
                                            text: this.targetUrl,
                                            width: 200,
                                            height: 200,
                                            colorDark : '#1f2937',
                                            colorLight : '#ffffff',
                                            correctLevel : QRCode.CorrectLevel.H,
                                            dotScale: 0.8
                                        });
                                    } catch(e) { console.error(e); }
                                }
                            });
                        },
                        copyLink() {
                            navigator.clipboard.writeText(this.targetUrl);
                            alert('Link copied to clipboard!');
                        }
                    }"
                    class="bg-white/80 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-gray-100 text-center sticky top-24">
                    <h3 class="font-bold text-gray-400 uppercase tracking-widest text-[10px] mb-4">Scan to Register</h3>

                    <div class="flex flex-col items-center justify-center min-h-[140px] mb-4">
                        <div x-show="showQr" x-ref="qrTarget" wire:ignore class="p-2 bg-white rounded-xl shadow-inner border border-gray-100"></div>

                        <button x-show="!showQr" @click="generate()" class="flex flex-col items-center gap-2 group">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-red-50 group-hover:scale-110 transition duration-300 shadow-sm border border-gray-200">
                                <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold text-gray-500 group-hover:text-red-600 uppercase tracking-wide">Show QR Code</span>
                        </button>
                    </div>

                    <div class="flex justify-center gap-2">
                        <button @click="copyLink()" class="text-[10px] md:text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition">
                            Copy Link
                        </button>
                    </div>
                </div>
                @endif
            </aside>

            {{-- RIGHT COLUMN: Description & Evaluation Analytics --}}
            <main class="lg:col-span-8 order-1 space-y-8">
                <div class="bg-white/60 backdrop-blur-sm p-6 md:p-12 rounded-[1.5rem] md:rounded-[2rem] border border-white/50 shadow-sm">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] md:text-sm border-b border-gray-200 pb-3 md:pb-4 mb-4 md:mb-6">About this Event</h3>

                    <div class="prose prose-sm md:prose-lg prose-stone max-w-none
                        prose-headings:font-heading prose-headings:font-black prose-headings:text-gray-900
                        prose-a:text-red-600 hover:prose-a:text-red-700
                        prose-img:rounded-2xl md:prose-img:rounded-3xl prose-img:shadow-xl prose-img:w-full">
                        {!! Str::markdown($event->description) !!}
                    </div>
                </div>

                {{-- POST-EVENT EVALUATION RESULTS --}}
                @if($event->evaluation && $totalResponses > 0 && $overallRating > 0 && !$event->isOpen())
                    <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-10 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]"
                         x-data="{ showChartDrawer: false, chartInitialized: false }"
                         x-init="$watch('showChartDrawer', value => {
                             if(value && !chartInitialized) {
                                 initEvaluationChart();
                                 chartInitialized = true;
                             }
                         })">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                            <div class="flex items-center gap-3">
                                <div class="p-2 md:p-2.5 bg-gray-900 rounded-xl text-white shadow-md">
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-heading text-xl md:text-2xl font-black text-gray-900 tracking-tight">Post-Event Analytics</h3>
                                    <p class="text-[10px] md:text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Based on {{ $totalResponses }} official evaluations</p>
                                </div>
                            </div>

                            @if($canViewEvaluationResults)
                                <a href="{{ route('admin.evaluations.results', $event->evaluation->slug) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-colors border border-red-100">
                                    Admin View &rarr;
                                </a>
                            @endif
                        </div>

                        {{-- High-Contrast Premium Score Card --}}
                        <div class="bg-gray-900 rounded-[1.25rem] md:rounded-[1.5rem] p-6 md:p-10 flex flex-col lg:flex-row items-center justify-between gap-8 mb-10 relative overflow-hidden shadow-2xl">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-red-600/20 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>

                            <div class="relative z-10 text-center lg:text-left w-full lg:w-auto">
                                <h4 class="text-gray-400 font-bold uppercase tracking-widest text-[10px] md:text-xs mb-2">Overall Satisfaction</h4>
                                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 md:gap-6">
                                    <div class="text-6xl md:text-8xl font-black text-red-500 leading-none tracking-tighter drop-shadow-md">
                                        {{ number_format($overallRating, 1) }}
                                    </div>
                                    <div class="flex flex-col items-center sm:items-start gap-1">
                                        <span class="text-sm font-bold text-gray-300">Out of 5.0</span>
                                        <div class="flex text-red-500">
                                            @for($i=1; $i<=5; $i++)
                                                <svg class="w-5 h-5 md:w-6 md:h-6 {{ $i <= round($overallRating) ? 'fill-current' : 'text-gray-700' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative z-10 flex flex-col items-center lg:items-end w-full lg:w-auto border-t lg:border-t-0 lg:border-l border-gray-800 pt-6 lg:pt-0 lg:pl-8">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Performance Rating</span>
                                <div class="text-lg md:text-xl font-black text-white bg-white/10 backdrop-blur-md px-6 py-2.5 rounded-full border border-white/10 shadow-inner">
                                    {{ $overallRating >= 4.5 ? 'Excellent' : ($overallRating >= 4.0 ? 'Very Good' : ($overallRating >= 3.0 ? 'Good' : 'Needs Improvement')) }}
                                </div>

                                <button @click="showChartDrawer = !showChartDrawer" class="mt-6 flex items-center justify-center gap-2 w-full lg:w-auto px-5 py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl text-[10px] md:text-xs font-bold uppercase tracking-widest transition-all shadow-lg hover:shadow-red-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                    <span x-text="showChartDrawer ? 'Hide Radar Chart' : 'View Radar Chart'"></span>
                                </button>
                            </div>
                        </div>

                        {{-- INTERACTIVE CHART DRAWER --}}
                        <div x-show="showChartDrawer" x-collapse x-cloak>
                            <div class="mb-10 bg-gray-50 rounded-[1.25rem] md:rounded-[1.5rem] border border-gray-200 p-4 md:p-8 relative shadow-inner">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 md:mb-6 text-center">Section Radar Analysis</h4>
                                <div class="relative h-[250px] sm:h-[300px] md:h-[450px] w-full flex justify-center">
                                    <canvas id="evaluationRadarChart"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Card-Based Section Breakdown --}}
                        <div>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-3">Detailed Metric Breakdown</h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                                @foreach($groupedLikertResults as $section)
                                    <div class="bg-white border border-gray-100 rounded-2xl p-5 md:p-6 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-5">
                                            <h5 class="text-sm font-black text-gray-900 uppercase tracking-widest leading-tight pr-4">{{ $section['title'] }}</h5>
                                            <div class="shrink-0 bg-gray-900 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                                {{ number_format($section['section_average'], 1) }}
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            @foreach($section['criteria'] as $criteria)
                                                @php
                                                    $percent = ($criteria['score'] / 5) * 100;
                                                    if ($criteria['score'] >= 4.5) {
                                                        $barColor = 'bg-green-500'; $textColor = 'text-green-600'; $shadow = 'rgba(34, 197, 94, 0.4)';
                                                    } elseif ($criteria['score'] >= 3.5) {
                                                        $barColor = 'bg-blue-500'; $textColor = 'text-blue-600'; $shadow = 'rgba(59, 130, 246, 0.4)';
                                                    } elseif ($criteria['score'] >= 2.5) {
                                                        $barColor = 'bg-yellow-400'; $textColor = 'text-yellow-600'; $shadow = 'rgba(250, 204, 21, 0.4)';
                                                    } else {
                                                        $barColor = 'bg-red-500'; $textColor = 'text-red-600'; $shadow = 'rgba(239, 68, 68, 0.4)';
                                                    }
                                                @endphp
                                                <div class="w-full block group/bar">
                                                    <div class="flex justify-between items-end gap-4 mb-1.5 w-full">
                                                        <div class="text-[11px] sm:text-xs font-bold text-gray-600 leading-snug group-hover/bar:text-gray-900 transition-colors">
                                                            {{ html_entity_decode($criteria['label']) }}
                                                        </div>
                                                        <div class="text-[10px] font-black {{ $textColor }} shrink-0">
                                                            {{ number_format($criteria['score'], 1) }}
                                                        </div>
                                                    </div>
                                                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                                        <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000 ease-out"
                                                             style="width: {{ $percent }}%; box-shadow: 0 0 8px {{ $shadow }};"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>

    {{-- FOOTER --}}
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

                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            <ul class="space-y-3 text-gray-400 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                <li class="pt-2 mt-2 border-t border-gray-800">
                    <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-white hover:translate-x-1 transition inline-block">Privacy Policy</a>
                </li>
            </ul>

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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const groupedResults = @json($groupedLikertResults ?? []);

        function initEvaluationChart() {
            const canvas = document.getElementById('evaluationRadarChart');
            if(!canvas) return;

            const ctx = canvas.getContext('2d');
            const labels = [];
            const dataScores = [];

            groupedResults.forEach(section => {
                let title = section.title;
                if(title.length > 18) title = title.substring(0, 18) + '...';
                labels.push(title);
                dataScores.push(section.section_average);
            });

            const gradientFill = ctx.createRadialGradient(
                canvas.width / 2, canvas.height / 2, 0,
                canvas.width / 2, canvas.height / 2, canvas.width / 2
            );
            gradientFill.addColorStop(0, 'rgba(220, 38, 38, 0.4)');
            gradientFill.addColorStop(1, 'rgba(239, 68, 68, 0.05)');

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Section Average',
                        data: dataScores,
                        backgroundColor: gradientFill,
                        borderColor: '#DC2626',
                        borderWidth: 3,
                        tension: 0.3,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#DC2626',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#991B1B',
                        pointHoverBorderColor: '#FFFFFF',
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 2000, easing: 'easeOutQuart' },
                    scales: {
                        r: {
                            min: 0, max: 5, circular: true,
                            angleLines: { color: 'rgba(0, 0, 0, 0.05)', lineWidth: 1 },
                            grid: { color: 'rgba(0, 0, 0, 0.05)', circular: true, lineWidth: 1.5, borderDash: [5, 5] },
                            pointLabels: {
                                font: { family: "'Inter', sans-serif", size: window.innerWidth < 768 ? 9 : 11, weight: '800' },
                                color: '#4B5563', padding: window.innerWidth < 768 ? 8 : 15
                            },
                            ticks: {
                                stepSize: 1, display: true, backdropColor: 'transparent',
                                color: '#9CA3AF', font: { size: 9, weight: 'bold', family: "'Inter', sans-serif" }, z: 10
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleFont: { family: "'Inter', sans-serif", size: 14, weight: 'bold' },
                            bodyFont: { family: "'Inter', sans-serif", size: 13 },
                            padding: 16, cornerRadius: 12, displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return '⭐ Average Score: ' + context.parsed.r.toFixed(2) + ' / 5.00';
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
