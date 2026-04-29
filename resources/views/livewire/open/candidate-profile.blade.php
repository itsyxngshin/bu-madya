<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32 animate-fade-in-up">
    
    @php
        // Robust display name fallback
        $candidateName = $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown Candidate';
        $initial = strtoupper(substr($candidateName, 0, 1));
    @endphp

    {{-- TOP NAVIGATION --}}
    <div class="flex items-center justify-between mb-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-red-600 transition-colors group bg-white px-5 py-2.5 rounded-full border border-gray-200 shadow-sm">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Roster
        </a>

        @if($candidate->status === 'approved')
            <div class="flex items-center gap-2 bg-gray-900 px-4 py-2 rounded-full shadow-sm">
                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-white text-[10px] font-black uppercase tracking-widest">Official Ballot Entry</span>
            </div>
        @else
            <span class="px-4 py-2 bg-yellow-50 text-yellow-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-yellow-200 shadow-sm">{{ $candidate->status }}</span>
        @endif
    </div>

    {{-- THE OFFICIAL BALLOT CARD --}}
    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden mb-8 relative">
        
        {{-- Security Ribbon (Tri-Color Aesthetic) --}}
        <div class="h-4 w-full bg-gradient-to-r from-red-600 via-yellow-400 to-green-600"></div>

        {{-- Ballot Header (Perforated Style) --}}
        <div class="bg-gray-50 border-b-[3px] border-dashed border-gray-200 px-6 py-4 flex flex-col sm:flex-row justify-between sm:items-center gap-2 relative">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-mono text-xs font-bold text-gray-500 tracking-widest uppercase">Electoral Dossier</span>
            </div>
            <span class="font-mono text-xs font-bold text-gray-400 tracking-widest uppercase">ID.No {{ str_pad($candidate->id ?? '0', 5, '0', STR_PAD_LEFT) }}</span>
            
            {{-- Semi-circle cutouts to sell the ticket/ballot effect --}}
            <div class="hidden sm:block absolute -bottom-[14px] -left-4 w-6 h-6 bg-gray-100 rounded-full border-t-[3px] border-r-[3px] border-gray-200 rotate-45"></div>
            <div class="hidden sm:block absolute -bottom-[14px] -right-4 w-6 h-6 bg-gray-100 rounded-full border-t-[3px] border-l-[3px] border-gray-200 -rotate-45"></div>
        </div>

        {{-- Main Candidate Profile Area --}}
        <div class="p-6 md:p-12 relative overflow-hidden bg-[url('https://www.transparenttextures.com/patterns/clean-text-pattern.png')] bg-white">
            
            {{-- Large Background Watermark --}}
            <div class="absolute -right-10 -bottom-10 text-gray-50 opacity-40 transform -rotate-12 pointer-events-none select-none">
                <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>

            <div class="flex flex-col md:flex-row gap-8 items-center md:items-start relative z-10">
                
                {{-- Avatar with "Verified Stamp" --}}
                <div class="shrink-0 relative group">
                    <div class="w-36 h-36 md:w-48 md:h-48 rounded-2xl border-4 border-gray-100 shadow-inner bg-white overflow-hidden flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
                        @if($candidate->profile_photo_path)
                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover filter contrast-125 saturate-110">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-6xl font-black text-gray-300 font-mono">
                                {{ $initial }}
                            </div>
                        @endif
                    </div>
                    @if($candidate->status === 'approved')
                        <div class="absolute -bottom-4 -right-4 bg-red-600 text-white text-[10px] font-black px-4 py-1.5 border-[3px] border-white transform -rotate-12 shadow-lg uppercase tracking-widest">
                            Verified
                        </div>
                    @endif
                </div>

                {{-- Candidate Ballot Info --}}
                <div class="flex-1 text-center md:text-left w-full min-w-0">
                    
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] mb-1.5">Position Sought</p>
                    <h2 class="text-2xl md:text-3xl font-black text-orange-600 uppercase tracking-tight mb-6">For {{ $candidate->position->title ?? 'Candidate' }}</h2>
                    
                    {{-- The Voting Oval + Name (Core Ballot Aesthetic) --}}
                    <div class="flex items-center justify-center md:justify-start gap-4 md:gap-5 mb-6">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-4 border-gray-300 shrink-0 bg-white shadow-inner flex items-center justify-center">
                            {{-- Simulated punch-hole/mark on hover --}}
                            <div class="w-5 h-5 rounded-full bg-gray-900 opacity-0 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter leading-none uppercase break-words font-serif">
                            {{ $candidateName }}
                        </h1>
                    </div>
                    
                    {{-- Official Identifiers --}}
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-6 gap-y-3 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">College</span>
                            <span class="text-sm font-black text-gray-800 uppercase">{{ $candidate->college->name ?? 'N/A' }}</span>
                        </div>
                        <div class="w-px h-6 bg-gray-300 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Program</span>
                            <span class="text-sm font-black text-gray-800 uppercase">{{ $candidate->program }}</span>
                        </div>
                        <div class="w-px h-6 bg-gray-300 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Year Level</span>
                            <span class="text-sm font-black text-gray-800 uppercase">{{ $candidate->year_level }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SPLIT DOSSIER GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- LEFT COLUMN: Declaration of Platforms --}}
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-3xl p-6 md:p-10 shadow-sm border border-gray-200">
                
                {{-- Document Title --}}
                <div class="flex items-center gap-4 mb-8 border-b-2 border-gray-900 pb-4">
                    <div class="w-10 h-10 border-2 border-gray-900 flex items-center justify-center rotate-45 shrink-0">
                        <div class="w-8 h-8 bg-green-500 border border-gray-900 -rotate-45 flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight font-serif">General Plan of Action</h3>
                        <p class="font-mono text-[10px] text-gray-500 uppercase tracking-widest">Statement of Intent & Platforms</p>
                    </div>
                </div>

                @if($candidate->platforms && $candidate->platforms->count() > 0)
                    <div class="space-y-6">
                        @foreach($candidate->platforms as $index => $platform)
                            <div class="group relative pl-10 md:pl-12">
                                {{-- Official Numbering --}}
                                <div class="absolute left-0 top-0 w-6 h-6 border-2 border-gray-900 rounded-full flex items-center justify-center font-black text-gray-900 text-xs bg-white group-hover:bg-green-500 group-hover:text-white group-hover:border-green-500 transition-colors z-10">
                                    {{ $index + 1 }}
                                </div>
                                {{-- Connecting Line --}}
                                @if(!$loop->last)
                                    <div class="absolute left-[11px] top-6 bottom-[-24px] w-[2px] bg-gray-200 group-hover:bg-green-200 transition-colors z-0"></div>
                                @endif

                                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 group-hover:border-green-400 group-hover:shadow-md transition-all duration-300">
                                    <h4 class="text-lg font-black text-gray-900 mb-2 uppercase">{{ $platform->title }}</h4>
                                    <p class="text-sm text-gray-700 leading-relaxed font-medium whitespace-pre-line">{{ $platform->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                        <p class="font-mono text-xs text-gray-500 font-bold uppercase tracking-widest">No platforms recorded on file.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT COLUMN: Record of Qualifications --}}
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                
                {{-- Document Title --}}
                <div class="flex items-center gap-4 mb-8 border-b-2 border-gray-900 pb-4">
                    <div class="w-10 h-10 border-2 border-gray-900 flex items-center justify-center rotate-45 shrink-0">
                        <div class="w-8 h-8 bg-yellow-400 border border-gray-900 -rotate-45 flex items-center justify-center text-gray-900 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight font-serif">Qualifications</h3>
                        <p class="font-mono text-[10px] text-gray-500 uppercase tracking-widest">Verified Credentials</p>
                    </div>
                </div>
                
                @if($candidate->credentials && $candidate->credentials->count() > 0)
                    <div class="space-y-4">
                        @foreach($candidate->credentials as $credential)
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl flex items-start gap-3 hover:border-yellow-400 transition-colors">
                                <div class="mt-0.5 text-gray-900">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                                <div>
                                    <p class="font-mono text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">{{ $credential->type }}</p>
                                    <p class="text-sm font-bold text-gray-900 leading-snug">{{ $credential->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="font-mono text-xs text-gray-400 font-bold uppercase tracking-widest">File is empty.</p>
                    </div>
                @endif
            </div>

            {{-- Mock Signature/Verification Block --}}
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200 text-center">
                <p class="font-serif text-sm italic text-gray-500 mb-6">"I hereby certify that the information above is true and accurate to the best of my knowledge."</p>
                <div class="w-3/4 mx-auto border-b border-gray-900 mb-2">
                    <p class="font-[cursive] text-2xl text-gray-800 opacity-60 transform -rotate-3">{{ $candidateName }}</p>
                </div>
                <p class="font-mono text-[9px] font-bold text-gray-400 uppercase tracking-widest">Electronic Signature</p>
            </div>
        </div>

    </div>
</div>