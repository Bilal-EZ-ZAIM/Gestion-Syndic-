<?php

namespace App\Http\Middleware;

use App\Models\Hoa;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckResident
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$params)
    {
        
        $hoa = Hoa::where('user_id', auth()->user()->id)->first();

        if (!$hoa) {
            return response()->json(['error' => 'HOA not found for the authenticated user'], 404);
        }

        try {
            $resident = User::findOrFail($params[0]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Resident not found'], 404);
        }

        if ($resident->hoa_id != $hoa->id) {
            return response()->json(['error' => 'This user does not belong to the same HOA'], 400);
        }

        return $next($request);
    }

    // public function handle(Request $request, Closure $next, ...$params)
    // {
    //     // قم باستخدام البيانات الممررة إلى الـ Middleware هنا
    //     // مثلاً: $params[0] إذا كانت المعلمة الأولى
    //     if ($params[0] === 'someValue') {
    //         // قم بكتابة منطق التحقق الخاص بك
    //     }

    //     return $next($request);
    // }
}
