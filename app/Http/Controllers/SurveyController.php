<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function create()
    {
        return view('create_survey');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required',
            'questions.*.type' => 'required|in:multiple_choice,true_false,simple_answer',
            'questions.*.choices' => 'required_if:questions.*.type,multiple_choice',
        ]);

        $survey = Survey::create([
            'title' => $request->title,
            'description' => $request->description,
            'questions' => $request->questions,
        ]);

        return view('create_survey')->with('success', 'Survey created successfully.');
    }
    public function answer($id)
    {
        $survey = Survey::findOrFail($id);
        return view('answer_survey', compact('survey'));
    }
    public function submit(Request $request, $id)
    {
        // Validate the request...

        // Process the answers...
        $answers = $request->answers;

        foreach ($answers as $index => $answer) {
            if (is_array($answer)) {
                $answers[$index] = implode(',', $answer);
            }
        }

        // Store the survey response...
        $surveyResponse = SurveyResponse::create([
            'survey_id' => $id,
            // ...

            'patient_id' => Auth::id(), // replace $patientId with the actual patient ID
            'answers' => json_encode($answers),
        ]);

        // Redirect back to the list of surveys...
        return redirect('/patient/surveys');
    }
}
