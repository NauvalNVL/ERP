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
        $perPage = $request->input('per_page', 10); // Items per page

        // Build the query using Eloquent
        $masterCards = MasterCard::query();

        // Apply search query if provided
        if ($query) {
            $masterCards->where(function ($q) use ($query) {
                $q->where('mc_seq', 'like', '%' . $query . '%')
                  ->orWhere('mc_model', 'like', '%' . $query . '%');
            });
        }

        // Apply status filter
        $validStatuses = ['Act', 'Obsolete']; // Define expected database status values
        $filteredStatuses = array_intersect($statuses, $validStatuses);

        if (!empty($filteredStatuses)) {
            $masterCards->whereIn('status', $filteredStatuses);
        } else {
            // If no valid status is provided, default to active
            $masterCards->where('status', 'Act');
        }

        // Apply sorting
        $validSortColumns = ['mc_seq', 'mc_model', 'part_no', 'comp_no', 'status', 'ext_dim_1', 'int_dim_1'];
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
                'part' => $item->part_no,
                'comp' => $item->comp_no,
                'status' => $item->status,
                'p_design' => $item->p_design,
                'ext_dim_1' => $item->ext_dim_1,
                'ext_dim_2' => $item->ext_dim_2,
                'ext_dim_3' => $item->ext_dim_3,
                'int_dim_1' => $item->int_dim_1,
                'int_dim_2' => $item->int_dim_2,
                'int_dim_3' => $item->int_dim_3,
            ];
        });

        $result = [
            'current_page' => $paginatedMasterCards->currentPage(),
            'last_page' => $paginatedMasterCards->lastPage(),
            'per_page' => $paginatedMasterCards->perPage(),
            'total' => $paginatedMasterCards->total(),
            'data' => $transformedData,
        ];

        Log::info('Master Card Query Results:', ['count' => $result['total'], 'data' => $result['data']]);

        return response()->json($result);
    }

    /**
     * Check if MCS number exists in database.
     *
     * @param  string  $mcsNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkMcs($mcsNumber)
    {
        try {
            $masterCard = MasterCard::where('mc_seq', $mcsNumber)->first();
            
            if ($masterCard) {
                return response()->json([
                    'exists' => true,
                    'data' => [
                        'mc_seq' => $masterCard->mc_seq,
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
                        'created_at' => $masterCard->created_at,
                        'updated_at' => $masterCard->updated_at,
                    ]
                ]);
            } else {
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
