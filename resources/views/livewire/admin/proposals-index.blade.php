<div class="min-h-screen bg-gray-100 font-sans text-gray-900">

    {{-- HEADER --}}
    <div class="bg-white border-b border-gray-200 px-8 py-5 sticky top-0 z-30 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
        <div>
            <h1 class="font-heading font-black text-xl text-gray-800 tracking-tight">
                Proposal <span class="text-red-600">Management</span>
            </h1>
            <p class="text-xs text-gray-500 mt-1">Review and manage incoming project proposals</p>
        </div>

        {{-- FILTERS --}}
        <div class="flex items-center gap-3">
            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="text-xs border-gray-200 rounded-lg focus:ring-red-500 py-2 pl-3 pr-8">
                <option value="">All Statuses</option>
                <option value="pending review">Pending Review</option>
                <option value="approved">Approved</option>
                <option value="returned">Returned</option>
                <option value="rejected">Rejected</option>
                <option value="draft">Draft</option>
            </select>

            {{-- Search Bar --}}
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="w-64 text-xs border-gray-200 rounded-lg focus:ring-red-500 py-2 pl-9"
                    placeholder="Search by title or proponent...">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="max-w-7xl mx-auto px-6 py-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            {{-- TABLE HEADER --}}
            <div class="grid grid-cols-12 gap-4 bg-gray-50 px-6 py-3 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <div class="col-span-5">Proposal Details</div>
                <div class="col-span-3">Proponent</div>
                <div class="col-span-2">Date Submitted</div>
                <div class="col-span-2 text-right">Status</div>
            </div>

            {{-- TABLE BODY --}}
            <div class="divide-y divide-gray-100">
                @forelse($proposals as $proposal)
                <a href="{{ route('admin.proposals.show', $proposal->id) }}" class="grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition group cursor-pointer">
                    
                    {{-- 1. Details --}}
                    <div class="col-span-5">
                        <span class="inline-block text-[9px] font-bold text-yellow-700 bg-yellow-50 border border-yellow-100 px-2 py-0.5 rounded mb-1 uppercase">
                            {{ $proposal->project_type }}
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition truncate pr-4">
                            {{ $proposal->title }}
                        </h3>
                        <p class="text-xs text-gray-500 truncate">{{ Str::limit($proposal->rationale, 80) }}</p>
                    </div>

                    {{-- 2. Proponent --}}
                    <div class="col-span-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ substr($proposal->user ? $proposal->user->name : $proposal->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate">
                                    {{ $proposal->user ? $proposal->user->name : $proposal->name }}
                                </p>
                                <p class="text-[10px] text-gray-400 truncate">
                                    {{ $proposal->proponent_type }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Date --}}
                    <div class="col-span-2">
                        <p class="text-xs text-gray-600 font-medium">{{ $proposal->created_at->format('M d, Y') }}</p>
                        <p class="text-[10px] text-gray-400">{{ $proposal->created_at->diffForHumans() }}</p>
                    </div>

                    {{-- 4. Status Badge --}}
                    <div class="col-span-2 text-right">
                        @if($proposal->status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wide">
                                Approved
                            </span>
                        @elseif($proposal->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 uppercase tracking-wide">
                                Rejected
                            </span>
                        @elseif($proposal->status === 'returned')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 uppercase tracking-wide">
                                Returned
                            </span>
                        @elseif($proposal->status === 'pending review')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 uppercase tracking-wide">
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-wide">
                                {{ $proposal->status }}
                            </span>
                        @endif
                    </div>
                </a>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">No Proposals Found</h3>
                        <p class="text-xs text-gray-500 mt-1">Try adjusting your filters or search query.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($proposals->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $proposals->links() }}
            </div>
            @endif

        </div>
    </div>
</div>