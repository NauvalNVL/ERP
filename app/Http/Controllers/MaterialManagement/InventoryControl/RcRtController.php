<?php

namespace App\Http\Controllers\MaterialManagement\InventoryControl;

use App\Http\Controllers\Controller;
use App\Models\RcRt;
use App\Models\Vendor;
use App\Models\MmSku;
use App\Models\MmLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RcRtController extends Controller
{
    /**
     * Display the Prepare RC page
     */
    public function prepareRc()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/PrepareRC', [
            'header' => 'Prepare Receipt (RC)'
        ]);
    }

    /**
     * Display the Amend RC page
     */
    public function amendRc()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/AmendRC', [
            'header' => 'Amend Receipt (RC)'
        ]);
    }

    /**
     * Display the Print RC page
     */
    public function printRc()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/PrintRC', [
            'header' => 'Print Receipt (RC)'
        ]);
    }

    /**
     * Display the View & Print RC Log page
     */
    public function viewPrintRcLog()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/ViewPrintRCLog', [
            'header' => 'View & Print Receipt (RC) Log'
        ]);
    }

    /**
     * Display the Prepare RT page
     */
    public function prepareRt()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/PrepareRT', [
            'header' => 'Prepare Return (RT)'
        ]);
    }

    /**
     * Display the Amend RT page
     */
    public function amendRt()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/AmendRT', [
            'header' => 'Amend Return (RT)'
        ]);
    }

    /**
     * Display the Print RT page
     */
    public function printRt()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/PrintRT', [
            'header' => 'Print Return (RT)'
        ]);
    }

    /**
     * Display the View & Print RT Log page
     */
    public function viewPrintRtLog()
    {
        return Inertia::render('material-management/Inventory-Control/RC-RT/ViewPrintRTLog', [
            'header' => 'View & Print Return (RT) Log'
        ]);
    }

    /**
     * API: Get RC transactions
     */
    public function apiGetRcTransactions(Request $request)
    {
        try {
            $query = RcRt::receipts()
                ->with(['supplier', 'sku', 'location', 'creator', 'approver', 'poster'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('transaction_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('transaction_date', '<=', $request->date_to);
            }

            if ($request->filled('supplier')) {
                $query->where('supplier_code', 'like', '%' . $request->supplier . '%');
            }

            if ($request->filled('sku')) {
                $query->where('sku_code', 'like', '%' . $request->sku . '%');
            }

            $transactions = $query->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'summary' => [
                    'total' => $transactions->total(),
                    'draft' => RcRt::receipts()->draft()->count(),
                    'posted' => RcRt::receipts()->posted()->count(),
                    'cancelled' => RcRt::receipts()->cancelled()->count(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching RC transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching data'], 500);
        }
    }

    /**
     * API: Get RT transactions
     */
    public function apiGetRtTransactions(Request $request)
    {
        try {
            $query = RcRt::returns()
                ->with(['supplier', 'sku', 'location', 'creator', 'approver', 'poster'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('transaction_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('transaction_date', '<=', $request->date_to);
            }

            if ($request->filled('supplier')) {
                $query->where('supplier_code', 'like', '%' . $request->supplier . '%');
            }

            if ($request->filled('sku')) {
                $query->where('sku_code', 'like', '%' . $request->sku . '%');
            }

            $transactions = $query->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'summary' => [
                    'total' => $transactions->total(),
                    'draft' => RcRt::returns()->draft()->count(),
                    'posted' => RcRt::returns()->posted()->count(),
                    'cancelled' => RcRt::returns()->cancelled()->count(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching RT transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching data'], 500);
        }
    }

    /**
     * API: Store new RC/RT transaction
     */
    public function apiStore(Request $request)
    {
        try {
            $request->validate([
                'transaction_type' => 'required|in:RC,RT',
                'transaction_date' => 'required|date',
                'supplier_code' => 'required|string|max:20',
                'supplier_name' => 'required|string|max:100',
                'po_number' => 'nullable|string|max:50',
                'sku_code' => 'required|string|max:20',
                'sku_name' => 'required|string|max:100',
                'quantity' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'location_code' => 'required|string|max:20',
                'location_name' => 'required|string|max:100',
                'remarks' => 'nullable|string',
            ]);

            // Generate transaction number
            $prefix = $request->transaction_type;
            $year = date('Y');
            $month = date('m');
            $lastTransaction = RcRt::where('transaction_type', $request->transaction_type)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('id', 'desc')
                ->first();

            $sequence = $lastTransaction ? intval(substr($lastTransaction->transaction_number, -4)) + 1 : 1;
            $transactionNumber = $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Calculate total amount
            $totalAmount = $request->quantity * $request->unit_price;

            $transaction = RcRt::create([
                'transaction_number' => $transactionNumber,
                'transaction_type' => $request->transaction_type,
                'transaction_date' => $request->transaction_date,
                'supplier_code' => $request->supplier_code,
                'supplier_name' => $request->supplier_name,
                'po_number' => $request->po_number,
                'sku_code' => $request->sku_code,
                'sku_name' => $request->sku_name,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_amount' => $totalAmount,
                'location_code' => $request->location_code,
                'location_name' => $request->location_name,
                'remarks' => $request->remarks,
                'created_by' => auth()->user()->username ?? 'system',
                'status' => 'Draft'
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->transaction_type . ' transaction created successfully',
                'data' => $transaction->load(['supplier', 'sku', 'location', 'creator'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating RC/RT transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating transaction'], 500);
        }
    }

    /**
     * API: Update RC/RT transaction
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $transaction = RcRt::findOrFail($id);

            // Only allow updates for Draft transactions
            if ($transaction->status !== 'Draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only Draft transactions can be updated'
                ], 422);
            }

            $request->validate([
                'transaction_date' => 'required|date',
                'supplier_code' => 'required|string|max:20',
                'supplier_name' => 'required|string|max:100',
                'po_number' => 'nullable|string|max:50',
                'sku_code' => 'required|string|max:20',
                'sku_name' => 'required|string|max:100',
                'quantity' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'location_code' => 'required|string|max:20',
                'location_name' => 'required|string|max:100',
                'remarks' => 'nullable|string',
            ]);

            // Calculate total amount
            $totalAmount = $request->quantity * $request->unit_price;

            $transaction->update([
                'transaction_date' => $request->transaction_date,
                'supplier_code' => $request->supplier_code,
                'supplier_name' => $request->supplier_name,
                'po_number' => $request->po_number,
                'sku_code' => $request->sku_code,
                'sku_name' => $request->sku_name,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_amount' => $totalAmount,
                'location_code' => $request->location_code,
                'location_name' => $request->location_name,
                'remarks' => $request->remarks,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction->load(['supplier', 'sku', 'location', 'creator'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating RC/RT transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating transaction'], 500);
        }
    }

    /**
     * API: Post RC/RT transaction
     */
    public function apiPost(Request $request, $id)
    {
        try {
            $transaction = RcRt::findOrFail($id);

            if ($transaction->status !== 'Draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only Draft transactions can be posted'
                ], 422);
            }

            DB::beginTransaction();

            $transaction->update([
                'status' => 'Posted',
                'posted_by' => auth()->user()->username ?? 'system',
                'posted_at' => now(),
            ]);

            // Here you would typically update inventory balances
            // For now, we'll just mark it as posted

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction posted successfully',
                'data' => $transaction->load(['supplier', 'sku', 'location', 'creator', 'poster'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error posting RC/RT transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error posting transaction'], 500);
        }
    }

    /**
     * API: Cancel RC/RT transaction
     */
    public function apiCancel(Request $request, $id)
    {
        try {
            $transaction = RcRt::findOrFail($id);

            if ($transaction->status === 'Cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction is already cancelled'
                ], 422);
            }

            $request->validate([
                'cancellation_reason' => 'required|string|max:500'
            ]);

            $transaction->update([
                'status' => 'Cancelled',
                'cancelled_by' => auth()->user()->username ?? 'system',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction cancelled successfully',
                'data' => $transaction->load(['supplier', 'sku', 'location', 'creator', 'canceller'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error cancelling RC/RT transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error cancelling transaction'], 500);
        }
    }

    /**
     * API: Get suppliers for dropdown
     */
    public function apiGetSuppliers(Request $request)
    {
        try {
            $query = Vendor::where('is_active', true);

            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('ap_ac_number', 'like', '%' . $request->search . '%')
                      ->orWhere('vendor_name', 'like', '%' . $request->search . '%');
                });
            }

            $suppliers = $query->select('ap_ac_number as code', 'vendor_name as name')
                ->orderBy('vendor_name')
                ->limit(20)
                ->get();

            return response()->json(['success' => true, 'data' => $suppliers]);
        } catch (\Exception $e) {
            Log::error('Error fetching suppliers: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching suppliers'], 500);
        }
    }

    /**
     * API: Get SKUs for dropdown
     */
    public function apiGetSkus(Request $request)
    {
        try {
            $query = MmSku::where('is_active', true);

            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('sku', 'like', '%' . $request->search . '%')
                      ->orWhere('sku_name', 'like', '%' . $request->search . '%');
                });
            }

            $skus = $query->select('sku as code', 'sku_name as name', 'uom')
                ->orderBy('sku_name')
                ->limit(20)
                ->get();

            return response()->json(['success' => true, 'data' => $skus]);
        } catch (\Exception $e) {
            Log::error('Error fetching SKUs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching SKUs'], 500);
        }
    }

    /**
     * API: Get locations for dropdown
     */
    public function apiGetLocations(Request $request)
    {
        try {
            $query = MmLocation::where('is_active', true);

            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('code', 'like', '%' . $request->search . '%')
                      ->orWhere('name', 'like', '%' . $request->search . '%');
                });
            }

            $locations = $query->select('code', 'name')
                ->orderBy('name')
                ->limit(20)
                ->get();

            return response()->json(['success' => true, 'data' => $locations]);
        } catch (\Exception $e) {
            Log::error('Error fetching locations: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching locations'], 500);
        }
    }

    /**
     * API: Generate RC/RT report
     */
    public function apiGenerateReport(Request $request)
    {
        try {
            $request->validate([
                'transaction_type' => 'required|in:RC,RT',
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'format' => 'required|in:pdf,excel'
            ]);

            $query = RcRt::where('transaction_type', $request->transaction_type)
                ->whereBetween('transaction_date', [$request->date_from, $request->date_to])
                ->with(['supplier', 'sku', 'location', 'creator', 'approver', 'poster']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $transactions = $query->orderBy('transaction_date')->get();

            // For now, return JSON. In a real implementation, you'd generate PDF/Excel
            return response()->json([
                'success' => true,
                'message' => 'Report generated successfully',
                'data' => [
                    'transactions' => $transactions,
                    'summary' => [
                        'total_transactions' => $transactions->count(),
                        'total_amount' => $transactions->sum('total_amount'),
                        'total_quantity' => $transactions->sum('quantity'),
                        'draft_count' => $transactions->where('status', 'Draft')->count(),
                        'posted_count' => $transactions->where('status', 'Posted')->count(),
                        'cancelled_count' => $transactions->where('status', 'Cancelled')->count(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating RC/RT report: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error generating report'], 500);
        }
    }
}
