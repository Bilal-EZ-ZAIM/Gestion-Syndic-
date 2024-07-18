<?php

namespace App\Http\Controllers;

use App\Services\ResidenceServiceInterface;
use Illuminate\Http\Request;

class ResedenceController extends Controller
{
    protected $resedenceService;

    public function __construct(ResidenceServiceInterface $resedenceService)
    {
        $this->resedenceService = $resedenceService;
    }

    public function getViewResedence()
    {
        return $this->resedenceService->getViewResidence();
    }

    public function index()
    {
        return $this->resedenceService->index();
    }

    public function show($id)
    {
        return $this->resedenceService->show($id);
    }

    public function getCreate()
    {
        return $this->resedenceService->getCreate();
    }

    public function store(Request $request)
    {
        return $this->resedenceService->store($request);
    }

    public function update(Request $request)
    {
        return $this->resedenceService->update($request);
    }

    public function destroy(Request $request)
    {
        return $this->resedenceService->destroy($request);
    }
}
