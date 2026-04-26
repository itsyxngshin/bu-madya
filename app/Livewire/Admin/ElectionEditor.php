<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\ElectionPosition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ElectionEditor extends Component
{
    use WithFileUploads;

    public Election $election;
    public $activeTab = 'details';

    // Election Details
    public $title = '';
    public $slug = '';
    public $description = '';
    public $type = 'general';
    public $allow_guest_voting = false;
    public $cover_photo;
    public $existing_cover_photo;

    // Timeline
    public $application_start;
    public $application_end;
    public $voting_start;
    public $voting_end;
    public $results_release;

    // Dynamic Positions
    public $positions = [];
    public $positionsToDelete = []; 

    public function mount(Election $election)
    {
        // Security: Ensure the user actually owns this election or is a super-admin
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this election entity.');
        }

        $this->election = $election;

        // Populate Basic Details
        $this->title = $election->title;
        $this->slug = $election->slug;
        $this->description = $election->description;
        $this->type = $election->type;
        $this->allow_guest_voting = $election->allow_guest_voting;
        $this->existing_cover_photo = $election->cover_photo_path;

        // Populate Timeline (Must format to Y-m-d\TH:i for HTML datetime-local inputs)
        $this->application_start = $election->application_start?->format('Y-m-d\TH:i');
        $this->application_end = $election->application_end?->format('Y-m-d\TH:i');
        $this->voting_start = $election->voting_start?->format('Y-m-d\TH:i');
        $this->voting_end = $election->voting_end?->format('Y-m-d\TH:i');
        $this->results_release = $election->results_release?->format('Y-m-d\TH:i');

        // Populate Positions
        foreach ($election->positions as $pos) {
            $this->positions[] = [
                'id' => $pos->id,
                'temp_id' => null,
                'title' => $pos->title,
                'max_winners' => $pos->max_winners,
                'candidate_count' => $pos->candidates()->count(), // Safety Lock Check
            ];
        }
    }

    public function addPosition()
    {
        $this->positions[] = [
            'id' => null,
            'temp_id' => (string) Str::uuid(),
            'title' => '',
            'max_winners' => 1,
            'candidate_count' => 0,
        ];
    }

    public function removePosition($index)
    {
        // SAFETY LOCK: Don't let them delete a position with active candidates
        if (isset($this->positions[$index]['candidate_count']) && $this->positions[$index]['candidate_count'] > 0) {
            session()->flash('position_error', 'Cannot delete this position. Candidates have already applied for it.');
            return;
        }

        if (!empty($this->positions[$index]['id'])) {
            $this->positionsToDelete[] = $this->positions[$index]['id'];
        }

        unset($this->positions[$index]);
        $this->positions = array_values($this->positions);
    }

    public function saveElection()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|alpha_dash|max:255|unique:elections,slug,' . $this->election->id,
            'type' => 'required|in:general,special,runoff',
            'cover_photo' => 'nullable|image|max:2048',
            'application_start' => 'nullable|date',
            'application_end' => 'nullable|date|after:application_start',
            'voting_start' => 'nullable|date|after:application_end',
            'voting_end' => 'nullable|date|after:voting_start',
            'positions.*.title' => 'required|string|max:255',
            'positions.*.max_winners' => 'required|integer|min:1',
        ], [
            'positions.*.title.required' => 'All positions must have a title.',
        ]);

        DB::transaction(function () {
            // 1. Handle Photo Upload & Cleanup
            if ($this->cover_photo) {
                if ($this->existing_cover_photo) {
                    Storage::disk('public')->delete($this->existing_cover_photo);
                }
                $this->election->cover_photo_path = $this->cover_photo->store('elections/covers', 'public');
            }

            // 2. Update Election Details
            $this->election->update([
                'title' => $this->title,
                'slug' => Str::slug($this->slug), // Force sanitization just in case
                'description' => $this->description,
                'type' => $this->type,
                'allow_guest_voting' => $this->allow_guest_voting,
                'application_start' => $this->application_start,
                'application_end' => $this->application_end,
                'voting_start' => $this->voting_start,
                'voting_end' => $this->voting_end,
                'results_release' => $this->results_release,
            ]);

            // 3. Delete Removed Positions
            if (!empty($this->positionsToDelete)) {
                ElectionPosition::whereIn('id', $this->positionsToDelete)->delete();
            }

            // 4. Update or Create Positions
            foreach ($this->positions as $index => $pos) {
                ElectionPosition::updateOrCreate(
                    ['id' => $pos['id'] ?? null],
                    [
                        'election_id' => $this->election->id,
                        'title' => $pos['title'],
                        'max_winners' => $pos['max_winners'],
                        'order' => $index, 
                    ]
                );
            }
        });

        session()->flash('success', 'Election settings updated successfully!');
        return redirect()->route('admin.elections.index'); 
    }

    public function render()
    {
        return view('livewire.admin.election-editor')->layout('layouts.madya-admin-deck');
    }
}