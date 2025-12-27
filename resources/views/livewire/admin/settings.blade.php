<div class="min-h-screen font-sans text-gray-900 pb-20">
    
    {{-- PAGE HEADER --}}
    <div class="mb-8 border-b border-gray-200 pb-6">
        <h1 class="font-heading font-black text-3xl text-gray-800 tracking-tight">
            System <span class="text-red-600">Settings</span>
        </h1>
        <p class="text-sm text-gray-500 font-bold uppercase tracking-widest mt-2">
            Master Configuration Panel
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- 1. SETTINGS SIDEBAR --}}
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                <nav class="flex flex-col p-2 space-y-1">
                    
                    @foreach($tabList as $key => $config)
                        {{-- Group Headers --}}
                        @if($key === 'academic-years')
                            <div class="px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">Academics</div>
                        @elseif($key === 'committees')
                            <div class="px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4">Organization</div>
                        @elseif($key === 'news-categories')
                            <div class="px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4">Content</div>
                        @elseif($key === 'linkage-types')
                            <div class="px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4">Linkages</div>
                        @elseif($key === 'maintenance')
                            <div class="px-3 py-2 text-[10px] font-bold text-red-500 uppercase tracking-widest mt-4">System Core</div>
                        @endif

                        <button wire:click="$set('activeTab', '{{ $key }}')" 
                            class="text-left px-3 py-2.5 rounded-lg text-xs font-bold transition flex items-center gap-3 w-full
                            {{ $activeTab === $key ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $activeTab === $key ? 'bg-red-600' : 'bg-gray-300' }}"></span>
                            {{ $config['title'] }}
                        </button>
                    @endforeach

                </nav>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA --}}
        <div class="flex-1 bg-white rounded-[2rem] shadow-sm border border-gray-200 p-8 min-h-[600px]">
            
            {{-- Notifications --}}
            @if (session()->has('message'))
                <div class="mb-6 p-3 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mb-6 p-3 bg-red-50 text-red-700 text-xs font-bold rounded-lg border border-red-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif


            {{-- A. SYSTEM LOGS TAB --}}
            @if($activeTab === 'system-logs')
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-heading font-bold text-xl text-gray-800">System Logs</h2>
                    <button wire:click="clearLogs" wire:confirm="Are you sure? This deletes history." class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-600 text-xs font-bold uppercase rounded-lg transition">
                        Clear Logs
                    </button>
                </div>

                <div class="bg-gray-900 rounded-xl p-4 overflow-x-auto shadow-inner h-[500px] overflow-y-scroll font-mono text-xs text-gray-300">
                    @forelse($logs as $log)
                        <div class="mb-1 border-b border-gray-800 pb-1 whitespace-nowrap {{ Str::contains($log, '.ERROR') ? 'text-red-400 font-bold' : (Str::contains($log, '.WARNING') ? 'text-yellow-400' : '') }}">
                            {{ $log }}
                        </div>
                    @empty
                        <div class="text-gray-500 italic">Log file is empty.</div>
                    @endforelse
                </div>

            {{-- B. MAINTENANCE TAB --}}
            @elseif($activeTab === 'maintenance')
                <div class="mb-6">
                    <h2 class="font-heading font-bold text-xl text-gray-800">Maintenance & Security</h2>
                    <p class="text-sm text-gray-500 mt-1">Control system access and cache.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Lockdown Card --}}
                    <div class="p-6 rounded-2xl border transition-colors {{ $isDown ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200' }}">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $isDown ? 'bg-red-200 text-red-700' : 'bg-green-100 text-green-600' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Lockdown Mode</h3>
                                <p class="text-xs text-gray-500">{{ $isDown ? 'SYSTEM IS LOCKED' : 'System is Online' }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mb-6 leading-relaxed">
                            When active, only Administrators can log in. All other users will be blocked from accessing the platform.
                        </p>
                        <button wire:click="toggleLockdown" wire:confirm="Are you sure you want to toggle system lockdown?" class="w-full py-3 rounded-xl font-bold text-xs uppercase transition shadow-sm {{ $isDown ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-gray-100 text-gray-600 hover:bg-red-600 hover:text-white' }}">
                            {{ $isDown ? 'Disable Lockdown (Go Live)' : 'Enable Lockdown Mode' }}
                        </button>
                    </div>

                    {{-- Cache Card --}}
                    <div class="bg-white p-6 rounded-2xl border border-gray-200">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">System Cache</h3>
                                <p class="text-xs text-gray-500">Flush config & views</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mb-6 leading-relaxed">
                            Use this if you made changes to the .env file or database configuration and they aren't reflecting.
                        </p>
                        <button wire:click="clearCache" class="w-full py-3 bg-white border border-gray-200 hover:bg-blue-50 hover:border-blue-200 text-gray-600 hover:text-blue-600 rounded-xl font-bold text-xs uppercase transition">
                            Clear System Cache
                        </button>
                    </div>
                </div>

            {{-- C. STANDARD CRUD TABLES --}}
            @else
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <h2 class="font-heading font-bold text-xl text-gray-800">
                        Manage {{ Str::plural($currentTab['title']) }}
                    </h2>
                    
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search {{ Str::plural($currentTab['title']) }}..." class="w-full pl-10 pr-4 py-2 border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                        
                        <button wire:click="create" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold uppercase rounded-lg hover:bg-red-600 transition flex-shrink-0">
                            + Add New
                        </button>
                    </div>
                </div>

                {{-- DYNAMIC TABLE --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Name</th>
                                
                                {{-- Conditional Headers --}}
                                @if($activeTab === 'academic-years')
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                @else
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Description</th>
                                @endif
                                
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $item)
                            <tr class="hover:bg-gray-50 transition">
                                
                                {{-- COL 1: Name --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($item->color)
                                            <div class="w-3 h-3 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $item->color }}"></div>
                                        @endif
                                        <span class="text-sm font-bold text-gray-900">{{ $item->name }}</span>
                                    </div>
                                </td>

                                {{-- COL 2: Variable --}}
                                @if($activeTab === 'academic-years')
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->start_date)->format('M Y') }} — {{ \Carbon\Carbon::parse($item->end_date)->format('M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-full border border-green-200">Active Year</span>
                                        @else
                                            <button wire:click="toggleActiveYear({{ $item->id }})" class="text-[10px] font-bold text-gray-400 hover:text-green-600 bg-gray-100 px-2 py-1 rounded transition uppercase">Set Active</button>
                                        @endif
                                    </td>
                                @else
                                    <td class="px-6 py-4">
                                        <span class="text-xs text-gray-500">{{ Str::limit($item->description, 50) ?? '-' }}</span>
                                    </td>
                                @endif

                                {{-- COL 3: Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $item->id }})" class="text-gray-400 hover:text-blue-600 mr-3 text-xs font-bold uppercase">Edit</button>
                                    <button wire:click="delete({{ $item->id }})" wire:confirm="Are you sure? This cannot be undone." class="text-gray-400 hover:text-red-600 text-xs font-bold uppercase">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-xs text-gray-400 italic">No records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-2">
                    {{ $items->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- 3. CONSOLIDATED CREATE/EDIT MODAL --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
            
            <h3 class="font-bold text-lg text-gray-900 mb-4">{{ $selectedId ? 'Edit' : 'Create' }} {{ $currentTab['title'] }}</h3>
            
            <div class="space-y-4">
                
                {{-- Generic Name Field --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Name</label>
                    <input wire:model="name" type="text" class="w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                    @error('name') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>

                {{-- Academic Year Specifics --}}
                @if($activeTab === 'academic-years')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Start Date</label>
                            <input wire:model="start_date" type="date" class="w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                            @error('start_date') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">End Date</label>
                            <input wire:model="end_date" type="date" class="w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                            @error('end_date') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                {{-- Standard Fields (Description & Color) --}}
                @else
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Description (Optional)</label>
                        <textarea wire:model="description" rows="3" class="w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                    </div>

                    @if(in_array($activeTab, ['news-categories', 'project-categories', 'linkage-types', 'linkage-statuses', 'colleges']))
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Color Code (Optional)</label>
                            <div class="flex items-center gap-2">
                                <input wire:model.live="color" type="color" class="h-10 w-10 border-none p-0 bg-transparent cursor-pointer rounded overflow-hidden">
                                <input wire:model.live="color" type="text" placeholder="#000000" class="flex-1 border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500 uppercase">
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-4">
                <button wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition">Cancel</button>
                <button wire:click="store" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition shadow-md">
                    {{ $selectedId ? 'Update Changes' : 'Save Record' }}
                </button>
            </div>

        </div>
    </div>
    @endif

</div>