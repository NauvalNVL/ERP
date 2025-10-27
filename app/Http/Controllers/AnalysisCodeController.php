<?php

namespace App\Http\Controllers;

use App\Models\AnalysisCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnalysisCodeController extends Controller
{
    public function index()
    {
        // Use the MmAnalysisCode model instead since that's what exists in the database
        $analysisCodes = \App\Models\MmAnalysisCode::all();
        return response()->json($analysisCodes);
    }

    public function show($code)
    {
        $analysisCode = \App\Models\MmAnalysisCode::where('code', $code)->first();
        if (!$analysisCode) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($analysisCode);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:mm_analysis_codes,code',
            'name' => 'required|string',
            'group' => 'required|string',
            'group2' => 'required|string',
        ]);
        $analysisCode = \App\Models\MmAnalysisCode::create($validated);
        return response()->json($analysisCode, 201);
    }

    public function update(Request $request, $code)
    {
        $analysisCode = \App\Models\MmAnalysisCode::where('code', $code)->first();
        if (!$analysisCode) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $validated = $request->validate([
            'name' => 'required|string',
            'group' => 'required|string',
            'group2' => 'required|string',
        ]);
        $analysisCode->update($validated);
        return response()->json($analysisCode);
    }

    public function destroy($code)
    {
        $analysisCode = \App\Models\MmAnalysisCode::where('code', $code)->first();
        if (!$analysisCode) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $analysisCode->delete();
        return response()->json(['message' => 'Deleted']);
    }
}