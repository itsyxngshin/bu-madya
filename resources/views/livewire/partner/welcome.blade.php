<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accreditation Center | BU MADYA Partners</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased text-gray-900 selection:bg-blue-500 selection:text-white flex flex-col min-h-screen">

    {{-- NAVIGATION BAR --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-red-600 to-yellow-500 rounded-lg shadow-sm flex items-center justify-center text-white font-black text-xs">
                        BU
                    </div>
                    <span class="font-black text-gray-900 tracking-tight">MADYA <span class="text-gray-400 font-medium">Partners</span></span>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('accreditation.dashboard') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 transition">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 transition">Log In</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-sm hover:bg-black transition active:scale-95">Register Org</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <main class="flex-grow flex flex-col items-center justify-center px-4 sm:px-6 relative py-20 lg:py-32 overflow-hidden">
        
        {{-- Background Accents --}}
        <div class="absolute top-0 inset-x-0 h-64 bg-gradient-to-b from-blue-50 to-transparent -z-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob -z-10"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000 -z-10"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-widest mb-8">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                A.Y. 2026-2027 Accreditation Now Open
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-gray-900 tracking-tighter leading-[1.1] mb-6">
                Streamlining Student <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 via-yellow-500 to-green-500">Organization Recognition.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-500 mb-10 max-w-2xl mx-auto font-medium leading-relaxed">
                The centralized portal for Bicol University student organizations to process their accreditation, manage rosters, and submit requirements directly to OSAS.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('partner.dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 bg-gray-900 text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg shadow-gray-900/20 hover:bg-black transition active:scale-95">
                        Access Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition active:scale-95">
                        Create Organization Account
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white text-gray-900 border border-gray-200 text-sm font-black uppercase tracking-widest rounded-xl shadow-sm hover:bg-gray-50 transition active:scale-95">
                        Log In
                    </a>
                @endauth
            </div>
        </div>

        {{-- HOW IT WORKS --}}
        <div class="max-w-6xl mx-auto w-full mt-24 md:mt-32">
            <h3 class="text-center text-xs font-black text-gray-400 uppercase tracking-widest mb-12">The Accreditation Process</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group hover:border-blue-200 transition">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-black text-xl mb-6 group-hover:scale-110 transition-transform">1</div>
                    <h4 class="text-lg font-black text-gray-900 mb-2">Setup Profile</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Register your organization's official account and complete your general profile and financial details.</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group hover:border-yellow-200 transition">
                    <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center font-black text-xl mb-6 group-hover:scale-110 transition-transform">2</div>
                    <h4 class="text-lg font-black text-gray-900 mb-2">Upload Roster & Docs</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Input your officers, members, activities, and easily attach your CBL and financial reports in one place.</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center font-black text-xl mb-6 group-hover:scale-110 transition-transform">3</div>
                    <h4 class="text-lg font-black text-gray-900 mb-2">OSAS Review</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Submit your application and track its status in real-time. Receive immediate feedback if revisions are needed.</p>
                </div>
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-200 bg-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-xs font-bold text-gray-400">
                &copy; {{ date('Y') }} BU MADYA. All rights reserved.
            </div>
            <div class="flex items-center gap-4 text-xs font-bold text-gray-400">
                <span>Powered by BU MADYA</span>
                <span>•</span>
                <a href="#" class="hover:text-gray-900 transition">Bicol University OSAS</a>
            </div>
        </div>
    </footer>

</body>
</html>