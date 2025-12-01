<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustGroup extends Model
{
    use HasFactory;

    protected $table = 'CUST_GROUP';
    protected $primaryKey = 'Group_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'No',
        'Group_ID',
        'Group_Name',
        'Currency',
        'AC',
        'Name',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'GROUP_', 'Group_ID');
    }
}
