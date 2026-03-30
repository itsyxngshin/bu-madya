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

    // [NEW] Track which question is currently selected
    public $activeQuestionIndex = null;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('evaluations', 'slug')->ignore($this->evaluation->id)],
            'theme_color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'project_id' => 'nullable|integer',
            'is_active' => 'boolean',

            // [FIXED] Explicitly validate all array keys so Livewire doesn't strip them during deletion
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

        // 1. Clone the main evaluation
        $newEval = $this->evaluation->replicate();
        $newEval->title = $this->evaluation->title . ' (Copy)';
        $newEval->slug = \Illuminate\Support\Str::random(16); // Give it a fresh, unique URL
        $newEval->is_active = false; // Always set copies to Draft mode
        $newEval->save();

        $idMap = []; // To keep track of old_id => new_id for skip logic

        // 2. Clone all questions
        foreach ($this->evaluation->questions as $q) {
            $newQ = $q->replicate();
            $newQ->evaluation_id = $newEval->id;
            $newQ->save();
            $idMap[$q->id] = $newQ->id;
        }

        // 3. Remap the Skip Logic (Jump Targets)
        foreach ($newEval->questions as $newQ) {
            if (in_array($newQ->type, ['radio', 'dropdown', 'page_break']) && is_array($newQ->options)) {
                $updatedOptions = [];
                $modified = false;

                foreach ($newQ->options as $opt) {
                    // If this option has a jump target, replace the old ID with the new ID
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

        // Redirect to the newly created form
        return redirect()->route('admin.evaluations.edit', $newEval->slug);
    }

    public function mount(Evaluation $evaluation = null)
    {
        $this->available_projects = Project::where('status', '!=', 'Draft')
            ->orderBy('title')->select('id', 'title')->get();

        $this->evaluation = $evaluation ?? new Evaluation();

        $user = auth()->user();
        $role = $user->role?->role_name;

        if (!in_array($role, ['administrator', 'director', 'organization'])) {
             abort(403, 'You do not have permission to build evaluations.');
        }

        // Check if the user is in the collaborators list
        $isCollaborator = $this->evaluation->exists ? $this->evaluation->collaborators()->where('user_id', $user->id)->exists() : false;

        // Block if not Admin, not Creator, AND not Collaborator
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

                    // [NEW] Ensure 'options' is a valid array
                    if (!isset($arr['options']) || !is_array($arr['options'])) {
                        $arr['options'] = is_string($arr['options'] ?? null)
                            ? json_decode($arr['options'], true) ?? []
                            : [];
                    }

                    // [NEW] Fix page_break routing data on load so the dropdown binds correctly
                    if ($arr['type'] === 'page_break' && empty($arr['options'])) {
                        $arr['options'] = [['jump' => '']];
                    }
                    return $arr;
                })
                ->toArray();

            // Set first question active by default if it exists
            if(count($this->questions) > 0) $this->activeQuestionIndex = 0;

        } else {
            $this->is_active = true;
            $this->questions = [];
        }
    }

    #[Computed]
    public function sections()
    {
        $sections = [];
        foreach ($this->questions as $index => $question) {
            if ($question['type'] === 'section') {
                $sections[] = [
                    // Use database ID if saved, otherwise use temp_id for brand new sections
                    'id' => !empty($question['id']) ? $question['id'] : $question['temp_id'],
                    'title' => !empty($question['question_text']) ? $question['question_text'] : 'Untitled Section',
                    'order' => $index,
                ];
            }
        }
        return $sections;
    }

    // --- SELECTION LOGIC ---
    public function setActiveQuestion($index)
    {
        $this->activeQuestionIndex = $index;
    }

    // --- DRAG & DROP LOGIC ---
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

        // Reset active index so we don't accidentally highlight the wrong one after sort
        $this->activeQuestionIndex = null;
    }

    // --- QUESTION MANAGEMENT ---
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
            'is_required' => !in_array($type, ['section', 'page_break']), // Auto-false for breaks/sections
            'order' => 0,
            'image_path' => null,
            'new_image' => null
        ];

        // [NEW] Insert logic: Place it right after the currently active question
        if ($this->activeQuestionIndex !== null && isset($this->questions[$this->activeQuestionIndex])) {
            array_splice($this->questions, $this->activeQuestionIndex + 1, 0, [$newQuestion]);
            $this->activeQuestionIndex++; // Move focus to the newly created question
        } else {
            $this->questions[] = $newQuestion; // Fallback: append to end
            $this->activeQuestionIndex = count($this->questions) - 1;
        }

        // Recalculate order integers
        foreach ($this->questions as $idx => $q) {
            $this->questions[$idx]['order'] = $idx;
        }
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);

        // Recalculate order integers
        foreach ($this->questions as $idx => $q) {
            $this->questions[$idx]['order'] = $idx;
        }

        // Adjust active index if we deleted the active one or one above it
        if ($this->activeQuestionIndex === $index) {
            $this->activeQuestionIndex = null;
        } elseif ($this->activeQuestionIndex > $index) {
            $this->activeQuestionIndex--;
        }
    }

    // --- OPTION MANAGEMENT ---
    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = ['text' => 'New Option', 'jump' => null];
    }

    public function removeOption($qIndex, $optIndex)
    {
        unset($this->questions[$qIndex]['options'][$optIndex]);
        $this->questions[$qIndex]['options'] = array_values($this->questions[$qIndex]['options']);
    }

    // --- COMPUTED PROPERTIES ---
    public function getSectionsProperty()
    {
        return collect($this->questions)
            ->where('type', 'section')
            ->map(function($q) {
                return [
                    'id' => $q['temp_id'],
                    'title' => $q['question_text'] ?: 'Untitled Section',
                    'order' => $q['order']
                ];
            })
            ->sortBy('order')
            ->values();
    }

    // --- ACTIONS ---
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

            // Fallback for empty strings to prevent db errors
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
            // [UPDATED] Check radio, dropdown, AND page_break for jump targets
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
        return redirect()->route('admin.evaluations.index');
    }

    public function render()
    {
        // Determine the correct layout based on the user's role
        $layoutFile = auth()->user()->role?->role_name === 'administrator'
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        // Pass the layout dynamically
        return view('livewire.admin.evaluation-builder')
            ->layout($layoutFile);
    }
}
