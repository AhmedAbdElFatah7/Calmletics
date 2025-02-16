<?php

namespace App\Http\Controllers\player;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FreeCommunityController extends Controller
{
    public function join(Request $request)
    {
        $user = User::find(auth()->id());
        // $validator = Validator::make($request->all(), [
        //     'com_free_id' => 'required|integer|min:0',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
        // $user->com_free_id = $request->com_free_id;
        $user->com_free_id = 1;
        $user->save();
        return response()->json(
            ['message' => 'Joined successfully']
            , 200
        );
    }


    public function community(Request $request)
    {
        $user = User::find(auth()->id());
        $com_free_id = $user->comfree->id;
        $users = user::where('com_free_id', $com_free_id)->where('id', '!=', $user->id)->select('name','image')->get();
        return response()->json($users);
    }
    
    public function leaderboard(Request $request)
    {
        $user = User::find(auth()->id());
        $time = $request->time;
        $topUsersByDay = DB::table('plandates')
        ->join('users', 'plandates.user_id', '=', 'users.id')
        ->select('users.name', 'users.image', 'users.flag', 'plandates.user_id', DB::raw('SUM(plandates.score) as total_score'))
        ->whereDate('plandates.date', now()->toDateString())
        ->groupBy('plandates.user_id', 'users.name', 'users.image', 'users.flag')
        ->orderByDesc('total_score')
        ->get();
    
    $topUsersByWeek = DB::table('plandates')
        ->join('users', 'plandates.user_id', '=', 'users.id')
        ->select('users.name', 'users.image', 'users.flag', 'plandates.user_id', DB::raw('SUM(plandates.score) as total_score'))
        ->whereBetween('plandates.date', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('plandates.user_id', 'users.name', 'users.image', 'users.flag')
        ->orderByDesc('total_score')
        ->get();
    
    $topUsersAllTime = DB::table('plandates')
        ->join('users', 'plandates.user_id', '=', 'users.id')
        ->select('users.name', 'users.image', 'users.flag', 'plandates.user_id', DB::raw('SUM(plandates.score) as total_score'))
        ->groupBy('plandates.user_id', 'users.name', 'users.image', 'users.flag')
        ->orderByDesc('total_score')
        ->get();
    
    
        if ($time === 'daily') {
            return response()->json([$topUsersByDay,'user_id'=>$user->id]);
        } elseif ($time === 'weekly') {
            return response()->json([$topUsersByWeek,'user_id'=>$user->id]);
        } else {
            return response()->json([$topUsersAllTime,'user_id'=>$user->id]);
        }
    }
    public function plan (){
        $user = User::find(auth()->id());
        if ($user->com_free_id == null) {
            $plan = $user->compre->plan;
            return response()->json($plan);
        }
        $plan = $user->comFree->plan;
        return response()->json($plan);
    }
}