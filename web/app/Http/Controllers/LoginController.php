<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{


  public function getUserFromToken()
  {
    $user = Auth::user();

    return response()->json(['user' => $user]);
  }





  public function loginUser(Request $request)
  {
    try {
      $credentials = $request->only('email', 'password');

      $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8',
      ]);

      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
      }

      $user = User::where('email', $request->email)->first();

      if ($user && password_verify($credentials['password'], $user->password)) {
        Auth::login($user);

        $tokenName = $user->nom ? $user->nom : 'DefaultTokenName';
        $token = $user->createToken($tokenName)->plainTextToken;

        $authenticatedUser = Auth::user();

        $authenticatedUser->hoa = null;

        if ($authenticatedUser->role === 'cindik') {
          $hoa = Hoa::where('user_id', $authenticatedUser->id)->first();
          $authenticatedUser->hoa = true;
          if ($hoa) {
            return response()->json(['user' => $authenticatedUser, 'token' => $token, 'hoa' => true], 200);
          }
        }
        return response()->json(['user' => $authenticatedUser, 'token' => $token], 200);
      } else {
        return response()->json(['error' => 'Email or password is incorrect'], 401);
      }
    } catch (\Exception $e) {

      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
