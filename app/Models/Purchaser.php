<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchaser extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'purchaser_id';

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
        'purchaser_id',
        'purchaser_name',
        'type',
        'email',
        'password',
        'cc_to_self',
        'is_active',
        'department',
        'position',
        'employee_id',
        'phone',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cc_to_self' => 'boolean',
        'is_active' => 'boolean',
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
     * Get the approval flows for the purchaser.
     */
    public function approvalFlows(): HasMany
    {
        return $this->hasMany(PurchaserApprovalFlow::class, 'purchaser_id', 'purchaser_id');
    }

    /**
     * Check if the purchaser is a purchaser type.
     */
    public function isPurchaser(): bool
    {
        return $this->type === 'PU';
    }

    /**
     * Check if the purchaser is a requestor type.
     */
    public function isRequestor(): bool
    {
        return $this->type === 'RQ';
    }

    /**
     * Get active purchasers only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get purchasers by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the display type text.
     */
    public function getTypeTextAttribute(): string
    {
        return $this->type === 'PU' ? 'Purchaser' : 'Requestor';
    }
} 