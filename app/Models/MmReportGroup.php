<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmReportGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the validation rules for the model.
     *
     * @return array
     */
    public static function rules($id = null)
    {
        return [
            'code' => 'required|string|max:10|unique:mm_report_groups,code,' . $id,
            'name' => 'required|string|max:100',
            'is_active' => 'boolean',
        ];
    }
} 