<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\WithFileUploads; // Crucial for photo uploads
use Illuminate\Support\Facades\Auth;
use App\Models\Engagement;
use App\Models\Portfolio;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class EditProfile extends Component
{
    use WithFileUploads;

    public $user;
    public $profile;
    
    // Form Fields
    public $photo;
    public $bio;
    public $course;
    public $year_level;

    // Dynamic Lists
    public $engagements = [];
    public $portfolios = [];

    public function mount()
    {
        $this->user = Auth::user();
        
        // Ensure profile exists
        if (!$this->user->profile) {
            $this->user->profile()->create([
                'first_name' => explode(' ', $this->user->name)[0],
                'last_name' => '', 
            ]);
            $this->user->refresh();
        }

        $this->profile = $this->user->profile;

        // Load Basic Info
        $this->bio = $this->profile->bio;
        $this->course = $this->profile->course;
        $this->year_level = $this->profile->year_level;

        // Load Engagements
        $this->engagements = $this->user->engagements->toArray();
        
        // Load Portfolios
        $this->portfolios = $this->profile->portfolios->map(function($p) {
            return [
                'id' => $p->id,
                'designation' => $p->designation,
                'place' => $p->place,
                'duration' => $p->duration,
                'status' => $p->status,
                'description' => $p->description,
            ];
        })->toArray();
    }

    public function saveBasic()
    {
        $this->validate([
            'bio' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($this->photo) {
            $path = $this->photo->store('profile-photos', 'public');
            $this->user->update(['profile_photo_path' => $path]);
        }

        $this->profile->update([
            'bio' => $this->bio,
            'course' => $this->course,
            'year_level' => $this->year_level,
        ]);

        session()->flash('message', 'Profile updated successfully!');
    }

    // --- ENGAGEMENTS LOGIC ---
    public function addEngagement()
    {
        $this->engagements[] = ['title' => '', 'description' => '', 'id' => null];
    }

    public function removeEngagement($index)
    {
        $item = $this->engagements[$index];
        if (isset($item['id'])) {
            Engagement::find($item['id'])->delete();
        }
        unset($this->engagements[$index]);
        $this->engagements = array_values($this->engagements);
    }

    public function saveEngagements()
    {
        foreach ($this->engagements as $data) {
            if (empty($data['title'])) continue;

            Engagement::updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'user_id' => $this->user->id,
                    'title' => $data['title'],
                    'description' => $data['description']
                ]
            );
        }
        session()->flash('engagement_message', 'Engagements saved!');
    }

    // --- PORTFOLIOS LOGIC [NEW] ---
    public function addPortfolio()
    {
        $this->portfolios[] = [
            'id' => null,
            'designation' => '',
            'place' => '',
            'duration' => '',
            'status' => 'Active',
            'description' => ''
        ];
    }

    public function removePortfolio($index)
    {
        $item = $this->portfolios[$index];
    
        if (!empty($item['id'])) {
            // 1. Remove the row from 'portfolio_sets'
            $this->profile->portfolios()->detach($item['id']);
            
            // 2. Remove the row from 'portfolios'
            Portfolio::find($item['id'])?->delete();
        }
        
        unset($this->portfolios[$index]);
        $this->portfolios = array_values($this->portfolios);
    }

    public function savePortfolios()
    {
        foreach ($this->portfolios as $data) {
            if (empty($data['designation'])) continue;

            // --- STEP 1: The 'portfolios' Table ---
            // This creates or updates the actual data (Designation, Place, etc.)
            // It does NOT know about the user yet.
            $portfolio = Portfolio::updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'designation' => $data['designation'],
                    'place' => $data['place'],
                    'duration' => $data['duration'],
                    'status' => $data['status'],
                    'description' => $data['description'],
                ]
            );

            // --- STEP 2: The 'portfolio_sets' Table ---
            // This is the specific line that writes to your PIVOT table.
            // It says: "Take this Profile, look at its 'portfolios' relationship, 
            // and ensure this Portfolio ID is linked in 'portfolio_sets'."
            $this->profile->portfolios()->syncWithoutDetaching($portfolio->id);
        }

        session()->flash('portfolio_message', 'Portfolios updated!');
    }

    public function render()
    {
        return view('livewire.director.edit-profile');
    }
}