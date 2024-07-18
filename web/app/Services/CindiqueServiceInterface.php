<?php

// app/Services/ResidenceServiceInterface.php
namespace App\Services;

use Illuminate\Http\Request;

interface CindiqueServiceInterface
{
    public function index();
    public function show($id);
    public function getCreate();
    public function store(Request $request);
    public function update(Request $request);
    public function destroy(Request $request);
}
