<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\Sdg;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ActivityManager extends Component
{
    use WithFileUploads;

    public $isModalOpen = false;
    public $isEditMode = false;
    public $editingId = null;

    // Form Fields
    public $title, $slug, $lead_organization, $nature_of_activity;
    public $start_date, $end_date, $sdg_id, $description, $status = 'upcoming';

    // File Uploads
    public $photos = [];
    public $existing_photos = [];

    // Relational Tagging
    public $searchQuery = '';
    public $searchResults = [];
    public $selectedFocals = []; // Array of User Arrays
    public $selectedParticipants = []; // Array of User Arrays

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
    }

    public function updatedTitle()
    {
        if (!$this->isEditMode) {
            $this->slug = Str::slug($this->title);
        }
    }

    // Live search for tagging users
    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = User::where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('id', 'like', '%' . $this->searchQuery . '%')
                ->take(5)->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addUserToRole($userId, $role)
    {
        $user = User::find($userId);
        if ($user) {
            if ($role === 'focal' && !collect($this->selectedFocals)->contains('id', $user->id)) {
                $this->selectedFocals[] = $user;
            } elseif ($role === 'participant' && !collect($this->selectedParticipants)->contains('id', $user->id)) {
                $this->selectedParticipants[] = $user;
            }
        }
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function removeUserFromRole($userId, $role)
    {
        if ($role === 'focal') {
            $this->selectedFocals = collect($this->selectedFocals)->reject(fn($u) => $u['id'] == $userId)->values()->toArray();
        } else {
            $this->selectedParticipants = collect($this->selectedParticipants)->reject(fn($u) => $u['id'] == $userId)->values()->toArray();
        }
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['title', 'slug', 'lead_organization', 'nature_of_activity', 'sdg_id', 'description', 'editingId', 'photos', 'existing_photos', 'selectedFocals', 'selectedParticipants']);
        $this->isEditMode = false;
        $this->status = 'upcoming';
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $activity = Activity::with(['focals', 'participants'])->where('user_id', Auth::id())->findOrFail($id);

        $this->editingId = $activity->id;
        $this->title = $activity->title;
        $this->slug = $activity->slug;
        $this->lead_organization = $activity->lead_organization;
        $this->nature_of_activity = $activity->nature_of_activity;
        $this->start_date = $activity->start_date->format('Y-m-d');
        $this->end_date = $activity->end_date ? $activity->end_date->format('Y-m-d') : null;
        $this->sdg_id = $activity->sdg_id;
        $this->description = $activity->description;
        $this->status = $activity->status;

        $this->existing_photos = $activity->highlight_photos ?? [];
        $this->selectedFocals = $activity->focals->toArray();
        $this->selectedParticipants = $activity->participants->toArray();

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function saveActivity()
    {
        $this->slug = Str::slug($this->slug);

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('activities', 'slug')->ignore($this->editingId)],
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'nature_of_activity' => 'required|string|max:255',
            'photos.*' => 'image|max:2048', // Max 2MB per image
        ]);

        // Process Image Uploads
        $photoPaths = $this->existing_photos;
        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $photoPaths[] = $photo->store('activities/highlights', 'public');
            }
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'lead_organization' => $this->lead_organization,
            'nature_of_activity' => $this->nature_of_activity,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'sdg_id' => $this->sdg_id,
            'description' => $this->description,
            'status' => $this->status,
            'highlight_photos' => $photoPaths,
        ];

        if ($this->isEditMode) {
            $activity = Activity::where('user_id', Auth::id())->findOrFail($this->editingId);
            $activity->update($data);
        } else {
            $data['user_id'] = Auth::id();
            $activity = Activity::create($data);
        }

        // SYNC RELATIONSHIPS to the Pivot Table
        $focalSync = collect($this->selectedFocals)->mapWithKeys(fn($u) => [$u['id'] => ['role' => 'focal']])->toArray();
        $participantSync = collect($this->selectedParticipants)->mapWithKeys(fn($u) => [$u['id'] => ['role' => 'participant']])->toArray();

        // Combine them and sync
        $activity->focals()->sync($focalSync + $participantSync);

        session()->flash('success', $this->isEditMode ? 'Activity updated successfully.' : 'Activity logged successfully.');
        $this->isModalOpen = false;
    }

    public function removeExistingPhoto($index)
    {
        unset($this->existing_photos[$index]);
        $this->existing_photos = array_values($this->existing_photos);
    }

    public function deleteActivity($id)
    {
        Activity::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('success', 'Activity removed permanently.');
    }

    public function render()
    {
        $activities = Activity::where('user_id', Auth::id())
                              ->with(['sdg', 'focals', 'participants'])
                              ->orderBy('start_date', 'desc')
                              ->get();

        $sdgs = Sdg::orderBy('goal_number')->get();

        return view('livewire.partner.activity-manager', [
            'activities' => $activities,
            'sdgs' => $sdgs,
        ])->layout('layouts.madya-admin-deck');
    }
}
