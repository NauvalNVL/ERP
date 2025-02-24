<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomisedProgramController extends Controller
{
    public function index()
    {
        return view('system-setup/customisedprogram');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'program_id' => 'required|string|max:255',
            // tambahkan validasi lain sesuai kebutuhan
        ]);

        // Logika penyimpanan data
        // ...

        return redirect()->back()->with('success', 'Program berhasil ditambahkan');
    }
} 