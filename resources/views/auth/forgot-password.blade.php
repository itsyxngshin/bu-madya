<x-guest-layout>
    <div class="min-h-screen bg-stone-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Blobs --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] left-[-5%] w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        {{-- Header / Logo --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/official_logo.png') }}" class="h-16 w-auto mx-auto" alt="BU MADYA Logo">
            </a>
            <h2 class="mt-6 text-3xl font-heading font-black text-gray-900 tracking-tight">
                Reset <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Password</span>
            </h2>
        </div>

        {{-- Main Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-[2rem] sm:px-10 border border-gray-100 relative overflow-hidden">
                
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-500 via-orange-400 to-yellow-400"></div>

                <div class="mb-6 text-sm text-gray-600 leading-relaxed text-center">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                </div>

                @session('status')
                    <div class="mb-4 font-bold text-sm text-green-600 bg-green-50 p-3 rounded-xl border border-green-100 text-center">
                        {{ $value }}
                    </div>
                @endsession

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    {{-- Email Input --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:border-transparent transition-all sm:text-sm"
                                placeholder="you@bumadya.space">
                            
                            {{-- Icon --}}
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gray-900 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition hover:-translate-y-0.5 uppercase tracking-widest">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>
                </form>

                {{-- Back to Login --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-gray-400 hover:text-red-600 transition flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Login
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Tailwind Custom Animation Style --}}
    <style>
        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
</x-guest-layout>