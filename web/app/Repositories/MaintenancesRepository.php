<?php
// app/Repositories/MaintenancesRepository.php
namespace App\Repositories;

use App\Models\Maintenance;
use App\Models\Maintenances;

class MaintenancesRepository implements MaintenancesRepositoryInterface
{
    public function all()
    {
        return Maintenances::all();
    }

    public function create(array $data)
    {
        return Maintenances::create($data);
    }

    public function update($id, array $data)
    {
        $maintenance = Maintenances::findOrFail($id);
        $maintenance->update($data);
        return $maintenance;
    }

    public function delete($id)
    {
        $maintenance = Maintenances::findOrFail($id);
        $maintenance->delete();
        return $maintenance;
    }
}
