<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAlternateAddress extends Model
{
    use HasFactory;

    protected $table = 'ALT_ADDRESS';

    // Define fillable fields, table name, etc. as needed
    // protected $fillable = ['field1', 'field2'];
}
