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
        try {
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
        } catch (\Exception $e) {
            Log::error('Error in MmSkuController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
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

    // SKU destroy method removed for security reasons
    // SKUs should only be managed through Obsolete/Reactive functionality

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
        Log::info('Attempting to fetch types from MmSkuController.');
        $types = MmSku::distinct()->pluck('type')->filter();
        Log::info('Types fetched: ' . $types->toJson());
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
        Log::info('changeSkuCode method called', [
            'old_sku' => $oldSku,
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'user_id' => Auth::id() ?? 'system',
            'timestamp' => now(),
        ]);
        
        $validated = $request->validate([
            'new_sku' => 'required|string|max:20|unique:mm_skus,sku',
            'reason' => 'required|string|in:correction,standardization,merge,restructure',
            'notes' => 'nullable|string|max:255',
            'merge_from' => 'nullable|string|max:20|exists:mm_skus,sku',
        ]);
        
        $newSku = $validated['new_sku'];
        $reason = $validated['reason'];
        $notes = $validated['notes'] ?? '';
        
        Log::info('Validation passed', [
            'new_sku' => $newSku,
            'reason' => $reason,
            'notes' => $notes,
        ]);
        
        try {
            DB::beginTransaction();
            
            // Find the existing SKU record
            $skuModel = MmSku::findOrFail($oldSku);
            
            Log::info('Found existing SKU', [
                'old_sku' => $oldSku,
                'sku_name' => $skuModel->sku_name,
                'category_code' => $skuModel->category_code,
            ]);
            
            // Check if the new SKU code is the same as the old one
            if ($oldSku === $newSku) {
                throw new \Exception('New SKU code must be different from the current one');
            }
            
            // Check if new SKU already exists (double-check)
            if (MmSku::where('sku', $newSku)->exists()) {
                throw new \Exception('The new SKU code already exists. Please choose a different code.');
            }
            
            // Log the attempt
            Log::info('Attempting to change SKU code', [
                'old_sku' => $oldSku,
                'new_sku' => $newSku,
                'reason' => $reason,
                'user_id' => Auth::id() ?? 'system',
                'timestamp' => now(),
            ]);
            
            // Handle different cases based on reason
            switch ($reason) {
                case 'correction':
                case 'standardization':
                case 'restructure':
                    // For simple changes, use a more efficient approach
                    // Update the primary key directly using raw SQL to avoid foreign key issues
                    
                    // First, disable foreign key checks temporarily
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    
                    try {
                        // Update the SKU code in the main table
                        DB::statement('UPDATE mm_skus SET sku = ? WHERE sku = ?', [$newSku, $oldSku]);
                        
                        // Update all related tables that reference this SKU
                        DB::statement('UPDATE sku_prices SET sku_code = ? WHERE sku_code = ?', [$newSku, $oldSku]);
                        DB::statement('UPDATE sku_custom_tariff_codes SET sku_id = ? WHERE sku_id = ?', [$newSku, $oldSku]);
                        DB::statement('UPDATE sku_consumption_budgets SET sku_id = ? WHERE sku_id = ?', [$newSku, $oldSku]);
                        DB::statement('UPDATE sku_reorder_levels SET sku_id = ? WHERE sku_id = ?', [$newSku, $oldSku]);
                        
                        // Update legacy tables that might also reference SKU codes (if they exist)
                        // These tables might not have foreign key constraints, so we update them safely
                        try {
                            DB::statement('UPDATE sku SET SKU = ? WHERE SKU = ?', [$newSku, $oldSku]);
                        } catch (\Exception $e) {
                            Log::warning('Could not update legacy sku table: ' . $e->getMessage());
                        }
                        
                        try {
                            DB::statement('UPDATE sku_balance SET SKU = ? WHERE SKU = ?', [$newSku, $oldSku]);
                        } catch (\Exception $e) {
                            Log::warning('Could not update sku_balance table: ' . $e->getMessage());
                        }
                        
                        try {
                            DB::statement('UPDATE rt SET SKU = ? WHERE SKU = ?', [$newSku, $oldSku]);
                        } catch (\Exception $e) {
                            Log::warning('Could not update rt table: ' . $e->getMessage());
                        }
                        
                        Log::info('SKU code updated successfully', [
                            'old_sku' => $oldSku,
                            'new_sku' => $newSku,
                            'reason' => $reason,
                        ]);
                        
                        $message = match($reason) {
                            'correction' => 'SKU code corrected successfully',
                            'standardization' => 'SKU code standardized successfully',
                            'restructure' => 'SKU code restructured successfully',
                            default => 'SKU code updated successfully'
                        };
                        
                    } finally {
                        // Re-enable foreign key checks
                        DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    }
                    
                    // Get the updated SKU model
                    $newSkuModel = MmSku::findOrFail($newSku);
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
                    
                    // Check that merge_from is not the same as newSku
                    if ($validated['merge_from'] === $newSku) {
                        throw new \Exception('Merge source SKU cannot be the same as the new SKU code');
                    }
                    
                    // Find the SKU to merge from
                    $mergeFromSku = MmSku::findOrFail($validated['merge_from']);
                    
                    Log::info('Found merge source SKU', [
                        'merge_from' => $validated['merge_from'],
                        'merge_from_name' => $mergeFromSku->sku_name,
                    ]);
                    
                    // Create new SKU with combined data
                    $newSkuModel = $skuModel->replicate();
                    $newSkuModel->sku = $newSku;
                    
                    // Combine quantities if appropriate
                    $newSkuModel->boh = ($skuModel->boh ?? 0) + ($mergeFromSku->boh ?? 0);
                    $newSkuModel->fpo = ($skuModel->fpo ?? 0) + ($mergeFromSku->fpo ?? 0);
                    
                    // Use the higher ROL value
                    $newSkuModel->rol = max($skuModel->rol ?? 0, $mergeFromSku->rol ?? 0);
                    
                    // Save the new combined SKU
                    $newSkuModel->save();
                    
                    Log::info('SKU merge completed', [
                        'old_sku' => $oldSku,
                        'merge_from' => $validated['merge_from'],
                        'new_sku' => $newSku,
                        'combined_boh' => $newSkuModel->boh,
                        'combined_fpo' => $newSkuModel->fpo,
                        'combined_rol' => $newSkuModel->rol,
                    ]);
                    
                    // Delete both old records (cascade will handle related tables)
                    $skuModel->delete();
                    $mergeFromSku->delete();
                    
                    $message = 'SKUs merged successfully into new SKU';
                    break;
                    
                default:
                    throw new \Exception('Invalid reason for SKU code change');
            }
            
            // Log the SKU code change
            $this->logSkuChange($oldSku, $newSku, $reason, $notes, $validated['merge_from'] ?? null);
            
            // Commit the transaction first
            DB::commit();
            
            // Then fetch the updated SKU data with category relationship
            $updatedSku = MmSku::with('category')->find($newSkuModel->sku);
            
            Log::info('SKU code change completed successfully', [
                'old_sku' => $oldSku,
                'new_sku' => $newSku,
                'message' => $message,
                'updated_sku_data' => $updatedSku ? $updatedSku->toArray() : null,
            ]);
            
            return response()->json([
                'message' => $message,
                'sku' => $updatedSku,
                'old_sku' => $oldSku,
                'new_sku' => $newSku,
                'success' => true
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            
            Log::error('Database error during SKU code change', [
                'old_sku' => $oldSku,
                'new_sku' => $newSku,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'sql' => $e->getSql() ?? 'N/A',
            ]);
            
            // Check for foreign key constraint violations
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot change SKU code because it is referenced by other records in the system. Please remove all references first.',
                    'error' => $e->getMessage(),
                    'success' => false
                ], 422);
            }
            
            return response()->json([
                'message' => 'Database error while changing SKU code: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error during SKU code change', [
                'old_sku' => $oldSku,
                'new_sku' => $newSku,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'message' => 'Failed to change SKU code: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'success' => false
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
        try {
            $sku = MmSku::where('sku', $skuId)->first();
            
            if (!$sku) {
                return response()->json(['error' => 'SKU not found'], 404);
            }
            
            // Mock balance data for now - in real implementation, this would come from inventory system
            $balance = [
                'sku' => $sku->sku,
                'sku_name' => $sku->sku_name,
                'current_balance' => $sku->boh ?? 0,
                'reserved_quantity' => 0,
                'available_quantity' => $sku->boh ?? 0,
                'last_updated' => now()->toISOString(),
            ];
            
            return response()->json($balance);
        } catch (\Exception $e) {
            Log::error('Error in getSkuBalance: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get SKU balance'], 500);
        }
    }

    public function getSkusForPrint()
    {
        try {
            $skus = MmSku::with('category')
                ->select([
                    'sku',
                    'sku_name',
                    'category_code',
                    'type',
                    'uom',
                    'boh',
                    'fpo',
                    'rol',
                    'total_part',
                    'is_active',
                    'additional_name',
                    'part_number1',
                    'part_number2',
                    'part_number3',
                    'min_qty',
                    'max_qty'
                ])
                ->orderBy('sku')
                ->get();
            
            return response()->json($skus);
        } catch (\Exception $e) {
            Log::error('Error in getSkusForPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch SKUs for print'], 500);
        }
    }
} 