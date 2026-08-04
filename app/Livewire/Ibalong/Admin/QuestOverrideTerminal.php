<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\IbalongQuestSubmission;

class QuestOverrideTerminal extends Component
{
    use WithFileUploads;

    public $submission;
    public $quest;
    public $answers = [];
    public $files = [];
    public $existingFiles = [];

    // Security Gate
    public $adminPassword = '';
    public $isVerified = false;

    public function mount($submission_id)
    {
        // Strict RBAC: Only Super Admins (1) and Admins (2) can override
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2])) {
            abort(403, 'OVERRIDE DENIED: Administrative clearance required.');
        }

        $this->submission = IbalongQuestSubmission::with('quest.tasks', 'team', 'answers')->findOrFail($submission_id);
        $this->quest = $this->submission->quest;

        // Load existing answers and file paths
        foreach ($this->submission->answers as $ans) {
            $this->answers[$ans->task_id] = json_decode($ans->answer_text, true) ?? $ans->answer_text;
            if ($ans->file_path) {
                $this->existingFiles[$ans->task_id] = $ans->file_path;
            }
        }

        // Initialize checklist arrays to avoid string/array conflict errors
        foreach ($this->quest->tasks as $task) {
            if ($task->type === 'checklist' && !isset($this->answers[$task->id])) {
                $this->answers[$task->id] = [];
            }
        }
    }

    public function verifyPassword()
    {
        $this->validate([
            'adminPassword' => 'required|string'
        ]);

        if (Hash::check($this->adminPassword, auth('ibalong')->user()->password)) {
            $this->isVerified = true;
            $this->adminPassword = ''; // Clear it from memory immediately
        } else {
            $this->addError('adminPassword', 'ACCESS DENIED: Incorrect administrative password.');
        }
    }

    private function getValidationRules()
    {
        $rules = [];
        foreach ($this->quest->tasks as $task) {
            $baseRule = $task->is_required ? 'required' : 'nullable';

            if ($task->type === 'file') {
                $maxKB = ($task->max_file_size_mb ?? 5) * 1024;
                if ($baseRule === 'required' && isset($this->existingFiles[$task->id])) {
                    $baseRule = 'nullable';
                }
                $rules["files.{$task->id}"] = "$baseRule|file|max:$maxKB";
            } elseif ($task->type === 'checklist') {
                $rules["answers.{$task->id}"] = "$baseRule|array";
            } else {
                $rules["answers.{$task->id}"] = "$baseRule|string";
            }
        }
        return $rules;
    }

    public function executeOverride()
    {
        if (!$this->isVerified) return;

        $this->validate($this->getValidationRules());

        foreach ($this->quest->tasks as $task) {
            $val = null;
            $filePath = $this->existingFiles[$task->id] ?? null;

            if ($task->type === 'file' && isset($this->files[$task->id])) {
                if ($filePath) Storage::disk('public')->delete($filePath);

                $file = $this->files[$task->id];
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                $sanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $originalName);
                if (empty(trim($sanitizedName, '_'))) {
                    $sanitizedName = 'admin_override';
                }

                $finalName = $sanitizedName . '_' . uniqid() . '.' . $extension;
                $filePath = $file->storeAs("quests/files/{$this->submission->id}", $finalName, 'public');

                $this->existingFiles[$task->id] = $filePath;
                unset($this->files[$task->id]);
            } elseif ($task->type !== 'file') {
                $val = is_array($this->answers[$task->id] ?? null)
                       ? json_encode($this->answers[$task->id])
                       : ($this->answers[$task->id] ?? null);
            }

            $this->submission->answers()->updateOrCreate(
                ['task_id' => $task->id],
                ['answer_text' => $val, 'file_path' => $filePath]
            );
        }

        // Move draft to submitted if an admin overrides it
        $newStatus = $this->submission->status === 'draft' ? 'submitted' : $this->submission->status;

        $this->submission->update([
            'status' => $newStatus,
            'submitted_at' => now(),
        ]);

        session()->flash('success', 'OVERRIDE COMPLETE: The cohort\'s transmission data has been forcefully modified.');
        return redirect()->route('ibalong.admin.quests.submissions', $this->quest->id);
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-override-terminal')->layout('layouts.dashboard');
    }
}
