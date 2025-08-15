<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class DrCrNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'note_number',
        'note_type',
        'reference_document',
        'reference_type',
        'customer_code',
        'vendor_code',
        'customer_name',
        'vendor_name',
        'amount',
        'description',
        'reason',
        'status',
        'note_date',
        'due_date',
        'currency',
        'exchange_rate',
        'created_by',
        'approved_by',
        'approved_at',
        'approval_notes',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'note_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'username');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'username');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('note_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('note_date', [$startDate, $endDate]);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('note_number', 'like', "%{$search}%")
              ->orWhere('reference_document', 'like', "%{$search}%")
              ->orWhere('customer_name', 'like', "%{$search}%")
              ->orWhere('vendor_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', '.');
    }

    public function getFormattedDateAttribute()
    {
        return $this->note_date->format('d/m/Y');
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'Draft' => 'bg-gray-100 text-gray-800',
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'Approved' => 'bg-green-100 text-green-800',
            'Rejected' => 'bg-red-100 text-red-800',
            'Posted' => 'bg-blue-100 text-blue-800'
        ];
        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getTypeBadgeClassAttribute()
    {
        return $this->note_type === 'DR' 
            ? 'bg-red-100 text-red-800' 
            : 'bg-green-100 text-green-800';
    }

    public function getCounterpartyNameAttribute()
    {
        return $this->customer_name ?? $this->vendor_name ?? 'N/A';
    }

    // Methods
    public function canEdit()
    {
        return in_array($this->status, ['Draft', 'Rejected']);
    }

    public function canDelete()
    {
        return $this->status === 'Draft';
    }

    public function canApprove()
    {
        return $this->status === 'Pending';
    }

    public function canPost()
    {
        return $this->status === 'Approved';
    }

    public function approve($approvedBy, $notes = null)
    {
        $this->update([
            'status' => 'Approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'approval_notes' => $notes
        ]);
    }

    public function reject($rejectedBy, $notes = null)
    {
        $this->update([
            'status' => 'Rejected',
            'approved_by' => $rejectedBy,
            'approved_at' => now(),
            'approval_notes' => $notes
        ]);
    }

    public function post($postedBy)
    {
        $this->update([
            'status' => 'Posted',
            'approved_by' => $postedBy,
            'approved_at' => now()
        ]);
    }

    // Static methods
    public static function generateNoteNumber($type)
    {
        $prefix = $type === 'DR' ? 'DR' : 'CR';
        $year = date('Y');
        $month = date('m');
        
        $lastNote = self::where('note_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('note_number', 'desc')
            ->first();
        
        if ($lastNote) {
            $lastNumber = (int) substr($lastNote->note_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf('%s%s%s%04d', $prefix, $year, $month, $newNumber);
    }
} 