<?php

namespace App\Http\Controllers;

use App\Models\PaperFlute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Database\Seeders\PaperFluteSeeder;
use Inertia\Inertia;

class PaperFluteController extends Controller
{
    /**
     * Display a listing of the paper flutes.
     */
    public function index(Request $request)
    {
        try {
            // Always load from database, ordered by code
            $paperFlutes = PaperFlute::orderBy('code')->get();
            
            // If there are no flutes in the database, seed them
            if ($paperFlutes->isEmpty()) {
                $seeder = new PaperFluteSeeder();
                $seeder->run();
                $paperFlutes = PaperFlute::orderBy('code')->get();
            }
            
            // If the request wants JSON, return JSON response
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($paperFlutes);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.paperflute', compact('paperFlutes'));
        } catch (\Exception $e) {
            Log::error('Error loading paper flutes: ' . $e->getMessage());
            
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
     * Store a newly created paper flute in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:20|unique:paper_flutes',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'flute_height' => 'required|numeric',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $paperFlute = PaperFlute::create($request->all());

            // Update the seeder file if needed
            $this->updateSeederFile($paperFlute);

            return response()->json([
                'success' => true,
                'message' => 'Paper flute berhasil ditambahkan.',
                'data' => $paperFlute
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating paper flute: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating paper flute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified paper flute.
     */
    public function edit($id)
    {
        $paperFlute = PaperFlute::findOrFail($id);
        $paperFlutes = PaperFlute::orderBy('code')->paginate(10);
        
        return view('sales-management.system-requirement.system-requirement.standard-requirement.paperflute', compact('paperFlute', 'paperFlutes'));
    }

    /**
     * Update the specified paper flute in storage.
     */
    public function update(Request $request, $code)
    {
        try {
            $paperFlute = PaperFlute::where('code', $code)->firstOrFail();

            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:20|unique:paper_flutes,code,' . $paperFlute->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
                'flute_height' => 'required|numeric',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the paper flute
            $paperFlute->update($request->all());

            // Update the seeder file
            $this->updateSeederFile($paperFlute);

            // Get the updated data
            $updatedFlute = PaperFlute::where('code', $code)->first();

            return response()->json([
                'success' => true,
                'message' => 'Paper flute berhasil diperbarui.',
                'data' => $updatedFlute
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating paper flute: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating paper flute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the seeder file with new data.
     */
    protected function updateSeederFile($paperFlute)
    {
        try {
            $seederPath = database_path('seeders/PaperFluteSeeder.php');
            $seederContent = file_get_contents($seederPath);

            // Create the new flute data string
            $newFluteData = "            [
                'code' => '{$paperFlute->code}',
                'name' => '{$paperFlute->name}',
                'description' => '{$paperFlute->description}',
                'tur_l2b' => {$paperFlute->tur_l2b},
                'tur_l3' => {$paperFlute->tur_l3},
                'tur_l1' => {$paperFlute->tur_l1},
                'tur_ace' => {$paperFlute->tur_ace},
                'tur_2l' => {$paperFlute->tur_2l},
                'flute_height' => {$paperFlute->flute_height},
                'starch_consumption' => {$paperFlute->starch_consumption},
                'is_active' => " . ($paperFlute->is_active ? 'true' : 'false') . ",
            ],";

            // Find the existing flute data in the seeder file
            $pattern = "/\[\s*'code'\s*=>\s*'{$paperFlute->code}'.*?\]/s";
            
            if (preg_match($pattern, $seederContent)) {
                // Replace existing flute data
                $newContent = preg_replace($pattern, $newFluteData, $seederContent);
            } else {
                // Add new flute data before the closing bracket of the array
                $newContent = str_replace(
                    "    protected \$flutes = [\n",
                    "    protected \$flutes = [\n" . $newFluteData . "\n",
                    $seederContent
                );
            }

            // Write the updated content back to the file
            file_put_contents($seederPath, $newContent);

            Log::info('Seeder file updated successfully for paper flute: ' . $paperFlute->code);
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating seeder file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove the specified paper flute from storage.
     */
    public function destroy($code)
    {
        try {
            $paperFlute = PaperFlute::where('code', $code)->firstOrFail();
            
            // Store information before deletion for the response
            $fluteInfo = [
                'id' => $paperFlute->id,
                'code' => $paperFlute->code,
                'name' => $paperFlute->name
            ];
            
            $paperFlute->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Paper flute berhasil dihapus.',
                'data' => $fluteInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting paper flute: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting paper flute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified paper flute.
     */
    public function show($id)
    {
        $paperFlute = PaperFlute::findOrFail($id);
        return response()->json($paperFlute);
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data paper flute, urutkan berdasarkan code
        $paperFlutes = PaperFlute::orderBy('code')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintpaperflute', compact('paperFlutes')); 
    }

    /**
     * Display a listing of the resource using Vue.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/paper-flute', [
                'header' => 'Paper Flute Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in PaperFluteController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load paper flute data'], 500);
        }
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-paper-flute');
        } catch (\Exception $e) {
            Log::error('Error in PaperFluteController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load paper flute data for printing'], 500);
        }
    }

    /**
     * Get seeder data for paper flutes.
     */
    public function getSeederData()
    {
        // Get the seeder instance
        $seeder = new PaperFluteSeeder();
        
        // Get the data from the seeder
        $paperFlutes = $seeder->getSeederData();
        
        return response()->json($paperFlutes);
    }

    /**
     * Update seeder data for paper flutes.
     */
    public function updateSeederData(Request $request)
    {
        try {
            $data = $request->all();
            
            // Update the seeder file
            $seederPath = database_path('seeders/PaperFluteSeeder.php');
            $seederContent = file_get_contents($seederPath);
            
            // Find the paper flutes array in the seeder file
            $pattern = '/\$flutes\s*=\s*\[(.*?)\];/s';
            
            // Create new paper flutes array content
            $newFlutesArray = "[\n";
            foreach ($data as $flute) {
                $newFlutesArray .= "            [\n";
                $newFlutesArray .= "                'code' => '{$flute['code']}',\n";
                $newFlutesArray .= "                'name' => '{$flute['name']}',\n";
                $newFlutesArray .= "                'description' => '{$flute['description']}',\n";
                $newFlutesArray .= "                'tur_l2b' => {$flute['tur_l2b']},\n";
                $newFlutesArray .= "                'tur_l3' => {$flute['tur_l3']},\n";
                $newFlutesArray .= "                'tur_l1' => {$flute['tur_l1']},\n";
                $newFlutesArray .= "                'tur_ace' => {$flute['tur_ace']},\n";
                $newFlutesArray .= "                'tur_2l' => {$flute['tur_2l']},\n";
                $newFlutesArray .= "                'flute_height' => {$flute['flute_height']},\n";
                $newFlutesArray .= "                'starch_consumption' => {$flute['starch_consumption']},\n";
                $newFlutesArray .= "                'is_active' => true,\n";
                $newFlutesArray .= "            ],\n";
            }
            $newFlutesArray .= "        ]";
            
            // Replace the old array with the new one
            $newContent = preg_replace($pattern, '$flutes = ' . $newFlutesArray . ';', $seederContent);
            
            // Write the updated content back to the file
            file_put_contents($seederPath, $newContent);

            // Also update the database
            foreach ($data as $flute) {
                PaperFlute::updateOrCreate(
                    ['code' => $flute['code']],
                    [
                        'name' => $flute['name'],
                        'description' => $flute['description'],
                        'tur_l2b' => $flute['tur_l2b'],
                        'tur_l3' => $flute['tur_l3'],
                        'tur_l1' => $flute['tur_l1'],
                        'tur_ace' => $flute['tur_ace'],
                        'tur_2l' => $flute['tur_2l'],
                        'flute_height' => $flute['flute_height'],
                        'starch_consumption' => $flute['starch_consumption'],
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