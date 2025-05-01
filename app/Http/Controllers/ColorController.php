<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\ColorGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    /**
     * Display a listing of the colors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $colors = DB::table('colors')
                    ->where('color_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('color_name', 'like', '%' . $searchTerm . '%')
                    ->orderBy('color_id', 'asc')
                    ->get();
            } else {
                // Get all colors matching the seed data structure
                $colors = DB::table('colors')
                    ->select([
                        'color_id',
                        'color_name',
                        'origin',
                        'color_group_id',
                        'cg_type'
                    ])
                    ->orderBy('color_id', 'asc')
                    ->get();
            }
            
            $colorGroups = ColorGroup::all();
            
            // For debugging
            if ($colors->isEmpty()) {
                Log::info('No colors found in the database');
            } else {
                Log::info('Found ' . $colors->count() . ' colors in the database');
            }
            
            // Return JSON if requested
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($colors);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.color', [
                'colors' => $colors,
                'colorGroups' => $colorGroups
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@index: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error loading data from database: ' . $e->getMessage()
                ], 500);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.color', [
                'colors' => [],
                'colorGroups' => [],
                'error' => 'Terjadi kesalahan dalam menampilkan data warna: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new color.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colorGroups = ColorGroup::all();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.color', compact('colorGroups'));
    }

    /**
     * Store a newly created color in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'color_id' => 'required|unique:colors|max:10',
                'color_name' => 'required|max:50',
                'color_group_id' => 'required|exists:color_groups,id',
                'origin' => 'nullable|max:100'
            ]);

            Color::create($validatedData);

            return redirect()->route('colors.index')->with('success', 'Color created successfully');
        } catch (\Exception $e) {
            Log::error('Error in ColorController@store: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified color.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $color = DB::table('colors')->where('color_id', $id)->first();
            
            if (!$color) {
                return redirect()->route('color.index')->with('error', 'Warna tidak ditemukan');
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.color-edit', ['color' => $color]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@edit: ' . $e->getMessage());
            return redirect()->route('color.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified color in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Updating color with ID: ' . $id);
            Log::info('Request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'color_name' => 'required|string|max:100',
                'origin' => 'required|string|max:50',
                'color_group_id' => 'required|string|max:10',
                'cg_type' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Find the color using DB facade since we're using color_id as primary key
            $color = DB::table('colors')->where('color_id', $id)->first();
            
            if (!$color) {
                Log::warning('Color not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Color tidak ditemukan'
                ], 404);
            }

            // Update the color using DB facade
            $updated = DB::table('colors')
                ->where('color_id', $id)
                ->update([
                    'color_name' => $request->color_name,
                    'origin' => $request->origin,
                    'color_group_id' => $request->color_group_id,
                    'cg_type' => $request->cg_type,
                    'updated_at' => now()
                ]);

            if (!$updated) {
                Log::warning('No changes made to color with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perubahan data'
                ], 400);
            }

            // Get the updated color data
            $updatedColor = DB::table('colors')->where('color_id', $id)->first();

            // Update the seeder file
            $this->updateSeederFile($updatedColor);

            Log::info('Color updated successfully:', ['color_id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Color berhasil diperbarui',
                'data' => $updatedColor
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating color: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating color: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the seeder file with new data.
     */
    protected function updateSeederFile($color)
    {
        try {
            $seederPath = database_path('seeders/ColorSeeder.php');
            
            if (!file_exists($seederPath)) {
                Log::error('Seeder file not found at: ' . $seederPath);
                return false;
            }

            $seederContent = file_get_contents($seederPath);
            
            // Create the new color data string
            $newColorData = "            [
                'color_id' => '{$color->color_id}',
                'color_name' => '{$color->color_name}',
                'origin' => '{$color->origin}',
                'color_group_id' => '{$color->color_group_id}',
                'cg_type' => '{$color->cg_type}',
                'created_at' => now(),
                'updated_at' => now(),
            ],";

            // Find the existing color data in the seeder file
            $pattern = "/\[\s*'color_id'\s*=>\s*'{$color->color_id}'.*?\]/s";
            
            if (preg_match($pattern, $seederContent)) {
                // Replace existing color data
                $newContent = preg_replace($pattern, $newColorData, $seederContent);
            } else {
                // Add new color data before the closing bracket of the array
                $newContent = str_replace(
                    "    protected \$colors = [\n",
                    "    protected \$colors = [\n" . $newColorData . "\n",
                    $seederContent
                );
            }

            // Write the updated content back to the file
            if (file_put_contents($seederPath, $newContent) === false) {
                Log::error('Failed to write to seeder file');
                return false;
            }

            Log::info('Seeder file updated successfully for color: ' . $color->color_id);
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating seeder file: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Remove the specified color from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $affected = DB::table('colors')->where('color_id', $id)->delete();
            
            if ($affected) {
                return redirect()->route('color.index')->with('success', 'Warna berhasil dihapus');
            } else {
                return back()->with('error', 'Warna tidak ditemukan');
            }
        } catch (\Exception $e) {
            Log::error('Error in ColorController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the colors for Vue component.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            // Get all colors matching the seed data structure
            $colors = DB::table('colors')
                ->select([
                    'color_id',
                    'color_name',
                    'origin',
                    'color_group_id',
                    'cg_type'
                ])
                ->orderBy('color_id', 'asc')
                ->get();
            
            $colorGroups = ColorGroup::all();
            
            return Inertia::render('sales-management/system-requirement/system-requirement/standard-requirement/color', [
                'colors' => $colors,
                'colorGroups' => $colorGroups
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@vueIndex: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/system-requirement/standard-requirement/color', [
                'colors' => [],
                'colorGroups' => [],
                'error' => 'Terjadi kesalahan dalam menampilkan data warna: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data warna, urutkan berdasarkan nama
        // Eager load ColorGroup
        $colors = Color::with('colorGroup')->orderBy('color_name')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintcolor', compact('colors')); 
    }
}
