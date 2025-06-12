<?php

namespace App\Http\Controllers;

use App\Models\RollTrimByProductDesign;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\PaperFlute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RollTrimByProductDesignController extends Controller
{
    /**
     * Display the roll trim by product design page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/RollTrimByProductDesign');
    }

    /**
     * Display the view and print page for roll trim by product design.
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintRollTrimByProductDesign');
    }

    /**
     * Get all roll trim data for API.
     */
    public function apiIndex()
    {
        try {
            $rollTrims = RollTrimByProductDesign::with(['product', 'productDesign', 'paperFlute'])->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $rollTrims
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving roll trims by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve roll trims data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new roll trim record.
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'product_design_id' => 'required|exists:product_designs,id',
                'flute_id' => 'required|exists:paper_flutes,id',
                'is_composite' => 'required|boolean',
                'min_trim' => 'required|integer|min:0',
                'max_trim' => 'required|integer|min:0|gte:min_trim',
            ]);

            $rollTrim = RollTrimByProductDesign::updateOrCreate(
                [
                    'product_id' => $validated['product_id'],
                    'product_design_id' => $validated['product_design_id'],
                    'flute_id' => $validated['flute_id'],
                ],
                [
                    'is_composite' => $validated['is_composite'],
                    'min_trim' => $validated['min_trim'],
                    'max_trim' => $validated['max_trim'],
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Roll trim data saved successfully',
                'data' => $rollTrim
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving roll trim by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing roll trim record.
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $rollTrim = RollTrimByProductDesign::findOrFail($id);
            
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'product_design_id' => 'required|exists:product_designs,id',
                'flute_id' => 'required|exists:paper_flutes,id',
                'is_composite' => 'required|boolean',
                'min_trim' => 'required|integer|min:0',
                'max_trim' => 'required|integer|min:0|gte:min_trim',
            ]);

            $rollTrim->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Roll trim data updated successfully',
                'data' => $rollTrim
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating roll trim by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a roll trim record.
     */
    public function apiDestroy($id)
    {
        try {
            $rollTrim = RollTrimByProductDesign::findOrFail($id);
            $rollTrim->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Roll trim data deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting roll trim by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export roll trim data to Excel.
     */
    public function apiExport()
    {
        try {
            $rollTrims = RollTrimByProductDesign::with(['product', 'productDesign', 'paperFlute'])->get();
            
            // Transform data for export
            $exportData = $rollTrims->map(function ($trim) {
                return [
                    'Product Design' => $trim->productDesign ? $trim->productDesign->pd_name : 'N/A',
                    'Product Code' => $trim->product ? $trim->product->product_code : 'N/A',
                    'Product Name' => $trim->product ? $trim->product->description : 'N/A',
                    'Flute Code' => $trim->paperFlute ? $trim->paperFlute->code : 'N/A',
                    'Composite' => $trim->is_composite ? 'Yes' : 'No',
                    'Min Trim (mm)' => $trim->min_trim,
                    'Max Trim (mm)' => $trim->max_trim,
                    'Created At' => $trim->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $trim->updated_at->format('Y-m-d H:i:s'),
                ];
            });
            
            // Return JSON data for now
            return response()->json([
                'status' => 'success',
                'data' => $exportData,
                'filename' => 'roll_trim_by_product_design_' . date('Y-m-d') . '.xlsx'
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting roll trim by product design data: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Seed initial data for roll trim by product design
     */
    public function apiSeed()
    {
        try {
            // Get products, designs and flutes
            $products = Product::all();
            $designs = ProductDesign::all();
            $flutes = PaperFlute::all();
            
            $count = 0;
            
            // Create sample data for common combinations
            foreach ($designs->take(3) as $design) {
                foreach ($products->take(5) as $product) {
                    foreach ($flutes->take(4) as $flute) {
                        RollTrimByProductDesign::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'product_design_id' => $design->id,
                                'flute_id' => $flute->id,
                            ],
                            [
                                'is_composite' => rand(0, 1) === 1,
                                'min_trim' => 20,
                                'max_trim' => 65
                            ]
                        );
                        $count++;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Roll trim by product design data seeded successfully ($count records created)"
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding roll trim by product design data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed data: ' . $e->getMessage()
            ], 500);
        }
    }
} 