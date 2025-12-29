<x-guest-layout>
    <div class="min-h-screen bg-stone-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Blobs --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        {{-- Header / Logo --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/official_logo.png') }}" class="h-16 w-auto mx-auto" alt="BU MADYA Logo">
            </a>
            <h2 class="mt-6 text-2xl font-heading font-black text-gray-900 tracking-tight">
                Verify <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Email</span>
            </h2>
        </div>

        {{-- Main Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-[2rem] sm:px-10 border border-gray-100 relative overflow-hidden">
                
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-500 via-orange-400 to-yellow-400"></div>

                {{-- Icon --}}
                <div class="mx-auto w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-6 text-blue-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>

                <div class="mb-6 text-sm text-gray-600 leading-relaxed text-center">
                    {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 font-bold text-xs text-green-700 bg-green-50 p-3 rounded-xl border border-green-100 text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('A new verification link has been sent to your email.') }}
                    </div>
                @endif

                <div class="space-y-4">
                    {{-- Primary Action: Resend --}}
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gray-900 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition hover:-translate-y-0.5 uppercase tracking-widest">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    {{-- Secondary Actions --}}
                    <div class="flex items-center justify-between border-t border-gray-100 pt-4 mt-4">
                        <a href="{{ route('profile.show') }}" 
                           class="text-xs font-bold text-gray-400 hover:text-red-600 transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Edit Profile') }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-gray-400 hover:text-red-600 transition flex items-center gap-1">
                                {{ __('Log Out') }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
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