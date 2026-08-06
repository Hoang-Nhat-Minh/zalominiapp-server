<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $query = Survey::withCount(['questions', 'responses']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('target_tdp', 'like', "%{$search}%");
            });
        }

        $surveys = $query->latest()->paginate(10)->withQueryString();

        $stats = (object)[
            'total'     => Survey::count(),
            'active'    => Survey::where('is_active', true)->count(),
            'responses' => SurveyResponse::count(),
        ];

        return view('frontend.surveys.index', compact('surveys', 'stats'));
    }

    public function create()
    {
        return view('frontend.surveys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'target_tdp'   => 'required|string',
            'deadline'     => 'nullable|date',
            'questions'    => 'required|array|min:1',
            'questions.*.question_text' => 'required|string|max:500',
            'questions.*.type'          => 'required|in:single_choice,multiple_choice,text,rating',
        ]);

        DB::transaction(function () use ($request) {
            $survey = Survey::create([
                'title'       => $request->title,
                'description' => $request->description,
                'target_tdp'  => $request->target_tdp,
                'deadline'    => $request->deadline,
                'is_active'   => $request->has('is_active'),
            ]);

            foreach ($request->questions as $index => $q) {
                $options = null;
                if (in_array($q['type'], ['single_choice', 'multiple_choice']) && !empty($q['options'])) {
                    // Normalize options array
                    $options = array_values(array_filter(array_map('trim', explode("\n", $q['options']))));
                }

                SurveyQuestion::create([
                    'survey_id'     => $survey->id,
                    'question_text' => $q['question_text'],
                    'type'          => $q['type'],
                    'options'       => $options,
                    'is_required'   => isset($q['is_required']),
                    'order'         => $index + 1,
                ]);
            }
        });

        return redirect()->route('surveys.index')->with('success', 'Tạo và phát hành bài khảo sát thành công!');
    }

    public function show(Survey $survey)
    {
        $survey->load(['questions', 'responses.user']);
        $totalResponses = $survey->responses->count();

        // Process statistics per question from actual database responses
        $questionsData = $survey->questions->map(function ($q, $qIndex) use ($survey, $totalResponses) {
            $optionCounts = [];
            $ratingSum = 0;
            $ratingCount = 0;
            $textAnswers = [];

            if (in_array($q->type, ['single_choice', 'multiple_choice'])) {
                $options = is_array($q->options) ? $q->options : [];
                foreach ($options as $opt) {
                    $optionCounts[$opt] = 0;
                }
            }

            foreach ($survey->responses as $response) {
                $answers = $response->answers;
                $val = null;

                if (is_array($answers)) {
                    if (array_key_exists($q->id, $answers)) {
                        $val = $answers[$q->id];
                    } elseif (array_key_exists("q_{$q->id}", $answers)) {
                        $val = $answers["q_{$q->id}"];
                    } elseif (array_key_exists($qIndex, $answers)) {
                        $val = $answers[$qIndex];
                    } else {
                        foreach ($answers as $item) {
                            if (is_array($item) && isset($item['question_id']) && $item['question_id'] == $q->id) {
                                $val = $item['answer'] ?? $item['value'] ?? null;
                                break;
                            }
                        }
                    }
                }

                if ($val === null || $val === '') {
                    continue;
                }

                if (in_array($q->type, ['single_choice', 'multiple_choice'])) {
                    $valArray = is_array($val) ? $val : array_map('trim', explode(',', (string)$val));
                    foreach ($valArray as $selectedOpt) {
                        if (isset($optionCounts[$selectedOpt])) {
                            $optionCounts[$selectedOpt]++;
                        } else {
                            foreach ($optionCounts as $optKey => $cnt) {
                                if (trim($optKey) === trim($selectedOpt)) {
                                    $optionCounts[$optKey]++;
                                    break;
                                }
                            }
                        }
                    }
                } elseif ($q->type === 'rating') {
                    if (is_numeric($val)) {
                        $ratingSum += (float)$val;
                        $ratingCount++;
                    }
                } elseif ($q->type === 'text') {
                    $textAnswers[] = [
                        'user_name'    => $response->user->full_name ?? ($response->user->name ?? 'Công dân'),
                        'text'         => is_array($val) ? implode(', ', $val) : (string)$val,
                        'submitted_at' => $response->submitted_at ? $response->submitted_at->format('H:i d/m/Y') : $response->created_at->format('H:i d/m/Y'),
                    ];
                }
            }

            $avgRating = $ratingCount > 0 ? round($ratingSum / $ratingCount, 1) : 0;

            return [
                'question'      => $q,
                'option_counts' => $optionCounts,
                'rating_avg'    => $avgRating,
                'rating_count'  => $ratingCount,
                'text_answers'  => $textAnswers,
            ];
        });

        return view('frontend.surveys.show', compact('survey', 'totalResponses', 'questionsData'));
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Xóa bài khảo sát thành công!');
    }
}
