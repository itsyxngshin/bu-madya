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

    // Tab & Individual Response Tracking
    public $tab = 'summary';
    public $currentIndex = 0;

    // Synthesis & AI Reports
    public $synthesisReport = null;
    public $aiReport = null;

    // Manual Issue Modal State
    public $issueModalOpen = false;
    public $issueResponseId = null;
    public $issueName = '';
    public $issueEmail = '';
    public $issueSubject = '';
    public $issueBody = '';

    public function mount(IbalongEvaluation $evaluation)
    {
        $this->evaluation = $evaluation;

        // Use standard auth or the custom 'ibalong' guard
        $user = auth()->user() ?? auth('ibalong')->user();

        // 1. Check if user is collaborator (safely checking if the relationship exists)
        $isCollaborator = false;
        if ($user && $this->evaluation->exists && method_exists($this->evaluation, 'collaborators')) {
            $isCollaborator = $this->evaluation->collaborators()->where('user_id', $user->id)->exists();
        }

        // 2. Public Access Bypass Logic
        $isPublic = $this->evaluation->is_public_results ?? false;

        // 3. Admin / Creator Logic (Checking standard role_name or ibalong role_id)
        $isAdminOrCreator = false;
        if ($user) {
            $isAdmin = isset($user->role_id) ? in_array($user->role_id, [1, 2]) : ($user->role?->role_name === 'administrator');
            $isAdminOrCreator = $isAdmin || $this->evaluation->created_by === $user->id;
        }

        // Block if not public, not an admin, and not a collaborator
        if ($this->evaluation->exists && !$isPublic && !$isAdminOrCreator && !$isCollaborator) {
            abort(403, 'SYSTEM REJECT: You do not have permission to access this evaluation.');
        }

        $this->calculateStats();
    }

    public function togglePublicAccess()
    {
        $user = auth()->user() ?? auth('ibalong')->user();
        $isAdmin = isset($user->role_id) ? in_array($user->role_id, [1, 2]) : ($user->role?->role_name === 'administrator');

        // Only Admins or Creators can toggle public access
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

    public function nextResponse()
    {
        $total = $this->evaluation->responses()->count();
        if ($this->currentIndex < $total - 1) {
            $this->currentIndex++;
        }
    }

    public function previousResponse()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    // --- RESTORED: Core Analytical Calculators ---
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

            // Extract labels safely
            $flatOptions = is_array($question->options) ? collect($question->options)->map(function($opt) {
                return is_array($opt) ? ($opt['text'] ?? '') : $opt;
            })->toArray() : [];

            // A. LIKERT LOGIC
            if ($question->type === 'likert') {
                $sum = 0;
                $counts = array_fill(0, count($flatOptions), 0);

                foreach ($question->answers as $answer) {
                    $ansVal = $answer->answer_value ?? $answer->value; // Safely check for value column name
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
            // B. RADIO & DROPDOWN LOGIC
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
            // C. CHECKBOX LOGIC
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
            // D. TEXT / FILE
            else {
                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                ];
            }
        }

        $this->generateSynthesis();
    }

    public function generateSynthesis()
    {
        $likertData = [];
        $overallSum = 0;
        $totalLikertQuestions = 0;

        foreach ($this->evaluation->questions as $question) {
            if ($question->type === 'likert' && isset($this->stats[$question->id])) {
                $avg = $this->stats[$question->id]['average'] ?? 0;
                if ($avg > 0) {
                    $cleanText = strip_tags(Str::markdown($question->question_text ?? ''));
                    $likertData[$cleanText] = $avg;
                    $overallSum += $avg;
                    $totalLikertQuestions++;
                }
            }
        }

        if (count($likertData) < 2) {
            $this->synthesisReport = null;
            return;
        }

        $overallAverage = round($overallSum / $totalLikertQuestions, 2);
        $highestScore = max($likertData);
        $highestCriteria = array_search($highestScore, $likertData);
        $lowestScore = min($likertData);
        $lowestCriteria = array_search($lowestScore, $likertData);

        $sentiment = $overallAverage >= 4.0 ? 'highly positive' : ($overallAverage >= 3.0 ? 'generally mixed' : 'concerning');
        $successLvl = $overallAverage >= 4.5 ? 'an overwhelming success' : ($overallAverage >= 3.5 ? 'a successful execution' : 'an area requiring significant review');

        $this->synthesisReport = "Based on the data collected, this event received a **{$sentiment}** reception with an overall aggregated score of **{$overallAverage} out of 5**, indicating {$successLvl}. " .
            "Respondents were most satisfied with **\"{$highestCriteria}\"**, which earned the highest rating of **{$highestScore}**. " .
            "However, data indicates an opportunity for improvement regarding **\"{$lowestCriteria}\"**, which received the lowest relative score of **{$lowestScore}**.";
    }

    public function exportToCsv()
    {
        $evaluation = $this->evaluation->load(['questions', 'responses.answers', 'responses.user']);
        $fileName = 'evaluation_results_' . Str::slug($evaluation->title) . '_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($evaluation) {
            $file = fopen('php://output', 'w');

            $headers = ['Response ID', 'Date Submitted', 'Participant Name', 'Participant Email'];
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
                    $response->user ? $response->user->name : 'Anonymous',
                    $response->user ? $response->user->email : 'N/A',
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

        // Dynamic Layout Fallback for Public vs Admin Users
        $layoutFile = 'layouts.guest';
        $user = auth()->user() ?? auth('ibalong')->user();

        if ($user) {
            $isAdmin = isset($user->role_id) ? in_array($user->role_id, [1, 2]) : ($user->role?->role_name === 'administrator');
            $layoutFile = $isAdmin ? 'layouts.madya-admin-deck' : 'layouts.dashboard'; // Change dashboard layout name to match yours
        }

        return view('livewire.admin.evaluation-results', [
            'totalResponsesCount' => $totalResponsesCount,
            'currentResponse' => $currentResponse,
            'allResponses' => $allResponses,
        ])->layout($layoutFile);
    }
}
