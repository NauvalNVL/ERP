<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\TaxGroup;
use App\Models\TaxGroupItem;
use App\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TaxGroupController extends Controller
{
    /**
     * Display a listing of tax groups.
     */
    public function index(Request $request)
    {
        try {
            $statusFilter = strtoupper($request->query('status', 'A'));

            $query = TaxGroup::orderBy('code');

            if ($statusFilter === 'ALL') {
                // No additional filtering
            } elseif ($statusFilter === 'O') {
                $query->where('status', 'O');
            } else {
                // Default to active tax groups
                $query->where('status', 'A');
            }

            $taxGroups = $query->get();

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
            // Only return active tax groups with their tax types
            $taxGroups = TaxGroup::with('taxTypes')
                ->where('status', 'A')
                ->orderBy('code')
                ->get();

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
     * Display the Obsolete/Unobsolete Tax Group status management page.
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $taxGroups = TaxGroup::orderBy('code')->get();

            return Inertia::render('warehouse-management/Invoice/Setup/ObsoleteUnobsoleteTaxGroup', [
                'taxGroups' => $taxGroups,
                'header' => 'Manage Tax Group Status',
            ]);
        } catch (\Exception $e) {
            return Inertia::render('warehouse-management/Invoice/Setup/ObsoleteUnobsoleteTaxGroup', [
                'taxGroups' => [],
                'header' => 'Manage Tax Group Status',
            ]);
        }
    }

    /**
     * Toggle tax group status (Active/Obsolete).
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request, $code)
    {
        try {
            $taxGroup = TaxGroup::find($code);

            if (!$taxGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tax group not found',
                ], 404);
            }

            $status = $request->input('status');

            if (!in_array($status, ['A', 'O'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value',
                ], 422);
            }

            DB::transaction(function () use ($taxGroup, $status) {
                $taxGroup->status = $status;
                $taxGroup->save();

                TaxGroupItem::where('tax_group_code', $taxGroup->code)
                    ->update(['status' => $status]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Tax group status updated successfully',
                'data' => $taxGroup->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tax group status: ' . $e->getMessage(),
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
            'status' => 'sometimes|in:A,O',
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
                'status' => $request->input('status', 'A'),
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

            if (strtoupper($taxGroup->status ?? '') === 'O') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot use this tax group because it is marked as Obsolete.',
                ], 422);
            }

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
            $taxGroup = TaxGroup::where('code', $code)->first();

            if (!$taxGroup) {
                Log::error("Tax Group not found: {$code}");
                return response()->json([
                    'success' => false,
                    'message' => "Tax group '{$code}' not found"
                ], 404);
            }

            // Read detailed items from tax_group_items + join tax_types for rate/name
            $items = TaxGroupItem::with('taxType')
                ->where('tax_group_code', $code)
                ->orderBy('sequence')
                ->get();

            $taxItems = $items->map(function (TaxGroupItem $item) {
                $taxType = $item->taxType;

                $includeRaw = $item->include;
                $include = ($includeRaw === true)
                    || ((string) $includeRaw === '1')
                    || in_array(strtoupper(trim((string) $includeRaw)), ['Y', 'YES', 'TRUE', 'T'], true);

                $applyRaw = $item->apply;
                $apply = ($applyRaw === true)
                    || ((string) $applyRaw === '1')
                    || in_array(strtoupper(trim((string) $applyRaw)), ['Y', 'YES', 'TRUE', 'T'], true);

                return [
                    'tax_group_code' => $item->tax_group_code,
                    'tax_code'       => $item->tax_type_code,
                    'tax_name'       => $taxType->name ?? '',
                    'rate'           => floatval($taxType->rate ?? 0),
                    'include'        => $include,
                    'status'         => $item->status ?? 'A',
                    'apply'          => $apply,
                    'sequence'       => $item->sequence,
                ];
            });

            if ($taxItems->isEmpty()) {
                Log::warning("Tax Group '{$code}' has no tax_group_items. Please assign tax types in Define Tax Group menu.");
            }

            return response()->json($taxItems);
        } catch (\Exception $e) {
            Log::error("Error fetching tax items for group {$code}: " . $e->getMessage());
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
            'status' => 'sometimes|in:A,O',
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
                'status' => $request->input('status', $taxGroup->status ?? 'A'),
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

            // Soft delete: mark as obsolete instead of physically deleting
            $taxGroup->update(['status' => 'O']);

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

            // Load items with their tax type for this group
            $items = TaxGroupItem::with('taxType')
                ->where('tax_group_code', $code)
                ->orderBy('sequence')
                ->get();

            $taxTypes = $items->map(function (TaxGroupItem $item) {
                $taxType = $item->taxType;

                return [
                    'code'        => $item->tax_type_code,
                    'name'        => $taxType->name ?? '',
                    'apply'       => $item->apply ?? 'Y',
                    'rate'        => floatval($taxType->rate ?? 0),
                    'custom_type' => $taxType->custom_type ?? 'Nil',
                    'include'     => $item->include ?? 'N',
                    'sequence'    => $item->sequence,
                    'status'      => $item->status ?? 'A',
                ];
            });

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
        // Support both legacy payload (tax_type_codes) and new detailed payload (tax_items)
        $validator = Validator::make($request->all(), [
            'tax_type_codes' => 'sometimes|array',
            'tax_type_codes.*' => 'required_with:tax_type_codes|string|exists:tax_types,code',
            'tax_items' => 'sometimes|array',
            'tax_items.*.tax_type_code' => 'required_with:tax_items|string|exists:tax_types,code',
            'tax_items.*.sequence' => 'sometimes|integer|min:1',
            'tax_items.*.apply' => 'sometimes|boolean',
            'tax_items.*.include' => 'sometimes|boolean',
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

            // Build normalized tax_items array
            $taxItemsInput = $request->input('tax_items');

            if (is_array($taxItemsInput) && count($taxItemsInput) > 0) {
                $taxItems = collect($taxItemsInput)->values()->map(function ($item, $index) use ($code) {
                    return [
                        'tax_group_code' => $code,
                        'tax_type_code'  => $item['tax_type_code'],
                        'sequence'       => isset($item['sequence']) ? (int) $item['sequence'] : $index + 1,
                        'apply'          => array_key_exists('apply', $item) ? ($item['apply'] ? 'Y' : 'N') : 'Y',
                        'include'        => array_key_exists('include', $item) ? ($item['include'] ? 'Y' : 'N') : 'N',
                        'status'         => 'A',
                    ];
                });
            } else {
                // Legacy payload: tax_type_codes => default attributes
                $taxTypeCodes = $request->input('tax_type_codes', []);
                $taxItems = collect($taxTypeCodes)->values()->map(function ($codeItem, $index) use ($code) {
                    return [
                        'tax_group_code' => $code,
                        'tax_type_code'  => $codeItem,
                        'sequence'       => $index + 1,
                        'apply'          => 'Y',
                        'include'        => 'N',
                        'status'         => 'A',
                    ];
                });
            }

            DB::transaction(function () use ($code, $taxItems) {
                // Remove existing items for this group
                TaxGroupItem::where('tax_group_code', $code)->delete();

                if ($taxItems->count() > 0) {
                    // Insert new items
                    TaxGroupItem::insert($taxItems->toArray());

                    // Keep legacy tax_types.tax_group_code roughly in sync (best-effort)
                    $codes = $taxItems->pluck('tax_type_code')->unique()->values();
                    TaxType::where('tax_group_code', $code)->update(['tax_group_code' => null]);
                    TaxType::whereIn('code', $codes)->update(['tax_group_code' => $code]);
                } else {
                    // If no items, clear legacy associations
                    TaxType::where('tax_group_code', $code)->update(['tax_group_code' => null]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Tax types updated successfully for group: ' . $code,
                'data' => [
                    'tax_group_code' => $code,
                    'tax_type_count' => $taxItems->count(),
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
