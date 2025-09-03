<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCard extends Model
{
    use HasFactory;

    protected $table = 'master_cards';

    protected $primaryKey = 'mc_seq';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'mc_seq',
        'mc_model',
        'mc_short_model',
        'part_no',
        'comp_no',
        'p_design',
        'status',
        'customer_code',
        'ext_dim_1',
        'ext_dim_2',
        'ext_dim_3',
        'int_dim_1',
        'int_dim_2',
        'int_dim_3',
        'customer_code',
    ];

    // Accessor untuk mendapatkan seq dari mc_seq
    public function getSeqAttribute()
    {
        return $this->mc_seq;
    }

    // Accessor untuk mendapatkan model dari mc_model
    public function getModelAttribute()
    {
        return $this->mc_model;
    }

    // Accessor untuk mendapatkan part dari part_no
    public function getPartAttribute()
    {
        return $this->part_no;
    }

    // Accessor untuk mendapatkan comp dari comp_no
    public function getCompAttribute()
    {
        return $this->comp_no;
    }

    // Relationship with UpdateCustomerAccount
    public function customerAccount()
    {
        return $this->belongsTo(UpdateCustomerAccount::class, 'customer_code', 'customer_code');
    }
}
