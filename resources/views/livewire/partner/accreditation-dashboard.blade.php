<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8 animate-fade-in-up">

    {{-- HEADER WELCOME --}}
    <div>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Organization Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back, <span class="font-bold text-gray-900">{{ $organization ? $organization->name : Auth::user()->name }}</span>.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- LEFT COLUMN: APPLICATION TRACKER --}}
        <div class="lg:col-span-8 space-y-8">
            
            @if(!$organization || !$application)
                {{-- STATE 1: No Application Yet --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-[2rem] p-8 md:p-12 text-white shadow-lg shadow-blue-900/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                    
                    <div class="relative z-10">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-[10px] font-black uppercase tracking-widest border border-white/20 mb-6 inline-block">Action Required</span>
                        <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-4 leading-tight">Start Your Accreditation<br>for A.Y. 2026-2027</h2>
                        <p class="text-blue-100 max-w-lg mb-8 leading-relaxed">You have not submitted an accreditation application yet. Ensure you have your bank details, CBL, and officer roster ready before starting.</p>
                        
                        <a href="{{ route('accreditation.apply') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-blue-900 text-sm font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-gray-50 transition active:scale-95">
                            Begin Application <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            @else
                {{-- STATE 2: Application Exists (Tracker) --}}
                <div class="bg-white rounded-[2rem] border border-gray-100 p-6 md:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Application Status</h2>
                            <p class="text-xs text-gray-500 mt-1 font-bold">A.Y. {{ $application->academicYear->year }} • {{ ucfirst($application->application_type) }}</p>
                        </div>
                        
                        @if($application->status === 'returned' || $application->status === 'draft')
                            <a href="{{ route('accreditation.apply') }}" class="px-5 py-2 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-sm hover:bg-blue-700 transition">Edit Application</a>
                        @endif
                    </div>

                    {{-- Dynamic Status Banner --}}
                    @if($application->status === 'returned')
                        <div class="mb-8 p-5 bg-red-50 border border-red-200 rounded-2xl flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-red-900 text-sm uppercase tracking-widest mb-1">Action Required: Revisions Needed</h3>
                                <p class="text-sm text-red-800 leading-relaxed mb-3">OSAS has reviewed your application and returned it for corrections. Please address the remarks below and resubmit.</p>
                                <div class="bg-white p-4 rounded-xl border border-red-100 text-sm text-gray-700 italic">
                                    "{{ $application->admin_remarks }}"
                                </div>
                            </div>
                        </div>
                    @elseif($application->status === 'approved')
                        <div class="mb-8 p-5 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-green-900 text-sm uppercase tracking-widest mb-0.5">Officially Accredited</h3>
                                <p class="text-sm text-green-800">Congratulations! Your organization is fully recognized for this academic year.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Visual Pipeline --}}
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t-2 border-gray-100"></div>
                        </div>
                        <div class="relative flex justify-between">
                            
                            {{-- Step 1: Draft/Submitted --}}
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ in_array($application->status, ['pending', 'under_review', 'approved']) ? 'bg-blue-600 text-white' : 'bg-white border-2 border-gray-300 text-gray-400' }} shadow-sm z-10 relative">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest {{ in_array($application->status, ['pending', 'under_review', 'approved']) ? 'text-blue-600' : 'text-gray-400' }}">Submitted</span>
                            </div>

                            {{-- Step 2: Under Review --}}
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ in_array($application->status, ['under_review', 'approved', 'returned']) ? 'bg-blue-600 text-white' : 'bg-white border-2 border-gray-300 text-gray-400' }} shadow-sm z-10 relative">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest {{ in_array($application->status, ['under_review', 'approved', 'returned']) ? 'text-blue-600' : 'text-gray-400' }}">Under Review</span>
                            </div>

                            {{-- Step 3: Final Verdict --}}
                            <div class="flex flex-col items-center gap-2">
                                @if($application->status === 'approved')
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-green-500 text-white shadow-sm z-10 relative">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-green-600">Approved</span>
                                @elseif($application->status === 'returned')
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-red-500 text-white shadow-sm z-10 relative">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Returned</span>
                                @else
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-white border-2 border-gray-300 text-gray-400 shadow-sm z-10 relative">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Final Verdict</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: ALERTS & DEADLINES --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Deadlines Box --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Important Deadlines
                </h3>
                
                @forelse($activeDeadlines as $dl)
                    <div class="p-3 bg-red-50/50 border border-red-100 rounded-xl mb-3 last:mb-0">
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">{{ ucfirst($dl->application_type) }} • A.Y. {{ $dl->academicYear->year }}</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($dl->end_date)->format('F d, Y') }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">Closes at {{ \Carbon\Carbon::parse($dl->end_date)->format('h:i A') }}</p>
                    </div>
                @empty
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center py-4">No active deadlines</p>
                @endforelse
            </div>

            {{-- Advisories Box --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    OSAS Advisories
                </h3>
                
                <div class="space-y-4">
                    @forelse($advisories as $advisory)
                        <div class="border-l-2 pl-3 pb-4 border-gray-100 last:pb-0 last:border-0 relative">
                            {{-- Color-coded dot based on type --}}
                            <div class="absolute -left-[5px] top-1.5 w-2 h-2 rounded-full 
                                {{ $advisory->type === 'urgent' ? 'bg-red-500' : ($advisory->type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500') }}">
                            </div>
                            
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $advisory->created_at->format('M d, Y') }}</p>
                            <h4 class="text-sm font-bold text-gray-900 leading-snug mb-1">{{ $advisory->title }}</h4>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $advisory->message }}</p>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center py-4">No recent advisories</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>