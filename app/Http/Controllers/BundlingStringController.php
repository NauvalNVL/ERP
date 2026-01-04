<?php

namespace App\Http\Controllers;

use App\Models\BundlingString;
use Database\Seeders\BundlingStringSeeder;
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
            $query = BundlingString::query()->orderBy('code', 'asc');

            $allStatus = $request->boolean('all_status') || $request->boolean('include_obsolete');
            if (!$allStatus) {
                $query->where(function ($q) {
                    $q->where('status', 'Act')
                        ->orWhere('is_active', true);
                });
            }

            $bundlingStrings = $query->get();
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
                'name' => 'required|max:255',
                'is_active' => 'boolean',
                'status' => 'nullable|string|max:3',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());

                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $data = [
                'code' => trim($request->code),
                'name' => trim($request->name),
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

            $bundlingString = BundlingString::create($data);

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
                'name' => 'required|string|max:255',
                'is_active' => 'boolean',
                'status' => 'nullable|string|max:3',
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

            $status = $request->input('status');
            if ($status === null || $status === '') {
                $status = $bundlingString->status ?? ($bundlingString->is_active ? 'Act' : 'Obs');
            }

            if ($request->has('is_active')) {
                $isActive = (bool) $request->boolean('is_active');
            } else {
                $isActive = $status === 'Act';
            }

            $bundlingString->update([
                'name' => trim($request->name),
                'status' => $status,
                'is_active' => $isActive,
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
                $bundlingString->status = 'Obs';
                $bundlingString->is_active = false;
                $bundlingString->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Bundling string berhasil dihapus (marked as obsolete)'
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
     * Display a listing of the resource with Vue.
     */
    public function vueIndex()
    {
        try {
            $bundlingStrings = BundlingString::orderBy('code', 'asc')->get();

            // If no data exists, seed sample data
            if ($bundlingStrings->isEmpty()) {
                $this->seedData();
                $bundlingStrings = BundlingString::orderBy('code', 'asc')->get();
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/BundlingString', [
                'bundlingStrings' => $bundlingStrings,
                'header' => 'Define Bundling String'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@vueIndex: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/BundlingString', [
                'bundlingStrings' => [],
                'header' => 'Define Bundling String',
                'error' => 'Error displaying bundling string data'
            ]);
        }
    }

    /**
     * Display the Obsolete/Unobsolete Bundling String status management page (Vue).
     */
    public function vueManageStatus()
    {
        try {
            $bundlingStrings = BundlingString::orderBy('code', 'asc')->get();

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteBundlingString', [
                'bundlingStrings' => $bundlingStrings,
                'header' => 'Manage Bundling String Status',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteBundlingString', [
                'bundlingStrings' => [],
                'header' => 'Manage Bundling String Status',
                'error' => 'Error displaying bundling string data',
            ]);
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
     * Toggle bundling string status (Act/Obs) via API.
     */
    public function toggleStatus(Request $request, $code)
    {
        try {
            $bundlingString = BundlingString::where('code', $code)->first();

            if (!$bundlingString) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bundling string tidak ditemukan',
                ], 404);
            }

            $status = $request->input('status');

            if (!in_array($status, ['Act', 'Obs'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid',
                ], 422);
            }

            $bundlingString->status = $status;
            $bundlingString->is_active = $status === 'Act';
            $bundlingString->save();

            return response()->json([
                'success' => true,
                'message' => 'Status bundling string berhasil diperbarui',
                'data' => $bundlingString,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BundlingStringController@toggleStatus: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating bundling string status: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function seedData()
    {
        try {
            DB::beginTransaction();

            $seeder = new BundlingStringSeeder();
            $seeder->run();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error seeding bundling strings: ' . $e->getMessage());
        }
    }
}
