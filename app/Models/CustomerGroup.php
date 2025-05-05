<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;

    protected $table = 'customer_groups';
    protected $primaryKey = 'group_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'group_code',
        'description',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with User for created_by
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Relationship with User for updated_by
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
