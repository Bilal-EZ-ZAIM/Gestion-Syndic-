<?php

// app/Services/ResidenceServiceInterface.php
namespace App\Services;

use Illuminate\Http\Request;

interface MaintenancesServiceInterface
{
    public function index();
    public function store(Request $request);
    public function update(Request $request);
    public function destroy(Request $request);
}
