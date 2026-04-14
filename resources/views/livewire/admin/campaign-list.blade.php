<div class="min-h-screen bg-gray-50 p-4 md:p-6 font-sans text-gray-900 pb-24">
    <div class="max-w-7xl mx-auto">

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 text-green-700 px-4 py-3 rounded-xl text-xs font-bold border border-green-200 animate-fade-in-up">
                {{ session('success') }}
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="w-full md:w-auto text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-black text-gray-900">Campaign Manager</h1>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Manage petitions, track signatures, and declare victories.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-2.5 md:py-2 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm shadow-sm bg-white" placeholder="Search campaigns...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3 md:top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                {{-- Create Button --}}
                <a href="{{ route('admin.campaigns.create') }}" class="w-full sm:w-auto justify-center px-5 py-2.5 md:py-2 bg-gray-900 text-white font-bold rounded-xl shadow-md hover:bg-orange-600 transition flex items-center gap-2 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Campaign
                </a>
            </div>
        </div>

        {{-- GRID LAYOUT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($campaigns as $campaign)
                @php
                    $progress = $campaign->target_signatures > 0 
                        ? min(100, ($campaign->signatures_count / $campaign->target_signatures) * 100) 
                        : 0;

                    $statusColors = match($campaign->status) {
                        'active' => 'bg-green-100 text-green-700 border-green-200',
                        'victorious' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                        'closed' => 'bg-red-100 text-red-700 border-red-200',
                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                    };
                @endphp

                <div class="bg-white rounded-[2rem] p-6 shadow-sm border {{ $campaign->status === 'victorious' ? 'border-yellow-400 ring-4 ring-yellow-50' : 'border-gray-200' }} relative flex flex-col h-full">

                    {{-- Status Badge --}}
                    <div class="absolute top-6 right-6">
                        <button wire:click="toggleStatus({{ $campaign->id }})" class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition border {{ $statusColors }} hover:opacity-80">
                            {{ $campaign->status }}
                        </button>
                    </div>

                    {{-- Card Content --}}
                    <div class="mt-2 flex-1">
                        <div class="w-12 h-12 rounded-xl {{ $campaign->status === 'victorious' ? 'bg-gradient-to-br from-yellow-400 to-yellow-600 shadow-yellow-200' : 'bg-gradient-to-br from-orange-400 to-orange-600 shadow-orange-200' }} text-white flex items-center justify-center mb-4 shadow-lg">
                            @if($campaign->status === 'victorious')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            @endif
                        </div>

                        <h3 class="text-xl font-black text-gray-900 leading-tight mb-2 line-clamp-2" title="{{ $campaign->title }}">
                            {{ $campaign->title }}
                        </h3>

                        <div class="mt-2 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-6">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="truncate">By: <span class="text-gray-900">{{ $campaign->creator->name ?? 'Unknown' }}</span></span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mb-2 flex justify-between items-end">
                            <span class="text-sm font-black text-gray-900">{{ number_format($campaign->signatures_count) }} <span class="text-[10px] text-gray-400 font-bold uppercase">Signs</span></span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Goal: {{ number_format($campaign->target_signatures) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $campaign->status === 'victorious' ? 'bg-yellow-500' : 'bg-orange-500' }} h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    {{-- Actions Footer --}}
                    <div class="border-t border-gray-100 pt-4 mt-6 flex flex-wrap items-center justify-between gap-2">
                        
                        {{-- Export CSV (Primary Action) --}}
                        <button wire:click="exportSignatures({{ $campaign->id }})" class="px-4 py-2.5 bg-gray-900 text-white hover:bg-gray-800 rounded-xl text-[10px] sm:text-xs font-bold uppercase tracking-widest flex items-center gap-1.5 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export CSV
                        </button>

                        <div class="flex items-center gap-1">
                            {{-- Mark Victorious --}}
                            @if($campaign->status !== 'victorious')
                                <button onclick="confirm('Declare victory for this campaign? This will show a success banner publicly.') || event.stopImmediatePropagation()" wire:click="markVictorious({{ $campaign->id }})" class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Declare Victory">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            @endif

                            {{-- Edit Link --}}
                            <a href="{{ route('admin.campaigns.edit', $campaign->slug ?? $campaign->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Campaign">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>

                            {{-- Delete --}}
                            <button onclick="confirm('Are you sure? This will delete the campaign and ALL signatures.') || event.stopImmediatePropagation()" wire:click="delete({{ $campaign->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete Campaign">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $campaigns->links() }}
        </div>
    </div>
</div>