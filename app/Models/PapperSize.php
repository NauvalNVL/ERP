<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PapperSize extends Model
{
    use HasFactory;

    protected $table = 'paper_sizes';
    
    protected $fillable = [
        'size',
        'inches',
        'unit',
        'is_active',
        'created_by',
        'updated_by'
    ];
    
    protected $casts = [
        'size' => 'decimal:2',
        'inches' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Konversi millimeter ke inches - Metode statis yang dipanggil dari Controller
     *
     * @param float|string $millimeters
     * @return float
     */
    public static function convertToInches($millimeters)
    {
        // Pastikan nilai valid (positif dan bukan null)
        if (!is_numeric($millimeters) || $millimeters <= 0) {
            return 0;
        }
        
        // Konversi mm ke inches (1 inch = 25.4 mm)
        return round((float)$millimeters / 25.4, 2);
    }

    /**
     * Konversi inches ke millimeter - Metode statis yang dipanggil dari Controller
     *
     * @param float|string $inches
     * @return float
     */
    public static function convertToMillimeters($inches)
    {
        // Pastikan nilai valid (positif dan bukan null)
        if (!is_numeric($inches) || $inches <= 0) {
            return 0;
        }
        
        // Konversi inches ke mm (1 inch = 25.4 mm)
        return round((float)$inches * 25.4, 2);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            self::handleSizeConversion($model);
        });
        
        static::updating(function ($model) {
            self::handleSizeConversion($model);
        });
    }
    
    /**
     * Handle size conversion between millimeters and inches
     */
    private static function handleSizeConversion($model)
    {
        try {
            // Konversi mm ke inches
            if (isset($model->size) && is_numeric($model->size) && $model->size > 0) {
                $model->inches = self::convertToInches($model->size);
            }
            // Konversi inches ke mm
            elseif (isset($model->inches) && is_numeric($model->inches) && $model->inches > 0) {
                $model->size = self::convertToMillimeters($model->inches);
            }
        } catch (\Exception $e) {
            // Set nilai default jika terjadi error
            if (!isset($model->size) || !is_numeric($model->size) || $model->size <= 0) {
                $model->size = 0;
            }
            if (!isset($model->inches) || !is_numeric($model->inches) || $model->inches <= 0) {
                $model->inches = 0;
            }
        }
    }

    /**
     * Mendapatkan nilai dalam inches dari millimeter
     */
    public function getInchesValue($millimeters = null)
    {
        $mm = $millimeters ?? $this->size;
        return self::convertToInches($mm);
    }

    /**
     * Mendapatkan nilai dalam millimeter dari inches
     */
    public function getMillimetersValue($inches = null)
    {
        $inch = $inches ?? $this->inches;
        return self::convertToMillimeters($inch);
    }
    
    /**
     * Scope untuk data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
