<div class="max-w-4xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-40 animate-fade-in-up">

    {{-- BACK NAVIGATION --}}
    <a href="{{ route('community.feed') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-blue-600 transition-colors mb-8 group bg-white px-5 py-2.5 rounded-full border border-gray-200 shadow-sm">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Community Feed
    </a>

    {{-- POST HEADER --}}
    <div class="mb-8 md:mb-10 text-center">
        @if($post->category)
            <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-blue-100 mb-4 shadow-sm">
                {{ $post->category->name }}
            </span>
        @endif

        <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tighter leading-tight mb-6">
            {{ $post->title }}
        </h1>

        <div class="flex items-center justify-center gap-4 text-sm font-bold text-gray-500">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-black border border-gray-200 shrink-0 shadow-sm">
                    {{ substr($post->author->name ?? 'A', 0, 1) }}
                </div>
                <span class="text-gray-900">{{ $post->author->name ?? 'Unknown Author' }}</span>
            </div>
            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
            <span class="uppercase tracking-wider text-[10px]">{{ $post->published_at ? $post->published_at->format('M d, Y • h:i A') : 'Draft' }}</span>
        </div>
    </div>

    {{-- COVER IMAGE --}}
    @if($post->cover_image_path)
        <div class="w-full h-64 md:h-96 rounded-[2.5rem] overflow-hidden shadow-lg border border-gray-100 mb-10 md:mb-12">
            <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover">
        </div>
    @endif

    {{-- MAIN CONTENT (With safe newline formatting!) --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-200 p-6 md:p-12 mb-8 relative">

        {{-- The actual text --}}
        <div class="text-base md:text-lg text-gray-800 leading-relaxed font-medium whitespace-pre-wrap">
            {{ $post->content }}
        </div>

        {{-- THE 4-PHOTO GALLERY (If they uploaded extra photos) --}}
        @if(!empty($post->gallery))
            <div class="mt-12 pt-8 border-t border-gray-100">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Attached Media</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($post->gallery as $photo)
                        <a href="{{ asset('storage/'.$photo) }}" target="_blank" class="block aspect-video sm:aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                            <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- THE ENGAGEMENT & COMMENT BAR --}}
    <livewire:community.post-engagement :post="$post" />

</div>
