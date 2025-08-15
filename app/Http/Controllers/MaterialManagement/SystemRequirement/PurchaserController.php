<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\Purchaser;
use App\Models\PurchaserApprovalFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PurchaserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Purchaser::with('approvalFlows');
            
            // Apply filters
            if ($request->has('purchaser_id')) {
                $query->where('purchaser_id', 'like', '%' . $request->purchaser_id . '%');
            }
            
            if ($request->has('purchaser_name')) {
                $query->where('purchaser_name', 'like', '%' . $request->purchaser_name . '%');
            }
            
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }
            
            if ($request->has('department')) {
                $query->where('department', 'like', '%' . $request->department . '%');
            }
            
            // Apply sorting
            $sortBy = $request->input('sort_by', 'purchaser_id');
            $sortDir = $request->input('sort_dir', 'asc');
            
            // Validate sort field to prevent SQL injection
            $allowedSortFields = ['purchaser_id', 'purchaser_name', 'type', 'email', 'department', 'position', 'is_active'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'purchaser_id';
            }
            
            $query->orderBy($sortBy, $sortDir);
            
            $purchasers = $query->get();
            return response()->json($purchasers);
        } catch (\Exception $e) {
            Log::error('Error in PurchaserController@index: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchaser_id' => 'required|string|max:20|unique:purchasers,purchaser_id',
            'purchaser_name' => 'required|string|max:100',
            'type' => 'required|in:PU,RQ',
            'email' => 'required|email|max:150',
            'password' => 'nullable|string|min:6',
            'cc_to_self' => 'boolean',
            'is_active' => 'boolean',
            'department' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:50',
            'employee_id' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $purchaser = Purchaser::create($validated);
        return response()->json($purchaser);
    }

    public function show($id)
    {
        $purchaser = Purchaser::with('approvalFlows')->findOrFail($id);
        return response()->json($purchaser);
    }

    public function update(Request $request, $id)
    {
        $purchaser = Purchaser::findOrFail($id);

        $validated = $request->validate([
            'purchaser_name' => 'required|string|max:100',
            'type' => 'required|in:PU,RQ',
            'email' => 'required|email|max:150',
            'password' => 'nullable|string|min:6',
            'cc_to_self' => 'boolean',
            'is_active' => 'boolean',
            'department' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:50',
            'employee_id' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $purchaser->update($validated);
        return response()->json($purchaser);
    }

    public function destroy($id)
    {
        $purchaser = Purchaser::findOrFail($id);
        
        // Check if purchaser has any approval flows
        if ($purchaser->approvalFlows()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete purchaser with existing approval flows. Please remove approval flows first.'
            ], 422);
        }
        
        $purchaser->delete();
        return response()->json(['message' => 'Purchaser deleted successfully']);
    }

    public function setupApprovalFlow(Request $request, $purchaserId)
    {
        $validated = $request->validate([
            'approvers' => 'required|array',
            'approvers.*.approver_id' => 'required|string|max:20',
            'approvers.*.approver_name' => 'required|string|max:100',
            'approvers.*.level_1' => 'boolean',
            'approvers.*.level_2' => 'boolean',
            'approvers.*.level_3' => 'boolean',
            'approvers.*.email_notification' => 'boolean',
        ]);

        $purchaser = Purchaser::findOrFail($purchaserId);

        try {
            DB::beginTransaction();

            // Delete existing approval flows
            $purchaser->approvalFlows()->delete();

            // Create new approval flows
            foreach ($validated['approvers'] as $approverData) {
                $approverData['purchaser_id'] = $purchaserId;
                PurchaserApprovalFlow::create($approverData);
            }

            DB::commit();

            $purchaser->load('approvalFlows');
            return response()->json([
                'message' => 'Approval flow setup successfully',
                'purchaser' => $purchaser
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting up approval flow: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to setup approval flow: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getApprovalFlow($purchaserId)
    {
        $purchaser = Purchaser::with('approvalFlows')->findOrFail($purchaserId);
        return response()->json($purchaser->approvalFlows);
    }

    public function toggleActive($id)
    {
        $purchaser = Purchaser::findOrFail($id);
        $purchaser->is_active = !$purchaser->is_active;
        $purchaser->save();

        return response()->json([
            'message' => 'Purchaser status updated successfully',
            'purchaser' => $purchaser
        ]);
    }

    public function getByType(Request $request)
    {
        $type = $request->input('type', 'PU');
        $purchasers = Purchaser::byType($type)->active()->get();
        return response()->json($purchasers);
    }

    public function searchApprovers(Request $request)
    {
        $query = $request->input('query', '');
        
        // In a real application, this might search from a user directory or employee database
        // For now, we'll search existing purchasers and users
        $purchasers = Purchaser::where('purchaser_name', 'like', "%{$query}%")
            ->orWhere('purchaser_id', 'like', "%{$query}%")
            ->select('purchaser_id as id', 'purchaser_name as name', 'email')
            ->limit(10)
            ->get();

        return response()->json($purchasers);
    }

    public function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');
        
        // Simple email validation - in real app might check against company directory
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        
        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? 'Email is valid' : 'Invalid email format'
        ]);
    }

    public function testEmailFlow(Request $request, $purchaserId)
    {
        $validated = $request->validate([
            'test_message' => 'required|string|max:255'
        ]);

        $purchaser = Purchaser::with('approvalFlows')->findOrFail($purchaserId);
        
        // In a real application, this would send test emails
        // For now, we'll just return success
        Log::info('Test email flow for purchaser: ' . $purchaserId, [
            'message' => $validated['test_message'],
            'approval_flows' => $purchaser->approvalFlows->toArray()
        ]);

        return response()->json([
            'message' => 'Test email sent successfully to approval flow',
            'recipients' => $purchaser->approvalFlows->where('email_notification', true)->count()
        ]);
    }

    /**
     * Get purchasers data for printing
     */
    public function getForPrint(Request $request)
    {
        try {
            $query = Purchaser::query();
            
            // Apply filters
            if ($request->filled('purchaser_id')) {
                $query->where('purchaser_id', 'like', '%' . $request->purchaser_id . '%');
            }
            
            if ($request->filled('purchaser_name')) {
                $query->where('purchaser_name', 'like', '%' . $request->purchaser_name . '%');
            }
            
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }
            
            if ($request->filled('department')) {
                $query->where('department', 'like', '%' . $request->department . '%');
            }
            
            // Apply sorting
            $sortBy = $request->input('sort_by', 'purchaser_id');
            $sortDir = $request->input('sort_dir', 'asc');
            
            // Validate sort field to prevent SQL injection
            $allowedSortFields = ['purchaser_id', 'purchaser_name', 'type', 'email', 'department', 'position', 'is_active'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'purchaser_id';
            }
            
            $query->orderBy($sortBy, $sortDir);
            
            // Get all records (no pagination for printing)
            $purchasers = $query->get();
            
            return response()->json($purchasers);
        } catch (\Exception $e) {
            Log::error('Error in PurchaserController@getForPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }
} 