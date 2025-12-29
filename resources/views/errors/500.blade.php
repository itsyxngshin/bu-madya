<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error | BU MADYA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 font-sans text-gray-900 h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    {{-- Background Decoration --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-gray-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-stone-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-gray-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- Main Card --}}
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-2xl overflow-hidden relative transform transition-all hover:scale-[1.01] duration-300">
        
        {{-- Gray Gradient Bar --}}
        <div class="h-2 w-full bg-gradient-to-r from-gray-700 via-gray-500 to-stone-600"></div>

        <div class="p-8 sm:p-12 text-center">
            
            {{-- Icon Container --}}
            <div class="relative mx-auto w-24 h-24 mb-6">
                <div class="absolute inset-0 bg-gray-200 rounded-full animate-pulse opacity-75"></div>
                <div class="relative w-full h-full bg-gray-50 rounded-full flex items-center justify-center border-4 border-white shadow-sm">
                    <svg class="w-10 h-10 text-gray-700 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            {{-- Titles --}}
            <h1 class="font-heading font-black text-3xl sm:text-4xl text-gray-900 mb-2 tracking-tight">
                Server <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-700 to-stone-500">Error</span>
            </h1>
            
            {{-- Error Code --}}
            <div class="inline-block px-4 py-1.5 rounded-full bg-gray-100 border border-gray-200 mb-6">
                <p class="text-xs font-bold text-gray-600 uppercase tracking-widest">
                    Code 500: Internal Error
                </p>
            </div>

            <p class="text-gray-600 mb-8 text-sm leading-relaxed px-4">
                <span class="font-semibold text-gray-800">
                    Something went wrong on our end.
                </span>
                <br>
                We're already working to fix this. Please try again in a few minutes.
            </p>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="location.reload()" class="group relative px-6 py-3 bg-white border-2 border-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all uppercase text-[10px] tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Try Again
                </button>

                <a href="{{ route('open.home') }}" class="group px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-700 transition-colors shadow-lg shadow-gray-200 uppercase text-[10px] tracking-widest flex items-center justify-center gap-2">
                    Back to Safety
                    <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </a>
            </div>

        </div>

        {{-- Footer Strip --}}
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">System Failure</span>
            <img src="{{ asset('images/official_logo.png') }}" class="h-6 opacity-50 grayscale hover:grayscale-0 transition duration-500" alt="Logo">
        </div>

    </div>

    {{-- CSS Styles are same as above --}}
    <style>
        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</body>
</html>