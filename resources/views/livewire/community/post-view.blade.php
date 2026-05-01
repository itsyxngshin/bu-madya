<div class="bg-white min-h-screen pb-32 animate-fade-in-up">

    {{-- 1. STICKY TOP BAR --}}
    <div class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 px-4 md:px-6 py-3 flex items-center gap-4">
        <a href="{{ route('community.feed') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 transition-colors text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-lg font-black text-gray-900 tracking-tight">Post</h2>
    </div>

    {{-- 2. AUTHOR HEADER --}}
    <div class="px-4 md:px-6 pt-6 pb-4 flex items-start justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 font-black flex items-center justify-center border border-blue-100 shrink-0 text-lg shadow-sm">
                {{ substr($post->author->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex flex-col justify-center">
                <p class="font-black text-gray-900 text-[15px] leading-tight flex items-center gap-1.5">
                    {{ $post->author->name ?? 'Unknown Author' }}
                    @if($post->is_featured)
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endif
                </p>
                <div class="flex items-center gap-1.5 text-xs font-bold text-gray-500 mt-1">
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y • h:i A') : 'Draft' }}</span>
                </div>
            </div>
        </div>

        @if($post->category)
            <span class="bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md mt-1">
                {{ $post->category->name }}
            </span>
        @endif
    </div>

    {{-- 3. THE CONTENT --}}
    <div class="px-4 md:px-6 pb-4">
        
        <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight leading-snug mb-4">
            {{ $post->title }}
        </h1>

        {{-- CRUCIAL FIX: Keep this completely on ONE LINE to destroy HTML editor indentation --}}
        <div class="text-[15px] md:text-[16px] text-gray-800 leading-relaxed font-medium whitespace-pre-wrap break-words">{{ trim($post->content) }}</div>
        
    </div>

    {{-- 4. EDGE-TO-EDGE MEDIA --}}
    @if($post->cover_image_path || !empty($post->gallery))
        <div class="w-full bg-gray-50 border-t border-b border-gray-100 mt-4 mb-2">
            @if($post->cover_image_path)
                <a href="{{ asset('storage/'.$post->cover_image_path) }}" target="_blank" class="block w-full max-h-[500px] overflow-hidden">
                    <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover bg-gray-100">
                </a>
            @endif

            @if(!empty($post->gallery))
                <div class="grid grid-cols-2 gap-0.5 mt-0.5 bg-white">
                    @foreach($post->gallery as $photo)
                        <a href="{{ asset('storage/'.$photo) }}" target="_blank" class="block aspect-square overflow-hidden bg-gray-100 relative group">
                            <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- 5. THE ENGAGEMENT BAR --}}
    <div class="mt-2">
        <livewire:community.post-engagement :post="$post" />
    </div>

</div>