<div class="p-6 max-w-7xl mx-auto min-h-screen">

    {{-- Header & Filters --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 leading-tight">Frame Approvals</h1>
            <p class="text-sm font-bold text-gray-500 mt-1">Review and publish Twibbonize campaign frames.</p>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search frames or orgs..." class="rounded-xl border-gray-200 text-sm focus:ring-red-500 w-full md:w-64">
            <select wire:model.live="filter" class="rounded-xl border-gray-200 text-sm focus:ring-red-500 font-bold text-gray-600">
                <option value="all">All Submissions</option>
                <option value="pending">Pending Only</option>
                <option value="approved">Approved</option>
            </select>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl font-bold text-sm mb-6 border border-green-200 animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif

    {{-- Frames Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($frames as $frame)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group">

                {{-- Transparent Checkerboard Background for PNG viewing --}}
                <div class="relative w-full aspect-square bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')]">
                    <img src="{{ asset('storage/' . $frame->frame_image) }}" class="absolute inset-0 w-full h-full object-contain">

                    {{-- Status Badge Overlay --}}
                    <div class="absolute top-3 right-3">
                        @if($frame->is_approved)
                            <span class="bg-green-500 text-white text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md shadow-sm">Published</span>
                        @else
                            <span class="bg-yellow-500 text-white text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md shadow-sm">Pending</span>
                        @endif
                    </div>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 truncate" title="{{ $frame->title }}">{{ $frame->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1 truncate">By: {{ $frame->user->name }}</p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                        {{-- Approve / Revoke Toggle --}}
                        <button wire:click="toggleApproval({{ $frame->id }})"
                                class="flex-1 text-xs font-bold uppercase tracking-widest px-3 py-2 rounded-lg transition
                                {{ $frame->is_approved ? 'bg-orange-50 text-orange-700 hover:bg-orange-100' : 'bg-gray-900 text-white hover:bg-gray-800' }}">
                            {{ $frame->is_approved ? 'Revoke' : 'Approve' }}
                        </button>

                        <a href="{{ route('open.frames.show', $frame->slug) }}" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-lg transition" title="Preview">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>

                        <button wire:click="deleteFrame({{ $frame->id }})" wire:confirm="Delete this frame permanently?" class="p-2 text-gray-400 hover:text-red-600 bg-gray-50 hover:bg-red-50 rounded-lg transition" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400 font-bold border-2 border-dashed border-gray-200 rounded-2xl">
                No frames found matching your criteria.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $frames->links() }}
    </div>
</div>
