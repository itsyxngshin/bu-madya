<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | BU MADYA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 font-sans text-gray-900 h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    {{-- Background Decoration --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- Main Card --}}
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-2xl overflow-hidden relative transform transition-all hover:scale-[1.01] duration-300">
        
        {{-- Yellow Gradient Bar --}}
        <div class="h-2 w-full bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500"></div>

        <div class="p-8 sm:p-12 text-center">
            
            {{-- Icon Container --}}
            <div class="relative mx-auto w-24 h-24 mb-6">
                <div class="absolute inset-0 bg-yellow-100 rounded-full animate-ping opacity-75"></div>
                <div class="relative w-full h-full bg-yellow-50 rounded-full flex items-center justify-center border-4 border-white shadow-sm">
                    <svg class="w-10 h-10 text-yellow-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Titles --}}
            <h1 class="font-heading font-black text-3xl sm:text-4xl text-gray-900 mb-2 tracking-tight">
                Page <span class="bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600">Not Found</span>
            </h1>
            
            {{-- Error Code --}}
            <div class="inline-block px-4 py-1.5 rounded-full bg-yellow-50 border border-yellow-100 mb-6">
                <p class="text-xs font-bold text-yellow-700 uppercase tracking-widest">
                    Code 404: Missing
                </p>
            </div>

            <p class="text-gray-600 mb-8 text-sm leading-relaxed px-4">
                <span class="font-semibold text-gray-800">
                    We couldn't find the page you were looking for.
                </span>
                <br>
                It might have been removed, renamed, or perhaps the link is broken.
            </p>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url()->previous() }}" class="group relative px-6 py-3 bg-white border-2 border-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all uppercase text-[10px] tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Go Back
                </a>

                <a href="{{ route('open.home') }}" class="group px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-yellow-600 transition-colors shadow-lg shadow-gray-200 uppercase text-[10px] tracking-widest flex items-center justify-center gap-2">
                    Return Home
                    <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </a>
            </div>

        </div>

        {{-- Footer Strip --}}
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Navigation Error</span>
            <img src="{{ asset('images/official_logo.png') }}" class="h-6 opacity-50 grayscale hover:grayscale-0 transition duration-500" alt="Logo">
        </div>

    </div>

    {{-- Tailwind Custom Animation Style --}}
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>