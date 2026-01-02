<?php

namespace App\Livewire\Open\News;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $visitorCount = 1;
    public function mount()
    {
        // 1. Check if this specific user has already been counted in this session
        if (!Session::has('has_visited_site')) {
            
            // 2. Increment the database value securely
            SiteStat::where('key', 'visitor_count')->increment('value');
            
            // 3. Mark this user as counted for this browser session
            Session::put('has_visited_site', true);
        }

        // 4. Retrieve the current total (cache it briefly to reduce DB queries on high traffic)
        // We remember it for 10 minutes, or fetch directly if you want instant real-time
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    // Reset pagination when filters change
    public function updatedSearch() { $this->resetPage(); }
    public function setCategory($cat) 
    { 
        $this->category = ($this->category === $cat) ? '' : $cat; // Toggle logic
        $this->resetPage();
    }

    public function render()
    {
        // 1. Base Query
        $query = News::with(['category', 'votes']) // Eager load votes for the count
            ->withCount('votes') // Get the actual count
            ->latest('published_at');

        // 2. Role-Based Status Filter
        // If user is a Director (adjust logic to match your specific Role check)
        if (in_array(Auth::user()?->role?->role_name, ['director', 'administrator'])){ 
            // Directors see Active, Draft, and For Evaluation
            $query->whereIn('status', ['active', 'draft', 'for evaluation']);
        } 
        else {
            // Everyone else (Guests & Regular Users) only sees Active
            $query->where('status', 'active');
        }

        // 3. Search Filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('summary', 'like', '%' . $this->search . '%');
            });
        }

        // 4. Category Filter
        if ($this->category) {
            $query->whereHas('category', function($q) {
                $q->where('name', $this->category);
            });
        }

        // 5. Get Featured (Most Voted Active Article)
        // Only fetch a featured article that is actually visible to the public
        $featured = News::withCount('votes')
            ->where('status', 'active') 
            ->orderBy('votes_count', 'desc')
            ->first();

        return view('livewire.open.news.index', [
            'news' => $query->paginate(9),
            'featured' => $featured,
            'categories' => NewsCategory::pluck('name'),
        ]);
    }
}