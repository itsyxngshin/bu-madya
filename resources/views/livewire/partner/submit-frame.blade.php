<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-7xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 leading-tight">Campaign Frames</h1>
            <p class="text-sm font-bold text-gray-500 mt-1">Submit custom Twibbonize frames for BU MADYA approval.</p>
        </div>

        {{-- BULLETPROOF FLEX LAYOUT --}}
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- LEFT COLUMN: Submission Form (Strict 1/3 width on desktop) --}}
            <div class="w-full lg:w-1/3 shrink-0 lg:sticky lg:top-28">
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100">

                    @if (session()->has('message'))
                        <div class="bg-green-50 text-green-700 px-4 py-4 rounded-xl font-bold text-sm mb-6 border border-green-200 flex items-center gap-3 animate-fade-in-down">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ session('message') }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-5">

                        {{-- Title --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Campaign Title</label>
                            <input type="text" wire:model="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-2.5 focus:ring-red-500" placeholder="e.g. Red Cross Youth Month">
                            @error('title') <span class="text-xs text-red-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Instructions / Description</label>
                            <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-2.5 focus:ring-red-500" placeholder="Tell students what this campaign is about..."></textarea>
                            @error('description') <span class="text-xs text-red-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Multiple Image Upload Zone --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Frame Variations (.PNG Only)</label>

                            <div class="relative w-full rounded-2xl border-2 border-dashed {{ count($frame_images ?? []) > 0 ? 'border-red-400 bg-gray-50 p-4' : 'border-gray-300 bg-gray-50 hover:bg-gray-100 p-8 aspect-square flex flex-col items-center justify-center' }} transition-colors overflow-hidden group">
                                
                                <input type="file" wire:model="frame_images" accept="image/png" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                                @if (count($frame_images ?? []) > 0)
                                    <div class="grid grid-cols-2 gap-3 relative z-10 pointer-events-none">
                                        @foreach($frame_images as $index => $img)
                                            <div class="aspect-square rounded-xl bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] relative border border-gray-200 shadow-sm overflow-hidden bg-repeat">
                                                <img src="{{ $img->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-contain p-2">
                                                <div class="absolute top-1 right-1 bg-gray-900/80 text-white text-[8px] font-bold px-1.5 py-0.5 rounded">Var {{ $index + 1 }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 text-center text-[10px] font-bold text-red-600 pointer-events-none relative z-10 uppercase tracking-widest">Click here to change files</div>
                                @else
                                    <div class="text-center pointer-events-none">
                                        <svg class="w-10 h-10 mb-3 text-gray-300 mx-auto group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <p class="text-sm font-bold text-gray-700">Click or drag frames</p>
                                        <p class="text-[9px] uppercase tracking-widest mt-1 font-bold text-gray-400">Max 5 Transparent PNGs</p>
                                    </div>
                                @endif

                                <div wire:loading wire:target="frame_images" class="absolute inset-0 bg-white/90 backdrop-blur-sm z-30 flex items-center justify-center">
                                    <span class="text-xs font-bold text-gray-900 uppercase tracking-widest animate-pulse">Processing...</span>
                                </div>
                            </div>
                            @error('frame_images') <span class="text-xs text-red-500 mt-2 block font-bold text-center">{{ $message }}</span> @enderror
                            @error('frame_images.*') <span class="text-xs text-red-500 mt-2 block font-bold text-center">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 mt-2 bg-gray-900 text-white font-black rounded-xl shadow-lg hover:bg-gray-800 transition-all text-sm uppercase tracking-widest disabled:opacity-50" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Submit Campaign</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>

                    </form>
                </div>
            </div>

            {{-- RIGHT COLUMN: Submission History (Flexible remaining width) --}}
            <div class="w-full lg:flex-1">
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-6">Your Previous Submissions</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @forelse($myFrames as $frame)
                            @php
                                // Handle both old single images and new array format
                                $images = is_array($frame->frame_images) ? $frame->frame_images : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
                                $coverImage = count($images) > 0 ? asset('storage/' . $images[0]) : '';
                            @endphp
                            
                            <div class="border border-gray-100 rounded-2xl p-4 flex flex-col gap-3 bg-gray-50 hover:bg-white hover:shadow-md transition-all">

                                {{-- Thumbnail --}}
                                <div class="w-full aspect-square rounded-xl bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] relative overflow-hidden border border-gray-200">
                                    <img src="{{ $coverImage }}" class="absolute inset-0 w-full h-full object-contain p-2">
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <h3 class="text-sm font-bold text-gray-900 truncate" title="{{ $frame->title }}">{{ $frame->title }}</h3>
                                            @if(count($images) > 1)
                                                <span class="bg-gray-200 text-gray-600 text-[9px] font-black px-1.5 py-0.5 rounded shrink-0">{{ count($images) }} Vars</span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $frame->created_at->format('M d, Y') }}</p>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between">
                                        @if($frame->is_approved)
                                            <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase tracking-widest rounded-md">Approved</span>
                                            <a href="{{ route('open.frames.show', $frame->slug) }}" target="_blank" class="p-1.5 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition" title="View Live">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        @else
                                            <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 text-[9px] font-black uppercase tracking-widest rounded-md">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-gray-400 text-sm font-bold border-2 border-dashed border-gray-200 rounded-2xl">
                                You haven't submitted any frames yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
