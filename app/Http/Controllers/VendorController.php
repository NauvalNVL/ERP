<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VendorController extends Controller
{
    /**
     * Get all vendors with optional search
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vendor::query();

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->byStatus($request->status);
        }

        // Apply active filter
        if ($request->has('active')) {
            $query->active();
        }

        $vendors = $query->orderBy('vendor_name')->paginate(50);

        return response()->json($vendors);
    }

    /**
     * Get vendor by AP AC number
     */
    public function show(string $apAcNumber): JsonResponse
    {
        $vendor = Vendor::where('ap_ac_number', $apAcNumber)->first();

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        return response()->json($vendor);
    }

    /**
     * Search vendors by name or AP AC number
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $vendors = Vendor::search($request->query)
            ->active()
            ->orderBy('vendor_name')
            ->limit(20)
            ->get();

        return response()->json($vendors);
    }

    /**
     * Get vendor suggestions for autocomplete
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $vendors = Vendor::search($query)
            ->active()
            ->select('id', 'vendor_name', 'ap_ac_number', 'status')
            ->orderBy('vendor_name')
            ->limit(10)
            ->get();

        return response()->json($vendors);
    }
}
