<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CertificateMail;
use Illuminate\Support\Str;

class EvaluationResults extends Component
{
    public Evaluation $evaluation;
    public $stats = [];

    // Tab & Individual Response Tracking
    public $tab = 'summary'; // 'summary' or 'individual'
    public $currentIndex = 0;

    // Synthesis & AI Reports
    public $synthesisReport = null;
    public $aiReport = null;

    // --- NEW: Manual Issue Modal State ---
    public $issueModalOpen = false;
    public $issueResponseId = null;
    public $issueName = '';
    public $issueEmail = '';
    public $issueSubject = '';
    public $issueBody = '';

    public function mount(Evaluation $evaluation)
    {
        $user = auth()->user();

        // Check if the user is in the collaborators list
        $isCollaborator = $this->evaluation->exists ? $this->evaluation->collaborators()->where('user_id', $user->id)->exists() : false;

        // Block if not Admin, not Creator, AND not Collaborator
        if ($this->evaluation->exists &&
            $user->role?->role_name !== 'administrator' &&
            $this->evaluation->created_by !== $user->id &&
            !$isCollaborator) {
            abort(403, 'You do not have permission to access this evaluation.');
        }

        $this->evaluation = $evaluation;
        $this->calculateStats();
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
        $this->currentIndex = 0;
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

    public function calculateStats()
    {
        // Load questions in order
        $this->evaluation->load(['questions' => function ($query) {
            $query->orderBy('order');
        }, 'questions.answers']);

        foreach ($this->evaluation->questions as $question) {

            // Skip structural elements early
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

            // Extract Labels safely
            $flatOptions = collect($question->options)->map(function($opt) {
                return is_array($opt) ? ($opt['text'] ?? '') : $opt;
            })->toArray();

            // A. LIKERT LOGIC
            if ($question->type === 'likert') {
                $sum = 0;
                $counts = array_fill(0, count($flatOptions), 0);

                foreach ($question->answers as $answer) {
                    $index = array_search($answer->answer_value, $flatOptions);
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
                    $val = $answer->answer_value;
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
                    $selections = json_decode($answer->answer_value, true);

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

        // Generate Algorithmic Synthesis
        $this->generateSynthesis();
    }

    public function generateSynthesis()
    {
        $likertData = [];
        $overallSum = 0;
        $totalLikertQuestions = 0;

        foreach ($this->evaluation->questions as $question) {
            if ($question->type === 'likert' && isset($this->stats[$question->id])) {
                $avg = $this->stats[$question->id]['average'];
                if ($avg > 0) {
                    $cleanText = strip_tags(Str::markdown($question->question_text));
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

    public function generateAIInsights()
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            session()->flash('ai_error', 'Gemini API key is missing. Please add it to your .env file.');
            return;
        }

        $eventContext = "Event Name: " . $this->evaluation->title . "\nTotal Responses: " . $this->evaluation->responses()->count() . "\n\n";
        
        $quantitativeData = "Quantitative (Likert) Averages:\n";
        $qualitativeData = "Qualitative (Text) Feedback:\n";

        foreach ($this->evaluation->questions as $question) {
            if ($question->type === 'likert' && isset($this->stats[$question->id])) {
                $cleanText = strip_tags(Str::markdown($question->question_text));
                $quantitativeData .= "- {$cleanText}: " . $this->stats[$question->id]['average'] . " out of 5\n";
            } 
            elseif (in_array($question->type, ['text', 'textarea'])) {
                $cleanText = strip_tags(Str::markdown($question->question_text));
                $qualitativeData .= "\nQuestion: {$cleanText}\nAnswers:\n";
                foreach ($question->answers->take(15) as $answer) {
                    if (!empty($answer->answer_value)) {
                        $qualitativeData .= "- \"{$answer->answer_value}\"\n";
                    }
                }
            }
        }

        $prompt = "You are the Chief Data Strategist for BU MADYA, a youth-led advocacy and student organization. Analyze the following post-event evaluation data: \n\n" . 
                  $eventContext . $quantitativeData . "\n" . $qualitativeData . "\n\n" .
                  "Write a concise, highly readable Executive Summary for the Director-General and Executive Committee. " .
                  "Do NOT use large markdown headers (like # or ##). Use bold text and bullet points for readability. " .
                  "You MUST structure your response exactly like this:\n\n" .
                  "**1. The Bottom Line:** Write a 2-3 sentence overarching summary of the event's overall success, dominant sentiment, and quantitative performance.\n\n" .
                  "**2. What Went Well:** Provide a bulleted list of 2-3 common positive themes or specific criteria that scored the highest.\n\n" .
                  "**3. Areas for Growth:** Provide a bulleted list of 2-3 recurring criticisms, common complaints, or areas with the lowest scores.\n\n" .
                  "**4. Strategic Recommendations:** Provide 2 concrete, actionable steps the project committee should take to improve the next event based purely on this data.";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);

            if ($response->successful()) {
                $this->aiReport = $response->json('candidates.0.content.parts.0.text');
            } else {
                session()->flash('ai_error', 'Google AI API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            session()->flash('ai_error', 'Connection failed: ' . $e->getMessage());
        }
    }

    public function openIssueModal($responseId)
    {
        $response = EvaluationResponse::with(['user', 'answers'])->find($responseId);
        if (!$response) return;

        $this->issueResponseId = $responseId;

        // Extract Name
        $nameAnswer = $response->answers->where('evaluation_question_id', $this->evaluation->cert_name_question_id)->first();
        $this->issueName = $nameAnswer && !empty($nameAnswer->answer_value) ? $nameAnswer->answer_value : ($response->user->name ?? 'Participant'); 

        // Extract Email
        $emailAnswer = $response->answers->where('evaluation_question_id', $this->evaluation->cert_email_question_id)->first();
        $this->issueEmail = $emailAnswer && !empty($emailAnswer->answer_value) ? $emailAnswer->answer_value : ($response->user->email ?? '');

        // Pre-fill Subject and Body
        $this->issueSubject = $this->evaluation->cert_use_custom_email ? $this->evaluation->cert_email_subject : 'Your Certificate of Participation - BU MADYA';
        $eventName = $this->evaluation->title;

        if ($this->evaluation->cert_use_custom_email) {
            $this->issueBody = str_replace(['[Name]', '[Event]'], [$this->issueName, $eventName], $this->evaluation->cert_email_body);
        } else {
            $this->issueBody = "Hi {$this->issueName},\n\nThank you for participating in {$eventName}. Please find your official certificate attached below.\n\nBest regards,\nBU MADYA";
        }

        $this->issueModalOpen = true;
    }

    public function closeIssueModal()
    {
        $this->issueModalOpen = false;
    }

    // Helper to generate the Intervention Image
    private function createCertificateImage()
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read(storage_path('app/public/' . $this->evaluation->certificate_template));
        $pixelX = $image->width() * ($this->evaluation->cert_pos_x / 100);
        $pixelY = $image->height() * ($this->evaluation->cert_pos_y / 100);

        // Map the font
        $fontFamily = $this->evaluation->cert_font_family ?? 'Montserrat';
        $fontFile = match($fontFamily) {
            'Arial' => public_path('fonts/Arial.ttf'),
            'Times New Roman' => public_path('fonts/TimesNewRoman.ttf'),
            'Playfair Display' => public_path('fonts/PlayfairDisplay-Bold.ttf'),
            default => public_path('fonts/Montserrat-Bold.ttf'),
        };
        if (!file_exists($fontFile)) $fontFile = public_path('fonts/Montserrat-Bold.ttf'); // Fallback

        $image->text($this->issueName, $pixelX, $pixelY, function($font) use ($fontFile) {
            $font->file($fontFile); 
            $font->size($this->evaluation->cert_font_size);
            $font->color($this->evaluation->cert_text_color);
            $font->align('center');
            $font->valign('middle');
        });

        return $image;
    }

    // Action 1: Send Email Only
    public function sendCertificateEmail()
    {
        $this->validate(['issueName' => 'required|string', 'issueEmail' => 'required|email']);
        
        $image = $this->createCertificateImage();
        $tempPath = storage_path('app/public/temp_cert_' . time() . '.png');
        $image->toPng()->save($tempPath);

        Mail::to($this->issueEmail)->send(new CertificateMail($this->issueSubject, $this->issueBody, $tempPath));
        unlink($tempPath);

        session()->flash('success', 'Certificate emailed successfully to ' . $this->issueEmail);
        $this->closeIssueModal();
    }

    // Action 2: Download Only
    public function downloadCertificate()
    {
        $this->validate(['issueName' => 'required|string']);

        $image = $this->createCertificateImage();
        $tempPath = storage_path('app/public/temp_cert_' . time() . '.png');
        $image->toPng()->save($tempPath);

        $this->closeIssueModal();
        return response()->download($tempPath, 'Certificate-' . Str::slug($this->issueName) . '.png')->deleteFileAfterSend(true);
    }

    public function render()
    {
        $totalResponsesCount = $this->evaluation->responses()->count();
        $currentResponse = null;

        if ($this->tab === 'individual' && $totalResponsesCount > 0) {
            $currentResponse = EvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at')
                ->skip($this->currentIndex)
                ->first();
        }

        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.evaluation-results', [
            'totalResponsesCount' => $totalResponsesCount,
            'currentResponse' => $currentResponse,
        ])->layout($layoutFile);
    }
}