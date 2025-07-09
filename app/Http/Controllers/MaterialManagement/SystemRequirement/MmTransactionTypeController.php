<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\MmTransactionType;
use Inertia\Inertia;

class MmTransactionTypeController extends Controller
{
    /**
     * Display the Transaction Type page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/TransactionType');
    }

    /**
     * Get all transaction types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionTypes()
    {
        try {
            $transactionTypes = MmTransactionType::orderBy('code')->get();
            
            return response()->json($transactionTypes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch transaction types: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created transaction type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:6|unique:mm_transaction_types,code',
                'name' => 'required|string|max:255',
                'group' => 'required|string|in:PO,IC,CX',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $transactionType = new MmTransactionType();
            $transactionType->code = $request->input('code');
            $transactionType->name = $request->input('name');
            $transactionType->group = $request->input('group');
            $transactionType->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction type created successfully',
                'data' => $transactionType
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create transaction type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified transaction type.
     *
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        try {
            $transactionType = MmTransactionType::where('code', $code)->firstOrFail();
            
            return response()->json($transactionType);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Transaction type not found'], 404);
        }
    }

    /**
     * Update the specified transaction type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        try {
            $transactionType = MmTransactionType::where('code', $code)->firstOrFail();
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'group' => 'required|string|in:PO,IC,CX',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $transactionType->name = $request->input('name');
            $transactionType->group = $request->input('group');
            $transactionType->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction type updated successfully',
                'data' => $transactionType
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update transaction type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified transaction type.
     *
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        try {
            $transactionType = MmTransactionType::where('code', $code)->firstOrFail();
            
            // Here you might want to check for dependencies before deletion
            // e.g., check if the transaction type is being used elsewhere
            
            $transactionType->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete transaction type: ' . $e->getMessage()], 500);
        }
    }

    public function getAll()
    {
        return response()->json(MmTransactionType::all());
    }

    /**
     * Show the View & Print Transaction Type page.
     */
    public function viewPrint()
    {
        return \Inertia\Inertia::render('material-management/system-requirement/standard-setup/ViewPrintTransactionType');
    }
} 