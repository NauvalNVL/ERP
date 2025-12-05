<?php

namespace App\Http\Controllers;

use App\Models\ReinforcementTape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class ReinforcementTapeController extends Controller
{
    /**
     * API method to get reinforcement tapes data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex(Request $request)
    {
        try {
            $reinforcementTapes = ReinforcementTape::orderBy('code', 'asc')->get();

            return response()->json($reinforcementTapes);
        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@apiIndex: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error displaying reinforcement tape data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the reinforcement tapes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        try {
            $reinforcementTapes = ReinforcementTape::orderBy('code', 'asc')->get();

            // For debugging
            if ($reinforcementTapes->isEmpty()) {
                Log::info('No reinforcement tapes found in the database');
            } else {
                Log::info('Found ' . $reinforcementTapes->count() . ' reinforcement tapes in the database');
            }

            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json($reinforcementTapes);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/ReinforcementTape', [
                'reinforcementTapes' => $reinforcementTapes,
                'header' => 'Define Reinforcement Tape'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@index: ' . $e->getMessage());

            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error displaying reinforcement tape data: ' . $e->getMessage()
                ], 500);
            }

            return Inertia::render('sales-management/system-requirement/standard-requirement/ReinforcementTape', [
                'reinforcementTapes' => [],
                'error' => 'Error displaying reinforcement tape data'
            ]);
        }
    }

    /**
     * Store a newly created reinforcement tape in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creating new reinforcement tape with data:', $request->all());

            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:reinforcement_tapes,code|max:50',
                'name' => 'required|max:255',
                'dry_end_code' => 'nullable|max:1',
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
                'dry_end_code' => $request->dry_end_code ? trim($request->dry_end_code) : null,
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

            $reinforcementTape = ReinforcementTape::create($data);

            Log::info('Reinforcement tape created successfully:', ['code' => $reinforcementTape->code]);

            return response()->json([
                'success' => true,
                'message' => 'Reinforcement tape berhasil ditambahkan',
                'data' => $reinforcementTape
            ]);

        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@store: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error creating reinforcement tape: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified reinforcement tape in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Updating reinforcement tape with ID: ' . $id);
            Log::info('Request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'dry_end_code' => 'nullable|string|max:1',
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

            $reinforcementTape = ReinforcementTape::find($id);

            if (!$reinforcementTape) {
                Log::warning('Reinforcement tape not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Reinforcement tape tidak ditemukan'
                ], 404);
            }

            $status = $request->input('status');
            if ($status === null || $status === '') {
                $status = $reinforcementTape->status ?? ($reinforcementTape->is_active ? 'Act' : 'Obs');
            }

            if ($request->has('is_active')) {
                $isActive = (bool) $request->boolean('is_active');
            } else {
                $isActive = $status === 'Act';
            }

            $reinforcementTape->update([
                'name' => trim($request->name),
                'dry_end_code' => $request->dry_end_code ? trim($request->dry_end_code) : null,
                'status' => $status,
                'is_active' => $isActive,
            ]);

            Log::info('Reinforcement tape updated successfully:', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Reinforcement tape berhasil diperbarui',
                'data' => $reinforcementTape
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating reinforcement tape: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error updating reinforcement tape: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified reinforcement tape from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $reinforcementTape = ReinforcementTape::find($id);

            if ($reinforcementTape) {
                $reinforcementTape->status = 'Obs';
                $reinforcementTape->is_active = false;
                $reinforcementTape->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Reinforcement tape berhasil dihapus (marked as obsolete)'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Reinforcement tape tidak ditemukan'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@destroy: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting reinforcement tape: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource with Vue.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            $reinforcementTapes = ReinforcementTape::orderBy('code', 'asc')->get();
            return Inertia::render('sales-management/system-requirement/standard-requirement/ReinforcementTape', [
                'reinforcementTapes' => $reinforcementTapes,
                'header' => 'Define Reinforcement Tape'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@vueIndex: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/ReinforcementTape', [
                'reinforcementTapes' => [],
                'header' => 'Define Reinforcement Tape',
                'error' => 'Error displaying reinforcement tape data'
            ]);
        }
    }

    /**
     * Display the Obsolete/Unobsolete Reinforcement Tape status management page (Vue)
     */
    public function vueManageStatus()
    {
        try {
            $reinforcementTapes = ReinforcementTape::orderBy('code', 'asc')->get();

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteReinforcementTape', [
                'reinforcementTapes' => $reinforcementTapes,
                'header' => 'Manage Reinforcement Tape Status',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/ObsoleteUnobsoleteReinforcementTape', [
                'reinforcementTapes' => [],
                'header' => 'Manage Reinforcement Tape Status',
                'error' => 'Error displaying reinforcement tape data',
            ]);
        }
    }

    /**
     * Display a listing of the resource for printing with Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-reinforcement-tape', [
                'header' => 'View & Print Reinforcement Tapes'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-reinforcement-tape', [
                'header' => 'View & Print Reinforcement Tapes',
                'error' => 'Failed to load reinforcement tape data for printing'
            ]);
        }
    }

    /**
     * Toggle reinforcement tape status (Act/Obs) via API
     */
    public function toggleStatus(Request $request, $code)
    {
        try {
            $reinforcementTape = ReinforcementTape::where('code', $code)->first();

            if (!$reinforcementTape) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reinforcement tape tidak ditemukan',
                ], 404);
            }

            $status = $request->input('status');

            if (!in_array($status, ['Act', 'Obs'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid',
                ], 422);
            }

            $reinforcementTape->status = $status;
            $reinforcementTape->is_active = $status === 'Act';
            $reinforcementTape->save();

            return response()->json([
                'success' => true,
                'message' => 'Status reinforcement tape berhasil diperbarui',
                'data' => $reinforcementTape,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ReinforcementTapeController@toggleStatus: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating reinforcement tape status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
