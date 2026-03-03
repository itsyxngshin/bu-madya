<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-7xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 leading-tight">Campaign Frames</h1>
            <p class="text-sm font-bold text-gray-500 mt-1">Submit custom Twibbonize frames for BU MADYA approval.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- LEFT COLUMN: Submission Form --}}
            <div class="lg:col-span-5">
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100">

                    @if (session()->has('message'))
                        <div class="bg-green-50 text-green-700 px-4 py-4 rounded-xl font-bold text-sm mb-6 border border-green-200 flex items-center gap-3 animate-fade-in-down">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-6">

                        {{-- Title --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Campaign Title</label>
                            <input type="text" wire:model="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3 focus:ring-red-500" placeholder="e.g. Red Cross Youth Month 2026">
                            @error('title') <span class="text-xs text-red-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Instructions / Description</label>
                            <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3 focus:ring-red-500" placeholder="Tell students what this campaign is about..."></textarea>
                            @error('description') <span class="text-xs text-red-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Image Upload Zone --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Frame Variations (.PNG Only, Max 5)</label>

                            <div class="relative w-full rounded-2xl border-2 border-dashed {{ count($frame_images) > 0 ? 'border-red-400 bg-gray-50 p-4' : 'border-gray-300 bg-gray-50 hover:bg-gray-100 p-12 aspect-video flex flex-col items-center justify-center' }} transition-colors overflow-hidden group">
                                
                                {{-- [NEW] Added "multiple" attribute --}}
                                <input type="file" wire:model="frame_images" accept="image/png" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                                @if (count($frame_images) > 0)
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 relative z-10 pointer-events-none">
                                        @foreach($frame_images as $index => $img)
                                            <div class="aspect-square rounded-xl bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] relative border border-gray-200 shadow-sm overflow-hidden bg-repeat">
                                                <img src="{{ $img->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-contain p-2">
                                                <div class="absolute top-1 right-1 bg-gray-900/80 text-white text-[8px] font-bold px-1.5 rounded">Var {{ $index + 1 }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 text-center text-xs font-bold text-red-600 pointer-events-none relative z-10">Click anywhere here to change files</div>
                                @else
                                    <div class="text-center pointer-events-none">
                                        <svg class="w-10 h-10 mb-3 text-gray-300 mx-auto group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <p class="text-sm font-bold text-gray-700">Click or drag multiple frames</p>
                                        <p class="text-[9px] uppercase tracking-widest mt-1 font-bold text-gray-400">Select up to 5 Transparent PNGs</p>
                                    </div>
                                @endif
                            </div>
                            @error('frame_images') <span class="text-xs text-red-500 mt-2 block font-bold text-center">{{ $message }}</span> @enderror
                            @error('frame_images.*') <span class="text-xs text-red-500 mt-2 block font-bold text-center">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 bg-gray-900 text-white font-black rounded-xl shadow-lg hover:bg-gray-800 transition-all text-sm uppercase tracking-widest disabled:opacity-50" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Submit Frame for Approval</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>

                    </form>
                </div>
            </div>

            {{-- RIGHT COLUMN: Submission History --}}
            <div class="lg:col-span-7">
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-6">Your Previous Submissions</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($myFrames as $frame)
                            <div class="border border-gray-100 rounded-2xl p-4 flex gap-4 items-center bg-gray-50 hover:bg-white transition-colors">

                                {{-- Thumbnail --}}
                                <div class="w-16 h-16 shrink-0 rounded-xl bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $frame->frame_image) }}" class="absolute inset-0 w-full h-full object-cover">
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 truncate">{{ $frame->title }}</h3>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $frame->created_at->format('M d, Y') }}</p>

                                    <div class="mt-2">
                                        @if($frame->is_approved)
                                            <span class="inline-block px-2 py-0.5 bg-green-100 text-green-700 text-[9px] font-black uppercase tracking-widest rounded">Approved</span>
                                            <a href="{{ route('open.frames.show', $frame->slug) }}" target="_blank" class="inline-block ml-2 text-[10px] font-bold text-blue-600 hover:underline">View Live &rarr;</a>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[9px] font-black uppercase tracking-widest rounded">Pending Approval</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center text-gray-400 text-sm font-bold border-2 border-dashed border-gray-200 rounded-xl">
                                You haven't submitted any frames yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
