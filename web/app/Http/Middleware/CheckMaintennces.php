<?php

namespace App\Http\Middleware;

use App\Models\Maintenances;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintennces
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$params)
    {
        $user = Auth::user();

        try {
            $maintenance = Maintenances::findOrFail($params[0]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Maintenance record not found'], 404);
        }

        if ($maintenance->user_id !== $user->id) {
            return response()->json(['error' => 'This user does not belong to the same HOA'], 400);
        }

        return $next($request);
    }
}
