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
}
