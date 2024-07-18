<?php

namespace App\Http\Controllers;

use App\Services\MaintenancesServiceInterface;
use Illuminate\Http\Request;

class MaintenancesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $maintenancesService;

    public function __construct(MaintenancesServiceInterface $maintenancesService)
    {
        $this->maintenancesService = $maintenancesService;
    }

    public function index()
    {
        return $this->maintenancesService->index();
    }

    public function getViewMantenace()
    {
        return view('maintances.tables-gridjs');
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->maintenancesService->store($request);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        return $this->maintenancesService->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        return $this->maintenancesService->destroy($request);
    }
}
