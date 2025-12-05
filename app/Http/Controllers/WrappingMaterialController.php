<?php

namespace App\Http\Controllers;

use App\Models\WrappingMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class WrappingMaterialController extends Controller
{
    public function apiIndex(Request $request)
    {
        try {
            $wrappingMaterials = WrappingMaterial::orderBy('code', 'asc')->get();

            return response()->json($wrappingMaterials);
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Error displaying wrapping material data'], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $wrappingMaterials = WrappingMaterial::orderBy('code', 'asc')->get();

            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json($wrappingMaterials);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/WrappingMaterial', [
                'wrappingMaterials' => $wrappingMaterials,
                'header' => 'Define Wrapping Material'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@index: ' . $e->getMessage());

            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json(['error' => true, 'message' => 'Error displaying wrapping material data'], 500);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/WrappingMaterial', [
                'wrappingMaterials' => [],
                'error' => 'Error displaying wrapping material data'
            ]);
        }
    }

    public function vueManageStatus()
    {
        try {
            $wrappingMaterials = WrappingMaterial::orderBy('code', 'asc')->get();

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteWrappingMaterial', [
                'wrappingMaterials' => $wrappingMaterials,
                'header' => 'Manage Wrapping Material Status',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteWrappingMaterial', [
                'wrappingMaterials' => [],
                'header' => 'Manage Wrapping Material Status',
                'error' => 'Error displaying wrapping material data',
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:wrapping_materials,code|max:50',
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

            $wrappingMaterial = WrappingMaterial::create($data);

            return response()->json(['success' => true, 'message' => 'Wrapping material berhasil ditambahkan', 'data' => $wrappingMaterial]);
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@store: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating wrapping material'], 500);
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

            $wrappingMaterial = WrappingMaterial::find($id);

            if (!$wrappingMaterial) {
                return response()->json(['success' => false, 'message' => 'Wrapping material tidak ditemukan'], 404);
            }

            $status = $request->input('status');
            if ($status === null || $status === '') {
                $status = $wrappingMaterial->status ?? ($wrappingMaterial->is_active ? 'Act' : 'Obs');
            }

            if ($request->has('is_active')) {
                $isActive = (bool) $request->boolean('is_active');
            } else {
                $isActive = $status === 'Act';
            }

            $wrappingMaterial->update([
                'name' => trim($request->name),
                'description' => $request->description ? trim($request->description) : null,
                'status' => $status,
                'is_active' => $isActive,
            ]);

            return response()->json(['success' => true, 'message' => 'Wrapping material berhasil diperbarui', 'data' => $wrappingMaterial]);
        } catch (\Exception $e) {
            Log::error('Error updating wrapping material: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating wrapping material'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $wrappingMaterial = WrappingMaterial::find($id);

            if ($wrappingMaterial) {
                $wrappingMaterial->status = 'Obs';
                $wrappingMaterial->is_active = false;
                $wrappingMaterial->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Wrapping material berhasil dihapus (marked as obsolete)'
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Wrapping material tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@destroy: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting wrapping material'], 500);
        }
    }

    public function vueIndex()
    {
        try {
            $wrappingMaterials = WrappingMaterial::orderBy('code', 'asc')->get();

            return Inertia::render('sales-management/system-requirement/standard-requirement/WrappingMaterial', [
                'wrappingMaterials' => $wrappingMaterials,
                'header' => 'Define Wrapping Material'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@vueIndex: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/WrappingMaterial', [
                'wrappingMaterials' => [],
                'header' => 'Define Wrapping Material',
                'error' => 'Error displaying wrapping material data'
            ]);
        }
    }

    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-wrapping-material', [
                'header' => 'View & Print Wrapping Materials'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-wrapping-material', [
                'header' => 'View & Print Wrapping Materials',
                'error' => 'Failed to load wrapping material data for printing'
            ]);
        }
    }

    public function toggleStatus(Request $request, $code)
    {
        try {
            $wrappingMaterial = WrappingMaterial::where('code', $code)->first();

            if (!$wrappingMaterial) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wrapping material tidak ditemukan',
                ], 404);
            }

            $status = $request->input('status');

            if (!in_array($status, ['Act', 'Obs'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid',
                ], 422);
            }

            $wrappingMaterial->status = $status;
            $wrappingMaterial->is_active = $status === 'Act';
            $wrappingMaterial->save();

            return response()->json([
                'success' => true,
                'message' => 'Status wrapping material berhasil diperbarui',
                'data' => $wrappingMaterial,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in WrappingMaterialController@toggleStatus: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating wrapping material status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
