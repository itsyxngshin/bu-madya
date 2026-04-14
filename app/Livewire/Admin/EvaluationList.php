<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;

class EvaluationList extends Component
{
    use WithPagination;

    public $search = '';
    public $sharingEvaluation = null;
    public $shareSearch = '';

    public function mount()
    {
        $role = auth()->user()->role?->role_name;
        if (!in_array($role, ['administrator', 'director', 'organization'])) {
            abort(403, 'You do not have permission to access the Evaluation Manager.');
        }
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $evaluation = Evaluation::find($id);
        $evaluation->is_active = !$evaluation->is_active;
        $evaluation->save();

        session()->flash('success', $evaluation->is_active ? 'Form published!' : 'Form unpublished.');
    }

    // --- SECURE DUPLICATE METHOD ---
    public function duplicate($id)
    {
        // 1. Eager load questions and collaborators
        $original = Evaluation::with(['questions', 'collaborators'])->find($id);

        if (!$original) return;

        // 2. BACKEND SECURITY CHECK: Admin, Owner, OR Collaborator
        $isOwner = $original->created_by === auth()->id();
        $isAdmin = auth()->user()->role?->role_name === 'administrator';
        $isCollaborator = $original->collaborators->contains('id', auth()->id());

        abort_if(
            !$isAdmin && !$isOwner && !$isCollaborator,
            403,
            'Unauthorized Action. You must be an owner or collaborator to duplicate this form.'
        );

        // 3. Clone the evaluation
        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->is_active = false;

        // ✨ THE MAGIC LINE: Force the new owner to be the person duplicating it
        $duplicate->created_by = auth()->id();

        if (isset($original->slug)) {
            $duplicate->slug = Str::slug($duplicate->title) . '-' . strtolower(Str::random(5));
        }

        $duplicate->save();

        // 4. Copy questions and map old IDs to new IDs
        $idMap = [];
        foreach ($original->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->evaluation_id = $duplicate->id;
            $newQuestion->save();
            $idMap[$question->id] = $newQuestion->id;
        }

        // 5. Scan the new duplicate's questions and remap the skip-logic pointers
        foreach ($duplicate->questions as $newQ) {
            if (in_array($newQ->type, ['radio', 'dropdown', 'page_break']) && is_array($newQ->options)) {
                $updatedOptions = [];
                $modified = false;

                foreach ($newQ->options as $opt) {
                    if (isset($opt['jump']) && is_numeric($opt['jump']) && isset($idMap[$opt['jump']])) {
                        // Point the jump logic to the newly created question ID
                        $opt['jump'] = $idMap[$opt['jump']];
                        $modified = true;
                    }
                    $updatedOptions[] = $opt;
                }

                // Only hit the database if we actually changed something
                if ($modified) {
                    $newQ->options = $updatedOptions;
                    $newQ->save();
                }
            }
        }

        session()->flash('success', 'Form duplicated successfully! You are the owner of this new copy.');
    }

    public function delete($id)
    {
        $evaluation = Evaluation::findOrFail($id);

        // BACKEND SECURITY CHECK: Block if they are not an Admin AND not the Owner
        abort_if(
            auth()->user()->role?->role_name !== 'administrator' && $evaluation->created_by !== auth()->id(),
            403,
            'Unauthorized Action. Only the form owner or an administrator can delete this evaluation.'
        );

        $evaluation->delete();
        session()->flash('success', 'Evaluation form deleted.');
    }

    // --- SECURE SHARE MODAL (Fixes 404 Error) ---
    public function openShareModal($id)
    {
        $evaluation = Evaluation::with(['creator', 'collaborators'])->find($id);

        if (!$evaluation) {
            session()->flash('error', 'Evaluation not found.');
            return;
        }

        if (auth()->user()->role?->role_name !== 'administrator' && $evaluation->created_by !== auth()->id()) {
            session()->flash('error', 'Only the form owner can manage access.');
            return;
        }

        $this->sharingEvaluation = $evaluation;
        $this->shareSearch = '';
    }

    public function closeShareModal()
    {
        $this->sharingEvaluation = null;
    }

    public function addCollaborator($userId)
    {
        if ($this->sharingEvaluation) {
            $this->sharingEvaluation->collaborators()->syncWithoutDetaching([$userId]);
            $this->shareSearch = '';
        }
    }

    public function removeCollaborator($userId)
    {
        if ($this->sharingEvaluation) {
            $this->sharingEvaluation->collaborators()->detach($userId);
        }
    }

    #[Computed]
    public function searchResults()
    {
        if (empty($this->shareSearch) || strlen($this->shareSearch) < 2 || !$this->sharingEvaluation) {
            return [];
        }

        return \App\Models\User::where('name', 'like', '%' . $this->shareSearch . '%')
            ->where('id', '!=', auth()->id())
            ->where('id', '!=', $this->sharingEvaluation->created_by)
            ->whereNotIn('id', $this->sharingEvaluation->collaborators->pluck('id'))
            ->take(5)
            ->get();
    }

    public function render()
    {
        $query = Evaluation::with(['creator', 'collaborators'])->withCount('responses')->latest();

        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhereHas('collaborators', function ($q2) {
                      $q2->where('user_id', auth()->id());
                  });
            });
        }

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $evaluations = $query->paginate(10);

        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.evaluation-list', compact('evaluations'))
            ->layout($layoutFile);
    }
}