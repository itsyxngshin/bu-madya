<div class="min-h-screen font-sans text-gray-900 pb-20">
    
    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading font-black text-2xl text-gray-800 tracking-tight">
                Linkage <span class="text-red-600">Network</span>
            </h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
                Partners, MOAs & Activities
            </p>
        </div>

        <button wire:click="create" 
           class="px-5 py-2.5 bg-gray-900 text-white text-xs font-bold uppercase rounded-xl shadow-lg hover:bg-red-600 transition flex items-center gap-2">
            <span>+ New Partner</span>
        </button>
    </div>

    {{-- TOOLBAR --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search partners..." 
                   class="w-full pl-4 pr-10 py-2 border-gray-200 rounded-xl text-sm focus:ring-red-500">
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto">
            <select wire:model.live="typeFilter" class="block w-full pl-3 pr-8 py-2 text-xs font-bold border-gray-200 rounded-xl bg-gray-50">
                <option value="">All Types</option>
                @foreach($this->types as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
            </select>
            <select wire:model.live="statusFilter" class="block w-full pl-3 pr-8 py-2 text-xs font-bold border-gray-200 rounded-xl bg-gray-50">
                <option value="">All Statuses</option>
                @foreach($this->statuses as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
            </select>
        </div>
    </div>

    {{-- LINKAGE GRID (Card Layout instead of Table for Branding) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($linkages as $linkage)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition group overflow-hidden flex flex-col h-full">
            
            {{-- Header Color Bar --}}
            <div class="h-1 w-full" style="background-color: {{ $linkage->type->color ?? '#e5e7eb' }}"></div>
            
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    {{-- Logo --}}
                    <div class="w-12 h-12 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden">
                        @if($linkage->logo_path)
                            <img src="{{ asset('storage/'.$linkage->logo_path) }}" class="w-full h-full object-contain">
                        @else
                            <span class="text-xs font-bold text-gray-400">{{ substr($linkage->name, 0, 2) }}</span>
                        @endif
                    </div>
                    
                    {{-- Status Badge --}}
                    <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-lg border" 
                          style="background-color: {{ $linkage->status->color ?? '#f3f4f6' }}20; color: {{ $linkage->status->color ?? '#374151' }}; border-color: {{ $linkage->status->color ?? '#e5e7eb' }}40;">
                        {{ $linkage->status->name ?? 'Unknown' }}
                    </span>
                </div>

                <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ $linkage->name }}</h3>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-4">
                    {{ $linkage->acronym }} • {{ $linkage->type->name ?? 'N/A' }}
                </p>
                
                <div class="space-y-2 text-xs text-gray-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Est: {{ optional($linkage->established_at)->format('M Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>{{ $linkage->agreementLevel->name ?? 'No Agreement' }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center">
                <button wire:click="viewDetails({{ $linkage->id }})" class="text-xs font-bold text-gray-600 hover:text-red-600 transition uppercase tracking-wider">
                    View Profile
                </button>
                <div class="flex gap-2">
                    <button wire:click="edit({{ $linkage->id }})" class="text-gray-400 hover:text-blue-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                    <button wire:click="delete({{ $linkage->id }})" wire:confirm="Remove this partner?" class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400 text-sm">No partners found.</div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $linkages->links() }}
    </div>


    {{-- CREATE / EDIT MODAL --}}
    @if($isCreateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">{{ $linkageId ? 'Edit' : 'Add New' }} Partner</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Left Column: Identity --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Partner Name</label>
                        <input wire:model="form.name" type="text" class="w-full border-gray-200 rounded-lg text-sm focus:ring-red-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input wire:model="form.acronym" type="text" placeholder="Acronym (e.g. DOH)" class="border-gray-200 rounded-lg text-sm">
                        <input wire:model="form.website" type="text" placeholder="Website" class="border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Logo</label>
                        <input wire:model="logo" type="file" class="w-full text-xs text-gray-500 border border-gray-200 rounded-lg">
                        @if($logo) <img src="{{ $logo->temporaryUrl() }}" class="h-10 mt-2 rounded"> @endif
                    </div>
                    <textarea wire:model="form.description" rows="3" placeholder="Brief description..." class="w-full border-gray-200 rounded-lg text-sm"></textarea>
                </div>

                {{-- Right Column: Classification --}}
                <div class="space-y-4">
                    <select wire:model="form.linkage_type_id" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Select Type</option>
                        @foreach($this->types as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                    </select>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <select wire:model="form.linkage_status_id" class="w-full border-gray-200 rounded-lg text-sm">
                            <option value="">Select Status</option>
                            @foreach($this->statuses as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                        </select>
                        <select wire:model="form.agreement_level_id" class="w-full border-gray-200 rounded-lg text-sm">
                            <option value="">Agreement</option>
                            @foreach($this->agreements as $a) <option value="{{ $a->id }}">{{ $a->name }}</option> @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Established</label>
                            <input wire:model="form.established_at" type="date" class="w-full border-gray-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Expires</label>
                            <input wire:model="form.expires_at" type="date" class="w-full border-gray-200 rounded-lg text-sm">
                        </div>
                    </div>

                    {{-- SDG Multi-Select (Simple Box) --}}
                    <div>
                         <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Related SDGs</label>
                         <div class="h-24 overflow-y-auto border border-gray-200 rounded-lg p-2 grid grid-cols-2 gap-2">
                             @foreach($this->sdgs as $sdg)
                                <label class="flex items-center gap-2 text-xs cursor-pointer">
                                    <input type="checkbox" wire:model="selectedSdgs" value="{{ $sdg->id }}" class="text-red-600 rounded">
                                    {{ $sdg->title }}
                                </label>
                             @endforeach
                         </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button wire:click="$set('isCreateModalOpen', false)" class="px-4 py-2 text-xs font-bold text-gray-500">Cancel</button>
                <button wire:click="store" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700">Save Linkage</button>
            </div>
        </div>
    </div>
    @endif


    {{-- DETAIL / PROFILE MODAL --}}
    @if($isDetailModalOpen && $viewingLinkage)
    <div class="fixed inset-0 z-50 flex items-center justify-end bg-black/50 backdrop-blur-sm" x-data="{ tab: 'overview' }">
        <div class="bg-white w-full max-w-2xl h-full overflow-y-auto shadow-2xl animate-slide-in-right p-8 relative">
            
            <button wire:click="$set('isDetailModalOpen', false)" class="absolute top-6 right-6 text-gray-400 hover:text-red-600">Close</button>

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-xl border border-gray-200 p-1 bg-white">
                    @if($viewingLinkage->logo_path)
                        <img src="{{ asset('storage/'.$viewingLinkage->logo_path) }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full bg-gray-50 flex items-center justify-center text-xs font-bold text-gray-400">IMG</div>
                    @endif
                </div>
                <div>
                    <h2 class="font-heading font-black text-2xl text-gray-900 leading-none">{{ $viewingLinkage->name }}</h2>
                    <p class="text-sm text-gray-500 font-bold mt-1">{{ $viewingLinkage->type->name }} • <span class="{{ $viewingLinkage->status->name == 'Active' ? 'text-green-600' : 'text-gray-500' }}">{{ $viewingLinkage->status->name }}</span></p>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-6 border-b border-gray-200 mb-6">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Overview</button>
                <button @click="tab = 'activities'" :class="tab === 'activities' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Activities Log</button>
                <button @click="tab = 'projects'" :class="tab === 'projects' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Projects</button>
            </div>

            {{-- Tab 1: Overview --}}
            <div x-show="tab === 'overview'" class="space-y-6">
                <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-600 leading-relaxed">
                    {{ $viewingLinkage->description ?? 'No description provided.' }}
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Contact Info</h4>
                        <p class="text-sm font-bold">{{ $viewingLinkage->contact_person ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $viewingLinkage->email }}</p>
                        <p class="text-xs text-gray-500">{{ $viewingLinkage->website }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Details</h4>
                        <p class="text-xs text-gray-600"><strong>Level:</strong> {{ $viewingLinkage->agreementLevel->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-600"><strong>Start:</strong> {{ optional($viewingLinkage->established_at)->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-600"><strong>End:</strong> {{ optional($viewingLinkage->expires_at)->format('M d, Y') }}</p>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Target SDGs</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($viewingLinkage->sdgs as $sdg)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-bold rounded">{{ $sdg->title }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tab 2: Activities --}}
            <div x-show="tab === 'activities'" class="space-y-6">
                {{-- Add Activity Form --}}
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <h4 class="text-xs font-bold text-blue-700 uppercase mb-2">Log New Activity</h4>
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <input wire:model="newActivity.title" type="text" placeholder="Activity Title" class="col-span-2 border-white rounded text-xs">
                        <input wire:model="newActivity.activity_date" type="date" class="border-white rounded text-xs">
                    </div>
                    <textarea wire:model="newActivity.description" rows="2" placeholder="Details..." class="w-full border-white rounded text-xs mb-2"></textarea>
                    <div class="flex justify-between items-center">
                        @if(session('activity_message')) <span class="text-xs text-green-600 font-bold">{{ session('activity_message') }}</span> @else <span></span> @endif
                        <button wire:click="saveActivity" class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded hover:bg-blue-700">Log Activity</button>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="space-y-4">
                    @forelse($viewingLinkage->activities as $activity)
                    <div class="relative pl-4 border-l-2 border-gray-200">
                        <div class="absolute -left-[5px] top-1 w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                        <p class="text-xs text-gray-400 font-bold mb-0.5">{{ \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y') }}</p>
                        <h5 class="text-sm font-bold text-gray-800">{{ $activity->title }}</h5>
                        <p class="text-xs text-gray-600 mt-1">{{ $activity->description }}</p>
                    </div>
                    @empty
                    <p class="text-center text-xs text-gray-400 py-4">No activities logged yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Tab 3: Projects --}}
            <div x-show="tab === 'projects'">
                <div class="space-y-3">
                    @forelse($viewingLinkage->projects as $project)
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center font-bold text-xs">P</div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $project->title }}</h4>
                            <p class="text-xs text-gray-500">Role: {{ $project->pivot->role ?? 'Partner' }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-xs text-gray-400 py-4">No linked projects found.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    @endif

</div>