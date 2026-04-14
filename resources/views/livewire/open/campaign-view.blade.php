<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6">
    
    {{-- Campaign Header & Image --}}
    @if($campaign->cover_image)
        <img src="{{ asset('storage/'.$campaign->cover_image) }}" class="w-full h-64 md:h-96 object-cover rounded-3xl mb-8 shadow-md">
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- LEFT COLUMN: The Details --}}
        <div class="lg:col-span-2">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 leading-tight">{{ $campaign->title }}</h1>
            
            {{-- THE ORGANIZER BLOCK (User vs Organization Logic) --}}
            <div class="flex items-center gap-4 py-4 mb-6 border-y border-gray-100">
                @if($campaign->creator->role?->role_name === 'organization')
                    {{-- Official Organization Branding --}}
                    @if($campaign->creator->profile_photo_path)
                        <img src="{{ asset('storage/'.$campaign->creator->profile_photo_path) }}" class="w-12 h-12 rounded-full border border-gray-200 shadow-sm object-cover">
                    @else
                        <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xl">
                            {{ substr($campaign->creator->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-orange-500">Official Campaign</p>
                        <p class="text-sm font-bold text-gray-900">{{ $campaign->creator->name }}</p>
                    </div>
                @else
                    {{-- Regular Student/User Branding --}}
                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold">
                        {{ substr($campaign->creator->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Started by</p>
                        <p class="text-sm font-bold text-gray-700">{{ $campaign->creator->name }}</p>
                    </div>
                @endif
            </div>

            <div class="prose prose-orange max-w-none text-gray-700">
                {!! Str::markdown($campaign->description) !!}
            </div>
        </div>

        {{-- RIGHT COLUMN: The Action Widget --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-gray-100 sticky top-6">
                
                <h3 class="text-2xl font-black text-gray-900 mb-1">
                    {{ number_format($signatureCount) }} <span class="text-base font-medium text-gray-500">signatures</span>
                </h3>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Goal: {{ number_format($campaign->target_signatures) }}</p>

                {{-- The Progress Bar --}}
                <div class="w-full bg-gray-100 rounded-full h-3 mb-6 overflow-hidden relative">
                    <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progressPercentage }}%"></div>
                </div>

                {{-- Interactive Button --}}
                @if($hasSigned)
                    <div class="w-full py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl text-center shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        You have signed this!
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500 mb-2">Help us reach our goal by sharing:</p>
                        {{-- Add Facebook/Twitter share buttons here --}}
                    </div>
                @else
                    <button wire:click="signPetition" wire:loading.attr="disabled" class="w-full py-4 bg-gray-900 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-orange-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="signPetition">Sign this Petition</span>
                        <span wire:loading wire:target="signPetition" class="animate-pulse">Signing...</span>
                    </button>
                @endif

            </div>
        </div>
    </div>
</div>