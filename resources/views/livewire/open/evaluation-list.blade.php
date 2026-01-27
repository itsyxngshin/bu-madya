<div class="min-h-screen bg-gray-100 p-6 font-sans text-gray-900">
    
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER & ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">Evaluation Manager</h1>
                <p class="text-sm text-gray-500 mt-1">Manage feedback forms and view responses.</p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                {{-- Search Bar --}}
                <div class="relative w-full md:w-64">
                    <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm transition" placeholder="Search forms...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <a href="{{ route('admin.evaluations.create') }}" class="px-5 py-2 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-orange-600 transition flex items-center gap-2 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create New
                </a>
            </div>
        </div>

        {{-- GRID LAYOUT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @forelse($evaluations as $eval)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-200 relative group hover:-translate-y-1 hover:shadow-md transition duration-300">
                    
                    {{-- Status Badge (Uses ID for internal action - Safe & Fast) --}}
                    <div class="absolute top-6 right-6">
                        <button wire:click="toggleStatus({{ $eval->id }})" 
                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide transition border
                                {{ $eval->is_active ? 'bg-green-100 text-green-700 border-green-200 hover:bg-green-200' : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}">
                            {{ $eval->is_active ? 'Active' : 'Draft' }}
                        </button>
                    </div>

                    {{-- Card Content --}}
                    <div class="mt-2 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center mb-4 shadow-lg shadow-orange-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 line-clamp-1" title="{{ $eval->title }}">
                            {{ $eval->title }}
                        </h3>
                        <p class="text-xs text-gray-500 mb-4 font-mono">
                            Updated {{ $eval->updated_at->diffForHumans() }}
                        </p>
                        
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1 font-bold">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                {{ $eval->responses_count }} Responses
                            </span>
                        </div>
                    </div>

                    {{-- Actions Footer --}}
                    <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                        
                        {{-- [SLUG ADOPTION] Use $eval object so Laravel uses the slug --}}
                        <a href="{{ route('admin.evaluations.results', $eval) }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Results
                        </a>

                        <div class="flex items-center gap-3">
                            {{-- [SLUG ADOPTION] Use $eval object --}}
                            <a href="{{ route('admin.evaluations.edit', $eval) }}" class="text-gray-400 hover:text-gray-900 transition" title="Edit Form">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            
                            {{-- Delete (Uses ID for internal action) --}}
                            <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delete({{ $eval->id }})" class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-400 border-2 border-dashed border-gray-200 rounded-[2rem]">
                    <p>No evaluation forms found.</p>
                </div>
            @endforelse

        </div>
        
        <div class="mt-8">
            {{ $evaluations->links() }}
        </div>

    </div>
</div>