<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900">
    <div class="max-w-7xl mx-auto">

        {{-- 1. HEADER & ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="w-full md:w-auto text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-black text-gray-900">Evaluation Manager</h1>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Manage feedback forms and view responses.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-2.5 md:py-2 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm shadow-sm" placeholder="Search forms...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3 md:top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                {{-- Dynamic Route Prefix based on Role --}}
                @php
                    $roleName = auth()->user()->role?->role_name ?? 'guest';

                    $routePrefix = match($roleName) {
                        'organization'  => 'partner.evaluations',
                        'director'      => 'director.evaluations',
                        default         => 'admin.evaluations',
                    };

                    $createRoute = route($routePrefix . '.create');
                @endphp

                {{-- Create Button --}}
                <a href="{{ $createRoute }}" class="w-full sm:w-auto justify-center px-5 py-2.5 md:py-2 bg-gray-900 text-white font-bold rounded-xl shadow-md hover:bg-orange-600 transition flex items-center gap-2 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create New
                </a>
            </div>
        </div>

        {{-- 2. GRID LAYOUT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- "Create New" Ghost Card --}}
            <a href="{{ $createRoute }}" class="group border-2 border-dashed border-gray-300 rounded-[2rem] p-6 flex flex-col items-center justify-center text-center hover:border-orange-400 hover:bg-orange-50 transition min-h-[250px] cursor-pointer">
                <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mb-4 group-hover:bg-orange-100 group-hover:text-orange-500 transition shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h3 class="font-bold text-gray-500 group-hover:text-orange-700">Create New Form</h3>
            </a>

            {{-- Evaluation Cards --}}
            @foreach($evaluations as $eval)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-200 relative group hover:-translate-y-1 hover:shadow-md transition duration-300 flex flex-col h-full">

                    {{-- Status Badge --}}
                    <div class="absolute top-6 right-6">
                        <button wire:click="toggleStatus({{ $eval->id }})"
                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide transition border
                                {{ $eval->is_active ? 'bg-green-100 text-green-700 border-green-200 hover:bg-green-200' : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}">
                            {{ $eval->is_active ? 'Active' : 'Draft' }}
                        </button>
                    </div>

                    {{-- Card Content --}}
                    <div class="mt-2 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center mb-4 shadow-lg shadow-orange-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 line-clamp-2" title="{{ $eval->title }}">
                            {{ $eval->title }}
                        </h3>

                        @if(auth()->user()->role?->role_name === 'administrator')
                            <div class="mt-2 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-gray-500">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="truncate">Built by: <span class="text-orange-600">{{ $eval->creator->name ?? 'System Admin' }}</span></span>
                            </div>
                        @endif
                        <p class="text-xs text-gray-500 mt-1 mb-4 font-mono">
                            Updated {{ $eval->updated_at->diffForHumans() }}
                        </p>

                        {{-- Stats Row --}}
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1.5 font-bold bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                {{ $eval->responses_count }} Responses
                            </span>
                        </div>
                    </div>

                    {{-- Actions Footer --}}
                    <div class="border-t border-gray-100 pt-4 mt-6 flex items-center justify-between">

                        <div class="flex items-center gap-2">
                            {{-- Results Link (Primary Action) --}}
                            <a href="{{ route($routePrefix . '.results', $eval->slug ?? $eval->id) }}" class="px-4 py-2 bg-orange-50 text-orange-600 hover:bg-orange-100 rounded-xl text-[10px] sm:text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Results
                            </a>
                        </div>

                        <div class="flex items-center gap-1">
                            {{-- Edit Link --}}
                            <a href="{{ route($routePrefix . '.edit', $eval->slug ?? $eval->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Form">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>

                            {{-- PREMIUM DROPDOWN MENU (Alpine.js) --}}
                            <div class="relative" x-data="{ menuOpen: false }">
                                <button @click="menuOpen = !menuOpen" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-500/50" title="More Options">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </button>

                                <div x-show="menuOpen"
                                     @click.outside="menuOpen = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                     style="display: none;"
                                     class="absolute right-0 bottom-full mb-3 w-56 bg-white/95 backdrop-blur-xl border border-gray-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] ring-1 ring-black/5 rounded-2xl p-1.5 z-50">

                                    {{-- Preview Link --}}
                                    <a href="{{ route('evaluations.show', $eval->slug) }}" target="_blank" class="group w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl flex items-center gap-2.5 transition-all">
                                        <div class="bg-gray-100 text-gray-500 group-hover:bg-orange-100 group-hover:text-orange-600 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </div>
                                        Preview Form
                                    </a>

                                    {{-- Copy Link Button --}}
                                    <button x-data="{ copied: false }"
                                            @click="navigator.clipboard.writeText('{{ route('evaluations.show', $eval->slug) }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                            class="group w-full text-left px-3 py-2 text-sm font-medium rounded-xl flex items-center gap-2.5 transition-all"
                                            :class="copied ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700'">
                                        <div class="p-1.5 rounded-lg transition-colors" :class="copied ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600'">
                                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                            <svg x-show="copied" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span x-text="copied ? 'Link Copied!' : 'Copy Public Link'"></span>
                                    </button>

                                    {{-- Share Link --}}
                                    @if(auth()->user()->role?->role_name === 'administrator' || $eval->created_by === auth()->id())
                                        <button wire:click="openShareModal({{ $eval->id }}); menuOpen = false" class="group w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-xl flex items-center gap-2.5 transition-all">
                                            <div class="bg-gray-100 text-gray-500 group-hover:bg-blue-100 group-hover:text-blue-600 p-1.5 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                            </div>
                                            Share Access
                                        </button>
                                    @endif

                                    <div class="h-px bg-gray-100 my-1.5 mx-2"></div>

                                    {{-- Duplicate --}}
                                    <button wire:click="duplicate({{ $eval->id }}); menuOpen = false" class="group w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl flex items-center gap-2.5 transition-all">
                                        <div class="bg-gray-100 text-gray-500 group-hover:bg-emerald-100 group-hover:text-emerald-600 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </div>
                                        Duplicate Form
                                    </button>

                                    {{-- Delete (Protected: Only Admin or Owner) --}}
                                    @if(auth()->user()->role?->role_name === 'administrator' || $eval->created_by === auth()->id())
                                        <button onclick="confirm('Are you sure? This cannot be undone.') || event.stopImmediatePropagation()" wire:click="delete({{ $eval->id }})" class="group w-full text-left px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl flex items-center gap-2.5 transition-all">
                                            <div class="bg-red-50 text-red-500 group-hover:bg-red-100 group-hover:text-red-600 p-1.5 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </div>
                                            Delete Form
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $evaluations->links() }}
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- SECURE SHARE MODAL --}}
    {{-- ========================================== --}}
    @if($sharingEvaluation && isset($sharingEvaluation->id))
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="closeShareModal"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-visible transform transition-all">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white rounded-t-3xl">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 leading-tight">Share Access</h3>
                        <p class="text-xs font-bold text-gray-400 mt-1 truncate max-w-[250px]">{{ $sharingEvaluation?->title }}</p>
                    </div>
                    <button wire:click="closeShareModal" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 bg-gray-50 rounded-b-3xl">
                    <div class="relative mb-8">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Add Collaborators</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input wire:model.live.debounce.300ms="shareSearch" type="text" placeholder="Search by name..." class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-sm font-bold placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm">
                        </div>

                        @if(!empty($shareSearch) && count($this->searchResults) > 0)
                            <div class="absolute z-50 mt-2 w-full bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                                <ul class="max-h-56 overflow-y-auto">
                                    @foreach($this->searchResults as $user)
                                        <li>
                                            <button wire:click="addCollaborator({{ $user->id }})" class="w-full text-left px-4 py-3 hover:bg-blue-50 flex items-center gap-3 transition border-b border-gray-50 last:border-0">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</p>
                                                    <p class="text-[10px] text-gray-500 truncate">{{ $user->email }}</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest bg-blue-100 px-2 py-1 rounded-md">Add</span>
                                                </div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif(!empty($shareSearch))
                            <div class="absolute z-50 mt-2 w-full bg-white shadow-xl rounded-2xl border border-gray-100 p-4 text-center">
                                <p class="text-xs text-gray-500 font-bold">No available users found.</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Who has access</label>
                        <div class="space-y-2 max-h-[35vh] overflow-y-auto pr-2 custom-scrollbar">
                            {{-- Owner --}}
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="h-8 w-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-black text-xs shrink-0 ring-2 ring-white shadow-sm">
                                        {{ substr($sharingEvaluation->creator->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $sharingEvaluation->creator->name ?? 'System Admin' }}</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-black text-orange-500 uppercase tracking-widest bg-orange-50 px-2 py-1 rounded-md shrink-0 border border-orange-100">Owner</span>
                            </div>

                            {{-- Collaborators --}}
                            @forelse($sharingEvaluation->collaborators as $collaborator)
                                <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="h-8 w-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-xs shrink-0 ring-2 ring-white shadow-sm">
                                            {{ substr($collaborator->name, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $collaborator->name }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="removeCollaborator({{ $collaborator->id }})" class="text-[10px] font-bold text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition shrink-0 uppercase tracking-widest border border-transparent hover:border-red-100">
                                        Remove
                                    </button>
                                </div>
                            @empty
                                <div class="text-center p-5 bg-white rounded-xl border border-gray-200 border-dashed">
                                    <svg class="w-6 h-6 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Only the owner has access.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
