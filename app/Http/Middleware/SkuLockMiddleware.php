<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MmSku;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SkuLockMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // This middleware can be used to automatically lock SKUs during certain operations
        // For now, it's just a placeholder to demonstrate the concept
        
        $response = $next($request);
        
        // Example: Lock SKU during update operations
        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $skuCode = $request->route('sku');
            
            if ($skuCode) {
                $sku = MmSku::find($skuCode);
                if ($sku && !$sku->is_locked) {
                    // Lock the SKU during update
                    $sku->lock('Transaction in progress - Update operation');
                    
                    Log::info('SKU locked during update operation', [
                        'sku' => $skuCode,
                        'user' => Auth::check() ? Auth::user()->name : 'System',
                        'timestamp' => now()
                    ]);
                }
            }
        }
        
        return $response;
    }
} 