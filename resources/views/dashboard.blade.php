<div>
    {{-- HEADER SLOT --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                
            </div>
        </div>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="py-6 md:py-12 bg-stone-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto space-y-6 md:space-y-8">
            {{-- 1. WELCOME BANNER --}}
            <div class="relative bg-gray-900 rounded-[2rem] p-6 md:p-8 overflow-hidden shadow-xl flex flex-col md:flex-row md:items-center justify-between text-white gap-6">
                {{-- Background Effects --}}
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-800 to-red-900 opacity-90 pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 via-yellow-400 to-red-600"></div>

                <div class="relative z-10">
                    <h1 class="text-2xl md:text-3xl font-black font-heading mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-300 text-sm max-w-xl leading-relaxed">
                        You have <strong class="text-yellow-400">{{ $pendingProposals }} new proposals</strong> waiting for your review. 
                        Check the "Proposals" section to take action.
                    </p>
                </div>
                
                <div class="relative z-10 flex flex-wrap gap-3">
                    {{-- 1. Create News --}}
                    <a href="{{ route('news.create') }}" 
                       class="px-4 py-3 bg-white/10 border border-white/20 text-white text-[10px] md:text-xs font-bold uppercase rounded-xl hover:bg-white/20 transition flex items-center gap-2 backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        <span>Add News</span>
                    </a>

                    {{-- 2. Create Project --}}
                    <a href="{{ route('projects.create') }}" 
                       class="px-5 py-3 bg-white text-gray-900 text-[10px] md:text-xs font-black uppercase rounded-xl shadow-lg hover:scale-105 transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Create Project</span>
                    </a>
                </div>
            </div>

            {{-- 2. STATS GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                
                {{-- Card 1: Pending --}}
                <a href="{{ route('admin.proposals.index') }}" class="group bg-white p-5 md:p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-yellow-400"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center group-hover:bg-yellow-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-[9px] font-bold uppercase text-gray-400 bg-gray-50 px-2 py-1 rounded">Action Needed</span>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-1">{{ $pendingProposals }}</h3>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wide">Pending Proposals</p>
                </a>

                {{-- Card 2: Active --}}
                <div class="bg-white p-5 md:p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-red-600"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-1">{{ $activeProjects }}</h3>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wide">Ongoing Projects</p>
                </div>

                {{-- Card 3: Partners --}}
                <div class="bg-white p-5 md:p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-green-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-1">{{ $totalPartners }}</h3>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wide">Linkages</p>
                </div>

                {{-- Card 4: Users --}}
                <div class="bg-white p-5 md:p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gray-400"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-1">{{ $totalUsers }}</h3>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wide">Registered Users</p>
                </div>
            </div>

            {{-- 3. RECENT ACTIVITY --}}
            <div class="grid lg:grid-cols-3 gap-6 md:gap-8">
                
                {{-- Left: Pending Reviews List --}}
                <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs">Waiting for Review</h3>
                        <a href="{{ route('admin.proposals.index') }}" class="text-[10px] font-bold text-red-600 hover:underline uppercase">View All &rarr;</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentProposals as $proposal)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition group gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center font-bold text-xs text-gray-500 shrink-0">
                                    {{ substr($proposal->user ? $proposal->user->name : $proposal->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition">{{ $proposal->title }}</h4>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold">{{ $proposal->proponent_type }} • {{ $proposal->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.proposals.show', $proposal->id) }}" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 text-[10px] font-bold uppercase rounded-lg shadow-sm hover:border-red-300 hover:text-red-600 transition text-center whitespace-nowrap">
                                Review
                            </a>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400 text-xs italic">
                            No pending proposals at the moment.
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Right: Upcoming Projects --}}
                <div class="bg-gray-900 text-white rounded-[2rem] shadow-lg p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-600 rounded-full blur-3xl opacity-20 -mr-10 -mt-10 pointer-events-none"></div>
                    <h3 class="font-bold text-white uppercase tracking-widest text-xs mb-6 relative z-10">Upcoming Projects</h3>
                    
                    <div class="space-y-4 relative z-10">
                        @forelse($upcomingProjects as $proj)
                        <div class="p-4 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/5 hover:bg-white/15 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-bold text-white mb-1">{{ $proj->title }}</h4>
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></div>
                                        <p class="text-[10px] text-gray-300 font-bold uppercase">{{ $proj->implementation_date ? $proj->implementation_date->format('M d') : 'TBA' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 text-xs italic">
                            No upcoming projects found.
                        </div>
                        @endforelse
                    </div>
                    <a href="{{ route('projects.index') }}" class="block mt-6 text-center text-[10px] font-bold uppercase text-gray-400 hover:text-white transition relative z-10">
                        View Calendar &rarr;
                    </a>
                </div>
            </div>

            {{-- 4. SECTION: MY LED PROJECTS --}}
            <div>
                <h3 class="font-heading font-black text-lg md:text-xl text-gray-800 mb-6 flex items-center gap-3">
                    <span class="w-2 h-6 md:h-8 bg-red-600 rounded-full"></span>
                    My Led Projects
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($myProjects as $project)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition group">
                        
                        <div>
                            {{-- Header: Status & Category --}}
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide 
                                    {{ $project->status === 'Completed' ? 'bg-green-100 text-green-700' : 
                                      ($project->status === 'Ongoing' ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $project->status }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">
                                    {{ $project->category->name ?? 'General' }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h4 class="text-lg font-black text-gray-900 leading-tight mb-2 group-hover:text-red-600 transition">
                                <a href="{{ route('projects.show', $project->slug) }}">
                                    {{ $project->title }}
                                </a>
                            </h4>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-4">
                                {{ $project->description }}
                            </p>
                        </div>

                        {{-- Footer: Meta --}}
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs font-bold text-gray-600">
                                    {{ $project->implementation_date ? $project->implementation_date->format('M d, Y') : 'Date TBA' }}
                                </span>
                            </div>
                            
                            {{-- "Manage" Link --}}
                            <a href="{{ route('projects.edit', $project->slug) }}" class="text-[10px] font-bold uppercase text-red-600 hover:underline flex items-center gap-1">
                                Manage Project <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-1 md:col-span-2 bg-white rounded-3xl p-8 text-center border border-dashed border-gray-300">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">No Projects Assigned</h3>
                        <p class="text-xs text-gray-500 mt-1">You haven't been listed as a proponent for any projects yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>