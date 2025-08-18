<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuCustomTariffCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku_id',
        'custom_tariff_code_id',
        'tariff_description',
        'duty_rate',
        'vat_rate',
        'pph_import_rate',
        'country_origin',
        'is_active',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'duty_rate' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'pph_import_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function sku()
    {
        return $this->belongsTo(MmSku::class, 'sku_id', 'sku');
    }

    public function customTariffCode()
    {
        return $this->belongsTo(CustomTariffCode::class, 'custom_tariff_code_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'username');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'username');
    }

    // Calculate total rate (duty + vat + pph)
    public function getTotalRate()
    {
        return $this->duty_rate + $this->vat_rate + $this->pph_import_rate;
    }

    // Get duty amount for a given value
    public function calculateDuty($value)
    {
        return ($value * $this->duty_rate) / 100;
    }

    // Get VAT amount for a given value
    public function calculateVat($value)
    {
        return ($value * $this->vat_rate) / 100;
    }

    // Get PPh import amount for a given value
    public function calculatePphImport($value)
    {
        return ($value * $this->pph_import_rate) / 100;
    }

    // Get total customs amount for a given value
    public function calculateTotalCustoms($value)
    {
        return $this->calculateDuty($value) + $this->calculateVat($value) + $this->calculatePphImport($value);
    }

    // Scope for active records
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for searching by SKU or tariff code
    public function scopeSearch($query, $search)
    {
        return $query->whereHas('sku', function($q) use ($search) {
            $q->where('sku', 'like', "%{$search}%")
              ->orWhere('sku_name', 'like', "%{$search}%");
        })->orWhereHas('customTariffCode', function($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%");
        });
    }

    // Get formatted tariff code with description
    public function getFormattedTariffCode()
    {
        if ($this->customTariffCode) {
            return $this->customTariffCode->code . ' - ' . $this->customTariffCode->name;
        }
        return 'Not assigned';
    }

    // Get tariff description (use custom or fallback to tariff code description)
    public function getTariffDescription()
    {
        return $this->tariff_description ?? $this->customTariffCode?->name ?? 'No description';
    }
} 