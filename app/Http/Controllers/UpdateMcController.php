<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MasterCard; // Assuming you have a MasterCard model

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
        // Dummy data, replace with actual database query
        $masterCards = [
            ['seq' => '1609182', 'model' => 'BIHUN DELLIS 5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609162', 'model' => 'BIHUN FANIA 5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609163', 'model' => 'BIHUN IKAN TUNA 4.5 KG BARU', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609164', 'model' => 'BIHUN IKAN TUNA 5 KG BARU', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609166', 'model' => 'BIHUN PIRING MAS 5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609181', 'model' => 'BIHUN POHON KOPI 5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609138', 'model' => 'BOX BASO 4.5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609144', 'model' => 'BOX IKAN HARIMAU 4.5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609173', 'model' => 'BOX JAGUNG SRIKAYA 5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609145', 'model' => 'BOX SRIKAYA 4.5 KG', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609186', 'model' => 'POLOS 480 X 410 X 401', 'part' => '', 'comp' => '', 'status' => 'Act'],
            ['seq' => '1609185', 'model' => 'POLOS UK 506 X 356 X 407', 'part' => '', 'comp' => '', 'status' => 'Act'],
        ];

        $query = $request->input('query');
        $sortBy = $request->input('sortBy', 'seq');
        $sortOrder = $request->input('sortOrder', 'asc');
        $status = $request->input('status', ['Act']);

        $filteredMasterCards = collect($masterCards)->filter(function ($mc) use ($query, $status) {
            $matchesQuery = true;
            if ($query) {
                $queryLower = strtolower($query);
                $matchesQuery = str_contains(strtolower($mc['seq']), $queryLower) ||
                                str_contains(strtolower($mc['model']), $queryLower) ||
                                str_contains(strtolower($mc['part']), $queryLower);
            }
            $matchesStatus = in_array($mc['status'], $status);
            return $matchesQuery && $matchesStatus;
        });

        $sortedMasterCards = $filteredMasterCards->sortBy(function ($mc) use ($sortBy) {
            return strtolower($mc[$sortBy]);
        }, SORT_NATURAL | SORT_FLAG_CASE);

        if ($sortOrder === 'desc') {
            $sortedMasterCards = $sortedMasterCards->reverse();
        }

        // Paginate results (example for dummy data, adjust for real DB query)
        $perPage = 10; // Or from request input
        $page = $request->input('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedMasterCards->forPage($page, $perPage),
            $sortedMasterCards->count(),
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        return response()->json($paginated);
    }
}
