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
    public function index()
    {
        $skus = MmSku::with('category')->get();
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
} 