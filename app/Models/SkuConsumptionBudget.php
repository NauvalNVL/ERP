<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuConsumptionBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku_id',
        'effective_month',
        'budget_quantity',
        'actual_quantity',
        'variance',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'budget_quantity' => 'decimal:2',
        'actual_quantity' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function sku()
    {
        return $this->belongsTo(MmSku::class, 'sku_id', 'sku');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'username');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'username');
    }

    // Calculate variance
    public function calculateVariance()
    {
        $this->variance = $this->budget_quantity - $this->actual_quantity;
        return $this->variance;
    }

    // Get variance percentage
    public function getVariancePercentage()
    {
        if ($this->budget_quantity == 0) {
            return 0;
        }
        return ($this->variance / $this->budget_quantity) * 100;
    }
} 