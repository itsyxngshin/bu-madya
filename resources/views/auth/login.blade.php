<x-guest-layout>
    <div class="min-h-screen bg-stone-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Blobs --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        {{-- Branding Header --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/official_logo.png') }}" class="h-16 w-auto mx-auto" alt="BU MADYA Logo">
            </a>
            <h2 class="mt-6 text-3xl font-heading font-black text-gray-900 tracking-tight">
                Welcome <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 via-yellow-500 to-red-600">Back</span>
            </h2>
            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-gray-400">
                Sign in to your account
            </p>
        </div>

        {{-- Login Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-[2rem] sm:px-10 border border-gray-100 relative overflow-hidden">
                
                {{-- Tricolor Gradient Decoration --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-green-600 via-yellow-400 to-red-600"></div>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-bold text-xs text-green-700 bg-green-50 p-3 rounded-xl border border-green-100 text-center">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="you@bumadya.space">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-gray-400 hover:text-red-600 transition uppercase tracking-wider">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="current-password" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer group">
                            <input id="remember_me" name="remember" type="checkbox" 
                                class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 bg-gray-50 cursor-pointer transition">
                            <span class="ml-2 text-xs text-gray-500 group-hover:text-gray-700 transition font-medium select-none">{{ __('Keep me signed in') }}</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-gray-900 to-gray-800 hover:from-red-600 hover:to-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition hover:-translate-y-0.5 uppercase tracking-widest">
                            {{ __('Sign In') }}
                        </button>
                    </div>
                </form>

                {{-- Register Link --}}
                <div class="mt-6 text-center pt-6 border-t border-gray-50">
                    <p class="text-xs text-gray-500">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-bold text-red-600 hover:text-red-500 transition">
                            Join the Movement
                        </a>
                    </p>
                </div>

            </div>
            
            {{-- Footer Text --}}
            <div class="mt-8 text-center">
                <p class="text-[10px] text-gray-400 font-medium">
                    &copy; {{ date('Y') }} BU MADYA. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    {{-- Tailwind Custom Animation Style --}}
    <style>
        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</x-guest-layout>