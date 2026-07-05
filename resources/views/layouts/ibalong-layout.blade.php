<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-[#FFFBF7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heroes of Innovation Challenge 2026 | Ibalong Launchpad</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #131011; }
        .font-pixel { font-family: 'Press Start 2P', cursive; }
        
        .btn-retro {
            border: 4px solid #131011;
            box-shadow: 0 4px 0 0 #131011;
            transition: all 0.1s ease-in-out;
        }
        .btn-retro:active {
            transform: translateY(4px);
            box-shadow: 0 0px 0 0 transparent;
        }

        /* Non-Repeating Stretched Ethnic Trims */
        .trim-element-1 {
            background-image: url('{{ asset('images/ELEMENT 1.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;
            height: 24px;
            width: 100%;
        }
        
        .trim-element-2 {
            background-image: url('{{ asset('images/ELEMENT 2.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;
            height: 24px;
            width: 100%;
        }

        @media (min-width: 768px) {
            .trim-element-1, .trim-element-2 { height: 32px; }
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden pt-20 md:pt-24">

    {{-- COLORFUL NAVBAR --}}
    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-[#FFFBF7]/95 border-b-4 border-[#CF452C] shadow-sm backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            
            <div class="font-pixel text-[10px] sm:text-xs tracking-tight text-[#CF452C] z-50">
                <span class="text-[#0095AC]">BU</span> MADYA
            </div>
            
            <div class="flex items-center gap-4 sm:gap-6 z-50">
                <div class="hidden md:flex gap-6 font-pixel text-[9px] text-[#131011]">
                    <a href="#home" class="hover:text-[#FF8623] transition-colors">HOME</a>
                    <a href="#about" class="hover:text-[#0095AC] transition-colors">ABOUT</a>
                    <a href="#pathways" class="hover:text-[#5C7914] transition-colors">PATHWAYS</a>
                    <a href="#timeline" class="hover:text-[#CF452C] transition-colors">TIMELINE</a>
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-[#CF452C] focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-cloak class="md:hidden absolute top-full left-0 w-full bg-[#FFFBF7] border-b-4 border-[#CF452C] shadow-lg font-pixel text-[10px] text-center">
            <div class="flex flex-col py-4">
                <a href="#home" @click="mobileMenuOpen = false" class="py-3 text-[#131011] hover:text-[#FF8623] hover:bg-orange-50">HOME</a>
                <a href="#about" @click="mobileMenuOpen = false" class="py-3 text-[#131011] hover:text-[#0095AC] hover:bg-teal-50">ABOUT</a>
                <a href="#pathways" @click="mobileMenuOpen = false" class="py-3 text-[#131011] hover:text-[#5C7914] hover:bg-green-50">PATHWAYS</a>
                <a href="#timeline" @click="mobileMenuOpen = false" class="py-3 text-[#131011] hover:text-[#CF452C] hover:bg-red-50 border-b border-[#CF452C]/20">TIMELINE</a>
                <a href="#register" @click="mobileMenuOpen = false" class="py-4 text-white font-bold bg-[#FF8623]">REGISTER NOW</a>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <footer class="bg-white pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 md:mb-10">
                <span class="font-pixel text-[8px] sm:text-[9px] tracking-widest text-[#0095AC] block mb-2">COUNCIL OF CO-FOUNDERS</span>
                <h3 class="text-base sm:text-lg font-bold uppercase text-[#131011] tracking-wide">ORGANIZED BY & PARTNERS</h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 max-w-5xl mx-auto">
                @php
                    $partners = [
                        ['name' => 'LGU LEGAZPI', 'label' => 'City Government Official Seal', 'img' => 'images/logo_legazpi.png'],
                        ['name' => 'IBALONG FESTIVAL', 'label' => 'Executive Festival Committee', 'img' => 'images/logo_ibalong.png'],
                        ['name' => 'DEVCON LEGAZPI', 'label' => 'Technology Ecosystem Partner', 'img' => 'images/logo_devcon.png'],
                        ['name' => 'BiCoRSE', 'label' => 'Lead Consortium Host', 'img' => 'images/logo_bicorse.png'],
                        ['name' => 'DOST V', 'label' => 'Innovation Support Partner', 'img' => 'images/logo_dost.png'],
                    ];
                @endphp

                @foreach($partners as $partner)
                    <div class="bg-[#FFFBF7] border-2 border-[#131011]/10 p-4 sm:p-6 flex flex-col items-center justify-center text-center rounded-xl hover:border-[#FF8623] hover:shadow-md transition-all">
                        <div class="h-12 sm:h-16 w-full flex items-center justify-center mb-2 sm:mb-3">
                            <div class="font-pixel text-[8px] sm:text-[10px] text-[#CF452C] leading-tight">
                                {{ $partner['name'] }}
                            </div>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-gray-500 uppercase tracking-wider leading-tight">
                            {{ $partner['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12 pb-8 border-t border-[#131011]/10 pt-6 text-[#131011] text-xs px-4">
                <p>&copy; {{ date('Y') }} BU MADYA & BiCoRSE.</p>
            </div>
        </div>
        
        <div class="trim-element-2"></div>
    </footer>

    @livewireScripts
</body>
</html>