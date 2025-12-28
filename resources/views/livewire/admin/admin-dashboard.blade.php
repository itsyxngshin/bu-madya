<div class="min-h-screen font-sans text-gray-900">

    {{-- 1. ADMIN HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading font-black text-2xl text-gray-800 tracking-tight">
                System <span class="text-red-600">Administration</span>
            </h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
                Platform Overview & Management
            </p>
        </div>

        {{-- Date Widget --}}
        <div class="hidden md:flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">System Date</p>
                <p class="text-xs font-bold text-gray-900">{{ now()->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- 2. STATS OVERVIEW --}}
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
                    <span class="px-2 py-1 bg-green-50 text-green-600 rounded text-[10px] font-bold uppercase">+ New this week</span>
                </div>
            </div>
        </div>

        {{-- Card B: Proposals (Actionable) --}}
        <a href="{{ route('admin.proposals.index') }}" class="bg-gradient-to-br from-red-600 to-red-700 p-6 rounded-[2rem] shadow-lg shadow-red-200 relative overflow-hidden group hover:scale-[1.02] transition">
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

        {{-- Card C: Projects --}}
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                <svg class="w-24 h-24 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Active Projects</p>
                <h3 class="text-4xl font-black text-gray-900">{{ $activeProjects }}</h3>
                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('admin.projects.index') }}" class="text-[10px] font-bold text-gray-500 hover:text-red-600 uppercase transition">View All Projects &rarr;</a>
                </div>
            </div>
        </div>

    </div>

    {{-- 3. CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- LEFT: Action Center (Proposals) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs">Require Approval</h3>
                    <a href="{{ route('admin.proposals.index') }}" class="text-[10px] font-bold text-red-600 hover:bg-red-50 px-3 py-1 rounded-lg transition">View All</a>
                </div>
                
                <div class="divide-y divide-gray-50">
                    @forelse($recentProposals as $proposal)
                    <div class="px-8 py-5 flex items-center justify-between hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center font-bold text-xs">
                                {{ substr($proposal->user ? $proposal->user->name : $proposal->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition">{{ $proposal->title }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $proposal->project_type }}</span>
                                    <span class="text-[10px] text-gray-300">•</span>
                                    <span class="text-[10px] text-gray-400">{{ $proposal->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.proposals.show', $proposal->id) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-[10px] font-bold uppercase rounded-xl shadow-sm hover:border-red-500 hover:text-red-600 transition">
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
        </div>

        {{-- RIGHT: Newest Users --}}
        <div>
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-6 border-b border-gray-100 pb-2">Newest Members</h3>
                
                <div class="space-y-4">
                    @foreach($newUsers as $user)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                        <span class="text-[9px] font-bold text-gray-300 uppercase">
                            {{ $user->created_at->format('M d') }}
                        </span>
                    </div>
                    @endforeach
                </div>

                <button class="w-full mt-6 py-3 border border-dashed border-gray-300 rounded-xl text-xs font-bold text-gray-400 hover:text-gray-600 hover:border-gray-400 transition uppercase">
                    Manage All Users
                </button>
            </div>
        </div>
    </div>
</div>