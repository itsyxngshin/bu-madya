<?php

namespace App\Livewire\Ibalong\Components;

use Livewire\Component;
use App\Models\IbalongNotification;

class NotificationBell extends Component
{
    public function markAsRead()
    {
        IbalongNotification::where('user_id', auth('ibalong')->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $notifications = IbalongNotification::where('user_id', auth('ibalong')->id())
            ->latest()
            ->take(10)
            ->get();
            
        $unreadCount = $notifications->where('is_read', false)->count();

        return view('livewire.ibalong.components.notification-bell', compact('notifications', 'unreadCount'));
    }
}