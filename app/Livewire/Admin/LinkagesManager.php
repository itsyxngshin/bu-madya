<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LinkageProposal;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class LinkagesManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $viewingProposal = null; // Holds the proposal being viewed in the modal

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    // Opens the modal to read the full message
    public function viewDetails($id)
    {
        $this->viewingProposal = LinkageProposal::find($id);
    }

    public function closeDetails()
    {
        $this->viewingProposal = null;
    }

    // Updates the status (e.g., pending -> reviewed -> accepted)
    public function updateStatus($id, $newStatus)
    {
        $proposal = LinkageProposal::find($id);
        if ($proposal) {
            $proposal->update(['status' => $newStatus]);
            session()->flash('message', 'Proposal status updated to ' . ucfirst($newStatus) . '.');
            
            // Update the modal view if it's currently open
            if ($this->viewingProposal && $this->viewingProposal->id === $id) {
                $this->viewingProposal->status = $newStatus;
            }
        }
    }

    // Securely download the attached PDF/DOC
    public function downloadFile($id)
    {
        $proposal = LinkageProposal::find($id);
        
        if ($proposal && $proposal->file_path && Storage::disk('public')->exists($proposal->file_path)) {
            return Storage::disk('public')->download($proposal->file_path);
        }

        session()->flash('error', 'Attachment file not found on the server.');
    }

    public function delete($id)
    {
        $proposal = LinkageProposal::find($id);
        if ($proposal) {
            if ($proposal->file_path) {
                Storage::disk('public')->delete($proposal->file_path);
            }
            $proposal->delete();
            session()->flash('message', 'Proposal deleted.');
        }
    }

    public function render()
    {
        $proposals = LinkageProposal::query()
            ->when($this->search, function ($q) {
                $q->where('organization_name', 'like', "%{$this->search}%")
                  ->orWhere('title', 'like', "%{$this->search}%")
                  ->orWhere('contact_person', 'like', "%{$this->search}%");
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.linkages-manager', [
            'proposals' => $proposals
        ]);
    }
}