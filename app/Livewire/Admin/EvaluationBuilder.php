<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use App\Models\Evaluation;
use App\Models\Project;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

class EvaluationBuilder extends Component
{
    use WithFileUploads;

    public Evaluation $evaluation;

    // Form Properties
    public $title = '';
    public $slug = '';
    public $description = '';
    public $project_id = null;
    public $is_active = true;
    public $header_image;
    public $existing_header_image;
    public $theme_color = '#f1e7e2';

    // Data Containers
    public $questions = [];
    public $available_projects = [];
    public $activeQuestionIndex = null;

    // Certificate Properties
    public $newTemplate; 
    public $certPosX;
    public $certPosY;
    public $certTextColor;
    public $certFontSize;
    public $certDeliveryMode;
    public $certUseCustomEmail = false;
    public $certEmailSubject = '';
    public $certEmailBody = '';
    public $certNameQuestionId = null;
    public $certEmailQuestionId = null;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('evaluations', 'slug')->ignore($this->evaluation->id)],
            'theme_color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'project_id' => 'nullable|integer',
            'is_active' => 'boolean',
            'questions' => 'array',
            'questions.*.id' => 'nullable',
            'questions.*.temp_id' => 'required|string',
            'questions.*.type' => 'required|in:text,textarea,radio,checkbox,dropdown,likert,section,file,page_break',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.description' => 'nullable|string',
            'questions.*.is_required' => 'boolean',
            'questions.*.order' => 'integer',
            'questions.*.image_path' => 'nullable|string',
            'questions.*.new_image' => 'nullable|image|max:2048',
            'questions.*.options' => 'nullable|array',
        ];
    }

    public function duplicate()
    {
        if (!$this->evaluation->exists) {
            session()->flash('error', 'Cannot duplicate an unsaved form.');
            return;
        }

        $newEval = $this->evaluation->replicate();
        $newEval->title = $this->evaluation->title . ' (Copy)';
        $newEval->slug = \Illuminate\Support\Str::random(16); 
        $newEval->is_active = false; 
        $newEval->save();

        $idMap = []; 

        foreach ($this->evaluation->questions as $q) {
            $newQ = $q->replicate();
            $newQ->evaluation_id = $newEval->id;
            $newQ->save();
            $idMap[$q->id] = $newQ->id;
        }

        foreach ($newEval->questions as $newQ) {
            if (in_array($newQ->type, ['radio', 'dropdown', 'page_break']) && is_array($newQ->options)) {
                $updatedOptions = [];
                $modified = false;

                foreach ($newQ->options as $opt) {
                    if (isset($opt['jump']) && is_numeric($opt['jump']) && isset($idMap[$opt['jump']])) {
                        $opt['jump'] = $idMap[$opt['jump']];
                        $modified = true;
                    }
                    $updatedOptions[] = $opt;
                }

                if ($modified) {
                    $newQ->options = $updatedOptions;
                    $newQ->save();
                }
            }
        }

        session()->flash('success', 'Form duplicated successfully!');
        
        $roleName = auth()->user()->role?->role_name ?? 'guest';
        $routePrefix = match($roleName) {
            'organization'  => 'partner.evaluations',
            'director'      => 'director.evaluations',
            default         => 'admin.evaluations',
        };
        return redirect()->route($routePrefix . '.edit', $newEval->slug);
    }

    public function mount(Evaluation $evaluation = null)
    {
        $this->available_projects = Project::where('status', '!=', 'Draft')
            ->orderBy('title')->select('id', 'title')->get();

        $this->evaluation = $evaluation ?? new Evaluation();

        $this->certPosX = $evaluation->cert_pos_x ?? 50;
        $this->certPosY = $evaluation->cert_pos_y ?? 50;
        $this->certTextColor = $evaluation->cert_text_color ?? '#1f2937';
        $this->certFontSize = $evaluation->cert_font_size ?? 80;
        $this->certDeliveryMode = $evaluation->cert_delivery_mode ?? 'automatic';

        $user = auth()->user();
        $role = $user->role?->role_name;

        if (!in_array($role, ['administrator', 'director', 'organization'])) {
             abort(403, 'You do not have permission to build evaluations.');
        }

        $isCollaborator = $this->evaluation->exists ? $this->evaluation->collaborators()->where('user_id', $user->id)->exists() : false;

        if ($this->evaluation->exists &&
            $user->role?->role_name !== 'administrator' &&
            $this->evaluation->created_by !== $user->id &&
            !$isCollaborator) {
            abort(403, 'You do not have permission to access this evaluation.');
        }

        if ($this->evaluation->exists) {
            $this->title = $this->evaluation->title;
            $this->slug = $this->evaluation->slug;
            $this->description = $this->evaluation->description;
            $this->project_id = $this->evaluation->project_id;
            $this->is_active = $this->evaluation->is_active;
            $this->existing_header_image = $this->evaluation->header_image;
            $this->theme_color = $this->evaluation->theme_color ?? '#f1e7e2';

            $this->questions = $this->evaluation->questions()
                ->orderBy('order')
                ->get()
                ->map(function($q) {
                    $arr = $q->toArray();
                    $arr['new_image'] = null;
                    $arr['description'] = $arr['description'] ?? '';
                    $arr['temp_id'] = (string) Str::uuid();

                    if (!isset($arr['options']) || !is_array($arr['options'])) {
                        $arr['options'] = is_string($arr['options'] ?? null)
                            ? json_decode($arr['options'], true) ?? []
                            : [];
                    }

                    if ($arr['type'] === 'page_break' && empty($arr['options'])) {
                        $arr['options'] = [['jump' => '']];
                    }
                    return $arr;
                })
                ->toArray();

            if(count($this->questions) > 0) $this->activeQuestionIndex = 0;

        } else {
            $this->is_active = true;
            $this->questions = [];
        }

        $this->certUseCustomEmail = $evaluation->cert_use_custom_email ?? false;
        $this->certEmailSubject = $evaluation->cert_email_subject ?? 'Your Certificate of Participation';
        $this->certEmailBody = $evaluation->cert_email_body ?? "Hi [Name],\n\nThank you for participating in our event and taking the time to provide your feedback. Please find your e-certificate attached.\n\nBest regards,\nBU MADYA";
        $this->certNameQuestionId = $evaluation->cert_name_question_id;
        $this->certEmailQuestionId = $evaluation->cert_email_question_id;
    }

    public function saveCertificateSettings()
    {
        if ($this->newTemplate) {
            $path = $this->newTemplate->store('certificates', 'public');
            $this->evaluation->certificate_template = $path;
        }

        $this->evaluation->cert_pos_x = $this->certPosX;
        $this->evaluation->cert_pos_y = $this->certPosY;
        $this->evaluation->cert_text_color = $this->certTextColor;
        $this->evaluation->cert_font_size = $this->certFontSize;
        $this->evaluation->cert_delivery_mode = $this->certDeliveryMode;
        $this->evaluation->cert_use_custom_email = $this->certUseCustomEmail;
        $this->evaluation->cert_email_subject = $this->certEmailSubject;
        $this->evaluation->cert_email_body = $this->certEmailBody;
        $this->evaluation->cert_name_question_id = $this->certNameQuestionId ?: null;
        $this->evaluation->cert_email_question_id = $this->certEmailQuestionId ?: null;
        $this->evaluation->save();

        session()->flash('success', 'Certificate & Email settings saved!');
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

    public function setActiveQuestion($index)
    {
        $this->activeQuestionIndex = $index;
    }

    public function updateQuestionOrder($list)
    {
        foreach ($list as $item) {
            $tempId = $item['value'];
            $order = $item['order'];

            foreach ($this->questions as $key => $q) {
                if ($q['temp_id'] === $tempId) {
                    $this->questions[$key]['order'] = $order;
                    break;
                }
            }
        }

        usort($this->questions, fn($a, $b) => $a['order'] <=> $b['order']);
        $this->activeQuestionIndex = null;
    }

    public function addQuestion($type)
    {
        $defaultOptions = [];

        if ($type === 'likert') {
            $defaultOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        }
        elseif (in_array($type, ['radio', 'checkbox', 'dropdown'])) {
            $defaultOptions = [
                ['text' => 'Option 1', 'jump' => null],
                ['text' => 'Option 2', 'jump' => null]
            ];
        }
        elseif ($type === 'page_break') {
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
            'order' => 0,
            'image_path' => null,
            'new_image' => null
        ];

        if ($this->activeQuestionIndex !== null && isset($this->questions[$this->activeQuestionIndex])) {
            array_splice($this->questions, $this->activeQuestionIndex + 1, 0, [$newQuestion]);
            $this->activeQuestionIndex++; 
        } else {
            $this->questions[] = $newQuestion; 
            $this->activeQuestionIndex = count($this->questions) - 1;
        }

        foreach ($this->questions as $idx => $q) {
            $this->questions[$idx]['order'] = $idx;
        }
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);

        foreach ($this->questions as $idx => $q) {
            $this->questions[$idx]['order'] = $idx;
        }

        if ($this->activeQuestionIndex === $index) {
            $this->activeQuestionIndex = null;
        } elseif ($this->activeQuestionIndex > $index) {
            $this->activeQuestionIndex--;
        }
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

    public function generateRandomSlug()
    {
        $this->slug = Str::random(16);
    }

    #[On('confirmed-reset')]
    public function resetResponses()
    {
        if (!$this->evaluation->exists) return;

        $responseIds = $this->evaluation->responses()->pluck('id');

        if ($responseIds->isNotEmpty()) {
            \App\Models\EvaluationAnswer::whereIn('evaluation_response_id', $responseIds)->delete();
            $this->evaluation->responses()->delete();

            $this->dispatch('swal:modal', [
                'type' => 'success', 'title' => 'Deleted!', 'text' => 'Responses cleared successfully.'
            ]);
        } else {
            $this->dispatch('swal:modal', [
                'type' => 'info', 'title' => 'Empty', 'text' => 'No responses to delete.'
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->header_image) {
            $this->evaluation->header_image = $this->header_image->store('evaluation-headers', 'public');
        }
        $this->evaluation->title = $this->title;
        $this->evaluation->slug = !empty($this->slug) ? Str::slug($this->slug) : Str::slug($this->title);
        $this->evaluation->description = $this->description;
        $this->evaluation->project_id = empty($this->project_id) ? null : $this->project_id;
        $this->evaluation->is_active = $this->is_active;
        if (!$this->evaluation->exists) {
            $this->evaluation->created_by = auth()->id();
        }
        $this->evaluation->save();

        $currentIds = collect($this->questions)->pluck('id')->filter()->toArray();
        try {
            $this->evaluation->questions()->whereNotIn('id', $currentIds)->delete();
        } catch (\Exception $e) {
            session()->flash('warning', 'Some removed questions could not be deleted due to existing responses.');
        }

        $tempIdMap = [];

        foreach ($this->questions as $index => $q) {
            $imagePath = $q['image_path'] ?? null;
            if (isset($q['new_image']) && $q['new_image']) {
                $imagePath = $q['new_image']->store('question-images', 'public');
            }

            $qText = empty($q['question_text']) ? ' ' : $q['question_text'];

            $dbQ = $this->evaluation->questions()->updateOrCreate(
                ['id' => $q['id'] ?? null],
                [
                    'type' => $q['type'],
                    'question_text' => $q['type'] === 'page_break' ? 'Page Break' : $qText,
                    'description' => $q['description'] ?? null,
                    'options' => $q['options'],
                    'is_required' => $q['is_required'],
                    'order' => $index,
                    'image_path' => $imagePath
                ]
            );

            $tempIdMap[$q['temp_id']] = $dbQ->id;
        }

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

        session()->flash('success', 'Evaluation saved successfully!');
        
        // [FIXED] DYNAMIC REDIRECT
        $roleName = auth()->user()->role?->role_name ?? 'guest';
        $routePrefix = match($roleName) {
            'organization'  => 'partner.evaluations',
            'director'      => 'director.evaluations',
            default         => 'admin.evaluations',
        };
        return redirect()->route($routePrefix . '.index');
    }

    public function render()
    {
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.evaluation-builder')->layout($layoutFile);
    }
}