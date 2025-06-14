<?php

namespace App\Http\Controllers;

use App\Models\ProductDesign;
use App\Models\Product;
use App\Models\PaperFlute;
use App\Models\SideTrimByProductDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SideTrimByProductDesignController extends Controller
{
    /**
     * Display the side trim by product design page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/SideTrimByProductDesign');
    }

    /**
     * Display the view and print page for side trims by product design.
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintSideTrimByProductDesign');
    }

    /**
     * Get all side trim data for API.
     */
    public function apiIndex()
    {
        try {
            $sideTrims = SideTrimByProductDesign::with(['productDesign', 'product', 'paperFlute'])->get();
            
            // Transform data to match desktop version format
            $transformedData = $sideTrims->map(function ($trim) {
                return [
                    'id' => $trim->id,
                    'product_design_id' => $trim->product_design_id,
                    'product_id' => $trim->product_id,
                    'flute_id' => $trim->flute_id,
                    'product_design' => null,  // Set to null to match desktop version
                    'product' => null,         // Set to null to match desktop version
                    'paper_flute' => $trim->paperFlute,
                    'is_composite' => $trim->is_composite,
                    'length_less' => $trim->length_less,
                    'length_add' => $trim->length_add
                ];
            });
            
            return response()->json([
                'status' => 'success',
                'data' => $transformedData
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving side trims by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve side trims data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new side trim record or update if exists.
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_design_id' => 'required|exists:product_designs,id',
                'product_id' => 'required|exists:products,id',
                'flute_id' => 'required|exists:paper_flutes,id',
                'length_add' => 'required|integer|min:0',
                'length_less' => 'required|integer|min:0',
                'is_composite' => 'required|boolean',
            ]);

            $sideTrim = SideTrimByProductDesign::updateOrCreate(
                [
                    'product_design_id' => $validated['product_design_id'],
                    'product_id' => $validated['product_id'],
                    'flute_id' => $validated['flute_id'],
                ],
                [
                    'length_add' => $validated['length_add'],
                    'length_less' => $validated['length_less'],
                    'is_composite' => $validated['is_composite'],
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim data saved successfully',
                'data' => $sideTrim
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving side trim by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save side trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing side trim record.
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $sideTrim = SideTrimByProductDesign::findOrFail($id);
            
            $validated = $request->validate([
                'product_design_id' => 'required|exists:product_designs,id',
                'product_id' => 'required|exists:products,id',
                'flute_id' => 'required|exists:paper_flutes,id',
                'length_add' => 'required|integer|min:0',
                'length_less' => 'required|integer|min:0',
                'is_composite' => 'required|boolean',
            ]);

            $sideTrim->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim data updated successfully',
                'data' => $sideTrim
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating side trim by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update side trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a side trim record.
     */
    public function apiDestroy($id)
    {
        try {
            $sideTrim = SideTrimByProductDesign::findOrFail($id);
            $sideTrim->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim data deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting side trim by product design: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete side trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export side trim data to Excel.
     */
    public function apiExport()
    {
        try {
            $sideTrims = SideTrimByProductDesign::with(['productDesign', 'product', 'paperFlute'])->get();
            
            // Set the filename
            $filename = 'side_trim_by_product_design_' . date('Y-m-d') . '.csv';
            
            // Create CSV content with headers
            $headers = [
                'Product Design', 
                'Product', 
                'Flute', 
                'Composite', 
                'Length Less (mm)', 
                'Length Add (mm)', 
                'Created At', 
                'Updated At'
            ];
            
            $callback = function() use ($sideTrims, $headers) {
                $file = fopen('php://output', 'w');
                
                // Add headers
                fputcsv($file, $headers);
                
                // Add data rows
                foreach ($sideTrims as $trim) {
                    fputcsv($file, [
                        $trim->productDesign ? $trim->productDesign->code : 'N/A',
                        $trim->product ? $trim->product->code : 'N/A',
                        $trim->paperFlute ? $trim->paperFlute->code : 'N/A',
                        $trim->is_composite ? 'Yes' : 'No',
                        $trim->length_less,
                        $trim->length_add,
                        $trim->created_at->format('Y-m-d H:i:s'),
                        $trim->updated_at->format('Y-m-d H:i:s'),
                    ]);
                }
                
                fclose($file);
            };
            
            // Return a downloadable response
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting side trim data: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export side trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Seed initial data for side trims by product design
     */
    public function apiSeed()
    {
        try {
            // Get all product designs, products, and flutes
            $productDesigns = ProductDesign::all();
            $products = Product::all();
            $flutes = PaperFlute::all();
            
            if ($productDesigns->count() === 0 || $products->count() === 0 || $flutes->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required data. Please ensure ProductDesign, Product, and PaperFlute models have data.'
                ], 400);
            }

            $count = 0;
            $maxEntries = 60; // Limit the number of entries to avoid creating too many
            
            // Create sample data
            foreach ($productDesigns->take(5) as $design) {
                foreach ($products->take(4) as $product) {
                    foreach ($flutes->take(3) as $flute) {
                        // Create a non-composite entry
                        SideTrimByProductDesign::updateOrCreate(
                            [
                                'product_design_id' => $design->id,
                                'product_id' => $product->id,
                                'flute_id' => $flute->id,
                            ],
                            [
                                'length_add' => rand(10, 30),
                                'length_less' => rand(5, 20),
                                'is_composite' => false,
                            ]
                        );
                        $count++;
                        
                        // Create a composite entry for some combinations
                        if (rand(0, 1) > 0) {
                            SideTrimByProductDesign::updateOrCreate(
                                [
                                    'product_design_id' => $design->id,
                                    'product_id' => $product->id,
                                    'flute_id' => $flute->id,
                                ],
                                [
                                    'length_add' => rand(20, 40),
                                    'length_less' => rand(10, 25),
                                    'is_composite' => true,
                                ]
                            );
                            $count++;
                        }
                        
                        if ($count >= $maxEntries) {
                            break 3; // Exit all loops if we've reached the maximum
                        }
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Side trim by product design data seeded successfully ($count records created)"
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding side trim by product design data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed data: ' . $e->getMessage()
            ], 500);
        }
    }
} 