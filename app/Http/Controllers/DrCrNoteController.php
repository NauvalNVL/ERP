<?php

namespace App\Http\Controllers;

use App\Models\DrCrNote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DrCrNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = DrCrNote::with(['creator', 'approver']);

            // Apply filters
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            if ($request->filled('type')) {
                $query->byType($request->type);
            }

            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }

            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $notes = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $notes->items(),
                'pagination' => [
                    'current_page' => $notes->currentPage(),
                    'last_page' => $notes->lastPage(),
                    'per_page' => $notes->perPage(),
                    'total' => $notes->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading notes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
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
                'due_date' => 'nullable|date|after_or_equal:note_date',
                'currency' => 'nullable|string|max:3',
                'exchange_rate' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $note = DrCrNote::create([
                'note_number' => DrCrNote::generateNoteNumber($request->note_type),
                'note_type' => $request->note_type,
                'reference_document' => $request->reference_document,
                'reference_type' => $request->reference_type,
                'customer_code' => $request->customer_code,
                'vendor_code' => $request->vendor_code,
                'customer_name' => $request->customer_name,
                'vendor_name' => $request->vendor_name,
                'amount' => $request->amount,
                'description' => $request->description,
                'reason' => $request->reason,
                'status' => 'Draft',
                'note_date' => $request->note_date,
                'due_date' => $request->due_date,
                'currency' => $request->currency ?? 'IDR',
                'exchange_rate' => $request->exchange_rate ?? 1.0000,
                'created_by' => 'system',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Note created successfully',
                'data' => $note->load(['creator', 'approver'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $note = DrCrNote::with(['creator', 'approver'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $note
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Note not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $note = DrCrNote::findOrFail($id);

            if (!$note->canEdit()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note cannot be edited in current status'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
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
                'due_date' => 'nullable|date|after_or_equal:note_date',
                'currency' => 'nullable|string|max:3',
                'exchange_rate' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $note->update($request->only([
                'reference_document',
                'reference_type',
                'customer_code',
                'vendor_code',
                'customer_name',
                'vendor_name',
                'amount',
                'description',
                'reason',
                'note_date',
                'due_date',
                'currency',
                'exchange_rate',
            ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Note updated successfully',
                'data' => $note->load(['creator', 'approver'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $note = DrCrNote::findOrFail($id);

            if (!$note->canDelete()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note cannot be deleted in current status'
                ], 403);
            }

            $note->delete();

            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve the note
     */
    public function approve(Request $request, $id): JsonResponse
    {
        try {
            $note = DrCrNote::findOrFail($id);

            if (!$note->canApprove()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note cannot be approved in current status'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'approval_notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $note->approve(
                'system',
                $request->approval_notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Note approved successfully',
                'data' => $note->load(['creator', 'approver'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject the note
     */
    public function reject(Request $request, $id): JsonResponse
    {
        try {
            $note = DrCrNote::findOrFail($id);

            if (!$note->canApprove()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note cannot be rejected in current status'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'approval_notes' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $note->reject(
                'system',
                $request->approval_notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Note rejected successfully',
                'data' => $note->load(['creator', 'approver'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Post the note
     */
    public function post($id): JsonResponse
    {
        try {
            $note = DrCrNote::findOrFail($id);

            if (!$note->canPost()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note cannot be posted in current status'
                ], 403);
            }

            $note->post('system');

            return response()->json([
                'success' => true,
                'message' => 'Note posted successfully',
                'data' => $note->load(['creator', 'approver'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error posting note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer suggestions
     */
    public function getCustomerSuggestions(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search', '');
            
            // This would typically query your customer/vendor database
            // For now, returning mock data
            $suggestions = [
                ['code' => 'CUST001', 'name' => 'PT. Customer Pertama'],
                ['code' => 'CUST002', 'name' => 'PT. Customer Kedua'],
                ['code' => 'VEND001', 'name' => 'PT. Vendor Pertama'],
                ['code' => 'VEND002', 'name' => 'PT. Vendor Kedua'],
            ];

            if ($search) {
                $suggestions = array_filter($suggestions, function($item) use ($search) {
                    return stripos($item['name'], $search) !== false || 
                           stripos($item['code'], $search) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'data' => array_values($suggestions)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting suggestions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics
     */
    public function getSummary(Request $request): JsonResponse
    {
        try {
            $query = DrCrNote::query();

            // Apply date range filter
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }

            $summary = [
                'total_notes' => $query->count(),
                'total_amount' => $query->sum('amount'),
                'dr_notes' => $query->clone()->byType('DR')->count(),
                'cr_notes' => $query->clone()->byType('CR')->count(),
                'draft_notes' => $query->clone()->byStatus('Draft')->count(),
                'pending_notes' => $query->clone()->byStatus('Pending')->count(),
                'approved_notes' => $query->clone()->byStatus('Approved')->count(),
                'posted_notes' => $query->clone()->byStatus('Posted')->count(),
                'rejected_notes' => $query->clone()->byStatus('Rejected')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting summary: ' . $e->getMessage()
            ], 500);
        }
    }
} 