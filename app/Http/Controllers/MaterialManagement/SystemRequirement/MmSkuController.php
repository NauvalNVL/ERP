<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmSku;
use App\Models\MmCategory;
use App\Models\MmUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MmSkuController extends Controller
{
    public function index(Request $request)
    {
        $query = MmSku::with('category');
        
        // Apply filters
        if ($request->has('sku')) {
            $query->where('sku', 'like', '%' . $request->sku . '%');
        }
        
        if ($request->has('category_code')) {
            $query->where('category_code', $request->category_code);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // Apply sorting
        $sortBy = $request->input('sort_by', 'sku');
        $sortDir = $request->input('sort_dir', 'asc');
        
        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['sku', 'sku_name', 'category_code', 'type', 'uom', 'boh', 'fpo', 'rol', 'is_active'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'sku'; // Default sort field
        }
        
        // Special case for category + sku sorting (common in desktop app)
        if ($sortBy === 'cat_sku') {
            $query->orderBy('category_code', $sortDir)
                  ->orderBy('sku', $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }
        
        $skus = $query->get();
        return response()->json($skus);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:20|unique:mm_skus,sku',
            'sts' => 'nullable|string|max:10',
            'sku_name' => 'required|string|max:150',
            'category_code' => 'required|string|max:20|exists:mm_categories,code',
            'type' => 'nullable|string|max:10',
            'uom' => 'nullable|string|max:10',
            'boh' => 'nullable|numeric',
            'fpo' => 'nullable|numeric',
            'rol' => 'nullable|numeric',
            'total_part' => 'nullable|integer',
            'min_qty' => 'nullable|numeric',
            'max_qty' => 'nullable|numeric',
            'additional_name' => 'nullable|string|max:200',
            'part_number1' => 'nullable|string|max:100',
            'part_number2' => 'nullable|string|max:100',
            'part_number3' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $sku = MmSku::create($validated);
        return response()->json($sku);
    }

    public function update(Request $request, $sku)
    {
        $validated = $request->validate([
            'sts' => 'nullable|string|max:10',
            'sku_name' => 'required|string|max:150',
            'category_code' => 'required|string|max:20|exists:mm_categories,code',
            'type' => 'nullable|string|max:10',
            'uom' => 'nullable|string|max:10',
            'boh' => 'nullable|numeric',
            'fpo' => 'nullable|numeric',
            'rol' => 'nullable|numeric',
            'total_part' => 'nullable|integer',
            'min_qty' => 'nullable|numeric',
            'max_qty' => 'nullable|numeric',
            'additional_name' => 'nullable|string|max:200',
            'part_number1' => 'nullable|string|max:100',
            'part_number2' => 'nullable|string|max:100',
            'part_number3' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $skuModel = MmSku::findOrFail($sku);
        $skuModel->update($validated);
        return response()->json($skuModel);
    }

    public function destroy($sku)
    {
        $skuModel = MmSku::findOrFail($sku);
        $skuModel->delete();
        return response()->json(['message' => 'SKU deleted successfully']);
    }

    public function show($sku)
    {
        $skuModel = MmSku::with('category')->findOrFail($sku);
        return response()->json($skuModel);
    }

    public function getCategories()
    {
        $categories = MmCategory::all();
        return response()->json($categories);
    }

    public function getUnits()
    {
        $units = MmUnit::all();
        return response()->json($units);
    }

    public function getTypes()
    {
        $types = MmSku::distinct()->pluck('type')->filter();
        return response()->json($types);
    }

    public function print()
    {
        $skus = MmSku::with('category')->get();
        return view('material-management.system-requirement.inventory-setup.sku.print', compact('skus'));
    }
    
    public function printSelected(Request $request)
    {
        $ids = $request->query('ids', '');
        $skuIds = explode(',', $ids);
        
        $skus = MmSku::with('category')
            ->whereIn('sku', $skuIds)
            ->get();
            
        return view('material-management.system-requirement.inventory-setup.sku.print', compact('skus'));
    }
    
    public function changeSkuCode(Request $request, $oldSku)
    {
        $validated = $request->validate([
            'new_sku' => 'required|string|max:20|unique:mm_skus,sku',
            'reason' => 'required|string|in:correction,standardization,merge,restructure',
            'notes' => 'nullable|string|max:255',
            'merge_from' => 'nullable|string|max:20|exists:mm_skus,sku',
        ]);
        
        $newSku = $validated['new_sku'];
        $reason = $validated['reason'];
        $notes = $validated['notes'] ?? '';
        
        try {
            DB::beginTransaction();
            
            // Find the existing SKU record
            $skuModel = MmSku::findOrFail($oldSku);
            
            // Handle different cases based on reason
            switch ($reason) {
                case 'correction':
                    // Simple correction - just change the SKU code
                    $newSkuModel = $skuModel->replicate();
                    $newSkuModel->sku = $newSku;
                    $newSkuModel->save();
                    
                    // Delete the old record
                    $skuModel->delete();
                    
                    $message = 'SKU code corrected successfully';
                    break;
                    
                case 'standardization':
                    // Standardization - change the SKU code to follow standard format
                    $newSkuModel = $skuModel->replicate();
                    $newSkuModel->sku = $newSku;
                    $newSkuModel->save();
                    
                    // Delete the old record
                    $skuModel->delete();
                    
                    $message = 'SKU code standardized successfully';
                    break;
                    
                case 'merge':
                    // Merge two SKUs - validate that merge_from is provided
                    if (empty($validated['merge_from'])) {
                        throw new \Exception('Merge source SKU is required for merge operation');
                    }
                    
                    // Check that merge_from is not the same as oldSku
                    if ($validated['merge_from'] === $oldSku) {
                        throw new \Exception('Cannot merge a SKU with itself');
                    }
                    
                    // Find the SKU to merge from
                    $mergeFromSku = MmSku::findOrFail($validated['merge_from']);
                    
                    // Create new SKU with combined data
                    $newSkuModel = $skuModel->replicate();
                    $newSkuModel->sku = $newSku;
                    
                    // Combine quantities if appropriate
                    $newSkuModel->boh = $skuModel->boh + $mergeFromSku->boh;
                    $newSkuModel->fpo = $skuModel->fpo + $mergeFromSku->fpo;
                    
                    // Use the higher ROL value
                    $newSkuModel->rol = max($skuModel->rol, $mergeFromSku->rol);
                    
                    // Save the new combined SKU
                    $newSkuModel->save();
                    
                    // Delete both old records
                    $skuModel->delete();
                    $mergeFromSku->delete();
                    
                    $message = 'SKUs merged successfully into new SKU';
                    break;
                    
                case 'restructure':
                    // Restructuring - change the SKU code as part of catalog restructuring
                    $newSkuModel = $skuModel->replicate();
                    $newSkuModel->sku = $newSku;
                    $newSkuModel->save();
                    
                    // Delete the old record
                    $skuModel->delete();
                    
                    $message = 'SKU code restructured successfully';
                    break;
                    
                default:
                    throw new \Exception('Invalid reason for SKU code change');
            }
            
            // Log the SKU code change
            $this->logSkuChange($oldSku, $newSku, $reason, $notes, $validated['merge_from'] ?? null);
            
            DB::commit();
            
            return response()->json([
                'message' => $message,
                'sku' => $newSkuModel
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            
            // Check for foreign key constraint violations
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot change SKU code because it is referenced by other records in the system. Please remove all references first.',
                    'error' => $e->getMessage()
                ], 422);
            }
            
            return response()->json([
                'message' => 'Database error while changing SKU code: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to change SKU code: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Toggle SKU active status (Obsolete/Reactivate functionality)
     */
    public function toggleActive(Request $request, $sku)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $skuModel = MmSku::findOrFail($sku);
        $currentStatus = $skuModel->is_active;
        $newStatus = !$currentStatus;
        
        try {
            DB::beginTransaction();
            
            $skuModel->is_active = $newStatus;
            // Update status field for display purposes
            $skuModel->sts = $newStatus ? 'ACT' : 'OBS';
            $skuModel->save();
            
            // Log the status change
            Log::info('SKU status changed', [
                'sku' => $sku,
                'from_status' => $currentStatus ? 'Active' : 'Obsolete',
                'to_status' => $newStatus ? 'Active' : 'Obsolete',
                'reason' => $validated['reason'],
                'user_id' => Auth::id() ?? 'system',
                'timestamp' => now(),
            ]);
            
            DB::commit();
            
            $statusText = $newStatus ? 'reactivated' : 'marked as obsolete';
            return response()->json([
                'message' => "SKU $sku has been successfully $statusText",
                'sku' => $skuModel
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to change SKU status: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk toggle SKU active status (for multiple SKUs at once)
     */
    public function bulkToggleActive(Request $request)
    {
        $validated = $request->validate([
            'skus' => 'required|array',
            'skus.*' => 'required|string|exists:mm_skus,sku',
            'set_active' => 'required|boolean',
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            $status = $validated['set_active'];
            $statusText = $status ? 'ACT' : 'OBS';
            $skus = $validated['skus'];
            
            // Update all the selected SKUs
            MmSku::whereIn('sku', $skus)
                ->update([
                    'is_active' => $status,
                    'sts' => $statusText
                ]);
                
            // Log the bulk status change
            foreach ($skus as $sku) {
                Log::info('SKU status changed (bulk)', [
                    'sku' => $sku,
                    'to_status' => $status ? 'Active' : 'Obsolete',
                    'reason' => $validated['reason'],
                    'user_id' => Auth::id() ?? 'system',
                    'timestamp' => now(),
                    'bulk_operation' => true
                ]);
            }
            
            DB::commit();
            
            $actionText = $status ? 'reactivated' : 'marked as obsolete';
            return response()->json([
                'message' => count($skus) . " SKUs have been $actionText successfully",
                'count' => count($skus)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to change SKU status: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Log SKU code change for audit purposes
     */
    private function logSkuChange($oldSku, $newSku, $reason, $notes, $mergeFrom = null)
    {
        // In a real implementation, this would log to a database table
        // For now, we'll just log to the Laravel log
        Log::info('SKU code changed', [
            'old_sku' => $oldSku,
            'new_sku' => $newSku,
            'reason' => $reason,
            'notes' => $notes,
            'merge_from' => $mergeFrom,
            'user_id' => Auth::id() ?? 'system',
            'timestamp' => now(),
        ]);
    }

    /**
     * Get SKU Balance by SKU ID.
     *
     * @param  string  $skuId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkuBalance($skuId)
    {
        // In a real application, you would fetch data from your database,
        // e.g., from a mm_sku_balances table.
        // For now, return mock data.
        $balanceData = [
            ['note' => 'REC-001', 'refDate' => '2023-01-01', 'refNo' => 'PO-123', 'status' => 'IN', 'balanceQty' => 100.000, 'location' => 'WH1'],
            ['note' => 'ISS-002', 'refDate' => '2023-01-15', 'refNo' => 'SO-456', 'status' => 'OUT', 'balanceQty' => -50.000, 'location' => 'WH1'],
            ['note' => 'TRF-003', 'refDate' => '2023-02-01', 'refNo' => 'LOC-789', 'status' => 'IN', 'balanceQty' => 20.000, 'location' => 'WH2'],
        ];

        // You would typically filter this by $skuId and fetch dynamic data
        // Example: 
        // $skuBalance = DB::table('mm_sku_balances')
        //                  ->where('sku_id', $skuId)
        //                  ->orderBy('ref_date', 'asc')
        //                  ->get();
        // return response()->json($skuBalance);

        return response()->json($balanceData);
    }
} 