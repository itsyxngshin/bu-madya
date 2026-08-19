<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IbalongEvaluation;
use App\Models\IbalongEvaluationResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CertificateMail;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EvaluationResults extends Component
{
    use WithPagination;

    public IbalongEvaluation $evaluation;
    public $stats = [];

    public $tab = 'summary';
    public $currentIndex = 0;

    public $synthesisReport = null;
    public $aiReport = null;

    public $issueModalOpen = false;
    public $issueResponseId = null;
    public $issueName = '';
    public $issueEmail = '';
    public $issueSubject = '';
    public $issueBody = '';

    public function mount($evaluation = null)
    {
        // 1. BULLETPROOF MODEL RETRIEVAL
        if ($evaluation instanceof IbalongEvaluation && $evaluation->exists) {
            $this->evaluation = $evaluation;
        } elseif (is_string($evaluation)) {
            $this->evaluation = IbalongEvaluation::where('slug', $evaluation)->firstOrFail();
        } elseif (is_object($evaluation) && isset($evaluation->slug)) {
            $this->evaluation = IbalongEvaluation::where('slug', $evaluation->slug)->firstOrFail();
        } else {
            $slug = request()->segment(3) ?? request()->segment(4);
            $this->evaluation = IbalongEvaluation::where('slug', $slug)->firstOrFail();
        }

        // 2. AUTHORIZATION
        $user = auth('ibalong')->user() ?? auth()->user();
        $isPublic = $this->evaluation->is_public_results ?? false;

        $isAdminOrCreator = false;
        if ($user) {
            $roleId = $user->role_id ?? 0;
            $isAdmin = in_array($roleId, [1, 2]);
            $isAdminOrCreator = $isAdmin || $this->evaluation->created_by === $user->id;
        }

        $isCollaborator = false;
        if ($user && method_exists($this->evaluation, 'collaborators')) {
            $isCollaborator = $this->evaluation->collaborators()->where('user_id', $user->id)->exists();
        }

        if (!$isPublic && !$isAdminOrCreator && !$isCollaborator) {
            abort(403, 'SYSTEM REJECT: You do not have permission to access this evaluation.');
        }

        $this->calculateStats();
    }

    public function togglePublicAccess()
    {
        $user = auth('ibalong')->user() ?? auth()->user();
        $roleId = $user->role_id ?? 0;
        $isAdmin = in_array($roleId, [1, 2]);

        if (!$user || (!$isAdmin && $this->evaluation->created_by !== $user->id)) {
            abort(403, 'Unauthorized to modify broadcast settings.');
        }

        $this->evaluation->update([
            'is_public_results' => !$this->evaluation->is_public_results
        ]);

        session()->flash('success', 'Public broadcast status successfully updated.');
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
        $this->currentIndex = 0;
        $this->resetPage();
    }

    public function calculateStats()
    {
        $this->evaluation->load(['questions' => function ($query) {
            $query->orderBy('id');
        }, 'questions.answers']);

        foreach ($this->evaluation->questions as $question) {
            if (in_array($question->type, ['section', 'page_break'])) {
                continue;
            }

            $totalResponses = $question->answers->count();

            if ($totalResponses === 0) {
                $this->stats[$question->id] = [
                    'count' => 0, 'average' => 0, 'breakdown' => []
                ];
                continue;
            }

            $flatOptions = is_array($question->options) ? collect($question->options)->map(function($opt) {
                return is_array($opt) ? ($opt['text'] ?? '') : $opt;
            })->toArray() : [];

            if ($question->type === 'likert') {
                $sum = 0;
                $counts = array_fill(0, count($flatOptions), 0);

                foreach ($question->answers as $answer) {
                    $ansVal = $answer->answer_value ?? $answer->value;
                    $index = array_search($ansVal, $flatOptions);
                    if ($index !== false) {
                        $sum += ($index + 1);
                        $counts[$index]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'average' => round($sum / $totalResponses, 2),
                    'breakdown' => $counts
                ];
            }
            elseif (in_array($question->type, ['radio', 'dropdown'])) {
                $counts = array_fill_keys($flatOptions, 0);

                foreach ($question->answers as $answer) {
                    $val = $answer->answer_value ?? $answer->value;
                    if (isset($counts[$val])) {
                        $counts[$val]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts
                ];
            }
            elseif ($question->type === 'checkbox') {
                $counts = array_fill_keys($flatOptions, 0);

                foreach ($question->answers as $answer) {
                    $ansVal = $answer->answer_value ?? $answer->value;
                    $selections = is_string($ansVal) ? json_decode($ansVal, true) : $ansVal;

                    if (is_array($selections)) {
                        foreach ($selections as $selected) {
                            if (array_key_exists($selected, $counts)) {
                                $counts[$selected]++;
                            }
                        }
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts
                ];
            }
            else {
                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                ];
            }
        }
    }

    // --- NEW EXPORT PROTOCOLS ---
    public function exportToCsv()
    {
        $evaluation = $this->evaluation->load(['questions', 'responses.answers']);
        $fileName = 'evaluation_results_' . Str::slug($evaluation->title) . '_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($evaluation) {
            $file = fopen('php://output', 'w');
            $headers = ['Response ID', 'Date Submitted'];
            $validQuestions = [];

            foreach ($evaluation->questions as $question) {
                if (in_array($question->type, ['section', 'page_break'])) continue;
                $headers[] = strip_tags(Str::markdown($question->question_text ?? ''));
                $validQuestions[] = $question->id;
            }
            fputcsv($file, $headers);

            foreach ($evaluation->responses as $response) {
                $row = [
                    $response->id,
                    $response->created_at->format('Y-m-d H:i:s'),
                ];

                $answerKey = isset($response->answers->first()->question_id) ? 'question_id' : 'evaluation_question_id';
                $answers = $response->answers->keyBy($answerKey);

                foreach ($validQuestions as $qId) {
                    if ($answers->has($qId)) {
                        $ansObj = $answers->get($qId);
                        $val = $ansObj->answer_value ?? $ansObj->value;

                        $decoded = is_string($val) ? json_decode($val, true) : $val;
                        if (is_array($decoded)) {
                            $val = implode(', ', $decoded);
                        }
                        $row[] = $val;
                    } else {
                        $row[] = '';
                    }
                }
                fputcsv($file, $row);
            }
            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }

    public function exportToWord()
    {
        $evaluation = $this->evaluation;
        $fileName = 'evaluation_results_' . Str::slug($evaluation->title) . '_' . date('Y-m-d_H-i-s') . '.doc';

        // Generate compatible HTML that MS Word interprets directly as a document
        $html = "<html><head><meta charset='utf-8'><title>{$evaluation->title}</title></head><body style='font-family: Arial, sans-serif;'>";
        $html .= "<h1>{$evaluation->title} - Telemetry Results</h1>";
        $html .= "<p>Total Responses: {$evaluation->responses->count()}</p><hr/>";

        $questionCounter = 1;
        foreach ($evaluation->questions as $question) {
            if ($question->type === 'section') {
                $html .= "<br/><h2>{$question->question_text}</h2>";
                continue;
            }
            if ($question->type === 'page_break') continue;

            $html .= "<h3>Q{$questionCounter}. {$question->question_text}</h3>";

            if (in_array($question->type, ['radio', 'dropdown', 'checkbox', 'likert'])) {
                $html .= "<ul>";
                $breakdown = $this->stats[$question->id]['breakdown'] ?? [];
                $totalAnswers = $this->stats[$question->id]['count'] ?? 0;

                foreach ($question->options as $index => $option) {
                    $optText = is_array($option) ? ($option['text'] ?? '') : $option;
                    $count = $question->type === 'likert' ? ($breakdown[$index] ?? 0) : ($breakdown[$optText] ?? 0);
                    $percentage = $totalAnswers > 0 ? round(($count / $totalAnswers) * 100) : 0;
                    $html .= "<li><strong>{$optText}:</strong> {$count} ({$percentage}%)</li>";
                }
                $html .= "</ul>";
            } else {
                $textAnswers = collect();
                if ($evaluation->responses) {
                    $textAnswers = $evaluation->responses->flatMap->answers->where('question_id', $question->id)->filter(function($ans) {
                        $val = $ans->answer_value ?? $ans->value;
                        return !empty($val);
                    });
                }

                if($textAnswers->isEmpty()) {
                    $html .= "<p><em>No qualitative responses recorded.</em></p>";
                } else {
                    $html .= "<ul>";
                    foreach($textAnswers as $ans) {
                        $val = $ans->answer_value ?? $ans->value;
                        $cleanVal = is_string($val) ? htmlspecialchars($val) : json_encode($val);
                        $html .= "<li>{$cleanVal}</li>";
                    }
                    $html .= "</ul>";
                }
            }
            $questionCounter++;
        }

        $html .= "</body></html>";

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $fileName, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }

    public function render()
    {
        $totalResponsesCount = $this->evaluation->responses()->count();
        $currentResponse = null;
        $allResponses = null;

        if ($this->tab === 'individual' && $totalResponsesCount > 0) {
            $currentResponse = IbalongEvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at')
                ->skip($this->currentIndex)
                ->first();
        }
        elseif ($this->tab === 'table' && $totalResponsesCount > 0) {
            $allResponses = IbalongEvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        $layoutFile = 'layouts.guest';

        $user = auth('ibalong')->user();
        if ($user) {
            $roleId = $user->role_id ?? 0;
            $isAdmin = in_array($roleId, [1, 2]);
            $layoutFile = $isAdmin ? 'layouts.dashboard' : 'layouts.guest';
        }

        return view('livewire.ibalong.admin.evaluation-results', [
            'totalResponsesCount' => $totalResponsesCount,
            'currentResponse' => $currentResponse,
            'allResponses' => $allResponses,
        ])->layout($layoutFile);
    }
}
