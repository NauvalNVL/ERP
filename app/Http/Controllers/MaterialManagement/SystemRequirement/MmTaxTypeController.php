<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\MmTaxType;
use Inertia\Inertia;

class MmTaxTypeController extends Controller
{
    /**
     * Display the Tax Type page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/TaxType');
    }

    /**
     * Get all tax types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaxTypes()
    {
        $taxTypes = MmTaxType::with('taxGroup')->get();
        return response()->json($taxTypes);
    }

    /**
     * Store a new tax type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:6|unique:mm_tax_types,code',
            'name' => 'required|string|max:255',
            'is_applied' => 'required|boolean',
            'rate' => 'required|numeric|min:0',
            'tax_group_code' => 'required|string|exists:mm_tax_groups,code',
        ]);

        $taxType = MmTaxType::create($request->all());
        $taxType->load('taxGroup');

        return response()->json([
            'success' => true,
            'data' => $taxType,
        ]);
    }

    /**
     * Display the specified tax type.
     *
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        try {
            $taxType = MmTaxType::where('code', $code)->first();
            
            if (!$taxType) {
                return response()->json([
                    'message' => 'Tax type not found'
                ], 404);
            }

            return response()->json($taxType);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving tax type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified tax type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $taxType = MmTaxType::findOrFail($code);

        $request->validate([
            'name' => 'required|string|max:255',
            'is_applied' => 'required|boolean',
            'rate' => 'required|numeric|min:0',
            'tax_group_code' => 'required|string|exists:mm_tax_groups,code',
        ]);

        $taxType->update($request->all());
        $taxType->load('taxGroup');

        return response()->json([
            'success' => true,
            'data' => $taxType,
        ]);
    }

    /**
     * Remove the specified tax type.
     *
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        try {
            $taxType = MmTaxType::where('code', $code)->first();
            
            if (!$taxType) {
                return response()->json([
                    'message' => 'Tax type not found'
                ], 404);
            }

            $taxType->delete();

            return response()->json([
                'message' => 'Tax type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting tax type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the View & Print Tax Type page.
     */
    public function viewPrint()
    {
        return \Inertia\Inertia::render('material-management/system-requirement/standard-setup/ViewPrintTaxType');
    }
} 