<?php

namespace App\Http\Controllers;

use App\Services\CindiqueServiceInterface;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Validator;

class CindiqueController extends Controller
{

    protected $cindiqueService;

    public function __construct(CindiqueServiceInterface $cindiqueService)
    {
        $this->cindiqueService = $cindiqueService;
    }

    public function index()
    {

        return $this->cindiqueService->index();
    }

    public function show($id)
    {
        return $this->cindiqueService->show($id);
    }

    public function getCreate()
    {
        return view('HOA.createRe');
    }

    public function getCindikView()
    {
        return view('admin.tables-cindique');
    }


    public function store(Request $request)
    {
        return $this->cindiqueService->store($request);
    }



    public function update(Request $request)
    {
        return $this->cindiqueService->update($request);
    }





    public function destroy($id)
    {
        return $this->cindiqueService->destroy($id);
    }
}
