<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heroes of Innovation Challenge 2026 | Ibalong Launchpad</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { background-color: #1A1C14; color: #E5E7EB; font-family: 'Inter', sans-serif; }
        .font-pixel { font-family: 'Press Start 2P', cursive; line-height: 1.5; }
        
        .btn-pixel {
            box-shadow: inset -4px -4px 0px 0px rgba(0,0,0,0.5);
            transition: all 0.1s;
        }
        .btn-pixel:active {
            box-shadow: inset 4px 4px 0px 0px rgba(0,0,0,0.5);
            transform: translate(2px, 2px);
        }

        .tribal-border {
            background-image: repeating-linear-gradient(45deg, #D97706 25%, transparent 25%, transparent 75%, #D97706 75%, #D97706), repeating-linear-gradient(45deg, #D97706 25%, #1A1C14 25%, #1A1C14 75%, #D97706 75%, #D97706);
            background-position: 0 0, 10px 10px;
            background-size: 20px 20px;
            height: 24px;
            border-top: 4px solid #F59E0B;
            border-bottom: 4px solid #10B981;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden relative">

    <nav class="fixed top-0 w-full z-50 bg-[#1A1C14]/90 backdrop-blur-md border-b border-yellow-600/30">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="font-pixel text-yellow-500 text-sm md:text-base flex items-center gap-2">
                <span class="text-white">BU</span> MADYA
            </div>
            <div class="hidden md:flex gap-8 font-pixel text-[10px] text-gray-300">
                <a href="#home" class="hover:text-yellow-400 transition">home</a>
                <a href="#about" class="hover:text-yellow-400 transition">about</a>
                <a href="#pathways" class="hover:text-yellow-400 transition">pathways</a>
                <a href="#timeline" class="hover:text-yellow-400 transition">timeline</a>
                <a href="#register" class="text-yellow-400 hover:text-white transition">register</a>
            </div>
        </div>
    </nav>

    {{-- INJECT LIVEWIRE COMPONENT --}}
    {{ $slot }}

    <footer class="bg-black pt-16">
        <div class="max-w-6xl mx-auto px-6 mb-12">
            <p class="text-center font-pixel text-[10px] text-gray-500 mb-8 uppercase">Organized By & Partners</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-70 hover:opacity-100 transition-opacity">
                <div class="text-white font-bold tracking-widest text-center">CITY OF<br>LEGAZPI</div>
                <div class="text-red-600 font-serif font-black text-2xl tracking-tighter">Ibalong<br><span class="text-sm text-white tracking-widest font-sans">FESTIVAL 2026</span></div>
                <div class="text-white font-black text-2xl tracking-tight">DEVCON<br><span class="text-sm font-normal tracking-widest">LEGAZPI CHAPTER</span></div>
                <div class="text-white font-black text-xl">BiCoRSE</div>
                <div class="text-blue-400 font-bold">DOST V</div>
            </div>
            
            <div class="text-center mt-12 text-gray-600 text-sm">
                <p>Contact: bicorse@bicol-u.edu.ph</p>
                <p class="mt-2">&copy; {{ date('Y') }} BU MADYA & BiCoRSE. All rights reserved.</p>
            </div>
        </div>
        <div class="w-full tribal-border"></div>
    </footer>

    @livewireScripts
</body>
</html>