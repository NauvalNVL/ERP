<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McLog extends Model
{
    use HasFactory;

    protected $table = 'MC_LOG';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'NO';

    protected $keyType = 'string';

    protected $fillable = [
        'NO',
        'DATE',
        'TIME',
        'ACTION',
        'UID',
        'UID2',
        'AC_NUM',
        'AC_NAME',
        'MCS_NUM',
        'MODEL',
        'DateSK',
    ];

    protected $casts = [
        'DateSK' => 'integer',
    ];
}
