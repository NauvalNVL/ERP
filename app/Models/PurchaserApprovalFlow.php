<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaserApprovalFlow extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchaser_approval_flows';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchaser_id',
        'approver_id',
        'approver_name',
        'level_1',
        'level_2',
        'level_3',
        'email_notification'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'level_1' => 'boolean',
        'level_2' => 'boolean',
        'level_3' => 'boolean',
        'email_notification' => 'boolean',
    ];

    /**
     * Get the purchaser that owns the approval flow.
     */
    public function purchaser(): BelongsTo
    {
        return $this->belongsTo(Purchaser::class, 'purchaser_id', 'purchaser_id');
    }

    /**
     * Get the approval levels as an array.
     */
    public function getApprovalLevelsAttribute(): array
    {
        $levels = [];
        if ($this->level_1) $levels[] = 1;
        if ($this->level_2) $levels[] = 2;
        if ($this->level_3) $levels[] = 3;
        return $levels;
    }

    /**
     * Check if approver has specific level.
     */
    public function hasLevel(int $level): bool
    {
        return match($level) {
            1 => $this->level_1,
            2 => $this->level_2,
            3 => $this->level_3,
            default => false
        };
    }
} 