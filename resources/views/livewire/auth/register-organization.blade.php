<div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100">

        <div class="text-center mb-8">
            <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-3">BU MADYA Partners</span>
            <h2 class="text-2xl font-black text-gray-900">Register Organization</h2>
            <p class="text-sm text-gray-500 mt-2">Create an account to submit custom campaign frames.</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Organization / CSO Name</label>
                <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3" placeholder="e.g. Red Cross Youth BU">
                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Official Email</label>
                <input type="email" wire:model="email" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3" placeholder="org@example.com">
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password</label>
                <input type="password" wire:model="password" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3">
                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Confirm Password</label>
                <input type="password" wire:model="password_confirmation" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3">
            </div>

            <button type="submit" class="w-full py-4 bg-gray-900 text-white font-black rounded-xl shadow-lg hover:bg-gray-800 transition-all mt-6 text-sm uppercase tracking-widest">
                <span wire:loading.remove wire:target="register">Create Partner Account</span>
                <span wire:loading wire:target="register">Registering...</span>
            </button>
        </form>
    </div>
</div>
