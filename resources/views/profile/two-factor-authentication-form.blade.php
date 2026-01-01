<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    <div class="p-8 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-900">{{ __('Two Factor Authentication') }}</h3>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Add additional security to your account using two factor authentication.') }}
        </p>
    </div>

    <div class="p-8">
        <h3 class="text-lg font-bold text-gray-900">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('Finish enabling two factor authentication.') }}
                @else
                    {{ __('You have enabled two factor authentication.') }}
                @endif
            @else
                {{ __('You have not enabled two factor authentication.') }}
            @endif
        </h3>

        <div class="mt-3 text-sm text-gray-600">
            <p>
                {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 text-sm text-gray-600 font-medium bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="font-bold text-gray-900 mb-2">
                        @if ($showingConfirmation)
                            {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                        @else
                            {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                        @endif
                    </p>
                    
                    <div class="mt-4 p-4 inline-block bg-white rounded-lg border border-gray-200 shadow-sm">
                        {!! $this->user->twoFactorQrCodeSvg() !!}
                    </div>

                    <div class="mt-4 text-xs font-mono bg-gray-200 px-3 py-2 rounded inline-block text-gray-700">
                        {{ __('Setup Key') }}: {{ decrypt($this->user->two_factor_secret) }}
                    </div>

                    @if ($showingConfirmation)
                        <div class="mt-4">
                            <x-label for="code" value="{{ __('Code') }}" class="font-bold" />
                            <x-input id="code" type="text" name="code" class="block mt-1 w-1/2 rounded-xl border-gray-300 focus:ring-yellow-400 focus:border-yellow-400" inputmode="numeric" autofocus autocomplete="one-time-code"
                                wire:model="code"
                                wire:keydown.enter="confirmTwoFactorAuthentication" />
                            <x-input-error for="code" class="mt-2" />
                        </div>
                    @endif
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 text-sm text-gray-600 bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                    <p class="font-bold text-yellow-800">
                        {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                    </p>
                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-white rounded-lg border border-yellow-200 text-gray-700">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button type="button" wire:loading.attr="disabled" class="bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-bold border-0">
                        {{ __('Enable') }}
                    </x-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-secondary-button class="me-3 rounded-xl font-bold">
                            {{ __('Regenerate Recovery Codes') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <x-button type="button" class="me-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-bold border-0" wire:loading.attr="disabled">
                            {{ __('Confirm') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <x-secondary-button class="me-3 rounded-xl font-bold">
                            {{ __('Show Recovery Codes') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-secondary-button wire:loading.attr="disabled" class="rounded-xl font-bold">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-danger-button wire:loading.attr="disabled" class="bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold border-0">
                            {{ __('Disable') }}
                        </x-danger-button>
                    </x-confirms-password>
                @endif
            @endif
        </div>
    </div>
</div>