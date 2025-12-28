<div class="min-h-screen font-sans text-gray-900 pb-20 px-3 md:px-0">
    
    {{-- PAGE HEADER: Compact on Mobile --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4 md:mb-8 pt-4">
        <div>
            <h1 class="font-heading font-black text-xl md:text-3xl text-gray-800 tracking-tight leading-none">
                Linkage <span class="text-red-600">Network</span>
            </h1>
            <p class="text-[10px] md:text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
                Partners & Activities
            </p>
        </div>

        <button wire:click="create" 
           class="w-full md:w-auto px-4 py-2.5 bg-gray-900 text-white text-[10px] md:text-xs font-bold uppercase rounded-xl shadow-lg hover:bg-red-600 transition flex items-center justify-center gap-2 active:scale-95">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Partner</span>
        </button>
    </div>

    {{-- TOOLBAR: Stacked & Tighter --}}
    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 mb-4 md:mb-6 flex flex-col md:flex-row gap-2 items-center justify-between">
        {{-- Search --}}
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..." 
                   class="w-full pl-9 pr-3 py-2 border-gray-200 rounded-lg text-xs md:text-sm focus:ring-red-500 focus:border-red-500">
        </div>

        {{-- Filters: Side-by-Side on Mobile --}}
        <div class="grid grid-cols-2 gap-2 w-full md:w-auto">
            <select wire:model.live="typeFilter" class="block w-full pl-2 pr-6 py-2 text-[10px] md:text-xs font-bold border-gray-200 rounded-lg bg-gray-50 focus:ring-red-500">
                <option value="">All Types</option>
                @foreach($this->types as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
            </select>
            <select wire:model.live="statusFilter" class="block w-full pl-2 pr-6 py-2 text-[10px] md:text-xs font-bold border-gray-200 rounded-lg bg-gray-50 focus:ring-red-500">
                <option value="">All Statuses</option>
                @foreach($this->statuses as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
            </select>
        </div>
    </div>

    {{-- LINKAGE GRID: Compact Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        @forelse($linkages as $linkage)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition group overflow-hidden flex flex-col h-full">
            
            {{-- Colored Top Border --}}
            <div class="h-1 w-full" style="background-color: {{ $linkage->type->color ?? '#e5e7eb' }}"></div>
            
            <div class="p-4 flex-1">
                {{-- Top Row: Logo & Status --}}
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                        @if($linkage->logo_path)
                            <img src="{{ asset('storage/'.$linkage->logo_path) }}" class="w-full h-full object-contain">
                        @else
                            <span class="text-[10px] font-bold text-gray-400">{{ substr($linkage->name, 0, 2) }}</span>
                        @endif
                    </div>
                    
                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded border ml-2 text-center" 
                          style="background-color: {{ $linkage->status->color ?? '#f3f4f6' }}20; color: {{ $linkage->status->color ?? '#374151' }}; border-color: {{ $linkage->status->color ?? '#e5e7eb' }}40;">
                        {{ $linkage->status->name ?? 'N/A' }}
                    </span>
                </div>

                {{-- Name & Acronym --}}
                <h3 class="font-bold text-gray-900 text-base leading-tight mb-0.5 line-clamp-1">{{ $linkage->name }}</h3>
                <p class="text-[10px] text-gray-500 font-medium uppercase tracking-wide mb-3 line-clamp-1">
                    {{ $linkage->acronym }} • {{ $linkage->type->name ?? 'N/A' }}
                </p>
                
                {{-- STATISTICS / METADATA: Horizontal Grid (Fixed Placement) --}}
                <div class="grid grid-cols-2 gap-2 text-[10px] text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100">
                    <div class="flex flex-col">
                        <span class="text-[9px] text-gray-400 font-bold uppercase">Established</span>
                        <span class="truncate font-semibold">{{ optional($linkage->established_at)->format('M Y') ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col border-l border-gray-200 pl-2">
                        <span class="text-[9px] text-gray-400 font-bold uppercase">Agreement</span>
                        <span class="truncate font-semibold text-blue-600">{{ $linkage->agreementLevel->name ?? 'None' }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="bg-gray-50/50 px-4 py-2 border-t border-gray-100 flex justify-between items-center">
                <button wire:click="viewDetails({{ $linkage->id }})" class="text-[10px] font-bold text-gray-500 hover:text-red-600 transition uppercase tracking-wider flex items-center gap-1">
                    View Details
                </button>
                <div class="flex gap-1">
                    <button wire:click="edit({{ $linkage->id }})" class="p-1.5 text-gray-400 hover:text-blue-600 rounded hover:bg-blue-50 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                    <button wire:click="delete({{ $linkage->id }})" wire:confirm="Delete?" class="p-1.5 text-gray-400 hover:text-red-600 rounded hover:bg-red-50 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-10 flex flex-col items-center justify-center text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
            <svg class="w-8 h-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="text-xs font-medium">No partners found.</span>
        </div>
        @endforelse
    </div>
    
    <div class="mt-6 px-1">
        {{ $linkages->links() }}
    </div>


    {{-- CREATE / EDIT MODAL --}}
    @if($isCreateModalOpen)
    <div class="fixed inset-0 z-50 flex items-end md:items-center justify-center bg-black/50 backdrop-blur-sm sm:p-4">
        {{-- Slide up from bottom on mobile, Center on desktop --}}
        <div class="bg-white w-full md:w-full max-w-2xl h-[90vh] md:h-auto md:rounded-2xl rounded-t-2xl shadow-xl overflow-hidden flex flex-col animate-slide-in-bottom md:animate-fade-in-scale">
            
            <div class="bg-gray-50 px-5 py-3 border-b border-gray-100 shrink-0 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 text-sm">{{ $linkageId ? 'Edit' : 'New' }} Partner</h3>
                <button wire:click="$set('isCreateModalOpen', false)" class="text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="p-5 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    {{-- Identity --}}
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Partner Name</label>
                            <input wire:model="form.name" type="text" class="w-full border-gray-200 rounded-lg text-xs md:text-sm focus:ring-red-500 py-2">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <input wire:model="form.acronym" type="text" placeholder="Acronym" class="border-gray-200 rounded-lg text-xs md:text-sm py-2">
                            <input wire:model="form.website" type="text" placeholder="Website" class="border-gray-200 rounded-lg text-xs md:text-sm py-2">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Logo</label>
                            <input wire:model="logo" type="file" class="w-full text-[10px] text-gray-500 border border-gray-200 rounded-lg p-1.5">
                        </div>
                        <textarea wire:model="form.description" rows="3" placeholder="Brief description..." class="w-full border-gray-200 rounded-lg text-xs md:text-sm py-2"></textarea>
                    </div>

                    {{-- Classification --}}
                    <div class="space-y-3">
                        <select wire:model="form.linkage_type_id" class="w-full border-gray-200 rounded-lg text-xs md:text-sm py-2">
                            <option value="">Select Type</option>
                            @foreach($this->types as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                        </select>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <select wire:model="form.linkage_status_id" class="w-full border-gray-200 rounded-lg text-xs md:text-sm py-2">
                                <option value="">Status</option>
                                @foreach($this->statuses as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                            </select>
                            <select wire:model="form.agreement_level_id" class="w-full border-gray-200 rounded-lg text-xs md:text-sm py-2">
                                <option value="">Agreement</option>
                                @foreach($this->agreements as $a) <option value="{{ $a->id }}">{{ $a->name }}</option> @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Established</label>
                                <input wire:model="form.established_at" type="date" class="w-full border-gray-200 rounded-lg text-xs py-2">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Expires</label>
                                <input wire:model="form.expires_at" type="date" class="w-full border-gray-200 rounded-lg text-xs py-2">
                            </div>
                        </div>

                        <div>
                             <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Related SDGs</label>
                             <div class="h-20 overflow-y-auto border border-gray-200 rounded-lg p-2 grid grid-cols-1 gap-1">
                                 @foreach($this->sdgs as $sdg)
                                    <label class="flex items-center gap-2 text-[10px] cursor-pointer">
                                        <input type="checkbox" wire:model="selectedSdgs" value="{{ $sdg->id }}" class="text-red-600 rounded w-3 h-3">
                                        <span class="truncate">{{ $sdg->title }}</span>
                                    </label>
                                 @endforeach
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 flex justify-end gap-3 shrink-0 border-t border-gray-100">
                <button wire:click="$set('isCreateModalOpen', false)" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button wire:click="store" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg shadow-md">Save</button>
            </div>
        </div>
    </div>
    @endif


    {{-- DETAIL / PROFILE MODAL --}}
    @if($isDetailModalOpen && $viewingLinkage)
    <div class="fixed inset-0 z-50 flex items-end md:items-center justify-end bg-black/50 backdrop-blur-sm" x-data="{ tab: 'overview' }">
        <div class="bg-white w-full md:w-[600px] h-[90vh] md:h-full overflow-y-auto shadow-2xl animate-slide-in-right p-5 md:p-8 rounded-t-2xl md:rounded-none relative flex flex-col">
            
            <button wire:click="$set('isDetailModalOpen', false)" class="absolute top-4 right-4 p-2 bg-gray-100 rounded-full text-gray-400 hover:text-red-600 transition z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6 shrink-0 mt-2">
                <div class="w-14 h-14 rounded-xl border border-gray-200 p-1 bg-white shadow-sm shrink-0">
                    @if($viewingLinkage->logo_path)
                        <img src="{{ asset('storage/'.$viewingLinkage->logo_path) }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full bg-gray-50 flex items-center justify-center text-[10px] font-bold text-gray-400">IMG</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-heading font-black text-xl text-gray-900 leading-tight mb-1 truncate">{{ $viewingLinkage->name }}</h2>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="truncate max-w-[100px]">{{ $viewingLinkage->type->name }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="{{ $viewingLinkage->status->name == 'Active' ? 'text-green-600' : 'text-gray-500' }}">{{ $viewingLinkage->status->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Scrollable Tabs --}}
            <div class="flex gap-6 border-b border-gray-200 mb-5 overflow-x-auto whitespace-nowrap scrollbar-hide shrink-0 pb-1">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Overview</button>
                <button @click="tab = 'activities'" :class="tab === 'activities' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Activities</button>
                <button @click="tab = 'projects'" :class="tab === 'projects' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Projects</button>
            </div>

            {{-- Content Area --}}
            <div class="flex-1 overflow-y-auto">
                
                {{-- Overview Tab --}}
                <div x-show="tab === 'overview'" class="space-y-5">
                    <div class="bg-gray-50 p-3 rounded-lg text-xs text-gray-600 leading-relaxed border border-gray-100">
                        {{ $viewingLinkage->description ?? 'No description.' }}
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 text-xs">
                        <div>
                            <h4 class="font-bold text-gray-400 uppercase mb-2 border-b border-gray-100 pb-1">Contact</h4>
                            <p class="font-bold text-gray-900 mb-1">{{ $viewingLinkage->contact_person ?? 'N/A' }}</p>
                            <p class="text-gray-500 mb-1">{{ $viewingLinkage->email }}</p>
                            <a href="{{ $viewingLinkage->website }}" target="_blank" class="text-blue-500 truncate block">{{ $viewingLinkage->website }}</a>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                            <h4 class="font-bold text-blue-800 uppercase mb-2">Details</h4>
                            <p><span class="text-blue-600">Agreement:</span> {{ $viewingLinkage->agreementLevel->name ?? 'N/A' }}</p>
                            <p><span class="text-blue-600">Start:</span> {{ optional($viewingLinkage->established_at)->format('M d, Y') }}</p>
                            <p><span class="text-blue-600">End:</span> {{ optional($viewingLinkage->expires_at)->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-2">Target SDGs</h4>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($viewingLinkage->sdgs as $sdg)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-900 text-[9px] font-bold rounded border border-yellow-200">{{ $sdg->title }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Activities Tab --}}
                <div x-show="tab === 'activities'" class="space-y-5">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <h4 class="text-[10px] font-bold text-gray-500 uppercase mb-2">Log Activity</h4>
                        <div class="space-y-2">
                            <input wire:model="newActivity.title" type="text" placeholder="Title" class="w-full border-gray-200 rounded text-xs p-2">
                            <div class="flex gap-2">
                                <input wire:model="newActivity.activity_date" type="date" class="w-1/2 border-gray-200 rounded text-xs p-2">
                                <button wire:click="saveActivity" class="w-1/2 bg-gray-900 text-white text-xs font-bold rounded shadow">Save</button>
                            </div>
                            <textarea wire:model="newActivity.description" rows="2" placeholder="Details..." class="w-full border-gray-200 rounded text-xs p-2"></textarea>
                        </div>
                    </div>

                    <div class="space-y-3 pl-2">
                        @forelse($viewingLinkage->activities as $activity)
                        <div class="relative pl-4 border-l-2 border-gray-200">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                            <p class="text-[9px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y') }}</p>
                            <h5 class="text-xs font-bold text-gray-800">{{ $activity->title }}</h5>
                            <p class="text-[10px] text-gray-600 mt-0.5">{{ $activity->description }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 italic text-center py-4">No activities.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Projects Tab --}}
                <div x-show="tab === 'projects'">
                    <div class="space-y-2">
                        @forelse($viewingLinkage->projects as $project)
                        <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="w-8 h-8 bg-white border border-gray-200 rounded flex items-center justify-center font-bold text-[10px] text-gray-400 shadow-sm">P</div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900 line-clamp-1">{{ $project->title }}</h4>
                                <p class="text-[10px] text-gray-500">Role: {{ $project->pivot->role ?? 'Partner' }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 italic text-center py-4">No linked projects.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif

</div>