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
        try {
            $taxTypes = MmTaxType::orderBy('code')->get();
            return response()->json($taxTypes);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching tax types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new tax type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:6|unique:mm_tax_types,code',
            'name' => 'required|string|max:255',
            'is_applied' => 'required|boolean',
            'rate' => 'required_if:is_applied,true|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taxType = new MmTaxType();
            $taxType->code = $request->code;
            $taxType->name = $request->name;
            $taxType->is_applied = $request->is_applied;
            $taxType->rate = $request->is_applied ? $request->rate : 0;
            $taxType->save();

            return response()->json([
                'message' => 'Tax type created successfully',
                'data' => $taxType
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating tax type: ' . $e->getMessage()
            ], 500);
        }
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'is_applied' => 'required|boolean',
            'rate' => 'required_if:is_applied,true|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $taxType = MmTaxType::where('code', $code)->first();
            
            if (!$taxType) {
                return response()->json([
                    'message' => 'Tax type not found'
                ], 404);
            }

            $taxType->name = $request->name;
            $taxType->is_applied = $request->is_applied;
            $taxType->rate = $request->is_applied ? $request->rate : 0;
            $taxType->save();

            return response()->json([
                'message' => 'Tax type updated successfully',
                'data' => $taxType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating tax type: ' . $e->getMessage()
            ], 500);
        }
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
} 