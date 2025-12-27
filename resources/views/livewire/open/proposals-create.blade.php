<div class="min-h-screen bg-stone-50 font-sans text-gray-900">

    {{-- NAVBAR --}}
    <nav class="relative top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="/" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">
                &larr; Home
            </a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">
                Submit <span class="text-red-600">Proposal</span>
            </span>
        </div>
        
        <button wire:click="save" wire:loading.attr="disabled"
            class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-bold uppercase rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition flex items-center gap-2">
            <span wire:loading.remove>Submit Proposal</span>
            <span wire:loading>Submitting...</span>
            <svg wire:loading class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </button>
    </nav>

    <div class="max-w-3xl mx-auto py-12 px-6 space-y-8">

        {{-- INTRO CARD --}}
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <h1 class="text-2xl font-black font-heading mb-2">Have a Project Idea?</h1>
            <p class="text-gray-300 text-sm leading-relaxed max-w-lg">
                We are looking for impactful initiatives. Fill out this form to submit your proposal to BU MADYA. All submissions are reviewed by our Directorate.
            </p>
        </div>

        {{-- SECTION 1: IDENTITY (Conditional) --}}
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-4 mb-6">1. Proponent Information</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                @auth
                    {{-- Logged In View --}}
                    <div class="col-span-2 flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Submitting as Authenticated User</p>
                        </div>
                    </div>
                @else
                    {{-- Anonymous / Guest View --}}
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input wire:model="name" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Juan Dela Cruz">
                        @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                        <input wire:model="email" type="email" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="juan@example.com">
                        @error('email') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                @endauth

                {{-- Shared Fields --}}
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Affiliation / Type</label>
                    <select wire:model="proponent_type" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-500">
                        <option value="BU Student">BU Student</option>
                        <option value="Faculty">Faculty</option>
                        <option value="NGO">NGO / External</option>
                        <option value="Community Stakeholder">Community Stakeholder</option>
                    </select>
                </div>
                
                {{-- College Dropdown (Only if BU Student/Faculty ideally, but can be general) --}}
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">College / Unit (Optional)</label>
                    <select wire:model="college_id" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-500">
                        <option value="">-- Select College --</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}">{{ $college->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- SECTION 2: THE IDEA --}}
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-4 mb-6">2. The Concept</h3>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Project Title</label>
                    <input wire:model="title" type="text" class="w-full text-lg font-bold border-gray-200 rounded-lg focus:ring-red-500 placeholder-gray-300" placeholder="e.g. Project Kalinga 2025">
                    @error('title') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Project Type</label>
                        <input wire:model="project_type" type="text" class="w-full text-sm border-gray-200 rounded-lg" placeholder="e.g. Seminar, Outreach, Medical Mission">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Beneficiaries</label>
                        <input wire:model="target_beneficiaries" type="text" class="w-full text-sm border-gray-200 rounded-lg" placeholder="e.g. 50 Grade School Students">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Rationale</label>
                    <p class="text-[10px] text-gray-400 mb-2">Why is this project necessary? What problem does it solve?</p>
                    <textarea wire:model="rationale" rows="5" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-500"></textarea>
                    @error('rationale') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                {{-- Objectives (Dynamic List) --}}
                <div class="bg-stone-50 p-5 rounded-xl border border-stone-200">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-xs font-bold text-gray-700 uppercase">Objectives</label>
                        <button wire:click="addObjective" type="button" class="text-[10px] bg-white border border-gray-200 hover:border-red-300 text-gray-600 px-2 py-1 rounded transition shadow-sm">+ Add Item</button>
                    </div>
                    <div class="space-y-2">
                        @foreach($objectives as $index => $obj)
                        <div class="flex gap-2">
                            <span class="flex items-center justify-center w-6 h-8 text-gray-400 text-xs font-bold">{{ $loop->iteration }}.</span>
                            <input wire:model="objectives.{{ $index }}" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-500" placeholder="Specific objective...">
                            <button wire:click="removeObjective({{ $index }})" type="button" class="text-gray-300 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: LOGISTICS & FINANCIALS --}}
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-4 mb-6">3. Logistics & Resources</h3>
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Target Start Date</label>
                    <input wire:model="target_start_date" type="date" class="w-full text-sm border-gray-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Target End Date</label>
                    <input wire:model="target_end_date" type="date" class="w-full text-sm border-gray-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Modality</label>
                    <select wire:model="modality" class="w-full text-sm border-gray-200 rounded-lg">
                        <option value="onsite">On-site</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Proposed Venue</label>
                    <input wire:model="venue" type="text" class="w-full text-sm border-gray-200 rounded-lg">
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Estimated Budget (PHP)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400 text-sm">₱</span>
                            <input wire:model="estimated_budget" type="number" step="0.01" class="w-full pl-7 text-sm border-gray-200 rounded-lg font-mono font-bold" placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Potential Partners</label>
                        <input wire:model="potential_partners" type="text" class="w-full text-sm border-gray-200 rounded-lg" placeholder="LGU, Student Council, etc.">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>