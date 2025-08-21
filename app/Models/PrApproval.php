<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pr_id',
        'approver_id',
        'approver_level',
        'action',
        'comments',
        'approved_date',
        'approval_sequence',
        'is_required',
        'delegation_from',
        'delegation_reason',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'approved_date' => 'datetime',
        'is_required' => 'boolean'
    ];

    // Constants for approval actions
    const ACTION_PENDING = 'PENDING';
    const ACTION_APPROVED = 'APPROVED';
    const ACTION_REJECTED = 'REJECTED';
    const ACTION_DELEGATED = 'DELEGATED';
    const ACTION_CANCELLED = 'CANCELLED';
    const ACTION_RETURNED = 'RETURNED';

    // Constants for approver levels
    const LEVEL_SUPERVISOR = 1;
    const LEVEL_MANAGER = 2;
    const LEVEL_DIRECTOR = 3;
    const LEVEL_CEO = 4;
    const LEVEL_FINANCE = 5;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }

    /**
     * Relationships
     */
    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function delegatedFrom()
    {
        return $this->belongsTo(User::class, 'delegation_from');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopeByPr($query, $prId)
    {
        return $query->where('pr_id', $prId);
    }

    public function scopeByApprover($query, $approverId)
    {
        return $query->where('approver_id', $approverId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('approver_level', $level);
    }

    public function scopePending($query)
    {
        return $query->where('action', self::ACTION_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('action', self::ACTION_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('action', self::ACTION_REJECTED);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeBySequence($query)
    {
        return $query->orderBy('approval_sequence');
    }

    /**
     * Accessors & Mutators
     */
    public function getActionBadgeAttribute()
    {
        $badges = [
            self::ACTION_PENDING => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
            self::ACTION_APPROVED => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
            self::ACTION_REJECTED => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            self::ACTION_DELEGATED => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Delegated'],
            self::ACTION_CANCELLED => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Cancelled'],
            self::ACTION_RETURNED => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Returned'],
        ];

        return $badges[$this->action] ?? $badges[self::ACTION_PENDING];
    }

    public function getLevelNameAttribute()
    {
        $levels = [
            self::LEVEL_SUPERVISOR => 'Supervisor',
            self::LEVEL_MANAGER => 'Manager',
            self::LEVEL_DIRECTOR => 'Director',
            self::LEVEL_CEO => 'CEO',
            self::LEVEL_FINANCE => 'Finance',
        ];

        return $levels[$this->approver_level] ?? 'Unknown';
    }

    /**
     * Business Logic Methods
     */
    public function approve($comments = null)
    {
        $this->update([
            'action' => self::ACTION_APPROVED,
            'comments' => $comments,
            'approved_date' => now(),
        ]);

        // Check if this is the final approval
        $this->checkFinalApproval();

        return $this;
    }

    public function reject($comments)
    {
        $this->update([
            'action' => self::ACTION_REJECTED,
            'comments' => $comments,
            'approved_date' => now(),
        ]);

        // Reject the entire PR
        $this->purchaseRequisition->reject($this->approver_id, $comments);

        return $this;
    }

    public function delegate($newApproverId, $reason)
    {
        // Create new approval record for delegated approver
        $newApproval = $this->replicate();
        $newApproval->approver_id = $newApproverId;
        $newApproval->delegation_from = $this->approver_id;
        $newApproval->delegation_reason = $reason;
        $newApproval->action = self::ACTION_PENDING;
        $newApproval->save();

        // Mark current approval as delegated
        $this->update([
            'action' => self::ACTION_DELEGATED,
            'comments' => $reason,
            'approved_date' => now(),
        ]);

        return $newApproval;
    }

    public function returnToPrevious($comments)
    {
        $this->update([
            'action' => self::ACTION_RETURNED,
            'comments' => $comments,
            'approved_date' => now(),
        ]);

        // Find previous approver and mark as pending
        $previousApproval = PrApproval::where('pr_id', $this->pr_id)
            ->where('approval_sequence', '<', $this->approval_sequence)
            ->orderBy('approval_sequence', 'desc')
            ->first();

        if ($previousApproval) {
            $previousApproval->update(['action' => self::ACTION_PENDING]);
        }

        return $this;
    }

    private function checkFinalApproval()
    {
        $pr = $this->purchaseRequisition;
        
        // Check if all required approvals are completed
        $pendingApprovals = PrApproval::where('pr_id', $this->pr_id)
            ->where('is_required', true)
            ->where('action', self::ACTION_PENDING)
            ->count();

        if ($pendingApprovals === 0) {
            // All required approvals are done, check if any rejections
            $rejectedApprovals = PrApproval::where('pr_id', $this->pr_id)
                ->where('is_required', true)
                ->where('action', self::ACTION_REJECTED)
                ->count();

            if ($rejectedApprovals === 0) {
                // No rejections, approve the PR
                $pr->approve($this->approver_id, 'All required approvals completed');
            }
        }
    }

    /**
     * Static methods for workflow management
     */
    public static function createApprovalWorkflow($prId, $approvers = [])
    {
        $sequence = 1;
        $approvalRecords = [];

        foreach ($approvers as $approver) {
            $approval = self::create([
                'pr_id' => $prId,
                'approver_id' => $approver['user_id'],
                'approver_level' => $approver['level'],
                'approval_sequence' => $sequence,
                'is_required' => $approver['required'] ?? true,
                'action' => $sequence === 1 ? self::ACTION_PENDING : self::ACTION_PENDING,
            ]);

            $approvalRecords[] = $approval;
            $sequence++;
        }

        return $approvalRecords;
    }

    public static function getApprovalWorkflow($prId)
    {
        return self::where('pr_id', $prId)
            ->with(['approver', 'delegatedFrom'])
            ->orderBy('approval_sequence')
            ->get();
    }

    public static function getCurrentApprover($prId)
    {
        return self::where('pr_id', $prId)
            ->where('action', self::ACTION_PENDING)
            ->orderBy('approval_sequence')
            ->first();
    }

    public static function getApprovalHistory($prId)
    {
        return self::where('pr_id', $prId)
            ->whereIn('action', [self::ACTION_APPROVED, self::ACTION_REJECTED, self::ACTION_DELEGATED])
            ->with(['approver', 'delegatedFrom'])
            ->orderBy('approved_date')
            ->get();
    }

    /**
     * Get workflow progress percentage
     */
    public static function getWorkflowProgress($prId)
    {
        $totalApprovals = self::where('pr_id', $prId)->where('is_required', true)->count();
        $completedApprovals = self::where('pr_id', $prId)
            ->where('is_required', true)
            ->whereIn('action', [self::ACTION_APPROVED, self::ACTION_REJECTED])
            ->count();

        if ($totalApprovals === 0) {
            return 0;
        }

        return round(($completedApprovals / $totalApprovals) * 100, 2);
    }
}
