<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseRequisition;
use App\Models\PrItem;
use App\Models\PrApproval;
use App\Models\UserCps;
use App\Models\MmSku;
use Carbon\Carbon;

class PurchaseRequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample users (create some if they don't exist)
        $users = $this->createSampleUsers();
        $skus = $this->getSampleSkus();

        // Create sample departments
        $departments = [
            ['code' => 'IT', 'name' => 'Information Technology'],
            ['code' => 'HR', 'name' => 'Human Resources'],
            ['code' => 'FIN', 'name' => 'Finance'],
            ['code' => 'OPS', 'name' => 'Operations'],
            ['code' => 'MKT', 'name' => 'Marketing'],
        ];

        // Create sample PRs
        foreach (range(1, 15) as $index) {
            $department = $departments[array_rand($departments)];
            $requestorId = $users[array_rand($users)]; // This is now a string ID
            
            $requestDate = Carbon::now()->subDays(rand(1, 30));
            $requiredDate = $requestDate->copy()->addDays(rand(7, 21));
            
            $status = $this->getRandomStatus();
            $priority = $this->getRandomPriority();
            
            $pr = PurchaseRequisition::create([
                'pr_number' => $this->generatePRNumber($index),
                'pr_period' => $requestDate->month,
                'pr_year' => $requestDate->year,
                'department_code' => $department['code'],
                'department_name' => $department['name'],
                'requestor_id' => $requestorId,
                'requestor_name' => 'User ' . $requestorId,
                'request_date' => $requestDate,
                'required_date' => $requiredDate,
                'priority' => $priority,
                'status' => $status,
                'budget_code' => 'BGT-' . $department['code'] . '-' . date('Y'),
                'description' => $this->generateDescription($department['name']),
                'currency' => 'IDR',
                'created_by' => $requestorId,
            ]);

            // Create PR items
            $itemCount = rand(2, 6);
            $totalValue = 0;

            foreach (range(1, $itemCount) as $lineNumber) {
                $sku = $skus->random();
                $quantity = rand(1, 20);
                $estimatedPrice = $this->getRandomPrice($priority);
                $totalPrice = $quantity * $estimatedPrice;
                $totalValue += $totalPrice;

                PrItem::create([
                    'pr_id' => $pr->id,
                    'line_number' => $lineNumber,
                    'sku_code' => $sku->sku,
                    'description' => $sku->sku_name,
                    'specification' => $this->generateSpecification($sku->sku_name),
                    'quantity' => $quantity,
                    'unit' => $sku->uom ?? 'PCS',
                    'estimated_price' => $estimatedPrice,
                    'total_price' => $totalPrice,
                    'current_stock' => $sku->boh ?? 0,
                    'stock_status' => $this->getStockStatus($sku->boh ?? 0, $quantity),
                    'urgency' => $this->getItemUrgency($priority),
                    'remaining_quantity' => $quantity,
                    'converted_quantity' => 0,
                    'created_by' => $requestorId,
                ]);
            }

            // Update total estimated value
            $pr->update(['total_estimated_value' => $totalValue]);

            // Create approval workflow for non-draft PRs
            if ($status !== PurchaseRequisition::STATUS_DRAFT) {
                $this->createApprovalWorkflow($pr, $users, $status);
            }
        }
    }

    /**
     * Create sample users if they don't exist
     */
    private function createSampleUsers()
    {
        $sampleUsers = [
            ['full_name' => 'John Supervisor', 'username' => 'john.supervisor', 'official_title' => 'supervisor'],
            ['full_name' => 'Jane Manager', 'username' => 'jane.manager', 'official_title' => 'manager'],
            ['full_name' => 'Bob Director', 'username' => 'bob.director', 'official_title' => 'director'],
            ['full_name' => 'Alice Finance', 'username' => 'alice.finance', 'official_title' => 'finance_manager'],
            ['full_name' => 'Mike Employee', 'username' => 'mike.employee', 'official_title' => 'employee'],
            ['full_name' => 'Sarah Employee', 'username' => 'sarah.employee', 'official_title' => 'employee'],
        ];

        // Get existing users from USERCPS or use default IDs
        $existingUsers = UserCps::limit(5)->get();
        
        if ($existingUsers->count() > 0) {
            // Use existing USERCPS users
            return $existingUsers->pluck('ID')->toArray();
        }
        
        // Fallback: use default user IDs
        return ['SYSTEM', 'ADMIN', 'USER01', 'USER02', 'USER03'];
    }

    /**
     * Get sample SKUs
     */
    private function getSampleSkus()
    {
        $skus = MmSku::limit(20)->get();
        
        if ($skus->isEmpty()) {
            // Create some sample SKUs if none exist
            $sampleSkus = [
                ['sku' => 'OFF001', 'sku_name' => 'Office Chair', 'uom' => 'PCS', 'boh' => 15],
                ['sku' => 'OFF002', 'sku_name' => 'Office Desk', 'uom' => 'PCS', 'boh' => 8],
                ['sku' => 'STA001', 'sku_name' => 'A4 Paper', 'uom' => 'REAM', 'boh' => 50],
                ['sku' => 'STA002', 'sku_name' => 'Ballpoint Pen', 'uom' => 'PCS', 'boh' => 100],
                ['sku' => 'COM001', 'sku_name' => 'Desktop Computer', 'uom' => 'PCS', 'boh' => 5],
                ['sku' => 'COM002', 'sku_name' => 'Laptop', 'uom' => 'PCS', 'boh' => 3],
                ['sku' => 'NET001', 'sku_name' => 'Network Cable', 'uom' => 'M', 'boh' => 200],
                ['sku' => 'NET002', 'sku_name' => 'Network Switch', 'uom' => 'PCS', 'boh' => 2],
            ];

            foreach ($sampleSkus as $skuData) {
                MmSku::firstOrCreate(
                    ['sku' => $skuData['sku']],
                    [
                        'sku' => $skuData['sku'],
                        'sku_name' => $skuData['sku_name'],
                        'uom' => $skuData['uom'],
                        'boh' => $skuData['boh'],
                        'is_active' => true,
                        'category_code' => 'GEN',
                        'type' => 'S',
                    ]
                );
            }

            $skus = MmSku::limit(20)->get();
        }

        return $skus;
    }

    /**
     * Generate PR number
     */
    private function generatePRNumber($index)
    {
        $year = date('Y');
        $month = date('m');
        return sprintf('PR%s%02d%04d', $year, $month, $index);
    }

    /**
     * Get random status
     */
    private function getRandomStatus()
    {
        $statuses = [
            PurchaseRequisition::STATUS_DRAFT,
            PurchaseRequisition::STATUS_PENDING_APPROVAL,
            PurchaseRequisition::STATUS_APPROVED,
            PurchaseRequisition::STATUS_REJECTED,
        ];

        $weights = [2, 3, 4, 1]; // Draft: 20%, Pending: 30%, Approved: 40%, Rejected: 10%
        
        return $this->getWeightedRandom($statuses, $weights);
    }

    /**
     * Get random priority
     */
    private function getRandomPriority()
    {
        $priorities = [
            PurchaseRequisition::PRIORITY_LOW,
            PurchaseRequisition::PRIORITY_MEDIUM,
            PurchaseRequisition::PRIORITY_HIGH,
            PurchaseRequisition::PRIORITY_URGENT,
        ];

        $weights = [3, 5, 2, 1]; // Low: 27%, Medium: 45%, High: 18%, Urgent: 9%
        
        return $this->getWeightedRandom($priorities, $weights);
    }

    /**
     * Generate description based on department
     */
    private function generateDescription($departmentName)
    {
        $descriptions = [
            'Information Technology' => [
                'Hardware replacement for development team',
                'Network infrastructure upgrade project',
                'Software licenses for new applications',
                'Server maintenance equipment',
                'Office equipment for remote work setup',
            ],
            'Human Resources' => [
                'Office furniture for new employees',
                'Training materials and supplies',
                'Employee wellness program items',
                'Office stationery and supplies',
                'Meeting room equipment upgrade',
            ],
            'Finance' => [
                'Accounting software and licenses',
                'Office supplies for financial reporting',
                'Document storage and filing systems',
                'Calculator and financial tools',
                'Audit preparation materials',
            ],
            'Operations' => [
                'Production equipment maintenance',
                'Safety equipment and supplies',
                'Quality control instruments',
                'Facility maintenance tools',
                'Operational efficiency improvements',
            ],
            'Marketing' => [
                'Marketing campaign materials',
                'Design software and equipment',
                'Promotional items and merchandise',
                'Event planning supplies',
                'Digital marketing tools',
            ],
        ];

        $deptDescriptions = $descriptions[$departmentName] ?? $descriptions['Operations'];
        return $deptDescriptions[array_rand($deptDescriptions)];
    }

    /**
     * Generate specification
     */
    private function generateSpecification($itemName)
    {
        $specs = [
            'High quality, durable construction',
            'ISO certified, meets company standards',
            'Compatible with existing systems',
            'Energy efficient, eco-friendly',
            'Standard size, professional grade',
            'Latest model with warranty',
            'Ergonomic design, user-friendly',
            'Multi-purpose, versatile usage',
        ];

        return $specs[array_rand($specs)];
    }

    /**
     * Get random price based on priority
     */
    private function getRandomPrice($priority)
    {
        $basePrice = rand(50000, 5000000); // 50K to 5M IDR

        switch ($priority) {
            case PurchaseRequisition::PRIORITY_URGENT:
                return $basePrice * rand(2, 5); // Higher prices for urgent items
            case PurchaseRequisition::PRIORITY_HIGH:
                return $basePrice * rand(1.5, 3);
            case PurchaseRequisition::PRIORITY_MEDIUM:
                return $basePrice;
            case PurchaseRequisition::PRIORITY_LOW:
                return $basePrice * rand(0.5, 1);
            default:
                return $basePrice;
        }
    }

    /**
     * Get stock status based on current stock and required quantity
     */
    private function getStockStatus($currentStock, $requiredQuantity)
    {
        if ($currentStock <= 0) {
            return PrItem::STOCK_OUT;
        } elseif ($currentStock < $requiredQuantity) {
            return PrItem::STOCK_LOW;
        } else {
            return PrItem::STOCK_AVAILABLE;
        }
    }

    /**
     * Get item urgency based on PR priority
     */
    private function getItemUrgency($prPriority)
    {
        $mapping = [
            PurchaseRequisition::PRIORITY_LOW => PrItem::URGENCY_LOW,
            PurchaseRequisition::PRIORITY_MEDIUM => PrItem::URGENCY_MEDIUM,
            PurchaseRequisition::PRIORITY_HIGH => PrItem::URGENCY_HIGH,
            PurchaseRequisition::PRIORITY_URGENT => PrItem::URGENCY_CRITICAL,
        ];

        return $mapping[$prPriority] ?? PrItem::URGENCY_MEDIUM;
    }

    /**
     * Create approval workflow
     */
    private function createApprovalWorkflow($pr, $users, $status)
    {
        // Since $users is now array of string IDs, just use them directly
        $approvers = [];
        
        // Simple workflow based on total value
        if ($pr->total_estimated_value >= 50000000) { // 50M IDR
            $approvers = [
                ['user_id' => $users[1] ?? $users[0], 'level' => 2, 'sequence' => 1],
                ['user_id' => $users[2] ?? $users[0], 'level' => 5, 'sequence' => 2],
                ['user_id' => $users[3] ?? $users[0], 'level' => 3, 'sequence' => 3],
            ];
        } elseif ($pr->total_estimated_value >= 10000000) { // 10M IDR
            $approvers = [
                ['user_id' => $users[1] ?? $users[0], 'level' => 2, 'sequence' => 1],
                ['user_id' => $users[2] ?? $users[0], 'level' => 5, 'sequence' => 2],
            ];
        } else {
            $approvers = [
                ['user_id' => $users[0], 'level' => 1, 'sequence' => 1],
            ];
        }

        foreach ($approvers as $approverData) {
            if (!$approverData['user_id']) continue;

            $action = PrApproval::ACTION_PENDING;
            $approvedDate = null;

            // Set action based on PR status and sequence
            if ($status === PurchaseRequisition::STATUS_APPROVED) {
                $action = PrApproval::ACTION_APPROVED;
                $approvedDate = $pr->created_at->addHours(rand(1, 48));
            } elseif ($status === PurchaseRequisition::STATUS_REJECTED && $approverData['sequence'] === 1) {
                $action = PrApproval::ACTION_REJECTED;
                $approvedDate = $pr->created_at->addHours(rand(1, 24));
            } elseif ($status === PurchaseRequisition::STATUS_PENDING_APPROVAL && $approverData['sequence'] === 1) {
                $action = PrApproval::ACTION_PENDING;
            } elseif ($status === PurchaseRequisition::STATUS_APPROVED && $approverData['sequence'] > 1) {
                $action = PrApproval::ACTION_APPROVED;
                $approvedDate = $pr->created_at->addHours(rand(1, 72));
            }

            PrApproval::create([
                'pr_id' => $pr->id,
                'approver_id' => $approverData['user_id'],
                'approver_level' => $approverData['level'],
                'action' => $action,
                'comments' => $this->getApprovalComment($action),
                'approved_date' => $approvedDate,
                'approval_sequence' => $approverData['sequence'],
                'is_required' => true,
                'created_by' => $pr->created_by,
            ]);
        }
    }

    /**
     * Get approval comment
     */
    private function getApprovalComment($action)
    {
        $comments = [
            PrApproval::ACTION_APPROVED => [
                'Approved for procurement',
                'Request approved as per budget allocation',
                'Items are necessary for operations',
                'Approved with standard terms',
            ],
            PrApproval::ACTION_REJECTED => [
                'Budget not available for this period',
                'Items not prioritized for current quarter',
                'Requires more detailed justification',
                'Alternative solutions should be considered',
            ],
            PrApproval::ACTION_PENDING => [
                null, // Pending approvals don't have comments yet
            ],
        ];

        $actionComments = $comments[$action] ?? [null];
        return $actionComments[array_rand($actionComments)];
    }

    /**
     * Get weighted random value
     */
    private function getWeightedRandom($values, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($values as $index => $value) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $value;
            }
        }

        return $values[0]; // Fallback
    }
}
