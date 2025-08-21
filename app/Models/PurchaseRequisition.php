<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pr_number',
        'pr_period',
        'pr_year',
        'department_code',
        'department_name',
        'requestor_id',
        'requestor_name',
        'request_date',
        'required_date',
        'priority',
        'status',
        'budget_code',
        'description',
        'total_estimated_value',
        'currency',
        'approved_by',
        'approved_date',
        'rejected_by',
        'rejected_date',
        'rejection_reason',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'request_date' => 'date',
        'required_date' => 'date',
        'approved_date' => 'datetime',
        'rejected_date' => 'datetime',
        'total_estimated_value' => 'decimal:2'
    ];

    // Constants for status
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_PENDING_APPROVAL = 'PENDING_APPROVAL';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_PARTIALLY_CONVERTED = 'PARTIALLY_CONVERTED';
    const STATUS_FULLY_CONVERTED = 'FULLY_CONVERTED';

    // Constants for priority
    const PRIORITY_LOW = 'LOW';
    const PRIORITY_MEDIUM = 'MEDIUM';
    const PRIORITY_HIGH = 'HIGH';
    const PRIORITY_URGENT = 'URGENT';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->pr_number)) {
                $model->pr_number = self::generatePRNumber();
            }
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }

    /**
     * Generate PR number
     */
    public static function generatePRNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Get the last PR number for current year/month
        $lastPr = self::where('pr_year', $year)
                     ->where('pr_period', $month)
                     ->orderBy('pr_number', 'desc')
                     ->first();

        if ($lastPr) {
            // Extract sequence number from last PR
            $lastNumber = intval(substr($lastPr->pr_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('PR%s%02d%04d', $year, $month, $newNumber);
    }

    /**
     * Relationships
     */
    public function items()
    {
        return $this->hasMany(PrItem::class, 'pr_id');
    }

    public function approvals()
    {
        return $this->hasMany(PrApproval::class, 'pr_id');
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
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
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByDepartment($query, $departmentCode)
    {
        return $query->where('department_code', $departmentCode);
    }

    public function scopeByRequestor($query, $requestorId)
    {
        return $query->where('requestor_id', $requestorId);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING_APPROVAL);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Accessors & Mutators
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_DRAFT => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Draft'],
            self::STATUS_PENDING_APPROVAL => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending Approval'],
            self::STATUS_APPROVED => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
            self::STATUS_REJECTED => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
            self::STATUS_CANCELLED => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Cancelled'],
            self::STATUS_PARTIALLY_CONVERTED => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Partially Converted'],
            self::STATUS_FULLY_CONVERTED => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Fully Converted'],
        ];

        return $badges[$this->status] ?? $badges[self::STATUS_DRAFT];
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            self::PRIORITY_LOW => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Low'],
            self::PRIORITY_MEDIUM => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Medium'],
            self::PRIORITY_HIGH => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'High'],
            self::PRIORITY_URGENT => ['class' => 'bg-red-100 text-red-800', 'text' => 'Urgent'],
        ];

        return $badges[$this->priority] ?? $badges[self::PRIORITY_MEDIUM];
    }

    /**
     * Business Logic Methods
     */
    public function canBeEdited()
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
    }

    public function canBeApproved()
    {
        return $this->status === self::STATUS_PENDING_APPROVAL;
    }

    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_DRAFT, 
            self::STATUS_PENDING_APPROVAL, 
            self::STATUS_APPROVED
        ]);
    }

    public function approve($approverId, $comments = null)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $approverId,
            'approved_date' => now(),
        ]);

        // Create approval record
        $this->approvals()->create([
            'approver_id' => $approverId,
            'action' => 'APPROVED',
            'comments' => $comments,
            'approved_date' => now(),
        ]);

        return $this;
    }

    public function reject($rejectorId, $reason)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_by' => $rejectorId,
            'rejected_date' => now(),
            'rejection_reason' => $reason,
        ]);

        // Create approval record
        $this->approvals()->create([
            'approver_id' => $rejectorId,
            'action' => 'REJECTED',
            'comments' => $reason,
            'approved_date' => now(),
        ]);

        return $this;
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'rejection_reason' => $reason,
        ]);

        return $this;
    }

    public function calculateTotalValue()
    {
        $total = $this->items->sum(function ($item) {
            return $item->quantity * $item->estimated_price;
        });

        $this->update(['total_estimated_value' => $total]);

        return $total;
    }

    /**
     * Search scope
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('pr_number', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('department_name', 'like', "%{$search}%")
              ->orWhere('requestor_name', 'like', "%{$search}%");
        });
    }
}
