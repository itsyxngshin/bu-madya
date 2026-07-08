<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use App\Models\SiteStat;
use Illuminate\Support\Facades\Session;
use App\Models\IbalongPartner; // Make sure to import this for your partners loop!

class Footer extends Component
{
    public $visitorCount = 0;

    public function mount()
    {
        // 1. Ensure the row exists in the database
        $stat = SiteStat::firstOrCreate(
            ['key' => 'visitor_count'], 
            ['value' => 0]
        );

        // 2. Apply your tracking logic
        if (!Session::has('has_visited_site')) {
            $stat->increment('value');
            Session::put('has_visited_site', true);
        }

        // 3. Retrieve the final count
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    public function render()
    {
        return view('livewire.ibalong.footer', [
            // Fetch partners here so the view has access to them
            'partners' => IbalongPartner::where('is_active', true)
                            ->orderBy('display_order', 'asc')
                            ->get()
        ]);
    }
}