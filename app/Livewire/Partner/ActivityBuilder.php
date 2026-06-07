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

class ActivityBuilder extends Component
{
    use WithFileUploads;

    public $activityId = null;
    public $isEditMode = false;

    // Form Fields
    public $title, $slug, $lead_organization, $nature_of_activity;
    public $start_date, $end_date, $sdg_id, $description, $status = 'upcoming';

    // File Uploads
    public $photos = [];
    public $existing_photos = [];

    // Relational Tagging
    public $searchQuery = '';
    public $searchResults = [];
    public $selectedFocals = [];
    public $selectedParticipants = [];

    public function mount($slug = null)
    {
        if ($slug) {
            $activity = Activity::with(['focals', 'participants'])
                ->where('slug', $slug)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $this->activityId = $activity->id;
            $this->isEditMode = true;

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
        } else {
            $this->start_date = date('Y-m-d');
            $this->end_date = date('Y-m-d');
        }
    }

    private function getManagerRoute()
    {
        $role = auth()->user()->role?->role_name ?? 'guest';
        
        return match($role) {
            'administrator' => 'admin.activities.manage',
            'organization'  => 'partner.activities.manage',
            'director'      => 'director.activities.manage',
            default         => 'dashboard',
        };
    }

    public function updatedTitle()
    {
        if (!$this->isEditMode) {
            $this->slug = Str::slug($this->title);
        }
    }

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
                $this->selectedFocals[] = $user->toArray();
            } elseif ($role === 'participant' && !collect($this->selectedParticipants)->contains('id', $user->id)) {
                $this->selectedParticipants[] = $user->toArray();
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

    public function removeExistingPhoto($index)
    {
        unset($this->existing_photos[$index]);
        $this->existing_photos = array_values($this->existing_photos);
    }

    public function removeUploadedPhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function saveActivity()
    {
        $this->slug = Str::slug($this->slug);

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('activities', 'slug')->ignore($this->activityId)],
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'nature_of_activity' => 'required|string|max:255',
            'photos.*' => 'image|max:2048',
        ]);

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
            $activity = Activity::where('user_id', Auth::id())->findOrFail($this->activityId);
            $activity->update($data);
        } else {
            $data['user_id'] = Auth::id();
            $activity = Activity::create($data);
        }

        $focalSync = collect($this->selectedFocals)->mapWithKeys(fn($u) => [$u['id'] => ['role' => 'focal']])->toArray();
        $participantSync = collect($this->selectedParticipants)->mapWithKeys(fn($u) => [$u['id'] => ['role' => 'participant']])->toArray();
        $activity->focals()->sync($focalSync + $participantSync);

        session()->flash('success', $this->isEditMode ? 'Activity updated successfully.' : 'Activity published successfully.');
        return redirect()->route('activities.manage');
    }

    public function render()
    {
        return view('livewire.partner.activity-builder', [
            'sdgs' => Sdg::orderBy('number')->get(),
        ])->layout('layouts.madya-template'); // Using guest layout since the builder has its own full-screen navbar
    }
}
