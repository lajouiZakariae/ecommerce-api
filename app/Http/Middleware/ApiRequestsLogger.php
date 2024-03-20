<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiRequestsLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $method = $request->method();
        $path = $request->path();
        $userAgent = $request->userAgent();
        $ip = $request->ip();


        $logMessage = "$method $path \"$userAgent\" $ip";

        Log::channel('requests')->info($logMessage);

        return $next($request);
    }
}
