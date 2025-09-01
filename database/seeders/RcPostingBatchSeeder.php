<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RcPostingBatch;
use Carbon\Carbon;

class RcPostingBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentPeriod = Carbon::now()->format('m Y');
        
        // Create sample batches
        $batches = [
            [
                'batch_number' => "RC/{$currentPeriod}-00013",
                'start_note_number' => "08-2025-00376",
                'end_note_number' => "08-2025-00376",
                'total_records' => 1,
                'total_errors' => 0,
                'print_count' => 0,
                'status' => 'Posted',
                'total_accrued_rc' => 15000.00,
                'current_ic_period' => $currentPeriod,
                'current_ap_period' => $currentPeriod,
                'current_gl_period' => $currentPeriod,
                'prepared_by' => 'acc04',
                'prepared_date' => '2025-08-20',
                'prepared_time' => '09:00:26',
                'posted_by' => 'acc04',
                'posted_date' => '2025-08-20',
                'posted_time' => '09:00:39',
            ],
            [
                'batch_number' => "RC/{$currentPeriod}-00012",
                'start_note_number' => "08-2025-00349",
                'end_note_number' => "08-2025-00349",
                'total_records' => 58,
                'total_errors' => 0,
                'print_count' => 0,
                'status' => 'Posted',
                'total_accrued_rc' => 875000.00,
                'current_ic_period' => $currentPeriod,
                'current_ap_period' => $currentPeriod,
                'current_gl_period' => $currentPeriod,
                'prepared_by' => 'acc04',
                'prepared_date' => '2025-08-19',
                'prepared_time' => '14:30:15',
                'posted_by' => 'acc04',
                'posted_date' => '2025-08-19',
                'posted_time' => '14:35:22',
            ],
            [
                'batch_number' => "RC/{$currentPeriod}-00011",
                'start_note_number' => "08-2025-00250",
                'end_note_number' => "08-2025-00348",
                'total_records' => 99,
                'total_errors' => 0,
                'print_count' => 0,
                'status' => 'Posted',
                'total_accrued_rc' => 1250000.00,
                'current_ic_period' => $currentPeriod,
                'current_ap_period' => $currentPeriod,
                'current_gl_period' => $currentPeriod,
                'prepared_by' => 'acc04',
                'prepared_date' => '2025-08-18',
                'prepared_time' => '11:15:30',
                'posted_by' => 'acc04',
                'posted_date' => '2025-08-18',
                'posted_time' => '11:20:45',
            ],
            [
                'batch_number' => "RC/{$currentPeriod}-00010",
                'start_note_number' => "08-2025-00151",
                'end_note_number' => "08-2025-00249",
                'total_records' => 95,
                'total_errors' => 0,
                'print_count' => 0,
                'status' => 'Posted',
                'total_accrued_rc' => 980000.00,
                'current_ic_period' => $currentPeriod,
                'current_ap_period' => $currentPeriod,
                'current_gl_period' => $currentPeriod,
                'prepared_by' => 'acc04',
                'prepared_date' => '2025-08-17',
                'prepared_time' => '16:45:12',
                'posted_by' => 'acc04',
                'posted_date' => '2025-08-17',
                'posted_time' => '16:50:28',
            ],
            [
                'batch_number' => "RC/{$currentPeriod}-00005",
                'start_note_number' => "05-2025-00734",
                'end_note_number' => "05-2025-00734",
                'total_records' => 0,
                'total_errors' => 0,
                'print_count' => 0,
                'status' => 'Cancelled',
                'total_accrued_rc' => 0.00,
                'current_ic_period' => $currentPeriod,
                'current_ap_period' => $currentPeriod,
                'current_gl_period' => $currentPeriod,
                'prepared_by' => 'acc04',
                'prepared_date' => '2025-08-14',
                'prepared_time' => '16:05:06',
                'cancelled_by' => 'acc04',
                'cancelled_date' => '2025-08-15',
                'cancelled_time' => '16:03:59',
            ],
            [
                'batch_number' => "RC/{$currentPeriod}-00004",
                'start_note_number' => "08-2025-00143",
                'end_note_number' => "08-2025-00143",
                'total_records' => 1,
                'total_errors' => 0,
                'print_count' => 0,
                'status' => 'Posted',
                'total_accrued_rc' => 25000.00,
                'current_ic_period' => $currentPeriod,
                'current_ap_period' => $currentPeriod,
                'current_gl_period' => $currentPeriod,
                'prepared_by' => 'acc04',
                'prepared_date' => '2025-08-13',
                'prepared_time' => '10:30:45',
                'posted_by' => 'acc04',
                'posted_date' => '2025-08-13',
                'posted_time' => '10:35:12',
            ],
        ];

        foreach ($batches as $batchData) {
            RcPostingBatch::create($batchData);
        }
    }
}
