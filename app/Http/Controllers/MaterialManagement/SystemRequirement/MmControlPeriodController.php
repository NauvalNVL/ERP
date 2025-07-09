<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\MmControlPeriod;
use Inertia\Inertia;

class MmControlPeriodController extends Controller
{
    /**
     * Display the Control Period page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/ControlPeriod');
    }

    /**
     * Get the current control period settings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getControlPeriod()
    {
        try {
            $controlPeriod = MmControlPeriod::first();
            
            if (!$controlPeriod) {
                // Return default values if no settings exist
                return response()->json([
                    'current_period' => date('m/Y'),
                    'fg_entry_date' => '',
                    'do_entry_date' => '',
                    'do_rejection_entry_date' => '',
                    'prRequisition' => [
                        'currentPeriod' => 'Same as P/Order Period',
                        'forwardPeriod' => '0',
                        'controlDate' => '1',
                    ],
                    'pOrder' => [
                        'currentPeriodMonth' => date('n'),
                        'currentPeriodYear' => date('Y'),
                        'forwardPeriod' => '1',
                        'controlDate' => '1',
                        'minAllowPercentage' => 0.00,
                        'maxAllowPercentage' => 0.00,
                        'zeroPO' => 'N',
                    ],
                    'inventory' => [
                        'currentPeriodMonth' => date('n'),
                        'currentPeriodYear' => date('Y'),
                        'backwardPeriod' => '0',
                        'controlDate' => '1',
                        'zeroTran' => 'Y',
                    ],
                    'costing' => [
                        'currentPeriodMonth' => date('n'),
                        'currentPeriodYear' => date('Y'),
                        'controlDate' => '1',
                        'yAllowAfterPeriod' => true,
                    ]
                ]);
            }
            
            // Format the response data
            $responseData = [
                'current_period' => $controlPeriod->po_current_period_month ? str_pad($controlPeriod->po_current_period_month, 2, '0', STR_PAD_LEFT) . '/' . $controlPeriod->po_current_period_year : '',
                'fg_entry_date' => $controlPeriod->fg_entry_date,
                'do_entry_date' => $controlPeriod->do_entry_date,
                'do_rejection_entry_date' => $controlPeriod->do_rejection_entry_date,
                'prRequisition' => [
                    'currentPeriod' => 'Same as P/Order Period',
                    'forwardPeriod' => $controlPeriod->pr_forward_period,
                    'controlDate' => $controlPeriod->pr_control_date,
                ],
                'pOrder' => [
                    'currentPeriodMonth' => $controlPeriod->po_current_period_month,
                    'currentPeriodYear' => $controlPeriod->po_current_period_year,
                    'forwardPeriod' => $controlPeriod->po_forward_period,
                    'controlDate' => $controlPeriod->po_control_date,
                    'minAllowPercentage' => $controlPeriod->po_min_allow_percentage,
                    'maxAllowPercentage' => $controlPeriod->po_max_allow_percentage,
                    'zeroPO' => $controlPeriod->po_zero_po,
                ],
                'inventory' => [
                    'currentPeriodMonth' => $controlPeriod->inv_current_period_month,
                    'currentPeriodYear' => $controlPeriod->inv_current_period_year,
                    'backwardPeriod' => $controlPeriod->inv_backward_period,
                    'controlDate' => $controlPeriod->inv_control_date,
                    'zeroTran' => $controlPeriod->inv_zero_tran,
                ],
                'costing' => [
                    'currentPeriodMonth' => $controlPeriod->cost_current_period_month,
                    'currentPeriodYear' => $controlPeriod->cost_current_period_year,
                    'controlDate' => $controlPeriod->cost_control_date,
                    'yAllowAfterPeriod' => $controlPeriod->cost_y_allow_after_period,
                ]
            ];
            
            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch control period settings: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update or create control period settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateControlPeriod(Request $request)
    {
        try {
            // Validate incoming request data
            $validator = Validator::make($request->all(), [
                'current_period' => 'required|string|regex:/^\\d{2}\\/{1}\\d{4}$/',
                'fg_entry_date' => 'required|string',
                'do_entry_date' => 'required|string',
                'do_rejection_entry_date' => 'required|string',
                'prRequisition.forwardPeriod' => 'required|string',
                'prRequisition.controlDate' => 'required|string',
                'pOrder.currentPeriodMonth' => 'required|integer|min:1|max:12',
                'pOrder.currentPeriodYear' => 'required|integer|min:2000|max:2100',
                'pOrder.forwardPeriod' => 'required|string',
                'pOrder.controlDate' => 'required|string',
                'pOrder.minAllowPercentage' => 'required|numeric|min:0|max:100',
                'pOrder.maxAllowPercentage' => 'required|numeric|min:0|max:100',
                'pOrder.zeroPO' => 'required|in:Y,N',
                'inventory.currentPeriodMonth' => 'required|integer|min:1|max:12',
                'inventory.currentPeriodYear' => 'required|integer|min:2000|max:2100',
                'inventory.backwardPeriod' => 'required|string',
                'inventory.controlDate' => 'required|string',
                'inventory.zeroTran' => 'required|in:Y,N',
                'costing.currentPeriodMonth' => 'required|integer|min:1|max:12',
                'costing.currentPeriodYear' => 'required|integer|min:2000|max:2100',
                'costing.controlDate' => 'required|string',
                'costing.yAllowAfterPeriod' => 'required|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            // Begin transaction
            DB::beginTransaction();
            
            $controlPeriod = MmControlPeriod::first();
            
            if (!$controlPeriod) {
                $controlPeriod = new MmControlPeriod();
            }
            
            // Update control period data
            $currentPeriodParts = explode('/', $request->input('current_period'));
            $controlPeriod->po_current_period_month = (int)$currentPeriodParts[0];
            $controlPeriod->po_current_period_year = (int)$currentPeriodParts[1];
            
            $controlPeriod->fg_entry_date = $request->input('fg_entry_date');
            $controlPeriod->do_entry_date = $request->input('do_entry_date');
            $controlPeriod->do_rejection_entry_date = $request->input('do_rejection_entry_date');

            $controlPeriod->pr_forward_period = $request->input('prRequisition.forwardPeriod');
            $controlPeriod->pr_control_date = $request->input('prRequisition.controlDate');
            
            $controlPeriod->po_current_period_month = $request->input('pOrder.currentPeriodMonth');
            $controlPeriod->po_current_period_year = $request->input('pOrder.currentPeriodYear');
            $controlPeriod->po_forward_period = $request->input('pOrder.forwardPeriod');
            $controlPeriod->po_control_date = $request->input('pOrder.controlDate');
            $controlPeriod->po_min_allow_percentage = $request->input('pOrder.minAllowPercentage');
            $controlPeriod->po_max_allow_percentage = $request->input('pOrder.maxAllowPercentage');
            $controlPeriod->po_zero_po = $request->input('pOrder.zeroPO');
            
            $controlPeriod->inv_current_period_month = $request->input('inventory.currentPeriodMonth');
            $controlPeriod->inv_current_period_year = $request->input('inventory.currentPeriodYear');
            $controlPeriod->inv_backward_period = $request->input('inventory.backwardPeriod');
            $controlPeriod->inv_control_date = $request->input('inventory.controlDate');
            $controlPeriod->inv_zero_tran = $request->input('inventory.zeroTran');
            
            $controlPeriod->cost_current_period_month = $request->input('costing.currentPeriodMonth');
            $controlPeriod->cost_current_period_year = $request->input('costing.currentPeriodYear');
            $controlPeriod->cost_control_date = $request->input('costing.controlDate');
            $controlPeriod->cost_y_allow_after_period = $request->input('costing.yAllowAfterPeriod');
            
            $controlPeriod->save();
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Control period settings saved successfully',
                'data' => $controlPeriod
            ]);
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();
            return response()->json(['error' => 'Failed to save control period settings: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the View & Print Control Period page.
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/ViewPrintControlPeriod');
    }

    /**
     * Get summary data for View & Print Control Period.
     */
    public function getControlPeriodSummary()
    {
        $controlPeriod = \App\Models\MmControlPeriod::first();
        $result = [];
        if ($controlPeriod) {
            $result = [
                [
                    'documentType' => 'P/Req & P/Order',
                    'currentPeriod' => sprintf('%02d/%04d', $controlPeriod->po_current_period_month, $controlPeriod->po_current_period_year),
                    'controlDate' => $this->getControlDateLabel($controlPeriod->po_control_date),
                ],
                [
                    'documentType' => 'Inventory',
                    'currentPeriod' => sprintf('%02d/%04d', $controlPeriod->inv_current_period_month, $controlPeriod->inv_current_period_year),
                    'controlDate' => $this->getControlDateLabel($controlPeriod->inv_control_date),
                ],
                [
                    'documentType' => 'Costing Period',
                    'currentPeriod' => sprintf('%02d/%04d', $controlPeriod->cost_current_period_month, $controlPeriod->cost_current_period_year),
                    'controlDate' => $this->getControlDateLabel($controlPeriod->cost_control_date),
                ],
            ];
        }
        return response()->json([
            'rows' => $result,
            'summary' => [
                'forwardPeriod' => $controlPeriod->po_forward_period ?? '01',
                'backwardPeriod' => $controlPeriod->inv_backward_period ?? 'N/A',
                'minAllow' => $controlPeriod->po_min_allow_percentage ?? 0,
                'maxAllow' => $controlPeriod->po_max_allow_percentage ?? 0,
            ]
        ]);
    }

    /**
     * Helper: Get control date label.
     */
    private function getControlDateLabel($val)
    {
        switch ($val) {
            case '1': return 'U - Under Current Period';
            case '2': return 'F - Under/Forward Current Period';
            case '3': return 'B - Under/Backward Current Period';
            case '4': return 'O - Open Date';
            default: return '-';
        }
    }
} 