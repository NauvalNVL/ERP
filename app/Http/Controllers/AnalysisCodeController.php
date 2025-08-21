<?php

namespace App\Http\Controllers;

use App\Models\AnalysisCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnalysisCodeController extends Controller
{
    public function index()
    {
        return response()->json(AnalysisCode::all());
    }

    public function show($code)
    {
        $analysisCode = AnalysisCode::where('code', $code)->first();
        if (!$analysisCode) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($analysisCode);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:analysis_codes,code',
            'name' => 'required|string',
            'grouping' => 'required|string',
        ]);
        $analysisCode = AnalysisCode::create($validated);
        return response()->json($analysisCode, 201);
    }

    public function update(Request $request, $code)
    {
        $analysisCode = AnalysisCode::where('code', $code)->first();
        if (!$analysisCode) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $validated = $request->validate([
            'name' => 'required|string',
            'grouping' => 'required|string',
        ]);
        $analysisCode->update($validated);
        return response()->json($analysisCode);
    }

    public function destroy($code)
    {
        $analysisCode = AnalysisCode::where('code', $code)->first();
        if (!$analysisCode) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $analysisCode->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
