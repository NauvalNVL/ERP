<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TaxTypeController extends Controller
{
    /**
     * Display the Define Tax Type page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('warehouse-management/Invoice/Setup/DefineTaxType');
    }

    /**
     * Display the Obsolete/Unobsolete Tax Type status management page.
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $taxTypes = TaxType::orderBy('code')->get();

            return Inertia::render('warehouse-management/Invoice/Setup/ObsoleteUnobsoleteTaxType', [
                'taxTypes' => $taxTypes,
                'header' => 'Manage Tax Type Status',
            ]);
        } catch (\Exception $e) {
            return Inertia::render('warehouse-management/Invoice/Setup/ObsoleteUnobsoleteTaxType', [
                'taxTypes' => [],
                'header' => 'Manage Tax Type Status',
            ]);
        }
    }

    /**
     * Get all tax types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaxTypes()
    {
        try {
            // Only return active tax types by default
            $taxTypes = TaxType::where('status', 'A')
                ->orderBy('code')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $taxTypes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tax types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific tax type by code.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        try {
            $taxType = TaxType::where('code', $code)->first();

            if (!$taxType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tax type not found'
                ], 404);
            }

            if (strtoupper($taxType->status ?? '') === 'O') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot use this tax type because it is marked as Obsolete.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data' => $taxType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tax type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new tax type.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:20|unique:tax_types,code',
                'name' => 'required|string|max:100',
                'apply' => 'required|in:Y,N',
                'rate' => 'required|numeric|min:0|max:100',
                'custom_type' => 'required|string|max:50',
                'status' => 'sometimes|in:A,O',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $taxType = TaxType::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'apply' => $request->apply,
                'rate' => $request->rate,
                'custom_type' => $request->custom_type,
                'status' => $request->input('status', 'A'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tax type created successfully',
                'data' => $taxType
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating tax type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing tax type.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        try {
            $taxType = TaxType::where('code', $code)->first();

            if (!$taxType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tax type not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'apply' => 'required|in:Y,N',
                'rate' => 'required|numeric|min:0|max:100',
                'custom_type' => 'required|string|max:50',
                'status' => 'sometimes|in:A,O',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $taxType->update([
                'name' => $request->name,
                'apply' => $request->apply,
                'rate' => $request->rate,
                'custom_type' => $request->custom_type,
                'status' => $request->input('status', $taxType->status ?? 'A'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tax type updated successfully',
                'data' => $taxType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating tax type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a tax type.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        try {
            $taxType = TaxType::where('code', $code)->first();

            if (!$taxType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tax type not found'
                ], 404);
            }

            // Soft delete: mark as obsolete instead of physically deleting
            $taxType->update(['status' => 'O']);

            return response()->json([
                'success' => true,
                'message' => 'Tax type deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting tax type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle tax type status (Active/Obsolete).
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request, $code)
    {
        try {
            $taxType = TaxType::where('code', $code)->first();

            if (!$taxType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tax type not found'
                ], 404);
            }

            $status = $request->input('status');

            if (!in_array($status, ['A', 'O'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 422);
            }

            $taxType->status = $status;
            $taxType->save();

            return response()->json([
                'success' => true,
                'message' => 'Tax type status updated successfully',
                'data' => $taxType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating tax type status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Seed sample tax types data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        try {
            $sampleTaxTypes = [
                ['code' => 'NIL', 'name' => 'NDH PPN', 'apply' => 'N', 'rate' => 0.00, 'custom_type' => 'N-NIL'],
                ['code' => 'PPN', 'name' => 'PPN 10%', 'apply' => 'Y', 'rate' => 10.00, 'custom_type' => 'N-NIL'],
                ['code' => 'PPN BRKT', 'name' => 'PPN KEL KWSN BERIKAT', 'apply' => 'Y', 'rate' => 10.00, 'custom_type' => 'N-NIL'],
                ['code' => 'PPN10', 'name' => 'PPN 10%', 'apply' => 'Y', 'rate' => 10.00, 'custom_type' => 'N-NIL'],
                ['code' => 'PPN11', 'name' => 'PPN 11%', 'apply' => 'Y', 'rate' => 11.00, 'custom_type' => 'N-NIL'],
                ['code' => 'PPN12', 'name' => 'PPN 12%', 'apply' => 'Y', 'rate' => 12.00, 'custom_type' => 'N-NIL'],
            ];

            foreach ($sampleTaxTypes as $data) {
                TaxType::updateOrCreate(
                    ['code' => $data['code']],
                    $data
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Sample tax types seeded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error seeding tax types: ' . $e->getMessage()
            ], 500);
        }
    }
}
