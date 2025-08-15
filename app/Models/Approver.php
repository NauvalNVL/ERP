<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Approver extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'approver_id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'approver_id',
        'approver_name',
        'user_id',
        'email',
        'password',
        'olda_enabled',
        'pr_authorized',
        'pr_level',
        'pr_zero_price_allowed',
        'pr_approval_style',
        'pr_price_history',
        'po_authorized',
        'po_level',
        'po_approval_style',
        'po_min_limit',
        'po_max_limit',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'olda_enabled' => 'boolean',
        'pr_authorized' => 'boolean',
        'pr_zero_price_allowed' => 'boolean',
        'pr_price_history' => 'boolean',
        'po_authorized' => 'boolean',
        'is_active' => 'boolean',
        'po_min_limit' => 'decimal:2',
        'po_max_limit' => 'decimal:2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the user associated with this approver.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Check if the approver is authorized for PR.
     */
    public function isPrAuthorized(): bool
    {
        return $this->pr_authorized && $this->is_active;
    }

    /**
     * Check if the approver is authorized for PO.
     */
    public function isPoAuthorized(): bool
    {
        return $this->po_authorized && $this->is_active;
    }

    /**
     * Get the PR approval style text.
     */
    public function getPrApprovalStyleTextAttribute(): string
    {
        return $this->pr_approval_style === 'tick_1_pr' ? 'Tick 1 PR' : 'Tick All PR';
    }

    /**
     * Get the PO approval style text.
     */
    public function getPoApprovalStyleTextAttribute(): string
    {
        return $this->po_approval_style === 'tick_1_po' ? 'Tick 1 PO' : 'Tick All PO';
    }

    /**
     * Get the OLDA status text.
     */
    public function getOldaStatusTextAttribute(): string
    {
        return $this->olda_enabled ? 'Yes' : 'No';
    }

    /**
     * Get active approvers only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get PR authorized approvers.
     */
    public function scopePrAuthorized($query)
    {
        return $query->where('pr_authorized', true)->where('is_active', true);
    }

    /**
     * Get PO authorized approvers.
     */
    public function scopePoAuthorized($query)
    {
        return $query->where('po_authorized', true)->where('is_active', true);
    }

    /**
     * Get approvers by PR level.
     */
    public function scopeByPrLevel($query, $level)
    {
        return $query->where('pr_level', $level);
    }

    /**
     * Get approvers by PO level.
     */
    public function scopeByPoLevel($query, $level)
    {
        return $query->where('po_level', $level);
    }

    /**
     * Set password with hashing.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
} 