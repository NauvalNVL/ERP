<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserCps;

class ObsolateReactiveMC extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'obsolete_reactive_mcs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mc_seq',
        'mc_model',
        'customer_id',
        'customer_name',
        'description',
        'status',
        'obsolate_date',
        'obsolate_by',
        'obsolate_reason',
        'reactive_date',
        'reactive_by',
        'reactive_reason',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'obsolate_date' => 'datetime',
        'reactive_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the master card.
     */
    public function customer()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_id');
    }

    /**
     * Get the user who obsolated the master card.
     */
    public function obsolateUser()
    {
        return $this->belongsTo(UserCps::class, 'obsolate_by');
    }

    /**
     * Get the user who reactivated the master card.
     */
    public function reactiveUser()
    {
        return $this->belongsTo(UserCps::class, 'reactive_by');
    }

    /**
     * Get the user who created the master card.
     */
    public function createdByUser()
    {
        return $this->belongsTo(UserCps::class, 'created_by');
    }

    /**
     * Get the user who updated the master card.
     */
    public function updatedByUser()
    {
        return $this->belongsTo(UserCps::class, 'updated_by');
    }
}
