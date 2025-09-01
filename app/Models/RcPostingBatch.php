<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RcPostingBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rc_posting_batches';

    protected $fillable = [
        'batch_number',
        'start_note_number',
        'end_note_number',
        'total_records',
        'total_errors',
        'print_count',
        'status', // New, Posted, Cancelled
        'total_accrued_rc',
        'current_ic_period',
        'current_ap_period',
        'current_gl_period',
        'prepared_by',
        'prepared_date',
        'prepared_time',
        'cancelled_by',
        'cancelled_date',
        'cancelled_time',
        'posted_by',
        'posted_date',
        'posted_time',
        'notes'
    ];

    protected $casts = [
        'prepared_date' => 'date',
        'cancelled_date' => 'date',
        'posted_date' => 'date',
        'total_accrued_rc' => 'decimal:2',
    ];

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'New' => 'bg-blue-100 text-blue-800',
            'Posted' => 'bg-green-100 text-green-800',
            'Cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function preparedByUser()
    {
        return $this->belongsTo(User::class, 'prepared_by', 'user_id');
    }

    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by', 'user_id');
    }

    public function postedByUser()
    {
        return $this->belongsTo(User::class, 'posted_by', 'user_id');
    }

    public function generateBatchNumber()
    {
        $currentPeriod = now()->format('m-Y');
        $lastBatch = self::where('batch_number', 'like', "RC/{$currentPeriod}-%")
            ->orderBy('batch_number', 'desc')
            ->first();

        if ($lastBatch) {
            $lastNumber = (int) substr($lastBatch->batch_number, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return "RC/{$currentPeriod}-{$newNumber}";
    }
}
