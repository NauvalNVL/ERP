<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\Approver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ApproverController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Approver::with('user');
            
            // Apply filters
            if ($request->has('approver_id')) {
                $query->where('approver_id', 'like', '%' . $request->approver_id . '%');
            }
            
            if ($request->has('approver_name')) {
                $query->where('approver_name', 'like', '%' . $request->approver_name . '%');
            }
            
            if ($request->has('pr_authorized')) {
                $query->where('pr_authorized', $request->pr_authorized);
            }
            
            if ($request->has('po_authorized')) {
                $query->where('po_authorized', $request->po_authorized);
            }
            
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }
            
            // Apply sorting
            $sortBy = $request->input('sort_by', 'approver_id');
            $sortDir = $request->input('sort_dir', 'asc');
            
            // Validate sort field to prevent SQL injection
            $allowedSortFields = ['approver_id', 'approver_name', 'user_id', 'email', 'pr_authorized', 'po_authorized', 'is_active'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'approver_id';
            }
            
            $query->orderBy($sortBy, $sortDir);
            
            $approvers = $query->get();
            return response()->json($approvers);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@index: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'approver_id' => 'required|string|max:20|unique:approvers,approver_id',
            'approver_name' => 'required|string|max:100',
            'user_id' => 'nullable|string|max:20|exists:users,user_id',
            'email' => 'required|email|max:150',
            'password' => 'nullable|string|min:6',
            'olda_enabled' => 'boolean',
            'pr_authorized' => 'boolean',
            'pr_level' => 'required_if:pr_authorized,true|integer|min:1|max:10',
            'pr_zero_price_allowed' => 'boolean',
            'pr_approval_style' => 'required_if:pr_authorized,true|in:tick_1_pr,tick_all_pr',
            'pr_price_history' => 'boolean',
            'po_authorized' => 'boolean',
            'po_level' => 'required_if:po_authorized,true|integer|min:1|max:10',
            'po_approval_style' => 'required_if:po_authorized,true|in:tick_1_po,tick_all_po',
            'po_min_limit' => 'required_if:po_authorized,true|numeric|min:0',
            'po_max_limit' => 'required_if:po_authorized,true|numeric|min:0|gte:po_min_limit',
            'is_active' => 'boolean',
        ]);

        try {
            $approver = Approver::create($validated);
            return response()->json($approver->load('user'), 201);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@store: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create approver: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $approver = Approver::with('user')->findOrFail($id);
            return response()->json($approver);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@show: ' . $e->getMessage());
            return response()->json(['error' => 'Approver not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $approver = Approver::findOrFail($id);

        $validated = $request->validate([
            'approver_name' => 'required|string|max:100',
            'user_id' => 'nullable|string|max:20|exists:users,user_id',
            'email' => 'required|email|max:150',
            'password' => 'nullable|string|min:6',
            'olda_enabled' => 'boolean',
            'pr_authorized' => 'boolean',
            'pr_level' => 'required_if:pr_authorized,true|integer|min:1|max:10',
            'pr_zero_price_allowed' => 'boolean',
            'pr_approval_style' => 'required_if:pr_authorized,true|in:tick_1_pr,tick_all_pr',
            'pr_price_history' => 'boolean',
            'po_authorized' => 'boolean',
            'po_level' => 'required_if:po_authorized,true|integer|min:1|max:10',
            'po_approval_style' => 'required_if:po_authorized,true|in:tick_1_po,tick_all_po',
            'po_min_limit' => 'required_if:po_authorized,true|numeric|min:0',
            'po_max_limit' => 'required_if:po_authorized,true|numeric|min:0|gte:po_min_limit',
            'is_active' => 'boolean',
        ]);

        try {
            $approver->update($validated);
            return response()->json($approver->load('user'));
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@update: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update approver: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $approver = Approver::findOrFail($id);
            $approver->delete();
            return response()->json(['message' => 'Approver deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@destroy: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete approver: ' . $e->getMessage()], 500);
        }
    }

    public function toggleActive($id)
    {
        try {
            $approver = Approver::findOrFail($id);
            $approver->is_active = !$approver->is_active;
            $approver->save();
            
            return response()->json([
                'message' => 'Approver status updated successfully',
                'is_active' => $approver->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@toggleActive: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update approver status: ' . $e->getMessage()], 500);
        }
    }

    public function getUsers(Request $request)
    {
        try {
            $query = User::query();
            
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('user_id', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            }
            
            $users = $query->select('user_id', 'name', 'email')
                          ->orderBy('name')
                          ->limit(50)
                          ->get();
            
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@getUsers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch users: ' . $e->getMessage()], 500);
        }
    }

    public function getPrAuthorizedApprovers()
    {
        try {
            $approvers = Approver::prAuthorized()
                ->select('approver_id', 'approver_name', 'pr_level')
                ->orderBy('pr_level')
                ->orderBy('approver_name')
                ->get();
            
            return response()->json($approvers);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@getPrAuthorizedApprovers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch PR authorized approvers: ' . $e->getMessage()], 500);
        }
    }

    public function getPoAuthorizedApprovers()
    {
        try {
            $approvers = Approver::poAuthorized()
                ->select('approver_id', 'approver_name', 'po_level', 'po_min_limit', 'po_max_limit')
                ->orderBy('po_level')
                ->orderBy('approver_name')
                ->get();
            
            return response()->json($approvers);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@getPoAuthorizedApprovers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch PO authorized approvers: ' . $e->getMessage()], 500);
        }
    }

    public function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'approver_id' => 'nullable|string'
        ]);

        try {
            $query = Approver::where('email', $request->email);
            
            if ($request->approver_id) {
                $query->where('approver_id', '!=', $request->approver_id);
            }
            
            $exists = $query->exists();
            
            return response()->json([
                'email' => $request->email,
                'available' => !$exists,
                'message' => $exists ? 'Email already exists' : 'Email is available'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@validateEmail: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to validate email: ' . $e->getMessage()], 500);
        }
    }

    public function viewPrint()
    {
        try {
            return inertia('material-management/system-requirement/purchase-order-setup/ViewPrintApprover');
        } catch (\Exception $e) {
            Log::error('Error in ApproverController@viewPrint: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load view print approver page: ' . $e->getMessage());
        }
    }
} 