<div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
    <div class="border-b border-gray-100 pb-4 mb-6">
        <h2 class="text-xl font-black text-gray-900">Signatory Matrix</h2>
        <p class="text-xs text-gray-500 mt-1">Manage the hierarchical approval chain for this document.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- The Active Hierarchy --}}
    <div class="space-y-3 mb-8">
        @forelse($signatories as $sig)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50 border border-gray-200 rounded-2xl p-4 hover:border-gray-300 transition-colors">
                <div class="flex items-center gap-4">
                    <span class="text-xl font-black text-gray-300 w-6 text-center">{{ $sig->order_sequence }}</span>
                    <div>
                        <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded-md inline-block mb-1">{{ $sig->role }}</span>
                        <h4 class="text-sm font-black text-gray-900">{{ $sig->name }}</h4>
                        <p class="text-xs font-medium text-gray-500">{{ $sig->position }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    @if($sig->has_signed)
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-lg">Signed</span>
                    @else
                        <span class="bg-gray-200 text-gray-500 text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-lg">Pending</span>
                    @endif
                    <button wire:click="removeSignatory({{ $sig->id }})" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Revoke">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No signatories assigned.</p>
            </div>
        @endforelse
    </div>

    {{-- Forging Form (Light rounded inputs) --}}
    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
        <h3 class="text-xs font-bold uppercase text-gray-500 tracking-widest mb-4">Append Signatory</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Approval Role</label>
                <select wire:model="role" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-bold shadow-sm">
                    @foreach($standardRoles as $stdRole)
                        <option value="{{ $stdRole }}">{{ $stdRole }}</option>
                    @endforeach
                    <option value="Custom">Custom Role (Type Below)</option>
                </select>
                @if($role === 'Custom')
                    <input type="text" wire:model="role" placeholder="e.g. Verified By" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-bold shadow-sm mt-2">
                @endif
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Sequence Order</label>
                <input type="number" wire:model="order_sequence" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-bold shadow-sm text-center">
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Signatory Name</label>
                <input type="text" wire:model="name" placeholder="e.g. POLLY CRISTY P. MADRILEJOS" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-bold shadow-sm">
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Official Position</label>
                <input type="text" wire:model="position" placeholder="e.g. Finance Officer, BU CircUITS" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-bold shadow-sm">
            </div>
        </div>

        <button wire:click="addSignatory" class="w-full mt-6 bg-gray-900 text-white font-bold rounded-xl py-3 shadow-md hover:bg-red-600 transition flex justify-center items-center gap-2">
            Lock into Hierarchy
        </button>
    </div>
</div>
