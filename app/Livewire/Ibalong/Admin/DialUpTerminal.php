<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongRegistration;
use App\Mail\IbalongTransmission;
use Illuminate\Support\Facades\Mail;

class DialUpTerminal extends Component
{
    public $target = 'all'; // 'all' or 'specific'

    // Changed to an array to hold multiple selections
    public $selectedTeamIds = [];

    public $subject = '';
    public $messageBody = '';
    public $teams = [];

    public function mount()
    {
        // RBAC: Admins (1, 2) and Facilitators (4)
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Dial-Up terminal requires Command Center clearance.');
        }

        // Load all active cohorts to populate the roster
        $this->teams = IbalongRegistration::with('user')
            ->whereNotNull('user_id')
            ->orderBy('team_name', 'asc')
            ->get();
    }

    public function transmit()
    {
        $this->validate([
            'target' => 'required|in:all,specific',
            'selectedTeamIds' => 'required_if:target,specific|array',
            'subject' => 'required|string|max:150',
            'messageBody' => 'required|string',
        ]);

        $dispatchedCount = 0;

        if ($this->target === 'all') {
            foreach ($this->teams as $team) {
                if ($team->user && $team->user->email) {
                    Mail::to($team->user->email)->send(new IbalongTransmission($this->subject, $this->messageBody, $team->team_name));
                    $dispatchedCount++;
                }
            }
        } else {
            // Fetch all cohorts that were checked off in the list
            $selectedTeams = IbalongRegistration::with('user')
                ->whereIn('id', $this->selectedTeamIds)
                ->get();

            foreach ($selectedTeams as $team) {
                if ($team->user && $team->user->email) {
                    Mail::to($team->user->email)->send(new IbalongTransmission($this->subject, $this->messageBody, $team->team_name));
                    $dispatchedCount++;
                }
            }
        }

        // Reset the form
        $this->reset(['subject', 'messageBody', 'selectedTeamIds']);

        // Tell the Quill rich text editor to clear its canvas
        $this->dispatch('messageBody-reset');

        session()->flash('success', "TRANSMISSION SUCCESSFUL: Signal routed to {$dispatchedCount} cohort leader(s).");
    }

    public function render()
    {
        return view('livewire.ibalong.admin.dial-up-terminal')->layout('layouts.dashboard');
    }
}
