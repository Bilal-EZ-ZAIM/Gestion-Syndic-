<?php

namespace App\Http\Middleware;

use App\Models\Hoa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckHOA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $hoa = Hoa::where('user_id', $user->id)->first();

        if ($hoa) {
            return $next($request);
        }

        return response()->json(['error' => 'HOA not found for the authenticated user'], 404);
    }
}
