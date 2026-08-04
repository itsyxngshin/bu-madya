<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;
use App\Models\IbalongEvaluation;

class EvaluationBuilder extends Component
{
    use WithFileUploads;

    public IbalongEvaluation $evaluation;

    // Form Properties
    public $title = '';
    public $slug = '';
    public $description = '';
    public $is_active = true;
    public $access_level = 'public'; // 'public' or 'teams_only'
    public $theme_color = '#FF8623'; // Hackathon Orange Default

    // Data Containers
    public $questions = [];
    public $activeQuestionIndex = null;
    public $sectionToDeleteIndex = null;

    // Certificate Properties
    public $newTemplate;
    public $certPosX = 50, $certPosY = 50;
    public $certTextColor = '#131011', $certFontSize = 80;
    public $certFontFamily = 'Montserrat', $certTextAlign = 'center';
    public $certDeliveryMode = 'automatic', $certUseCustomEmail = false;
    public $certEmailSubject = '', $certEmailBody = '';
    public $certNameQuestionId = null, $certEmailQuestionId = null;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'theme_color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'access_level' => 'required|in:public,teams_only',
            'questions' => 'array',
            'questions.*.temp_id' => 'required|string',
            'questions.*.type' => 'required|in:text,textarea,radio,checkbox,dropdown,likert,section,file,page_break',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.options' => 'nullable|array',
        ];
    }

    public function mount(IbalongEvaluation $evaluation = null)
    {
        // Enforce RBAC for Command Center
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Command Center clearance required.');
        }

        $this->evaluation = $evaluation ?? new IbalongEvaluation();

        if ($this->evaluation->exists) {
            $this->title = $this->evaluation->title;
            $this->slug = $this->evaluation->slug;
            $this->description = $this->evaluation->description;
            $this->is_active = $this->evaluation->is_active;
            $this->access_level = $this->evaluation->access_level ?? 'public';
            $this->theme_color = $this->evaluation->theme_color ?? '#FF8623';
            
            // Map JSON properties and assign UUIDs for SortableJS tracking
            $this->questions = $this->evaluation->questions()->orderBy('order')->get()->map(function($q) {
                $arr = $q->toArray();
                $arr['new_image'] = null;
                $arr['description'] = $arr['description'] ?? '';
                $arr['temp_id'] = (string) Str::uuid();
                if (!is_array($arr['options'])) {
                    $arr['options'] = json_decode($arr['options'], true) ?? [];
                }
                return $arr;
            })->toArray();
            
            // Map certificate configurations
            $this->certPosX = $this->evaluation->cert_pos_x ?? 50;
            $this->certPosY = $this->evaluation->cert_pos_y ?? 50;
            $this->certTextAlign = $this->evaluation->cert_text_align ?? 'center';
            $this->certUseCustomEmail = $this->evaluation->cert_use_custom_email ?? false;
            $this->certEmailBody = $this->evaluation->cert_email_body ?? "Hi [Name],\n\nThank you for participating in the Heroes of Innovation challenge. Please find your official e-certificate attached.\n\nCommand Center";
        } else {
            $this->is_active = true;
            $this->access_level = 'public';
        }
    }

    #[Computed]
    public function sections()
    {
        $sections = [];
        foreach ($this->questions as $index => $question) {
            if ($question['type'] === 'section') {
                $sections[] = [
                    'id' => !empty($question['id']) ? $question['id'] : $question['temp_id'],
                    'title' => !empty($question['question_text']) ? $question['question_text'] : 'Untitled Section',
                    'order' => $index,
                ];
            }
        }
        return $sections;
    }

    // --- NODE INJECTION & REMOVAL ---

    public function addQuestion($type)
    {
        $defaultOptions = [];
        if ($type === 'likert') {
            $defaultOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        } elseif (in_array($type, ['radio', 'checkbox', 'dropdown'])) {
            $defaultOptions = [['text' => 'Option 1', 'jump' => null], ['text' => 'Option 2', 'jump' => null]];
        } elseif ($type === 'page_break') {
            $defaultOptions = [['jump' => null]];
        }

        $newQuestion = [
            'id' => null,
            'temp_id' => (string) Str::uuid(),
            'type' => $type,
            'question_text' => '',
            'description' => '',
            'options' => $defaultOptions,
            'is_required' => !in_array($type, ['section', 'page_break']),
            'order' => count($this->questions),
        ];

        if ($this->activeQuestionIndex !== null && isset($this->questions[$this->activeQuestionIndex])) {
            array_splice($this->questions, $this->activeQuestionIndex + 1, 0, [$newQuestion]);
            $this->activeQuestionIndex++;
        } else {
            $this->questions[] = $newQuestion;
            $this->activeQuestionIndex = count($this->questions) - 1;
        }

        $this->refreshQuestionOrders();
    }

    // --- ADVANCED FLOW CONTROL & REORDERING ---

    public function duplicateQuestion($index)
    {
        $original = $this->questions[$index];
        $copy = json_decode(json_encode($original), true);
        $copy['id'] = null;
        $copy['temp_id'] = (string) Str::uuid();
        $copy['question_text'] = $copy['question_text'] . ' (Copy)';

        array_splice($this->questions, $index + 1, 0, [$copy]);
        $this->activeQuestionIndex = $index + 1;
        $this->refreshQuestionOrders();
    }

    public function duplicateSection($index)
    {
        $itemsToDuplicate = [];
        $itemsToDuplicate[] = $this->questions[$index];

        $i = $index + 1;
        while ($i < count($this->questions)) {
            $type = $this->questions[$i]['type'];
            if (in_array($type, ['section', 'page_break'])) break;
            $itemsToDuplicate[] = $this->questions[$i];
            $i++;
        }

        $insertionIndex = $i;
        $newItems = [];
        foreach ($itemsToDuplicate as $item) {
            $copy = json_decode(json_encode($item), true);
            $copy['id'] = null;
            $copy['temp_id'] = (string) Str::uuid();
            if ($copy['type'] === 'section') {
                $copy['question_text'] = $copy['question_text'] . ' (Copy)';
            }
            $newItems[] = $copy;
        }

        array_splice($this->questions, $insertionIndex, 0, $newItems);
        $this->activeQuestionIndex = $insertionIndex;
        $this->refreshQuestionOrders();
    }

    public function removeQuestion($index)
    {
        if (!isset($this->questions[$index])) return;

        $isSection = $this->questions[$index]['type'] === 'section';

        if ($isSection) {
            // Cascade delete all contained questions
            $indicesToRemove = [$index];
            $i = $index + 1;

            while ($i < count($this->questions)) {
                if (in_array($this->questions[$i]['type'], ['section', 'page_break'])) {
                    break;
                }
                $indicesToRemove[] = $i;
                $i++;
            }

            rsort($indicesToRemove); 
            foreach ($indicesToRemove as $idx) {
                unset($this->questions[$idx]);
            }
        } else {
            unset($this->questions[$index]);
        }

        $this->questions = array_values($this->questions);
        $this->refreshQuestionOrders();
        $this->activeQuestionIndex = null;
    }

    private function refreshQuestionOrders()
    {
        foreach ($this->questions as $i => $q) {
            $this->questions[$i]['order'] = $i;
        }
    }

    public function updateQuestionOrder($list)
    {
        foreach ($list as $item) {
            foreach ($this->questions as $key => $q) {
                if ($q['temp_id'] == $item['value'] || (!empty($q['id']) && $q['id'] == $item['value'])) {
                    $this->questions[$key]['order'] = $item['order'];
                    break;
                }
            }
        }
        usort($this->questions, fn($a, $b) => $a['order'] <=> $b['order']);
    }

    // --- UI HELPER METHODS ---

    public function setActiveQuestion($index)
    {
        $this->activeQuestionIndex = $index;
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = ['text' => 'New Option', 'jump' => null];
    }

    public function removeOption($qIndex, $optIndex)
    {
        unset($this->questions[$qIndex]['options'][$optIndex]);
        $this->questions[$qIndex]['options'] = array_values($this->questions[$qIndex]['options']);
    }

    public function confirmDeleteSection($index)
    {
        $this->sectionToDeleteIndex = $index;
    }

    public function cancelDeleteSection()
    {
        $this->sectionToDeleteIndex = null;
    }

    public function executeDeleteSection()
    {
        if ($this->sectionToDeleteIndex !== null) {
            $this->removeQuestion($this->sectionToDeleteIndex);
            $this->sectionToDeleteIndex = null;
        }
    }

    // --- SAVE AND DEPLOY ---

    public function save()
    {
        $this->validate();

        $this->evaluation->title = $this->title;
        $this->evaluation->slug = !empty($this->slug) ? Str::slug($this->slug) : Str::slug($this->title);
        $this->evaluation->description = $this->description;
        $this->evaluation->theme_color = $this->theme_color;
        $this->evaluation->is_active = $this->is_active;
        $this->evaluation->access_level = $this->access_level;
        $this->evaluation->created_by = auth('ibalong')->id();

        // Save Certificate Data
        $this->evaluation->cert_text_align = $this->certTextAlign;
        $this->evaluation->cert_use_custom_email = $this->certUseCustomEmail;
        $this->evaluation->cert_email_body = $this->certEmailBody;
        $this->evaluation->save();

        $currentIds = collect($this->questions)->pluck('id')->filter()->toArray();
        $this->evaluation->questions()->whereNotIn('id', $currentIds)->delete();

        $tempIdMap = []; // Maps Temp UUIDs to actual Database IDs for jump logic

        foreach ($this->questions as $index => $q) {
            $dbQ = $this->evaluation->questions()->updateOrCreate(
                ['id' => $q['id'] ?? null],
                [
                    'type' => $q['type'],
                    'question_text' => $q['type'] === 'page_break' ? 'Page Break' : ($q['question_text'] ?: ' '),
                    'description' => $q['description'] ?? null,
                    'options' => $q['options'],
                    'is_required' => $q['is_required'],
                    'order' => $index,
                ]
            );
            
            $tempIdMap[$q['temp_id']] = $dbQ->id;
        }

        // Second pass to update Jump logic with real Database IDs
        foreach ($this->evaluation->questions as $question) {
            if (in_array($question->type, ['radio', 'dropdown', 'page_break']) && is_array($question->options)) {
                $updatedOptions = [];
                $modified = false;

                foreach ($question->options as $opt) {
                    if (isset($opt['jump']) && isset($tempIdMap[$opt['jump']])) {
                        $opt['jump'] = $tempIdMap[$opt['jump']];
                        $modified = true;
                    }
                    $updatedOptions[] = $opt;
                }

                if ($modified) {
                    $question->options = $updatedOptions;
                    $question->save();
                }
            }
        }

        session()->flash('success', 'Form blueprint established and saved to the databanks.');
        return redirect()->route('admin.evaluations.index'); 
    }

    public function render()
    {
        return view('livewire.ibalong.admin.evaluation-builder')->layout('layouts.dashboard');
    }
}