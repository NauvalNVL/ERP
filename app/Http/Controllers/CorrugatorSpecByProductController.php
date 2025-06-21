<?php

namespace App\Http\Controllers;

use App\Models\CorrugatorSpecByProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CorrugatorSpecByProductController extends Controller
{
    /**
     * Display the corrugator specifications by product page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/CorrugatorSpesificationByProduct');
    }

    /**
     * Display the view and print page for corrugator specifications by product.
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintCorrugatorSpesificationByProduct');
    }

    /**
     * API: Get all corrugator specifications by product
     */
    public function apiIndex()
    {
        $specs = CorrugatorSpecByProduct::with('product')->get();
        return response()->json($specs);
    }

    /**
     * API: Get a specific corrugator specification by product
     */
    public function apiShow($id)
    {
        $spec = CorrugatorSpecByProduct::with('product')->findOrFail($id);
        return response()->json($spec);
    }

    /**
     * API: Store a new corrugator specification by product
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'compute' => 'sometimes|boolean',
            'min_sheet_length' => 'nullable|integer|min:1',
            'max_sheet_length' => 'nullable|integer|min:1',
            'min_sheet_width' => 'nullable|integer|min:1',
            'max_sheet_width' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if specification already exists for this product
        $existingSpec = CorrugatorSpecByProduct::where('product_id', $request->product_id)->first();
        if ($existingSpec) {
            // Update existing specification
            $existingSpec->update($request->all());
            return response()->json($existingSpec, 200);
        }

        $spec = CorrugatorSpecByProduct::create($request->all());
        return response()->json($spec, 201);
    }

    /**
     * API: Update a corrugator specification by product
     */
    public function apiUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'sometimes|exists:products,id',
            'compute' => 'sometimes|boolean',
            'min_sheet_length' => 'nullable|integer|min:1',
            'max_sheet_length' => 'nullable|integer|min:1',
            'min_sheet_width' => 'nullable|integer|min:1',
            'max_sheet_width' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $spec = CorrugatorSpecByProduct::findOrFail($id);
        $spec->update($request->all());
        
        return response()->json($spec);
    }

    /**
     * API: Delete a corrugator specification by product
     */
    public function apiDestroy($id)
    {
        $spec = CorrugatorSpecByProduct::findOrFail($id);
        $spec->delete();
        
        return response()->json(null, 204);
    }

    /**
     * API: Batch update or create corrugator specifications by product
     */
    public function apiBatchUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.product_id' => 'required|exists:products,id',
            '*.compute' => 'required|boolean',
            '*.min_sheet_length' => 'nullable|integer|min:1',
            '*.max_sheet_length' => 'nullable|integer|min:1',
            '*.min_sheet_width' => 'nullable|integer|min:1',
            '*.max_sheet_width' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $results = [];
        $errors = [];

        foreach ($request->all() as $specData) {
            try {
                $spec = CorrugatorSpecByProduct::updateOrCreate(
                    ['product_id' => $specData['product_id']],
                    $specData
                );
                $results[] = $spec;
            } catch (\Exception $e) {
                Log::error('Error updating corrugator spec for product ' . $specData['product_id'] . ': ' . $e->getMessage());
                $errors[] = [
                    'product_id' => $specData['product_id'],
                    'error' => $e->getMessage()
                ];
            }
        }

        if (count($errors) > 0) {
            return response()->json([
                'message' => 'Some specifications could not be updated',
                'results' => $results,
                'errors' => $errors
            ], 207); // 207 Multi-Status
        }

        return response()->json([
            'message' => 'All specifications updated successfully',
            'results' => $results
        ]);
    }

    /**
     * API: Export corrugator specifications by product to Excel
     */
    public function apiExport()
    {
        $specs = CorrugatorSpecByProduct::with('product')->get();
        
        // Transform data for export
        $exportData = $specs->map(function ($spec) {
            return [
                'Product Code' => $spec->product->product_code ?? 'N/A',
                'Product Name' => $spec->product->description ?? 'Unknown Product',
                'Compute' => $spec->compute ? 'Yes' : 'No',
                'Min Sheet Length' => $spec->min_sheet_length,
                'Max Sheet Length' => $spec->max_sheet_length,
                'Min Sheet Width' => $spec->min_sheet_width,
                'Max Sheet Width' => $spec->max_sheet_width,
                'Created At' => $spec->created_at->format('Y-m-d H:i:s'),
                'Updated At' => $spec->updated_at->format('Y-m-d H:i:s'),
            ];
        });
        
        // Return JSON for now - in a real implementation, this would return an Excel file
        return response()->json([
            'data' => $exportData,
            'filename' => 'corrugator_specifications_' . date('Y-m-d') . '.xlsx'
        ]);
    }
} 