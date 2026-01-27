<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;

class EvaluationResults extends Component
{
    public Evaluation $evaluation;

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    /**
     * Calculate statistics for a specific question.
     * Returns an array of options with their counts and percentages.
     */
    public function getQuestionStats($questionId)
    {
        $question = EvaluationQuestion::find($questionId);
        
        // 1. Get all answers for this question
        $answers = $question->hasMany(\App\Models\EvaluationAnswer::class)->get();
        $total = $answers->count();

        if ($total === 0) return [];

        // 2. Count frequencies
        $counts = $answers->groupBy('answer_value')->map->count();

        // 3. Map to options format (preserving original option order)
        // We look at the defined options for the question to ensure 0-count options appear too
        $stats = collect($question->options)->map(function ($option) use ($counts, $total) {
            $count = $counts->get($option, 0);
            return [
                'label' => $option,
                'count' => $count,
                'percentage' => round(($count / $total) * 100, 1)
            ];
        });

        return $stats;
    }

    /**
     * Get text responses for a specific question
     */
    public function getTextResponses($questionId)
    {
        return \App\Models\EvaluationAnswer::where('evaluation_question_id', $questionId)
            ->whereNotNull('answer_value')
            ->latest()
            ->take(20) // Limit to last 20 for preview
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.evaluation-results');
    }
}