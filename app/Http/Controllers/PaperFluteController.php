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
            // Always load from database, ordered by Flute
            $paperFlutes = PaperFlute::orderBy('Flute')->get();
            
            // If there are no flutes in the database, seed them
            if ($paperFlutes->isEmpty()) {
                $this->seedData();
                $paperFlutes = PaperFlute::orderBy('Flute')->get();
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
                'Flute' => 'required|string|max:25|unique:Flute_CPS,Flute',
                'Descr' => 'required|string|max:100',
                'DB' => 'nullable|numeric|min:0',
                'B' => 'nullable|numeric|min:0',
                '_1L' => 'nullable|numeric|min:0',
                'A_C_E' => 'nullable|numeric|min:0',
                '_2L' => 'nullable|numeric|min:0',
                'Height' => 'nullable|numeric|min:0',
                'Starch' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $data = $request->all();
            $data['No'] = PaperFlute::max('No') + 1;
            
            $paperFlute = PaperFlute::create($data);

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
    public function update(Request $request, $flute)
    {
        try {
            $paperFlute = PaperFlute::where('Flute', $flute)->firstOrFail();

            $validator = Validator::make($request->all(), [
                'Descr' => 'required|string|max:100',
                'DB' => 'nullable|numeric|min:0',
                'B' => 'nullable|numeric|min:0',
                '_1L' => 'nullable|numeric|min:0',
                'A_C_E' => 'nullable|numeric|min:0',
                '_2L' => 'nullable|numeric|min:0',
                'Height' => 'nullable|numeric|min:0',
                'Starch' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the paper flute
            $paperFlute->update($request->all());

            // Get the updated data
            $updatedFlute = PaperFlute::where('Flute', $flute)->first();

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
    public function destroy($flute)
    {
        try {
            $paperFlute = PaperFlute::where('Flute', $flute)->firstOrFail();
            
            // Store information before deletion for the response
            $fluteInfo = [
                'Flute' => $paperFlute->Flute,
                'Descr' => $paperFlute->Descr
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
        // Ambil semua data paper flute, urutkan berdasarkan Flute
        $paperFlutes = PaperFlute::orderBy('Flute')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintpaperflute', compact('paperFlutes')); 
    }

    /**
     * Display a listing of the resource using Vue.
     *
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
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
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
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
     * Display a listing of the resource for API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $paperFlutes = PaperFlute::orderBy('Flute')->get();
            return response()->json($paperFlutes);
        } catch (\Exception $e) {
            Log::error('Error fetching paper flutes for API: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load paper flute data'], 500);
        }
    }

    private function seedData()
    {
        try {
            $seeder = new PaperFluteSeeder();
            $seeder->run();
        } catch (\Exception $e) {
            Log::error('Error seeding paper flute data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Paper flute seed data created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating paper flute seed data: ' . $e->getMessage()
            ], 500);
        }
    }
} 