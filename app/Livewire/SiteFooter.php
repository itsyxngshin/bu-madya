<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SiteStat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class SiteFooter extends Component
{
    public $visitorCount = 1;

    public function mount()
    {
        $this->fetchVisitorCount();
    }

    // Optional: If you want to use wire:poll to update live
    public function fetchVisitorCount()
    {
        if (!Session::has('has_visited_site')) {
            SiteStat::where('key', 'visitor_count')->increment('value');
            Session::put('has_visited_site', true);
        }
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
        // For example: Visitor::count() or Cache::get(...)
    }
    public function render()
    {
        return view('livewire.site-footer');
    }
}
