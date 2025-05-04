<?php

namespace App\Http\Controllers\SalesManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemRequirementController extends Controller
{
    public function viewPrintSalespersonTeam()
    {
        $salespersonTeams = DB::table('salesperson_teams')
            ->select('s_person_code', 'salesperson_name', 'st_code', 'sales_team_name', 'sales_team_position')
            ->orderBy('s_person_code')
            ->get();

        return view('sales-management.system-requirement.system-requirement.viewandprintsalespersonteam', compact('salespersonTeams'));
    }
} 