<div class="max-w-6xl mx-auto px-4 py-12 space-y-8">

    {{-- Load HTML5 QR Code Scanner Library globally for the component --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    {{-- Terminal Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 sm:p-8 text-white text-center relative overflow-hidden">
        @if(!$activePoll)
            <h1 class="text-3xl font-black uppercase tracking-widest text-iba-red">Terminal Offline</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-2">There are currently no active voting broadcasts.</p>
        @else
            <div class="absolute top-4 right-4 flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-iba-green opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-iba-green"></span>
                </span>
                <span class="text-[10px] font-black text-iba-green uppercase tracking-widest hidden sm:inline-block">Live Broadcast</span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-black uppercase tracking-widest text-white mt-4">{{ $activePoll->title }}</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-2">Select your choice from the official nominees below.</p>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-6 shadow-[4px_4px_0_0_#131011] animate-fade-in-up">
            <p class="text-sm font-black text-iba-teal uppercase tracking-widest flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-iba-red/10 border-l-4 border-iba-red p-6 shadow-[4px_4px_0_0_#131011] animate-fade-in-up">
            <p class="text-sm font-black text-iba-red uppercase tracking-widest flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </p>
        </div>
    @endif

    @if($activePoll)
        @if($hasVoted)
            {{-- Post-Vote State --}}
            <div class="bg-gray-50 border-4 border-dashed border-gray-400 p-12 text-center shadow-[6px_6px_0_0_#131011] animate-fade-in-up">
                <h2 class="text-xl font-black text-iba-black uppercase tracking-widest mb-2">Vote Locked</h2>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Your authorization has been recorded. Please wait for the final tabulation.</p>
            </div>
        @elseif(count($teams) === 0)
            {{-- No Nominees State --}}
            <div class="bg-gray-50 border-4 border-dashed border-iba-red p-12 text-center shadow-[6px_6px_0_0_#131011] animate-fade-in-up">
                <h2 class="text-xl font-black text-iba-red uppercase tracking-widest mb-2">Standby for Nominees</h2>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">The Command Center is currently finalizing the eligible cohorts.</p>
            </div>
        @else
            {{-- Active Voting Interface --}}
            <div class="space-y-8 animate-fade-in-up">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($teams as $team)
                        <div wire:click="selectTeam({{ $team->id }})"
                             class="border-4 flex flex-col cursor-pointer transition-all duration-200 relative group p-6
                             {{ $selectedTeamId === $team->id ? 'border-iba-black bg-iba-orange shadow-[6px_6px_0_0_#131011] -translate-y-1' : 'border-gray-300 bg-white hover:border-iba-black hover:bg-orange-50 hover:-translate-y-1 hover:shadow-[6px_6px_0_0_#131011]' }}">

                            {{-- Selection Indicator --}}
                            <div class="absolute top-4 right-4 w-6 h-6 border-2 border-iba-black rounded-full flex items-center justify-center {{ $selectedTeamId === $team->id ? 'bg-iba-black' : 'bg-white' }}">
                                @if($selectedTeamId === $team->id)
                                    <div class="w-2.5 h-2.5 bg-iba-orange rounded-full"></div>
                                @endif
                            </div>

                            {{-- Cohort Identity (Logo + Name) --}}
                            <div class="flex items-center gap-4 mb-4 pr-8">
                                <div class="w-16 h-16 bg-white border-2 border-iba-black shadow-[2px_2px_0_0_#131011] flex items-center justify-center overflow-hidden shrink-0">
                                    @if($team->logo || $team->logo_path)
                                        <img src="{{ Storage::url($team->logo ?? $team->logo_path) }}" alt="Logo" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl font-black text-gray-300">?</span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-black uppercase text-iba-black leading-tight">{{ $team->team_name }}</h3>
                                    <p class="text-[10px] font-bold uppercase {{ $selectedTeamId === $team->id ? 'text-black/70' : 'text-gray-500' }} mt-1">
                                        {{ $team->category ?? 'General' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Project Description --}}
                            <div class="border-t-2 border-dashed {{ $selectedTeamId === $team->id ? 'border-black/30' : 'border-gray-300' }} pt-4 mt-auto">
                                <p class="text-xs font-bold {{ $selectedTeamId === $team->id ? 'text-black/80' : 'text-gray-600' }} line-clamp-3">
                                    {{ $team->team_about ?? $team->project_description ?? 'No project narrative provided in the logs.' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    {{-- MODAL: Execute Vote & Scan Ticket --}}
    @if($selectedTeamId)
        @php
            $selectedTeam = $teams->firstWhere('id', $selectedTeamId);
        @endphp

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/90 backdrop-blur-sm" wire:click="cancelSelection"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white border-4 border-iba-black shadow-[12px_12px_0_0_#FF8623] flex flex-col text-left animate-fade-in-up">

                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b-4 border-iba-black bg-gray-100">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Confirm Authorization For:</h3>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white border-2 border-iba-black shrink-0 flex items-center justify-center overflow-hidden">
                                @if($selectedTeam->logo || $selectedTeam->logo_path)
                                    <img src="{{ Storage::url($selectedTeam->logo ?? $selectedTeam->logo_path) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xl font-black text-gray-300">?</span>
                                @endif
                            </div>
                            <h2 class="text-2xl font-black uppercase text-iba-black leading-tight">{{ $selectedTeam->team_name }}</h2>
                        </div>
                    </div>

                    <div class="p-6">
                        @if (session()->has('error'))
                            <div class="bg-iba-red text-white p-3 mb-6 border-2 border-iba-black font-black text-xs uppercase tracking-widest shadow-[2px_2px_0_0_#131011]">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="castVote" class="space-y-6">
                            @if($activePoll->require_ticket)

                                <div class="bg-gray-50 p-4 border-2 border-dashed border-gray-300 relative">
                                    <label class="block text-[10px] font-black uppercase text-iba-red mb-2 tracking-widest">Event Ticket Verification <span class="text-iba-black">*</span></label>

                                    {{-- Ticket Input Field --}}
                                    <div class="flex gap-2 mb-3">
                                        <input type="text" wire:model="ticketCode" id="ticketCodeInput" placeholder="Enter code manually..." class="w-full border-2 border-iba-black p-3 font-black uppercase tracking-widest focus:outline-none focus:border-iba-teal bg-white text-lg">

                                        {{-- Open Scanner Button --}}
                                        <button type="button" onclick="window.initVotingScanner()" class="bg-iba-black text-white px-4 border-2 border-iba-black hover:bg-iba-teal transition-colors flex items-center justify-center" title="Scan or Upload QR Ticket">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        </button>
                                    </div>
                                    @error('ticketCode') <span class="text-[10px] font-bold text-iba-red uppercase block mt-1">{{ $message }}</span> @enderror

                                    {{-- QR Scanner UI Container (Hidden by default) --}}
                                    <div wire:ignore>
                                        <div id="qr-reader" style="display: none;" class="w-full border-4 border-dashed border-gray-300 p-2 bg-white text-xs mt-4"></div>
                                    </div>
                                </div>

                            @else
                                <div class="bg-gray-100 border-2 border-dashed border-gray-300 p-4 text-center">
                                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Public Broadcast: No ticket required.</p>
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t-2 border-iba-black">
                                <button type="button" wire:click="cancelSelection" class="w-full sm:w-1/3 bg-gray-100 text-iba-black text-xs font-black uppercase tracking-widest py-4 border-2 border-iba-black hover:bg-gray-200 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="w-full sm:w-2/3 bg-iba-teal text-white text-sm font-black uppercase tracking-widest py-4 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">
                                    Finalize Vote
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Javascript & Styling for QR Scanner Protocol --}}
    <script>
        let votingScanner = null;

        window.initVotingScanner = function() {
            const scannerDiv = document.getElementById('qr-reader');
            if (!scannerDiv) return;

            // Toggle scanner visibility
            if (scannerDiv.style.display === 'block') {
                if (votingScanner) {
                    votingScanner.clear().catch(e => console.log("Clear error", e));
                    votingScanner = null;
                }
                scannerDiv.style.display = 'none';
                return;
            }

            scannerDiv.style.display = 'block';

            votingScanner = new Html5QrcodeScanner(
                "qr-reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                false
            );

            votingScanner.render(
                function onScanSuccess(decodedText) {
                    // 1. Play success tone
                    const beep = new Audio('https://www.soundjay.com/buttons/sounds/button-09.mp3');
                    beep.play().catch(e => console.log('Audio feedback disabled by browser.'));

                    // 2. Push decoded value to Livewire backend
                    @this.set('ticketCode', decodedText);

                    // 3. Close the scanner UI automatically
                    votingScanner.clear().catch(e => console.log("Clear error", e));
                    votingScanner = null;
                    scannerDiv.style.display = 'none';
                },
                function onScanFailure(error) {
                    // Suppress continuous background scanning warnings
                }
            );
        };

        // Listen for the backend telling us to close the modal to safely shut down the camera
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('close-scanner', () => {
                if (votingScanner) {
                    votingScanner.clear().catch(error => {
                        console.error("Failed to clear scanner.", error);
                    });
                    votingScanner = null;
                }
            });
        });
    </script>

    <style>
        /* Neo-Brutalist overrides for the injected QR library UI */
        #qr-reader { border: none !important; }
        #qr-reader img { margin: 0 auto; }
        #qr-reader button {
            background-color: #0095AC !important;
            color: white !important;
            border: 2px solid #131011 !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            box-shadow: 3px 3px 0 0 #131011 !important;
            border-radius: 0 !important;
            margin: 10px 5px !important;
            transition: all 0.2s;
        }
        #qr-reader button:hover {
            transform: translateY(2px);
            box-shadow: none !important;
        }
        #qr-reader__dashboard_section_swaplink {
            color: #FF8623 !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            text-decoration: none !important;
            letter-spacing: 1px !important;
            display: inline-block !important;
            margin: 15px 0 !important;
            border-bottom: 2px dashed #FF8623 !important;
            transition: color 0.2s;
        }
        #qr-reader__dashboard_section_swaplink:hover {
            color: #CF452C !important;
            border-color: #CF452C !important;
        }
        #qr-reader__dashboard_section_fsr input[type="file"] {
            background: #ffffff !important;
            border: 2px solid #131011 !important;
            padding: 8px !important;
            font-weight: bold !important;
            margin-top: 10px !important;
            width: 100%;
            max-width: 300px;
            color: #131011 !important;
            cursor: pointer;
        }
    </style>
</div>
