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
                'compute' => 'required|boolean',
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
                    'compute' => $validated['compute'],
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
                'compute' => 'required|boolean',
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
            
            // Create CSV content
            $headers = [
                'Product Design',
                'Flute Code',
                'Compute',
                'Min Trim (mm)',
                'Max Trim (mm)',
                'Created At',
                'Updated At'
            ];
            
            $callback = function() use ($rollTrims, $headers) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);
                
                foreach ($rollTrims as $trim) {
                    fputcsv($file, [
                        $trim->productDesign ? $trim->productDesign->pd_name : 'N/A',
                        $trim->paperFlute ? $trim->paperFlute->code : 'N/A',
                        $trim->compute ? 'Yes' : 'No',
                        $trim->min_trim,
                        $trim->max_trim,
                        $trim->created_at->format('Y-m-d H:i:s'),
                        $trim->updated_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            $filename = 'roll_trim_by_product_design_' . date('Y-m-d') . '.csv';
            
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
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
                                'compute' => rand(0, 1) === 1,
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
                'status' => 'error',
                'message' => 'Failed to seed roll trim data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Batch update multiple roll trim records.
     */
    public function apiBatchUpdate(Request $request)
    {
        $results = [];
        $errors = [];

        foreach ($request->all() as $itemData) {
            try {
                $validator = Validator::make($itemData, [
                    'product_id' => 'required|integer|exists:products,id',
                    'product_design_id' => 'required|integer|exists:product_designs,id',
                    'flute_id' => 'required|integer|exists:paper_flutes,id',
                    'compute' => 'required|boolean',
                    'min_trim' => 'nullable|numeric|min:0',
                    'max_trim' => 'nullable|numeric|min:0',
                ]);

                if ($validator->fails()) {
                    $errors[] = [
                        'product_id' => $itemData['product_id'] ?? null,
                        'product_design_id' => $itemData['product_design_id'] ?? null,
                        'flute_id' => $itemData['flute_id'] ?? null,
                        'error' => $validator->errors()->first(),
                    ];
                    continue;
                }

                $rollTrim = RollTrimByProductDesign::updateOrCreate(
                    [
                        'product_id' => $itemData['product_id'],
                        'product_design_id' => $itemData['product_design_id'],
                        'flute_id' => $itemData['flute_id'],
                    ],
                    [
                        'compute' => $itemData['compute'],
                        'min_trim' => $itemData['min_trim'] ?? 0,
                        'max_trim' => $itemData['max_trim'] ?? null,
                    ]
                );

                $results[] = $rollTrim;
            } catch (\Exception $e) {
                $errors[] = [
                    'product_id' => $itemData['product_id'] ?? null,
                    'product_design_id' => $itemData['product_design_id'] ?? null,
                    'flute_id' => $itemData['flute_id'] ?? null,
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => count($results) . ' roll trim specifications saved successfully',
            'results' => $results,
            'errors' => $errors
        ]);
    }
} 