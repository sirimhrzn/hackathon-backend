<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorResource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $vendor_id = $request->route('vendor_id');
        if ($vendor_id == null) {
            throw new Exception("Invalid Vendor", 500);
        }
        config(['request.vendor_id' => $vendor_id]);
        $request->merge([
            'vendor_id' => $vendor_id
        ]);
        return $next($request);
    }
}
