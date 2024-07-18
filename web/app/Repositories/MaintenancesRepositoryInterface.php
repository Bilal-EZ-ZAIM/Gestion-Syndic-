<?php


// app/Repositories/MaintenancesRepositoryInterface.php
namespace App\Repositories;

use Illuminate\Http\Request;

interface MaintenancesRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}


