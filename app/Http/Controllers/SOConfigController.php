<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\SOConfig;

class SOConfigController extends Controller
{
    public function index()
    {
        $configuration = $this->getConfiguration();

        return Inertia::render('sales-management/sales-order/setup/define-so-config', [
            'configuration' => $configuration
        ]);
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'activateOrderType' => 'required|array',
            'activateOrderType.S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV' => 'boolean',
            'activateOrderType.S2-Sales from SO >> DO >> IV (Kanban/JIT)' => 'boolean',
            'activateOrderType.S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV' => 'boolean',
            'activateOrderType.N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO' => 'boolean',
            'activateOrderType.N2-Non-Sales from SO >> DO (IFDC)' => 'boolean',
            'activateOrderType.N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)' => 'boolean',
            'activateOrderType.N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)' => 'boolean',
            'checkRollBalance' => 'required|string',
            'rollStockBalanceCheck' => 'required|array',
            'rollStockBalanceCheck.S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV' => 'boolean',
            'rollStockBalanceCheck.S2-Sales from SO >> DO >> IV (Kanban/JIT)' => 'boolean',
            'rollStockBalanceCheck.S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV' => 'boolean',
            'rollStockBalanceCheck.N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO' => 'boolean',
            'rollStockBalanceCheck.N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)' => 'boolean',
            'rollStockBalanceCheck.N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)' => 'boolean',
            'computeRollStockBalance' => 'required|integer|min:10|max:99',
            'computeWIPSalesOrders' => 'required|integer|min:10|max:99',
            'jitSOPONo' => 'required|in:0,1',
        ]);

        $configData = [
            'activate_order_type' => json_encode($validated['activateOrderType']),
            'check_roll_balance' => $validated['checkRollBalance'],
            'roll_stock_balance_check' => json_encode($validated['rollStockBalanceCheck']),
            'compute_roll_stock_balance' => $validated['computeRollStockBalance'],
            'compute_wip_sales_orders' => $validated['computeWIPSalesOrders'],
            'jit_so_po_no' => $validated['jitSOPONo'],
        ];

        $config = SOConfig::first();
        if ($config) {
            $config->update($configData);
        } else {
            SOConfig::create($configData);
        }

        return response()->json([
            'message' => 'SO configuration saved successfully.'
        ], 200);
    }

    private function getConfiguration()
    {
        $config = SOConfig::first();

        if ($config) {
            return [
                'activateOrderType' => json_decode($config->activate_order_type, true),
                'checkRollBalance' => $config->check_roll_balance,
                'rollStockBalanceCheck' => json_decode($config->roll_stock_balance_check, true),
                'computeRollStockBalance' => $config->compute_roll_stock_balance,
                'computeWIPSalesOrders' => $config->compute_wip_sales_orders,
                'jitSOPONo' => $config->jit_so_po_no,
            ];
        }

        return [
            'activateOrderType' => [
                'S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV' => false,
                'S2-Sales from SO >> DO >> IV (Kanban/JIT)' => false,
                'S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV' => false,
                'N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO' => false,
                'N2-Non-Sales from SO >> DO (IFDC)' => false,
                'N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)' => false,
                'N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)' => false
            ],
            'checkRollBalance' => 'N-No Check',
            'rollStockBalanceCheck' => [
                'S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV' => false,
                'S2-Sales from SO >> DO >> IV (Kanban/JIT)' => false,
                'S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV' => false,
                'N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO' => false,
                'N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)' => false,
                'N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)' => false
            ],
            'computeRollStockBalance' => 10,
            'computeWIPSalesOrders' => 10,
            'jitSOPONo' => '0'
        ];
    }
} 