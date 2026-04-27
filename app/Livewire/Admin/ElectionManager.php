<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\AcademicYear;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\VoterLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ElectionManager extends Component
{
    use WithFileUploads;

    public ?Election $electionRecord = null; // Holds the election if editing
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

    // Wipe Out State
    public $showWipeModal = false;
    public $adminPassword = '';

    public function mount(?Election $election = null)
    {
        // If we are editing an existing election, populate the fields
        if ($election && $election->exists) {
            $this->electionRecord = $election;
            $this->title = $election->title;
            $this->description = $election->description;
            $this->type = $election->type;
            $this->allow_guest_voting = $election->allow_guest_voting;

            // Format for HTML datetime-local inputs
            $this->application_start = $election->application_start?->format('Y-m-d\TH:i');
            $this->application_end = $election->application_end?->format('Y-m-d\TH:i');
            $this->voting_start = $election->voting_start?->format('Y-m-d\TH:i');
            $this->voting_end = $election->voting_end?->format('Y-m-d\TH:i');
            $this->results_release = $election->results_release?->format('Y-m-d\TH:i');

            foreach ($election->positions()->orderBy('order')->get() as $pos) {
                $this->positions[] = [
                    'temp_id' => (string) Str::uuid(),
                    'id' => $pos->id,
                    'title' => $pos->title,
                    'max_winners' => $pos->max_winners
                ];
            }
        } else {
            $this->addPosition();
        }
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

    // --- WIPE OUT DATA (DANGER ZONE) ---

    public function confirmWipe()
    {
        $this->showWipeModal = true;
        $this->adminPassword = '';
        $this->resetErrorBag('adminPassword');
    }

    public function executeWipe()
    {
        if (!$this->electionRecord) return;

        $this->validate([
            'adminPassword' => 'required|string'
        ]);

        // Verify the currently logged-in admin's password
        if (!Hash::check($this->adminPassword, auth()->user()->password)) {
            $this->addError('adminPassword', 'Incorrect password. Action aborted.');
            return;
        }

        // Wipe the data cleanly in a transaction
        DB::transaction(function () {
            Vote::where('election_id', $this->electionRecord->id)->delete();
            VoterLog::where('election_id', $this->electionRecord->id)->delete();
            Candidate::where('election_id', $this->electionRecord->id)->delete();
        });

        $this->showWipeModal = false;
        $this->adminPassword = '';

        session()->flash('success', 'Factory Reset Complete: All candidates, votes, and logs have been permanently wiped.');
        return redirect()->route('admin.elections.edit', $this->electionRecord->slug);
    }

    public function saveElection()
    {
        $this->validate([
            'title' => 'required|string|max:255',
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
            $photoPath = $this->cover_photo ? $this->cover_photo->store('elections/covers', 'public') : ($this->electionRecord->cover_photo_path ?? null);

            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'cover_photo_path' => $photoPath,
                'type' => $this->type,
                'status' => 'active',
                'allow_guest_voting' => $this->allow_guest_voting,
                'application_start' => $this->application_start,
                'application_end' => $this->application_end,
                'voting_start' => $this->voting_start,
                'voting_end' => $this->voting_end,
                'results_release' => $this->results_release,
            ];

            if ($this->electionRecord) {
                // Update existing
                $this->electionRecord->update($data);
                $election = $this->electionRecord;

                // For simplicity on positions: delete old and recreate
                ElectionPosition::where('election_id', $election->id)->delete();
            } else {
                // Create new
                $activeYear = AcademicYear::where('is_active', true)->first();
                $data['user_id'] = auth()->id();
                $data['academic_year_id'] = $activeYear->id ?? 1;
                $data['slug'] = Str::slug($this->title . '-' . uniqid());
                $election = Election::create($data);
            }

            foreach ($this->positions as $index => $pos) {
                ElectionPosition::create([
                    'election_id' => $election->id,
                    'title' => $pos['title'],
                    'max_winners' => $pos['max_winners'],
                    'order' => $index,
                ]);
            }
        });

        session()->flash('success', 'Election saved successfully!');
        return redirect()->route('admin.elections.index');
    }

    public function render()
    {
        return view('livewire.admin.election-manager')->layout('layouts.madya-admin-deck');
    }
}
