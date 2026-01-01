<div class="bg-white rounded-3xl shadow-xl overflow-hidden border-2 border-red-50">
    <div class="p-8 border-b border-red-50 bg-red-50/30">
        <h3 class="text-xl font-bold text-red-600">{{ __('Delete Account') }}</h3>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Permanently delete your account.') }}
        </p>
    </div>

    <div class="p-8">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled" class="rounded-xl font-bold shadow-lg">
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>

        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Delete Account') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4 rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500"
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled" class="rounded-xl font-bold">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3 rounded-xl font-bold" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>