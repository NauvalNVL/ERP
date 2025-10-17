<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoNote extends Model
{
    protected $table = 'DO';
    public $timestamps = false;

    protected $primaryKey = 'DO_Num';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];
}


