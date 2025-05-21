<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAlternateAddress;

class CustomerAlternateAddressController extends Controller
{
    public function index()
    {
        // You would typically fetch data here if needed
        $alternateAddresses = CustomerAlternateAddress::all();
        return view('sales-management.system-requirement.system-requirement.customer account.customeralternateaddress', compact('alternateAddresses'));
    }

    // Add other methods like store, update, destroy as needed later
}



