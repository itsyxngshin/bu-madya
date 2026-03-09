<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EvaluationForm extends Component
{
    use WithFileUploads;

    public Evaluation $evaluation;
    public $project_id = null;
    public $answers = [];
    public $isSubmitted = false;

    // --- Pagination Properties ---
    public $currentPage = 0;

    protected $queryString = ['project_id'];

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;

        if (request()->has('project_id')) {
            $this->project_id = request()->query('project_id');
        }

        // Initialize answers with correct data types
        foreach($evaluation->questions as $q) {
            if ($q->type === 'checkbox') {
                $this->answers[$q->id] = [];
            } else {
                $this->answers[$q->id] = '';
            }
        }
    }

    // --- DYNAMIC PAGE BUILDER (Respects Skip Logic) ---
    private function getPages()
    {
        $pages = [];
        $pageIndex = 0;
        $skipping = false;
        $targetSectionId = null;

        $sortedQuestions = $this->evaluation->questions->sortBy('order');

        foreach ($sortedQuestions as $question) {

            // 1. Check if we reached the skip logic destination
            if ($skipping && $question->type === 'section' && $question->id == $targetSectionId) {
                $skipping = false;
                $targetSectionId = null;
            }

            // 2. If we are NOT skipping, process the question
            if (!$skipping) {
                if ($question->type === 'page_break') {
                    // Create a new page index (do not add the break itself to the UI)
                    $pageIndex++;
                } else {
                    // Add question to current page
                    $pages[$pageIndex][] = $question;
                }

                // 3. Trigger Skip Logic check (Radio only)
                if (isset($this->answers[$question->id]) && $question->type === 'radio') {
                    $selectedAnswer = $this->answers[$question->id];

                    if (is_array($question->options)) {
                        foreach ($question->options as $opt) {
                            $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                            $jumpTarget = is_array($opt) ? ($opt['jump'] ?? null) : null;

                            if ($optText == $selectedAnswer && !empty($jumpTarget)) {
                                $skipping = true;
                                $targetSectionId = ($jumpTarget === 'submit') ? 9999999 : $jumpTarget;
                            }
                        }
                    }
                }
            }
        }

        // Return re-indexed array in case there are empty pages from back-to-back breaks
        return array_values(array_filter($pages));
    }

    // --- NAVIGATION ---
    public function nextPage()
    {
        $pages = $this->getPages();

        // 1. Validate only the current page before allowing them to proceed
        $this->validateCurrentPage($pages[$this->currentPage] ?? []);

        // 2. Proceed
        if ($this->currentPage < count($pages) - 1) {
            $this->currentPage++;
            $this->dispatch('scroll-to-top'); // Using your existing Alpine scroll trigger
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 0) {
            $this->currentPage--;
            $this->dispatch('scroll-to-top');
        }
    }

    private function validateCurrentPage($currentQuestions)
    {
        $rules = [];
        foreach ($currentQuestions as $q) {
            if ($q->is_required && $q->type !== 'section') {
                $rules["answers.{$q->id}"] = 'required';
            }
        }
        if (!empty($rules)) {
            $this->validate($rules, ['required' => 'This question is required.']);
        }
    }

    // --- SUBMISSION ---
    public function submit()
    {
        $pages = $this->getPages();

        // 1. Validate the final page
        $this->validateCurrentPage($pages[$this->currentPage] ?? []);

        // 2. Create Response
        $response = EvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
        ]);

        // 3. Save Answers (ONLY for questions that were visible/not skipped)
        $visibleQuestionIds = collect($pages)->flatten()->pluck('id')->toArray();

        foreach ($this->answers as $questionId => $value) {
            if (!in_array($questionId, $visibleQuestionIds)) continue; // Ignore skipped questions
            if ($value === '' || $value === null || $value === []) continue;

            $finalValue = $value;

            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $finalValue = $value->store('evaluation-uploads', 'public');
            } elseif (is_array($value)) {
                $finalValue = json_encode($value);
            }

            EvaluationAnswer::create([
                'evaluation_response_id' => $response->id,
                'evaluation_question_id' => $questionId,
                'answer_value' => $finalValue
            ]);
        }

        $this->isSubmitted = true;
        $this->dispatch('scroll-to-top');
    }

    public function render()
    {
        $pages = $this->getPages();
        $totalPages = count($pages);

        // Progress Calculation based on Pages rather than fields
        $progress = $totalPages > 0 ? round((($this->currentPage + 1) / $totalPages) * 100) : 0;

        return view('livewire.open.evaluation-form', [
            'pages' => $pages,
            'totalPages' => $totalPages,
            'progress' => $progress
        ]);
    }
}
