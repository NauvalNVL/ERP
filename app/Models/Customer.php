<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'CUSTOMER';
    protected $primaryKey = 'CODE';
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = [
        'CODE',
        'AC_STS',
        'NAME',
        'ADDRESS1',
        'ADDRESS2',
        'ADDRESS3',
        'PERSON_CONTACT',
        'TEL_NO',
        'FAX_NO',
        'EMAIL',
        'CREDIT_LIMIT',
        'TERM',
        'TYPE',
        'CURRENCY',
        'SLM',
        'AREA',
        'IND',
        'GROUP_',
        'NPWP',
        'CUST_TYPE'
    ];

    protected $casts = [
        'CREDIT_LIMIT' => 'decimal:2',
        'TERM' => 'decimal:2'
    ];

    // Disable timestamps for this model
    public $timestamps = false;

    // Relationship with MasterCard
    public function masterCards()
    {
        return $this->hasMany(MasterCard::class, 'customer_code', 'CODE');
    }

    public function salesTaxIndices()
    {
        return $this->hasMany(CustomerSalesTaxIndex::class, 'customer_code', 'CODE');
    }

    public function taxProductTieups()
    {
        return $this->hasMany(CustomerTaxProductTieup::class, 'customer_code', 'CODE');
    }

    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class, 'SLM', 'Code');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'IND', 'code');
    }

    public function geo()
    {
        return $this->belongsTo(Geo::class, 'AREA', 'CODE');
    }

    public function custGroup()
    {
        return $this->belongsTo(CustGroup::class, 'GROUP_', 'Group_ID');
    }

    public function mcs()
    {
        return $this->hasMany(Mc::class, 'AC_NUM', 'CODE');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_code', 'CODE');
    }

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class, 'AC_Num', 'CODE');
    }

	public function invoices()
	{
		return $this->hasMany(Invoice::class, 'AC_NUM', 'CODE');
	}
}
