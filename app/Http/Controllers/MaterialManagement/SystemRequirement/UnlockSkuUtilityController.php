<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnlockSkuUtilityController extends Controller
{
    /**
     * Display the unlock SKU utility page
     */
    public function index()
    {
        return inertia('material-management/system-requirement/inventory-setup/UnlockSkuUtility');
    }

    /**
     * Get locked SKUs with pagination and search
     */
    public function getLockedSkus(Request $request)
    {
        try {
            $request->validate([
                'search' => 'nullable|string|max:100',
                'per_page' => 'nullable|integer|min:10|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            $query = MmSku::with(['category', 'skuType'])
                ->where('is_locked', true)
                ->orderBy('locked_at', 'desc');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('sku', 'like', "%{$search}%")
                      ->orWhere('sku_name', 'like', "%{$search}%")
                      ->orWhere('locked_by', 'like', "%{$search}%")
                      ->orWhere('lock_reason', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $skus = $query->paginate($perPage);

            // Add lock duration to each SKU
            $skus->getCollection()->transform(function ($sku) {
                $sku->lock_duration = $sku->lock_duration;
                $sku->is_stale = $sku->isLockStale();
                return $sku;
            });

            return response()->json($skus);
        } catch (\Exception $e) {
            Log::error('Error in getLockedSkus', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'message' => 'Failed to load locked SKUs: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Unlock a single SKU
     */
    public function unlockSku(Request $request, $sku)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $skuModel = MmSku::findOrFail($sku);

            if (!$skuModel->is_locked) {
                return response()->json([
                    'message' => 'SKU is not locked',
                    'success' => false
                ], 400);
            }

            // Log the unlock action
            Log::info('SKU unlocked via utility', [
                'sku' => $sku,
                'sku_name' => $skuModel->sku_name,
                'previous_locked_by' => $skuModel->locked_by,
                'previous_lock_reason' => $skuModel->lock_reason,
                'previous_locked_at' => $skuModel->locked_at,
                'unlock_reason' => $request->reason,
                'unlocked_by' => Auth::check() ? (Auth::user()->name ?? 'System') : 'System',
                'user_id' => Auth::id(),
                'timestamp' => now(),
            ]);

            // Unlock the SKU
            $skuModel->unlock();

            DB::commit();

            return response()->json([
                'message' => "SKU $sku has been successfully unlocked",
                'success' => true,
                'sku' => $skuModel->fresh(['category', 'skuType'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error unlocking SKU', [
                'sku' => $sku,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to unlock SKU: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Bulk unlock multiple SKUs
     */
    public function bulkUnlock(Request $request)
    {
        $request->validate([
            'skus' => 'required|array',
            'skus.*' => 'required|string|exists:mm_skus,sku',
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $skus = $request->skus;
            $reason = $request->reason;
            $unlockedCount = 0;
            $failedSkus = [];

            foreach ($skus as $skuCode) {
                $skuModel = MmSku::find($skuCode);
                
                if (!$skuModel->is_locked) {
                    $failedSkus[] = [
                        'sku' => $skuCode,
                        'reason' => 'SKU is not locked'
                    ];
                    continue;
                }

                // Log the unlock action
                Log::info('SKU unlocked via bulk utility', [
                    'sku' => $skuCode,
                    'sku_name' => $skuModel->sku_name,
                    'previous_locked_by' => $skuModel->locked_by,
                    'previous_lock_reason' => $skuModel->lock_reason,
                    'unlock_reason' => $reason,
                    'unlocked_by' => Auth::check() ? (Auth::user()->name ?? 'System') : 'System',
                    'user_id' => Auth::id(),
                    'timestamp' => now(),
                    'bulk_operation' => true
                ]);

                $skuModel->unlock();
                $unlockedCount++;
            }

            DB::commit();

            $message = "Successfully unlocked $unlockedCount SKU(s)";
            if (!empty($failedSkus)) {
                $message .= ". Failed to unlock " . count($failedSkus) . " SKU(s)";
            }

            return response()->json([
                'message' => $message,
                'success' => true,
                'unlocked_count' => $unlockedCount,
                'failed_skus' => $failedSkus
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error in bulk unlock operation', [
                'skus' => $request->skus,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to perform bulk unlock: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Unlock stale locks (older than 30 minutes)
     */
    public function unlockStaleLocks(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $staleSkus = MmSku::where('is_locked', true)
                ->where('locked_at', '<', now()->subMinutes(30))
                ->get();

            $unlockedCount = 0;

            foreach ($staleSkus as $sku) {
                // Log the stale lock unlock
                Log::info('Stale SKU lock unlocked', [
                    'sku' => $sku->sku,
                    'sku_name' => $sku->sku_name,
                    'previous_locked_by' => $sku->locked_by,
                    'lock_duration_minutes' => $sku->lock_duration,
                    'unlock_reason' => $request->reason,
                    'unlocked_by' => Auth::check() ? (Auth::user()->name ?? 'System') : 'System',
                    'user_id' => Auth::id(),
                    'timestamp' => now(),
                    'stale_lock' => true
                ]);

                $sku->unlock();
                $unlockedCount++;
            }

            DB::commit();

            return response()->json([
                'message' => "Successfully unlocked $unlockedCount stale SKU lock(s)",
                'success' => true,
                'unlocked_count' => $unlockedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error unlocking stale locks', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to unlock stale locks: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Get lock statistics
     */
    public function getLockStatistics()
    {
        $totalLocked = MmSku::where('is_locked', true)->count();
        $staleLocks = MmSku::where('is_locked', true)
            ->where('locked_at', '<', now()->subMinutes(30))
            ->count();
        
        $lockReasons = MmSku::where('is_locked', true)
            ->selectRaw('lock_reason, COUNT(*) as count')
            ->groupBy('lock_reason')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $recentLocks = MmSku::where('is_locked', true)
            ->where('locked_at', '>=', now()->subHours(24))
            ->count();

        return response()->json([
            'total_locked' => $totalLocked,
            'stale_locks' => $staleLocks,
            'recent_locks' => $recentLocks,
            'lock_reasons' => $lockReasons
        ]);
    }
} 