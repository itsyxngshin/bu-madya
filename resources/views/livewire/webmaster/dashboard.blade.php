<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-900 leading-tight tracking-tight">
            {{ __('Webmaster Operations') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Introduction Banner --}}
            <div class="mb-8 bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                {{-- Decorative background accent --}}
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-5 blur-3xl pointer-events-none"></div>

                <h3 class="text-xl font-black mb-1 relative z-10">OJT Tracking Dashboard</h3>
                <p class="text-sm text-gray-300 font-medium relative z-10">Manage your daily punches, weekly reports, and accumulated practicum hours.</p>
            </div>

            {{-- Responsive CSS Grid Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- Left Column: The Punch Clock (Takes up 1 out of 3 columns) --}}
                <div class="lg:col-span-1 sticky top-6">
                    <livewire:ojt.time-tracker />
                </div>

                {{-- Right Column: The Journal (Takes up 2 out of 3 columns) --}}
                <div class="lg:col-span-2">
                    <livewire:ojt.blog-manager />
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
