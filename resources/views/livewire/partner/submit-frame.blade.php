<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8 font-sans pb-24">
    <div class="max-w-7xl mx-auto">

        {{-- Header (Scaled down slightly) --}}
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Campaign Frames</h1>
            <p class="text-sm font-bold text-gray-500 mt-1">Submit custom frames for BU MADYA approval.</p>
        </div>

        {{-- STRICT CSS GRID: 1/3 Form, 2/3 History --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">

            {{-- LEFT COLUMN: Submission Form (Strictly 1 column out of 3) --}}
            <div class="lg:col-span-1 sticky top-24">
                <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200">

                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-3">Create New Campaign</h2>

                    @if (session()->has('message'))
                        <div class="bg-green-50 text-green-700 p-3 rounded-lg font-bold text-xs mb-5 border border-green-200 flex items-start gap-2 animate-fade-in-down">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ session('message') }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-4">

                        {{-- Title --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Campaign Title</label>
                            <input type="text" wire:model="title" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white text-sm py-2 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="e.g. Red Cross Youth Month">
                            @error('title') <span class="text-[10px] text-red-500 mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Instructions / Description</label>
                            <textarea wire:model="description" rows="2" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white text-sm py-2 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="Briefly describe the goals..."></textarea>
                            @error('description') <span class="text-[10px] text-red-500 mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                        </div>

                        {{-- Multiple Image Upload Zone --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest">Frame Variations</label>
                                <span class="text-[9px] font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">Max 5 PNGs</span>
                            </div>

                            {{-- Restricted max-w-xs so it doesn't blow up in size --}}
                            <div class="relative w-full max-w-xs mx-auto aspect-square rounded-xl border-2 border-dashed {{ count($frame_images ?? []) > 0 ? 'border-red-300 bg-red-50/30 p-3' : 'border-gray-300 bg-gray-50 hover:bg-gray-100 p-6 flex flex-col items-center justify-center' }} transition-all overflow-hidden group">
                                
                                <input type="file" wire:model="frame_images" accept="image/png" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                                @if (count($frame_images ?? []) > 0)
                                    <div class="grid grid-cols-2 gap-2 relative z-10 pointer-events-none w-full">
                                        @foreach($frame_images as $index => $img)
                                            <div class="aspect-square rounded-lg bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] relative border border-gray-200 shadow-sm overflow-hidden bg-repeat bg-white">
                                                <img src="{{ $img->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-contain p-1">
                                                <div class="absolute top-1 right-1 bg-gray-900/90 text-white text-[8px] font-black px-1 py-0.5 rounded shadow-sm">Var {{ $index + 1 }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-2 text-center text-[9px] font-bold text-red-600 pointer-events-none relative z-10 uppercase tracking-widest">
                                        Click to replace files
                                    </div>
                                @else
                                    <div class="text-center pointer-events-none flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-700">Upload PNGs</p>
                                        <p class="text-[9px] uppercase tracking-widest mt-1 font-bold text-gray-400">1080x1080</p>
                                    </div>
                                @endif

                                <div wire:loading wire:target="frame_images" class="absolute inset-0 bg-white/95 backdrop-blur-sm z-30 flex flex-col items-center justify-center">
                                    <span class="text-[10px] font-black text-red-600 uppercase tracking-widest animate-pulse">Uploading...</span>
                                </div>
                            </div>
                            @error('frame_images') <span class="text-[10px] text-red-500 mt-1.5 block font-bold uppercase text-center">{{ $message }}</span> @enderror
                            @error('frame_images.*') <span class="text-[10px] text-red-500 mt-1.5 block font-bold uppercase text-center">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-2.5 mt-2 bg-gray-900 text-white font-black rounded-lg shadow-md hover:bg-red-600 transition-all text-xs uppercase tracking-widest disabled:opacity-50" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Submit Campaign</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>

                    </form>
                </div>
            </div>

            {{-- RIGHT COLUMN: Submission History (Strictly 2 columns out of 3) --}}
            <div class="lg:col-span-2 overflow-hidden">
                <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 min-h-[calc(100vh-10rem)]">
                    
                    <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                        <h2 class="text-xs font-black text-gray-800 uppercase tracking-widest">Your Campaigns</h2>
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-0.5 rounded-full">{{ count($myFrames) }} Total</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($myFrames as $frame)
                            @php
                                $images = is_array($frame->frame_images) ? $frame->frame_images : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
                                $coverImage = count($images) > 0 ? asset('storage/' . $images[0]) : '';
                            @endphp
                            
                            <div class="border border-gray-100 rounded-xl p-3 flex gap-3 bg-gray-50/50 hover:bg-white hover:shadow-md transition-all duration-200">

                                {{-- Thumbnail Header --}}
                                <div class="w-20 h-20 shrink-0 rounded-lg bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] relative overflow-hidden ring-1 ring-gray-200 bg-repeat bg-white">
                                    <img src="{{ $coverImage }}" class="absolute inset-0 w-full h-full object-contain p-1">
                                    
                                    @if(count($images) > 1)
                                        <div class="absolute bottom-1 right-1 bg-gray-900/80 text-white text-[8px] font-black px-1.5 py-0.5 rounded-md flex items-center gap-0.5">
                                            {{ count($images) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Info Body --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-xs font-black text-gray-900 truncate" title="{{ $frame->title }}">{{ $frame->title }}</h3>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $frame->created_at->format('M d, Y') }}</p>
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        @if($frame->is_approved)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-700 ring-1 ring-green-200 text-[8px] font-black uppercase tracking-widest rounded">
                                                Active
                                            </span>
                                            <a href="{{ route('open.frames.show', $frame->slug) }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors" title="View Live">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200 text-[8px] font-black uppercase tracking-widest rounded">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-10 text-center flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                                <h3 class="text-xs font-black text-gray-800">No campaigns yet</h3>
                                <p class="text-[10px] text-gray-500 mt-1 max-w-xs mx-auto">Upload your first frame variation to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>