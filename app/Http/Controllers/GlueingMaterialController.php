<?php

namespace App\Http\Controllers;

use App\Models\GlueingMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class GlueingMaterialController extends Controller
{
    public function apiIndex(Request $request)
    {
        try {
            $glueingMaterials = GlueingMaterial::orderBy('code', 'asc')->get();
            
            if ($glueingMaterials->isEmpty()) {
                $this->seedData();
                $glueingMaterials = GlueingMaterial::orderBy('code', 'asc')->get();
            }
            
            return response()->json($glueingMaterials);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Error displaying glueing material data'], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $glueingMaterials = GlueingMaterial::orderBy('code', 'asc')->get();

            if ($glueingMaterials->isEmpty()) {
                $this->seedData();
                $glueingMaterials = GlueingMaterial::orderBy('code', 'asc')->get();
            }

            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json($glueingMaterials);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/GlueingMaterial', [
                'glueingMaterials' => $glueingMaterials,
                'header' => 'Define Glueing Material'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@index: ' . $e->getMessage());

            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json(['error' => true, 'message' => 'Error displaying glueing material data'], 500);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/GlueingMaterial', [
                'glueingMaterials' => [],
                'error' => 'Error displaying glueing material data'
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:glueing_materials,code|max:50',
                'name' => 'required|max:255',
                'description' => 'nullable|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $glueingMaterial = GlueingMaterial::create([
                'code' => trim($request->code),
                'name' => trim($request->name),
                'description' => $request->description ? trim($request->description) : null,
                'is_active' => true
            ]);

            return response()->json(['success' => true, 'message' => 'Glueing material berhasil ditambahkan', 'data' => $glueingMaterial]);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@store: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating glueing material'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $glueingMaterial = GlueingMaterial::find($id);

            if (!$glueingMaterial) {
                return response()->json(['success' => false, 'message' => 'Glueing material tidak ditemukan'], 404);
            }

            $glueingMaterial->update([
                'name' => trim($request->name),
                'description' => $request->description ? trim($request->description) : null
            ]);

            return response()->json(['success' => true, 'message' => 'Glueing material berhasil diperbarui', 'data' => $glueingMaterial]);
        } catch (\Exception $e) {
            Log::error('Error updating glueing material: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating glueing material'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $glueingMaterial = GlueingMaterial::find($id);

            if ($glueingMaterial) {
                $glueingMaterial->delete();
                return response()->json(['success' => true, 'message' => 'Glueing material berhasil dihapus']);
            } else {
                return response()->json(['success' => false, 'message' => 'Glueing material tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@destroy: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting glueing material'], 500);
        }
    }

    public function vueIndex()
    {
        try {
            $glueingMaterials = GlueingMaterial::orderBy('code', 'asc')->get();
            
            if ($glueingMaterials->isEmpty()) {
                $this->seedData();
                $glueingMaterials = GlueingMaterial::orderBy('code', 'asc')->get();
            }
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/GlueingMaterial', [
                'glueingMaterials' => $glueingMaterials,
                'header' => 'Define Glueing Material'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@vueIndex: ' . $e->getMessage());
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/GlueingMaterial', [
                'glueingMaterials' => [],
                'header' => 'Define Glueing Material',
                'error' => 'Error displaying glueing material data'
            ]);
        }
    }

    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-glueing-material', [
                'header' => 'View & Print Glueing Materials'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-glueing-material', [
                'header' => 'View & Print Glueing Materials',
                'error' => 'Failed to load glueing material data for printing'
            ]);
        }
    }

    private function seedData()
    {
        try {
            $glueingMaterials = [
                ['code' => '001', 'name' => 'PVAC', 'description' => 'Polyvinyl Acetate Glue', 'is_active' => true],
                ['code' => '002', 'name' => 'STARCH', 'description' => 'Starch Based Glue', 'is_active' => true],
            ];

            foreach ($glueingMaterials as $material) {
                if (!GlueingMaterial::where('code', $material['code'])->exists()) {
                    GlueingMaterial::create($material);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding glueing material data: ' . $e->getMessage());
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Glueing material seed data created successfully']);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@seed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to seed glueing material data'], 500);
        }
    }
}
