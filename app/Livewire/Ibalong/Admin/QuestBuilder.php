<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use Illuminate\Support\Facades\DB;

class QuestBuilder extends Component
{
    public $quest_id; // Will be set if editing
    public $title, $description, $deadline, $is_published = false;

    public $tasks = [];
    public $criteria = [];

    public function mount($quest_id = null)
    {
        if ($quest_id) {
            $quest = IbalongQuest::with('tasks', 'criteria')->findOrFail($quest_id);
            $this->quest_id = $quest->id;
            $this->title = $quest->title;
            $this->description = $quest->description;
            $this->deadline = $quest->deadline->format('Y-m-d\TH:i');
            $this->is_published = $quest->is_published;

            // Load existing tasks
            $this->tasks = $quest->tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'question' => $task->question,
                    'type' => $task->type,
                    'options' => is_array($task->options) ? implode(', ', $task->options) : '',
                    'max_file_size_mb' => $task->max_file_size_mb,
                    'is_required' => $task->is_required,
                ];
            })->toArray();

            // Load existing criteria with Evaluation Group
            $this->criteria = $quest->criteria->map(function($crit) {
                return [
                    'id' => $crit->id,
                    'evaluation_group' => $crit->evaluation_group ?? 'Main Scoring Matrix',
                    'name' => $crit->name,
                    'max_score' => $crit->max_score,
                    'description' => $crit->description,
                    'levels' => $crit->rubric_levels,
                ];
            })->toArray();

        } else {
            // Default setup for a new quest
            $this->addTask();
            $this->addCriteria();
        }
    }

    public function addTask()
    {
        $this->tasks[] = [
            'id' => null,
            'question' => '',
            'type' => 'short_text',
            'options' => '',
            'max_file_size_mb' => 5,
            'is_required' => true,
        ];
    }

    public function removeTask($index)
    {
        unset($this->tasks[$index]);
        $this->tasks = array_values($this->tasks);
    }

    public function addCriteria()
    {
        $this->criteria[] = [
            'id' => null,
            'evaluation_group' => 'Main Scoring Matrix',
            'name' => '',
            'max_score' => 10,
            'description' => '',
            'levels' => [
                ['degree' => 'Outstanding', 'range' => '', 'description' => ''],
                ['degree' => 'Strong', 'range' => '', 'description' => ''],
                ['degree' => 'Developing', 'range' => '', 'description' => ''],
                ['degree' => 'Emerging', 'range' => '', 'description' => ''],
            ]
        ];
    }

    public function removeCriteria($index)
    {
        unset($this->criteria[$index]);
        $this->criteria = array_values($this->criteria);
    }

    public function saveQuest()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'tasks.*.question' => 'required|string',
            'criteria.*.evaluation_group' => 'nullable|string|max:255',
            'criteria.*.name' => 'required|string',
            'criteria.*.max_score' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            // Update or Create Master Quest
            $quest = IbalongQuest::updateOrCreate(
                ['id' => $this->quest_id],
                [
                    'title' => $this->title,
                    'description' => $this->description,
                    'deadline' => $this->deadline,
                    'is_published' => $this->is_published,
                ]
            );

            // Sync Tasks
            $existingTaskIds = [];
            foreach ($this->tasks as $index => $taskData) {
                $options = null;
                if (in_array($taskData['type'], ['dropdown', 'checklist'])) {
                    $options = is_array($taskData['options']) ? $taskData['options'] : array_map('trim', explode(',', $taskData['options'] ?? ''));
                }

                $task = $quest->tasks()->updateOrCreate(
                    ['id' => $taskData['id']],
                    [
                        'question' => $taskData['question'],
                        'type' => $taskData['type'],
                        'options' => $options,
                        'max_file_size_mb' => $taskData['type'] === 'file' ? $taskData['max_file_size_mb'] : null,
                        'is_required' => $taskData['is_required'],
                        'order_index' => $index,
                    ]
                );
                $existingTaskIds[] = $task->id;
            }
            $quest->tasks()->whereNotIn('id', $existingTaskIds)->delete();

            // Sync Criteria
            $existingCritIds = [];
            foreach ($this->criteria as $critData) {
                $crit = $quest->criteria()->updateOrCreate(
                    ['id' => $critData['id']],
                    [
                        'evaluation_group' => empty($critData['evaluation_group']) ? 'Main Scoring Matrix' : $critData['evaluation_group'],
                        'name' => $critData['name'],
                        'max_score' => $critData['max_score'],
                        'description' => $critData['description'],
                        'rubric_levels' => $critData['levels'],
                    ]
                );
                $existingCritIds[] = $crit->id;
            }
            $quest->criteria()->whereNotIn('id', $existingCritIds)->delete();
        });

        session()->flash('success', $this->quest_id ? 'Quest Log modifications accepted.' : 'Quest successfully forged and added to the logs!');
        return redirect()->route('ibalong.admin.quests.index');
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-builder')->layout('layouts.dashboard');
    }
}
