<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;

    // Use existing CPS table
    protected $table = 'CUST_GROUP';
    protected $primaryKey = 'Group_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // CPS tables don't have timestamps

    protected $fillable = [
        'No',
        'Group_ID',
        'Group_Name',
        'Currency',
        'AC',
        'Name',
        'status'
    ];

    // Add accessors to $appends for JSON serialization
    // Note: 'status' is NOT in appends because it's a real database column
    protected $appends = ['group_code', 'description'];

    // Map to CPS fields for backward compatibility
    public function getGroupCodeAttribute()
    {
        return $this->Group_ID;
    }

    public function setGroupCodeAttribute($value)
    {
        $this->attributes['Group_ID'] = $value;
    }

    public function getDescriptionAttribute()
    {
        return $this->Group_Name;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['Group_Name'] = $value;
    }

    public function getStatusAttribute($value)
    {
        $trim = trim((string) ($value ?? ''));
        return $trim === '' ? 'Act' : $trim;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
    }
}
