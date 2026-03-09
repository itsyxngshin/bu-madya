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

#[Layout('layouts.madya-admin-deck')]
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

    // Data Containers
    public $questions = [];
    public $available_projects = [];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('evaluations', 'slug')->ignore($this->evaluation->id)],
            'project_id' => 'nullable|integer',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:text,textarea,radio,checkbox,dropdown,likert,section,file,page_break',
            'questions.*.new_image' => 'nullable|image|max:2048',
        ];
    }

    public function mount(Evaluation $evaluation = null)
    {
        // 1. Load Projects for Dropdown
        $this->available_projects = Project::where('status', '!=', 'Draft')
            ->orderBy('title')->select('id', 'title')->get();

        $this->evaluation = $evaluation ?? new Evaluation();

        // 2. Load Evaluation Data
        if ($this->evaluation->exists) {
            $this->title = $this->evaluation->title;
            $this->slug = $this->evaluation->slug;
            $this->description = $this->evaluation->description;
            $this->project_id = $this->evaluation->project_id;
            $this->is_active = $this->evaluation->is_active;
            $this->existing_header_image = $this->evaluation->header_image;

            // 3. Load Questions & Assign Temp IDs for Dragging
            $this->questions = $this->evaluation->questions()
                ->orderBy('order')
                ->get()
                ->map(function($q) {
                    $arr = $q->toArray();
                    $arr['new_image'] = null;
                    $arr['description'] = $arr['description'] ?? '';
                    $arr['temp_id'] = (string) Str::uuid(); // CRITICAL for Drag & Drop
                    return $arr;
                })
                ->toArray();
        } else {
            $this->is_active = true;
            $this->questions = [];
        }
    }

    // --- DRAG & DROP LOGIC ---

    public function updateQuestionOrder($list)
    {
        foreach ($list as $item) {
            $tempId = $item['value'];
            $order = $item['order'];

            // Find question by temp_id and update order
            foreach ($this->questions as $key => $q) {
                if ($q['temp_id'] === $tempId) {
                    $this->questions[$key]['order'] = $order;
                    break;
                }
            }
        }

        // Sort array in memory so the view reflects the change
        usort($this->questions, fn($a, $b) => $a['order'] <=> $b['order']);
    }

    // --- QUESTION MANAGEMENT ---

    public function addQuestion($type)
    {
        $defaultOptions = [];

        if ($type === 'likert') {
            $defaultOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        } elseif (in_array($type, ['radio', 'checkbox', 'dropdown'])) { // <-- Added 'dropdown' here
            $defaultOptions = [
                ['text' => 'Option 1', 'jump' => null],
                ['text' => 'Option 2', 'jump' => null]
            ];
        }

        $this->questions[] = [
            'id' => null,
            'temp_id' => (string) Str::uuid(), // Unique ID for new items
            'type' => $type,
            'question_text' => '',
            'description' => '',
            'options' => $defaultOptions,
            'is_required' => ($type !== 'section'),
            'order' => count($this->questions),
            'image_path' => null,
            'new_image' => null
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions); // Re-index array
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
                    'id' => $q['temp_id'], // Map section temp_id for dropdown
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

        // 1. Save Evaluation
        if ($this->header_image) {
            $this->evaluation->header_image = $this->header_image->store('evaluation-headers', 'public');
        }
        $this->evaluation->title = $this->title;
        $this->evaluation->slug = !empty($this->slug) ? Str::slug($this->slug) : Str::slug($this->title);
        $this->evaluation->description = $this->description;
        $this->evaluation->project_id = $this->project_id;
        $this->evaluation->is_active = $this->is_active;
        $this->evaluation->save();

        // 2. Sync Questions (Delete Removed)
        $currentIds = collect($this->questions)->pluck('id')->filter()->toArray();
        try {
            $this->evaluation->questions()->whereNotIn('id', $currentIds)->delete();
        } catch (\Exception $e) {
            session()->flash('warning', 'Some removed questions could not be deleted due to existing responses. Use "Reset Data" to clear them.');
        }

        // 3. Save Questions & Map Temp IDs
        $tempIdMap = []; // Maps 'uuid-temp' -> 5 (real DB ID)

        foreach ($this->questions as $index => $q) {
            $imagePath = $q['image_path'] ?? null;
            if (isset($q['new_image']) && $q['new_image']) {
                $imagePath = $q['new_image']->store('question-images', 'public');
            }

            $dbQ = $this->evaluation->questions()->updateOrCreate(
                ['id' => $q['id'] ?? null],
                [
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'description' => $q['description'] ?? null,
                    'options' => $q['options'],
                    'is_required' => $q['is_required'],
                    'order' => $index,
                    'image_path' => $imagePath
                ]
            );

            // Save the mapping for Skip Logic pass
            $tempIdMap[$q['temp_id']] = $dbQ->id;
        }

        // 4. Second Pass: Fix Skip Logic IDs
        // The dropdown saved a 'temp_id'. We need to replace it with the 'real_id' we just created.
        foreach ($this->evaluation->questions as $question) {
            if ($question->type === 'radio' && is_array($question->options)) {
                $updatedOptions = [];
                $modified = false;

                foreach ($question->options as $opt) {
                    if (isset($opt['jump']) && isset($tempIdMap[$opt['jump']])) {
                        $opt['jump'] = $tempIdMap[$opt['jump']]; // Swap UUID for Integer ID
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
        return view('livewire.admin.evaluation-builder');
    }
}
