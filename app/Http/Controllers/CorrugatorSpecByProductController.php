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
            'product_code' => 'required|exists:products,product_code',
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
        $existingSpec = CorrugatorSpecByProduct::where('product_code', $request->product_code)->first();
        if ($existingSpec) {
            // Update existing specification
            $existingSpec->update($request->all());
            return response()->json(['success' => true, 'data' => $existingSpec], 200);
        }

        $spec = CorrugatorSpecByProduct::create($request->all());
        return response()->json(['success' => true, 'data' => $spec], 201);
    }

    /**
     * API: Update a corrugator specification by product
     */
    public function apiUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'sometimes|exists:products,product_code',
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
        
        return response()->json(['success' => true, 'data' => $spec]);
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
            '*.product_code' => 'required|exists:products,product_code',
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
                    ['product_code' => $specData['product_code']],
                    $specData
                );
                $results[] = $spec;
            } catch (\Exception $e) {
                Log::error('Error updating corrugator spec for product ' . $specData['product_code'] . ': ' . $e->getMessage());
                $errors[] = [
                    'product_code' => $specData['product_code'],
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
} 