<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateCustomerAccount;

class UpdateCustomerAccountController extends Controller
{
    public function index()
    {
        $accounts = \App\Models\UpdateCustomerAccount::all();
        return view('sales-management.system-requirement.system-requirement.customer account.updatecustomeraccount', compact('accounts'));
    }
}
