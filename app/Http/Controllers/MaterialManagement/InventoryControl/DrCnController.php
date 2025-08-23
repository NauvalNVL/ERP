<?php

namespace App\Http\Controllers\MaterialManagement\InventoryControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\DrCrNote;
use App\Models\Vendor;
use App\Models\MmSku;
use App\Models\MmLocation;
use App\Models\User;

class DrCnController extends Controller
{
    // DN (Debit Note) Methods
    public function prepareDn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/PrepareDN', [
            'title' => 'Prepare Debit Note',
            'noteType' => 'DR'
        ]);
    }

    public function amendDn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/AmendDN', [
            'title' => 'Amend Debit Note',
            'noteType' => 'DR'
        ]);
    }

    public function cancelDn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/CancelDN', [
            'title' => 'Cancel Debit Note',
            'noteType' => 'DR'
        ]);
    }

    public function printDn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/PrintDN', [
            'title' => 'Print Debit Note',
            'noteType' => 'DR'
        ]);
    }

    public function viewPrintDnLog()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/ViewPrintDNLog', [
            'title' => 'View & Print Debit Note Log',
            'noteType' => 'DR'
        ]);
    }

    // CN (Credit Note) Methods
    public function prepareCn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/PrepareCN', [
            'title' => 'Prepare Credit Note',
            'noteType' => 'CR'
        ]);
    }

    public function amendCn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/AmendCN', [
            'title' => 'Amend Credit Note',
            'noteType' => 'CR'
        ]);
    }

    public function cancelCn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/CancelCN', [
            'title' => 'Cancel Credit Note',
            'noteType' => 'CR'
        ]);
    }

    public function printCn()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/PrintCN', [
            'title' => 'Print Credit Note',
            'noteType' => 'CR'
        ]);
    }

    public function viewPrintCnLog()
    {
        return Inertia::render('material-management/Inventory-Control/DR-CN/ViewPrintCNLog', [
            'title' => 'View & Print Credit Note Log',
            'noteType' => 'CR'
        ]);
    }

    // API Methods
    public function apiGenerateNoteNumber(Request $request)
    {
        try {
            $validated = $request->validate([
                'note_type' => 'required|in:DR,CR'
            ]);

            $noteNumber = DrCrNote::generateNoteNumber($validated['note_type']);
            
            return response()->json([
                'success' => true,
                'note_number' => $noteNumber
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating note number: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error generating note number'], 500);
        }
    }

    public function apiGetDnTransactions(Request $request)
    {
        try {
            $query = DrCrNote::where('note_type', 'DR');
            
            if ($request->filled('search')) {
                $query->search($request->search);
            }
            
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }
            
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }
            
            $transactions = $query->with(['creator', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            return response()->json(['success' => true, 'data' => $transactions]);
        } catch (\Exception $e) {
            Log::error('Error fetching DN transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching transactions'], 500);
        }
    }

    public function apiGetCnTransactions(Request $request)
    {
        try {
            $query = DrCrNote::where('note_type', 'CR');
            
            if ($request->filled('search')) {
                $query->search($request->search);
            }
            
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }
            
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }
            
            $transactions = $query->with(['creator', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            return response()->json(['success' => true, 'data' => $transactions]);
        } catch (\Exception $e) {
            Log::error('Error fetching CN transactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching transactions'], 500);
        }
    }

    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'note_type' => 'required|in:DR,CR',
                'reference_document' => 'nullable|string|max:100',
                'reference_type' => 'nullable|string|max:50',
                'customer_code' => 'nullable|string|max:20',
                'vendor_code' => 'nullable|string|max:20',
                'customer_name' => 'nullable|string|max:200',
                'vendor_name' => 'nullable|string|max:200',
                'amount' => 'required|numeric|min:0',
                'description' => 'required|string',
                'reason' => 'nullable|string',
                'note_date' => 'required|date',
                'due_date' => 'nullable|date',
                'currency' => 'required|string|max:3',
                'exchange_rate' => 'required|numeric|min:0'
            ]);

            $validated['note_number'] = DrCrNote::generateNoteNumber($validated['note_type']);
            $validated['status'] = 'Draft';
            $validated['created_by'] = auth()->user()->username ?? 'system';
            $validated['is_active'] = true;

            $note = DrCrNote::create($validated);
            
            return response()->json([
                'success' => true, 
                'message' => 'Note created successfully',
                'data' => $note->load(['creator'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating note: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating note'], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $note = DrCrNote::findOrFail($id);
            
            if (!$note->canEdit()) {
                return response()->json(['success' => false, 'message' => 'Note cannot be edited'], 400);
            }

            $validated = $request->validate([
                'reference_document' => 'nullable|string|max:100',
                'reference_type' => 'nullable|string|max:50',
                'customer_code' => 'nullable|string|max:20',
                'vendor_code' => 'nullable|string|max:20',
                'customer_name' => 'nullable|string|max:200',
                'vendor_name' => 'nullable|string|max:200',
                'amount' => 'required|numeric|min:0',
                'description' => 'required|string',
                'reason' => 'nullable|string',
                'note_date' => 'required|date',
                'due_date' => 'nullable|date',
                'currency' => 'required|string|max:3',
                'exchange_rate' => 'required|numeric|min:0'
            ]);

            $note->update($validated);
            
            return response()->json([
                'success' => true, 
                'message' => 'Note updated successfully',
                'data' => $note->load(['creator'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating note: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating note'], 500);
        }
    }

    public function apiPost(Request $request, $id)
    {
        try {
            $note = DrCrNote::findOrFail($id);
            
            if (!$note->canPost()) {
                return response()->json(['success' => false, 'message' => 'Note cannot be posted'], 400);
            }

            $note->post(auth()->user()->username ?? 'system');
            
            return response()->json([
                'success' => true, 
                'message' => 'Note posted successfully',
                'data' => $note->load(['creator', 'approver'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error posting note: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error posting note'], 500);
        }
    }

    public function apiCancel(Request $request, $id)
    {
        try {
            $note = DrCrNote::findOrFail($id);
            
            if (!$note->canDelete()) {
                return response()->json(['success' => false, 'message' => 'Note cannot be cancelled'], 400);
            }

            $note->update([
                'status' => 'Cancelled',
                'approved_by' => auth()->user()->username ?? 'system',
                'approved_at' => now(),
                'approval_notes' => $request->input('notes', 'Cancelled by user')
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Note cancelled successfully',
                'data' => $note->load(['creator', 'approver'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error cancelling note: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error cancelling note'], 500);
        }
    }

    public function apiGetVendors(Request $request)
    {
        try {
            $query = Vendor::where('is_active', true);
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('ap_ac_number', 'like', '%' . $request->search . '%')
                      ->orWhere('vendor_name', 'like', '%' . $request->search . '%');
                });
            }
            $vendors = $query->select('ap_ac_number as code', 'vendor_name as name')
                ->orderBy('vendor_name')
                ->limit(20)
                ->get();
            return response()->json(['success' => true, 'data' => $vendors]);
        } catch (\Exception $e) {
            Log::error('Error fetching vendors: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching vendors'], 500);
        }
    }

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
            $skus = $query->select('sku as code', 'sku_name as name')
                ->orderBy('sku_name')
                ->limit(20)
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

    public function apiGenerateReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'note_type' => 'required|in:DR,CR',
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'status' => 'nullable|string',
                'format' => 'required|in:pdf,excel'
            ]);

            $query = DrCrNote::where('note_type', $validated['note_type'])
                ->byDateRange($validated['date_from'], $validated['date_to']);

            if ($validated['status']) {
                $query->byStatus($validated['status']);
            }

            $notes = $query->with(['creator', 'approver'])->get();

            // Mock report generation
            $reportData = [
                'title' => ($validated['note_type'] === 'DR' ? 'Debit' : 'Credit') . ' Note Report',
                'period' => $validated['date_from'] . ' to ' . $validated['date_to'],
                'total_count' => $notes->count(),
                'total_amount' => $notes->sum('amount'),
                'notes' => $notes
            ];

            return response()->json([
                'success' => true,
                'message' => 'Report generated successfully',
                'data' => $reportData
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error generating report'], 500);
        }
    }
}
