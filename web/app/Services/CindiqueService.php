<?php

namespace App\Services;


use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Validator;

class CindiqueService implements CindiqueServiceInterface
{
    public function index()
    {

        $cindique = User::where('role', 'cindik')->get();
        return response()->json(['cindique' => $cindique], 200);
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
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:2', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
                'phone' => ['required', 'string', 'max:20'],

            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $avatarName = null;
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
                $avatarPath = public_path('/images/');
                $avatar->move($avatarPath, $avatarName);
            }

            $cindique = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $avatarName,
                'role' => 'cindik',
                'phone' => $request->phone,
            ]);


            $data = [
                'username' => $request->name,
                'password' => $request->password,
            ];

            Mail::to($request->email)->send(new WelcomeMail('Bienvenue sur Manager Cindique - Accédez à votre compte maintenant', $data));


            return response()->json(['message' => 'Cindique created successfully', 'cindique' => $cindique], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }



    public function update(Request $request)
    {
        try {
            $cindique = User::findOrFail($request->id);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:5', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $cindique->update($request->only(['name']));

            return response()->json(['message' => 'Resident updated successfully', 'cindique' => $cindique], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Resident not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }





    public function destroy($id)
    {
        $cindiques = User::findOrFail($id);
        $cindiques->delete();
        $cindique = User::where('role', 'cindik')->get();
        return response()->json(['message' => 'Cindique deleted successfully',  'cindique' => $cindique], 200);
    }
}
