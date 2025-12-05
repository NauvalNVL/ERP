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

    public function vueManageStatus()
    {
        try {
            $glueingMaterials = GlueingMaterial::orderBy('code', 'asc')->get();

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteGlueingMaterial', [
                'glueingMaterials' => $glueingMaterials,
                'header' => 'Manage Glueing Material Status',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteGlueingMaterial', [
                'glueingMaterials' => [],
                'header' => 'Manage Glueing Material Status',
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
                'description' => 'nullable|max:255',
                'is_active' => 'boolean',
                'status' => 'nullable|string|max:3',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $data = [
                'code' => trim($request->code),
                'name' => trim($request->name),
                'description' => $request->description ? trim($request->description) : null,
            ];

            $status = $request->input('status');
            if ($status === null || $status === '') {
                $status = 'Act';
            }
            $data['status'] = $status;

            if ($request->has('is_active')) {
                $data['is_active'] = (bool) $request->boolean('is_active');
            } else {
                $data['is_active'] = $status === 'Act';
            }

            $glueingMaterial = GlueingMaterial::create($data);

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
                'description' => 'nullable|string|max:255',
                'is_active' => 'boolean',
                'status' => 'nullable|string|max:3',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $glueingMaterial = GlueingMaterial::find($id);

            if (!$glueingMaterial) {
                return response()->json(['success' => false, 'message' => 'Glueing material tidak ditemukan'], 404);
            }

            $status = $request->input('status');
            if ($status === null || $status === '') {
                $status = $glueingMaterial->status ?? ($glueingMaterial->is_active ? 'Act' : 'Obs');
            }

            if ($request->has('is_active')) {
                $isActive = (bool) $request->boolean('is_active');
            } else {
                $isActive = $status === 'Act';
            }

            $glueingMaterial->update([
                'name' => trim($request->name),
                'description' => $request->description ? trim($request->description) : null,
                'status' => $status,
                'is_active' => $isActive,
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
                $glueingMaterial->status = 'Obs';
                $glueingMaterial->is_active = false;
                $glueingMaterial->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Glueing material berhasil dihapus (marked as obsolete)'
                ]);
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

    public function toggleStatus(Request $request, $code)
    {
        try {
            $glueingMaterial = GlueingMaterial::where('code', $code)->first();

            if (!$glueingMaterial) {
                return response()->json([
                    'success' => false,
                    'message' => 'Glueing material tidak ditemukan',
                ], 404);
            }

            $status = $request->input('status');

            if (!in_array($status, ['Act', 'Obs'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid',
                ], 422);
            }

            $glueingMaterial->status = $status;
            $glueingMaterial->is_active = $status === 'Act';
            $glueingMaterial->save();

            return response()->json([
                'success' => true,
                'message' => 'Status glueing material berhasil diperbarui',
                'data' => $glueingMaterial,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GlueingMaterialController@toggleStatus: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating glueing material status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
