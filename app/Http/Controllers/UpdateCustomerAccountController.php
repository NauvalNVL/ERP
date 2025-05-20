<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateCustomerAccount;
use App\Models\Industry;
use App\Models\Geo;

class UpdateCustomerAccountController extends Controller
{
    public function index()
    {
        $accounts = \App\Models\UpdateCustomerAccount::all();
        $industries = Industry::all();
        $geoData = Geo::all();
        return view('sales-management.system-requirement.system-requirement.customer account.updatecustomeraccount', compact('accounts', 'industries', 'geoData'));
    }
}
