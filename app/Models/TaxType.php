<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tax_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * The "type" of the primary key.
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
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'apply',
        'rate',
        'custom_type',
        'tax_group_code',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'decimal:2',
    ];

    /**
     * Get the tax group that owns this tax type.
     */
    public function taxGroup()
    {
        return $this->belongsTo(TaxGroup::class, 'tax_group_code', 'code');
    }

	public function invoices()
	{
		return $this->hasMany(Invoice::class, 'IV_TAX_CODE', 'code');
	}
}
