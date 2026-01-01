<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    <div class="p-8 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-900">{{ __('Update Password') }}</h3>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <form wire:submit="updatePassword" class="p-8 space-y-6">
        {{-- Current Password --}}
        <div>
            <x-label for="current_password" value="{{ __('Current Password') }}" class="font-bold text-gray-700" />
            <x-input id="current_password" type="password" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-yellow-400 focus:border-yellow-400" wire:model="state.current_password" autocomplete="current-password" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        {{-- New Password --}}
        <div>
            <x-label for="password" value="{{ __('New Password') }}" class="font-bold text-gray-700" />
            <x-input id="password" type="password" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-yellow-400 focus:border-yellow-400" wire:model="state.password" autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="font-bold text-gray-700" />
            <x-input id="password_confirmation" type="password" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-yellow-400 focus:border-yellow-400" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>

        <div class="flex items-center justify-end pt-4 border-t border-gray-100">
            <x-action-message class="me-3 text-green-600 font-bold" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button class="bg-gray-900 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-lg border-0">
                {{ __('Save Password') }}
            </x-button>
        </div>
    </form>
</div>