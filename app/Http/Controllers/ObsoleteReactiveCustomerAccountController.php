<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ObsoleteReactiveCustomerAccountController extends Controller
{
    public function index()
    {
        // You would typically fetch data here if needed
        $customers = []; // Placeholder for customer data
        return view('sales-management.system-requirement.system-requirement.customer account.obsoletereactivecustomeraccount', compact('customers'));
    }
} 