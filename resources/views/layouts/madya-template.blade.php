<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- 1. Dynamic Page Title --}}
    <title>BU MADYA</title>
    <link rel="icon" href="{{ asset('images/MADYA Web Logo1.png') }}">

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
    <script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

    @stack('adsense')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">
    <x-madya-navbar />

    <div>
        {{ $slot }}
    </div>

    @stack('scripts')
    @stack('modals')
    @livewireScripts
</body>
</html>
