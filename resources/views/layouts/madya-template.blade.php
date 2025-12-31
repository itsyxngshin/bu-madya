<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- 1. Dynamic Page Title --}}
    <title>@yield('meta_title', 'BU MADYA Web')</title>

    {{-- 2. Standard Description --}}
    <meta name="description" content="@yield('meta_description', 'Advocating for youth empowerment and social change.')">

    {{-- 3. FACEBOOK / OPEN GRAPH META TAGS --}}
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('meta_title', 'BU MADYA Web')" />
    <meta property="og:description" content="@yield('meta_description', 'Join the movement for youth-led advocacy.')" />
    <meta property="og:image" content="@yield('meta_image', asset('images/default_share_image.jpg'))" />

    {{-- 4. TWITTER CARD DATA --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Join the movement for youth-led advocacy.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/default_share_image.jpg'))">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans antialiased">
    <x-madya-navbar />

    <div>
        {{ $slot }}
    </div>

        {{-- 
    <div x-data="{ sidebarOpen: true }" class="min-h-screen flex bg-gray-100 relative">
        <x-madya-sidebar />
        
        <main class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out"
              :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">
            
            <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center sticky top-0 z-20">
                
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-red-600 focus:outline-none transition-colors p-1 rounded-md hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </button>

                <h2 class="ml-4 font-bold text-gray-700 font-heading">
                    {{ $header ?? 'Dashboard' }}
                </h2>
            </div>
            
           

        </main>
    </div>
    --}}
    @stack('modals')
    @livewireScripts
</body>
</html>