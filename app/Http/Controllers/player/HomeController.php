<?php

namespace App\Http\Controllers\player;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getScore(Request $request){
        $user = User::find(auth()->id());

        if (!$user) {
            return response()->json(['error' => 'Unauthorized. Token might be invalid or expired.'], 401);
        }
    
        $validator = Validator::make($request->all(), [
            'score' => 'required|integer|min:0',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $user->score = $request->score;
        $user->save(); 
    
        return response()->json([
            'message' => 'Score Stored successfully',
            'score' => $user->score,
        ], 200);
    }
    
    public function userPlan (){
        $user = User::find(auth()->id());
        if ($user->com_free_id == null && $user->com_pre_id == null) {
            $plan = $user->plan;
            return response()->json($plan);
        }
        if ($user->com_free_id == null) {
            $plan = $user->compre->plan;
            return response()->json($plan);
        }
        $plan = $user->comFree->plan;
        return response()->json($plan);
    }


    public function image(Request $request)
    {
        $user = User::find(auth()->id());
        
        $validator = Validator::make($request->all(), [
            'image' => 'required|', 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user->image = $request->image;
        $user->save();

        return response()->json(['message' => 'Image stored successfully'], 200);
    }
    public function flag(Request $request)
    {
        $user = User::find(auth()->id());
        $validator = Validator::make($request->all(), [
            'flag' => 'required|', 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user->flag = $request->flag;
        $user->save();

        return response()->json(['message' => 'flag stored successfully'], 200);
    }
}
