<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalRedirect
{
    /**
     * Handle an incoming request.
     * 
     * Ensures canonical URLs:
     * 1. Cleveland uses /cleveland-locksmith, not /locksmith/cleveland
     * 2. Trailing slash policy (no trailing slash)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        // Redirect /locksmith/cleveland to /cleveland-locksmith (canonical)
        if (preg_match('#^locksmith/cleveland(/.*)?$#', $path)) {
            $rest = substr($path, strlen('locksmith/cleveland'));
            $canonical = 'cleveland-locksmith' . $rest;
            return redirect('/' . $canonical, 301);
        }
        
        // Remove trailing slash (except for root)
        if ($path !== '' && $path !== '/' && str_ends_with($path, '/')) {
            $canonical = rtrim($path, '/');
            return redirect('/' . $canonical, 301);
        }
        
        return $next($request);
    }
}
