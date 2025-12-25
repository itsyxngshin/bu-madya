<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- 1. EDITOR NAV --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('linkages.show', $partnerId) }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">
                &larr; Cancel
            </a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">
                Edit <span class="text-blue-600">Partner</span>
            </span>
        </div>
        
        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-xs font-bold uppercase bg-gray-200 px-3 py-1 rounded">
            <span x-text="mobilePreview ? 'Edit' : 'Preview'"></span>
        </button>

        <div class="flex items-center gap-3">
            {{-- Flash Message --}}
            <span class="text-xs text-green-600 font-bold mr-2" 
                  x-data="{ show: false }" 
                  x-show="show" 
                  x-transition.duration.1000ms
                  x-init="@this.on('message', () => { show = true; setTimeout(() => show = false, 2000) })">
                Changes Saved!
            </span>

            <button wire:click="update" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md">
                Update Profile
            </button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: EDITOR       --}}
        {{-- ======================== --}}
        <div class="w-full md:w-5/12 h-full overflow-y-auto p-6 bg-gray-50 border-r border-gray-200 space-y-6"
             :class="mobilePreview ? 'hidden' : 'block'">

            {{-- 1. IDENTITY --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Partner Identity</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Organization Name</label>
                        <input wire:model.live="name" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-blue-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Type</label>
                            <select wire:model.live="type" class="w-full text-xs border-gray-200 rounded-lg">
                                <option>Government</option>
                                <option>NGO</option>
                                <option>Academic</option>
                                <option>International</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                            <select wire:model.live="status" class="w-full text-xs border-gray-200 rounded-lg">
                                <option>MOU Signed</option>
                                <option>Accredited</option>
                                <option>Formal Partner</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Partner Since</label>
                            <input wire:model.live="since" type="text" class="w-full text-xs border-gray-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Scope</label>
                            <input wire:model.live="scope" type="text" class="w-full text-xs border-gray-200 rounded-lg">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. DESCRIPTION --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">About</h3>
                <textarea wire:model.live="description" rows="4" class="w-full text-sm border-gray-200 rounded-lg focus:ring-blue-500"></textarea>
            </div>

            {{-- 3. ENGAGEMENTS --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-xs font-bold text-gray-700 uppercase">Engagement History</label>
                    <button wire:click="addEngagement" class="text-[10px] bg-gray-100 hover:bg-blue-100 text-gray-600 px-2 py-1 rounded transition">+ Add Activity</button>
                </div>
                
                <div class="space-y-4">
                    @foreach($engagements as $index => $eng)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 relative group">
                        <button wire:click="removeEngagement({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <input wire:model.live="engagements.{{ $index }}.date" type="text" class="text-[10px] border-gray-200 rounded" placeholder="Date">
                            <input wire:model.live="engagements.{{ $index }}.type" type="text" class="text-[10px] border-gray-200 rounded" placeholder="Type">
                        </div>
                        <input wire:model.live="engagements.{{ $index }}.title" type="text" class="w-full text-xs font-bold border-gray-200 rounded mb-2" placeholder="Title">
                        <textarea wire:model.live="engagements.{{ $index }}.desc" rows="2" class="w-full text-[10px] border-gray-200 rounded" placeholder="Description"></textarea>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 4. SDGs --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">SDGs</h3>
                <div class="grid grid-cols-6 gap-2">
                    @foreach($allSdgs as $id => $sdg)
                    <button wire:click="toggleSdg({{ $id }})" 
                            class="aspect-square flex items-center justify-center rounded text-[10px] font-bold transition-all transform hover:scale-105
                            {{ in_array($id, $selectedSdgs) ? $sdg['color'] . ' text-white ring-2 ring-offset-1 ring-gray-300' : 'bg-gray-100 text-gray-400' }}">
                        {{ $id }}
                    </button>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL: PREVIEW     --}}
        {{-- ======================== --}}
        <div class="w-full md:w-7/12 h-full overflow-y-auto bg-stone-100 relative shadow-inner"
             :class="mobilePreview ? 'block' : 'hidden md:block'">
            
            <div class="absolute top-4 right-4 z-50 bg-black/80 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full backdrop-blur pointer-events-none">
                Live Preview
            </div>

            {{-- START OF SHOW TEMPLATE (Simplified for context) --}}
            <div class="min-h-full bg-stone-50 pb-20 origin-top scale-90 md:scale-100 transition-transform pointer-events-none select-none">
                
                {{-- Hero Cover --}}
                <div class="relative h-[300px] w-full overflow-hidden bg-gray-200">
                    @if($cover_img) <img src="{{ $cover_img }}" class="w-full h-full object-cover"> @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-stone-50 via-stone-50/20 to-transparent"></div>
                </div>

                {{-- Content Area --}}
                <div class="max-w-5xl mx-auto px-6 -mt-32 relative z-10 pb-24">
                    <div class="grid lg:grid-cols-12 gap-8">
                        
                        {{-- Left Sidebar --}}
                        <aside class="lg:col-span-4 space-y-8">
                            {{-- Profile Card --}}
                            <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100 text-center relative overflow-hidden">
                                <div class="w-32 h-32 mx-auto bg-white rounded-2xl p-2 shadow-lg -mt-16 mb-6 border border-gray-100 relative z-10">
                                    @if($logo) <img src="{{ $logo }}" class="w-full h-full object-contain rounded-xl"> @endif
                                </div>
                                <h1 class="font-heading font-black text-2xl text-gray-900 leading-tight mb-2">{{ $name }}</h1>
                                <div class="flex flex-wrap justify-center gap-2 mb-6">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded-full">{{ $type }}</span>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-wider rounded-full">{{ $status }}</span>
                                </div>
                            </div>

                            {{-- SDGs --}}
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Goals</h3>
                                <div class="flex flex-col gap-2">
                                    @foreach($selectedSdgs as $id)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 {{ $allSdgs[$id]['color'] ?? 'bg-gray-500' }} rounded text-white font-black text-xs flex items-center justify-center">{{ $id }}</div>
                                        <span class="text-[10px] font-bold text-gray-700 uppercase">{{ $allSdgs[$id]['label'] ?? 'SDG '.$id }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </aside>

                        {{-- Right Content --}}
                        <main class="lg:col-span-8 space-y-12 pt-8 lg:pt-0">
                            {{-- About --}}
                            <section>
                                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-blue-500 w-16 pb-2 mb-6">About</h3>
                                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                                    <p class="text-gray-600 leading-relaxed font-serif text-lg">{{ $description }}</p>
                                    <div class="mt-6">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Scope</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(explode(',', $scope) as $tag)
                                                @if(trim($tag))
                                                <span class="px-3 py-1 bg-gray-50 text-gray-600 text-xs font-bold rounded-lg border border-gray-200">{{ trim($tag) }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {{-- Timeline --}}
                            <section>
                                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-red-500 w-32 pb-2 mb-6">Journey</h3>
                                <div class="relative border-l-2 border-gray-200 ml-4 space-y-8 pl-8 pb-4">
                                    @foreach($engagements as $activity)
                                    <div class="relative group">
                                        <div class="absolute -left-[41px] top-1 w-6 h-6 bg-white rounded-full border-4 border-gray-200"></div>
                                        <h4 class="font-heading font-bold text-lg text-gray-900">{{ $activity['title'] }}</h4>
                                        <p class="text-xs text-gray-400 mb-2">{{ $activity['date'] }} • {{ $activity['type'] }}</p>
                                        <p class="text-sm text-gray-600 leading-relaxed bg-white p-4 rounded-xl shadow-sm border border-gray-100">{{ $activity['desc'] }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </section>
                        </main>

                    </div>
                </div>
            </div>
            {{-- END PREVIEW --}}

        </div>
    </div>
</div>