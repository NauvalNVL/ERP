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
        $validSortColumns = ['mc_seq', 'mc_model', 'part_no', 'comp_no', 'status'];
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
}
