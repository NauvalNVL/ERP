<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\TaxGroup;
use App\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TaxGroupController extends Controller
{
    /**
     * Display a listing of tax groups.
     */
    public function index()
    {
        try {
            $taxGroups = TaxGroup::orderBy('code')->get();
            
            return response()->json([
                'success' => true,
                'data' => $taxGroups
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tax groups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tax groups with their tax types.
     */
    public function getTaxGroupsWithTypes()
    {
        try {
            $taxGroups = TaxGroup::with('taxTypes')->orderBy('code')->get();
            
            return response()->json([
                'success' => true,
                'data' => $taxGroups
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tax groups with types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created tax group.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:tax_groups,code',
            'name' => 'required|string|max:100',
            'sales_tax_applied' => 'required|in:Y,N',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taxGroup = TaxGroup::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'sales_tax_applied' => $request->sales_tax_applied,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tax group created successfully',
                'data' => $taxGroup
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tax group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified tax group.
     */
    public function show($code)
    {
        try {
            $taxGroup = TaxGroup::with('taxTypes')->findOrFail($code);
            
            return response()->json([
                'success' => true,
                'data' => $taxGroup
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tax group not found'
            ], 404);
        }
    }

    /**
     * Get tax items for a specific tax group.
     * Used by Check Sales Tax Screen in Prepare Invoice workflow.
     */
    public function getTaxItems($code)
    {
        try {
            $taxGroup = TaxGroup::where('code', $code)->with('taxTypes')->first();
            
            if (!$taxGroup) {
                \Log::error("Tax Group not found: {$code}");
                return response()->json([
                    'success' => false,
                    'message' => "Tax group '{$code}' not found"
                ], 404);
            }
            
            // Get tax types associated with this tax group
            $taxItems = $taxGroup->taxTypes->map(function($taxType) {
                return [
                    'tax_code' => $taxType->code,
                    'tax_name' => $taxType->name ?? '',
                    'rate' => floatval($taxType->rate ?? 0),
                    'include' => isset($taxType->include) ? ($taxType->include === 'Y' || $taxType->include === true) : false,
                    'status' => 'A', // Default to Active
                    'apply' => isset($taxType->apply) ? ($taxType->apply === 'Y' || $taxType->apply === true) : true,
                ];
            });
            
            // Log if no tax items found
            if ($taxItems->isEmpty()) {
                \Log::warning("Tax Group '{$code}' has no tax types assigned. Please assign tax types in Define Tax Group menu.");
            }
            
            return response()->json($taxItems);
        } catch (\Exception $e) {
            \Log::error("Error fetching tax items for group {$code}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tax items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified tax group.
     */
    public function update(Request $request, $code)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'sales_tax_applied' => 'required|in:Y,N',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taxGroup = TaxGroup::findOrFail($code);
            
            $taxGroup->update([
                'name' => $request->name,
                'sales_tax_applied' => $request->sales_tax_applied,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tax group updated successfully',
                'data' => $taxGroup
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tax group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified tax group.
     */
    public function destroy($code)
    {
        try {
            $taxGroup = TaxGroup::findOrFail($code);
            
            // Check if tax group has associated tax types
            $taxTypesCount = $taxGroup->taxTypes()->count();
            if ($taxTypesCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete tax group. It has {$taxTypesCount} associated tax type(s)."
                ], 400);
            }
            
            $taxGroup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tax group deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tax group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Seed sample tax groups matching CPS data.
     */
    public function seed()
    {
        try {
            $sampleGroups = [
                [
                    'code' => 'NIL',
                    'name' => 'NDH PPN',
                    'sales_tax_applied' => 'N',
                ],
                [
                    'code' => 'PPN',
                    'name' => 'PPN 10%',
                    'sales_tax_applied' => 'Y',
                ],
                [
                    'code' => 'PPN BRKT',
                    'name' => 'PPN KEL KWSN BERIKAT',
                    'sales_tax_applied' => 'Y',
                ],
                [
                    'code' => 'PPN11',
                    'name' => 'PPN 11%',
                    'sales_tax_applied' => 'Y',
                ],
                [
                    'code' => 'PPN12',
                    'name' => 'PPN 12%',
                    'sales_tax_applied' => 'Y',
                ],
            ];

            $created = 0;
            $skipped = 0;

            foreach ($sampleGroups as $group) {
                $exists = TaxGroup::where('code', $group['code'])->exists();
                if (!$exists) {
                    TaxGroup::create($group);
                    $created++;
                } else {
                    $skipped++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Seeded {$created} tax groups, skipped {$skipped} existing ones"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed tax groups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tax types for a specific tax group (for the Tax Item Screen).
     */
    public function getTaxTypes($code)
    {
        try {
            $taxGroup = TaxGroup::findOrFail($code);
            $taxTypes = $taxGroup->taxTypes()->orderBy('code')->get();
            
            return response()->json([
                'success' => true,
                'data' => $taxTypes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tax types for group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save tax types association for a tax group.
     * This will replace all existing associations with the new ones.
     */
    public function saveTaxTypes(Request $request, $code)
    {
        $validator = Validator::make($request->all(), [
            'tax_type_codes' => 'required|array',
            'tax_type_codes.*' => 'required|string|exists:tax_types,code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taxGroup = TaxGroup::findOrFail($code);
            
            // Get the tax type codes from request
            $taxTypeCodes = $request->tax_type_codes;
            
            // Update all tax types: set tax_group_code to this group for selected ones
            // First, clear existing associations
            TaxType::where('tax_group_code', $code)->update(['tax_group_code' => null]);
            
            // Then, set new associations
            if (!empty($taxTypeCodes)) {
                TaxType::whereIn('code', $taxTypeCodes)->update(['tax_group_code' => $code]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tax types updated successfully for group: ' . $code,
                'data' => [
                    'tax_group_code' => $code,
                    'tax_type_count' => count($taxTypeCodes)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save tax types: ' . $e->getMessage()
            ], 500);
        }
    }
}
