<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionDebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        Log::debug("DEBUG Middleware: Handling request for path: {$path}");


        if ($request->hasSession() && $request->session()->isStarted()) {
            Log::debug("DEBUG Middleware: Session is started for path: {$path}");
            try {
                Log::debug("DEBUG Middleware: Session ID: " . $request->session()->getId() . " for path: {$path}");
                Log::debug("DEBUG Middleware: Session has user ID: " . ($request->session()->has('login_web_'.sha1(Auth::getDefaultDriver())) ? 'YES' : 'NO'));
                if (Auth::check()) {
                    Log::debug("DEBUG Middleware: Auth::check() is TRUE, User ID: " . Auth::id() . " for path: {$path}");
                } else {
                    Log::debug("DEBUG Middleware: Auth::check() is FALSE for path: {$path}");
                }
                Log::debug("DEBUG Middleware: Session payload: " . json_encode($request->session()->all()) . " for path: {$path}");
            } catch (\Throwable $e) {
                Log::error("DEBUG Middleware: Error accessing session after StartSession: " . $e->getMessage() . " for path: {$path}");
            }
        } else {
            Log::debug("DEBUG Middleware: Session NOT started for path: {$path}");
        }

        return $next($request);
    }
}
