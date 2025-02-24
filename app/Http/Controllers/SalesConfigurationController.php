<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesConfigurationController extends Controller
{
    public function index()
    {
        return view('system-requirement/salesconfiguration');
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
