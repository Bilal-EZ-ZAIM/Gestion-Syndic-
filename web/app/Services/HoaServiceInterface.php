<?php

namespace App\Services;

use Illuminate\Http\Request;

interface HoaServiceInterface
{
    public function getviewHoa();
    public function getHOA();
    public function createHoa(array $data);
    public function updateHoa(array $data);
    public function deleteHoa(int $id);
}
