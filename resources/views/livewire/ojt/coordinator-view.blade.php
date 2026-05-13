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
                                <p class="text-xs font-black text-gray-900 truncate">{{ $student->program ?: 'N/A' }}</p>
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
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Physical Time</p>
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
                                <div class="border-t border-gray-100 p-6 md:p-8 bg-[#F8FAFC]/50">
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 xl:gap-12">

                                        {{-- LEFT: Attendance --}}
                                        <div>
                                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                Attendance Records
                                            </h4>

                                            <div class="space-y-3">
                                                @forelse($data['logs'] as $log)
                                                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
                                                        <div class="flex items-center gap-3 min-w-0 w-full sm:w-auto">
                                                            <div class="w-10 h-10 rounded-xl bg-[#F8FAFC] border border-gray-100 flex flex-col items-center justify-center shrink-0">
                                                                <span class="text-[8px] font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($log->log_date)->format('D') }}</span>
                                                                <span class="text-sm font-black text-gray-900 leading-none">{{ \Carbon\Carbon::parse($log->log_date)->format('d') }}</span>
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <p class="font-mono text-[11px] md:text-xs font-bold text-gray-600 truncate">
                                                                    <span class="text-green-600">{{ $log->morning_in ? \Carbon\Carbon::parse($log->morning_in)->format('h:ia') : '--' }}</span>
                                                                    <span class="text-gray-300 mx-1">→</span>
                                                                    <span class="text-red-600">{{ $log->afternoon_out ? \Carbon\Carbon::parse($log->afternoon_out)->format('h:ia') : '--' }}</span>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        {{-- Row-Level Credited & Overtime Toggle --}}
                                                        <div class="bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 text-center sm:text-right shrink-0 w-full sm:w-auto flex flex-col sm:justify-between items-center sm:items-end">
                                                            <div>
                                                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Credited</p>
                                                                <p class="text-sm font-black text-gray-900 leading-none">{{ floor($log->credited_minutes / 60) }}h {{ $log->credited_minutes % 60 }}m</p>
                                                            </div>
                                                            <button wire:click="toggleRowOvertime({{ $log->id }})"
                                                                    class="mt-2 w-full text-[8px] font-black uppercase tracking-widest py-1 px-2 rounded-md border shadow-sm transition-all active:scale-95
                                                                    {{ $log->is_overtime_approved ? 'bg-green-100 text-green-700 border-green-200 hover:bg-green-200' : 'bg-white text-gray-400 border-gray-200 hover:text-green-600 hover:border-green-300' }}">
                                                                {{ $log->is_overtime_approved ? '✓ OT Auth' : '+ Auth OT' }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-2xl">
                                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No attendance logged</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        {{-- RIGHT: Journals --}}
                                        <div>
                                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Journal Entries
                                            </h4>

                                            <div class="border-l-2 border-gray-200 ml-3 pl-5 space-y-5">
                                                @forelse($data['blogs'] as $blog)
                                                    <div class="relative">
                                                        <div class="absolute -left-[35px] top-1.5 w-6 h-6 rounded-full bg-white border-[3px] border-[#F8FAFC] shadow-sm z-10 {{ $blog->type === 'weekly_summary' ? 'bg-red-500 border-red-100' : 'bg-blue-500 border-blue-100' }}"></div>

                                                        <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-shadow min-w-0">
                                                            <div class="flex items-center justify-between mb-2">
                                                                <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-md {{ $blog->type === 'weekly_summary' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                                                                    {{ str_replace('_', ' ', $blog->type) }}
                                                                </span>
                                                                <span class="text-[9px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($blog->report_date)->format('M d') }}</span>
                                                            </div>
                                                            <h5 class="font-black text-sm text-gray-900 mb-1.5 leading-snug break-words">{{ $blog->title }}</h5>
                                                            <p class="text-xs text-gray-500 leading-relaxed break-words line-clamp-3">{{ $blog->content }}</p>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-2xl bg-white">
                                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No entries filed</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm mt-8">
                            <p class="text-lg font-black text-gray-900">No Practicum Data Found</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>
