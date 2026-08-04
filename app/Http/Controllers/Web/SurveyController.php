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

        return view('frontend.surveys.show', compact('survey', 'totalResponses'));
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()->route('surveys.index')->with('success', 'Xóa bài khảo sát thành công!');
    }
}
