<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesConfigurationController extends Controller
{
    public function index()
    {
        return view('sales-management.system-requirement.salesconfiguration');
    }

    public function vueIndex()
    {
        return Inertia::render('sales-management/system-requirement/sales-configuration/sales-configuration', [
            'title' => 'Define Sales Configuration'
        ]);
    }

    public function store(Request $request)
    {
        // Implementasi logika penyimpanan nanti
    }

    public function update(Request $request)
    {
        // Implementasi logika update nanti
    }
}
