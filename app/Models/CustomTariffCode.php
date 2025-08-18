<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTariffCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'duty_rate',
        'tax_rate',
        'category',
        'country_origin',
        'is_active',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'duty_rate' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'username');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'username');
    }

    // Calculate total rate (duty + tax)
    public function getTotalRate()
    {
        return $this->duty_rate + $this->tax_rate;
    }

    // Get formatted code with dashes (e.g., 1234.56.78)
    public function getFormattedCode()
    {
        $code = $this->code;
        if (strlen($code) >= 8) {
            return substr($code, 0, 4) . '.' . substr($code, 4, 2) . '.' . substr($code, 6, 2);
        }
        return $code;
    }

    // Scope for active codes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for searching by code or name
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Get duty amount for a given value
    public function calculateDuty($value)
    {
        return ($value * $this->duty_rate) / 100;
    }

    // Get tax amount for a given value
    public function calculateTax($value)
    {
        return ($value * $this->tax_rate) / 100;
    }

    // Get total customs amount for a given value
    public function calculateTotalCustoms($value)
    {
        return $this->calculateDuty($value) + $this->calculateTax($value);
    }
} 