<div class="relative min-h-screen bg-slate-50 overflow-x-hidden font-sans pb-24 z-0">
    
    {{-- Rainbow Blobs Background --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] rounded-full bg-gradient-to-br from-fuchsia-400 to-purple-500 blur-[100px] opacity-40 mix-blend-multiply"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] rounded-full bg-gradient-to-tl from-yellow-300 to-rose-400 blur-[100px] opacity-40 mix-blend-multiply"></div>
        <div class="absolute top-[20%] -right-[5%] w-[40vw] h-[40vw] max-w-[500px] max-h-[500px] rounded-full bg-gradient-to-bl from-cyan-300 to-blue-500 blur-[100px] opacity-40 mix-blend-multiply"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start w-full">

            {{-- LEFT COLUMN: Details (4/12) --}}
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-12 min-w-0 w-full">
                
                {{-- Main Info Card --}}
                <div class="bg-white/60 backdrop-blur-2xl p-6 md:p-8 rounded-[2rem] border border-white/80 shadow-2xl shadow-purple-900/5 w-full overflow-hidden">
                    <div class="flex items-center justify-between mb-5">
                        <span class="inline-block px-3 py-1 bg-white/80 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm border border-rose-100 backdrop-blur-md">
                            Campaign Frame
                        </span>
                        
                        {{-- NEW: Usage Counter Badge --}}
                        <div class="flex items-center gap-1.5 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                            <span>{{ number_format($frame->usage_count) }} Used</span>
                        </div>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-4 tracking-tight drop-shadow-sm break-all w-full">
                        {{ $frame->title }}
                    </h1>
                    
                    <p class="text-sm text-gray-700 mb-8 leading-relaxed font-medium break-words w-full">{{ $frame->description }}</p>
                    
                    <div class="flex items-center gap-3 bg-white/80 p-3 rounded-2xl border border-white shadow-sm max-w-full">
                        <div class="w-10 h-10 shrink-0 bg-gradient-to-tr from-gray-100 to-gray-200 rounded-xl flex items-center justify-center font-black text-gray-500 text-xs uppercase shadow-inner">
                            {{ substr($frame->user->name ?? 'BU', 0, 2) }}
                        </div>
                        <div class="pr-3 min-w-0 flex-1">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Created By</p>
                            <p class="text-xs font-black text-gray-900 leading-tight truncate w-full">{{ $frame->user->name ?? 'BU MADYA' }}</p>
                        </div>
                    </div>
                </div>

                {{-- NEW: Caption Card (Only shows if caption exists) --}}
                @if($frame->caption)
                <div class="bg-white/60 backdrop-blur-2xl p-5 md:p-6 rounded-[2rem] border border-white/80 shadow-2xl shadow-indigo-900/5" x-data="{ copiedCap: false }">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            Official Caption
                        </p>
                        <button @click="navigator.clipboard.writeText($refs.captionBlock.innerText); copiedCap = true; setTimeout(() => copiedCap = false, 2000)" 
                                class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg transition-colors"
                                :class="copiedCap ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            <span x-show="!copiedCap">Copy</span>
                            <span x-show="copiedCap" style="display: none;">Copied!</span>
                        </button>
                    </div>
                    <div class="bg-white/80 border border-white/50 shadow-inner rounded-xl p-4 text-xs text-gray-700 leading-relaxed font-medium whitespace-pre-wrap max-h-48 overflow-y-auto" x-ref="captionBlock">{{ $frame->caption }}</div>
                </div>
                @endif

                {{-- Share Card --}}
                <div class="bg-white/60 backdrop-blur-2xl p-5 md:p-6 rounded-[2rem] border border-white/80 shadow-2xl shadow-blue-900/5" x-data="{ copied: false }">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Share Campaign Link</p>
                    <div class="flex items-center gap-2 w-full">
                        <input type="text" readonly value="{{ url()->current() }}" class="flex-1 w-full min-w-0 bg-white/80 border border-white/50 shadow-inner rounded-xl text-xs py-3 px-4 text-gray-600 focus:outline-none font-medium">
                        <button @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="bg-gray-900 hover:bg-gray-800 text-white p-3 rounded-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0 w-11 h-11 shadow-lg">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" style="display: none;" class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: The Studio (8/12) --}}
            <div class="lg:col-span-8 w-full min-w-0 flex flex-col items-center lg:items-start">
                
                <div class="w-full bg-white/70 backdrop-blur-2xl p-4 md:p-8 lg:p-10 rounded-[2.5rem] shadow-2xl shadow-rose-900/10 border border-white"
                     @php
                         $images = is_array($frame->frame_images) ? array_filter($frame->frame_images) : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
                         $frameUrls = array_map(fn($path) => asset('storage/' . $path), $images);
                     @endphp
                     x-data="studio(@js($frameUrls))"
                     x-init="init()"
                >
                    
                    {{-- Toolbar --}}
                    <div class="flex flex-col gap-4 mb-8 bg-white/90 shadow-sm p-4 rounded-3xl border border-gray-100 max-w-[500px] mx-auto w-full">
                        
                        <label class="cursor-pointer w-full py-4 md:py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-md hover:shadow-lg hover:scale-[1.02] transition-all text-center flex items-center justify-center gap-2.5 relative overflow-hidden group" 
                               style="background: linear-gradient(90deg, #ec4899, #f97316, #eab308); color: #ffffff;">
                            <div class="absolute inset-0 bg-white/20 -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                            <svg class="w-5 h-5 shrink-0 relative z-10 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <input type="file" accept="image/png, image/jpeg" class="hidden" @change="uploadPhoto">
                            <span x-show="!userImg" class="relative z-10 drop-shadow-sm">Upload Photo</span>
                            <span x-show="userImg" style="display: none;" class="relative z-10 drop-shadow-sm">Change Photo</span>
                        </label>

                        <div x-show="userImg" style="display: none;" class="flex items-center gap-3 w-full px-2 py-1">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                            <input type="range" x-model="scale" @input="draw" min="0.1" max="5" step="0.01" class="w-full flex-1 min-w-[100px] h-2 bg-gray-200 rounded-full appearance-none cursor-pointer accent-indigo-500">
                            <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        </div>
                    </div>

                    {{-- Canvas Area --}}
                    <div class="relative w-full max-w-[500px] mx-auto aspect-square bg-white rounded-3xl overflow-hidden shadow-inner ring-4 ring-white/50 group touch-none">
                        <div x-show="!userImg" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none bg-gray-50/80 backdrop-blur-sm z-10 transition-opacity duration-300">
                            <div class="w-20 h-20 bg-gradient-to-tr from-pink-100 to-yellow-100 rounded-full shadow-lg flex items-center justify-center mb-6 border border-white">
                                <svg class="w-10 h-10 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-gray-600">Select a photo to begin</p>
                        </div>

                        <canvas x-ref="canvas"
                                width="1080" height="1080"
                                class="w-full h-full cursor-move origin-top-left touch-none bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] bg-repeat"
                                @mousedown="startDrag" @mousemove="drag" @mouseup="endDrag" @mouseleave="endDrag"
                                @touchstart.prevent="handleTouchStart" @touchmove.prevent="handleTouchMove" @touchend.prevent="handleTouchEnd">
                        </canvas>
                    </div>

                    {{-- Variation Selector --}}
                    <div x-show="frames.length > 1" style="display: none;" class="mt-8 bg-white/80 p-5 rounded-2xl border border-white shadow-sm max-w-[500px] mx-auto backdrop-blur-md">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4 text-center">Select Variation</p>
                        <div class="flex flex-wrap justify-center gap-3">
                            <template x-for="(frameUrl, index) in frames" :key="index">
                                <button @click="changeFrame(frameUrl)" 
                                        :class="{'ring-4 ring-rose-400 scale-110 shadow-lg z-10': activeFrame === frameUrl, 'border border-gray-200 hover:border-rose-300 opacity-60 hover:opacity-100 hover:scale-105': activeFrame !== frameUrl}"
                                        class="w-16 h-16 rounded-2xl bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] overflow-hidden transition-all duration-300 bg-repeat focus:outline-none relative bg-white">
                                    <img :src="frameUrl" class="absolute inset-0 w-full h-full object-contain p-1.5">
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-8 text-center max-w-[500px] mx-auto">
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-black mb-5 flex items-center justify-center gap-2" x-show="userImg" style="display: none;">
                            <svg class="w-4 h-4 text-pink-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                            Pinch to Zoom / Drag to Reposition
                        </p>

                        <button @click="download" x-show="userImg" style="display: none;" class="w-full bg-gray-900 text-white py-4 rounded-xl font-black uppercase tracking-widest shadow-xl shadow-gray-900/20 hover:bg-black transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 shrink-0 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Download Frame
                        </button>
                    </div>

                    {{-- SUCCESS MODAL PROMPT --}}
                    <div x-show="showSuccess" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4">
                        <div x-show="showSuccess" x-transition.opacity.duration.300ms class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
                        <div x-show="showSuccess" 
                             @click.away="showSuccess = false"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                             class="bg-white rounded-[2rem] p-8 max-w-sm w-full relative z-10 shadow-2xl flex flex-col items-center text-center">
                             
                             <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-6 shadow-inner ring-4 ring-green-50">
                                 <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                             </div>
                             
                             <h3 class="text-2xl font-black text-gray-900 mb-2">Frame Saved!</h3>
                             <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">Your campaign frame has been successfully downloaded. Don't forget to copy the official caption to post with it!</p>
                             
                             <button @click="showSuccess = false" class="w-full bg-gray-900 text-white font-black uppercase tracking-widest py-3.5 rounded-xl hover:bg-gray-800 transition-colors shadow-lg">
                                Awesome!
                             </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-bold text-2xl tracking-tight">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-6 text-sm">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                </p>
                
                {{-- Social Media Links --}}
                <div class="flex space-x-4">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>

                    {{-- X (Twitter) --}}
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
            
            <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                    <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                    <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                    <li class="pt-2 mt-2 border-t border-gray-800">
                        <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-white hover:translate-x-1 transition inline-block">Privacy Policy</a>
                    </li>
            </ul>

            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500 uppercase tracking-widest text-xs">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-inner">
                    <span class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studio', (frameUrls) => ({
            canvas: null, ctx: null,
            userImg: null, frameImg: null,
            scale: 1, dx: 0, dy: 0,
            
            isDragging: false, 
            startX: 0, startY: 0,
            initialDx: 0, initialDy: 0,
            initialPinchDist: null,
            initialScale: 1,

            frames: Array.isArray(frameUrls) ? frameUrls : [],
            activeFrame: null,
            imageError: false,
            showSuccess: false,

            init() {
                this.canvas = this.$refs.canvas;
                this.ctx = this.canvas.getContext('2d');
                if (this.frames.length > 0 && this.frames[0] !== '') {
                    this.activeFrame = this.frames[0];
                    this.loadFrameImage(this.activeFrame);
                }
            },
            
            loadFrameImage(url) {
                this.imageError = false;
                this.frameImg = new Image();
                this.frameImg.crossOrigin = "Anonymous"; 
                this.frameImg.onload = () => this.draw();
                this.frameImg.onerror = () => {
                    this.imageError = true;
                    this.frameImg = null; 
                    this.draw(); 
                };
                this.frameImg.src = url; 
            },

            changeFrame(url) {
                this.activeFrame = url;
                this.loadFrameImage(url);
            },

            uploadPhoto(e) {
                let file = e.target.files[0];
                if(!file) return;

                let reader = new FileReader();
                reader.onload = (event) => {
                    this.userImg = new Image();
                    this.userImg.onload = () => {
                        this.dx = 0; this.dy = 0;
                        let scaleX = 1080 / this.userImg.width;
                        let scaleY = 1080 / this.userImg.height;
                        this.scale = Math.max(scaleX, scaleY); 
                        this.draw();
                    }
                    this.userImg.src = event.target.result;
                }
                reader.readAsDataURL(file);
            },

            draw() {
                if (!this.ctx) return;
                this.ctx.clearRect(0, 0, 1080, 1080);
                if(this.userImg) {
                    let w = this.userImg.width * this.scale;
                    let h = this.userImg.height * this.scale;
                    let x = (1080 - w) / 2 + this.dx;
                    let y = (1080 - h) / 2 + this.dy;
                    this.ctx.drawImage(this.userImg, x, y, w, h);
                }
                if(this.frameImg && !this.imageError) {
                    this.ctx.drawImage(this.frameImg, 0, 0, 1080, 1080);
                }
            },

            startDrag(e) {
                if(!this.userImg) return;
                this.isDragging = true;
                this.startX = e.clientX;
                this.startY = e.clientY;
                this.initialDx = this.dx;
                this.initialDy = this.dy;
            },
            drag(e) {
                if(!this.isDragging) return;
                let canvasRect = this.canvas.getBoundingClientRect();
                let scaleRatio = 1080 / canvasRect.width;
                this.dx = this.initialDx + ((e.clientX - this.startX) * scaleRatio);
                this.dy = this.initialDy + ((e.clientY - this.startY) * scaleRatio);
                this.draw();
            },
            endDrag() {
                this.isDragging = false;
            },

            getDistance(touch1, touch2) {
                let dx = touch1.clientX - touch2.clientX;
                let dy = touch1.clientY - touch2.clientY;
                return Math.sqrt(dx * dx + dy * dy);
            },
            handleTouchStart(e) {
                if(!this.userImg) return;
                if (e.touches.length === 2) {
                    this.isDragging = false;
                    this.initialPinchDist = this.getDistance(e.touches[0], e.touches[1]);
                    this.initialScale = parseFloat(this.scale);
                } else if (e.touches.length === 1) {
                    this.isDragging = true;
                    this.startX = e.touches[0].clientX;
                    this.startY = e.touches[0].clientY;
                    this.initialDx = this.dx;
                    this.initialDy = this.dy;
                }
            },
            handleTouchMove(e) {
                if(!this.userImg) return;
                if (e.touches.length === 2 && this.initialPinchDist) {
                    let newDist = this.getDistance(e.touches[0], e.touches[1]);
                    let zoomFactor = newDist / this.initialPinchDist;
                    let newScale = this.initialScale * zoomFactor;
                    if (newScale < 0.1) newScale = 0.1;
                    if (newScale > 5) newScale = 5;
                    this.scale = newScale;
                    this.draw();
                } else if (e.touches.length === 1 && this.isDragging) {
                    let canvasRect = this.canvas.getBoundingClientRect();
                    let scaleRatio = 1080 / canvasRect.width;
                    this.dx = this.initialDx + ((e.touches[0].clientX - this.startX) * scaleRatio);
                    this.dy = this.initialDy + ((e.touches[0].clientY - this.startY) * scaleRatio);
                    this.draw();
                }
            },
            handleTouchEnd(e) {
                this.isDragging = false;
                this.initialPinchDist = null;
            },

            download() {
                let link = document.createElement('a');
                link.download = 'BU-MADYA-' + Date.now() + '.png';
                link.href = this.canvas.toDataURL('image/png');
                link.click();
                this.showSuccess = true;
                @this.incrementUsage();
            }
        }));
    });
</script>
@endpush