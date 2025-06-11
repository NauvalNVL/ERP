<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringFormula extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_design_id',
        'paper_flute_id',
        'scoring_length_formula',
        'scoring_width_formula',
        'length_conversion',
        'width_conversion',
        'height_conversion',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the product design associated with this scoring formula.
     */
    public function productDesign()
    {
        return $this->belongsTo(ProductDesign::class);
    }

    /**
     * Get the paper flute associated with this scoring formula.
     */
    public function paperFlute()
    {
        return $this->belongsTo(PaperFlute::class);
    }

    /**
     * Scope a query to only include active formulas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted scoring length formula.
     */
    public function getScoringLengthFormulaAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Set scoring length formula.
     */
    public function setScoringLengthFormulaAttribute($value)
    {
        $this->attributes['scoring_length_formula'] = json_encode($value);
    }

    /**
     * Get formatted scoring width formula.
     */
    public function getScoringWidthFormulaAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Set scoring width formula.
     */
    public function setScoringWidthFormulaAttribute($value)
    {
        $this->attributes['scoring_width_formula'] = json_encode($value);
    }
} 