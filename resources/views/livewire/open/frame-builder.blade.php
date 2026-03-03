{{-- OUTER WRAPPER: relative and overflow-x-hidden guarantee NO horizontal scrolling --}}
<div class="relative min-h-screen bg-slate-50 overflow-x-hidden font-sans pb-24 z-0">
    
    {{-- ========================================== --}}
    {{-- RAINBOW BLOBS BACKGROUND                   --}}
    {{-- ========================================== --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        {{-- Top Left Pink/Purple --}}
        <div class="absolute -top-[10%] -left-[10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] rounded-full bg-gradient-to-br from-fuchsia-400 to-purple-500 blur-[100px] opacity-40 mix-blend-multiply"></div>
        {{-- Bottom Right Yellow/Red --}}
        <div class="absolute -bottom-[10%] -right-[10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] rounded-full bg-gradient-to-tl from-yellow-300 to-rose-400 blur-[100px] opacity-40 mix-blend-multiply"></div>
        {{-- Middle Right Cyan/Blue --}}
        <div class="absolute top-[20%] -right-[5%] w-[40vw] h-[40vw] max-w-[500px] max-h-[500px] rounded-full bg-gradient-to-bl from-cyan-300 to-blue-500 blur-[100px] opacity-40 mix-blend-multiply"></div>
    </div>

    {{-- ========================================== --}}
    {{-- MAIN CONTENT GRID                          --}}
    {{-- ========================================== --}}
    <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        {{-- STRICT 3-COLUMN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start w-full">

            {{-- LEFT COLUMN: Details (Strictly 1 Column - 33%) --}}
            <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-12">
                
                {{-- Details Glass Card --}}
                <div class="bg-white/60 backdrop-blur-2xl p-6 md:p-8 rounded-[2rem] border border-white/80 shadow-2xl shadow-purple-900/5">
                    <span class="inline-block px-3 py-1 bg-white/80 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-full mb-5 shadow-sm border border-rose-100 backdrop-blur-md">
                        Campaign Frame
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-4 tracking-tight drop-shadow-sm">{{ $frame->title }}</h1>
                    <p class="text-sm text-gray-700 mb-8 leading-relaxed font-medium">{{ $frame->description }}</p>
                    
                    <div class="flex items-center gap-3 bg-white/80 p-3 rounded-2xl border border-white shadow-sm inline-flex">
                        <div class="w-10 h-10 bg-gradient-to-tr from-gray-100 to-gray-200 rounded-xl flex items-center justify-center font-black text-gray-500 text-xs uppercase shadow-inner">
                            {{ substr($frame->user->name ?? 'BU', 0, 2) }}
                        </div>
                        <div class="pr-3">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Created By</p>
                            <p class="text-xs font-black text-gray-900 leading-tight truncate max-w-[150px]">{{ $frame->user->name ?? 'BU MADYA' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Share Glass Card --}}
                <div class="bg-white/60 backdrop-blur-2xl p-5 md:p-6 rounded-[2rem] border border-white/80 shadow-2xl shadow-blue-900/5" x-data="{ copied: false }">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Share Campaign Link</p>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ url()->current() }}" class="flex-1 bg-white/80 border border-white/50 shadow-inner rounded-xl text-xs py-3 px-4 text-gray-600 focus:outline-none font-medium">
                        <button @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="bg-gray-900 hover:bg-gray-800 text-white p-3 rounded-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0 w-11 h-11 shadow-lg">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" style="display: none;" class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: The Studio (Strictly 2 Columns - 66%) --}}
            {{-- min-w-0 forces the grid to stop child elements from expanding past the column limits --}}
            <div class="lg:col-span-2 w-full min-w-0 flex justify-center lg:justify-start">
                
                {{-- Inner Studio Container --}}
                <div class="w-full bg-white/70 backdrop-blur-2xl p-6 md:p-10 rounded-[2.5rem] shadow-2xl shadow-rose-900/10 border border-white"
                     @php
                         // Safe array mapping
                         $images = is_array($frame->frame_images) ? array_filter($frame->frame_images) : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
                         $frameUrls = array_map(fn($path) => asset('storage/' . $path), $images);
                     @endphp
                     x-data="studio(@js($frameUrls))"
                     x-init="init()"
                >
                    
                    {{-- Toolbar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 bg-white/80 shadow-sm p-3 rounded-2xl border border-white max-w-[500px] mx-auto">
                        <label class="cursor-pointer bg-gradient-to-r from-rose-500 to-red-600 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-red-500/30 hover:shadow-red-500/50 hover:scale-[1.02] transition-all w-full sm:w-auto text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <input type="file" accept="image/png, image/jpeg" class="hidden" @change="uploadPhoto">
                            <span x-show="!userImg">Upload Photo</span>
                            <span x-show="userImg" style="display: none;">Change Photo</span>
                        </label>

                        {{-- Zoom Slider --}}
                        <div x-show="userImg" style="display: none;" class="flex items-center gap-3 w-full sm:flex-1 sm:px-4">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                            <input type="range" x-model="scale" @input="draw" min="0.1" max="3" step="0.01" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-rose-500">
                            <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        </div>
                    </div>

                    {{-- Canvas Area --}}
                    {{-- max-w-[500px] guarantees the square never explodes to fill a massive screen --}}
                    <div class="relative w-full max-w-[500px] mx-auto aspect-square bg-white rounded-3xl overflow-hidden shadow-inner ring-4 ring-white/50 group">
                        
                        {{-- Placeholder Screen --}}
                        <div x-show="!userImg" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none bg-gray-50/80 backdrop-blur-sm z-10 transition-opacity duration-300">
                            <div class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center mb-6 border border-gray-100">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-gray-600">Select a photo to begin</p>
                        </div>

                        {{-- The actual canvas element --}}
                        <canvas x-ref="canvas"
                                width="1080" height="1080"
                                class="w-full h-full cursor-move origin-top-left touch-none bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] bg-repeat"
                                @mousedown="startDrag" @mousemove="drag" @mouseup="endDrag" @mouseleave="endDrag"
                                @touchstart.passive="startDrag" @touchmove.prevent="drag" @touchend.passive="endDrag">
                        </canvas>
                    </div>

                    {{-- Error Message --}}
                    <div x-show="imageError" style="display: none;" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl text-center max-w-[500px] mx-auto shadow-sm">
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-wider">⚠️ Error loading frame image. The path might be broken.</p>
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

                    {{-- Download Section --}}
                    <div class="mt-8 text-center max-w-[500px] mx-auto">
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-black mb-5 flex items-center justify-center gap-2" x-show="userImg" style="display: none;">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                            Drag photo to reposition
                        </p>

                        <button @click="download" x-show="userImg" style="display: none;" class="w-full bg-gray-900 text-white py-4 rounded-xl font-black uppercase tracking-widest shadow-xl shadow-gray-900/20 hover:bg-black transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Download Frame
                        </button>
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
            isDragging: false, startX: 0, startY: 0,
            initialDx: 0, initialDy: 0,
            frames: Array.isArray(frameUrls) ? frameUrls : [],
            activeFrame: null,
            imageError: false,

            init() {
                this.canvas = this.$refs.canvas;
                this.ctx = this.canvas.getContext('2d');
                
                if (this.frames.length > 0 && this.frames[0] !== '') {
                    this.activeFrame = this.frames[0];
                    this.loadFrameImage(this.activeFrame);
                } else {
                    console.warn("No valid frames provided.");
                    this.imageError = true;
                    this.draw();
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
                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let clientY = e.touches ? e.touches[0].clientY : e.clientY;
                this.startX = clientX;
                this.startY = clientY;
                this.initialDx = this.dx;
                this.initialDy = this.dy;
            },

            drag(e) {
                if(!this.isDragging) return;
                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let clientY = e.touches ? e.touches[0].clientY : e.clientY;
                let canvasRect = this.canvas.getBoundingClientRect();
                let scaleRatio = 1080 / canvasRect.width;
                this.dx = this.initialDx + ((clientX - this.startX) * scaleRatio);
                this.dy = this.initialDy + ((clientY - this.startY) * scaleRatio);
                this.draw();
            },

            endDrag() {
                this.isDragging = false;
            },

            download() {
                let link = document.createElement('a');
                link.download = 'BU-MADYA-' + Date.now() + '.png';
                link.href = this.canvas.toDataURL('image/png');
                link.click();
            }
        }));
    });
</script>
@endpush