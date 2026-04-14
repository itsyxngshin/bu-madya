<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Campaign;
use Illuminate\Support\Str;

class CampaignList extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
        $role = auth()->user()->role?->role_name;
        if (!in_array($role, ['administrator', 'director', 'organization'])) {
            abort(403, 'You do not have permission to access the Campaign Manager.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Cycles through the campaign lifecycle
    public function toggleStatus($id)
    {
        $campaign = Campaign::findOrFail($id);

        // Security check
        abort_if(
            auth()->user()->role?->role_name !== 'administrator' && $campaign->created_by !== auth()->id(), 
            403, 
            'Unauthorized Action.'
        );

        if ($campaign->status === 'draft') {
            $campaign->status = 'active';
            session()->flash('success', 'Campaign is now live!');
        } elseif ($campaign->status === 'active') {
            $campaign->status = 'closed';
            session()->flash('success', 'Campaign has been closed.');
        } else {
            $campaign->status = 'draft';
            session()->flash('success', 'Campaign reverted to draft.');
        }

        $campaign->save();
    }

    // The Celebration Trigger!
    public function markVictorious($id)
    {
        $campaign = Campaign::findOrFail($id);

        abort_if(
            auth()->user()->role?->role_name !== 'administrator' && $campaign->created_by !== auth()->id(), 
            403, 
            'Unauthorized Action.'
        );

        $campaign->status = 'victorious';
        $campaign->save();

        session()->flash('success', 'Victory declared! The public page will now show the success banner.');
    }

    // Generates the CSV for the Dean/President
    public function exportSignatures($id)
    {
        $campaign = Campaign::with('signatures.user')->findOrFail($id);

        abort_if(
            auth()->user()->role?->role_name !== 'administrator' && $campaign->created_by !== auth()->id(), 
            403, 
            'Unauthorized Action.'
        );
        
        $csvData = "Name,Email,Date Signed\n";
        foreach($campaign->signatures as $sig) {
            // Check if user exists to prevent errors if a user account was deleted
            if($sig->user) {
                $date = $sig->created_at->format('Y-m-d H:i:s');
                $csvData .= "\"{$sig->user->name}\",\"{$sig->user->email}\",\"{$date}\"\n";
            }
        }

        return response()->streamDownload(function () use ($csvData) {
            echo $csvData;
        }, "{$campaign->slug}-signatures.csv");
    }

    public function delete($id)
    {
        $campaign = Campaign::findOrFail($id);

        abort_if(
            auth()->user()->role?->role_name !== 'administrator' && $campaign->created_by !== auth()->id(), 
            403, 
            'Unauthorized Action.'
        );

        $campaign->delete();
        session()->flash('success', 'Campaign deleted successfully.');
    }

    public function render()
    {
        $query = Campaign::with('creator')->withCount('signatures')->latest();

        // Standard organizations only see their own campaigns. Admins see all.
        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where('created_by', auth()->id());
        }

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $campaigns = $query->paginate(9);

        // Uses your existing layout logic
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.campaign-list', compact('campaigns'))->layout($layoutFile);
    }
}