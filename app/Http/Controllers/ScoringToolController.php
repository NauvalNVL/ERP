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
                $seeder = new ScoringToolSeeder();
                $seeder->run();
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
            'specification' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('scoring-tool.index')
                ->withErrors($validator)
                ->withInput();
        }

        ScoringTool::create($request->all());

        return redirect()->route('scoring-tool.index')
            ->with('success', 'Scoring Tool berhasil ditambahkan.');
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
            'code' => 'required|string|max:10|unique:scoring_tools,code,' . $id,
            'name' => 'required|string|max:100',
                'scores' => 'required|numeric',
                'gap' => 'required|numeric',
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

            // Update the scoring tool
            $scoringTool->update($request->all());

            // Update the seeder file
            $this->updateSeederFile($scoringTool);

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
     * Update the seeder file with new data.
     */
    protected function updateSeederFile($scoringTool)
    {
        try {
            $seederPath = database_path('seeders/ScoringToolSeeder.php');
            $seederContent = file_get_contents($seederPath);

            // Create the new tool data string
            $newToolData = "            [
                'code' => '{$scoringTool->code}',
                'name' => '{$scoringTool->name}',
                'scores' => {$scoringTool->scores},
                'gap' => {$scoringTool->gap},
                'specification' => '{$scoringTool->specification}',
                'description' => '{$scoringTool->description}',
                'is_active' => " . ($scoringTool->is_active ? 'true' : 'false') . ",
            ],";

            // Find the existing tool data in the seeder file
            $pattern = "/\[\s*'code'\s*=>\s*'{$scoringTool->code}'.*?\]/s";
            
            if (preg_match($pattern, $seederContent)) {
                // Replace existing tool data
                $newContent = preg_replace($pattern, $newToolData, $seederContent);
            } else {
                // Add new tool data before the closing bracket of the array
                $newContent = str_replace(
                    "    protected \$scoringTools = [\n",
                    "    protected \$scoringTools = [\n" . $newToolData . "\n",
                    $seederContent
                );
            }

            // Write the updated content back to the file
            file_put_contents($seederPath, $newContent);

            Log::info('Seeder file updated successfully for scoring tool: ' . $scoringTool->code);
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating seeder file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove the specified scoring tool from storage.
     */
    public function destroy($id)
    {
        $scoringTool = ScoringTool::findOrFail($id);
        $scoringTool->delete();

        return redirect()->route('scoring-tool.index')
            ->with('success', 'Scoring Tool berhasil dihapus.');
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
}
