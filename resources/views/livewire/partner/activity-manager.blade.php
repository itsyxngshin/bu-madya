<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8 animate-fade-in-up">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Activity & Engagements</h1>
            <p class="text-sm text-gray-500 mt-1">Track partnerships, external events, and SDG impacts.</p>
        </div>
        
        <button wire:click="openCreateModal" class="w-full sm:w-auto px-6 py-3 bg-gray-900 text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-black transition active:scale-95 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Log Activity
        </button>
    </div>

    @if(session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mb-6 shadow-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ACTIVITY CARDS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($activities as $activity)
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full relative overflow-hidden group">
                
                {{-- Status Ribbon --}}
                <div class="absolute top-0 left-0 w-full h-1 z-10 {{ $activity->status === 'completed' ? 'bg-green-500' : ($activity->status === 'ongoing' ? 'bg-orange-500' : 'bg-blue-500') }}"></div>

                {{-- HIGHLIGHT PHOTO HEADER --}}
                <div class="h-40 w-full bg-gray-100 relative overflow-hidden shrink-0">
                    @if(!empty($activity->highlight_photos) && count($activity->highlight_photos) > 0)
                        <img src="{{ asset('storage/' . $activity->highlight_photos[0]) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-indigo-50 flex items-center justify-center">
                            <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    
                    {{-- Date Badge Floating on Image --}}
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-xl px-3 py-2 text-center border border-white/50 shadow-sm">
                        <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest leading-none">{{ $activity->start_date->format('M') }}</span>
                        <span class="block text-xl font-black text-gray-900 leading-none mt-0.5">{{ $activity->start_date->format('d') }}</span>
                    </div>
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">{{ $activity->nature_of_activity }}</p>
                        @if($activity->sdg)
                            <span class="text-[9px] font-bold text-white px-1.5 py-0.5 rounded uppercase tracking-wider shadow-sm" style="background-color: {{ $activity->sdg->color_hex ?? '#3b82f6' }}" title="{{ $activity->sdg->name }}">
                                SDG {{ $activity->sdg->goal_number }}
                            </span>
                        @endif
                    </div>

                    <h3 class="font-black text-lg text-gray-900 leading-tight mb-4">{{ $activity->title }}</h3>
                    
                    <div class="space-y-3 mb-4 mt-auto">
                        @if($activity->focals->count() > 0)
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Focal Persons</p>
                                <div class="flex -space-x-2 overflow-hidden">
                                    @foreach($activity->focals->take(4) as $focal)
                                        @php $fallback = 'https://ui-avatars.com/api/?name='.urlencode($focal->name).'&background=eff6ff&color=2563eb&bold=true'; @endphp
                                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover" src="{{ $focal->avatar ?? $fallback }}" onerror="this.src='{{$fallback}}'" title="{{ $focal->name }}" alt="{{ $focal->name }}"/>
                                    @endforeach
                                    @if($activity->focals->count() > 4)
                                        <div class="flex items-center justify-center h-6 w-6 rounded-full ring-2 ring-white bg-gray-100 text-[8px] font-bold text-gray-500">+{{ $activity->focals->count() - 4 }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($activity->participants->count() > 0)
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Participants</p>
                                <div class="flex -space-x-2 overflow-hidden">
                                    @foreach($activity->participants->take(5) as $participant)
                                        @php $fallback = 'https://ui-avatars.com/api/?name='.urlencode($participant->name).'&background=f8fafc&color=64748b&bold=true'; @endphp
                                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover" src="{{ $participant->avatar ?? $fallback }}" onerror="this.src='{{$fallback}}'" title="{{ $participant->name }}" alt="{{ $participant->name }}"/>
                                    @endforeach
                                    @if($activity->participants->count() > 5)
                                        <div class="flex items-center justify-center h-6 w-6 rounded-full ring-2 ring-white bg-gray-100 text-[8px] font-bold text-gray-500">+{{ $activity->participants->count() - 5 }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Action Bar --}}
                    <div class="pt-4 border-t border-gray-100 flex items-center gap-2">
                        <button wire:click="openEditModal({{ $activity->id }})" class="flex-1 py-2 bg-gray-50 hover:bg-orange-50 text-gray-600 hover:text-orange-600 text-xs font-black uppercase tracking-widest rounded-xl transition-colors">
                            Manage
                        </button>
                        
                        <button wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Are you sure you want to delete this activity?" class="p-2 bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-600 rounded-xl transition-colors" title="Delete Activity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No activities logged yet.</p>
            </div>
        @endforelse
    </div>

    {{-- CREATE / EDIT MODAL --}}
    <template x-teleport="body">
        <div x-show="$wire.isModalOpen" style="display: none; z-index: 99999;" class="fixed inset-0 flex items-center justify-center p-4">
            <div x-show="$wire.isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" @click="$wire.isModalOpen = false"></div>

            <div x-show="$wire.isModalOpen" x-transition class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl flex flex-col overflow-hidden max-h-[90vh]">
                <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                    <h3 class="font-black text-gray-900">{{ $isEditMode ? 'Edit Activity Details' : 'Log New Activity' }}</h3>
                    <button @click="$wire.isModalOpen = false" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <div class="p-5 sm:p-6 overflow-y-auto custom-scrollbar">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- LEFT COLUMN: Details --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Activity Title <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.live.debounce.300ms="title" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                                @error('title') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">URL Slug</label>
                                <div class="flex rounded-xl shadow-sm border border-gray-200 overflow-hidden focus-within:border-blue-500 focus-within:ring-1">
                                    <span class="px-2 py-2 bg-gray-100 text-gray-400 text-[10px] border-r border-gray-200 flex items-center font-mono">/a/</span>
                                    <input type="text" wire:model="slug" class="w-full text-sm border-0 bg-gray-50 focus:ring-0 px-2 py-2 font-mono text-blue-600">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Start Date <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="start_date" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">End Date</label>
                                    <input type="date" wire:model="end_date" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nature <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="nature_of_activity" placeholder="e.g. Partnership" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">SDG Mapping</label>
                                <select wire:model="sdg_id" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                                    <option value="">None...</option>
                                    @foreach($sdgs as $sdg)
                                        <option value="{{ $sdg->id }}">SDG {{ $sdg->goal_number }}: {{ $sdg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</label>
                                <select wire:model="status" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                                    <option value="upcoming">Upcoming</option>
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Highlight Photos (Multiple Allowed)</label>
                                <input type="file" wire:model="photos" multiple accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                <div wire:loading wire:target="photos" class="text-[10px] text-blue-500 font-bold mt-1 animate-pulse">Uploading photos...</div>
                            </div>
                            
                            @if(count($existing_photos) > 0)
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($existing_photos as $index => $photo)
                                        <div class="relative group w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                                            <button wire:click="removeExistingPhoto({{ $index }})" class="absolute inset-0 bg-red-500/80 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>

                        {{-- RIGHT COLUMN: Participants & Relational Tagging --}}
                        <div class="space-y-6">
                            
                            {{-- User Tagging Search Box --}}
                            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 relative">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Tag Users (Focals & Participants)</h4>
                                
                                <div class="relative w-full mb-3">
                                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search by name or ID..." class="w-full text-xs rounded-xl border-blue-200 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                </div>

                                @if(strlen($searchQuery) >= 2)
                                    <div class="absolute z-50 left-4 right-4 mt-1 bg-white border border-gray-100 shadow-xl rounded-xl overflow-hidden max-h-48 overflow-y-auto">
                                        @forelse($searchResults as $user)
                                            <div class="flex items-center justify-between p-2 border-b border-gray-50 hover:bg-gray-50 transition">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-[10px] font-bold text-gray-900 truncate">{{ $user->name }}</p>
                                                </div>
                                                <div class="flex gap-1 shrink-0 ml-2">
                                                    <button wire:click.prevent="addUserToRole({{ $user->id }}, 'focal')" class="px-2 py-1 bg-orange-100 hover:bg-orange-600 text-orange-700 hover:text-white text-[9px] font-bold uppercase rounded-md transition">Focal</button>
                                                    <button wire:click.prevent="addUserToRole({{ $user->id }}, 'participant')" class="px-2 py-1 bg-blue-100 hover:bg-blue-600 text-blue-700 hover:text-white text-[9px] font-bold uppercase rounded-md transition">Participant</button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-3 text-center text-[9px] font-bold text-gray-400 uppercase">No users found.</div>
                                        @endforelse
                                    </div>
                                @endif
                            </div>

                            {{-- Selected Focals List --}}
                            <div>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 flex items-center justify-between">
                                    Lead Focals <span class="bg-gray-100 px-2 py-0.5 rounded-full">{{ count($selectedFocals) }}</span>
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($selectedFocals as $focal)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-100">
                                            {{ $focal['name'] }}
                                            <button wire:click.prevent="removeUserFromRole({{ $focal['id'] }}, 'focal')" class="text-orange-400 hover:text-red-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No focals selected.</span>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Selected Participants List --}}
                            <div>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 flex items-center justify-between">
                                    Participants <span class="bg-gray-100 px-2 py-0.5 rounded-full">{{ count($selectedParticipants) }}</span>
                                </h4>
                                <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto custom-scrollbar">
                                    @forelse($selectedParticipants as $participant)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $participant['name'] }}
                                            <button wire:click.prevent="removeUserFromRole({{ $participant['id'] }}, 'participant')" class="text-blue-400 hover:text-red-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No participants selected.</span>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <button wire:click="saveActivity" class="w-full py-4 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-blue-700 transition">
                            {{ $isEditMode ? 'Update Activity' : 'Save & Publish Activity' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </template>
</div>