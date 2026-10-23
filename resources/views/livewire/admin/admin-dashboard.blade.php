<div class="min-h-screen font-sans text-gray-900 pb-20">

    {{-- 1. ADMIN HEADER (Landing Page Banner Aesthetic) --}}
    <header class="bg-gray-900 rounded-[2rem] p-8 md:p-12 text-white relative overflow-hidden shadow-2xl mb-8">
        {{-- Abstract Tricolor Glow --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-red-600/20 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        <div class="absolute bottom-0 right-32 w-48 h-48 bg-yellow-400/20 rounded-full blur-3xl -mb-10 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="font-heading text-3xl md:text-5xl font-black tracking-tight mb-2 uppercase">
                    SYSTEM <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-orange-400 to-red-500">ADMIN</span>
                </h1>
                <p class="text-gray-400 text-sm md:text-base font-light max-w-lg leading-relaxed">
                    Welcome back. Oversee platform analytics, manage incoming organizational data, and monitor BU MADYA's core advocacy initiatives at a glance.
                </p>
            </div>
            
            {{-- Date Widget --}}
            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-3 rounded-xl border border-white/20 shadow-sm shrink-0">
                <div class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">System Date</p>
                    <p class="text-sm font-black text-white">{{ now()->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Tricolor Stroke at the Bottom --}}
        <div class="absolute bottom-0 left-0 w-full h-2 flex z-20">
            <div class="w-1/3 bg-green-600"></div>
            <div class="w-1/3 bg-yellow-400"></div>
            <div class="w-1/3 bg-red-600"></div>
        </div>
    </header>

    {{-- 2. CRITICAL ACTION OVERVIEW --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        {{-- Card A: Users --}}
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                <svg class="w-24 h-24 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Total Users</p>
                <h3 class="text-4xl font-black text-gray-900">{{ $totalUsers }}</h3>
                <div class="mt-4 flex items-center gap-2">
                    <span class="px-2 py-1 bg-green-50 text-green-600 rounded text-[10px] font-bold uppercase">{{ $orgsCount }} Organizations</span>
                </div>
            </div>
        </div>

        {{-- Card B: Proposals (Actionable - Red Pulse) --}}
        <a href="{{ route('admin.proposals.index') }}" class="bg-gradient-to-br from-red-600 to-red-700 p-6 rounded-[2rem] shadow-lg shadow-red-200 relative overflow-hidden group hover:scale-[1.02] transition block">
            <div class="absolute top-0 right-0 p-4 opacity-20 transform group-hover:rotate-12 transition">
                <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div class="relative z-10 text-white">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-bold uppercase tracking-wider opacity-80">Pending Reviews</p>
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                </div>
                <h3 class="text-4xl font-black">{{ $pendingProposals }}</h3>
                <p class="mt-4 text-xs font-medium opacity-90 underline">Review submissions &rarr;</p>
            </div>
        </a>

        {{-- Card C: Active Projects --}}
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                <svg class="w-24 h-24 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Active Projects</p>
                <h3 class="text-4xl font-black text-gray-900">{{ $activeProjects }}</h3>
                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('admin.projects.index') }}" class="text-[10px] font-bold text-gray-500 hover:text-red-600 uppercase transition tracking-widest">View All Projects &rarr;</a>
                </div>
            </div>
        </div>

    </div>

    {{-- 3. AT A GLANCE: MINI STATS GRID --}}
    <section class="mb-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Stat: Events --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900">{{ number_format($eventsCount) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Events</p>
                </div>
            </div>

            {{-- Stat: Evaluations --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900">{{ number_format($evaluationsCount) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Evaluations</p>
                </div>
            </div>

            {{-- Stat: Frames --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900">{{ number_format($framesCount) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Profile Frames</p>
                </div>
            </div>

            {{-- Stat: Regular Users --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900">{{ number_format($regularUsersCount) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Regular Users</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- LEFT: Action Center (Proposals & Events) --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Pending Proposals Table --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                        <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs">Require Approval</h3>
                    </div>
                    <a href="{{ route('admin.proposals.index') }}" class="text-[10px] font-bold text-red-600 hover:bg-red-50 px-3 py-1 rounded-lg transition uppercase tracking-widest">View All</a>
                </div>
                
                <div class="divide-y divide-gray-50">
                    @forelse($recentProposals as $proposal)
                    <div class="px-8 py-5 flex items-center justify-between hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center font-bold text-xs shrink-0">
                                {{ substr($proposal->user ? $proposal->user->name : $proposal->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-red-600 transition">{{ $proposal->title }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase truncate">{{ $proposal->project_type }}</span>
                                    <span class="text-[10px] text-gray-300">•</span>
                                    <span class="text-[10px] text-gray-400 shrink-0">{{ $proposal->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.proposals.show', $proposal->id) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-[10px] font-bold uppercase tracking-widest rounded-xl shadow-sm hover:border-red-500 hover:text-red-600 transition shrink-0 ml-4">
                            Review
                        </a>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <p class="text-xs text-gray-400 font-medium">No pending proposals. You're all caught up!</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Events Grid --}}
            <div>
                <div class="pl-4 border-l-4 border-yellow-400 mb-6 flex justify-between items-end">
                    <div>
                        <h2 class="font-heading text-xl md:text-2xl font-black text-gray-900 tracking-tight">Recent Events</h2>
                        <p class="text-gray-500 font-medium mt-1 uppercase tracking-widest text-[10px]">Latest Scheduled Activities</p>
                    </div>
                    <a href="{{ route('admin.events.index') }}" class="text-[10px] font-black text-gray-400 hover:text-yellow-600 uppercase tracking-widest transition-colors flex items-center gap-1">
                        View All <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    @forelse($latestEvents as $event)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md hover:-translate-y-1 transition-all group">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0 relative">
                                @if($event->cover_image)
                                    <img src="{{ asset('storage/' . $event->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sm text-gray-900 truncate group-hover:text-red-600 transition-colors">{{ $event->title }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 truncate">
                                    {{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('M d, Y') : 'Date TBA' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-8 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">No events found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT: Newest Users & Projects --}}
        <div class="space-y-8">
            
            {{-- Newest Members --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-6 border-b border-gray-100 pb-2">Newest Members</h3>
                
                <div class="space-y-4">
                    @foreach($newUsers as $user)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                        <span class="text-[9px] font-bold text-gray-300 uppercase shrink-0">
                            {{ $user->created_at->format('M d') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Projects List --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-end border-b border-gray-100 pb-2 mb-6">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs">Recent Projects</h3>
                    <a href="{{ route('admin.projects.index') }}" class="text-[9px] font-bold text-gray-400 hover:text-green-600 uppercase tracking-widest transition">View All</a>
                </div>
                
                <div class="space-y-4">
                    @forelse($latestProjects as $project)
                    <div class="flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                            @if($project->cover_img)
                                <img src="{{ asset('storage/' . $project->cover_img) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-gray-900 truncate group-hover:text-green-600 transition">{{ $project->title }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest {{ strtolower($project->status) === 'completed' ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-500' }}">
                                    {{ $project->status ?? 'Active' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">No projects found.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>