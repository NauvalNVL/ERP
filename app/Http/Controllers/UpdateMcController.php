<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MasterCard;
use App\Models\Mc;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
        $sortBy = $request->input('sortBy', 'mc_seq'); // Default sort by 'mc_seq'
        $sortOrder = $request->input('sortOrder', 'asc'); // Default sort order 'asc'
        $statuses = $request->input('status', ['Act']); // Default to 'Act' status
        $customerCode = $request->input('customer_code'); // Customer filter
        $perPage = $request->input('per_page', 10); // Items per page

        // Build the query using Eloquent
        $masterCards = MasterCard::query();

        // MANDATORY: Filter by customer_code if provided
        if ($customerCode) {
            $masterCards->where('customer_code', $customerCode);
        } else {
            // If no customer_code provided, return empty result for security
            return response()->json([
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
                'data' => [],
            ]);
        }

        // Apply search query if provided
        if ($query) {
            $masterCards->where(function ($q) use ($query) {
                $q->where('mc_seq', 'like', '%' . $query . '%')
                  ->orWhere('mc_model', 'like', '%' . $query . '%');
            });
        }

        // Apply status filter - handle both 'Act' and 'Active' status values
        $validStatuses = ['Act', 'Active', 'Obsolete']; // Define expected database status values
        $filteredStatuses = array_intersect($statuses, $validStatuses);

        if (!empty($filteredStatuses)) {
            // Map 'Act' to also include 'Active' for compatibility
            $dbStatuses = [];
            foreach ($filteredStatuses as $status) {
                if ($status === 'Act') {
                    $dbStatuses[] = 'Active'; // Map 'Act' to 'Active' in database
                } else {
                    $dbStatuses[] = $status;
                }
            }
            $masterCards->whereIn('status', $dbStatuses);
        } else {
            // If no valid status is provided, default to active
            $masterCards->where('status', 'Active');
        }

        // Apply sorting
        $validSortColumns = ['mc_seq', 'mc_model', 'part_no', 'comp_no', 'status', 'ext_dim_1', 'int_dim_1', 'customer_code'];
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'mc_seq'; // Fallback to a safe default
        }
        
        $masterCards->orderBy($sortBy, $sortOrder);

        // Paginate the results
        $paginatedMasterCards = $masterCards->paginate($perPage);

        // Transform the data to match the frontend expectations
        $transformedData = $paginatedMasterCards->getCollection()->map(function ($item) {
            // Derive approval status: either explicit 'Yes' on master_cards or active in approve_mcs
            try {
                $isApproved = ($item->mc_approval === 'Yes') || \App\Models\ApproveMC::where('mc_seq', $item->mc_seq)
                    ->where('status', 'active')
                    ->exists();
            } catch (\Throwable $e) {
                $isApproved = ($item->mc_approval === 'Yes');
            }

            return [
                'seq' => $item->mc_seq,
                'model' => $item->mc_model,
                'short_model' => $item->mc_short_model,
                'part' => $item->part_no,
                'comp' => $item->comp_no,
                'status' => $item->status,
                'mc_approval' => $isApproved ? 'Yes' : 'No',
                'p_design' => $item->p_design,
                'customer_code' => $item->customer_code,
                'customer_name' => $item->customer_code, // Use customer_code for now
                'ext_dim_1' => $item->ext_dim_1,
                'ext_dim_2' => $item->ext_dim_2,
                'ext_dim_3' => $item->ext_dim_3,
                'int_dim_1' => $item->int_dim_1,
                'int_dim_2' => $item->int_dim_2,
                'int_dim_3' => $item->int_dim_3,
                'last_mcs' => $item->mc_seq,
                'last_updated_seq' => $item->mc_seq,
            ];
        });

        $result = [
            'current_page' => $paginatedMasterCards->currentPage(),
            'last_page' => $paginatedMasterCards->lastPage(),
            'per_page' => $paginatedMasterCards->perPage(),
            'total' => $paginatedMasterCards->total(),
            'data' => $transformedData,
        ];

        Log::info('Master Card Query Results:', [
            'customer_code' => $customerCode,
            'count' => $result['total'], 
            'data_sample' => $transformedData->take(2)->toArray()
        ]);

        return response()->json($result);
    }

    /**
     * Show a single Master Card (by mc_seq) with details/PD setup.
     */
    public function apiShow($mcSeq, Request $request)
    {
        $customerCode = $request->input('customer_code');
        $query = MasterCard::where('mc_seq', $mcSeq);
        if ($customerCode) {
            $query->where('customer_code', $customerCode);
        }
        $mc = $query->first();
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
            
            // Build query with customer validation
            $query = MasterCard::where('mc_seq', $mcsNumber);
            
            // If customer_code is provided, validate ownership
            if ($customerCode) {
                $query->where('customer_code', $customerCode);
            }
            
            $masterCard = $query->first();
            
            if ($masterCard) {
                // Derive approval from both sources
                try {
                    $isApproved = ($masterCard->mc_approval === 'Yes') || \App\Models\ApproveMC::where('mc_seq', $masterCard->mc_seq)
                        ->where('status', 'active')
                        ->exists();
                } catch (\Throwable $e) {
                    $isApproved = ($masterCard->mc_approval === 'Yes');
                }

                return response()->json([
                    'exists' => true,
                    'data' => [
                        'mc_seq' => $masterCard->mc_seq,
                        'customer_code' => $masterCard->customer_code,
                        'mc_model' => $masterCard->mc_model,
                        'mc_short_model' => $masterCard->mc_short_model ?? '',
                        'status' => $masterCard->status,
                        'mc_approval' => $isApproved ? 'Yes' : 'No',
                        'part_no' => $masterCard->part_no,
                        'comp_no' => $masterCard->comp_no,
                        'p_design' => $masterCard->p_design,
                        'ext_dim_1' => $masterCard->ext_dim_1,
                        'ext_dim_2' => $masterCard->ext_dim_2,
                        'ext_dim_3' => $masterCard->ext_dim_3,
                        'int_dim_1' => $masterCard->int_dim_1,
                        'int_dim_2' => $masterCard->int_dim_2,
                        'int_dim_3' => $masterCard->int_dim_3,
                        'customer_name' => $masterCard->customer_code, // Use customer_code for now
                        'created_at' => $masterCard->created_at,
                        'updated_at' => $masterCard->updated_at,
                    ]
                ]);
            } else {
                // Check if MCS exists but belongs to different customer
                if ($customerCode) {
                    $existsElsewhere = MasterCard::where('mc_seq', $mcsNumber)->exists();
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
            Log::error('Error checking MCS: ' . $e->getMessage());
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
        $validated = $request->validate([
            'mc_seq' => 'required|string',
            'customer_code' => 'required|string|max:20',
            'mc_model' => 'nullable|string',
            'mc_short_model' => 'nullable|string',
            'status' => 'required|string|in:Active,Obsolete',
            'mc_approval' => 'required|string|in:Yes,No',
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
            'pcsToJoint' => 'nullable|string',
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
        ]);

        try {
            return DB::transaction(function () use ($validated) {
            $mc = MasterCard::updateOrCreate(
                [
                    'mc_seq' => $validated['mc_seq'],
                    'customer_code' => $validated['customer_code'],
                ],
                [
                    'mc_model' => $validated['mc_model'] ?? null,
                    'mc_short_model' => $validated['mc_short_model'] ?? null,
                    'status' => $validated['status'],
                    'mc_approval' => $validated['mc_approval'],
                    'part_no' => $validated['part_no'] ?? null,
                    'comp_no' => $validated['comp_no'] ?? null,
                    'p_design' => $validated['p_design'] ?? null,
                    'ext_dim_1' => $validated['ext_dim_1'] ?? null,
                    'ext_dim_2' => $validated['ext_dim_2'] ?? null,
                    'ext_dim_3' => $validated['ext_dim_3'] ?? null,
                    'int_dim_1' => $validated['int_dim_1'] ?? null,
                    'int_dim_2' => $validated['int_dim_2'] ?? null,
                    'int_dim_3' => $validated['int_dim_3'] ?? null,
                    'detailed_master_card' => $validated['detailed_master_card'] ?? null,
                    'pd_setup' => $validated['pd_setup'] ?? null,
                ]
            );

            // Map and upsert to legacy MC table for reporting/compatibility
            $legacy = [
                'AC_NUM' => $validated['customer_code'],
                // Try to resolve customer name; fallback to code
                'AC_NAME' => optional(\App\Models\UpdateCustomerAccount::where('customer_code', $validated['customer_code'])->first())
                    ->customer_name ?? $validated['customer_code'],
                'STS' => $validated['status'],
                'COMP' => $validated['comp_no'] ?? null,
                'P_DESIGN' => $validated['p_design'] ?? $validated['selectedProductDesign'] ?? null,
                'MCS_Num' => $validated['mc_seq'],
                'MODEL' => $validated['mc_model'] ?? null,
                'PART_NO' => $validated['part_no'] ?? null,
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
                if (!empty($validated[$src])) {
                    $legacy[$col] = is_numeric($validated[$src]) ? $validated[$src] : null;
                } elseif (is_array($details) && isset($details[$src]) && $details[$src] !== '') {
                    $legacy[$col] = is_numeric($details[$src]) ? $details[$src] : null;
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
                ];
            }
            
            if (is_array($pd)) {
                // Colors can come as array of codes or objects with code
                $colors = $pd['printColorCodes'] ?? $pd['colors'] ?? [];
                if (is_array($colors)) {
                    for ($i = 1; $i <= 7; $i++) {
                        $colorVal = $colors[$i - 1] ?? null;
                        if (is_array($colorVal)) {
                            $colorVal = $colorVal['code'] ?? $colorVal['value'] ?? null;
                        }
                        $legacy['COLOR' . $i] = $colorVal;
                    }
                }
                // Optional: color area percentages
                $colorAreas = $pd['colorAreaPercents'] ?? $pd['color_area_percents'] ?? null;
                if (is_array($colorAreas)) {
                    for ($i = 1; $i <= 7; $i++) {
                        $key = 'COLOR' . $i . '_AREA_PERCENT';
                        $legacy[$key] = isset($colorAreas[$i - 1]) ? $num($colorAreas[$i - 1]) : null;
                    }
                }
                // SO/WO pack qtys if present
                // SO/WO packing quantities may be provided with different keys
                $so = $pd['soValues'] ?? $pd['so_pq'] ?? $pd['so'] ?? [];
                $wo = $pd['woValues'] ?? $pd['wo_pq'] ?? $pd['wo'] ?? [];
                if (is_array($so)) {
                    for ($i = 1; $i <= 5; $i++) {
                        $legacy['SO_PQ' . $i] = $so[$i - 1] ?? null;
                    }
                }
                if (is_array($wo)) {
                    for ($i = 1; $i <= 5; $i++) {
                        $legacy['WO_PQ' . $i] = $wo[$i - 1] ?? null;
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

                // Core product attributes
                $legacy['P_DESIGN'] = $alias($pd, ['pDesign','p_design','productDesign','pdCode','pd','selectedProductDesign']);
                $legacy['FLUTE'] = $alias($pd, ['flute','paperFlute','flute_code','paper_flute','selectedPaperFlute']);
                $legacy['S_TOOL'] = $alias($pd, ['scoringTool','scoreTool','sTool','S_TOOL','selectedScoringToolCode']);
                $legacy['COAT'] = $alias($pd, ['chemicalCoat','chemCoat','coat','selectedChemicalCoat']);
                $legacy['TAPE'] = $alias($pd, ['reinforcementTape','rfTape','r_f_tape','rftape','RF_Tape','selectedReinforcementTape']);

                // Block / mould
                $legacy['PRINTING_BLOCK_NO'] = $alias($pd, ['printingBlockNo','printing_block_no','printBlockNo','pitBlockNo']);
                $legacy['DIECUT_MOULD_NO'] = $alias($pd, ['diecutMouldNo','diecut_mould_no','dcMouldNo','dcutBlockNo']);

                // Process flags to Yes/No strings
                $toYesNo = function ($v) {
                    if ($v === null || $v === '') return null;
                    return filter_var($v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true || $v === 'Y' || $v === 'Yes' || $v === 'YES' ? 'Yes' : 'No';
                };

                // Prefer coded values if provided, otherwise map booleans to Yes/No
                $legacy['FSH'] = $alias($pd, ['finishing','finishingCode','FSH','selectedFinishingCode']) ?? $toYesNo($alias($pd, ['hasFinishing']));
                $legacy['SWIRE'] = $alias($pd, ['stitchWire','swire','selectedStitchWireCode']) ?? $toYesNo($alias($pd, ['hasSwire']));
                $legacy['GLUEING'] = $alias($pd, ['glueing','gluing','glueingCode','selectedGlueingCode']) ?? $toYesNo($alias($pd, ['hasGlueing']));
                $legacy['WRAPPING'] = $alias($pd, ['wrapping','wrappingCode','selectedWrappingCode']) ?? $toYesNo($alias($pd, ['hasWrapping']));
                $legacy['HAND_HOLE'] = $toYesNo($alias($pd, ['handHole','hand_hole'])) ;
                $legacy['ROTARY_DC'] = $toYesNo($alias($pd, ['rotaryDc','rotary_dc','rotaryDCut'])) ;
                $legacy['FB_PRINTING'] = $toYesNo($alias($pd, ['fbPrinting','fb_printing','fullBlockPrint'])) ;
                $legacy['STRING_TYPE'] = $alias($pd, ['stringType','string_type']);
                $legacy['ITEM_REMARK'] = $alias($pd, ['itemRemark','item_remark']);
                $legacy['UNIT'] = $alias($pd, ['unit','uom']);
                $legacy['CURRENCY'] = $alias($pd, ['currency']);
                // Already set S_TOOL/COAT/TAPE above, keep backward aliases too
                if (!isset($legacy['S_TOOL'])) $legacy['S_TOOL'] = $alias($pd, ['stitchingTool','s_tool']);
                if (!isset($legacy['COAT'])) $legacy['COAT'] = $alias($pd, ['coat','chemicalCoat','coating']);
                if (!isset($legacy['TAPE'])) $legacy['TAPE'] = $alias($pd, ['tape']);

                // MSP1..MSP12 mapping if provided in a flat or grouped form
                for ($i = 1; $i <= 12; $i++) {
                    $group = $alias($pd, ["MSP{$i}", strtolower("msp{$i}"), "msp{$i}"]); // may return array
                    if (is_array($group)) {
                        $legacy["MSP{$i}_MCH"] = $alias($group, ['mch','machine','MSP_MCH']);
                        $legacy["MSP{$i}_UP"] = $alias($group, ['up','unit_price','MSP_UP']);
                        $legacy["MSP{$i}_SPECIAL_INST"] = $alias($group, ['special','special_inst','instruction']);
                    } else {
                        $legacy["MSP{$i}_MCH"] = $alias($pd, ["MSP{$i}_MCH", strtolower("MSP{$i}_MCH")]);
                        $legacy["MSP{$i}_UP"] = $alias($pd, ["MSP{$i}_UP", strtolower("MSP{$i}_UP")]);
                        $legacy["MSP{$i}_SPECIAL_INST"] = $alias($pd, ["MSP{$i}_SPECIAL_INST", strtolower("MSP{$i}_SPECIAL_INST")]);
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
                $legacy['PCS_PER_BLD'] = $num($alias($pd, ['pcsPerBld','pcs_per_bld']));
                $legacy['BLD_PER_PLD'] = $num($alias($pd, ['bldPerPld','bld_per_pld','bdlPerPallet']));
                $legacy['PCS_SET'] = $num($alias($pd, ['pcsSet','pcs_set','pcsPerSet']));
                $legacy['UNIT_PRICE'] = $num($alias($pd, ['unitPrice','unit_price']));

                // Additional numeric fields commonly used in MC
                $legacy['SWIRE_PCS'] = $num($alias($pd, ['swirePcs','swire_pcs']));
                $legacy['PEEL_OFF_PERCENT'] = $num($alias($pd, ['peelOffPercent','peel_off_percent']));
                $legacy['STRING_TYPE_VALUE'] = $num($alias($pd, ['stringTypeValue','string_type_value']));
                $legacy['MC_GROSS_M2_PER_PCS'] = $num($alias($pd, ['mcGrossM2PerPcs','mc_gross_m2_per_pcs']));
                $legacy['MC_NET_M2_PER_PCS'] = $num($alias($pd, ['mcNetM2PerPcs','mc_net_m2_per_pcs']));
                $legacy['MC_GROSS_KG_PER_SET'] = $num($alias($pd, ['mcGrossKgPerSet','mc_gross_kg_per_set']));
                $legacy['MC_NET_KG_PER_PCS'] = $num($alias($pd, ['mcNetKgPerPcs','mc_net_kg_per_pcs']));
                $legacy['TOTAL_COLOR'] = $num($alias($pd, ['totalColor','total_color']));

                // Sheet and cut metrics
                $legacy['SHEET_LENGTH'] = $num($alias($pd, ['sheetLength','sheet_length']));
                $legacy['SHEET_WIDTH'] = $num($alias($pd, ['sheetWidth','sheet_width']));
                $legacy['PAPER_SIZE'] = $num($alias($pd, ['paperSize','paper_size','selectedPaperSize']));
                $legacy['CORR_OUT'] = $num($alias($pd, ['corrOut','corr_out','conOut']));
                $legacy['SLIT_OUT'] = $num($alias($pd, ['slitOut','slit_out']));
                $legacy['DIE_OUT'] = $num($alias($pd, ['dieOut','die_out']));
                $legacy['JOIN_'] = $num($alias($pd, ['join','join_','pcsToJoint']));
                $legacy['NEST_SLOT'] = $num($alias($pd, ['nestSlot','nest_slot']));
                $legacy['CREASE'] = $num($alias($pd, ['crease','creaseValue']));

                // SL1..SL8 and SW1..SW8 and totals
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
                }
                $legacy['TOTAL_SL'] = $num($alias($pd, ['totalSl','TOTAL_SL']));
                $legacy['TOTAL_SW'] = $num($alias($pd, ['totalSw','TOTAL_SW']));
            }

            // Normalize empty strings to null
            $normalized = array_map(function ($v) {
                return ($v === '') ? null : $v;
            }, $legacy);

            // Upsert via query builder due to lack of primary key
            DB::table('MC')->updateOrInsert(
                [
                    'MCS_Num' => $normalized['MCS_Num'],
                    'AC_NUM' => $normalized['AC_NUM'],
                ],
                $normalized
            );

            return response()->json([
                'message' => 'Master Card saved successfully',
                'data' => $mc,
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
}