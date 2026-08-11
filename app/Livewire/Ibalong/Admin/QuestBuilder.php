<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use Illuminate\Support\Facades\DB;

class QuestBuilder extends Component
{
    public $quest_id;
    public $title, $description, $deadline, $is_published = false;

    public $tasks = [];
    public $evaluationGroups = [];

    public function mount($quest_id = null)
    {
        if ($quest_id) {
            $quest = IbalongQuest::with('tasks', 'criteria')->findOrFail($quest_id);
            $this->quest_id = $quest->id;
            $this->title = $quest->title;
            $this->description = $quest->description;
            $this->deadline = $quest->deadline->format('Y-m-d\TH:i');
            $this->is_published = $quest->is_published;

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

            $groupedCriteria = $quest->criteria->groupBy(function($crit) {
                return !empty($crit->evaluation_group) ? $crit->evaluation_group : 'Main Scoring Matrix';
            });

            foreach ($groupedCriteria as $groupName => $crits) {
                $this->evaluationGroups[] = [
                    'name' => $groupName,
                    'criteria' => $crits->map(function($crit) {
                        return [
                            'id' => $crit->id,
                            'name' => $crit->name,
                            'max_score' => $crit->max_score,
                            'description' => $crit->description,
                            'levels' => $crit->rubric_levels,
                        ];
                    })->toArray()
                ];
            }

        } else {
            $this->addTask();
            $this->addEvaluationGroup();
        }
    }

    // --- TASK PROTOCOLS ---
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

    // --- GROUP & CRITERIA PROTOCOLS ---
    public function addEvaluationGroup()
    {
        $this->evaluationGroups[] = [
            'name' => '',
            'criteria' => []
        ];
        $this->addCriteriaToGroup(count($this->evaluationGroups) - 1);
    }

    public function removeEvaluationGroup($groupIndex)
    {
        unset($this->evaluationGroups[$groupIndex]);
        $this->evaluationGroups = array_values($this->evaluationGroups);
    }

    public function addCriteriaToGroup($groupIndex)
    {
        $this->evaluationGroups[$groupIndex]['criteria'][] = [
            'id' => null,
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

    public function removeCriteriaFromGroup($groupIndex, $criteriaIndex)
    {
        unset($this->evaluationGroups[$groupIndex]['criteria'][$criteriaIndex]);
        $this->evaluationGroups[$groupIndex]['criteria'] = array_values($this->evaluationGroups[$groupIndex]['criteria']);
    }

    // NEW: DRAG AND DROP MATRIX REORGANIZER
    public function moveCriteria($oldGroupIndex, $newGroupIndex, $oldIndex, $newIndex)
    {
        // Safety check to ensure the dragged item actually exists in the array
        if (!isset($this->evaluationGroups[$oldGroupIndex]['criteria'][$oldIndex])) {
            return;
        }

        // 1. Extract the criteria block from the old group
        $item = $this->evaluationGroups[$oldGroupIndex]['criteria'][$oldIndex];

        // 2. Remove it from the old array and re-index
        unset($this->evaluationGroups[$oldGroupIndex]['criteria'][$oldIndex]);
        $this->evaluationGroups[$oldGroupIndex]['criteria'] = array_values($this->evaluationGroups[$oldGroupIndex]['criteria']);

        // 3. Inject it into the new array at the exact dropped position
        if (!isset($this->evaluationGroups[$newGroupIndex]['criteria'])) {
            $this->evaluationGroups[$newGroupIndex]['criteria'] = [];
        }
        array_splice($this->evaluationGroups[$newGroupIndex]['criteria'], $newIndex, 0, [$item]);

        // 4. Final re-index to ensure wire:models don't desync
        $this->evaluationGroups[$newGroupIndex]['criteria'] = array_values($this->evaluationGroups[$newGroupIndex]['criteria']);
    }

    // --- EXECUTE SAVE ---
    public function saveQuest()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'tasks.*.question' => 'required|string',
            'evaluationGroups.*.name' => 'required|string|max:255',
            'evaluationGroups.*.criteria.*.name' => 'required|string',
            'evaluationGroups.*.criteria.*.max_score' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            $quest = IbalongQuest::updateOrCreate(
                ['id' => $this->quest_id],
                [
                    'title' => $this->title,
                    'description' => $this->description,
                    'deadline' => $this->deadline,
                    'is_published' => $this->is_published,
                ]
            );

            $existingTaskIds = [];
            foreach ($this->tasks as $index => $taskData) {
                $options = null;
                if (in_array($taskData['type'], ['dropdown', 'checklist'])) {
                    $options = is_array($taskData['options']) ? $taskData['options'] : array_map('trim', explode(',', $taskData['options'] ?? ''));
                }

                $task = $quest->tasks()->updateOrCreate(
                    ['id' => $taskData['id'] ?? null],
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

            $existingCritIds = [];
            foreach ($this->evaluationGroups as $group) {
                $groupName = trim($group['name']);

                foreach ($group['criteria'] as $critData) {
                    $crit = $quest->criteria()->updateOrCreate(
                        ['id' => $critData['id'] ?? null],
                        [
                            'evaluation_group' => $groupName ?: 'Main Scoring Matrix',
                            'name' => $critData['name'],
                            'max_score' => $critData['max_score'],
                            'description' => $critData['description'],
                            'rubric_levels' => $critData['levels'],
                        ]
                    );
                    $existingCritIds[] = $crit->id;
                }
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
