<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ElectionManager extends Component
{
    use WithFileUploads;

    public $activeTab = 'details'; // details, timeline, positions

    // Election Details
    public $title = '';
    public $description = '';
    public $type = 'general';
    public $allow_guest_voting = false;
    public $cover_photo;

    // Timeline
    public $application_start;
    public $application_end;
    public $voting_start;
    public $voting_end;
    public $results_release;

    // Dynamic Positions
    public $positions = [];

    public function mount()
    {
        // Start with one empty position
        $this->addPosition();
    }

    public function addPosition()
    {
        $this->positions[] = [
            'temp_id' => (string) Str::uuid(),
            'title' => '',
            'max_winners' => 1
        ];
    }

    public function removePosition($index)
    {
        if (count($this->positions) > 1) {
            unset($this->positions[$index]);
            $this->positions = array_values($this->positions);
        }
    }

    public function saveElection()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:general,special,runoff',
            'cover_photo' => 'nullable|image',
            'slug' => Str::slug($this->title . '-' . uniqid()),
            'application_start' => 'required|date',
            'application_end' => 'required|date|after:application_start',
            'voting_start' => 'required|date|after:application_end',
            'voting_end' => 'required|date|after:voting_start',
            'positions.*.title' => 'required|string|max:255',
            'positions.*.max_winners' => 'required|integer|min:1',
        ], [
            'positions.*.title.required' => 'All positions must have a title.',
        ]);

        DB::transaction(function () {
            $photoPath = $this->cover_photo ? $this->cover_photo->store('elections/covers', 'public') : null;
            $activeYear = AcademicYear::where('is_active', true)->first();

            // 1. Create the Master Election Entity
            $election = Election::create([
                'user_id' => auth()->id(), // The Organization Owner
                'academic_year_id' => $activeYear->id ?? 1,
                'title' => $this->title,
                'description' => $this->description,
                'cover_photo_path' => $photoPath,
                'type' => $this->type,
                'status' => 'active', // Set to active immediately for this example
                'allow_guest_voting' => $this->allow_guest_voting,
                'application_start' => $this->application_start,
                'application_end' => $this->application_end,
                'voting_start' => $this->voting_start,
                'voting_end' => $this->voting_end,
                'results_release' => $this->results_release,
            ]);

            // 2. Create the Custom Positions
            foreach ($this->positions as $index => $pos) {
                ElectionPosition::create([
                    'election_id' => $election->id,
                    'title' => $pos['title'],
                    'max_winners' => $pos['max_winners'],
                    'order' => $index,
                ]);
            }
        });

        session()->flash('success', 'Election entity created successfully!');
        return redirect()->route('admin.elections.index'); // Adjust route as needed
    }

    public function render()
    {
        return view('livewire.admin.election-manager')->layout('layouts.madya-admin-deck');
    }
}