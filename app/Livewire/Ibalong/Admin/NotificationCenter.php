<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IbalongNotification;
use App\Models\IbalongRegistration;
use App\Models\IbalongUser;

class NotificationCenter extends Component
{
    use WithPagination;

    public $target = 'all'; // 'all' or 'team_{id}'
    public $message = '';
    public $link = '';
    public $type = 'announcement'; // announcement, warning, success

    public function dispatchNotification()
    {
        // Security: Ensure only admins can dispatch
        if (!in_array(auth('ibalong')->user()->role_id, [1, 2])) abort(403);

        $this->validate([
            'message' => 'required|string|max:255',
            'link' => 'nullable|url',
        ]);

        $notifications = [];
        $now = now();

        if ($this->target === 'all') {
            $users = IbalongUser::pluck('id');
            foreach ($users as $id) {
                $notifications[] = [
                    'user_id' => $id, 'type' => $this->type, 'message' => $this->message,
                    'link' => $this->link ?: null, 'is_read' => false,
                    'created_at' => $now, 'updated_at' => $now
                ];
            }
        } elseif (str_starts_with($this->target, 'team_')) {
            $teamId = str_replace('team_', '', $this->target);
            $team = IbalongRegistration::find($teamId);
            if ($team && $team->user_id) {
                $notifications[] = [
                    'user_id' => $team->user_id, 'type' => $this->type, 'message' => $this->message,
                    'link' => $this->link ?: null, 'is_read' => false,
                    'created_at' => $now, 'updated_at' => $now
                ];
            }
        }

        IbalongNotification::insert($notifications);
        $this->reset(['message', 'link', 'target', 'type']);
        session()->flash('success', 'System Alert Dispatched Successfully.');
    }

    public function render()
    {
        $teams = IbalongRegistration::orderBy('team_name')->get();
        $recentAlerts = IbalongNotification::latest()->take(10)->get()->unique('message');

        return view('livewire.ibalong.admin.notification-center', compact('teams', 'recentAlerts'))
            ->layout('layouts.dashboard');
    }
}