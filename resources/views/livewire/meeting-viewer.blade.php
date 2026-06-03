<div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 animate-fade-in-up">
    
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Header --}}
        <div class="p-6 md:p-8 bg-gray-900 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="flex items-center gap-2 mb-4 relative z-10">
                <span class="px-3 py-1 bg-red-500 text-white text-[9px] font-black uppercase tracking-widest rounded-md animate-pulse">
                    Live Feed Active
                </span>
                <span class="px-3 py-1 bg-gray-800 text-gray-300 text-[9px] font-black uppercase tracking-widest rounded-md">
                    {{ $meeting->status }}
                </span>
            </div>
            <h1 class="text-2xl md:text-4xl font-black mb-2 relative z-10">{{ $meeting->title }}</h1>
            <p class="text-gray-400 text-xs font-medium relative z-10">Hosted by <span class="font-bold text-gray-200">{{ $meeting->user->name }}</span></p>
        </div>

        <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Main Live Minutes Area --}}
            <div class="lg:col-span-2 space-y-6">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest border-b border-gray-100 pb-3 flex items-center justify-between">
                    Official Minutes
                    <div class="flex items-center gap-1.5 text-[9px] text-gray-400">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Auto-syncing
                    </div>
                </h3>
                
                @if($meeting->agenda)
                    <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 mb-6">
                        <h4 class="text-[10px] font-black text-blue-800 uppercase tracking-widest mb-2">Meeting Agenda</h4>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $meeting->agenda }}</p>
                    </div>
                @endif
                
                {{-- THE LIVE POLLING CONTAINER FOR MINUTES --}}
                <div wire:poll.3s class="prose prose-sm max-w-none text-gray-800 bg-gray-50/80 p-6 sm:p-8 rounded-3xl border border-gray-100 min-h-[400px] whitespace-pre-line shadow-inner">
                    @if($meeting->minutes)
                        {!! nl2br(e($meeting->minutes)) !!}
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 py-12">
                            <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span class="italic text-sm">Waiting for secretariat to begin typing minutes...</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Info & Attendees --}}
            <div class="space-y-6">
                
                {{-- Meeting Details Card --}}
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Meeting Details</h4>
                    <ul class="space-y-3.5 text-xs text-gray-700 font-medium">
                        <li class="flex items-start gap-3">
                            <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                            <div class="pt-0.5">{{ $meeting->meeting_date->format('l, F d, Y') }}</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="p-1.5 bg-orange-50 text-orange-600 rounded-lg shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                            <div class="pt-0.5">{{ $meeting->start_time->format('h:i A') }}</div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="p-1.5 bg-green-50 text-green-600 rounded-lg shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                            <div class="pt-0.5 leading-snug">{{ $meeting->location ?? 'TBA' }}</div>
                        </li>
                    </ul>
                </div>

                {{-- LIVE ATTENDANCE ROLL --}}
                <div class="bg-gray-50/80 p-5 rounded-3xl border border-gray-200">
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4 flex items-center justify-between">
                        Live Attendance
                        {{-- Live Total Counter --}}
                        <span class="bg-white border border-gray-200 shadow-sm text-gray-900 px-2 py-0.5 rounded-lg" wire:poll.3s>
                            {{ $meeting->attendees->count() }} Present
                        </span>
                    </h4>
                    
                    {{-- The Live Polling Container for Attendees --}}
                    <div class="overflow-y-auto custom-scrollbar pr-2 flex-1" wire:poll.3s>
                        <ul class="space-y-3">
                            @forelse($meeting->attendees as $attendee)
                                @php
                                    // 1. Look up the user to grab their real avatar
                                    $user = \App\Models\User::find($attendee->student_id);
                                    
                                    // 2. Generate the fallback UI-Avatar
                                    $fallbackUrl = 'https://ui-avatars.com/api/?name='.urlencode($attendee->name ?? 'Unknown').'&background=f8fafc&color=334155&bold=true';
                                    
                                    // 3. Resolve the actual image (using the accessor we created earlier)
                                    $avatarUrl = $user ? $user->avatar : $fallbackUrl;
                                @endphp

                                <li class="flex items-center gap-3 p-2.5 bg-white border border-gray-100 hover:border-blue-200 rounded-2xl shadow-sm transition-all group">
                                    
                                    {{-- Real User Avatar --}}
                                    <div class="w-10 h-10 rounded-full bg-white border border-gray-100 shadow-sm shrink-0 overflow-hidden group-hover:scale-105 transition-transform">
                                        <img src="{{ $avatarUrl }}" 
                                             onerror="this.onerror=null; this.src='{{ $fallbackUrl }}';" 
                                             class="w-full h-full object-cover bg-gray-50"
                                             alt="{{ $attendee->name }}">
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ $attendee->name }}</p>
                                        <p class="text-[9px] font-mono text-gray-400 mt-0.5">Checked in: {{ $attendee->time_in->format('h:i A') }}</p>
                                    </div>
                                    <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)] shrink-0 mr-2"></div>
                                </li>
                            @empty
                                <li class="flex flex-col items-center justify-center py-12 text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-center block">No check-ins yet.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>