<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- LEFT COLUMN: Details --}}
        <div class="lg:col-span-5 space-y-6">
            <a href="{{ route('events.index') }}" class="text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition">&larr; Back to Events</a>

            <div>
                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-3">
                    BU MADYA Campaign Frame
                </span>
                <h1 class="text-4xl font-black text-gray-900 leading-tight mb-4">{{ $frame->title }}</h1>
                <p class="text-sm text-gray-600 mb-6">{{ $frame->description }}</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-xs uppercase">
                        {{ substr($frame->user->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Created By</p>
                        <p class="text-sm font-bold text-gray-900">{{ $frame->user->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: The Interactive Studio --}}
        <div class="lg:col-span-7">
            <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl border border-gray-100"
                 @php
                     // Prepare full URLs for the array
                     $frameUrls = array_map(fn($path) => asset('storage/' . $path), $frame->frame_images ?? []);
                 @endphp
                 x-data="studio(@js($frameUrls))"
                 x-init="init()"
            >
                {{-- Toolbar --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <label class="cursor-pointer bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-800 transition w-full sm:w-auto text-center">
                        <input type="file" accept="image/png, image/jpeg" class="hidden" @change="uploadPhoto">
                        <span x-show="!userImg">Upload Your Photo</span>
                        <span x-show="userImg" style="display: none;">Change Photo</span>
                    </label>

                    {{-- Zoom Slider (Only shows when photo is uploaded) --}}
                    <div x-show="userImg" style="display: none;" class="flex items-center gap-3 w-full sm:w-1/2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                        <input type="range" x-model="scale" @input="draw" min="0.1" max="3" step="0.01" class="w-full accent-red-600">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                    </div>
                </div>

                {{-- Canvas Area --}}
                <div class="relative w-full aspect-square bg-gray-100 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 shadow-inner group">
                    <div x-show="!userImg" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none">
                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm font-bold uppercase tracking-widest">Select a photo to begin</p>
                    </div>

                    {{-- The actual canvas element --}}
                    <canvas x-ref="canvas"
                            width="1080" height="1080"
                            class="w-full h-full cursor-move origin-top-left touch-none"
                            @mousedown="startDrag" @mousemove="drag" @mouseup="endDrag" @mouseleave="endDrag"
                            @touchstart.passive="startDrag" @touchmove.prevent="drag" @touchend.passive="endDrag">
                    </canvas>
                </div>

                {{-- [NEW] Variation Selector --}}
                <div x-show="frames.length > 1" style="display: none;" class="mt-6">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 text-center">Select Variation</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <template x-for="(frameUrl, index) in frames" :key="index">
                            <button @click="changeFrame(frameUrl)" 
                                    :class="{'ring-2 ring-red-500 ring-offset-2 scale-105': activeFrame === frameUrl, 'border-gray-200 hover:border-red-300 opacity-70 hover:opacity-100': activeFrame !== frameUrl}"
                                    class="w-16 h-16 rounded-xl border bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] overflow-hidden transition-all duration-200 bg-repeat focus:outline-none">
                                <img :src="frameUrl" class="w-full h-full object-contain p-1">
                            </button>
                        </template>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-400 font-bold mb-4" x-show="userImg" style="display: none;">Hint: Drag your photo to reposition it.</p>

                    <button @click="download" x-show="userImg" style="display: none;" class="w-full bg-red-600 text-white py-4 rounded-xl font-black uppercase tracking-widest shadow-xl hover:bg-red-700 hover:shadow-red-500/30 transition-all transform hover:-translate-y-1">
                        Download Frame
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studio', (frameUrl) => ({
            canvas: null, ctx: null,
            userImg: null, frameImg: null,
            scale: 1, dx: 0, dy: 0,
            isDragging: false, startX: 0, startY: 0,
            frames: frameUrls,
            activeFrame: frameUrls[0] || null, 

            init() {
                this.canvas = this.$refs.canvas;
                this.ctx = this.canvas.getContext('2d');
                
                if(this.activeFrame) {
                    this.loadFrameImage(this.activeFrame);
                }
            },
            
            // [NEW] Helper to load and swap frames
            loadFrameImage(url) {
                this.frameImg = new Image();
                this.frameImg.crossOrigin = "Anonymous";
                this.frameImg.src = url;
                this.frameImg.onload = () => this.draw();
            },

            // [NEW] Click handler for the variation thumbnails
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
                    this.userImg.src = event.target.result;
                    this.userImg.onload = () => {
                        // Reset positions
                        this.dx = 0; this.dy = 0;

                        // Calculate perfect initial scale to cover the 1080x1080 canvas
                        let scaleX = 1080 / this.userImg.width;
                        let scaleY = 1080 / this.userImg.height;
                        this.scale = Math.max(scaleX, scaleY); // Ensure no blank space

                        this.draw();
                    }
                }
                reader.readAsDataURL(file);
            },

            draw() {
                // Clear canvas
                this.ctx.clearRect(0, 0, 1080, 1080);

                // 1. Draw User Image (Underneath)
                if(this.userImg) {
                    let w = this.userImg.width * this.scale;
                    let h = this.userImg.height * this.scale;

                    // Center the image relative to canvas, plus user dragging offsets
                    let x = (1080 - w) / 2 + this.dx;
                    let y = (1080 - h) / 2 + this.dy;

                    this.ctx.drawImage(this.userImg, x, y, w, h);
                }

                // 2. Draw Frame Image (On Top)
                if(this.frameImg) {
                    this.ctx.drawImage(this.frameImg, 0, 0, 1080, 1080);
                }
            },

            // --- Mouse & Touch Dragging Logic ---
            startDrag(e) {
                if(!this.userImg) return;
                this.isDragging = true;

                // Handle both Mouse and Touch events
                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let clientY = e.touches ? e.touches[0].clientY : e.clientY;

                this.startX = clientX - this.dx;
                this.startY = clientY - this.dy;
            },

            drag(e) {
                if(!this.isDragging) return;

                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let clientY = e.touches ? e.touches[0].clientY : e.clientY;

                // Calculate movement, speed up the drag slightly to match canvas scaling
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
                link.download = 'BU-MADYA-Frame-' + Date.now() + '.png';
                link.href = this.canvas.toDataURL('image/png');
                link.click();
            }
        }));
    });
</script>
@endpush
