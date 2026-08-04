<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongRegistration;
use App\Mail\IbalongTransmission;
use Illuminate\Support\Facades\Mail;

class DialUpTerminal extends Component
{
    public $target = 'all'; // 'all' or 'specific'
    public $selectedTeamId = '';
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

        // Load all active cohorts to populate the dropdown
        $this->teams = IbalongRegistration::with('user')
            ->whereNotNull('user_id')
            ->orderBy('team_name', 'asc')
            ->get();
    }

    public function transmit()
    {
        $this->validate([
            'target' => 'required|in:all,specific',
            'selectedTeamId' => 'required_if:target,specific',
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
            $team = IbalongRegistration::with('user')->findOrFail($this->selectedTeamId);
            if ($team->user && $team->user->email) {
                Mail::to($team->user->email)->send(new IbalongTransmission($this->subject, $this->messageBody, $team->team_name));
                $dispatchedCount++;
            }
        }

        // Reset the form
        $this->reset(['subject', 'messageBody', 'selectedTeamId']);

        session()->flash('success', "TRANSMISSION SUCCESSFUL: Signal routed to {$dispatchedCount} cohort leader(s).");
    }

    public function render()
    {
        return view('livewire.ibalong.admin.dial-up-terminal')->layout('layouts.dashboard');
    }
}
