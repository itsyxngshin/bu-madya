<div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
    <div class="border-b-4 border-iba-black pb-4 mb-6">
        <h2 class="text-xl font-black uppercase text-iba-black tracking-widest">Signatory Matrix</h2>
        <p class="text-[10px] font-bold text-gray-500 uppercase">Manage the hierarchical approval chain for this document.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-3 mb-6 shadow-[2px_2px_0_0_#131011]">
            <p class="text-[10px] font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    {{-- The Active Hierarchy --}}
    <div class="space-y-4 mb-8">
        @forelse($signatories as $sig)
            <div class="flex items-center justify-between border-2 border-dashed border-gray-300 p-4 hover:border-iba-black hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-4">
                    <span class="text-2xl font-black text-gray-300 w-8 text-center">{{ $sig->order_sequence }}</span>
                    <div>
                        <span class="text-[9px] font-black text-iba-orange uppercase tracking-widest">{{ $sig->role }}:</span>
                        <h4 class="text-sm font-black text-iba-black">{{ $sig->name }}</h4>
                        <p class="text-[10px] font-bold text-gray-500 uppercase">{{ $sig->position }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @if($sig->has_signed)
                        <span class="bg-iba-teal text-white text-[8px] font-black uppercase px-2 py-1 border border-iba-black">Signed</span>
                    @else
                        <span class="bg-gray-200 text-gray-500 text-[8px] font-black uppercase px-2 py-1 border border-gray-300">Pending</span>
                    @endif
                    <button wire:click="removeSignatory({{ $sig->id }})" class="text-gray-400 hover:text-iba-red transition-colors" title="Revoke Signatory">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-8 border-4 border-dashed border-gray-200 bg-gray-50">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No signatories assigned yet.</p>
            </div>
        @endforelse
    </div>

    {{-- Forging Form --}}
    <div class="bg-gray-100 border-2 border-iba-black p-4">
        <h3 class="text-xs font-black uppercase tracking-widest mb-4">Append Signatory</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Approval Role</label>
                <select wire:model="role" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-white">
                    @foreach($standardRoles as $stdRole)
                        <option value="{{ $stdRole }}">{{ $stdRole }}</option>
                    @endforeach
                    <option value="Custom">Custom Role (Type Below)</option>
                </select>
                @if($role === 'Custom')
                    <input type="text" wire:model="role" placeholder="e.g. Verified By" class="w-full border-2 border-iba-black border-t-0 p-2 font-bold focus:outline-none focus:border-iba-orange bg-white mt-1">
                @endif
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Sequence Order</label>
                <input type="number" wire:model="order_sequence" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-white text-center">
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Signatory Name</label>
                <input type="text" wire:model="name" placeholder="e.g. POLLY CRISTY P. MADRILEJOS" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-white">
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Official Position</label>
                <input type="text" wire:model="position" placeholder="e.g. Finance Officer, BU CircUITS" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-white">
            </div>
        </div>

        <button wire:click="addSignatory" class="w-full mt-4 bg-iba-black text-white text-xs font-black uppercase tracking-widest py-3 hover:bg-iba-orange hover:text-iba-black transition-all">
            Lock into Hierarchy
        </button>
    </div>
</div>
