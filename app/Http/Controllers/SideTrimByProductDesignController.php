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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

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
    public function apiIndex(Request $request)
    {
        try {
            $productDesigns = ProductDesign::select('id', 'pd_name as code', 'pd_code')->orderBy('pd_name')->get();
            $products = Product::select('id', 'product_code as code')->orderBy('product_code')->get();
            $flutes = PaperFlute::select('id', 'code')->orderBy('code')->get();

            $existingTrims = SideTrimByProductDesign::all()
                ->keyBy(function ($item) {
                    return $item->product_design_id . '-' . $item->product_id . '-' . $item->flute_id;
                });

            $allCombinations = [];
            $tempIdCounter = 1;

            foreach ($productDesigns as $design) {
                foreach ($products as $product) {
                    foreach ($flutes as $flute) {
                        $key = $design->id . '-' . $product->id . '-' . $flute->id;
                        $existingTrim = $existingTrims->get($key);

                        $allCombinations[] = [
                            'id' => $existingTrim ? $existingTrim->id : 'new-' . $tempIdCounter++,
                            'product_design_id' => $design->id,
                            'product_id' => $product->id,
                            'flute_id' => $flute->id,
                            'product_design_code' => $design->pd_code,
                            'product_code' => $product->code,
                            'flute_code' => $flute->code,
                            'compute' => $existingTrim ? (bool)$existingTrim->compute : false,
                            'length_less' => $existingTrim ? $existingTrim->length_less : 0,
                            'length_add' => $existingTrim ? $existingTrim->length_add : 0,
                        ];
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $allCombinations,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching side trims by product design: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load data. ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new side trim record.
     */
    public function apiStore(Request $request)
    {
        try {
            Log::info('Received side trim data:', $request->all());
            $validated = $request->validate([
                'product_design_id' => 'required|exists:product_designs,id',
                'product_id' => 'required|exists:products,id',
                'flute_id' => 'required|exists:paper_flutes,id',
                'length_add' => 'nullable|integer|min:0',
                'length_less' => 'nullable|integer|min:0',
                'compute' => 'required|boolean',
            ]);

            // Prevent duplicates
            $existing = SideTrimByProductDesign::where('product_design_id', $validated['product_design_id'])
                ->where('product_id', $validated['product_id'])
                ->where('flute_id', $validated['flute_id'])
                ->first();

            if ($existing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This combination already exists'
                ], 422);
            }
            
            $dataToCreate = $validated;
            $dataToCreate['length_add'] = $validated['length_add'] ?? 0;
            $dataToCreate['length_less'] = $validated['length_less'] ?? 0;

            SideTrimByProductDesign::create($dataToCreate);
            
            return response()->json(['status' => 'success', 'message' => 'Side trim added successfully']);
        } catch (ValidationException $e) {
            Log::error('Validation errors:', $e->errors());
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'input' => $request->all()
            ], 422);
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
                'length_add' => 'nullable|integer|min:0',
                'length_less' => 'nullable|integer|min:0',
                'compute' => 'required|boolean',
            ]);

            // Prevent duplicates on update
            $existing = SideTrimByProductDesign::where('product_design_id', $validated['product_design_id'])
                ->where('product_id', $validated['product_id'])
                ->where('flute_id', $validated['flute_id'])
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This combination already exists'
                ], 422);
            }

            $sideTrim->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Side trim updated successfully',
                'data' => $sideTrim
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation errors:', $e->errors());
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'input' => $request->all()
            ], 422);
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

            return response()->json(['status' => 'success', 'message' => 'Side trim deleted successfully']);
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
            // This part can be implemented with a library like Laravel Excel
            // For now, we'll just return a placeholder response
            return response()->json(['message' => 'Export functionality not yet implemented'], 501);
        } catch (\Exception $e) {
            Log::error('Error exporting side trim data: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to export data'], 500);
        }
    }

    /**
     * Seed the database with initial side trim data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSeed()
    {
        try {
            // Use the seeder to populate the data
            Artisan::call('db:seed', [
                '--class' => 'SideTrimByProductDesignSeeder',
                '--force' => true // Use force in production
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Side trim data seeded successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed side trim data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch update side trim records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiBatchUpdate(Request $request)
    {
        try {
            // Log the full request data with sensitive information masked
            $logData = $request->except(['_token']);
            Log::info('Received batch update data for side trims', [
                'count' => count($logData),
                'sample' => array_slice($logData, 0, 2)
            ]);
            
            // Validate the entire request
            $validated = $request->validate([
                '*.id' => 'required|exists:side_trims_by_product_design,id',
                '*.product_design_id' => 'required|exists:product_designs,id',
                '*.product_id' => 'required|exists:products,id',
                '*.flute_id' => 'required|exists:paper_flutes,id',
                '*.length_add' => 'nullable|integer|min:0',
                '*.length_less' => 'nullable|integer|min:0',
                '*.compute' => 'required|boolean',
            ]);

            $errors = [];
            $updatedCount = 0;
            $processedIds = [];

            // Use a database transaction to ensure data integrity
            DB::beginTransaction();

            foreach ($validated as $data) {
                try {
                    // Find the side trim record
                    $sideTrim = SideTrimByProductDesign::findOrFail($data['id']);
                    
                    // Explicitly set each attribute
                    $sideTrim->product_design_id = $data['product_design_id'];
                    $sideTrim->product_id = $data['product_id'];
                    $sideTrim->flute_id = $data['flute_id'];
                    $sideTrim->length_add = $data['length_add'] ?? 0;
                    $sideTrim->length_less = $data['length_less'] ?? 0;
                    
                    // Determine which column to update based on database schema
                    $computeColumn = Schema::hasColumn('side_trims_by_product_design', 'compute') 
                        ? 'compute' 
                        : 'is_composite';
                    
                    $sideTrim->setAttribute($computeColumn, $data['compute']);

                    // Check for duplicate combination
                    $existingTrim = SideTrimByProductDesign::where('product_design_id', $data['product_design_id'])
                        ->where('product_id', $data['product_id'])
                        ->where('flute_id', $data['flute_id'])
                        ->where('id', '!=', $data['id'])
                        ->first();

                    if ($existingTrim) {
                        throw new \Exception('Duplicate combination for product design, product, and flute');
                    }

                    $sideTrim->save();
                    $updatedCount++;
                    $processedIds[] = $data['id'];
                } catch (\Exception $e) {
                    // Log individual record update errors
                    Log::error("Error updating side trim {$data['id']}: " . $e->getMessage(), [
                        'data' => $data,
                        'error' => $e->getMessage()
                    ]);
                    $errors[] = [
                        'id' => $data['id'],
                        'message' => $e->getMessage()
                    ];
                }
            }

            // Commit the transaction if all updates are successful
            DB::commit();

            // Prepare response based on update results
            $response = [
                'status' => !empty($errors) ? 'partial_success' : 'success',
                'updated_count' => $updatedCount,
                'processed_ids' => $processedIds,
            ];

            if (!empty($errors)) {
                $response['errors'] = $errors;
                Log::warning('Batch update completed with errors', $response);
                return response()->json($response, 206);
            }

            Log::info('Batch update completed successfully', $response);
            return response()->json($response);

        } catch (\Exception $e) {
            // Rollback the transaction in case of any unexpected errors
            DB::rollBack();

            Log::error('Batch update error: ' . $e->getMessage(), [
                'error_details' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during the batch update.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
} 