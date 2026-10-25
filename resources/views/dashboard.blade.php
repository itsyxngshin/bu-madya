<div>
    {{-- HEADER SLOT (Hidden as we are now using the unified deck header, but kept for compatibility) --}}
    <x-slot name="header">
        <div class="hidden">Director's Dashboard</div>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="animate-fade-in-up max-w-[1600px] mx-auto space-y-8">

        {{-- 1. PREMIUM WELCOME BANNER --}}
        <div class="relative bg-gray-900 rounded-[2rem] p-8 md:p-12 overflow-hidden shadow-2xl border border-gray-800 group">
            {{-- Tricolor Top Border Sweep --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 via-yellow-400 to-green-500"></div>

            {{-- Ambient Tricolor Glows --}}
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-red-600/20 rounded-full blur-3xl pointer-events-none group-hover:bg-red-600/30 transition-all duration-700"></div>
            <div class="absolute right-40 -bottom-20 w-64 h-64 bg-yellow-400/10 rounded-full blur-3xl pointer-events-none group-hover:bg-yellow-400/20 transition-all duration-700"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-green-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 text-white">
                <div>
                    <h2 class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Director Command Center</h2>
                    <h1 class="font-heading text-3xl md:text-5xl font-black text-white mb-3 tracking-tight">
                        Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">{{ auth()->user()->name }}</span>!
                    </h1>
                    <p class="text-gray-300 text-sm max-w-xl leading-relaxed">
                        You have <strong class="text-yellow-400">{{ $pendingProposals }} new proposals</strong> waiting for your review.
                        Monitor your projects and manage public content seamlessly from your unified workspace.
                    </p>
                </div>

                {{-- Quick Action Buttons --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('news.create') }}" class="px-5 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md text-white text-[10px] md:text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        Add News
                    </a>
                    <a href="{{ route('projects.create') }}" class="px-6 py-3.5 rounded-xl bg-white hover:bg-gray-50 text-gray-900 text-[10px] md:text-xs font-bold uppercase tracking-widest shadow-lg transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Project
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. STATS GRID (Tricolor Accented) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

            {{-- Card 1: Pending (Yellow/Action Accent) --}}
            <a href="{{ route('admin.proposals.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all relative overflow-hidden flex flex-col">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-yellow-400 group-hover:w-2 transition-all duration-300"></div>
                <div class="flex justify-between items-start mb-4 pl-3">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    @if($pendingProposals > 0)
                        <span class="text-[9px] font-bold uppercase text-red-600 bg-red-50 px-2.5 py-1 rounded-md animate-pulse">Action Needed</span>
                    @endif
                </div>
                <div class="pl-3 mt-auto">
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter mb-1">{{ number_format($pendingProposals) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pending Proposals</p>
                </div>
            </a>

            {{-- Card 2: Active (Red Accent) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-red-600"></div>
                <div class="flex justify-between items-start mb-4 pl-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                </div>
                <div class="pl-3 mt-auto">
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter mb-1">{{ number_format($activeProjects) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ongoing Projects</p>
                </div>
            </div>

            {{-- Card 3: Partners (Green Accent) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-green-500"></div>
                <div class="flex justify-between items-start mb-4 pl-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="pl-3 mt-auto">
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter mb-1">{{ number_format($totalPartners) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Linkages</p>
                </div>
            </div>

            {{-- Card 4: Users (Gray Accent) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-gray-800"></div>
                <div class="flex justify-between items-start mb-4 pl-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-800 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
                <div class="pl-3 mt-auto">
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter mb-1">{{ number_format($totalUsers) }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Registered Users</p>
                </div>
            </div>
        </div>

        {{-- 3. CALENDAR SECTION --}}
        <div class="grid grid-cols-1">
            <livewire:events-calendar />
        </div>

        {{-- 4. RECENT ACTIVITY GRIDS --}}
        <div class="grid lg:grid-cols-3 gap-6 md:gap-8">

            {{-- Left: Pending Reviews List --}}
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                    <h3 class="font-heading font-black text-lg text-gray-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span> Needs Review
                    </h3>
                    <a href="{{ route('admin.proposals.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-gray-900 hover:underline">View All Proposals</a>
                </div>

                <div class="space-y-4 flex-1">
                    @forelse($recentProposals as $proposal)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 border border-transparent hover:border-gray-200 transition-all group gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center font-bold text-xs text-gray-500 shrink-0 group-hover:border-red-200 group-hover:text-red-600 transition-colors">
                                {{ substr($proposal->user ? $proposal->user->name : $proposal->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition">{{ $proposal->title }}</h4>
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mt-0.5">
                                    {{ $proposal->proponent_type }} &bull; {{ $proposal->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.proposals.show', $proposal->id) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-[10px] font-bold uppercase rounded-lg shadow-sm hover:border-red-300 hover:text-red-600 transition-colors text-center whitespace-nowrap">
                            Review &rarr;
                        </a>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center text-green-500 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900">All Caught Up!</p>
                        <p class="text-xs text-gray-500 mt-1">No pending proposals right now.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Right: Upcoming Projects (Simple Dark Mode List) --}}
            <div class="bg-gray-900 text-white rounded-3xl shadow-lg p-6 md:p-8 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 w-40 h-40 bg-red-600 rounded-full blur-3xl opacity-20 -mr-10 -mt-10 pointer-events-none"></div>

                <h3 class="font-heading font-black text-lg text-white mb-6 pb-4 border-b border-gray-800 relative z-10 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Next Up
                </h3>

                <div class="space-y-3 relative z-10 flex-1">
                    @forelse($upcomingProjects as $proj)
                    <div class="p-4 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                        <h4 class="text-sm font-bold text-white mb-1.5 leading-snug truncate">{{ $proj->title }}</h4>
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                Starts {{ $proj->implementation_date ? \Carbon\Carbon::parse($proj->implementation_date)->format('M d') : 'TBA' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-500 text-xs italic">
                        No upcoming projects found.
                    </div>
                    @endforelse
                </div>

                <a href="{{ route('projects.index') }}" class="block mt-6 text-center text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-white transition-colors relative z-10">
                    View Full List &rarr;
                </a>
            </div>
        </div>

        {{-- 5. SECTION: MY LED PROJECTS --}}
        <div>
            <h3 class="font-heading font-black text-xl text-gray-900 mb-6 flex items-center gap-3 pl-2 border-l-4 border-red-600">
                My Led Projects
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($myProjects as $project)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow group">

                    <div>
                        {{-- Header: Status & Category --}}
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-widest
                                {{ $project->status === 'Completed' ? 'bg-green-50 text-green-700 border border-green-100' :
                                  ($project->status === 'Ongoing' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-yellow-50 text-yellow-700 border border-yellow-100') }}">
                                {{ $project->status }}
                            </span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                {{ $project->category->name ?? 'General' }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h4 class="text-lg font-black text-gray-900 leading-tight mb-2 group-hover:text-red-600 transition-colors">
                            <a href="{{ route('projects.show', $project->slug) }}">
                                {{ $project->title }}
                            </a>
                        </h4>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-6 leading-relaxed">
                            {{ $project->description }}
                        </p>
                    </div>

                    {{-- Footer: Meta --}}
                    <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-600">
                                {{ $project->implementation_date ? \Carbon\Carbon::parse($project->implementation_date)->format('M d, Y') : 'Date TBA' }}
                            </span>
                        </div>

                        {{-- "Manage" Link --}}
                        <a href="{{ route('projects.edit', $project->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-red-600 hover:text-red-700 hover:underline flex items-center gap-1 bg-red-50 px-3 py-1.5 rounded-lg group-hover:bg-red-100 transition-colors">
                            Manage <span>&rarr;</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 bg-white rounded-3xl p-10 text-center border border-dashed border-gray-200 shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-base font-black text-gray-900 mb-1">No Led Projects Yet</h3>
                    <p class="text-sm text-gray-500">You haven't been listed as a proponent for any projects.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
