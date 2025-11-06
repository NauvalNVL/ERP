<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MachineController extends Controller
{
    /**
     * Display a listing of the machines.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $machines = Machine::orderBy('machine_code', 'asc')->get();
            
            // If no data exists, seed sample data
            if ($machines->isEmpty()) {
                $this->seedData();
                $machines = Machine::orderBy('machine_code', 'asc')->get();
            }
            
            // Transform data to match Vue component expected format
            $machinesTransformed = $machines->map(function($machine) {
                return [
                    'id' => $machine->id,
                    'machine_code' => $machine->machine_code,
                    'machine_name' => $machine->machine_name,
                    'process' => $machine->process,
                    'sub_process' => $machine->sub_process,
                    'resource_type' => $machine->resource_type,
                    'track_capacity' => $machine->track_capacity,
                    'track_option_yz' => $machine->track_option_yz,
                    'track_option_bypass' => $machine->track_option_bypass,
                    'finisher_type' => $machine->finisher_type
                ];
            });
            
            // For debugging
            if ($machines->isEmpty()) {
                Log::info('No machines found in the database');
            } else {
                Log::info('Found ' . $machines->count() . ' machines in the database');
            }
            
            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'machines' => $machinesTransformed
                ]);
            }
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/machine', [
                'machines' => $machinesTransformed,
                'header' => 'Define Machine'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in MachineController@index: ' . $e->getMessage());
            
            // Only return JSON for explicit API requests
            if ($request->is('api/*') || ($request->header('X-Requested-With') === 'XMLHttpRequest' && !$request->header('X-Inertia'))) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error displaying machine data: ' . $e->getMessage()
                ], 500);
            }
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/machine', [
                'machines' => [],
                'header' => 'Define Machine',
                'error' => 'Error displaying machine data'
            ]);
        }
    }

    /**
     * Store a newly created machine in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creating new machine with data:', $request->all());

            $validator = Validator::make($request->all(), [
                'machine_code' => 'required|unique:machine,machine_code|max:20',
                'machine_name' => 'required|max:100',
                'process' => 'nullable|string|max:100',
                'sub_process' => 'nullable|string|max:100',
                'resource_type' => 'nullable|string|max:50',
                'track_capacity' => 'nullable|string|max:20',
                'track_option_yz' => 'boolean',
                'track_option_bypass' => 'boolean',
                'finisher_type' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ], 422);
                }
                
                return back()->withErrors($validator)->withInput();
            }

            $machine = Machine::create([
                'machine_code' => trim($request->machine_code),
                'machine_name' => trim($request->machine_name),
                'process' => $request->process ? trim($request->process) : null,
                'sub_process' => $request->sub_process ? trim($request->sub_process) : null,
                'resource_type' => $request->resource_type ? trim($request->resource_type) : null,
                'track_capacity' => $request->track_capacity,
                'track_option_yz' => $request->track_option_yz ?? false,
                'track_option_bypass' => $request->track_option_bypass ?? false,
                'finisher_type' => $request->finisher_type ? trim($request->finisher_type) : null
            ]);
            
            Log::info('Machine created successfully:', ['machine_code' => $machine->machine_code]);
            
            // Transform back to Vue format for response
            $machineResponse = [
                'id' => $machine->id,
                'machine_code' => $machine->machine_code,
                'machine_name' => $machine->machine_name,
                'process' => $machine->process,
                'sub_process' => $machine->sub_process,
                'resource_type' => $machine->resource_type,
                'track_capacity' => $machine->track_capacity,
                'track_option_yz' => $machine->track_option_yz,
                'track_option_bypass' => $machine->track_option_bypass,
                'finisher_type' => $machine->finisher_type
            ];
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Machine berhasil ditambahkan',
                    'data' => $machineResponse
                ]);
            }

            return redirect()->route('machine.index')->with('success', 'Machine created successfully');
        } catch (\Exception $e) {
            Log::error('Error in MachineController@store: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating machine: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified machine in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Updating machine with ID: ' . $id);
            Log::info('Request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'machine_name' => 'required|string|max:100',
                'process' => 'nullable|string|max:100',
                'sub_process' => 'nullable|string|max:100',
                'resource_type' => 'nullable|string|max:50',
                'track_capacity' => 'nullable|string|max:20',
                'track_option_yz' => 'boolean',
                'track_option_bypass' => 'boolean',
                'finisher_type' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $machine = Machine::find($id);
            
            if (!$machine) {
                Log::warning('Machine not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Machine tidak ditemukan'
                ], 404);
            }

            $machine->update([
                'machine_name' => trim($request->machine_name),
                'process' => $request->process ? trim($request->process) : null,
                'sub_process' => $request->sub_process ? trim($request->sub_process) : null,
                'resource_type' => $request->resource_type ? trim($request->resource_type) : null,
                'track_capacity' => $request->track_capacity,
                'track_option_yz' => $request->track_option_yz ?? false,
                'track_option_bypass' => $request->track_option_bypass ?? false,
                'finisher_type' => $request->finisher_type ? trim($request->finisher_type) : null
            ]);

            Log::info('Machine updated successfully:', ['id' => $id]);
            
            // Transform back to Vue format for response
            $machineResponse = [
                'id' => $machine->id,
                'machine_code' => $machine->machine_code,
                'machine_name' => $machine->machine_name,
                'process' => $machine->process,
                'sub_process' => $machine->sub_process,
                'resource_type' => $machine->resource_type,
                'track_capacity' => $machine->track_capacity,
                'track_option_yz' => $machine->track_option_yz,
                'track_option_bypass' => $machine->track_option_bypass,
                'finisher_type' => $machine->finisher_type
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Machine berhasil diperbarui',
                'data' => $machineResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating machine: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating machine: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified machine from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $machine = Machine::find($id);
            
            if ($machine) {
                $machine->delete();
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Machine berhasil dihapus'
                    ]);
                }
                
                return redirect()->route('machine.index')->with('success', 'Machine berhasil dihapus');
            } else {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Machine tidak ditemukan'
                    ], 404);
                }
                
                return back()->with('error', 'Machine tidak ditemukan');
            }
        } catch (\Exception $e) {
            Log::error('Error in MachineController@destroy: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting machine: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-machine', [
                'header' => 'View & Print Machines'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in MachineController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-machine', [
                'header' => 'View & Print Machines',
                'error' => 'Failed to load machine data for printing'
            ]);
        }
    }

    /**
     * Seed machine data (private method for internal use).
     *
     * @return void
     */
    private function seedData()
    {
        try {
            $machines = [
                [
                    'machine_code' => 'M001',
                    'machine_name' => 'Corrugator Machine 1',
                    'process' => '10 - CORRUGATING',
                    'sub_process' => '10 - PRINTER',
                    'resource_type' => 'I-InHouse',
                    'track_capacity' => 'Y-Yes',
                    'track_option_yz' => true,
                    'track_option_bypass' => false,
                    'finisher_type' => 'Standard'
                ],
                [
                    'machine_code' => 'M002',
                    'machine_name' => 'Corrugator Machine 2',
                    'process' => '10 - CORRUGATING',
                    'sub_process' => '20 - DIECUTTER',
                    'resource_type' => 'I-InHouse',
                    'track_capacity' => 'Y-Yes',
                    'track_option_yz' => false,
                    'track_option_bypass' => true,
                    'finisher_type' => 'Premium'
                ],
                [
                    'machine_code' => 'M003',
                    'machine_name' => 'Flexo Printing Machine 1',
                    'process' => '20 - CONVERTING',
                    'sub_process' => '10 - PRINTER',
                    'resource_type' => 'E-External',
                    'track_capacity' => 'N-No',
                    'track_option_yz' => false,
                    'track_option_bypass' => false,
                    'finisher_type' => 'X-N/Applicable'
                ],
                [
                    'machine_code' => 'M004',
                    'machine_name' => 'Die Cutting Machine 1',
                    'process' => '20 - CONVERTING',
                    'sub_process' => '20 - DIECUTTER',
                    'resource_type' => 'I-InHouse',
                    'track_capacity' => 'Y-Yes',
                    'track_option_yz' => true,
                    'track_option_bypass' => true,
                    'finisher_type' => 'Standard'
                ],
                [
                    'machine_code' => 'M005',
                    'machine_name' => 'Folder Gluer Machine 1',
                    'process' => '30 - WAREHOUSE',
                    'sub_process' => '30 - FINISHER',
                    'resource_type' => 'I-InHouse',
                    'track_capacity' => 'N-No',
                    'track_option_yz' => false,
                    'track_option_bypass' => false,
                    'finisher_type' => 'Premium'
                ]
            ];

            foreach ($machines as $machine) {
                if (!Machine::where('machine_code', $machine['machine_code'])->exists()) {
                    Machine::create($machine);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding machine data: ' . $e->getMessage());
        }
    }

    /**
     * Seed the database with sample machine data (public API method).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        try {
            $this->seedData();
            
            return response()->json([
                'success' => true,
                'message' => 'Machine seed data created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in MachineController@seed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed machine data: ' . $e->getMessage()
            ], 500);
        }
    }
}