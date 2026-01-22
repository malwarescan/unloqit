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
     * 1. Legacy Cleveland URLs -> /locksmith/oh/cleveland
     * 2. Trailing slash policy (no trailing slash)
     * 3. Lowercase slugs only
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        $originalPath = $path;
        
        // 1. Legacy Cleveland URLs -> new canonical structure
        if (preg_match('#^cleveland-locksmith(/.*)?$#', $path)) {
            $rest = substr($path, strlen('cleveland-locksmith'));
            $canonical = 'locksmith/oh/cleveland' . $rest;
            return redirect('/' . $canonical, 301);
        }
        
        // 2. Remove trailing slash (except for root)
        if ($path !== '' && $path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        
        // 3. Lowercase normalization (except query string)
        $pathParts = explode('/', $path);
        $lowercasePath = array_map('strtolower', $pathParts);
        $normalizedPath = implode('/', $lowercasePath);
        
        // If path changed, redirect to lowercase version
        if ($normalizedPath !== $path) {
            $queryString = $request->getQueryString();
            $redirectUrl = '/' . $normalizedPath;
            if ($queryString) {
                $redirectUrl .= '?' . $queryString;
            }
            return redirect($redirectUrl, 301);
        }
        
        // If original path had trailing slash or was uppercase, redirect
        if ($originalPath !== $normalizedPath) {
            $queryString = $request->getQueryString();
            $redirectUrl = '/' . $normalizedPath;
            if ($queryString) {
                $redirectUrl .= '?' . $queryString;
            }
            return redirect($redirectUrl, 301);
        }
        
        return $next($request);
    }
}
