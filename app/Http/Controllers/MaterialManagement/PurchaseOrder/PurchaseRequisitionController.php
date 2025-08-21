<?php

namespace App\Http\Controllers\MaterialManagement\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequisition;
use App\Models\PrItem;
use App\Models\PrApproval;
use App\Models\User;
use App\Models\MmSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PurchaseRequisitionController extends Controller
{
    /**
     * Display the PR preparation page
     */
    public function index()
    {
        return Inertia::render('material-management/Purchase-Order/PR-PO/PreparePR');
    }

    /**
     * Get PR list with filters
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = PurchaseRequisition::with(['requestor', 'approvals', 'items'])
                ->select([
                    'id', 'pr_number', 'department_name', 'requestor_name', 
                    'request_date', 'required_date', 'priority', 'status', 
                    'total_estimated_value', 'created_at'
                ]);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('department')) {
                $query->where('department_code', $request->department);
            }

            if ($request->filled('requestor')) {
                $query->where('requestor_id', $request->requestor);
            }

            if ($request->filled('date_from')) {
                $query->where('request_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('request_date', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Sort
            $sortField = $request->get('sort_field', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Paginate
            $perPage = $request->get('per_page', 15);
            $prs = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $prs->items(),
                'pagination' => [
                    'current_page' => $prs->currentPage(),
                    'last_page' => $prs->lastPage(),
                    'per_page' => $prs->perPage(),
                    'total' => $prs->total(),
                    'from' => $prs->firstItem(),
                    'to' => $prs->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase requisitions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new PR
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pr_period' => 'required|integer|between:1,12',
                'pr_year' => 'required|integer|min:2020|max:2050',
                'department_code' => 'nullable|string|max:20',
                'department_name' => 'nullable|string|max:100',
                'requestor_id' => 'required|exists:users,id',
                'requestor_name' => 'required|string|max:100',
                'request_date' => 'required|date',
                'required_date' => 'required|date|after:request_date',
                'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
                'budget_code' => 'nullable|string|max:50',
                'description' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.sku_code' => 'nullable|string|max:50',
                'items.*.description' => 'required|string|max:500',
                'items.*.specification' => 'nullable|string|max:500',
                'items.*.quantity' => 'required|numeric|min:0.001',
                'items.*.unit' => 'nullable|string|max:20',
                'items.*.estimated_price' => 'required|numeric|min:0',
                'items.*.urgency' => 'nullable|in:LOW,MEDIUM,HIGH,CRITICAL',
                'items.*.notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Create PR
            $prData = $request->except('items');
            $prData['created_by'] = auth()->id();
            
            $pr = PurchaseRequisition::create($prData);

            // Create PR items
            $lineNumber = 1;
            $totalValue = 0;

            foreach ($request->items as $itemData) {
                $itemData['pr_id'] = $pr->id;
                $itemData['line_number'] = $lineNumber++;
                $itemData['total_price'] = $itemData['quantity'] * $itemData['estimated_price'];
                $itemData['remaining_quantity'] = $itemData['quantity'];
                $itemData['created_by'] = auth()->id();

                $item = PrItem::create($itemData);
                
                // Update stock information
                $item->updateStockInfo();
                
                $totalValue += $itemData['total_price'];
            }

            // Update total estimated value
            $pr->update(['total_estimated_value' => $totalValue]);

            // Create approval workflow if status is PENDING_APPROVAL
            if ($request->status === PurchaseRequisition::STATUS_PENDING_APPROVAL) {
                $this->createApprovalWorkflow($pr);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase requisition created successfully',
                'data' => $pr->load(['items', 'approvals']),
                'pr_number' => $pr->pr_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase requisition',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show specific PR
     */
    public function show($id)
    {
        try {
            $pr = PurchaseRequisition::with([
                'items' => function($query) {
                    $query->orderBy('line_number');
                },
                'approvals' => function($query) {
                    $query->with(['approver', 'delegatedFrom'])->orderBy('approval_sequence');
                },
                'requestor',
                'approvedBy',
                'rejectedBy'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pr
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase requisition not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update PR
     */
    public function update(Request $request, $id)
    {
        try {
            $pr = PurchaseRequisition::findOrFail($id);

            // Check if PR can be edited
            if (!$pr->canBeEdited()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This PR cannot be edited in its current status'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'pr_period' => 'sometimes|integer|between:1,12',
                'pr_year' => 'sometimes|integer|min:2020|max:2050',
                'department_code' => 'nullable|string|max:20',
                'department_name' => 'nullable|string|max:100',
                'requestor_id' => 'sometimes|exists:users,id',
                'requestor_name' => 'sometimes|string|max:100',
                'request_date' => 'sometimes|date',
                'required_date' => 'sometimes|date|after:request_date',
                'priority' => 'sometimes|in:LOW,MEDIUM,HIGH,URGENT',
                'budget_code' => 'nullable|string|max:50',
                'description' => 'nullable|string|max:1000',
                'items' => 'sometimes|array|min:1',
                'items.*.sku_code' => 'nullable|string|max:50',
                'items.*.description' => 'required|string|max:500',
                'items.*.specification' => 'nullable|string|max:500',
                'items.*.quantity' => 'required|numeric|min:0.001',
                'items.*.unit' => 'nullable|string|max:20',
                'items.*.estimated_price' => 'required|numeric|min:0',
                'items.*.urgency' => 'nullable|in:LOW,MEDIUM,HIGH,CRITICAL',
                'items.*.notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Update PR data
            $prData = $request->except('items');
            $prData['updated_by'] = auth()->id();
            $pr->update($prData);

            // Update items if provided
            if ($request->has('items')) {
                // Delete existing items
                $pr->items()->delete();

                // Create new items
                $lineNumber = 1;
                $totalValue = 0;

                foreach ($request->items as $itemData) {
                    $itemData['pr_id'] = $pr->id;
                    $itemData['line_number'] = $lineNumber++;
                    $itemData['total_price'] = $itemData['quantity'] * $itemData['estimated_price'];
                    $itemData['remaining_quantity'] = $itemData['quantity'];
                    $itemData['created_by'] = auth()->id();

                    $item = PrItem::create($itemData);
                    $item->updateStockInfo();
                    
                    $totalValue += $itemData['total_price'];
                }

                // Update total estimated value
                $pr->update(['total_estimated_value' => $totalValue]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase requisition updated successfully',
                'data' => $pr->load(['items', 'approvals'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update purchase requisition',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete PR
     */
    public function destroy($id)
    {
        try {
            $pr = PurchaseRequisition::findOrFail($id);

            // Check if PR can be deleted
            if (!$pr->canBeEdited()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This PR cannot be deleted in its current status'
                ], 422);
            }

            $pr->delete();

            return response()->json([
                'success' => true,
                'message' => 'Purchase requisition deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete purchase requisition',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit PR for approval
     */
    public function submit($id)
    {
        try {
            $pr = PurchaseRequisition::findOrFail($id);

            if ($pr->status !== PurchaseRequisition::STATUS_DRAFT) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only draft PRs can be submitted for approval'
                ], 422);
            }

            DB::beginTransaction();

            // Update status
            $pr->update(['status' => PurchaseRequisition::STATUS_PENDING_APPROVAL]);

            // Create approval workflow
            $this->createApprovalWorkflow($pr);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase requisition submitted for approval successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit purchase requisition',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve PR
     */
    public function approve(Request $request, $id)
    {
        try {
            $pr = PurchaseRequisition::findOrFail($id);

            if (!$pr->canBeApproved()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This PR cannot be approved in its current status'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'comments' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Find current approval
            $currentApproval = PrApproval::getCurrentApprover($pr->id);

            if (!$currentApproval || $currentApproval->approver_id !== auth()->id()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to approve this PR at this stage'
                ], 403);
            }

            // Approve
            $currentApproval->approve($request->comments);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase requisition approved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve purchase requisition',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject PR
     */
    public function reject(Request $request, $id)
    {
        try {
            $pr = PurchaseRequisition::findOrFail($id);

            if (!$pr->canBeApproved()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This PR cannot be rejected in its current status'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Find current approval
            $currentApproval = PrApproval::getCurrentApprover($pr->id);

            if (!$currentApproval || $currentApproval->approver_id !== auth()->id()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to reject this PR at this stage'
                ], 403);
            }

            // Reject
            $currentApproval->reject($request->reason);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase requisition rejected successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject purchase requisition',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel PR
     */
    public function cancel(Request $request, $id)
    {
        try {
            $pr = PurchaseRequisition::findOrFail($id);

            if (!$pr->canBeCancelled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This PR cannot be cancelled in its current status'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pr->cancel($request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Purchase requisition cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel purchase requisition',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get PR statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_prs' => PurchaseRequisition::count(),
                'draft_prs' => PurchaseRequisition::draft()->count(),
                'pending_approval' => PurchaseRequisition::pending()->count(),
                'approved_prs' => PurchaseRequisition::approved()->count(),
                'total_value' => PurchaseRequisition::sum('total_estimated_value'),
                'urgent_prs' => PurchaseRequisition::where('priority', 'URGENT')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get my pending approvals
     */
    public function myPendingApprovals()
    {
        try {
            $approvals = PrApproval::where('approver_id', auth()->id())
                ->where('action', PrApproval::ACTION_PENDING)
                ->with(['purchaseRequisition', 'purchaseRequisition.requestor'])
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $approvals
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get pending approvals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create approval workflow
     */
    private function createApprovalWorkflow($pr)
    {
        // This is a simplified workflow. In real implementation,
        // you would define approval rules based on amount, department, etc.
        
        $approvers = [];
        
        // Example workflow based on total value
        $totalValue = $pr->total_estimated_value;
        
        if ($totalValue >= 100000000) { // 100M IDR
            // High value requires multiple approvals
            $approvers = [
                ['user_id' => $this->findDepartmentManager($pr->department_code), 'level' => 2, 'required' => true],
                ['user_id' => $this->findFinanceManager(), 'level' => 5, 'required' => true],
                ['user_id' => $this->findDirector(), 'level' => 3, 'required' => true],
            ];
        } elseif ($totalValue >= 10000000) { // 10M IDR
            // Medium value
            $approvers = [
                ['user_id' => $this->findDepartmentManager($pr->department_code), 'level' => 2, 'required' => true],
                ['user_id' => $this->findFinanceManager(), 'level' => 5, 'required' => true],
            ];
        } else {
            // Low value
            $approvers = [
                ['user_id' => $this->findSupervisor($pr->department_code), 'level' => 1, 'required' => true],
            ];
        }

        // Filter out null approvers
        $approvers = array_filter($approvers, function($approver) {
            return $approver['user_id'] !== null;
        });

        if (!empty($approvers)) {
            PrApproval::createApprovalWorkflow($pr->id, $approvers);
        }
    }

    /**
     * Helper methods to find approvers
     * In real implementation, these would query actual user/role tables
     */
    private function findSupervisor($departmentCode)
    {
        // Return first user with supervisor role for the department
        return User::where('role', 'supervisor')
                  ->where('department_code', $departmentCode)
                  ->first()?->id;
    }

    private function findDepartmentManager($departmentCode)
    {
        // Return first user with manager role for the department
        return User::where('role', 'manager')
                  ->where('department_code', $departmentCode)
                  ->first()?->id;
    }

    private function findFinanceManager()
    {
        // Return first user with finance manager role
        return User::where('role', 'finance_manager')->first()?->id;
    }

    private function findDirector()
    {
        // Return first user with director role
        return User::where('role', 'director')->first()?->id;
    }
}
