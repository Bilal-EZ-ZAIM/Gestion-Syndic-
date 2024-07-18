<?php

namespace App\Services;

use App\Http\Middleware\CheckMaintennces;
use App\Models\Hoa;
use App\Models\Maintenances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MaintenancesService implements MaintenancesServiceInterface
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $maintenances = Maintenances::where('user_id', $user->id)->get();
        return response()->json(['maintenances' =>  $maintenances], 200);
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $hoa = Hoa::where('user_id', $user->id)->first();

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:255|min:3',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'facture' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $imageUrl = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if ($image->isValid()) {
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads'), $imageName);
                    $imageUrl = url('uploads/' . $imageName);
                } else {
                    return response()->json(['error' => "An error occurred while uploading the image."], 500);
                }
            }

            $maintenances = Maintenances::create([
                'title' => $request->title,
                'description' => $request->description,
                'facture' => $request->facture,
                'user_id' => $user->id,
                'image' => $imageUrl,
                'hoa_id' => $hoa->id,
            ]);

            if ($maintenances) {
                return response()->json(['success' => 'Maintenances created successfully', 'maintenances' => $maintenances], 201);
            } else {
                return response()->json(['error' => 'Failed to create Maintenances'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {

            $response = app()->make(CheckMaintennces::class)->handle($request, function ($request) {
                return null;
            }, $request->id);

            if ($response) {
                return $response;
            }

            $maintenancesUpdate = Maintenances::findOrFail($request->id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:255|min:3',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'facture' => 'numeric|min:0'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }


            $maintenancesUpdate->title = $request->title;
            $maintenancesUpdate->description = $request->description;
            $maintenancesUpdate->facture = $request->facture;
            $maintenancesUpdate->save();

            return response()->json(['message' => 'Maintenances updated successfully', 'maintenances' => $maintenancesUpdate], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Maintenances not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $response = app()->make(CheckMaintennces::class)->handle($request, function ($request) {
            return null;
        }, $request->id);

        if ($response) {
            return $response;
        }
        $user = Auth::user();
        $maintenancesDelet = Maintenances::findOrFail($request->id);
        $maintenancesDelet->delete();
        $maintenances = Maintenances::where('user_id', $user->id)->get();

        return response()->json(['message' => 'Maintenances deleted successfully',  'maintenances' => $maintenances], 200);
    }
}
