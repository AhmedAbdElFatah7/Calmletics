<?php

namespace App\Http\Controllers\Answer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function storeAnswers(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized. Token might be invalid or expired.'], 401);
    }

    $existingAnswer = Answer::where('user_id', $user->id)->first();
    
    if ($existingAnswer) {
        return response()->json(['error' => 'You have already submitted an answer.'], 403);
    }

    $validator = Validator::make($request->all(), [
        'Age' => 'required|string',
        'Years_of_Excersie_Experince' => 'required|string',
        'Weekly_Anxiety' => 'required|string',
        'Daily_App_Usage' => 'required|string',
        'Comfort_in_Social_Situations' => 'required|string',
        'Competition_Level' => 'required|string',
        'gender' => 'required|string',
        'Current_Status' => 'required|string',
        'Feeling_Anxious' => 'required|string',
        'Preferred_Anxiety_Treatment' => 'required|string',
        'Handling_Anxiety_Situations' => 'required|string',
        'General_Mood' => 'required|string',
        'Preferred_Content' => 'required|string',
        'Online_Interaction_Over_Offline' => 'required|string',
        'score' => 'nullable|integer|min:0|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $answer = Answer::updateOrCreate(
        ['user_id' => $user->id], 
        [
            'Age' => $request->Age,
            'Years_of_Excersie_Experince' => $request->Years_of_Excersie_Experince,
            'Weekly_Anxiety' => $request->Weekly_Anxiety,
            'Daily_App_Usage' => $request->Daily_App_Usage,
            'Comfort_in_Social_Situations' => $request->Comfort_in_Social_Situations,
            'Competition_Level' => $request->Competition_Level,
            'gender' => $request->gender,
            'Current_Status' => $request->Current_Status,
            'Feeling_Anxious' => $request->Feeling_Anxious,
            'Preferred_Anxiety_Treatment' => $request->Preferred_Anxiety_Treatment,
            'Handling_Anxiety_Situations' => $request->Handling_Anxiety_Situations,
            'General_Mood' => $request->General_Mood,
            'Preferred_Content' => $request->Preferred_Content,
            'Online_Interaction_Over_Offline' => $request->Online_Interaction_Over_Offline,
        ]
    );
    

    return response()->json([
        'message' => 'Answers stored successfully',
        'answers' => collect($answer),
    ], 200);
}


public function getUserAnswer()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized. Token might be invalid or expired.'], 401);
    }

    $answer = Answer::where('user_id', $user->id)->first();

    if (!$answer) {
        return response()->json(['message' => 'No answer found for this user'], 404);
    }

    return response()->json([
          collect($answer)->except(['id', 'user_id', 'created_at', 'updated_at']),
    ], 200);
}
}
