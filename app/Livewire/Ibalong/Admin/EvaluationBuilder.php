<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
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
    public $header_image;
    public $existing_header_image;
    public $theme_color = '#FF8623'; // Default to Hackathon Orange
    public $access_level = 'public';

    // Data Containers
    public $questions = [];
    public $activeQuestionIndex = null;
    public $sectionToDeleteIndex = null;

    // Certificate Properties mapped from BU MADYA implementation
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
            'questions' => 'array',
            'questions.*.temp_id' => 'required|string',
            'questions.*.type' => 'required|in:text,textarea,radio,checkbox,dropdown,likert,section,file,page_break',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.options' => 'nullable|array',
            'access_level' => 'required|in:public,teams_only'
        ];
    }

    public function mount(IbalongEvaluation $evaluation = null)
    {
        // Admin & Director RBAC check
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
            $this->theme_color = $this->evaluation->theme_color ?? '#FF8623';
            $this->access_level = $this->evaluation->access_level ?? 'public';

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

            // Map certificate configurations[cite: 6]
            $this->certPosX = $this->evaluation->cert_pos_x ?? 50;
            $this->certPosY = $this->evaluation->cert_pos_y ?? 50;
            $this->certTextAlign = $this->evaluation->cert_text_align ?? 'center';
            $this->certUseCustomEmail = $this->evaluation->cert_use_custom_email ?? false;
            $this->certEmailBody = $this->evaluation->cert_email_body ?? "Hi [Name],\n\nThank you for participating in the Heroes of Innovation 2026 challenge. Please find your official e-certificate attached.\n\nCommand Center";
        } else {
            $this->is_active = true;
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

    public function addQuestion($type)
    {
        $defaultOptions = [];
        if ($type === 'likert') {
            $defaultOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        } elseif (in_array($type, ['radio', 'checkbox', 'dropdown'])) {
            $defaultOptions = [['text' => 'Option 1', 'jump' => null], ['text' => 'Option 2', 'jump' => null]];
        }

        $this->questions[] = [
            'id' => null,
            'temp_id' => (string) Str::uuid(),
            'type' => $type,
            'question_text' => '',
            'description' => '',
            'options' => $defaultOptions,
            'is_required' => !in_array($type, ['section', 'page_break']),
            'order' => count($this->questions),
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions); // Reset indices
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
        // Adds a new choice to Radio, Checkbox, or Dropdown nodes
        $this->questions[$questionIndex]['options'][] = ['text' => 'New Option', 'jump' => null];
    }

    public function removeOption($qIndex, $optIndex)
    {
        unset($this->questions[$qIndex]['options'][$optIndex]);
        // Re-index the array so Livewire doesn't throw a string conflict
        $this->questions[$qIndex]['options'] = array_values($this->questions[$qIndex]['options']);
    }

    // --- SECTION PURGE PROTOCOLS ---

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

    public function save()
    {
        $this->validate();

        $this->evaluation->title = $this->title;
        $this->evaluation->slug = !empty($this->slug) ? Str::slug($this->slug) : Str::slug($this->title);
        $this->evaluation->description = $this->description;
        $this->evaluation->theme_color = $this->theme_color;
        $this->evaluation->is_active = $this->is_active;
        $this->evaluation->created_by = auth('ibalong')->id();

        // Save Certificate Data
        $this->evaluation->cert_text_align = $this->certTextAlign;
        $this->evaluation->cert_use_custom_email = $this->certUseCustomEmail;
        $this->evaluation->cert_email_body = $this->certEmailBody;
        $this->evaluation->save();

        $currentIds = collect($this->questions)->pluck('id')->filter()->toArray();
        $this->evaluation->questions()->whereNotIn('id', $currentIds)->delete();

        foreach ($this->questions as $index => $q) {
            $this->evaluation->questions()->updateOrCreate(
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
        }

        session()->flash('success', 'Form blueprint established and saved to the databanks.');
        // Return to an index page (you will need to create this route)
        return redirect()->route('ibalong.admin.evaluations.index');
    }

    public function render()
    {
        return view('livewire.ibalong.admin.evaluation-builder')->layout('layouts.dashboard');
    }
}
