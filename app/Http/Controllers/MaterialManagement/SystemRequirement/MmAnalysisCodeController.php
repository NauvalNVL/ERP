<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmAnalysisCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MmAnalysisCodeController extends Controller
{
    /**
     * Display the analysis code management interface.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/AnalysisCode');
    }
    
    /**
     * Display the view and print analysis code interface.
     *
     * @return \Inertia\Response
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/ViewPrintAnalysisCode');
    }
    
    /**
     * Get all analysis codes for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalysisCodes(Request $request)
    {
        $query = MmAnalysisCode::query();
        
        // Filter by group if provided
        if ($request->has('group')) {
            $query->where('group', $request->group);
        }
        
        // Filter by group2 if provided
        if ($request->has('group2')) {
            $query->where('group2', $request->group2);
        }
        
        $analysisCodes = $query->orderBy('code')->get();
        return response()->json($analysisCodes);
    }
    
    /**
     * Get unique groups for filtering
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroups()
    {
        $groups = MmAnalysisCode::select('group')->distinct()->orderBy('group')->pluck('group');
        return response()->json($groups);
    }
    
    /**
     * Get unique group2 values for filtering
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroup2s()
    {
        $group2s = MmAnalysisCode::select('group2')->distinct()->orderBy('group2')->pluck('group2');
        return response()->json($group2s);
    }
    
    /**
     * Show a specific analysis code
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $analysisCode = MmAnalysisCode::findOrFail($code);
        return response()->json($analysisCode);
    }
    
    /**
     * Store a new analysis code
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:mm_analysis_codes,code',
            'name' => 'required|string|max:100',
            'group' => 'required|string|max:10',
            'group2' => 'required|string|max:10',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $analysisCode = MmAnalysisCode::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'group' => strtoupper($request->group),
            'group2' => strtoupper($request->group2),
        ]);
        
        return response()->json($analysisCode, 201);
    }
    
    /**
     * Update an existing analysis code
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'group' => 'required|string|max:10',
            'group2' => 'required|string|max:10',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $analysisCode = MmAnalysisCode::findOrFail($code);
        $analysisCode->update([
            'name' => $request->name,
            'group' => strtoupper($request->group),
            'group2' => strtoupper($request->group2),
        ]);
        
        return response()->json($analysisCode, 200);
    }
    
    /**
     * Delete an analysis code
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        $analysisCode = MmAnalysisCode::findOrFail($code);
        $analysisCode->delete();
        
        return response()->json([
            'message' => 'Analysis code deleted successfully',
        ], 200);
    }
    
    /**
     * Seed analysis codes with sample data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        // Call the seeder
        $seeder = new \Database\Seeders\MmAnalysisCodeSeeder();
        $seeder->run();
        
        return response()->json([
            'message' => 'Sample analysis codes seeded successfully',
        ], 200);
    }
} 