<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\VoterLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ElectionEditor extends Component
{
    use WithFileUploads;

    public Election $election; 
    public $activeTab = 'details';

    // Election Details
    public $title;
    public $slug;
    public $description;
    public $type;
    public $allow_guest_voting;
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

    // Wipe Out State
    public $showWipeModal = false;
    public $adminPassword = '';

    public function mount(Election $election)
    {
        // Security: Ensure only authorized admins can edit
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->election = $election;
            
        $this->title = $election->title;
        $this->slug = $election->slug;
        $this->description = $election->description;
        $this->type = $election->type;
        $this->allow_guest_voting = $election->allow_guest_voting;
        $this->existing_cover_photo = $election->cover_photo_path;

        // Format for HTML datetime-local inputs
        $this->application_start = $election->application_start?->format('Y-m-d\TH:i');
        $this->application_end = $election->application_end?->format('Y-m-d\TH:i');
        $this->voting_start = $election->voting_start?->format('Y-m-d\TH:i');
        $this->voting_end = $election->voting_end?->format('Y-m-d\TH:i');
        $this->results_release = $election->results_release?->format('Y-m-d\TH:i');

        $this->loadPositions();
    }

    public function loadPositions()
    {
        $this->positions = []; 
        foreach ($this->election->positions()->orderBy('order')->get() as $pos) {
            $this->positions[] = [
                'temp_id' => (string) Str::uuid(),
                'id' => $pos->id,
                'title' => $pos->title,
                'max_winners' => $pos->max_winners,
                'candidate_count' => $pos->candidates()->count()
            ];
        }
    }

    public function addPosition()
    {
        $this->positions[] = [
            'temp_id' => (string) Str::uuid(),
            'title' => '',
            'max_winners' => 1,
            'candidate_count' => 0
        ];
    }

    public function removePosition($index)
    {
        if (count($this->positions) > 1) {
            if (isset($this->positions[$index]['candidate_count']) && $this->positions[$index]['candidate_count'] > 0) {
                session()->flash('position_error', 'Cannot remove a position that already has applicants.');
                return;
            }
            unset($this->positions[$index]);
            $this->positions = array_values($this->positions);
        }
    }

    // --- WIPE OUT DATA (FACTORY RESET) ---

    public function confirmWipe()
    {
        $this->showWipeModal = true;
        $this->adminPassword = '';
        $this->resetErrorBag('adminPassword');
    }

    public function executeWipe()
    {
        $this->validate(['adminPassword' => 'required|string']);

        if (!Hash::check($this->adminPassword, auth()->user()->password)) {
            $this->addError('adminPassword', 'Incorrect password. Action aborted.');
            return;
        }

        DB::transaction(function () {
            Vote::where('election_id', $this->election->id)->delete();
            VoterLog::where('election_id', $this->election->id)->delete();
            Candidate::where('election_id', $this->election->id)->delete();
        });

        $this->showWipeModal = false;
        $this->adminPassword = '';
        $this->loadPositions(); // Refresh candidate counts to 0
        $this->activeTab = 'details'; 

        session()->flash('success', 'Factory Reset Complete: All candidates, votes, and logs wiped.');
    }

    // --- SAVE ELECTION ---

    public function saveElection()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:elections,slug,' . $this->election->id,
            'type' => 'required|in:general,special,runoff',
            'cover_photo' => 'nullable|image|max:2048',
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
            $photoPath = $this->cover_photo ? $this->cover_photo->store('elections/covers', 'public') : $this->existing_cover_photo;

            $this->election->update([
                'title' => $this->title,
                'slug' => Str::slug($this->slug),
                'description' => $this->description,
                'cover_photo_path' => $photoPath,
                'type' => $this->type,
                'allow_guest_voting' => $this->allow_guest_voting,
                'application_start' => $this->application_start,
                'application_end' => $this->application_end,
                'voting_start' => $this->voting_start,
                'voting_end' => $this->voting_end,
                'results_release' => $this->results_release ?: null,
            ]);

            // Sync Positions Safely
            $savedPositionIds = [];
            foreach ($this->positions as $index => $pos) {
                if (isset($pos['id'])) {
                    $position = ElectionPosition::find($pos['id']);
                    $position->update(['title' => $pos['title'], 'max_winners' => $pos['max_winners'], 'order' => $index]);
                    $savedPositionIds[] = $position->id;
                } else {
                    $newPos = ElectionPosition::create([
                        'election_id' => $this->election->id,
                        'title' => $pos['title'], 'max_winners' => $pos['max_winners'], 'order' => $index
                    ]);
                    $savedPositionIds[] = $newPos->id;
                }
            }

            ElectionPosition::where('election_id', $this->election->id)->whereNotIn('id', $savedPositionIds)->delete();
        });

        session()->flash('success', 'Election saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.election-editor')->layout('layouts.madya-admin-deck');
    }
}