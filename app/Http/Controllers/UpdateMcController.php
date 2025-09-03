<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MasterCard;
use Illuminate\Support\Facades\Log;

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
                    $dbStatuses[] = 'Act'; // Keep 'Act' as is in database
                    $dbStatuses[] = 'Active'; // Also include 'Active' for compatibility
                } else {
                    $dbStatuses[] = $status;
                }
            }
            $masterCards->whereIn('status', $dbStatuses);
        } else {
            // If no valid status is provided, default to both 'Act' and 'Active'
            $masterCards->whereIn('status', ['Act', 'Active']);
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
            return [
                'seq' => $item->mc_seq,
                'model' => $item->mc_model,
                'short_model' => $item->mc_short_model,
                'part' => $item->part_no,
                'comp' => $item->comp_no,
                'status' => $item->status,
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
                return response()->json([
                    'exists' => true,
                    'data' => [
                        'mc_seq' => $masterCard->mc_seq,
                        'customer_code' => $masterCard->customer_code,
                        'mc_model' => $masterCard->mc_model,
                        'mc_short_model' => $masterCard->mc_short_model ?? '',
                        'status' => $masterCard->status,
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
}
