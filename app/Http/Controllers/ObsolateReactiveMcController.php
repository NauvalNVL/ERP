<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\UpdateMC;
use App\Models\UpdateCustomerAccount;
use App\Models\McUpdateLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ObsolateReactiveMcController extends Controller
{
    /**
     * Get current logged in user ID
     */
    private function getCurrentUserId()
    {
        // Log session information
        Log::info('ObsolateReactiveMcController - Session Info', [
            'session_id' => session()->getId(),
            'has_session' => session()->has('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'),
            'session_data' => session()->all(),
        ]);
        
        // Try to get userID from authenticated user
        if (Auth::check()) {
            $user = Auth::user();
            
            // Log user information for debugging
            Log::info('ObsolateReactiveMcController - Getting user ID', [
                'auth_check' => true,
                'user_class' => get_class($user),
                'user_attributes' => $user->getAttributes(),
                'has_userID' => isset($user->userID),
                'has_id' => isset($user->id),
                'userID_value' => $user->userID ?? null,
                'id_value' => $user->id ?? null,
            ]);
            
            // Check if user has userID property (from usercps table)
            if (isset($user->userID) && !empty($user->userID)) {
                Log::info('✅ Using userID from authenticated user', ['userID' => $user->userID]);
                return $user->userID;
            }
            
            // Fallback to id if userID doesn't exist
            if (isset($user->id) && !empty($user->id)) {
                Log::info('⚠️ Using id from authenticated user (userID not found)', ['id' => $user->id]);
                return $user->id;
            }
        } else {
            Log::warning('❌ ObsolateReactiveMcController - No authenticated user found', [
                'guards' => config('auth.guards'),
                'default_guard' => config('auth.defaults.guard'),
            ]);
        }
        
        // If no authenticated user, return 'SYSTEM'
        Log::error('❌ Using fallback user ID: SYSTEM - This should not happen in production!');
        return 'SYSTEM';
    }
    
    /**
     * Display the Obsolate Reactive MC page
     */
    public function index()
    {
        // Get all master cards from MC table
        $masterCards = UpdateMC::select('MCS_Num', 'AC_NUM', 'AC_NAME', 'MODEL', 'STS', 'COMP', 'P_DESIGN')
            ->orderBy('MCS_Num')
            ->get();
        
        $customers = UpdateCustomerAccount::orderBy('customer_name')->get();
        
        return Inertia::render('sales-management/system-requirement/master-card/obsolete-reactive-mc', [
            'masterCards' => $masterCards,
            'customers' => $customers
        ]);
    }

    /**
     * Get all master cards for API
     */
    public function apiIndex()
    {
        $masterCards = UpdateMC::select('MCS_Num', 'AC_NUM', 'AC_NAME', 'MODEL', 'STS', 'COMP', 'P_DESIGN')
            ->orderBy('MCS_Num')
            ->get();
        return response()->json($masterCards);
    }

    /**
     * Obsolate a master card - Change STS to 'OBSOLETE'
     */
    public function obsolate(Request $request, $mcsNum)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $masterCard = UpdateMC::where('MCS_Num', $mcsNum)->first();
        
        if (!$masterCard) {
            return response()->json(['error' => 'Master Card not found'], 404);
        }

        // Update status to OBSOLETE
        DB::table('MC')
            ->where('MCS_Num', $mcsNum)
            ->update(['STS' => 'OBSOLETE']);

        // Log the action using McUpdateLog model
        McUpdateLog::create([
            'MCS_Num' => $mcsNum,
            'status' => 'OBSOLETE',
            'reason' => $request->reason ?? 'No reason provided',
            'user_id' => $this->getCurrentUserId(),
        ]);

        return response()->json([
            'message' => 'Master Card has been marked as obsolete',
            'mcs_num' => $mcsNum
        ]);
    }

    /**
     * Reactive a master card - Change STS back to 'ACTIVE' or 'APPROVED'
     */
    public function reactive(Request $request, $mcsNum)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $masterCard = UpdateMC::where('MCS_Num', $mcsNum)->first();
        
        if (!$masterCard) {
            return response()->json(['error' => 'Master Card not found'], 404);
        }

        // Update status to ACTIVE
        DB::table('MC')
            ->where('MCS_Num', $mcsNum)
            ->update(['STS' => 'ACTIVE']);

        // Log the action using McUpdateLog model
        McUpdateLog::create([
            'MCS_Num' => $mcsNum,
            'status' => 'ACTIVE',
            'reason' => $request->reason ?? 'No reason provided',
            'user_id' => $this->getCurrentUserId(),
        ]);

        return response()->json([
            'message' => 'Master Card has been reactivated',
            'mcs_num' => $mcsNum
        ]);
    }

    /**
     * Bulk obsolete master cards.
     */
    public function bulkObsolete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mcs_nums' => 'required|array',
            'mcs_nums.*' => 'string',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $this->getCurrentUserId();
        $mcsNums = $request->input('mcs_nums');
        $reason = $request->input('reason') ?? 'No reason provided';

        // Update all selected master cards to OBSOLETE
        DB::table('MC')
            ->whereIn('MCS_Num', $mcsNums)
            ->update(['STS' => 'OBSOLETE']);

        // Log each action using McUpdateLog model
        foreach ($mcsNums as $mcsNum) {
            McUpdateLog::create([
                'MCS_Num' => $mcsNum,
                'status' => 'OBSOLETE',
                'reason' => $reason,
                'user_id' => $userId,
            ]);
        }

        return response()->json([
            'message' => 'Master cards have been obsoleted successfully.',
            'count' => count($mcsNums),
        ]);
    }

    /**
     * Bulk reactivate master cards.
     */
    public function bulkReactivate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mcs_nums' => 'required|array',
            'mcs_nums.*' => 'string',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $this->getCurrentUserId();
        $mcsNums = $request->input('mcs_nums');
        $reason = $request->input('reason') ?? 'No reason provided';

        // Update all selected master cards to ACTIVE
        DB::table('MC')
            ->whereIn('MCS_Num', $mcsNums)
            ->update(['STS' => 'ACTIVE']);

        // Log each action using McUpdateLog model
        foreach ($mcsNums as $mcsNum) {
            McUpdateLog::create([
                'MCS_Num' => $mcsNum,
                'status' => 'ACTIVE',
                'reason' => $reason,
                'user_id' => $userId,
            ]);
        }

        return response()->json([
            'message' => 'Master cards have been reactivated successfully.',
            'count' => count($mcsNums),
        ]);
    }

    /**
     * Get master cards by customer
     */
    public function getByCustomer($customerCode)
    {
        $masterCards = UpdateMC::select('MCS_Num', 'AC_NUM', 'AC_NAME', 'MODEL', 'STS', 'COMP', 'P_DESIGN')
            ->where('AC_NUM', $customerCode)
            ->orderBy('MCS_Num')
            ->get();
        return response()->json($masterCards);
    }

    /**
     * Get update log for a master card
     */
    public function getUpdateLog($mcsNum)
    {
        $logs = McUpdateLog::where('MCS_Num', $mcsNum)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($logs);
    }
    
    /**
     * Get master card details by MCS number with salesperson information
     */
    public function getMcDetails($mcsNum)
    {
        // Get MC details with customer information
        $mc = DB::table('MC')
            ->leftJoin('CUSTOMER', 'MC.AC_NUM', '=', 'CUSTOMER.CODE')
            ->select(
                'MC.*',
                'CUSTOMER.NAME as AC_NAME',
                'CUSTOMER.SLM as salesperson_code'
            )
            ->where('MC.MCS_Num', $mcsNum)
            ->first();
        
        if (!$mc) {
            return response()->json(['error' => 'Master Card not found'], 404);
        }
        
        // Get salesperson name from salesperson table
        if (!empty($mc->salesperson_code)) {
            $salesperson = DB::table('salesperson')
                ->where('Code', $mc->salesperson_code)
                ->first();
            
            if ($salesperson) {
                $mc->salesperson_name = $salesperson->Name;
            }
        }
        
        return response()->json($mc);
    }
    
    /**
     * Display the View and Print Master Cards page
     */
    public function viewAndPrint()
    {
        return Inertia::render('sales-management/system-requirement/master-card/view-and-print-MC');
    }
    
    /**
     * Display the View and Print MC Maintenance Log page
     */
    public function viewAndPrintMcMaintenanceLog()
    {
        return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-maintenance-log');
    }
}
