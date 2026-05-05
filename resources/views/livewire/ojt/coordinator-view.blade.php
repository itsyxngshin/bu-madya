<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6">

    {{-- Header Section --}}
    <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8 mb-8 flex flex-col sm:flex-row items-center justify-between text-center sm:text-left gap-6">
        <div>
            <h1 class="text-2xl font-black text-gray-900 mb-1">OJT Practicum Portfolio</h1>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">{{ $student->name }} | Bicol University</p>
        </div>

        <div class="bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-lg border border-gray-800">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Accumulated Time</p>
            <p class="text-4xl font-black font-mono text-red-500">{{ $grandTotalHours }} <span class="text-lg text-white">hrs</span></p>
        </div>
    </div>

    {{-- Weekly Breakdown --}}
    <div class="space-y-4">
        @forelse($weeklyData as $weekKey => $data)
            {{-- Alpine Accordion --}}
            <div x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Accordion Header --}}
                <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-5 bg-gray-50/50 hover:bg-gray-50 transition-colors focus:outline-none">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-black">
                            W{{ $loop->iteration }}
                        </div>
                        <div class="text-left">
                            <h3 class="font-black text-gray-900 text-[15px]">{{ $data['label'] }}</h3>
                            <p class="text-[12px] font-bold text-gray-500">
                                {{ count($data['logs']) }} Shifts • {{ count($data['blogs']) }} Reports
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-black text-gray-900 bg-white border border-gray-200 px-3 py-1 rounded-lg shadow-sm">
                            {{ round($data['total_minutes'] / 60, 2) }} hrs
                        </span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </button>

                {{-- Accordion Body --}}
                <div x-show="expanded" x-collapse>
                    <div class="p-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-8">

                        {{-- Left Column: Time Logs Table --}}
                        <div>
                            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Time Logs
                            </h4>
                            <div class="space-y-2">
                                @forelse($data['logs'] as $log)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100 text-[12px]">
                                        <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($log->log_date)->format('D, M d') }}</span>
                                        <span class="text-gray-500 font-mono text-[11px]">
                                            {{ $log->morning_in ? \Carbon\Carbon::parse($log->morning_in)->format('h:ia') : '--' }} to {{ $log->afternoon_out ? \Carbon\Carbon::parse($log->afternoon_out)->format('h:ia') : '--' }}
                                        </span>
                                        <span class="font-black text-gray-900">{{ floor($log->total_minutes_rendered / 60) }}h {{ $log->total_minutes_rendered % 60 }}m</span>
                                    </div>
                                @empty
                                    <p class="text-[12px] text-gray-400 italic">No time recorded this week.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Right Column: Blog/Reports --}}
                        <div>
                            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                Reports & Learnings
                            </h4>
                            <div class="space-y-3">
                                @forelse($data['blogs'] as $blog)
                                    <div class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded {{ $blog->type === 'weekly_summary' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500' }}">
                                                {{ str_replace('_', ' ', $blog->type) }}
                                            </span>
                                            <span class="text-[10px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($blog->report_date)->format('M d') }}</span>
                                        </div>
                                        <h5 class="font-black text-[13px] text-gray-900 mb-1.5">{{ $blog->title }}</h5>
                                        <p class="text-[12px] text-gray-600 leading-relaxed">{{ $blog->content }}</p>
                                    </div>
                                @empty
                                    <p class="text-[12px] text-gray-400 italic">No reports filed this week.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                <p class="text-gray-400 font-bold">No OJT records found.</p>
            </div>
        @endforelse
    </div>
</div>
