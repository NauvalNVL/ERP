<?php

namespace App\Http\Controllers;

use App\Models\ScoringTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Database\Seeders\ScoringToolSeeder;

class ScoringToolController extends Controller
{
    /**
     * Display a listing of the scoring tools.
     */
    public function index(Request $request)
    {
        try {
            // Always load from database, ordered by code
            $scoringTools = ScoringTool::orderBy('code')->get();
            
            // If there are no tools in the database, seed them
            if ($scoringTools->isEmpty()) {
                $this->seedData();
                $scoringTools = ScoringTool::orderBy('code')->get();
            }
            
            // If the request wants JSON, return JSON response
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($scoringTools);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.scoringtool', compact('scoringTools'));
        } catch (\Exception $e) {
            Log::error('Error loading scoring tools: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error loading data from database: ' . $e->getMessage()
                ], 500);
            }
            
            // Return view with error message using session flash
            return redirect()->back()->with('error', 'Error loading data from database: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created scoring tool in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:scoring_tools',
            'name' => 'required|string|max:100',
            'scorer_gap' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $scoringTool = ScoringTool::create([
            'code' => $request->code,
            'name' => $request->name,
            'scorer_gap' => $request->scorer_gap
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scoring Tool berhasil ditambahkan.',
            'data' => $scoringTool
        ]);
    }

    /**
     * Show the form for editing the specified scoring tool.
     */
    public function edit($id)
    {
        $scoringTool = ScoringTool::findOrFail($id);
        $scoringTools = ScoringTool::orderBy('code')->paginate(10);
        
        return view('sales-management.system-requirement.system-requirement.standard-requirement.scoringtool', compact('scoringTool', 'scoringTools'));
    }

    /**
     * Update the specified scoring tool in storage.
     */
    public function update(Request $request, $id)
    {
        try {
        $scoringTool = ScoringTool::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|max:10|unique:scoring_tools,code,' . $id,
            'name' => 'nullable|string|max:100',
            'scorer_gap' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the scoring tool
            $updateData = [];
            if ($request->has('code')) $updateData['code'] = $request->code;
            if ($request->has('name')) $updateData['name'] = $request->name;
            if ($request->has('scorer_gap')) $updateData['scorer_gap'] = $request->scorer_gap;
            if ($request->has('is_active')) $updateData['is_active'] = $request->is_active;
            
            $scoringTool->update($updateData);

            // Get the updated data
            $updatedTool = ScoringTool::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Scoring Tool berhasil diperbarui.',
                'data' => $updatedTool
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating scoring tool: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating scoring tool: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified scoring tool from storage.
     */
    public function destroy($id)
    {
        try {
            Log::info('Attempting to delete scoring tool with ID/code: ' . $id);
            
            // Try to find by ID first (if it's a number)
            if (is_numeric($id)) {
                $scoringTool = ScoringTool::find($id);
            } else {
                // If not found or not numeric, try to find by code
                $scoringTool = ScoringTool::where('code', $id)->first();
            }
            
            if (!$scoringTool) {
                Log::warning('Scoring tool not found with ID/code: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Scoring tool not found with identifier: ' . $id
                ], 404);
            }
            
            Log::info('Found scoring tool to delete: ' . json_encode($scoringTool));
            
            // Store info before deletion for the response
            $toolInfo = [
                'id' => $scoringTool->id,
                'code' => $scoringTool->code,
                'name' => $scoringTool->name
            ];
            
            $scoringTool->delete();
            Log::info('Successfully deleted scoring tool: ' . json_encode($toolInfo));
            
            return response()->json([
                'success' => true,
                'message' => 'Scoring Tool berhasil dihapus.',
                'data' => $toolInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting scoring tool: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting scoring tool: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified scoring tool.
     */
    public function show($id)
    {
        $scoringTool = ScoringTool::findOrFail($id);
        return response()->json($scoringTool);
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data scoring tool, urutkan berdasarkan code
        $scoringTools = ScoringTool::orderBy('code')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintscoringtool', compact('scoringTools')); 
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
     */
    public function vueViewAndPrint()
    {
        try {
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-scoring-tool');
        } catch (\Exception $e) {
            Log::error('Error in ScoringToolController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load scoring tool data for printing'], 500);
        }
    }

    /**
     * Get seeder data for scoring tools.
     */
    public function getSeederData()
    {
        // Get the seeder instance
        $seeder = new ScoringToolSeeder();
        
        // Get the data from the seeder
        $scoringTools = $seeder->getSeederData();
        
        return response()->json($scoringTools);
    }

    /**
     * Update seeder data for scoring tools.
     */
    public function updateSeederData(Request $request)
    {
        try {
            $data = $request->all();
            
            // Update the seeder file
            $seederPath = database_path('seeders/ScoringToolSeeder.php');
            $seederContent = file_get_contents($seederPath);
            
            // Find the scoring tools array in the seeder file
            $pattern = '/\$scoringTools\s*=\s*\[(.*?)\];/s';
            
            // Create new scoring tools array content
            $newToolsArray = "[\n";
            foreach ($data as $tool) {
                $newToolsArray .= "            [\n";
                $newToolsArray .= "                'code' => '{$tool['code']}',\n";
                $newToolsArray .= "                'name' => '{$tool['name']}',\n";
                $newToolsArray .= "                'scores' => {$tool['scores']},\n";
                $newToolsArray .= "                'gap' => {$tool['gap']},\n";
                $newToolsArray .= "                'specification' => '',\n";
                $newToolsArray .= "                'description' => '{$tool['name']}',\n";
                $newToolsArray .= "                'is_active' => true,\n";
                $newToolsArray .= "            ],\n";
            }
            $newToolsArray .= "        ]";
            
            // Replace the old array with the new one
            $newContent = preg_replace($pattern, '$scoringTools = ' . $newToolsArray . ';', $seederContent);
            
            // Write the updated content back to the file
            file_put_contents($seederPath, $newContent);

            // Also update the database
            foreach ($data as $tool) {
                ScoringTool::updateOrCreate(
                    ['code' => $tool['code']],
                    [
                        'name' => $tool['name'],
                        'scores' => $tool['scores'],
                        'gap' => $tool['gap'],
                        'specification' => '',
                        'description' => $tool['name'],
                        'is_active' => true
                    ]
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Seeder data updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating seeder data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the Vue component for scoring tool management.
     *
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
     */
    public function vueIndex()
    {
        try {
            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/scoring-tool', [
                'header' => 'Scoring Tool Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScoringToolController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load scoring tool data'], 500);
        }
    }

    /**
     * Display the Vue version of scoring tool status management page
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $scoringTools = ScoringTool::orderBy('code', 'asc')->paginate(15);

            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-scoring-tool', [
                'scoringTools' => $scoringTools->items(),
                'pagination' => [
                    'currentPage' => $scoringTools->currentPage(),
                    'perPage' => $scoringTools->perPage(),
                    'total' => $scoringTools->total()
                ],
                'header' => 'Manage Scoring Tool Status'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScoringToolController@vueManageStatus: ' . $e->getMessage());

            return \Inertia\Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-scoring-tool', [
                'scoringTools' => [],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => 15,
                    'total' => 0
                ],
                'header' => 'Manage Scoring Tool Status',
                'error' => 'Error displaying scoring tools: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get all scoring tools as JSON for API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $scoringTools = ScoringTool::orderBy('code')->get();
            return response()->json($scoringTools);
        } catch (\Exception $e) {
            Log::error('Error in ScoringToolController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load scoring tool data'], 500);
        }
    }
    
    /**
     * Run the scoring tool seeder.
     *
     * @return void
     */
    private function seedData()
    {
        try {
            $seeder = new ScoringToolSeeder();
            $seeder->run();
        } catch (\Exception $e) {
            Log::error('Error seeding scoring tool data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Scoring tool seed data created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating scoring tool seed data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API method to seed scoring tools data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSeed()
    {
        try {
            $sampleTools = [
                ['code' => 'MM01', 'name' => 'MALE MALE', 'scores' => 1.0, 'gap' => 0.5, 'specification' => 'Standard', 'description' => 'Male Male scoring tool'],
                ['code' => 'MF10', 'name' => 'MALE FEMALE 10MM', 'scores' => 1.5, 'gap' => 1.0, 'specification' => 'Medium', 'description' => 'Male Female 10mm scoring tool'],
                ['code' => 'MF15', 'name' => 'MALE FEMALE 15MM', 'scores' => 2.0, 'gap' => 1.5, 'specification' => 'Large', 'description' => 'Male Female 15mm scoring tool'],
                ['code' => 'MF20', 'name' => 'MALE FLAT', 'scores' => 2.5, 'gap' => 2.0, 'specification' => 'Special', 'description' => 'Male Flat scoring tool'],
                ['code' => 'FF01', 'name' => 'FEMALE FLAT', 'scores' => 3.0, 'gap' => 2.5, 'specification' => 'Custom', 'description' => 'Female Flat scoring tool'],
            ];

            $createdTools = [];
            foreach ($sampleTools as $tool) {
                // Check if the tool already exists
                $existingTool = ScoringTool::where('code', $tool['code'])->first();
                
                if (!$existingTool) {
                    $newTool = ScoringTool::create([
                        'code' => $tool['code'],
                        'name' => $tool['name'],
                        'scores' => $tool['scores'],
                        'gap' => $tool['gap'],
                        'specification' => $tool['specification'],
                        'description' => $tool['description'],
                        'is_active' => true
                    ]);
                    
                    $createdTools[] = $newTool;
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($createdTools) . ' scoring tools seeded successfully',
                'data' => $createdTools
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScoringToolController@apiSeed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error seeding scoring tools: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the seeder file with new scoring tool data
     *
     * @param  \App\Models\ScoringTool  $scoringTool
     * @return void
     */
    private function updateSeederFile($scoringTool)
    {
        try {
            // This method would update the seeder file with the new tool
            // For now, we'll just log the action
            Log::info('Seeder file update needed for tool: ' . $scoringTool->code);
        } catch (\Exception $e) {
            Log::error('Error updating seeder file: ' . $e->getMessage());
        }
    }

    /**
     * API method to store a scoring tool
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:10|unique:scoring_tools',
                'name' => 'required|string|max:100',
                'scores' => 'required|numeric|min:0',
                'gap' => 'required|numeric|min:0',
                'specification' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $scoringTool = ScoringTool::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'scores' => $request->scores,
                'gap' => $request->gap,
                'specification' => $request->specification,
                'description' => $request->description,
                'is_active' => $request->is_active ?? true,
            ]);
            
            // Update the seeder file
            $this->updateSeederFile($scoringTool);

            return response()->json([
                'success' => true,
                'message' => 'Scoring Tool created successfully',
                'data' => $scoringTool
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating scoring tool: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create scoring tool: ' . $e->getMessage()
            ], 500);
        }
    }
}
