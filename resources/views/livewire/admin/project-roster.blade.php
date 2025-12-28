<div class="min-h-screen font-sans text-gray-900">
    
    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading font-black text-2xl text-gray-800 tracking-tight">
                Project <span class="text-red-600">Management</span>
            </h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
                Monitor and Manage Initiatives
            </p>
        </div>

        <a href="{{ route('projects.create') }}" 
           class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-bold uppercase rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>New Project</span>
        </a>
    </div>

    {{-- TOOLBAR (Search & Filter) --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        
        {{-- Search --}}
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-red-500 focus:border-red-500 sm:text-sm transition duration-150 ease-in-out" 
                   placeholder="Search projects by title or location...">
        </div>

        {{-- Filter --}}
        <div class="flex items-center gap-2 w-full md:w-auto">
            <span class="text-[10px] font-bold uppercase text-gray-400 whitespace-nowrap">Filter Status:</span>
            <select wire:model.live="statusFilter" class="block w-full md:w-48 pl-3 pr-10 py-2 text-xs font-bold border-gray-200 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-xl bg-gray-50">
                <option value="">All Projects</option>
                <option value="Upcoming">Upcoming</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
    </div>

    {{-- PROJECTS LIST --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Table Header --}}
        <div class="grid grid-cols-12 gap-4 px-8 py-4 bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            <div class="col-span-6 md:col-span-5">Project Details</div>
            <div class="hidden md:block md:col-span-3">Status & Date</div>
            <div class="hidden md:block md:col-span-2">Proponents</div>
            <div class="col-span-6 md:col-span-2 text-right">Actions</div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($projects as $project)
            <div class="grid grid-cols-12 gap-4 px-8 py-5 items-center hover:bg-stone-50 transition group">
                
                {{-- 1. Info --}}
                <div class="col-span-6 md:col-span-5 flex items-start gap-4">
                    {{-- Thumbnail --}}
                    <div class="w-12 h-12 rounded-xl bg-gray-200 shrink-0 overflow-hidden border border-gray-100">
                        @if($project->cover_img)
                            <img src="{{ Str::startsWith($project->cover_img, 'http') ? $project->cover_img : asset('storage/' . $project->cover_img) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-[8px]">NO IMG</div>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[9px] font-bold text-gray-400 uppercase bg-gray-100 px-1.5 py-0.5 rounded">
                                {{ $project->category->name ?? 'Uncategorized' }}
                            </span>
                            {{-- Mobile Status Badge --}}
                            <span class="md:hidden text-[9px] font-bold uppercase {{ $project->status === 'Completed' ? 'text-green-600' : ($project->status === 'Ongoing' ? 'text-red-600' : 'text-yellow-600') }}">
                                • {{ $project->status }}
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 leading-tight group-hover:text-red-600 transition">
                            <a href="{{ route('projects.show', $project->slug) }}">{{ $project->title }}</a>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5 truncate max-w-xs">{{ $project->location ?? 'No location specified' }}</p>
                    </div>
                </div>

                {{-- 2. Status & Date --}}
                <div class="hidden md:block md:col-span-3">
                    @if($project->status === 'Completed')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-100 uppercase tracking-wide mb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Completed
                        </span>
                    @elseif($project->status === 'Ongoing')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase tracking-wide mb-1 animate-pulse">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ongoing
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100 uppercase tracking-wide mb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Upcoming
                        </span>
                    @endif
                    
                    <p class="text-[10px] text-gray-400 font-bold uppercase pl-1">
                        {{ $project->implementation_date ? $project->implementation_date->format('M d, Y') : 'Date TBA' }}
                    </p>
                </div>

                {{-- 3. Proponents --}}
                <div class="hidden md:block md:col-span-2">
                    <div class="flex -space-x-2">
                        @foreach($project->proponents->take(3) as $proponent)
                            <div class="w-8 h-8 rounded-full bg-white border-2 border-white flex items-center justify-center text-[10px] font-bold text-gray-600 bg-gray-100 shadow-sm" title="{{ $proponent->name }}">
                                {{ substr($proponent->name, 0, 1) }}
                            </div>
                        @endforeach
                        @if($project->proponents->count() > 3)
                            <div class="w-8 h-8 rounded-full bg-gray-50 border-2 border-white flex items-center justify-center text-[9px] font-bold text-gray-400 shadow-sm">
                                +{{ $project->proponents->count() - 3 }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 4. Actions --}}
                <div class="col-span-6 md:col-span-2 flex items-center justify-end gap-2">
                    <a href="{{ route('projects.edit', $project->slug) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    
                    <button wire:click="deleteProject({{ $project->id }})" 
                            wire:confirm="Are you sure you want to delete this project? This cannot be undone."
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                            title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>

            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">No Projects Found</h3>
                <p class="text-xs text-gray-500 mt-1">Try adjusting your search or filters, or create a new one.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($projects->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
        @endif

    </div>
</div>