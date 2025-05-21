<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessForm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bf_code',
        'bf_name',
        'bf_group',
        'bf_iso',
        'check_by_name',
        'check_by_title',
        'approve_by_name',
        'approve_by_title',
    ];
}
