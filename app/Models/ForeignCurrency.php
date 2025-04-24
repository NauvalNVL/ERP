<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForeignCurrency extends Model
{
    use HasFactory;

    protected $table = 'foreign_currencies';

    protected $fillable = [
        'currency_code',
        'country',
        'currency_name',
        'exchange_rate',
        'exchange_method',
        'variance_control',
        'max_tax_adj',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'variance_control' => 'decimal:2',
        'max_tax_adj' => 'decimal:2',
    ];
}
