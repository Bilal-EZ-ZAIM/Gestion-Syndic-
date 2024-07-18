<?php

// app/Services/ResidenceService.php
namespace App\Services;

use App\Http\Middleware\CheckResident;
use App\Models\Hoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\WelcomeMail;

class ResidenceService implements ResidenceServiceInterface
{
  public function getViewResidence()
  {
    return view('HOA.listResidence');
  }

  public function index()
  {
    $user = Auth::user();
    $hoa = Hoa::where('user_id', $user->id)->first();

    if (!$hoa) {
      return response()->json(['error' => 'No HOA associated with this user'], 400);
    }

    $users = User::where('hoa_id', $hoa->id)->get();

    return response()->json(['residence' => $users], 200);
  }

  public function show($id)
  {
    $user = User::findOrFail($id);
    return response()->json(['user' => $user], 200);
  }

  public function getCreate()
  {
    return view('HOA.createRe');
  }

  public function store(Request $request)
  {
    $hoa = Hoa::where('user_id', auth()->user()->id)->first();

    try {
      $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'min:3', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8'],
        'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        'phone' => ['required', 'string', 'max:20'],
        'apartment_number' => ['required', 'string', 'max:10'],
      ]);

      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
      }

      // Check if apartment_number already exists
      $existingUser = User::where('apartment_number', $request->apartment_number)
        ->where('hoa_id', $hoa->id)
        ->first();
      if ($existingUser) {
        return response()->json(['error' => 'Apartment number already exists.'], 422);
      }

      $avatarName = null;
      if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');
        $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
        $avatarPath = public_path('/images/');
        $avatar->move($avatarPath, $avatarName);
      }

      $residence = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'avatar' => $avatarName,
        'phone' => $request->phone,
        'apartment_number' => $request->apartment_number,
        'hoa_id' => $hoa->id,
        'role' => 'resident',
      ]);

      $data = [
        'username' => $request->name,
        'password' => $request->password,
      ];

      Mail::to($request->email)->send(new WelcomeMail('Bienvenue sur Manager Cindique - Accédez à votre compte maintenant', $data));

      return response()->json(['message' => 'Résidence created successfully', 'residence' => $residence], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
    }
  }

  public function update(Request $request)
  {
    try {
      $hoa = Hoa::where('user_id', auth()->user()->id)->first();
      $response = app()->make(CheckResident::class)->handle($request, function ($request) {
        return null;
      }, $request->id);

      if ($response) {
        return $response;
      }

      $resident = User::findOrFail($request->id);

      $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255', 'min:3'],
        'apartment_number' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:20'],
      ]);

      if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
      }
      // Check if apartment_number already exists
      // $existingUser = User::where('apartment_number', $request->apartment_number)
      //     ->where('hoa_id', $hoa->id)
      //     ->first();
      // if ($existingUser) {
      //     return response()->json(['error' => 'Apartment number already exists.'], 422);
      // }

      $resident->update($request->only(['name', 'apartment_number', 'phone']));

      return response()->json(['message' => 'Resident updated successfully', 'resident' => $resident], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['error' => 'Resident not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
    }
  }


  public function destroy(Request $request)
  {
    $response = app()->make(CheckResident::class)->handle($request, function ($request) {
      return null;
    }, $request->id);

    if ($response) {
      return $response;
    }

    $hoa = Hoa::where('user_id', auth()->user()->id)->first();
    $user = User::findOrFail($request->id);

    $user->delete();

    $users = User::where('hoa_id', $hoa->id)->get();

    return response()->json(['message' => 'User deleted successfully', 'residence' => $users], 200);
  }
}
