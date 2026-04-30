<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\ElectionParty; // Make sure this is imported!
use App\Models\AcademicYear;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\VoterLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ElectionEditor extends Component
{
    use WithFileUploads;

    public ?Election $electionRecord = null;
    public $activeTab = 'details'; // details, timeline, positions, parties

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

    // Dynamic Arrays
    public $positions = [];
    public $electionParties = []; // Holds our Parties & Slates

    // Wipe Out State
    public $showWipeModal = false;
    public $adminPassword = '';

    public function mount(?Election $election = null)
    {
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

            // Load Existing Positions
            foreach ($election->positions()->orderBy('order')->get() as $pos) {
                $this->positions[] = [
                    'temp_id' => (string) Str::uuid(),
                    'id' => $pos->id,
                    'title' => $pos->title,
                    'max_winners' => $pos->max_winners
                ];
            }

            // Load Existing Parties
            foreach ($election->parties as $party) {
                $this->electionParties[] = [
                    'id' => $party->id,
                    'name' => $party->name,
                    'color' => $party->color,
                    'existing_logo' => $party->logo_path,
                    'new_logo' => null,
                ];
            }
        } else {
            $this->addPosition();
        }
    }

    // --- POSITIONS LOGIC ---
    public function addPosition()
    {
        $this->positions[] = ['temp_id' => (string) Str::uuid(), 'title' => '', 'max_winners' => 1];
    }

    public function removePosition($index)
    {
        if (count($this->positions) > 1) {
            unset($this->positions[$index]);
            $this->positions = array_values($this->positions);
        }
    }

    // --- PARTIES & SLATES LOGIC ---
    public function addParty()
    {
        $this->electionParties[] = [
            'id' => null, 
            'name' => '', 
            'color' => '#3b82f6', // Default starting color (Blue)
            'existing_logo' => null,
            'new_logo' => null,
        ];
    }

    public function removeParty($index)
    {
        unset($this->electionParties[$index]);
        $this->electionParties = array_values($this->electionParties);
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

        $this->validate(['adminPassword' => 'required|string']);

        if (!Hash::check($this->adminPassword, auth()->user()->password)) {
            $this->addError('adminPassword', 'Incorrect password. Action aborted.');
            return;
        }

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

    // --- SAVE ALL ELECTION DATA ---
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
            
            // Validate Arrays
            'positions.*.title' => 'required|string|max:255',
            'positions.*.max_winners' => 'required|integer|min:1',
            'electionParties.*.name' => 'required|string|max:255',
            'electionParties.*.color' => 'required|string|size:7', // Hex validation
            'electionParties.*.new_logo' => 'nullable|image|max:2048',
        ], [
            'positions.*.title.required' => 'All positions must have a title.',
            'electionParties.*.name.required' => 'All parties must have a name.',
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

                // For simplicity: delete old positions and recreate
                ElectionPosition::where('election_id', $election->id)->delete();
            } else {
                // Create new
                $activeYear = AcademicYear::where('is_active', true)->first();
                $data['user_id'] = auth()->id();
                $data['academic_year_id'] = $activeYear->id ?? 1;
                $data['slug'] = Str::slug($this->title . '-' . uniqid());
                $election = Election::create($data);
            }

            // 1. Save Positions
            foreach ($this->positions as $index => $pos) {
                ElectionPosition::create([
                    'election_id' => $election->id,
                    'title' => $pos['title'],
                    'max_winners' => $pos['max_winners'],
                    'order' => $index,
                ]);
            }

            // 2. Sync Parties (Preserve IDs so Candidate relations don't break)
            $savedPartyIds = [];
            foreach ($this->electionParties as $partyData) {
                $logoPath = $partyData['existing_logo'];

                // Handle new logo upload
                if (isset($partyData['new_logo']) && $partyData['new_logo']) {
                    if ($logoPath) { Storage::disk('public')->delete($logoPath); }
                    $logoPath = $partyData['new_logo']->store('party-logos', 'public');
                }

                if (isset($partyData['id']) && $partyData['id']) {
                    $party = ElectionParty::find($partyData['id']);
                    if ($party) {
                        $party->update(['name' => $partyData['name'], 'color' => $partyData['color'], 'logo_path' => $logoPath]);
                        $savedPartyIds[] = $party->id;
                    }
                } else {
                    $newParty = ElectionParty::create([
                        'election_id' => $election->id,
                        'name' => $partyData['name'],
                        'color' => $partyData['color'],
                        'logo_path' => $logoPath,
                    ]);
                    $savedPartyIds[] = $newParty->id;
                }
            }

            // 3. Cleanup removed parties and their logos
            $partiesToDelete = ElectionParty::where('election_id', $election->id)->whereNotIn('id', $savedPartyIds)->get();
            foreach ($partiesToDelete as $partyToDelete) {
                if ($partyToDelete->logo_path) { Storage::disk('public')->delete($partyToDelete->logo_path); }
                $partyToDelete->delete();
            }
        });

        session()->flash('success', 'Election, positions, and political parties saved successfully!');
        return redirect()->route('admin.elections.index');
    }

    public function render()
    {
        return view('livewire.admin.election-editor')->layout('layouts.madya-admin-deck');
    }
}