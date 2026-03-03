<div class="relative min-h-screen bg-slate-50 overflow-x-hidden font-sans pb-24 z-0">
    
    {{-- Rainbow Blobs Background --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] rounded-full bg-gradient-to-br from-fuchsia-400 to-purple-500 blur-[100px] opacity-40 mix-blend-multiply"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] rounded-full bg-gradient-to-tl from-yellow-300 to-rose-400 blur-[100px] opacity-40 mix-blend-multiply"></div>
        <div class="absolute top-[20%] -right-[5%] w-[40vw] h-[40vw] max-w-[500px] max-h-[500px] rounded-full bg-gradient-to-bl from-cyan-300 to-blue-500 blur-[100px] opacity-40 mix-blend-multiply"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        {{-- BULLETPROOF 12-COLUMN GRID (1/3 and 2/3 Split) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start w-full">

            {{-- LEFT COLUMN: Details (Strictly 4/12 = 1/3 Width) --}}
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-12 min-w-0 w-full">
                
                <div class="bg-white/60 backdrop-blur-2xl p-6 md:p-8 rounded-[2rem] border border-white/80 shadow-2xl shadow-purple-900/5">
                    <span class="inline-block px-3 py-1 bg-white/80 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-full mb-5 shadow-sm border border-rose-100 backdrop-blur-md">
                        Campaign Frame
                    </span>
                    
                    {{-- [FIXED] Changed to break-all to forcefully wrap unbroken strings --}}
                    <h1 class="break-all text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-4 tracking-tight drop-shadow-sm">{{ $frame->title }}</h1>
                    <p class="text-sm text-gray-700 mb-8 leading-relaxed font-medium break-words">{{ $frame->description }}</p>
                    
                    <div class="flex items-center gap-3 bg-white/80 p-3 rounded-2xl border border-white shadow-sm inline-flex max-w-full">
                        <div class="w-10 h-10 shrink-0 bg-gradient-to-tr from-gray-100 to-gray-200 rounded-xl flex items-center justify-center font-black text-gray-500 text-xs uppercase shadow-inner">
                            {{ substr($frame->user->name ?? 'BU', 0, 2) }}
                        </div>
                        <div class="pr-3 min-w-0">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Created By</p>
                            <p class="text-xs font-black text-gray-900 leading-tight truncate">{{ $frame->user->name ?? 'BU MADYA' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/60 backdrop-blur-2xl p-5 md:p-6 rounded-[2rem] border border-white/80 shadow-2xl shadow-blue-900/5" x-data="{ copied: false }">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Share Campaign Link</p>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ url()->current() }}" class="flex-1 w-full bg-white/80 border border-white/50 shadow-inner rounded-xl text-xs py-3 px-4 text-gray-600 focus:outline-none font-medium min-w-0">
                        <button @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="bg-gray-900 hover:bg-gray-800 text-white p-3 rounded-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0 w-11 h-11 shadow-lg">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" style="display: none;" class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: The Studio (Strictly 8/12 = 2/3 Width) --}}
            <div class="lg:col-span-8 w-full min-w-0 flex flex-col items-center lg:items-start">
                
                <div class="w-full bg-white/70 backdrop-blur-2xl p-6 md:p-10 rounded-[2.5rem] shadow-2xl shadow-rose-900/10 border border-white"
                     @php
                         $images = is_array($frame->frame_images) ? array_filter($frame->frame_images) : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
                         $frameUrls = array_map(fn($path) => asset('storage/' . $path), $images);
                     @endphp
                     x-data="studio(@js($frameUrls))"
                     x-init="init()"
                >
                    
                    {{-- Toolbar [FIXED] Now stacked vertically using flex-col and gap-4 --}}
                    <div class="flex flex-col gap-4 mb-8 bg-white/90 shadow-sm p-4 rounded-3xl border border-gray-100 max-w-[500px] mx-auto w-full">
                        
                        <label class="cursor-pointer w-full py-4 md:py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-md hover:shadow-lg hover:scale-[1.02] transition-all text-center flex items-center justify-center gap-2.5 relative overflow-hidden group" 
                               style="background: linear-gradient(90deg, #ec4899, #f97316, #eab308); color: #ffffff;">
                            
                            <div class="absolute inset-0 bg-white/20 -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                            
                            <svg class="w-5 h-5 shrink-0 relative z-10 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <input type="file" accept="image/png, image/jpeg" class="hidden" @change="uploadPhoto">
                            <span x-show="!userImg" class="relative z-10 drop-shadow-sm">Upload Photo</span>
                            <span x-show="userImg" style="display: none;" class="relative z-10 drop-shadow-sm">Change Photo</span>
                        </label>

                        {{-- Zoom Slider underneath (with purple accent) --}}
                        <div x-show="userImg" style="display: none;" class="flex items-center gap-3 w-full px-2 py-1">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                            <input type="range" x-model="scale" @input="draw" min="0.1" max="5" step="0.01" class="w-full flex-1 min-w-[100px] h-2 bg-gray-200 rounded-full appearance-none cursor-pointer accent-indigo-500">
                            <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        </div>
                    </div>

                    {{-- Canvas Area --}}
                    <div class="relative w-full max-w-[500px] mx-auto aspect-square bg-white rounded-3xl overflow-hidden shadow-inner ring-4 ring-white/50 group">
                        
                        <div x-show="!userImg" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none bg-gray-50/80 backdrop-blur-sm z-10 transition-opacity duration-300">
                            <div class="w-20 h-20 bg-gradient-to-tr from-pink-100 to-yellow-100 rounded-full shadow-lg flex items-center justify-center mb-6 border border-white">
                                <svg class="w-10 h-10 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-gray-600">Select a photo to begin</p>
                        </div>

                        {{-- Unified Mouse & Touch Event Bindings for Pinch-to-Zoom --}}
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
                                        class="w-16 h-16 rounded-xl bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] overflow-hidden transition-all duration-300 bg-repeat focus:outline-none relative bg-white">
                                    <img :src="frameUrl" class="absolute inset-0 w-full h-full object-contain p-1">
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
                             <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">Your campaign frame has been successfully downloaded to your device. You're ready to post!</p>
                             
                             <button @click="showSuccess = false" class="w-full bg-gray-900 text-white font-black uppercase tracking-widest py-3.5 rounded-xl hover:bg-gray-800 transition-colors shadow-lg">
                                Awesome!
                             </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studio', (frameUrls) => ({
            canvas: null, ctx: null,
            userImg: null, frameImg: null,
            scale: 1, dx: 0, dy: 0,
            
            // Drag & Pinch Variables
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

            // --- Desktop Mouse Events ---
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

            // --- Mobile Touch & Pinch Events ---
            getDistance(touch1, touch2) {
                let dx = touch1.clientX - touch2.clientX;
                let dy = touch1.clientY - touch2.clientY;
                return Math.sqrt(dx * dx + dy * dy);
            },
            handleTouchStart(e) {
                if(!this.userImg) return;
                if (e.touches.length === 2) {
                    // Pinch Started
                    this.isDragging = false;
                    this.initialPinchDist = this.getDistance(e.touches[0], e.touches[1]);
                    this.initialScale = parseFloat(this.scale);
                } else if (e.touches.length === 1) {
                    // Drag Started
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
                    // Process Pinching (Zooming)
                    let newDist = this.getDistance(e.touches[0], e.touches[1]);
                    let zoomFactor = newDist / this.initialPinchDist;
                    let newScale = this.initialScale * zoomFactor;
                    if (newScale < 0.1) newScale = 0.1;
                    if (newScale > 5) newScale = 5;
                    this.scale = newScale;
                    this.draw();
                } else if (e.touches.length === 1 && this.isDragging) {
                    // Process Dragging (Panning)
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
            }
        }));
    });
</script>
@endpush