<?php

namespace App\Http\Controllers\MaterialManagement\InventoryControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\IsMiMoLt;

class IsMiMoLtController extends Controller
{
    /**
     * Display the Prepare IS page.
     */
    public function prepareIs()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/PrepareIS');
    }

    /**
     * Display the Cancel IS page.
     */
    public function cancelIs()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/CancelIS');
    }

    /**
     * Display the Print IS page.
     */
    public function printIs()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/PrintIS');
    }

    /**
     * Display the Prepare MI page.
     */
    public function prepareMi()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/PrepareMI');
    }

    /**
     * Display the Cancel MI page.
     */
    public function cancelMi()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/CancelMI');
    }

    /**
     * Display the Print MI page.
     */
    public function printMi()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/PrintMI');
    }

    /**
     * Display the Prepare MO page.
     */
    public function prepareMo()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/PrepareMO');
    }

    /**
     * Display the View & Print IS/MI/MO/LT Log page.
     */
    public function viewPrintLog()
    {
        return Inertia::render('material-management/Inventory-Control/IS-MI-MO-LT/ViewPrintLog');
    }

    // API Methods for IS Transactions
    public function apiGetIsTransactions(Request $request)
    {
        try {
            $query = IsMiMoLt::where('transaction_type', 'IS')
                ->with(['sku', 'location', 'category']);

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

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('sku', function ($sq) use ($search) {
                          $sq->where('sku', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
                      });
                });
            }

            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching IS transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching transactions'], 500);
        }
    }

    // API Methods for MI Transactions
    public function apiGetMiTransactions(Request $request)
    {
        try {
            $query = IsMiMoLt::where('transaction_type', 'MI')
                ->with(['sku', 'location', 'category']);

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

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('sku', function ($sq) use ($search) {
                          $sq->where('sku', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
                      });
                });
            }

            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching MI transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching transactions'], 500);
        }
    }

    // API Methods for MO Transactions
    public function apiGetMoTransactions(Request $request)
    {
        try {
            $query = IsMiMoLt::where('transaction_type', 'MO')
                ->with(['sku', 'location', 'category']);

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

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('sku', function ($sq) use ($search) {
                          $sq->where('sku', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
                      });
                });
            }

            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching MO transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching transactions'], 500);
        }
    }

    // API Methods for LT Transactions
    public function apiGetLtTransactions(Request $request)
    {
        try {
            $query = IsMiMoLt::where('transaction_type', 'LT')
                ->with(['sku', 'location', 'category']);

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

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('sku', function ($sq) use ($search) {
                          $sq->where('sku', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
                      });
                });
            }

            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching LT transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching transactions'], 500);
        }
    }

    // Store new transaction
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'transaction_type' => 'required|in:IS,MI,MO,LT',
                'sku_code' => 'required|string|max:20',
                'location_code' => 'required|string|max:20',
                'category_code' => 'required|string|max:20',
                'quantity' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'description' => 'required|string|max:500',
                'transaction_date' => 'required|date',
                'reference_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:1000'
            ]);

            $validated['transaction_number'] = IsMiMoLt::generateTransactionNumber($validated['transaction_type']);
            $validated['status'] = 'Draft';
            $validated['total_amount'] = $validated['quantity'] * $validated['unit_price'];
            $validated['created_by'] = auth()->id();

            $transaction = IsMiMoLt::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction->load(['sku', 'location', 'category'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating transaction'], 500);
        }
    }

    // Update transaction
    public function apiUpdate(Request $request, $id)
    {
        try {
            $transaction = IsMiMoLt::findOrFail($id);

            $validated = $request->validate([
                'sku_code' => 'required|string|max:20',
                'location_code' => 'required|string|max:20',
                'category_code' => 'required|string|max:20',
                'quantity' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'description' => 'required|string|max:500',
                'transaction_date' => 'required|date',
                'reference_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:1000'
            ]);

            $validated['total_amount'] = $validated['quantity'] * $validated['unit_price'];
            $validated['updated_by'] = auth()->id();

            $transaction->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction->load(['sku', 'location', 'category'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating transaction'], 500);
        }
    }

    // Cancel transaction
    public function apiCancel(Request $request, $id)
    {
        try {
            $transaction = IsMiMoLt::findOrFail($id);

            if ($transaction->status === 'Posted') {
                return response()->json(['success' => false, 'message' => 'Cannot cancel posted transaction'], 400);
            }

            $transaction->update([
                'status' => 'Cancelled',
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now(),
                'cancellation_reason' => $request->get('reason', 'Cancelled by user')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction cancelled successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error cancelling transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error cancelling transaction'], 500);
        }
    }

    // Post transaction
    public function apiPost(Request $request, $id)
    {
        try {
            $transaction = IsMiMoLt::findOrFail($id);

            if ($transaction->status !== 'Draft') {
                return response()->json(['success' => false, 'message' => 'Only draft transactions can be posted'], 400);
            }

            $transaction->update([
                'status' => 'Posted',
                'posted_by' => auth()->id(),
                'posted_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction posted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error posting transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error posting transaction'], 500);
        }
    }

    // Get lookup data
    public function apiGetSkus(Request $request)
    {
        try {
            $query = \App\Models\MmSku::where('is_active', true);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('sku', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%");
                });
            }

            $skus = $query->select('sku', 'description', 'unit_code')
                ->orderBy('sku')
                ->limit(50)
                ->get();

            return response()->json(['success' => true, 'data' => $skus]);
        } catch (\Exception $e) {
            Log::error('Error fetching SKUs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching SKUs'], 500);
        }
    }

    public function apiGetLocations(Request $request)
    {
        try {
            $query = \App\Models\MmLocation::where('is_active', true);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('code', 'like', "%{$request->search}%")
                      ->orWhere('name', 'like', "%{$request->search}%");
                });
            }

            $locations = $query->select('code', 'name')
                ->orderBy('code')
                ->limit(50)
                ->get();

            return response()->json(['success' => true, 'data' => $locations]);
        } catch (\Exception $e) {
            Log::error('Error fetching locations: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching locations'], 500);
        }
    }

    public function apiGetCategories(Request $request)
    {
        try {
            $query = \App\Models\MmCategory::where('is_active', true);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('code', 'like', "%{$request->search}%")
                      ->orWhere('name', 'like', "%{$request->search}%");
                });
            }

            $categories = $query->select('code', 'name')
                ->orderBy('code')
                ->limit(50)
                ->get();

            return response()->json(['success' => true, 'data' => $categories]);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching categories'], 500);
        }
    }

    // Get all transactions with filters
    public function apiGetTransactions(Request $request)
    {
        try {
            $query = IsMiMoLt::with(['sku', 'location', 'category']);

            // Apply filters
            if ($request->filled('transaction_type')) {
                $query->where('transaction_type', $request->transaction_type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('transaction_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('transaction_date', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                      ->orWhere('sku_code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortField = $request->get('sort_field', 'transaction_date');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Pagination
            $perPage = $request->get('per_page', 25);
            $transactions = $query->paginate($perPage);

            // Calculate summary
            $summary = [
                'total' => IsMiMoLt::count(),
                'posted' => IsMiMoLt::where('status', 'Posted')->count(),
                'draft' => IsMiMoLt::where('status', 'Draft')->count(),
                'cancelled' => IsMiMoLt::where('status', 'Cancelled')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching transactions'], 500);
        }
    }

    // Generate transaction number
    public function apiGenerateNumber(Request $request)
    {
        try {
            $transactionType = $request->input('transaction_type');
            
            if (!in_array($transactionType, ['IS', 'MI', 'MO', 'LT'])) {
                return response()->json(['success' => false, 'message' => 'Invalid transaction type'], 400);
            }

            $transactionNumber = IsMiMoLt::generateTransactionNumber($transactionType);
            
            return response()->json([
                'success' => true,
                'transaction_number' => $transactionNumber
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating transaction number: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error generating transaction number'], 500);
        }
    }

    // Generate report
    public function apiGenerateReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'transaction_type' => 'required|in:IS,MI,MO,LT,ALL',
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'status' => 'nullable|in:Draft,Posted,Cancelled',
                'format' => 'required|in:pdf,excel'
            ]);

            $query = IsMiMoLt::with(['sku', 'location', 'category']);

            if ($validated['transaction_type'] !== 'ALL') {
                $query->where('transaction_type', $validated['transaction_type']);
            }

            $query->whereBetween('transaction_date', [$validated['date_from'], $validated['date_to']]);

            if ($validated['status']) {
                $query->where('status', $validated['status']);
            }

            $transactions = $query->orderBy('transaction_date', 'desc')->get();

            // Generate report data
            $reportData = [
                'period' => $validated['date_from'] . ' to ' . $validated['date_to'],
                'transaction_type' => $validated['transaction_type'],
                'total_transactions' => $transactions->count(),
                'total_amount' => $transactions->sum('total_amount'),
                'transactions' => $transactions
            ];

            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error generating report'], 500);
        }
    }
}
