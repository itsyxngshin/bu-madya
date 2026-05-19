<div class="min-h-screen bg-[#F8FAFC] pb-32 font-sans animate-fade-in-up">

    {{-- ========================================== --}}
    {{-- FULL WIDTH BANNER                          --}}
    {{-- ========================================== --}}
    <div class="w-full h-48 md:h-64 bg-gradient-to-r from-red-600 via-yellow-500 to-green-500 relative">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/crosses.png')] mix-blend-overlay"></div>

        <div class="absolute top-6 left-4 sm:left-6 lg:left-8">
            <a href="#" onclick="history.back(); return false;" class="inline-flex items-center gap-2 text-xs font-black text-gray-900 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm hover:bg-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MAIN CONTENT GRID                          --}}
    {{-- ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 md:-mt-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- LEFT COLUMN: FLOATING PROFILE CARD --}}
            <div class="lg:col-span-4">
                <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8 text-center relative">

                    <div class="w-32 h-32 md:w-36 md:h-36 mx-auto rounded-full overflow-hidden border-[6px] md:border-8 border-[#F8FAFC] shadow-sm -mt-20 md:-mt-24 bg-white relative z-10">
                        @if($student->profile_photo_path)
                            <img src="{{ asset('storage/'.$student->profile_photo_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-black text-red-600 text-5xl bg-red-50">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h1 class="mt-4 text-2xl font-black text-gray-900 tracking-tight leading-tight">
                        {{ $student->name }}
                    </h1>
                    <div class="mt-2 inline-flex items-center justify-center px-4 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-100">
                        Practicum Intern
                    </div>

                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-4 p-4 bg-[#F8FAFC] rounded-2xl border border-gray-100 text-left hover:border-gray-200 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center shrink-0 text-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Program / Course</p>
                                <p class="text-xs font-black text-gray-900 truncate">{{ $student->profile->course ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 bg-[#F8FAFC] rounded-2xl border border-gray-100 text-left hover:border-gray-200 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center shrink-0 text-orange-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Institution</p>
                                <p class="text-xs font-black text-gray-900 truncate">Bicol University</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 bg-[#F8FAFC] rounded-2xl border border-gray-100 text-left hover:border-gray-200 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center shrink-0 text-green-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Email Contact</p>
                                <p class="text-xs font-black text-gray-900 truncate">{{ $student->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: DASHBOARD & TIMELINE --}}
            <div class="lg:col-span-8 lg:mt-32 space-y-8">

                {{-- Section Header --}}
                <div class="flex items-center gap-3 ml-2">
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 shrink-0 border border-red-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900">Dashboard & Portfolio</h2>
                </div>

                {{-- Master Stats --}}
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between w-full text-center sm:text-left gap-6">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Credited Hours</p>
                            <p class="text-5xl md:text-6xl font-black font-mono text-gray-900 leading-none tracking-tighter">
                                {{ $grandTotalHours }}<span class="text-xl text-gray-400 ml-1">hrs</span>
                            </p>
                        </div>
                        <div class="sm:mb-2 sm:text-right">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Physical Time (Raw)</p>
                            <p class="text-xl font-black text-gray-500">{{ $grandTotalRawHours }} hrs</p>
                        </div>
                    </div>
                </div>

                {{-- Weekly Sprints Timeline --}}
                <div class="space-y-4">
                    @forelse($weeklyData as $weekKey => $data)
                        <div x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">

                            {{-- Week Header --}}
                            <button @click="expanded = !expanded" class="w-full flex flex-col sm:flex-row items-center justify-between p-6 bg-transparent transition-colors focus:outline-none gap-4">
                                <div class="flex items-center gap-4 w-full sm:w-auto">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-50 to-orange-50 border border-red-100 text-red-600 flex items-center justify-center font-black text-lg shrink-0">
                                        W{{ $loop->iteration }}
                                    </div>
                                    <div class="text-left">
                                        <h3 class="font-black text-gray-900 text-lg leading-tight">{{ $data['label'] }}</h3>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">
                                            {{ count($data['logs']) }} Shifts • {{ count($data['blogs']) }} Reports
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 border-gray-100 pt-4 sm:pt-0">
                                    <div class="text-right">
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Credited</p>
                                        <span class="text-lg font-black text-gray-900 leading-none">
                                            {{ round($data['total_credited'] / 60, 2) }} <span class="text-xs text-gray-400">hrs</span>
                                        </span>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-[#F8FAFC] border border-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                                        <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </button>

                            {{-- Week Body --}}
                            <div x-show="expanded" x-collapse>
                                <div class="border-t border-gray-100 p-6 md:p-8 bg-[#F8FAFC]/50 space-y-10">

                                    {{-- 1. ATTENDANCE GRID (Horizontal Chronological Row) --}}
                                    <div>
                                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Attendance Log
                                        </h4>

                                        @if(count($data['logs']) > 0)
                                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                                @foreach($data['logs'] as $log)
                                                    <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm flex flex-col justify-between hover:border-blue-300 transition-colors">
                                                        <div class="flex justify-between items-center mb-3">
                                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($log->log_date)->format('D') }}</span>
                                                            <span class="text-xs font-black text-gray-900">{{ \Carbon\Carbon::parse($log->log_date)->format('M d') }}</span>
                                                        </div>
                                                        <div class="text-[9px] font-mono font-bold text-gray-500 mb-3 text-center bg-gray-50 rounded py-1 border border-gray-100">
                                                            {{ $log->morning_in ? \Carbon\Carbon::parse($log->morning_in)->format('h:ia') : '--' }}
                                                            <span class="text-gray-300 mx-0.5">→</span>
                                                            {{ $log->afternoon_out ? \Carbon\Carbon::parse($log->afternoon_out)->format('h:ia') : '--' }}
                                                        </div>
                                                        <div class="flex justify-between items-end">
                                                            <div>
                                                                <span class="block text-[8px] font-bold text-gray-400 uppercase leading-none mb-0.5">Credited</span>
                                                                <span class="text-sm font-black text-green-600 leading-none">{{ floor($log->credited_minutes / 60) }}h {{ $log->credited_minutes % 60 }}m</span>
                                                            </div>
                                                            @if($log->is_overtime_approved)
                                                                <span class="text-[8px] font-black bg-yellow-100 text-yellow-700 border border-yellow-200 px-1.5 py-0.5 rounded uppercase tracking-wider shadow-sm">OT</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-2xl bg-white">
                                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No attendance logged</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 2. FULL WIDTH JOURNALS --}}
                                    <div>
                                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Journal Entries
                                        </h4>

                                        <div class="space-y-4">
                                            @forelse($data['blogs'] as $blog)
                                                @if($blog->type === 'weekly_summary')
                                                    {{-- HIGHLIGHTED WEEKLY SUMMARY --}}
                                                    <div class="bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 p-6 rounded-2xl shadow-sm relative overflow-hidden">
                                                        <div class="absolute top-0 right-0 bg-red-600 text-white text-[8px] font-black uppercase tracking-widest px-4 py-1.5 rounded-bl-xl shadow-sm">Weekly Highlight</div>
                                                        
                                                        <div class="flex items-center justify-between mb-3 mt-1">
                                                            <h5 class="font-black text-lg text-gray-900 leading-snug tracking-tight">{{ $blog->title }}</h5>
                                                        </div>
                                                        <span class="inline-block mb-3 text-[10px] font-bold text-red-600/80">{{ \Carbon\Carbon::parse($blog->report_date)->format('M d, Y') }}</span>
                                                        
                                                        {{-- Weekly Summary Read More Toggle --}}
                                                        <div x-data="{ expandedText: false }">
                                                            <p class="text-sm text-gray-700 leading-relaxed font-medium whitespace-pre-line transition-all" 
                                                               :class="expandedText ? '' : 'line-clamp-3'">
                                                                {{ $blog->content }}
                                                            </p>
                                                            @if(strlen($blog->content) > 150)
                                                                <button @click="expandedText = !expandedText" 
                                                                        class="text-[10px] font-black text-red-600 hover:text-red-800 uppercase tracking-widest mt-2 focus:outline-none transition-colors">
                                                                    <span x-text="expandedText ? 'Show Less' : 'Read More'"></span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- STANDARD DAILY LOG --}}
                                                    <div class="bg-white border border-gray-200 p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                                                            <h5 class="font-black text-base text-gray-900 leading-snug tracking-tight">{{ $blog->title }}</h5>
                                                            <div class="flex items-center gap-3">
                                                                <span class="text-[9px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($blog->report_date)->format('M d, Y') }}</span>
                                                                <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded bg-blue-50 text-blue-600 border border-blue-100">
                                                                    {{ str_replace('_', ' ', $blog->type) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        {{-- Daily Log Read More Toggle --}}
                                                        <div x-data="{ expandedText: false }">
                                                            <p class="text-xs md:text-sm text-gray-500 leading-relaxed whitespace-pre-line transition-all" 
                                                               :class="expandedText ? '' : 'line-clamp-3'">
                                                                {{ $blog->content }}
                                                            </p>
                                                            @if(strlen($blog->content) > 150)
                                                                <button @click="expandedText = !expandedText" 
                                                                        class="text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-widest mt-2 focus:outline-none transition-colors">
                                                                    <span x-text="expandedText ? 'Show Less' : 'Read More'"></span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @empty
                                                <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-2xl bg-white">
                                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No entries filed</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- 3. WEEKLY MEDIA COLLAGE --}}
                                    <div class="pt-6 border-t border-gray-200/60">
                                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Media Documentation
                                        </h4>
                                        
                                        @if(count($data['photos'] ?? []) > 0)
                                            <div class="grid gap-2 {{ count($data['photos']) === 1 ? 'grid-cols-1' : (count($data['photos']) === 2 ? 'grid-cols-2' : 'grid-cols-3') }}">
                                                @foreach($data['photos'] as $index => $photo)
                                                    <div class="relative rounded-2xl overflow-hidden group border border-gray-200 shadow-sm bg-gray-50
                                                                {{ (count($data['photos']) > 2 && $index === 0) ? 'col-span-2 row-span-2 aspect-video md:aspect-auto md:h-72' : 'aspect-square md:h-36' }}">
                                                        
                                                        <img src="{{ $photo['url'] }}" alt="{{ $photo['title'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                                                        
                                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5">
                                                            <span class="text-[9px] font-black text-yellow-400 uppercase tracking-widest mb-1">{{ $photo['date'] }}</span>
                                                            <h4 class="text-white font-bold text-xs md:text-sm truncate leading-tight">{{ $photo['title'] }}</h4>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="h-32 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 bg-white">
                                                <svg class="w-6 h-6 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">No photos submitted</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm mt-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <p class="text-lg font-black text-gray-900">No Practicum Data Found</p>
                            <p class="text-sm text-gray-500 mt-1">The intern has not logged any time or entries yet.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>