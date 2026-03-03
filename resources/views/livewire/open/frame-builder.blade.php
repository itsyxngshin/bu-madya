<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8 font-sans pb-24">
    <div class="max-w-6xl mx-auto">
        
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Events
        </a>

        {{-- BULLETPROOF FLEX LAYOUT --}}
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">

            {{-- LEFT COLUMN: Details (Strict 1/3 width, fixed padding) --}}
            <div class="w-full lg:w-[380px] xl:w-[420px] shrink-0 lg:sticky lg:top-24">
                <div>
                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-4 shadow-sm border border-red-200">
                        BU MADYA Campaign Frame
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-4 tracking-tight">{{ $frame->title }}</h1>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">{{ $frame->description }}</p>
                    
                    <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-gray-100 shadow-sm w-max">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-bold text-gray-500 text-xs uppercase">
                            {{ substr($frame->user->name ?? 'BU', 0, 2) }}
                        </div>
                        <div class="pr-2">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Created By</p>
                            <p class="text-xs font-bold text-gray-900 leading-tight truncate max-w-[200px]">{{ $frame->user->name ?? 'BU MADYA' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: The Interactive Studio --}}
            <div class="w-full lg:flex-1 min-w-0">
                <div class="bg-white p-5 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 ring-1 ring-gray-900/5"
                     @php
                         // Safe array mapping: Strictly force an array, even if the DB returns null
                         $images = is_array($frame->frame_images) ? $frame->frame_images : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
                         $frameUrls = array_map(fn($path) => asset('storage/' . $path), $images);
                     @endphp
                     {{-- Pass the JSON encoded array to Alpine --}}
                     x-data="studio(@js($frameUrls))"
                     x-init="init()"
                >
                    
                    {{-- Toolbar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6 bg-gray-50 p-2.5 rounded-2xl border border-gray-100">
                        <label class="cursor-pointer bg-red-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-md hover:bg-red-700 hover:shadow-lg transition-all transform hover:-translate-y-0.5 w-full sm:w-auto text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <input type="file" accept="image/png, image/jpeg" class="hidden" @change="uploadPhoto">
                            <span x-show="!userImg">Upload Photo</span>
                            <span x-show="userImg" style="display: none;">Change Photo</span>
                        </label>

                        {{-- Zoom Slider --}}
                        <div x-show="userImg" style="display: none;" class="flex items-center gap-3 w-full sm:flex-1 sm:px-4">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                            <input type="range" x-model="scale" @input="draw" min="0.1" max="3" step="0.01" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600">
                            <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        </div>
                    </div>

                    {{-- Canvas Area (Locked width to prevent blowing out) --}}
                    <div class="relative w-full max-w-[500px] mx-auto aspect-square bg-gray-50 rounded-2xl overflow-hidden border-4 border-gray-100 shadow-inner group">
                        
                        {{-- Placeholder Screen --}}
                        <div x-show="!userImg" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none bg-white/60 backdrop-blur-sm z-10 transition-opacity duration-300">
                            <div class="w-16 h-16 bg-white rounded-full shadow-md flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-gray-800">Select a photo to begin</p>
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
                    <div x-show="imageError" style="display: none;" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-center max-w-[500px] mx-auto">
                        <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider">⚠️ Error loading frame image. The image path might be broken or empty.</p>
                    </div>

                    {{-- Variation Selector (Only shows if array > 1) --}}
                    <div x-show="frames.length > 1" style="display: none;" class="mt-6 bg-gray-50/50 p-4 rounded-2xl border border-gray-100 max-w-[500px] mx-auto">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 text-center">Select Variation</p>
                        <div class="flex flex-wrap justify-center gap-3">
                            <template x-for="(frameUrl, index) in frames" :key="index">
                                <button @click="changeFrame(frameUrl)" 
                                        :class="{'ring-2 ring-red-500 ring-offset-2 scale-110 shadow-md': activeFrame === frameUrl, 'border border-gray-200 hover:border-red-300 opacity-50 hover:opacity-100': activeFrame !== frameUrl}"
                                        class="w-14 h-14 rounded-xl bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] overflow-hidden transition-all duration-200 bg-repeat focus:outline-none relative bg-white">
                                    <img :src="frameUrl" class="absolute inset-0 w-full h-full object-contain p-1">
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 text-center max-w-[500px] mx-auto">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-4 flex items-center justify-center gap-2" x-show="userImg" style="display: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                            Drag photo to reposition
                        </p>

                        <button @click="download" x-show="userImg" style="display: none;" class="w-full bg-gray-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest shadow-xl hover:bg-gray-800 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
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
            
            // Safe array mapping
            frames: Array.isArray(frameUrls) ? frameUrls : [],
            activeFrame: null,
            imageError: false,

            init() {
                this.canvas = this.$refs.canvas;
                this.ctx = this.canvas.getContext('2d');
                
                if (this.frames.length > 0) {
                    this.activeFrame = this.frames[0];
                    this.loadFrameImage(this.activeFrame);
                } else {
                    console.warn("No frames provided.");
                    this.imageError = true;
                    this.draw();
                }
            },
            
            loadFrameImage(url) {
                this.imageError = false;
                this.frameImg = new Image();
                this.frameImg.crossOrigin = "Anonymous"; 
                
                this.frameImg.onload = () => {
                    this.draw();
                };
                
                this.frameImg.onerror = () => {
                    console.error("Failed to load frame image: " + url);
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
                
                // Unified touch/mouse coordinates
                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let clientY = e.touches ? e.touches[0].clientY : e.clientY;
                
                this.startX = clientX - this.dx;
                this.startY = clientY - this.dy;
            },

            drag(e) {
                if(!this.isDragging) return;
                
                // Unified touch/mouse coordinates
                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let clientY = e.touches ? e.touches[0].clientY : e.clientY;
                
                let canvasRect = this.canvas.getBoundingClientRect();
                let scaleRatio = 1080 / canvasRect.width;
                
                this.dx = (clientX - this.startX) * scaleRatio;
                this.dy = (clientY - this.startY) * scaleRatio;
                this.draw();
            },

            endDrag() {
                this.isDragging = false;
            },

            download() {
                let link = document.createElement('a');
                link.download = 'BU-MADYA-Campaign-' + Date.now() + '.png';
                link.href = this.canvas.toDataURL('image/png');
                link.click();
            }
        }));
    });
</script>
@endpush