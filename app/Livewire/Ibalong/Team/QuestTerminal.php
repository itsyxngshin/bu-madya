<?php

namespace App\Livewire\Ibalong\Team;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\IbalongQuest;
use App\Models\IbalongQuestSubmission;

class QuestTerminal extends Component
{
    use WithFileUploads;

    public $quest;
    public $submission;
    public $answers = [];
    public $files = [];
    public $existingFiles = []; // To track files already saved in drafts
    public $isLocked = false;

    public function mount($quest_id)
    {
        $this->quest = IbalongQuest::with('tasks', 'criteria')->findOrFail($quest_id);
        $team_id = auth('ibalong')->user()->registration->id ?? null;

        if (!$team_id) abort(403, 'UNAUTHORIZED: Terminal access restricted to registered cohorts.');

        $this->submission = IbalongQuestSubmission::firstOrCreate(
            ['quest_id' => $this->quest->id, 'team_id' => $team_id],
            ['status' => 'draft']
        );

        // Load existing answers and file paths
        foreach ($this->submission->answers as $ans) {
            $this->answers[$ans->task_id] = json_decode($ans->answer_text, true) ?? $ans->answer_text;
            if ($ans->file_path) {
                $this->existingFiles[$ans->task_id] = $ans->file_path;
            }
        }

        // Initialize checklist arrays
        foreach ($this->quest->tasks as $task) {
            if ($task->type === 'checklist' && !isset($this->answers[$task->id])) {
                $this->answers[$task->id] = [];
            }
        }
    }

    private function getValidationRules($isDraft = false)
    {
        $rules = [];
        foreach ($this->quest->tasks as $task) {
            $baseRule = ($task->is_required && !$isDraft) ? 'required' : 'nullable';

            if ($task->type === 'file') {
                $maxKB = ($task->max_file_size_mb ?? 5) * 1024;
                if ($baseRule === 'required' && isset($this->existingFiles[$task->id])) {
                    $baseRule = 'nullable';
                }

                // Reverted back to the standard, clean validation
                $rules["files.{$task->id}"] = "$baseRule|file|max:$maxKB";

            } elseif ($task->type === 'checklist') {
                $rules["answers.{$task->id}"] = "$baseRule|array";
            } else {
                $rules["answers.{$task->id}"] = "$baseRule|string";
            }
        }
        return $rules;
    }

    private function saveAnswersToDatabase()
    {
        foreach ($this->quest->tasks as $task) {
            $val = null;
            $filePath = $this->existingFiles[$task->id] ?? null;

            if ($task->type === 'file' && isset($this->files[$task->id])) {
                // Delete the old file if they are replacing it
                if ($filePath) Storage::disk('public')->delete($filePath);

                $file = $this->files[$task->id];
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                // SANITIZER: Replace anything that isn't a letter, number, or underscore with an underscore
                $sanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $originalName);

                // Fallback just in case the original filename was entirely emojis or symbols
                if (empty(trim($sanitizedName, '_'))) {
                    $sanitizedName = 'cohort_upload';
                }

                // Append a unique short ID so files with the same name never overwrite each other
                $finalName = $sanitizedName . '_' . uniqid() . '.' . $extension;

                // Use storeAs() to save the file with our newly cleaned filename
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
    }

    public function saveDraft()
    {
        $this->validate($this->getValidationRules(true));
        $this->saveAnswersToDatabase();
        session()->flash('success', 'Draft saved. You can safely leave and return later.');
    }

    public function submitQuest()
    {
        if ($this->quest->deadline->isPast()) {
            session()->flash('error', 'SYSTEM LOCK: The deadline for this quest has already passed.');
            return;
        }

        $this->validate($this->getValidationRules(false));
        $this->saveAnswersToDatabase();

        $this->submission->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        session()->flash('success', 'Your response has been delivered to the Council!');
    }

    public function render()
    {
        return view('livewire.ibalong.team.quest-terminal')->layout('layouts.dashboard');
    }
}
