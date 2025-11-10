<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MasterCard;
use App\Models\Mc;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class UpdateMcController extends Controller
{
    /**
     * Display the Update MC page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('sales-management/system-requirement/master-card/update-mc');
    }

    /**
     * Search for an AC number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAc(Request $request)
    {
        // In a real application, you would search the database here
        return response()->json(['message' => 'Search functionality will be implemented']);
    }

    /**
     * Search for a MCS number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMcs(Request $request)
    {
        // In a real application, you would search the database here
        return response()->json(['message' => 'Search functionality will be implemented']);
    }

    /**
     * Get Master Card data for API consumption.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex(Request $request)
    {
        Log::info('apiIndex request', $request->all());

        $query = $request->input('query');
        $sortBy = $request->input('sortBy', 'mc_seq');
        $sortOrder = $request->input('sortOrder', 'asc');
        $statuses = $request->input('status', ['Act']);
        $customerCode = $request->input('customer_code');
        $perPage = (int) $request->input('per_page', 10);

        if (!$customerCode) {
            return response()->json([
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
                'data' => [],
            ]);
        }

        $mcQuery = DB::table('MC')->where('AC_NUM', $customerCode);

        if ($query) {
            $mcQuery->where(function ($q) use ($query) {
                $q->where('MCS_Num', 'like', '%' . $query . '%')
                  ->orWhere('MODEL', 'like', '%' . $query . '%');
            });
        }

        $validStatuses = ['Act', 'Active', 'Obsolete'];
        $filteredStatuses = array_intersect($statuses, $validStatuses);
        if (!empty($filteredStatuses)) {
            $dbStatuses = [];
            foreach ($filteredStatuses as $status) {
                $dbStatuses[] = $status === 'Act' ? 'Active' : $status;
            }
            $mcQuery->whereIn('STS', $dbStatuses);
        } else {
            $mcQuery->where('STS', 'Active');
        }

        $sortMap = [
            'mc_seq' => 'MCS_Num',
            'mc_model' => 'MODEL',
            'part_no' => 'PART_NO',
            'comp_no' => 'COMP',
            'status' => 'STS',
            'ext_dim_1' => 'EXT_LENGTH',
            'int_dim_1' => 'INT_LENGTH',
            'customer_code' => 'AC_NUM',
        ];
        $sortCol = $sortMap[$sortBy] ?? 'MCS_Num';
        $mcQuery->orderBy($sortCol, $sortOrder === 'desc' ? 'desc' : 'asc');

        $paginated = $mcQuery->paginate($perPage);

        $transformed = collect($paginated->items())->map(function ($item) {
            $isApproved = false; // MC table does not store approval; default false
            return [
                'seq' => $item->MCS_Num,
                'model' => $item->MODEL,
                'short_model' => null,
                'part' => $item->PART_NO,
                'comp' => $item->COMP,
                'status' => $item->STS,
                'mc_approval' => $isApproved ? 'Yes' : 'No',
                'p_design' => $item->P_DESIGN,
                'customer_code' => $item->AC_NUM,
                'customer_name' => $item->AC_NAME ?? $item->AC_NUM,
                'ext_dim_1' => $item->EXT_LENGTH,
                'ext_dim_2' => $item->EXT_WIDTH,
                'ext_dim_3' => $item->EXT_HEIGHT,
                'int_dim_1' => $item->INT_LENGTH,
                'int_dim_2' => $item->INT_WIDTH,
                'int_dim_3' => $item->INT_HEIGHT,
                'last_mcs' => $item->MCS_Num,
                'last_updated_seq' => $item->MCS_Num,
            ];
        });

        $result = [
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'data' => $transformed,
        ];

        Log::info('MC Query Results:', [
            'customer_code' => $customerCode,
            'count' => $result['total'],
        ]);

        return response()->json($result);
    }

    /**
     * Show a single Master Card (by mc_seq) with details/PD setup.
     */
    public function apiShow($mcSeq, Request $request)
    {
        $customerCode = $request->input('customer_code');
        $q = DB::table('MC')->where('MCS_Num', $mcSeq);
        if ($customerCode) {
            $q->where('AC_NUM', $customerCode);
        }
        $mc = $q->first();
        if (!$mc) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($mc);
    }

    /**
     * Check if MCS number exists in database with customer validation.
     *
     * @param  string  $mcsNumber
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkMcs($mcsNumber, Request $request)
    {
        try {
            $customerCode = $request->input('customer_code');

            $q = DB::table('MC')->where('MCS_Num', $mcsNumber);
            if ($customerCode) {
                $q->where('AC_NUM', $customerCode);
            }
            $row = $q->first();

            if ($row) {
                return response()->json([
                    'exists' => true,
                    'data' => [
                        'mc_seq' => $row->MCS_Num,
                        'customer_code' => $row->AC_NUM,
                        'mc_model' => $row->MODEL,
                        'mc_short_model' => '',
                        'status' => $row->STS,
                        'mc_approval' => 'No',
                        'part_no' => $row->PART_NO,
                        'comp_no' => $row->COMP,
                        'p_design' => $row->P_DESIGN,
                        'ext_dim_1' => $row->EXT_LENGTH,
                        'ext_dim_2' => $row->EXT_WIDTH,
                        'ext_dim_3' => $row->EXT_HEIGHT,
                        'int_dim_1' => $row->INT_LENGTH,
                        'int_dim_2' => $row->INT_WIDTH,
                        'int_dim_3' => $row->INT_HEIGHT,
                        'customer_name' => $row->AC_NAME ?? $row->AC_NUM,
                    ]
                ]);
            } else {
                if ($customerCode) {
                    $existsElsewhere = DB::table('MC')->where('MCS_Num', $mcsNumber)->exists();
                    if ($existsElsewhere) {
                        return response()->json([
                            'exists' => false,
                            'data' => null,
                            'message' => 'MCS exists but belongs to a different customer'
                        ]);
                    }
                }

                return response()->json([
                    'exists' => false,
                    'data' => null
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error checking MCS (MC table): ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'error' => 'Database error occurred'
            ], 500);
        }
    }

    /**
     * Store or update Master Card with details and PD setup.
     */
    public function store(Request $request)
    {
        // Log incoming request for debugging
        Log::info('=== UpdateMC Store Request START ===');
        Log::info('Request Data Summary', [
            'customer_code' => $request->input('customer_code'),
            'mc_seq' => $request->input('mc_seq'),
            'p_design' => $request->input('p_design'),
            'selectedProductDesign' => $request->input('selectedProductDesign'),
            'has_pd_setup' => $request->has('pd_setup'),
            'has_mspData' => $request->has('mspData')
        ]);
        
        try {
            $validated = $request->validate([
            'mc_seq' => 'required|string',
            'customer_code' => 'required|string|max:20',
            'customer_name' => 'nullable|string',
            'mc_model' => 'nullable|string',
            'mc_short_model' => 'nullable|string',
            'status' => 'nullable|string|in:Active,Obsolete',
            'mc_approval' => 'nullable|string|in:Yes,No',
            'part_no' => 'nullable|string',
            'comp_no' => 'nullable|string',
            'p_design' => 'nullable|string',
            'ext_dim_1' => 'nullable|string',
            'ext_dim_2' => 'nullable|string',
            'ext_dim_3' => 'nullable|string',
            'int_dim_1' => 'nullable|string',
            'int_dim_2' => 'nullable|string',
            'int_dim_3' => 'nullable|string',
            'detailed_master_card' => 'nullable|array',
            'pd_setup' => 'nullable|array',
            // Accept PD setup data directly from request
            'selectedProductDesign' => 'nullable|string',
            'selectedPaperFlute' => 'nullable|string',
            'selectedChemicalCoat' => 'nullable|string',
            'selectedReinforcementTape' => 'nullable|string',
            'selectedPaperSize' => 'nullable|string',
            'selectedScoringToolCode' => 'nullable|string',
            'printColorCodes' => 'nullable|array',
            'scoreL' => 'nullable|array',
            'scoreW' => 'nullable|array',
            'sheetLength' => 'nullable|string',
            'sheetWidth' => 'nullable|string',
            'conOut' => 'nullable|string',
            'convDuctX2' => 'nullable|string',
            'slitOut' => 'nullable|string',
            'dieOut' => 'nullable|string',
            'pcsToJoint' => 'nullable|string',
            'mcGrossM2PerPcs' => 'nullable|numeric',
            'mcNetM2PerPcs' => 'nullable|numeric',
            'mcGrossKgPerSet' => 'nullable|numeric',
            'mcNetKgPerPcs' => 'nullable|numeric',
            'id' => 'nullable|array',
            'ed' => 'nullable|array',
            'pcsPerSet' => 'nullable|string',
            'creaseValue' => 'nullable|string',
            'nestSlot' => 'nullable|string',
            'dcutSheet' => 'nullable|array',
            'dcutMould' => 'nullable|array',
            'dcutBlockNo' => 'nullable|string',
            'pitBlockNo' => 'nullable|string',
            'stitchWirePieces' => 'nullable|string',
            'bdlPerPallet' => 'nullable|string',
            'peelOffPercent' => 'nullable|string',
            'itemRemark' => 'nullable|string',
            'handHole' => 'nullable|boolean',
            'rotaryDCut' => 'nullable|boolean',
            'fullBlockPrint' => 'nullable|boolean',
            'selectedFinishingCode' => 'nullable|string',
            'selectedStitchWireCode' => 'nullable|string',
            'selectedBundlingStringCode' => 'nullable|string',
            'bundlingStringQty' => 'nullable|string',
            'selectedGlueingCode' => 'nullable|string',
            'selectedWrappingCode' => 'nullable|string',
            'soValues' => 'nullable|array',
            'woValues' => 'nullable|array',
            'specialInstructions' => 'nullable|array',
            'moreDescriptions' => 'nullable|array',
            'mcSpecialInst1' => 'nullable|string',
            'mcSpecialInst2' => 'nullable|string',
            'mcSpecialInst3' => 'nullable|string',
            'mcSpecialInst4' => 'nullable|string',
            'mcMoreDescription1' => 'nullable|string',
            'mcMoreDescription2' => 'nullable|string',
            'mcMoreDescription3' => 'nullable|string',
            'mcMoreDescription4' => 'nullable|string',
            'mcMoreDescription5' => 'nullable|string',
            'mspData' => 'nullable|array',
            'components' => 'nullable|array',
            'subMaterials' => 'nullable|array',
            'colorAreaPercents' => 'nullable|array',
            'partNo' => 'nullable|string',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error in UpdateMC Store', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
                'request_keys' => array_keys($request->all())
            ]);
            
            // Return detailed error response
            $receivedFields = array_keys($request->all());
            $validationRules = [
                'mc_seq', 'customer_code', 'customer_name', 'mc_model', 'mc_short_model', 'status', 'mc_approval',
                'part_no', 'comp_no', 'p_design', 'ext_dim_1', 'ext_dim_2', 'ext_dim_3',
                'int_dim_1', 'int_dim_2', 'int_dim_3', 'detailed_master_card', 'pd_setup',
                'selectedProductDesign', 'selectedPaperFlute', 'selectedChemicalCoat',
                'selectedReinforcementTape', 'selectedPaperSize', 'selectedScoringToolCode',
                'printColorCodes', 'scoreL', 'scoreW', 'sheetLength', 'sheetWidth',
                'conOut', 'convDuctX2', 'slitOut', 'dieOut', 'pcsToJoint',
                'mcGrossM2PerPcs', 'mcNetM2PerPcs', 'mcGrossKgPerSet', 'mcNetKgPerPcs',
                'id', 'ed', 'pcsPerSet', 'creaseValue', 'nestSlot', 'dcutSheet', 'dcutMould',
                'dcutBlockNo', 'pitBlockNo', 'stitchWirePieces', 'bdlPerPallet', 'peelOffPercent',
                'itemRemark', 'handHole', 'rotaryDCut', 'fullBlockPrint',
                'selectedFinishingCode', 'selectedStitchWireCode', 'selectedBundlingStringCode',
                'bundlingStringQty', 'selectedGlueingCode', 'selectedWrappingCode',
                'soValues', 'woValues', 'specialInstructions', 'moreDescriptions',
                'mcSpecialInst1', 'mcSpecialInst2', 'mcSpecialInst3', 'mcSpecialInst4',
                'mcMoreDescription1', 'mcMoreDescription2', 'mcMoreDescription3',
                'mcMoreDescription4', 'mcMoreDescription5',
                'mspData', 'components', 'subMaterials', 'colorAreaPercents', 'partNo'
            ];
            $missingValidations = array_values(array_diff($receivedFields, $validationRules));
            
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'debug' => [
                    'received_fields' => $receivedFields,
                    'missing_validations' => $missingValidations,
                    'received_count' => count($receivedFields),
                    'missing_count' => count($missingValidations)
                ]
            ], 422);
        }

        try {
            return DB::transaction(function () use ($validated) {
            // Load existing MC row to preserve unchanged fields on partial updates
            $existing = DB::table('MC')
                ->where('MCS_Num', $validated['mc_seq'])
                ->where('AC_NUM', $validated['customer_code'])
                ->first();

            // Helper to keep existing value when incoming is empty
            $keep = function ($key, $incoming) use ($existing) {
                if (($incoming === null || $incoming === '') && $existing) {
                    return $existing->{$key} ?? null;
                }
                return $incoming;
            };

            // Map and upsert to legacy MC table for reporting/compatibility
            // Get customer data to populate AC_NAME and CURRENCY
            $customer = \App\Models\Customer::where('CODE', $validated['customer_code'])->first();
            
            Log::info('Customer lookup', [
                'customer_code' => $validated['customer_code'],
                'customer_found' => $customer ? 'YES' : 'NO',
                'customer_currency' => $customer ? $customer->CURRENCY : 'NULL'
            ]);

            // Get UNIT from product_designs -> products table
            $unit = null;
            $pDesignCode = $validated['p_design'] ?? $validated['selectedProductDesign'] ?? null;
            
            Log::info('P_DESIGN lookup start', [
                'p_design_from_validated' => $validated['p_design'] ?? 'NULL',
                'selectedProductDesign_from_validated' => $validated['selectedProductDesign'] ?? 'NULL',
                'final_pDesignCode' => $pDesignCode
            ]);
            
            if ($pDesignCode) {
                // Get product code from product_designs table
                $productDesign = DB::table('product_designs')
                    ->where('pd_code', $pDesignCode)
                    ->first();
                
                Log::info('Product Design lookup', [
                    'pd_code' => $pDesignCode,
                    'product_design_found' => $productDesign ? 'YES' : 'NO',
                    'product_code' => $productDesign ? $productDesign->product : 'NULL'
                ]);
                
                if ($productDesign && $productDesign->product) {
                    // Get unit from products table
                    $product = DB::table('products')
                        ->where('product_code', $productDesign->product)
                        ->first();
                    
                    Log::info('Product lookup', [
                        'product_code' => $productDesign->product,
                        'product_found' => $product ? 'YES' : 'NO',
                        'unit' => $product ? ($product->unit ?? 'NULL') : 'NULL'
                    ]);
                    
                    if ($product && $product->unit) {
                        $unit = $product->unit;
                        Log::info('✓ UNIT successfully fetched', [
                            'pd_code' => $pDesignCode,
                            'product_code' => $productDesign->product,
                            'unit' => $unit
                        ]);
                    } else {
                        Log::warning('✗ UNIT not found or empty', [
                            'product_exists' => $product ? 'YES' : 'NO',
                            'unit_field' => $product ? ($product->unit ?? 'EMPTY') : 'N/A'
                        ]);
                    }
                } else {
                    Log::warning('✗ Product Design not found or has no product', [
                        'pd_code' => $pDesignCode,
                        'product_design_exists' => $productDesign ? 'YES' : 'NO'
                    ]);
                }
            } else {
                Log::warning('✗ No P_DESIGN code provided');
            }

            $currency = $customer ? $customer->CURRENCY : null;
            
            if (!$currency) {
                Log::warning('✗ CURRENCY not found', [
                    'customer_exists' => $customer ? 'YES' : 'NO',
                    'customer_code' => $validated['customer_code']
                ]);
            } else {
                Log::info('✓ CURRENCY successfully fetched', [
                    'customer_code' => $validated['customer_code'],
                    'currency' => $currency
                ]);
            }
            
            Log::info('MC Save - Initial values', [
                'customer_code' => $validated['customer_code'],
                'currency' => $currency,
                'unit' => $unit,
                'p_design' => $pDesignCode
            ]);

            $legacy = [
                'AC_NUM' => $validated['customer_code'],
                // Store customer NAME, not code
                'AC_NAME' => $customer ? $customer->NAME : $validated['customer_code'],
                'STS' => $validated['status'],
                'COMP' => $validated['comp_no'] ?? null,
                'P_DESIGN' => $pDesignCode,
                'MCS_Num' => $validated['mc_seq'],
                'MODEL' => $validated['mc_model'] ?? null,
                'PART_NO' => $validated['part_no'] ?? null,
                // Get CURRENCY from customer table
                'CURRENCY' => $currency,
                // Get UNIT from product_designs -> products table
                'UNIT' => $unit,
            ];

            // Try to enrich with dimensions from detailed_master_card if present
            $details = Arr::get($validated, 'detailed_master_card.mc_details', []);
            $mapDecimals = [
                'EXT_LENGTH' => 'ext_dim_1',
                'EXT_WIDTH' => 'ext_dim_2',
                'EXT_HEIGHT' => 'ext_dim_3',
                'INT_LENGTH' => 'int_dim_1',
                'INT_WIDTH' => 'int_dim_2',
                'INT_HEIGHT' => 'int_dim_3',
            ];
            foreach ($mapDecimals as $col => $src) {
                $incoming = null;
                if (!empty($validated[$src])) {
                    $incoming = is_numeric($validated[$src]) ? $validated[$src] : null;
                } elseif (is_array($details) && isset($details[$src]) && $details[$src] !== '') {
                    $incoming = is_numeric($details[$src]) ? $details[$src] : null;
                }
                // Preserve existing value when incoming is empty
                if (!isset($legacy[$col])) {
                    $legacy[$col] = $incoming;
                } else {
                    $legacy[$col] = ($incoming === null && isset($existing->{$col})) ? $existing->{$col} : $incoming;
                }
            }

            // Helper: coerce numeric-like values, otherwise null
            $num = function ($v) {
                if ($v === '' || $v === null) return null;
                // accept strings that are numeric (int/float)
                return is_numeric($v) ? (0 + $v) : null;
            };

            // Colors and additional PD setup fields
            $pd = $validated['pd_setup'] ?? [];

            // If no pd_setup array, build from direct request fields
            if (empty($pd)) {
                $pd = [
                    'selectedProductDesign' => $validated['selectedProductDesign'] ?? null,
                    'selectedPaperFlute' => $validated['selectedPaperFlute'] ?? null,
                    'selectedChemicalCoat' => $validated['selectedChemicalCoat'] ?? null,
                    'selectedReinforcementTape' => $validated['selectedReinforcementTape'] ?? null,
                    'selectedPaperSize' => $validated['selectedPaperSize'] ?? null,
                    'selectedScoringToolCode' => $validated['selectedScoringToolCode'] ?? null,
                    'printColorCodes' => $validated['printColorCodes'] ?? [],
                    'scoreL' => $validated['scoreL'] ?? [],
                    'scoreW' => $validated['scoreW'] ?? [],
                    'sheetLength' => $validated['sheetLength'] ?? null,
                    'sheetWidth' => $validated['sheetWidth'] ?? null,
                    'conOut' => $validated['conOut'] ?? null,
                    'convDuctX2' => $validated['convDuctX2'] ?? null,
                    'pcsToJoint' => $validated['pcsToJoint'] ?? null,
                    'id' => $validated['id'] ?? [],
                    'ed' => $validated['ed'] ?? [],
                    'pcsPerSet' => $validated['pcsPerSet'] ?? null,
                    'creaseValue' => $validated['creaseValue'] ?? null,
                    'nestSlot' => $validated['nestSlot'] ?? null,
                    'dcutSheet' => $validated['dcutSheet'] ?? [],
                    'dcutMould' => $validated['dcutMould'] ?? [],
                    'dcutBlockNo' => $validated['dcutBlockNo'] ?? null,
                    'pitBlockNo' => $validated['pitBlockNo'] ?? null,
                    'stitchWirePieces' => $validated['stitchWirePieces'] ?? null,
                    'bdlPerPallet' => $validated['bdlPerPallet'] ?? null,
                    'peelOffPercent' => $validated['peelOffPercent'] ?? null,
                    'itemRemark' => $validated['itemRemark'] ?? null,
                    'handHole' => $validated['handHole'] ?? false,
                    'rotaryDCut' => $validated['rotaryDCut'] ?? false,
                    'fullBlockPrint' => $validated['fullBlockPrint'] ?? false,
                    'selectedFinishingCode' => $validated['selectedFinishingCode'] ?? null,
                    'selectedStitchWireCode' => $validated['selectedStitchWireCode'] ?? null,
                    'selectedBundlingStringCode' => $validated['selectedBundlingStringCode'] ?? null,
                    'bundlingStringQty' => $validated['bundlingStringQty'] ?? null,
                    'selectedGlueingCode' => $validated['selectedGlueingCode'] ?? null,
                    'selectedWrappingCode' => $validated['selectedWrappingCode'] ?? null,
                    'soValues' => $validated['soValues'] ?? [],
                    'woValues' => $validated['woValues'] ?? [],
                    'specialInstructions' => $validated['specialInstructions'] ?? [],
                    'moreDescriptions' => $validated['moreDescriptions'] ?? [],
                    'mcSpecialInst1' => $validated['mcSpecialInst1'] ?? null,
                    'mcSpecialInst2' => $validated['mcSpecialInst2'] ?? null,
                    'mcSpecialInst3' => $validated['mcSpecialInst3'] ?? null,
                    'mcSpecialInst4' => $validated['mcSpecialInst4'] ?? null,
                    'mcMoreDescription1' => $validated['mcMoreDescription1'] ?? null,
                    'mcMoreDescription2' => $validated['mcMoreDescription2'] ?? null,
                    'mcMoreDescription3' => $validated['mcMoreDescription3'] ?? null,
                    'mcMoreDescription4' => $validated['mcMoreDescription4'] ?? null,
                    'mcMoreDescription5' => $validated['mcMoreDescription5'] ?? null,
                ];
            }

            if (is_array($pd)) {
                // Colors can come as array of codes or objects with code
                $colors = $pd['printColorCodes'] ?? $pd['colors'] ?? [];
                $colorCount = 0; // Track number of non-empty colors
                if (is_array($colors)) {
                    for ($i = 1; $i <= 7; $i++) {
                        $colorVal = $colors[$i - 1] ?? null;
                        if (is_array($colorVal)) {
                            $colorVal = $colorVal['code'] ?? $colorVal['value'] ?? null;
                        }
                        $legacy['COLOR' . $i] = $keep('COLOR' . $i, $colorVal);
                        // Count non-empty colors (only count if not null and not empty string)
                        if ($colorVal !== null && $colorVal !== '') {
                            $colorCount++;
                        }
                    }
                    // Calculate TOTAL_COLOR: just the count of selected colors (not multiplied)
                    $legacy['TOTAL_COLOR'] = $colorCount > 0 ? $colorCount : null;
                    
                    Log::info('TOTAL_COLOR calculated', [
                        'color_count' => $colorCount,
                        'total_color' => $legacy['TOTAL_COLOR'],
                        'colors' => array_filter($colors, function($c) { return $c !== null && $c !== ''; })
                    ]);
                }
                // Optional: color area percentages
                $colorAreas = $pd['colorAreaPercents'] ?? $pd['color_area_percents'] ?? null;
                if (is_array($colorAreas)) {
                    for ($i = 1; $i <= 7; $i++) {
                        $key = 'COLOR' . $i . '_AREA_PERCENT';
                        // Preserve existing value if incoming is empty
                        $incoming = isset($colorAreas[$i - 1]) ? $num($colorAreas[$i - 1]) : null;
                        $legacy[$key] = $keep($key, $incoming);
                    }
                }
                // SO/WO pack qtys if present
                // SO/WO packing quantities may be provided with different keys
                $so = $pd['soValues'] ?? $pd['so_pq'] ?? $pd['so'] ?? [];
                $wo = $pd['woValues'] ?? $pd['wo_pq'] ?? $pd['wo'] ?? [];
                if (is_array($so)) {
                    for ($i = 1; $i <= 5; $i++) {
                        $key = 'SO_PQ' . $i;
                        $incoming = $so[$i - 1] ?? null;
                        $legacy[$key] = $keep($key, $incoming);
                    }
                }
                if (is_array($wo)) {
                    for ($i = 1; $i <= 5; $i++) {
                        $key = 'WO_PQ' . $i;
                        $incoming = $wo[$i - 1] ?? null;
                        $legacy[$key] = $keep($key, $incoming);
                    }
                }

                // Helper to fetch value by multiple aliases
                $alias = function (array $src, array $keys) {
                    foreach ($keys as $k) {
                        if (strpos($k, '.') !== false) {
                            // Handle nested array access like 'dcutSheet.L'
                            $parts = explode('.', $k);
                            $value = $src;
                            foreach ($parts as $part) {
                                if (is_array($value) && array_key_exists($part, $value)) {
                                    $value = $value[$part];
                                } else {
                                    $value = null;
                                    break;
                                }
                            }
                            if ($value !== '' && $value !== null) {
                                return $value;
                            }
                        } else {
                            if (array_key_exists($k, $src) && $src[$k] !== '' && $src[$k] !== null) {
                                return $src[$k];
                            }
                        }
                    }
                    return null;
                };

                // Map PART_NO from PD if provided
                $legacy['PART_NO'] = $keep('PART_NO', $alias($pd, ['partNo','part_no']));

                // Core product attributes
                $newPDesign = $alias($pd, ['pDesign','p_design','productDesign','pdCode','pd','selectedProductDesign']);
                $legacy['P_DESIGN'] = $keep('P_DESIGN', $newPDesign);
                
                $legacy['FLUTE'] = $keep('FLUTE', $alias($pd, ['flute','paperFlute','flute_code','paper_flute','selectedPaperFlute']));
                $legacy['S_TOOL'] = $keep('S_TOOL', $alias($pd, ['scoringTool','scoreTool','sTool','S_TOOL','selectedScoringToolCode']));
                $legacy['COAT'] = $keep('COAT', $alias($pd, ['chemicalCoat','chemCoat','coat','selectedChemicalCoat']));
                $legacy['TAPE'] = $keep('TAPE', $alias($pd, ['reinforcementTape','rfTape','r_f_tape','rftape','RF_Tape','selectedReinforcementTape']));

                // Block / mould
                $legacy['PRINTING_BLOCK_NO'] = $keep('PRINTING_BLOCK_NO', $alias($pd, ['printingBlockNo','printing_block_no','printBlockNo','pitBlockNo']));
                $legacy['DIECUT_MOULD_NO'] = $keep('DIECUT_MOULD_NO', $alias($pd, ['diecutMouldNo','diecut_mould_no','dcMouldNo','dcutBlockNo']));

                // Process flags to Yes/No strings
                $toYesNo = function ($v) {
                    if ($v === null || $v === '') return null;
                    return filter_var($v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true || $v === 'Y' || $v === 'Yes' || $v === 'YES' ? 'Yes' : 'No';
                };

                // Prefer coded values if provided, otherwise map booleans to Yes/No
                $legacy['FSH'] = $keep('FSH', $alias($pd, ['finishing','finishingCode','FSH','selectedFinishingCode']) ?? $toYesNo($alias($pd, ['hasFinishing'])));
                $legacy['SWIRE'] = $keep('SWIRE', $alias($pd, ['stitchWire','swire','selectedStitchWireCode']) ?? $toYesNo($alias($pd, ['hasSwire'])));
                $legacy['GLUEING'] = $keep('GLUEING', $alias($pd, ['glueing','gluing','glueingCode','selectedGlueingCode']) ?? $toYesNo($alias($pd, ['hasGlueing'])));
                $legacy['WRAPPING'] = $keep('WRAPPING', $alias($pd, ['wrapping','wrappingCode','selectedWrappingCode']) ?? $toYesNo($alias($pd, ['hasWrapping'])));
                $legacy['HAND_HOLE'] = $toYesNo($alias($pd, ['handHole','hand_hole'])) ;
                $legacy['ROTARY_DC'] = $toYesNo($alias($pd, ['rotaryDc','rotary_dc','rotaryDCut'])) ;
                $legacy['FB_PRINTING'] = $toYesNo($alias($pd, ['fbPrinting','fb_printing','fullBlockPrint'])) ;
                $legacy['STRING_TYPE'] = $keep('STRING_TYPE', $alias($pd, ['stringType','string_type','selectedBundlingStringCode']));
                $legacy['ITEM_REMARK'] = $keep('ITEM_REMARK', $alias($pd, ['itemRemark','item_remark']));
                
                // UNIT: Only override if not already set from product_designs lookup
                $unitBeforeCheck = $legacy['UNIT'] ?? null;
                if (!isset($legacy['UNIT']) || $legacy['UNIT'] === null) {
                    $unitFromPd = $alias($pd, ['unit','uom']);
                    $legacy['UNIT'] = $unitFromPd;
                    Log::info('UNIT check in PD section', [
                        'unit_before' => $unitBeforeCheck,
                        'unit_from_pd' => $unitFromPd,
                        'unit_after' => $legacy['UNIT']
                    ]);
                } else {
                    Log::info('UNIT preserved from product lookup', [
                        'unit' => $legacy['UNIT']
                    ]);
                }
                
                // CURRENCY: Should always come from customer table (already set above)
                // Don't override with PD currency even if customer not found
                Log::info('CURRENCY check in PD section', [
                    'currency' => $legacy['CURRENCY'] ?? 'NULL'
                ]);
                // Already set S_TOOL/COAT/TAPE above, keep backward aliases too
                if (!isset($legacy['S_TOOL'])) $legacy['S_TOOL'] = $alias($pd, ['stitchingTool','s_tool']);
                if (!isset($legacy['COAT'])) $legacy['COAT'] = $alias($pd, ['coat','chemicalCoat','coating']);
                if (!isset($legacy['TAPE'])) $legacy['TAPE'] = $alias($pd, ['tape']);

                // MSP1..MSP12 mapping if provided in a flat or grouped form
                // First check if mspData is provided from the new MSP modal
                $mspData = $validated['mspData'] ?? null;
                if (is_array($mspData) && isset($mspData['machines']) && is_array($mspData['machines'])) {
                    // Clear all MSP fields first
                    for ($i = 1; $i <= 12; $i++) {
                        $legacy["MSP{$i}_MCH"] = null;
                        $legacy["MSP{$i}_UP"] = null;
                        $legacy["MSP{$i}_SPECIAL_INST"] = null;
                    }
                    
                    // Process MSP data from the new modal format
                    foreach ($mspData['machines'] as $index => $machine) {
                        $mspNum = $index + 1;
                        if ($mspNum <= 12) {
                            $legacy["MSP{$mspNum}_MCH"] = $machine['mchCode'] ?? null;
                            $legacy["MSP{$mspNum}_UP"] = $machine['noUp'] ?? null;
                            $legacy["MSP{$mspNum}_SPECIAL_INST"] = $machine['specialInstruction'] ?? null;
                        }
                    }
                } else {
                    // Fallback to old format for backward compatibility
                    for ($i = 1; $i <= 12; $i++) {
                        $group = $alias($pd, ["MSP{$i}", strtolower("msp{$i}"), "msp{$i}"]); // may return array
                        if (is_array($group)) {
                            $legacy["MSP{$i}_MCH"] = $keep("MSP{$i}_MCH", $alias($group, ['mch','machine','MSP_MCH']));
                            $legacy["MSP{$i}_UP"] = $keep("MSP{$i}_UP", $alias($group, ['up','unit_price','MSP_UP']));
                            $legacy["MSP{$i}_SPECIAL_INST"] = $keep("MSP{$i}_SPECIAL_INST", $alias($group, ['special','special_inst','instruction']));
                        } else {
                            $legacy["MSP{$i}_MCH"] = $keep("MSP{$i}_MCH", $alias($pd, ["MSP{$i}_MCH", strtolower("MSP{$i}_MCH")]));
                            $legacy["MSP{$i}_UP"] = $keep("MSP{$i}_UP", $alias($pd, ["MSP{$i}_UP", strtolower("MSP{$i}_UP")]));
                            $legacy["MSP{$i}_SPECIAL_INST"] = $keep("MSP{$i}_SPECIAL_INST", $alias($pd, ["MSP{$i}_SPECIAL_INST", strtolower("MSP{$i}_SPECIAL_INST")]));
                        }
                    }
                }

                // DC sheet and mould dimensions if available
                $dcShtL = $alias($pd, ['dcSheetL','dc_sht_l']);
                $dcShtW = $alias($pd, ['dcSheetW','dc_sht_w']);
                $dcMouldL = $alias($pd, ['dcMouldL','dc_mould_l']);
                $dcMouldW = $alias($pd, ['dcMouldW','dc_mould_w']);

                // Try nested array access
                if ($dcShtL === null && isset($pd['dcutSheet']['L'])) $dcShtL = $pd['dcutSheet']['L'];
                if ($dcShtW === null && isset($pd['dcutSheet']['W'])) $dcShtW = $pd['dcutSheet']['W'];
                if ($dcMouldL === null && isset($pd['dcutMould']['L'])) $dcMouldL = $pd['dcutMould']['L'];
                if ($dcMouldW === null && isset($pd['dcutMould']['W'])) $dcMouldW = $pd['dcutMould']['W'];

                $legacy['DC_SHT_L'] = $num($dcShtL);
                $legacy['DC_SHT_W'] = $num($dcShtW);
                $legacy['DC_MOULD_L'] = $num($dcMouldL);
                $legacy['DC_MOULD_W'] = $num($dcMouldW);
                // PCS_PER_BLD should store bundling string qty (bundlingStringQty)
                $legacy['PCS_PER_BLD'] = $keep('PCS_PER_BLD', $num($alias($pd, ['bundlingStringQty','pcsPerBld','pcs_per_bld'])));
                $legacy['BLD_PER_PLD'] = $keep('BLD_PER_PLD', $num($alias($pd, ['bldPerPld','bld_per_pld','bdlPerPallet'])));
                $legacy['PCS_SET'] = $keep('PCS_SET', $num($alias($pd, ['pcsSet','pcs_set','pcsPerSet'])));
                $legacy['UNIT_PRICE'] = $keep('UNIT_PRICE', $num($alias($pd, ['unitPrice','unit_price'])));

                // Additional numeric fields commonly used in MC
                $legacy['SWIRE_PCS'] = $keep('SWIRE_PCS', $num($alias($pd, ['swirePcs','swire_pcs','stitchWirePieces'])));
                $legacy['PEEL_OFF_PERCENT'] = $keep('PEEL_OFF_PERCENT', $num($alias($pd, ['peelOffPercent','peel_off_percent'])));
                // STRING_TYPE_VALUE column doesn't exist in database, using STRING_TYPE instead
                // $legacy['STRING_TYPE_VALUE'] = $keep('STRING_TYPE_VALUE', $num($alias($pd, ['stringTypeValue','string_type_value','bundlingStringQty'])));
                // Always update calculated values (don't preserve old values)
                $legacy['MC_GROSS_M2_PER_PCS'] = $num($alias($pd, ['mcGrossM2PerPcs','mc_gross_m2_per_pcs']));
                $legacy['MC_NET_M2_PER_PCS'] = $num($alias($pd, ['mcNetM2PerPcs','mc_net_m2_per_pcs']));
                $legacy['MC_GROSS_KG_PER_SET'] = $num($alias($pd, ['mcGrossKgPerSet','mc_gross_kg_per_set']));
                $legacy['MC_NET_KG_PER_PCS'] = $num($alias($pd, ['mcNetKgPerPcs','mc_net_kg_per_pcs']));
                // TOTAL_COLOR is calculated earlier based on number of selected colors * 2

                // Sheet and cut metrics
                $legacy['SHEET_LENGTH'] = $keep('SHEET_LENGTH', $num($alias($pd, ['sheetLength','sheet_length'])));
                $legacy['SHEET_WIDTH'] = $keep('SHEET_WIDTH', $num($alias($pd, ['sheetWidth','sheet_width'])));
                $legacy['PAPER_SIZE'] = $keep('PAPER_SIZE', $num($alias($pd, ['paperSize','paper_size','selectedPaperSize'])));
                $legacy['CORR_OUT'] = $keep('CORR_OUT', $num($alias($pd, ['corrOut','corr_out','conOut'])));
                $legacy['SLIT_OUT'] = $keep('SLIT_OUT', $num($alias($pd, ['slitOut','slit_out'])));
                $legacy['DIE_OUT'] = $keep('DIE_OUT', $num($alias($pd, ['dieOut','die_out'])));
                $legacy['JOIN_'] = $keep('JOIN_', $num($alias($pd, ['join','join_','pcsToJoint'])));
                $legacy['NEST_SLOT'] = $num($alias($pd, ['nestSlot','nest_slot']));
                $legacy['CREASE'] = $num($alias($pd, ['crease','creaseValue']));

                // SL1..SL8 and SW1..SW8 and totals
                $totalSL = 0;
                $totalSW = 0;

                for ($i = 1; $i <= 8; $i++) {
                    $scoreLVal = null;
                    $scoreWVal = null;

                    // Try direct field names first
                    $scoreLVal = $alias($pd, ['sl' . $i, 'SL' . $i]);
                    $scoreWVal = $alias($pd, ['sw' . $i, 'SW' . $i]);

                    // If not found, try array access
                    if ($scoreLVal === null && isset($pd['scoreL']) && is_array($pd['scoreL']) && isset($pd['scoreL'][$i-1])) {
                        $scoreLVal = $pd['scoreL'][$i-1];
                    }
                    if ($scoreWVal === null && isset($pd['scoreW']) && is_array($pd['scoreW']) && isset($pd['scoreW'][$i-1])) {
                        $scoreWVal = $pd['scoreW'][$i-1];
                    }

                    $legacy['SL' . $i] = $num($scoreLVal);
                    $legacy['SW' . $i] = $num($scoreWVal);

                    // Calculate totals
                    if ($legacy['SL' . $i] !== null) {
                        $totalSL += (float)$legacy['SL' . $i];
                    }
                    if ($legacy['SW' . $i] !== null) {
                        $totalSW += (float)$legacy['SW' . $i];
                    }
                }

                // Set calculated totals (override any passed value)
                $legacy['TOTAL_SL'] = $totalSL > 0 ? $totalSL : null;
                $legacy['TOTAL_SW'] = $totalSW > 0 ? $totalSW : null;

                // MC Special Instructions (1-4)
                for ($i = 1; $i <= 4; $i++) {
                    $key = 'MC_SPECIAL_INST' . $i;
                    $incoming = $alias($pd, [
                        'mcSpecialInst' . $i,
                        'mc_special_inst_' . $i,
                        'MC_SPECIAL_INST' . $i,
                        'specialInstructions.' . ($i - 1),
                        'special_instructions.' . ($i - 1)
                    ]);
                    $legacy[$key] = $keep($key, $incoming);
                }

                // MC More Descriptions (1-5)
                for ($i = 1; $i <= 5; $i++) {
                    $key = 'MC_MORE_DESCRIPTION_' . $i;
                    $incoming = $alias($pd, [
                        'mcMoreDescription' . $i,
                        'mc_more_description_' . $i,
                        'MC_MORE_DESCRIPTION_' . $i,
                        'moreDescriptions.' . ($i - 1),
                        'more_descriptions.' . ($i - 1)
                    ]);
                    $legacy[$key] = $keep($key, $incoming);
                }
            }

            // CRITICAL: Fetch UNIT based on final P_DESIGN value
            // This ensures UNIT is always synced with the selected P_DESIGN
            $finalPDesign = $legacy['P_DESIGN'] ?? null;
            if ($finalPDesign) {
                Log::info('Fetching UNIT based on final P_DESIGN', [
                    'final_p_design' => $finalPDesign,
                    'p_design_length' => strlen($finalPDesign),
                    'p_design_chars' => str_split($finalPDesign)
                ]);
                
                // Try exact match first
                $productDesign = DB::table('product_designs')
                    ->where('pd_code', $finalPDesign)
                    ->first();
                
                // If not found, try case-insensitive search
                if (!$productDesign) {
                    Log::info('Exact match not found, trying case-insensitive search');
                    $productDesign = DB::table('product_designs')
                        ->whereRaw('UPPER(pd_code) = ?', [strtoupper($finalPDesign)])
                        ->first();
                }
                
                // If still not found, try common typo corrections (0 vs O, R vs P, etc.)
                if (!$productDesign) {
                    Log::info('Case-insensitive search failed, trying typo corrections');
                    
                    $typoCorrections = [
                        ['0', 'O'],  // number 0 vs letter O
                        ['R', 'P'],  // R vs P (APR → APP)
                        ['1', 'I'],  // number 1 vs letter I
                    ];
                    
                    foreach ($typoCorrections as $correction) {
                        [$from, $to] = $correction;
                        $correctedCode = str_replace($from, $to, $finalPDesign);
                        
                        if ($correctedCode !== $finalPDesign) {
                            Log::info("Trying correction: {$from} → {$to}", ['corrected_code' => $correctedCode]);
                            $productDesign = DB::table('product_designs')
                                ->where('pd_code', $correctedCode)
                                ->first();
                            
                            if ($productDesign) {
                                Log::info('✓ Found with typo correction!', [
                                    'original' => $finalPDesign,
                                    'corrected' => $correctedCode,
                                    'correction_applied' => "{$from} → {$to}",
                                    'suggestion' => 'Update P_DESIGN to use correct code'
                                ]);
                                // Update the P_DESIGN in legacy array to the correct code
                                $legacy['P_DESIGN'] = $correctedCode;
                                break; // Stop after first successful correction
                            }
                        }
                    }
                }
                
                // If still not found, try to find similar codes
                if (!$productDesign) {
                    Log::info('Case-insensitive search failed, searching for similar codes');
                    $similarCodes = DB::table('product_designs')
                        ->where('pd_code', 'LIKE', '%' . substr($finalPDesign, 0, 2) . '%')
                        ->limit(10)
                        ->pluck('pd_code')
                        ->toArray();
                    
                    // If no similar codes, check if table has any data at all
                    if (empty($similarCodes)) {
                        $totalDesigns = DB::table('product_designs')->count();
                        $sampleDesigns = DB::table('product_designs')
                            ->select('pd_code')
                            ->limit(10)
                            ->pluck('pd_code')
                            ->toArray();
                        
                        Log::warning('Product Design not found - Database status', [
                            'searched_pd_code' => $finalPDesign,
                            'total_designs_in_db' => $totalDesigns,
                            'sample_codes' => $sampleDesigns,
                            'issue' => $totalDesigns == 0 ? 'product_designs table is EMPTY' : 'P_DESIGN code does not exist',
                            'suggestion' => $totalDesigns == 0 
                                ? 'Add product design data to database first' 
                                : 'Use one of the existing codes or add new product design'
                        ]);
                    } else {
                        Log::warning('Product Design not found - Similar codes in database', [
                            'searched_pd_code' => $finalPDesign,
                            'similar_codes' => $similarCodes,
                            'suggestion' => 'Check if P_DESIGN code is correct - use one of the similar codes'
                        ]);
                    }
                }
                
                if ($productDesign) {
                    Log::info('Product Design found', [
                        'searched_pd_code' => $finalPDesign,
                        'found_pd_code' => $productDesign->pd_code,
                        'pd_name' => $productDesign->pd_name ?? 'N/A',
                        'product_code' => $productDesign->product ?? 'NULL'
                    ]);
                    
                    if ($productDesign->product) {
                        $product = DB::table('products')
                            ->where('product_code', $productDesign->product)
                            ->first();
                        
                        if ($product) {
                            $unitValue = $product->unit ?? null;
                            $unitTrimmed = $unitValue ? trim($unitValue) : null;
                            
                            Log::info('Product found', [
                                'product_code' => $productDesign->product,
                                'description' => $product->description ?? 'N/A',
                                'unit_raw' => $unitValue,
                                'unit_length' => $unitValue ? strlen($unitValue) : 0,
                                'unit_trimmed' => $unitTrimmed,
                                'unit_is_empty' => empty($unitTrimmed)
                            ]);
                            
                            // Check if unit exists and is not empty (after trimming)
                            if (!empty($unitTrimmed)) {
                                $legacy['UNIT'] = $unitTrimmed;
                                Log::info('✓✓✓ UNIT FINAL SET from P_DESIGN', [
                                    'pd_code' => $finalPDesign,
                                    'found_pd_code' => $productDesign->pd_code,
                                    'product_code' => $productDesign->product,
                                    'unit' => $unitTrimmed,
                                    'unit_length' => strlen($unitTrimmed)
                                ]);
                            } else {
                                Log::warning('Product has no unit field or unit is empty', [
                                    'product_code' => $productDesign->product,
                                    'product_description' => $product->description ?? 'N/A',
                                    'unit_raw' => $unitValue,
                                    'unit_is_null' => $unitValue === null,
                                    'unit_is_empty_string' => $unitValue === ''
                                ]);
                            }
                        } else {
                            Log::warning('Product not found in products table', [
                                'product_code' => $productDesign->product,
                                'pd_code' => $productDesign->pd_code
                            ]);
                        }
                    } else {
                        Log::warning('Product Design has no product code', [
                            'pd_code' => $productDesign->pd_code,
                            'pd_name' => $productDesign->pd_name ?? 'N/A'
                        ]);
                    }
                }
            } else {
                Log::warning('No P_DESIGN in legacy array');
            }

            // Normalize empty strings to null
            $normalized = array_map(function ($v) {
                return ($v === '') ? null : $v;
            }, $legacy);

            // Log final values before insert
            Log::info('MC Save - Final values before insert', [
                'MCS_Num' => $normalized['MCS_Num'],
                'AC_NUM' => $normalized['AC_NUM'],
                'UNIT' => $normalized['UNIT'] ?? 'NULL',
                'CURRENCY' => $normalized['CURRENCY'] ?? 'NULL',
                'TOTAL_COLOR' => $normalized['TOTAL_COLOR'] ?? 'NULL',
                'P_DESIGN' => $normalized['P_DESIGN'] ?? 'NULL'
            ]);

            // Upsert via query builder due to lack of primary key
            DB::table('MC')->updateOrInsert(
                [
                    'MCS_Num' => $normalized['MCS_Num'],
                    'AC_NUM' => $normalized['AC_NUM'],
                ],
                $normalized
            );

            // Build a minimal response reflecting MC values stored
            $responseData = [
                'mc_seq' => $validated['mc_seq'],
                'customer_code' => $validated['customer_code'],
                'mc_model' => $validated['mc_model'] ?? null,
                'status' => $validated['status'],
                'ext_dim_1' => $validated['ext_dim_1'] ?? null,
                'ext_dim_2' => $validated['ext_dim_2'] ?? null,
                'ext_dim_3' => $validated['ext_dim_3'] ?? null,
                'int_dim_1' => $validated['int_dim_1'] ?? null,
                'int_dim_2' => $validated['int_dim_2'] ?? null,
                'int_dim_3' => $validated['int_dim_3'] ?? null,
            ];

            return response()->json([
                'message' => 'Master Card saved successfully (MC table only)',
                'data' => $responseData,
            ], 201);
            });
        } catch (\Throwable $e) {
            Log::error('UpdateMcController.store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Failed to save Master Card',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the Obsolete & Reactive MC page
     */
    public function obsoleteReactiveIndex()
    {
        return inertia('sales-management/system-requirement/master-card/obsolete-reactive-mc');
    }

    /**
     * Get MCs by customer with pagination (for UpdateMcModal)
     */
    public function getMcsByCustomerPaginated(Request $request)
    {
        try {
            $customerCode = $request->input('ac');
            $sortBy = $request->input('sort', 'mc_seq');
            $order = $request->input('order', 'asc');
            $status = $request->input('status', 'Act');
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 10);

            if (!$customerCode) {
                return response()->json([
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => 0,
                ]);
            }

            $query = DB::table('MC')->where('AC_NUM', $customerCode);

            // Filter by status
            if ($status === 'Act') {
                $query->where('STS', 'Active');
            } elseif ($status === 'Obsolete') {
                $query->where('STS', 'Obsolete');
            }

            // Search
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('MCS_Num', 'like', "%{$search}%")
                      ->orWhere('MODEL', 'like', "%{$search}%")
                      ->orWhere('PART_NO', 'like', "%{$search}%");
                });
            }

            // Sort
            $sortColumn = match($sortBy) {
                'mc_seq' => 'MCS_Num',
                'model' => 'MODEL',
                'status' => 'STS',
                default => 'MCS_Num',
            };
            $query->orderBy($sortColumn, $order);

            // Paginate
            $results = $query->paginate($perPage);

            // Transform data
            $transformedData = $results->map(function ($mc) {
                return [
                    'seq' => $mc->MCS_Num,
                    'model' => $mc->MODEL,
                    'part' => $mc->PART_NO ?? '',
                    'comp' => $mc->COMP ?? '',
                    'p_design' => $mc->P_DESIGN ?? '',
                    'status' => $mc->STS === 'Active' ? 'Act' : $mc->STS,
                ];
            });

            return response()->json([
                'data' => $transformedData,
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'total' => $results->total(),
                'per_page' => $results->perPage(),
            ]);

        } catch (\Exception $e) {
            Log::error('Get MCs by Customer Paginated Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Error occurred while fetching master cards.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get MC details for Obsolete & Reactive MC (CPS style - get salesperson from Customer)
     */
    public function getMcDetails($mcsNum)
    {
        try {
            Log::info('getMcDetails called', ['mcs_num' => $mcsNum]);

            $mc = DB::table('MC')->where('MCS_Num', $mcsNum)->first();

            if (!$mc) {
                return response()->json(['message' => 'Master Card not found.'], 404);
            }

            // Get customer name from MC.AC_NAME or CUSTOMER table
            $customerName = '';
            if (!empty($mc->AC_NAME)) {
                $customerName = $mc->AC_NAME;
            } elseif (!empty($mc->AC_NUM)) {
                $customerName = DB::table('CUSTOMER')->where('CODE', $mc->AC_NUM)->value('NAME') ?? '';
            }

            // Get salesperson from CUSTOMER.SLM field (CPS style - same as Update Customer Account)
            $salespersonCode = '';
            $salespersonName = '';
            if (!empty($mc->AC_NUM)) {
                $customer = DB::table('CUSTOMER')->where('CODE', $mc->AC_NUM)->first();

                if ($customer && !empty($customer->SLM)) {
                    $salespersonCode = $customer->SLM; // Use original SLM code (e.g., S101)

                    // Get salesperson name from salesperson table (same as Update Customer Account)
                    $salesperson = DB::table('salesperson')->where('Code', $customer->SLM)->first();
                    if ($salesperson) {
                        $salespersonName = $salesperson->Name ?? '';
                    }
                }
            }

            // Get last update log
            $lastUpdate = DB::table('MC_UPDATE_LOG')
                ->where('MCS_Num', $mcsNum)
                ->orderBy('updated_at', 'desc')
                ->first();

            $totalUpdate = DB::table('MC_UPDATE_LOG')->where('MCS_Num', $mcsNum)->count();

            return response()->json([
                'customer_name' => $customerName,
                'model' => $mc->MODEL ?? '',
                'salesperson_code' => $salespersonCode,
                'salesperson_name' => $salespersonName,
                'status' => $mc->STS ?? 'Active',
                'last_update' => $lastUpdate ? [
                    'status' => $lastUpdate->status ?? '',
                    'user_id' => $lastUpdate->user_id ?? '',
                    'date' => $lastUpdate->updated_at ? \Carbon\Carbon::parse($lastUpdate->updated_at)->timezone('Asia/Jakarta')->format('d/m/Y') : '',
                    'time' => $lastUpdate->updated_at ? \Carbon\Carbon::parse($lastUpdate->updated_at)->timezone('Asia/Jakarta')->format('H:i') : '',
                    'reason' => $lastUpdate->reason ?? '',
                    'total_update' => $totalUpdate,
                ] : [
                    'status' => $mc->STS ?? '',
                    'user_id' => '',
                    'date' => '',
                    'time' => '',
                    'reason' => '',
                    'total_update' => $totalUpdate,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Get MC Details Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update individual MC status (Obsolete or Reactivate)
     */
    public function updateMcStatus(Request $request)
    {
        try {
            $mcsNum = $request->input('mcs_num');
            $reason = $request->input('reason');
            $action = $request->input('action');

            if (!$mcsNum || !$reason || !$action) {
                return response()->json(['success' => false, 'message' => 'Missing fields.'], 400);
            }

            $mc = DB::table('MC')->where('MCS_Num', $mcsNum)->first();
            if (!$mc) {
                return response()->json(['success' => false, 'message' => 'MC not found.'], 404);
            }

            $newStatus = ($action === 'To Obsolete') ? 'Obsolete' : 'Active';

            // Update MC status (MC table doesn't have timestamps)
            DB::table('MC')->where('MCS_Num', $mcsNum)->update(['STS' => $newStatus]);

            // Get authenticated user ID
            $userId = 'system';
            if (Auth::check()) {
                $user = Auth::user();
                $userId = $user->name ?? $user->user_id ?? $user->userID ?? 'system';
            }

            DB::table('MC_UPDATE_LOG')->insert([
                'MCS_Num' => $mcsNum,
                'status' => $newStatus,
                'user_id' => $userId,
                'reason' => $reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => ($action === 'To Obsolete')
                    ? "MC {$mcsNum} obsoleted successfully."
                    : "MC {$mcsNum} reactivated successfully.",
            ]);
        } catch (\Exception $e) {
            Log::error('Update MC Status Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error occurred.'], 500);
        }
    }
}
