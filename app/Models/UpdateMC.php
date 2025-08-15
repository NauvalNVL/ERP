<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateMC extends Model
{
    use HasFactory;

    protected $table = 'master_cards'; // Specify the table name

    protected $fillable = [
        'seq',
        'model',
        'status',
    ];
}
