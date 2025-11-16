<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\CustomerSalesTaxIndex;
use App\Models\CustomerTaxProductTieup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CustomerSalesTaxIndexController extends Controller
{
    /**
     * Display the customer sales tax index page.
     */
    public function index()
    {
        return Inertia::render('warehouse-management/Invoice/Setup/DefineCustomerSalesTaxIndex');
    }

    /**
     * Get all tax indices for a specific customer.
     * Data diambil dari tabel customer_sales_tax_indices (Define Customer Sales Tax Index menu)
     */
    public function getCustomerIndices($customerCode)
    {
        try {
            \Log::info('═══════════════════════════════════════════════════════════');
            \Log::info('📋 API REQUEST: Get Customer Tax Indices');
            \Log::info('   Endpoint: GET /api/invoices/customer-tax-indices/{customerCode}');
            \Log::info('   Customer Code: ' . $customerCode);
            \Log::info('   Data Source: customer_sales_tax_indices table');
            \Log::info('   Source Menu: Define Customer Sales Tax Index');

            // Query customer_sales_tax_indices table
            $indices = CustomerSalesTaxIndex::where('customer_code', $customerCode)
                ->with('taxGroup')
                ->orderBy('index_number')
                ->get();

            \Log::info('✅ Query Result: ' . $indices->count() . ' tax indices found');
            if ($indices->count() > 0) {
                foreach ($indices as $idx) {
                    $taxGroupName = $idx->taxGroup->name ?? 'No name';
                    \Log::info("   - Index {$idx->index_number}: {$idx->tax_group_code} ({$taxGroupName}) - Status: {$idx->status}");
                }
            } else {
                \Log::warning('⚠️ No tax indices found for customer: ' . $customerCode);
                \Log::warning('   Please add indices in Define Customer Sales Tax Index menu');
            }
            \Log::info('═══════════════════════════════════════════════════════════');

            return response()->json([
                'success' => true,
                'data' => $indices
            ]);
        } catch (\Exception $e) {
            \Log::error('═══════════════════════════════════════════════════════════');
            \Log::error('❌ ERROR querying customer_sales_tax_indices table');
            \Log::error('   Customer Code: ' . $customerCode);
            \Log::error('   Error: ' . $e->getMessage());
            \Log::error('═══════════════════════════════════════════════════════════');

            return response()->json([
                'success' => false,
                'message' => 'Failed to load customer tax indices: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific tax index.
     */
    public function show($customerCode, $indexNumber)
    {
        try {
            $index = CustomerSalesTaxIndex::where('customer_code', $customerCode)
                ->where('index_number', $indexNumber)
                ->with('taxGroup')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $index
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tax index not found'
            ], 404);
        }
    }

    /**
     * Store or update a customer sales tax index.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string|max:50',
            'index_number' => 'required|integer|min:1',
            'tax_group_code' => 'required|string|max:20|exists:tax_groups,code',
            'status' => 'required|in:A,O',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $index = CustomerSalesTaxIndex::updateOrCreate(
                [
                    'customer_code' => $request->customer_code,
                    'index_number' => $request->index_number
                ],
                [
                    'tax_group_code' => $request->tax_group_code,
                    'status' => $request->status
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Customer sales tax index saved successfully',
                'data' => $index
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save tax index: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a customer sales tax index.
     */
    public function destroy($customerCode, $indexNumber)
    {
        try {
            $index = CustomerSalesTaxIndex::where('customer_code', $customerCode)
                ->where('index_number', $indexNumber)
                ->firstOrFail();

            // Soft delete: mark index as obsolete instead of physically deleting
            $index->update(['status' => 'O']);

            return response()->json([
                'success' => true,
                'message' => 'Tax index deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tax index: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product tieups for a specific tax index.
     */
    public function getProductTieups($customerCode, $indexNumber)
    {
        try {
            $tieups = CustomerTaxProductTieup::where('customer_code', $customerCode)
                ->where('index_number', $indexNumber)
                ->with('productGroup')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tieups
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load product tieups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save product tieups for a tax index.
     */
    public function saveProductTieups(Request $request, $customerCode, $indexNumber)
    {
        $validator = Validator::make($request->all(), [
            'tieups' => 'required|array',
            'tieups.*.product_group_code' => 'required|string|exists:product_groups,product_group_id',
            'tieups.*.tie_up_enabled' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Delete existing tieups
            CustomerTaxProductTieup::where('customer_code', $customerCode)
                ->where('index_number', $indexNumber)
                ->delete();

            // Insert new tieups
            foreach ($request->tieups as $tieup) {
                CustomerTaxProductTieup::create([
                    'customer_code' => $customerCode,
                    'index_number' => $indexNumber,
                    'product_group_code' => $tieup['product_group_code'],
                    'tie_up_enabled' => $tieup['tie_up_enabled']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product tieups saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save product tieups: ' . $e->getMessage()
            ], 500);
        }
    }
}
