<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SkuItemNoteAnalysisGroup;

class SkuItemNoteAnalysisGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $analysisGroups = [
            // Quality Control Groups
            [
                'group_code' => 'QC-001',
                'group_name' => 'Quality Issue Notes',
                'category' => 'Quality Control',
                'description' => 'Notes related to quality issues, defects, and quality control problems',
                'is_active' => true
            ],
            [
                'group_code' => 'QC-002',
                'group_name' => 'Quality Inspection Results',
                'category' => 'Quality Control',
                'description' => 'Results from quality inspections and testing procedures',
                'is_active' => true
            ],
            [
                'group_code' => 'QC-003',
                'group_name' => 'Quality Improvement Notes',
                'category' => 'Quality Control',
                'description' => 'Notes on quality improvement initiatives and recommendations',
                'is_active' => true
            ],

            // Supplier Related Groups
            [
                'group_code' => 'SUP-001',
                'group_name' => 'Supplier Feedback Notes',
                'category' => 'Supplier Management',
                'description' => 'Feedback and communication notes from suppliers',
                'is_active' => true
            ],
            [
                'group_code' => 'SUP-002',
                'group_name' => 'Supplier Performance Notes',
                'category' => 'Supplier Management',
                'description' => 'Notes on supplier performance evaluation and assessment',
                'is_active' => true
            ],
            [
                'group_code' => 'SUP-003',
                'group_name' => 'Supplier Issue Resolution',
                'category' => 'Supplier Management',
                'description' => 'Notes on resolving issues with suppliers',
                'is_active' => true
            ],

            // Storage and Warehouse Groups
            [
                'group_code' => 'STG-001',
                'group_name' => 'Storage Condition Notes',
                'category' => 'Storage Management',
                'description' => 'Notes on storage conditions, temperature, humidity, etc.',
                'is_active' => true
            ],
            [
                'group_code' => 'STG-002',
                'group_name' => 'Warehouse Location Notes',
                'category' => 'Storage Management',
                'description' => 'Notes on warehouse locations, movements, and arrangements',
                'is_active' => true
            ],
            [
                'group_code' => 'STG-003',
                'group_name' => 'Inventory Adjustment Notes',
                'category' => 'Storage Management',
                'description' => 'Notes on inventory adjustments and discrepancies',
                'is_active' => true
            ],

            // Production Related Groups
            [
                'group_code' => 'PRD-001',
                'group_name' => 'Production Usage Notes',
                'category' => 'Production',
                'description' => 'Notes on how SKU items are used in production processes',
                'is_active' => true
            ],
            [
                'group_code' => 'PRD-002',
                'group_name' => 'Production Issue Notes',
                'category' => 'Production',
                'description' => 'Notes on production issues related to specific SKU items',
                'is_active' => true
            ],
            [
                'group_code' => 'PRD-003',
                'group_name' => 'Production Efficiency Notes',
                'category' => 'Production',
                'description' => 'Notes on production efficiency and optimization',
                'is_active' => true
            ],

            // Maintenance Groups
            [
                'group_code' => 'MNT-001',
                'group_name' => 'Maintenance Schedule Notes',
                'category' => 'Maintenance',
                'description' => 'Notes on maintenance schedules for equipment and tools',
                'is_active' => true
            ],
            [
                'group_code' => 'MNT-002',
                'group_name' => 'Maintenance Issue Notes',
                'category' => 'Maintenance',
                'description' => 'Notes on maintenance issues and repairs',
                'is_active' => true
            ],

            // Cost Analysis Groups
            [
                'group_code' => 'CST-001',
                'group_name' => 'Cost Analysis Notes',
                'category' => 'Cost Management',
                'description' => 'Notes on cost analysis and cost-related observations',
                'is_active' => true
            ],
            [
                'group_code' => 'CST-002',
                'group_name' => 'Price Variance Notes',
                'category' => 'Cost Management',
                'description' => 'Notes on price variances and cost fluctuations',
                'is_active' => true
            ],

            // Safety and Compliance Groups
            [
                'group_code' => 'SFT-001',
                'group_name' => 'Safety Compliance Notes',
                'category' => 'Safety & Compliance',
                'description' => 'Notes on safety compliance and regulatory requirements',
                'is_active' => true
            ],
            [
                'group_code' => 'SFT-002',
                'group_name' => 'Environmental Notes',
                'category' => 'Safety & Compliance',
                'description' => 'Notes on environmental impact and sustainability',
                'is_active' => true
            ],

            // General Groups
            [
                'group_code' => 'GEN-001',
                'group_name' => 'General Observations',
                'category' => 'General',
                'description' => 'General observations and miscellaneous notes',
                'is_active' => true
            ],
            [
                'group_code' => 'GEN-002',
                'group_name' => 'Special Instructions',
                'category' => 'General',
                'description' => 'Special handling instructions and important notes',
                'is_active' => true
            ]
        ];

        foreach ($analysisGroups as $group) {
            SkuItemNoteAnalysisGroup::create($group);
        }
    }
} 