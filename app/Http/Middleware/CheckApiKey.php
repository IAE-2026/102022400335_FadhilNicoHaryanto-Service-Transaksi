<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-IAE-KEY');

        if ($apiKey !== '102022400335') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => null,
                'meta' => null,
                'errors' => ['Invalid or missing API Key']
            ], 401);
        }

        return $next($request);
    }
}