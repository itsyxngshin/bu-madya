<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class ProjectRoster extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // 'Upcoming', 'Ongoing', 'Completed'

    // Reset pagination when filters change
    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        if ($project) {
            $project->delete();
            session()->flash('message', 'Project successfully deleted.');
        }
    }

    public function render()
    {
        $projects = Project::query()
            ->with(['category', 'proponents']) // Eager load for performance
            ->when($this->search, function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.project-roster', [
            'projects' => $projects
        ]);
    }
}