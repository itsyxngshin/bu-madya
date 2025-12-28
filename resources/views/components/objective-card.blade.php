@props(['icon', 'title', 'desc'])

<div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-red-100 transition group">
    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-red-600 transition duration-300">
        {{-- You can use Heroicons here. I'm using a generic SVG logic for the example --}}
        <svg class="w-6 h-6 text-red-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {{-- Simple switch for icons based on prop name --}}
            @if($icon == 'globe') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            @elseif($icon == 'users') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            @elseif($icon == 'heart') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            @else <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            @endif
        </svg>
    </div>
    <h3 class="font-bold text-gray-900 text-lg mb-3">{{ $title }}</h3>
    <p class="text-gray-500 text-sm leading-relaxed">
        {{ $desc }}
    </p>
</div>