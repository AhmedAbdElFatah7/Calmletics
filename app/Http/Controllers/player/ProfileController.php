<?php

namespace App\Http\Controllers\player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    
    public function getUserInfo(Request $request)
{
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['error' => 'Unauthorized. Token not provided.'], 401);
    }

    try {
        $user = JWTAuth::authenticate($token);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Invalid or expired token'], 401);
    }
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'score' => $user->score,
        'role' => $user->role,
    ], 200);
}    

public function updateimage(Request $request)
{
    $user = User::find(auth()->id());
    $user->image = $request->image;
    $user->save();
    return response()->json([
        'message' => 'Image updated successfully',
        'image' => $user->image,
    ], 200);


}

public function editprofile(Request $request)
{
    $user = User::find(auth()->id());
    $user->name = $request->name;
    $user->email = $request->email;
    $user->flag=$request->flag;
    $user->save();
    return response()->json([
        'message' => 'Profile updated successfully',
        'name' => $user->name,
        'email' => $user->email,
        'flag' => $user->flag,
    ], 200);
}

public function logout()
{
    try {
        $user = JWTAuth::parseToken()->authenticate(); 

        if ($user) {
            $user->delete();
        }

        JWTAuth::invalidate(JWTAuth::getToken()); 

        return response()->json(['message' => 'User successfully logged out and deleted']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}

public function logoutcom(Request $request)
{
    try {
        $user = User::find(auth()->id());

        if ($user) {
            $user->com_free_id = null;
            $user->com_pre_id = null;
            $user->save(); 

            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'User successfully logged out and fields reset']);
        }

        return response()->json(['error' => 'User not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong', 'details' => $e->getMessage()], 500);
    }
}

public function updatepassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::find(auth()->id());

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
        'message' => 'Password updated successfully',
    ], 200);
}


}