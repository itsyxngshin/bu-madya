<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Campaign;

class CampaignBuilder extends Component
{
    use WithFileUploads;

    public Campaign $campaign;

    // Form Properties
    public $title = '';
    public $slug = '';
    public $description = '';
    public $target_signatures = 1000;
    public $status = 'draft';
    public $cover_image;
    public $existing_cover_image;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('campaigns', 'slug')->ignore($this->campaign->id)],
            'description' => 'required|string',
            'target_signatures' => 'required|integer|min:1',
            'status' => 'required|in:draft,active,closed,victorious',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072', // 3MB Max
        ];
    }

    public function mount($campaign = null)
    {
        // 1. Bulletproof Database Fetching (Handles ID, Slug, or Model)
        if (is_string($campaign) || is_numeric($campaign)) {
            $this->campaign = Campaign::where('slug', $campaign)->orWhere('id', $campaign)->firstOrFail();
        } elseif ($campaign instanceof Campaign) {
            $this->campaign = $campaign;
        } else {
            $this->campaign = new Campaign();
        }

        // 2. Role Check
        $user = auth()->user();
        $role = $user->role?->role_name;

        if (!in_array($role, ['administrator', 'director', 'organization'])) {
             abort(403, 'You do not have permission to build campaigns.');
        }

        // 3. Ownership Security Check (if editing)
        if ($this->campaign->exists && $role !== 'administrator' && $this->campaign->created_by !== $user->id) {
            abort(403, 'You do not have permission to edit this campaign.');
        }

        // 4. Populate the form with existing data!
        if ($this->campaign->exists) {
            $this->title = $this->campaign->title;
            $this->slug = $this->campaign->slug;
            $this->description = $this->campaign->description;
            $this->target_signatures = $this->campaign->target_signatures;
            $this->status = $this->campaign->status;
            $this->existing_cover_image = $this->campaign->cover_image;
        }
    }

    public function generateRandomSlug()
    {
        $this->slug = Str::random(16);
    }

    public function save()
    {
        $this->validate();

        // Handle Image Upload
        if ($this->cover_image) {
            $this->campaign->cover_image = $this->cover_image->store('campaign-covers', 'public');
        }

        // Save Data
        $this->campaign->title = $this->title;
        $this->campaign->slug = !empty($this->slug) ? Str::slug($this->slug) : Str::slug($this->title);
        $this->campaign->description = $this->description;
        $this->campaign->target_signatures = $this->target_signatures;
        $this->campaign->status = $this->status;
        
        if (!$this->campaign->exists) {
            $this->campaign->created_by = auth()->id();
        }

        $this->campaign->save();

        session()->flash('success', 'Campaign saved successfully!');

        // Redirect back to the correct dashboard based on role
        $roleName = auth()->user()->role?->role_name ?? 'guest';
        $routePrefix = match($roleName) {
            'organization'  => 'partner.campaigns',
            'director'      => 'director.campaigns',
            default         => 'admin.campaigns',
        };
        
        return redirect()->route($routePrefix . '.index');
    }

    public function render()
    {
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.campaign-builder')->layout($layoutFile);
    }
}