<?php

namespace App\Http\Controllers;

use App\Models\Hoa;
use App\Services\HoaServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HOAController extends Controller
{
    protected $hoaService;

    public function __construct(HoaServiceInterface $hoaService)
    {
        $this->hoaService = $hoaService;
    }

    public function getviewHoaFormCreate()
    {
        $user = Auth::user();
        $hoa = Hoa::where('user_id', $user->id)->first();

        if ($hoa) {
            return view('HOA.hoa-details');
        }
        
        return view('HOA.forms-HOA');
    }

    public function getviewHoa()
    {
        return $this->hoaService->getviewHoa();
    }

    public function getHOA()
    {
        try {
            $hoa = $this->hoaService->getHOA();
            return response()->json($hoa);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $hoa = $this->hoaService->createHoa($request->all());
            return response()->json(['success' => 'HOA created successfully', 'hoa' => $hoa], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $hoa = $this->hoaService->updateHoa($request->all());
            return response()->json(['success' => 'HOA updated successfully', 'hoa' => $hoa], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->hoaService->deleteHoa($id);
            return response()->json(['success' => 'HOA deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
