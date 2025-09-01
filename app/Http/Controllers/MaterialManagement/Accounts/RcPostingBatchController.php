<?php

namespace App\Http\Controllers\MaterialManagement\Accounts;

use App\Http\Controllers\Controller;
use App\Models\RcPostingBatch;
use App\Models\RcRt;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RcPostingBatchController extends Controller
{
    /**
     * Display the prepare RC posting batch page
     */
    public function prepareBatch()
    {
        return inertia('material-management/Accounts/Posting-to-Accounts/Post-RC/PrepareRcPostingBatch');
    }

    /**
     * Display the cancel RC posting batch page
     */
    public function cancelBatch()
    {
        return inertia('material-management/Accounts/Posting-to-Accounts/Post-RC/CancelRcPostingBatch');
    }

    /**
     * Display the view & print RC posting batch page
     */
    public function viewPrintBatch()
    {
        return inertia('material-management/Accounts/Posting-to-Accounts/Post-RC/ViewPrintRcPostingBatch');
    }

    /**
     * Display the confirm to post RC page
     */
    public function confirmToPost()
    {
        return inertia('material-management/Accounts/Posting-to-Accounts/Post-RC/ConfirmToPostRc');
    }

    /**
     * Get current periods
     */
    public function getCurrentPeriods(): JsonResponse
    {
        $currentDate = Carbon::now();
        $currentPeriod = $currentDate->format('m Y');

        return response()->json([
            'current_ic_period' => $currentPeriod,
            'current_ap_period' => $currentPeriod,
            'current_gl_period' => $currentPeriod,
            'current_costing_period' => $currentPeriod,
        ]);
    }

    /**
     * Get available RC notes for batch preparation
     */
    public function getAvailableRcNotes(): JsonResponse
    {
        $rcNotes = RcRt::where('transaction_type', 'RC')
            ->where('status', 'Posted')
            ->orderBy('transaction_number', 'desc')
            ->limit(300)
            ->get()
            ->map(function ($note) {
                return [
                    'rc_note_number' => $note->transaction_number,
                    'rc_date' => $note->transaction_date,
                    'p_order_number' => $note->po_number,
                    'vendor_code' => $note->supplier_code,
                    'status' => $note->status,
                    'post_status' => $note->status === 'Posted' ? 'Posted' : 'UnPosted',
                ];
            });

        return response()->json($rcNotes);
    }

    /**
     * Prepare a new RC posting batch
     */
    public function prepareBatchAction(Request $request): JsonResponse
    {
        $request->validate([
            'start_note_number' => 'required|string',
            'end_note_number' => 'required|string',
            'current_ic_period' => 'required|string',
            'current_ap_period' => 'required|string',
            'current_gl_period' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Generate batch number
            $batch = new RcPostingBatch();
            $batch->batch_number = $batch->generateBatchNumber();
            $batch->start_note_number = $request->start_note_number;
            $batch->end_note_number = $request->end_note_number;
            $batch->current_ic_period = $request->current_ic_period;
            $batch->current_ap_period = $request->current_ap_period;
            $batch->current_gl_period = $request->current_gl_period;
            $batch->status = 'New';
            $batch->prepared_by = Auth::user()->user_id;
            $batch->prepared_date = Carbon::now()->toDateString();
            $batch->prepared_time = Carbon::now()->toTimeString();
            $batch->notes = "This program is built with rollback control.\nThis program only allowed 300 transaction per batch.\nPost Accrued is not activated. Those RC with blank invoice# will be skip to batch.";

            // Count records in range
            $totalRecords = RcRt::where('transaction_type', 'RC')
                ->where('transaction_number', '>=', $request->start_note_number)
                ->where('transaction_number', '<=', $request->end_note_number)
                ->where('status', 'Posted')
                ->count();

            $batch->total_records = $totalRecords;
            $batch->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'RC Posting Batch prepared successfully',
                'batch' => $batch
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an RC posting batch
     */
    public function cancelBatchAction(Request $request): JsonResponse
    {
        $request->validate([
            'batch_number' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $batch = RcPostingBatch::where('batch_number', $request->batch_number)
                ->where('status', 'New')
                ->first();

            if (!$batch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batch not found or cannot be cancelled'
                ], 404);
            }

            $batch->status = 'Cancelled';
            $batch->cancelled_by = Auth::user()->user_id;
            $batch->cancelled_date = Carbon::now()->toDateString();
            $batch->cancelled_time = Carbon::now()->toTimeString();
            $batch->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'RC Posting Batch cancelled successfully',
                'batch' => $batch
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get batches for view & print
     */
    public function getBatches(Request $request): JsonResponse
    {
        $query = RcPostingBatch::query();

        // Filter by status
        if ($request->has('status')) {
            $statuses = $request->status;
            if (!empty($statuses)) {
                $query->whereIn('status', $statuses);
            }
        }

        // Filter by batch number range
        if ($request->has('batch_start') && $request->batch_start) {
            $query->where('batch_number', '>=', $request->batch_start);
        }
        if ($request->has('batch_end') && $request->batch_end) {
            $query->where('batch_number', '<=', $request->batch_end);
        }

        $batches = $query->orderBy('created_at', 'desc')
            ->with(['preparedByUser', 'cancelledByUser', 'postedByUser'])
            ->get()
            ->map(function ($batch) {
                return [
                    'id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                    'start_note_number' => $batch->start_note_number,
                    'end_note_number' => $batch->end_note_number,
                    'total_records' => $batch->total_records,
                    'total_errors' => $batch->total_errors,
                    'print_count' => $batch->print_count,
                    'status' => $batch->status,
                    'total_accrued_rc' => $batch->total_accrued_rc,
                    'prepared_by' => $batch->prepared_by,
                    'prepared_date' => $batch->prepared_date,
                    'prepared_time' => $batch->prepared_time,
                    'cancelled_by' => $batch->cancelled_by,
                    'cancelled_date' => $batch->cancelled_date,
                    'cancelled_time' => $batch->cancelled_time,
                    'posted_by' => $batch->posted_by,
                    'posted_date' => $batch->posted_date,
                    'posted_time' => $batch->posted_time,
                    'status_badge_class' => $batch->status_badge_class,
                ];
            });

        return response()->json($batches);
    }

    /**
     * Confirm and post RC batch
     */
    public function confirmToPostAction(Request $request): JsonResponse
    {
        $request->validate([
            'batch_number' => 'required|string',
            'current_ic_period' => 'required|string',
            'current_ap_period' => 'required|string',
            'current_gl_period' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $batch = RcPostingBatch::where('batch_number', $request->batch_number)
                ->where('status', 'New')
                ->first();

            if (!$batch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batch not found or cannot be posted'
                ], 404);
            }

            // Update RC notes to posted
            $updatedRcCount = RcRt::where('transaction_type', 'RC')
                ->where('transaction_number', '>=', $batch->start_note_number)
                ->where('transaction_number', '<=', $batch->end_note_number)
                ->where('status', 'Posted')
                ->update([
                    'posted_date' => Carbon::now()->toDateString(),
                    'posted_time' => Carbon::now()->toTimeString(),
                    'posted_by' => Auth::user()->user_id
                ]);

            // Update batch status
            $batch->status = 'Posted';
            $batch->posted_by = Auth::user()->user_id;
            $batch->posted_date = Carbon::now()->toDateString();
            $batch->posted_time = Carbon::now()->toTimeString();
            $batch->total_records = $updatedRcCount;
            $batch->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'RC Posting Batch posted successfully',
                'batch' => $batch,
                'posted_records' => $updatedRcCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to post batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get batch details for confirmation
     */
    public function getBatchDetails(Request $request): JsonResponse
    {
        $batch = RcPostingBatch::where('batch_number', $request->batch_number)
            ->with(['preparedByUser', 'cancelledByUser', 'postedByUser'])
            ->first();

        if (!$batch) {
            return response()->json([
                'success' => false,
                'message' => 'Batch not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'batch' => $batch
        ]);
    }
}
