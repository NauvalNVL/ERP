<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\UpdateMC; // Correctly use the UpdateMC model
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
        $sortBy = $request->input('sortBy', 'seq'); // Default sort by 'seq'
        $sortOrder = $request->input('sortOrder', 'asc'); // Default sort order 'asc'
        $statuses = $request->input('status', ['Active']); // Default to 'Active' status
        $perPage = $request->input('per_page', 10); // Items per page

        // Build the query using Eloquent
        $masterCards = UpdateMC::query();

        // Apply search query if provided
        if ($query) {
            $masterCards->where(function ($q) use ($query) {
                $q->where('seq', 'like', '%' . $query . '%')
                  ->orWhere('model', 'like', '%' . $query . '%');
                  // Add more fields here if needed for search, e.g., ->orWhere('part', 'like', '%' . $query . '%');
            });
        }

        // Apply status filter
        // Ensure status values from frontend match database values (e.g., 'Active' instead of 'Act')
        $validStatuses = ['Active', 'Obsolete']; // Define expected database status values
        $filteredStatuses = array_intersect($statuses, $validStatuses); // Use only valid statuses

        if (!empty($filteredStatuses)) {
            $masterCards->whereIn('status', $filteredStatuses);
        } else {
            // If no valid status is provided, or if an empty array is sent, default to active
            $masterCards->where('status', 'Active');
        }

        // Apply sorting
        // Ensure $sortBy is a valid column to prevent SQL injection
        $validSortColumns = ['seq', 'model', 'part', 'comp', 'status']; // Add all sortable columns
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'seq'; // Fallback to a safe default
        }
        
        $masterCards->orderBy($sortBy, $sortOrder);

        // Paginate the results
        $paginatedMasterCards = $masterCards->paginate($perPage);

        Log::info('Master Card Query Results:', ['count' => $paginatedMasterCards->total(), 'data' => $paginatedMasterCards->items()]);

        return response()->json($paginatedMasterCards);
    }
}
