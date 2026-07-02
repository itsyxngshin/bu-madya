<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Content Requests Board</h1>
        <p class="text-gray-500 mt-2">Review and approve announcements and spotlights submitted by organizations.</p>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Announcements Queue --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    Pending Announcements
                    <span class="bg-red-100 text-red-700 text-xs py-1 px-2 rounded-full">{{ $pendingAnnouncements->count() }}</span>
                </h2>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($pendingAnnouncements as $announcement)
                    <li class="p-6 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">
                                    {{ $announcement->type->name }} • From: {{ $announcement->user->name ?? 'Unknown' }}
                                </span>
                                <h3 class="text-md font-bold text-gray-900">{{ $announcement->title }}</h3>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-4 bg-gray-100 p-3 rounded-lg">{{ $announcement->message }}</p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>Runs: {{ $announcement->start_at ? $announcement->start_at->format('M d, Y') : 'Immediately' }} - {{ $announcement->end_at ? $announcement->end_at->format('M d, Y') : 'Forever' }}</span>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="approve('announcement', {{ $announcement->id }})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">Approve</button>
                            <button wire:click="confirmReject('announcement', {{ $announcement->id }})" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Reject</button>
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-gray-500 text-sm">No pending announcements.</li>
                @endforelse
            </ul>
        </div>

        {{-- Spotlights Queue --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    Pending Spotlights
                    <span class="bg-blue-100 text-blue-700 text-xs py-1 px-2 rounded-full">{{ $pendingSpotlights->count() }}</span>
                </h2>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($pendingSpotlights as $spotlight)
                    <li class="p-6 hover:bg-gray-50 transition">
                        <div class="mb-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">
                                {{ $spotlight->category->name }} • From: {{ $spotlight->user->name ?? 'Unknown' }}
                            </span>
                            <h3 class="text-md font-bold text-gray-900">{{ $spotlight->title }}</h3>
                        </div>
                        
                        {{-- Image Preview --}}
                        <div class="w-full aspect-[21/9] bg-gray-200 rounded-lg mb-4 overflow-hidden border border-gray-300">
                            <img src="{{ Storage::url($spotlight->image_path) }}" alt="Preview" class="w-full h-full object-cover">
                        </div>

                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>Runs: {{ $spotlight->start_at ? $spotlight->start_at->format('M d, Y') : 'Immediately' }} - {{ $spotlight->end_at ? $spotlight->end_at->format('M d, Y') : 'Forever' }}</span>
                            @if($spotlight->link)
                                <a href="{{ $spotlight->link }}" target="_blank" class="text-blue-600 hover:underline">Test Link &rarr;</a>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="approve('spotlight', {{ $spotlight->id }})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">Approve</button>
                            <button wire:click="confirmReject('spotlight', {{ $spotlight->id }})" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Reject</button>
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-gray-500 text-sm">No pending spotlights.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Rejection Modal --}}
    @if($showRejectModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl">
                <h3 class="text-xl font-bold mb-2">Reject Request</h3>
                <p class="text-sm text-gray-600 mb-4">Please provide a reason. This will be visible to the requester.</p>
                
                <textarea wire:model="rejectionReason" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm mb-2" placeholder="e.g., Image resolution is too low..."></textarea>
                @error('rejectionReason') <span class="text-red-500 text-xs block mb-4">{{ $message }}</span> @enderror
                
                <div class="flex justify-end gap-3 mt-4">
                    <button wire:click="$set('showRejectModal', false)" class="px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button wire:click="processReject" class="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg">Confirm Rejection</button>
                </div>
            </div>
        </div>
    @endif
</div>
