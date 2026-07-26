<div class="max-w-5xl mx-auto space-y-8 pb-24">
    <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#D93B3B]">
        <h1 class="text-2xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Control Zone: System Alerts</h1>
        <p class="text-sm font-bold text-gray-500 mt-1">Dispatch global announcements or ping specific cohorts directly.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- DISPATCH FORM --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#1A1617] border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
            <form wire:submit.prevent="dispatchNotification" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Target Audience</label>
                    <select wire:model="target" class="w-full border-4 border-iba-black p-3 text-xs font-bold bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white focus:outline-none focus:border-iba-teal">
                        <option value="all">🌐 ALL USERS (Global Broadcast)</option>
                        <optgroup label="Specific Teams">
                            @foreach($teams as $team)
                                <option value="team_{{ $team->id }}">🎯 {{ $team->team_name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Alert Type</label>
                        <select wire:model="type" class="w-full border-4 border-iba-black p-3 text-xs font-bold bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white focus:outline-none focus:border-iba-teal">
                            <option value="announcement">📢 Standard Announcement</option>
                            <option value="warning">⚠ Urgent Warning</option>
                            <option value="success">✅ Success / Milestone</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Action Link (Optional)</label>
                        <input type="url" wire:model="link" placeholder="https://..." class="w-full border-4 border-iba-black p-3 text-xs font-bold bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white focus:outline-none focus:border-iba-teal">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Message Payload</label>
                    <textarea wire:model="message" rows="3" class="w-full border-4 border-iba-black p-3 text-sm font-bold bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white focus:outline-none focus:border-iba-teal resize-none" placeholder="Enter system alert message..."></textarea>
                    @error('message') <span class="text-iba-red text-[10px] font-black block mt-1">⚠ {{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-iba-red text-white font-black px-6 py-4 text-sm uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">
                    💥 Dispatch Alert
                </button>
            </form>
        </div>

        {{-- RECENT LOG --}}
        <div class="bg-gray-100 dark:bg-gray-800 border-4 border-iba-black p-6">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-500 mb-4 border-b-2 border-dashed border-gray-300 pb-2">Recent Dispatches</h3>
            <div class="space-y-4">
                @foreach($recentAlerts as $alert)
                    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black p-3 shadow-[2px_2px_0_0_#131011]">
                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 line-clamp-2">{{ $alert->message }}</p>
                        <p class="text-[9px] font-black text-gray-400 mt-2 uppercase">{{ $alert->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>