<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McUpdateLog extends Model
{
    use HasFactory;

    protected $table = 'MC_UPDATE_LOG';

    protected $fillable = [
        'MCS_Num',
        'status',
        'user_id',
        'reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the master card associated with this log
     */
    public function masterCard()
    {
        return $this->belongsTo(Mc::class, 'MCS_Num', 'MCS_Num');
    }
}
