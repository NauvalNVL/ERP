<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramPrinterController extends Controller
{
    public function index()
    {
        return view('programprinter');
    }
}
