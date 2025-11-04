<?php

namespace App\Http\Controllers;

use App\Models\BundlingString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class BundlingStringController extends Controller
{
    /**
     * API method to get bundling strings data.
     */
    public function apiIndex(Request $request)
    {
        try {
            $bundlingStrings = BundlingString::orderBy('code', 'asc')->get();
            
            if ($bundlingStrings->isEmpty()) {
                $this->seedData();
                $bundlingStrings = BundlingString::orderBy('code', 'asc')->get();
            }
            
            return response()->json($bundlingStrings);
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@apiIndex: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error displaying bundling string data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the bundling strings.
     */
    public function index(Request $request)
    {
        try {
            $bundlingStrings = BundlingString::orderBy('code', 'asc')->get();
            
            // If no data exists, seed sample data
            if ($bundlingStrings->isEmpty()) {
                $this->seedData();
                $bundlingStrings = BundlingString::orderBy('code', 'asc')->get();
            }
            
            Log::info('Found ' . $bundlingStrings->count() . ' bundling strings in the database');
            
            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json($bundlingStrings);
            }
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/BundlingString', [
                'bundlingStrings' => $bundlingStrings,
                'header' => 'Define Bundling String'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@index: ' . $e->getMessage());
            
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error displaying bundling string data: ' . $e->getMessage()
                ], 500);
            }
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/BundlingString', [
                'bundlingStrings' => [],
                'error' => 'Error displaying bundling string data'
            ]);
        }
    }

    /**
     * Store a newly created bundling string in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creating new bundling string with data:', $request->all());

            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:bundling_strings,code|max:50',
                'name' => 'required|max:255'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $bundlingString = BundlingString::create([
                'code' => trim($request->code),
                'name' => trim($request->name),
                'is_active' => true
            ]);
            
            Log::info('Bundling string created successfully:', ['code' => $bundlingString->code]);
            
            return response()->json([
                'success' => true,
                'message' => 'Bundling string berhasil ditambahkan',
                'data' => $bundlingString
            ]);

        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@store: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating bundling string: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified bundling string in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Updating bundling string with ID: ' . $id);
            Log::info('Request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $bundlingString = BundlingString::find($id);
            
            if (!$bundlingString) {
                Log::warning('Bundling string not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Bundling string tidak ditemukan'
                ], 404);
            }

            $bundlingString->update([
                'name' => trim($request->name)
            ]);

            Log::info('Bundling string updated successfully:', ['id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Bundling string berhasil diperbarui',
                'data' => $bundlingString
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating bundling string: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating bundling string: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified bundling string from storage.
     */
    public function destroy($id)
    {
        try {
            $bundlingString = BundlingString::find($id);
            
            if ($bundlingString) {
                $bundlingString->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Bundling string berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Bundling string tidak ditemukan'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@destroy: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting bundling string: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource for printing with Vue.
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-bundling-string', [
                'header' => 'View & Print Bundling Strings'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-bundling-string', [
                'header' => 'View & Print Bundling Strings',
                'error' => 'Failed to load bundling string data for printing'
            ]);
        }
    }

    /**
     * Seed bundling string data (private method for internal use).
     */
    private function seedData()
    {
        try {
            $bundlingStrings = [
                ['code' => '001', 'name' => '5 MM', 'is_active' => true],
                ['code' => '002', 'name' => '7 MM', 'is_active' => true],
                ['code' => '003', 'name' => '10 MM', 'is_active' => true],
            ];

            foreach ($bundlingStrings as $string) {
                if (!BundlingString::where('code', $string['code'])->exists()) {
                    BundlingString::create($string);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding bundling string data: ' . $e->getMessage());
        }
    }

    /**
     * Seed the database with sample bundling string data (public API method).
     */
    public function seed()
    {
        try {
            $this->seedData();
            
            return response()->json([
                'success' => true,
                'message' => 'Bundling string seed data created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@seed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed bundling string data: ' . $e->getMessage()
            ], 500);
        }
    }
}
