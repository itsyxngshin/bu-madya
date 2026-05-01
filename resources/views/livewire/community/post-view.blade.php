<div class="max-w-2xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-40 animate-fade-in-up">

    {{-- BACK NAVIGATION --}}
    <div class="mb-6">
        <a href="{{ route('community.feed') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-blue-600 transition-colors group px-4 py-2 bg-white rounded-full border border-gray-200 shadow-sm w-max">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Feed
        </a>
    </div>

    {{-- MAIN SOCIAL CARD --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden">
        
        {{-- 1. Post Header (Author Info) --}}
        <div class="p-5 md:p-6 flex items-start justify-between">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 font-black flex items-center justify-center border border-blue-100 shrink-0 text-lg shadow-sm">
                    {{ substr($post->author->name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <p class="font-black text-gray-900 text-base leading-tight flex items-center gap-1.5">
                        {{ $post->author->name ?? 'Unknown Author' }}
                        @if($post->is_featured)
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endif
                    </p>
                    <div class="flex items-center gap-1.5 text-xs font-bold text-gray-500 mt-0.5">
                        <span>{{ $post->published_at ? $post->published_at->format('F d, Y • h:i A') : 'Draft' }}</span>
                        @if($post->category)
                            <span>•</span>
                            <span class="uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ $post->category->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Post Body (Title & Text) --}}
        <div class="px-5 md:px-6 pb-4">
            <h1 class="font-black text-gray-900 text-xl md:text-2xl mb-3 leading-snug">{{ $post->title }}</h1>
            <div class="text-[15px] md:text-base text-gray-800 leading-relaxed font-medium whitespace-pre-wrap">
                {{ $post->content }}
            </div>
        </div>

        {{-- 3. Edge-to-Edge Media Grid --}}
        @if($post->cover_image_path || !empty($post->gallery))
            <div class="w-full bg-gray-50 border-t border-b border-gray-100 mt-2">
                
                {{-- Cover Image --}}
                @if($post->cover_image_path)
                    <a href="{{ asset('storage/'.$post->cover_image_path) }}" target="_blank" class="block w-full max-h-[500px] overflow-hidden">
                        <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-contain bg-gray-100">
                    </a>
                @endif

                {{-- Gallery Images --}}
                @if(!empty($post->gallery))
                    <div class="grid grid-cols-2 gap-1 mt-1 bg-white">
                        @foreach($post->gallery as $photo)
                            <a href="{{ asset('storage/'.$photo) }}" target="_blank" class="block aspect-square overflow-hidden bg-gray-100 relative group">
                                <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- 4. Integrated Engagement Bar --}}
        <livewire:community.post-engagement :post="$post" />

    </div>
</div>