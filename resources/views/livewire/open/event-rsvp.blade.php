<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4 sm:p-8 font-sans relative overflow-hidden">

    {{-- Blurred Background (Luma Style) --}}
    @if($event->cover_image)
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover opacity-20 blur-3xl scale-110">
        </div>
    @endif

    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">

        {{-- LEFT COLUMN: EVENT DETAILS --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100 flex flex-col justify-between">
            <div>
                @if($event->cover_image)
                    <div class="w-full aspect-video rounded-2xl overflow-hidden mb-8 shadow-sm">
                        <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-tight mb-6">
                    {{ $event->title }}
                </h1>

                <div class="space-y-4 mb-8">
                    {{-- Date/Time --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900">
                            {{ $event->start_date ? $event->start_date->format('l, F j, Y') : 'Date TBA' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $event->start_date ? $event->start_date->format('g:i A') : 'Time TBA' }}
                        </p>
                    </div>

                    {{-- Location --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $event->location }}</p>
                            <a href="#" class="text-xs font-bold text-orange-500 hover:underline">View Map</a>
                        </div>
                    </div>
                </div>

                <div class="prose prose-sm text-gray-600">
                    {{ $event->description }}
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: RSVP / TICKETING --}}
        <div class="flex items-center">
            <div class="w-full bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100">

                {{-- STATE: ALREADY REGISTERED (Show Ticket) --}}
                @if($isRegistered)
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-1">You're in!</h2>
                        <p class="text-sm text-gray-500 mb-8">Present this QR code at the entrance.</p>

                        {{-- THE QR CODE --}}
                        {{-- THE STYLISTIC QR CODE --}}
                        <div class="bg-white p-4 rounded-3xl border border-gray-100 inline-block mb-6 shadow-md relative"
                             x-data="{
                                init() {
                                    const qrCode = new QRCodeStyling({
                                        width: 220,
                                        height: 220,
                                        data: '{{ $registrationRecord->ticket_code }}',
                                        image: '/path/to/your/madya-logo.png', // OPTIONAL: Add your logo path here
                                        dotsOptions: {
                                            color: '#111827', // Dark gray/black for the main dots
                                            type: 'rounded'   // Options: rounded, dots, classy, classy-rounded, square
                                        },
                                        cornersSquareOptions: {
                                            type: 'extra-rounded', // Makes the outer corner boxes pill-shaped
                                            color: '#ea580c'       // Orange-600 to match your theme
                                        },
                                        cornersDotOptions: {
                                            type: 'dot',           // Makes the inner corner dot a circle
                                            color: '#ea580c'       // Orange-600
                                        },
                                        backgroundOptions: {
                                            color: 'transparent',
                                        },
                                        imageOptions: {
                                            crossOrigin: 'anonymous',
                                            margin: 8
                                        }
                                    });
                                    qrCode.append(this.$refs.qrBox);
                                }
                             }">
                            {{-- The library will inject the canvas into this div --}}
                            <div x-ref="qrBox" class="flex justify-center items-center"></div>
                        </div>

                        {{-- REGISTRATION DETAILS --}}

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Ticket Code</p>
                            <p class="text-lg font-mono font-bold text-gray-900">{{ $registrationRecord->ticket_code }}</p>
                            <p class="text-sm font-bold text-gray-700 mt-2">{{ $registrationRecord->name }}</p>
                        </div>
                    </div>

                {{-- STATE: REGISTRATION FORM --}}
                @else
                    <h2 class="text-2xl font-black text-gray-900 mb-6">Join Event</h2>

                    @if(session()->has('error'))
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-bold mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="register" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Full Name</label>
                            <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3" placeholder="Juan Dela Cruz">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Address</label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3" placeholder="juan@example.com">
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 bg-gray-900 text-white font-black rounded-xl shadow-lg hover:bg-gray-800 transition-all mt-4 text-sm uppercase tracking-widest">
                            Register Now
                        </button>
                    </form>

                    <p class="text-xs text-gray-400 text-center mt-6">
                        By registering, you agree to BU MADYA's privacy policy regarding event data collection.
                    </p>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
@endpush

