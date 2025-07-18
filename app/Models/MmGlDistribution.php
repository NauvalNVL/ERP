<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmGlDistribution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gl_dist_code',
        'gl_dist_name',
        'gl_account',
        'gl_account_segment1',
        'gl_account_segment2',
        'gl_account_segment3',
        'gl_account_name',
        'is_linked',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_linked' => 'boolean',
    ];

    /**
     * Get the validation rules for the model.
     *
     * @return array
     */
    public static function rules($id = null)
    {
        return [
            'gl_dist_code' => 'required|string|max:10|unique:mm_gl_distributions,gl_dist_code,' . $id,
            'gl_dist_name' => 'required|string|max:100',
            'gl_account' => 'required|string|max:20',
            'gl_account_segment1' => 'required|string|max:6',
            'gl_account_segment2' => 'required|string|max:2',
            'gl_account_segment3' => 'required|string|max:2',
            'gl_account_name' => 'nullable|string|max:100',
        ];
    }
    
    /**
     * Get the formatted GL account number
     * 
     * @return string
     */
    public function getFormattedGlAccountAttribute()
    {
        return $this->gl_account_segment1 . '-' . $this->gl_account_segment2 . '-' . $this->gl_account_segment3;
    }
    
    /**
     * Set the GL account segments when the full account is set
     *
     * @param string $value
     * @return void
     */
    public function setGlAccountAttribute($value)
    {
        $this->attributes['gl_account'] = $value;
        
        // Parse the account segments if the format is like "114000-04-00"
        $segments = explode('-', $value);
        if (count($segments) === 3) {
            $this->attributes['gl_account_segment1'] = $segments[0];
            $this->attributes['gl_account_segment2'] = $segments[1];
            $this->attributes['gl_account_segment3'] = $segments[2];
        }
    }
} 