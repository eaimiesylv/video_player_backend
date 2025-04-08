<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HeaderCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $platform = $request->header('platform');

        $type = $request->header('type');

        if (!in_array($platform, ['ios', 'web', 'android'])) {

            return response()->json(['message' => 'accepted platform Header are ios, web, android'], 400);
        }

        if (!in_array($type, ['sender', 'angel', 'guest'])) {

            return response()->json(['message' => 'accepted type Header are sender, angel, guest'], 400);
        }
        return $next($request);
    }
}
