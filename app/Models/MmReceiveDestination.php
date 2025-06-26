<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmReceiveDestination extends Model
{
    use HasFactory;

    protected $table = 'mm_receive_destinations';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'code',
        'name',
        'address',
        'contact',
        'tel',
        'fax',
    ];
} 