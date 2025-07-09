<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmReceiveDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MmReceiveDestinationController extends Controller
{
    /**
     * Display the receive destination management interface.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/ReceiveDestination');
    }
    
    /**
     * Display a view for printing the receive destinations.
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/standard-setup/ViewPrintReceiveDestination');
    }
    
    /**
     * Get all receive destinations for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReceiveDestinations()
    {
        $destinations = MmReceiveDestination::orderBy('code')->get();
        return response()->json($destinations);
    }
    
    /**
     * Show a specific receive destination for API
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $destination = MmReceiveDestination::findOrFail($code);
        return response()->json($destination);
    }
    
    /**
     * Store a new receive destination
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:mm_receive_destinations,code',
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:100',
            'tel' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $destination = MmReceiveDestination::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'tel' => $request->tel,
            'fax' => $request->fax,
        ]);
        
        return response()->json($destination, 201);
    }
    
    /**
     * Update an existing receive destination
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:100',
            'tel' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $destination = MmReceiveDestination::findOrFail($code);
        $destination->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'tel' => $request->tel,
            'fax' => $request->fax,
        ]);
        
        return response()->json($destination, 200);
    }
    
    /**
     * Delete a receive destination
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        $destination = MmReceiveDestination::findOrFail($code);
        $destination->delete();
        
        return response()->json([
            'message' => 'Receive destination deleted successfully',
        ], 200);
    }
    
    /**
     * Seed receive destinations with sample data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed()
    {
        $sampleDestinations = [
            [
                'code' => 'BP', 
                'name' => 'GUDANG BAHAN PEMBANTU',
                'address' => 'MBI CIKANDE',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'IMPORT', 
                'name' => 'SPARE PARTS WAREHOUSE',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'KIM', 
                'name' => 'KARYA INDAH MULTIGUNA',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'MBI', 
                'name' => 'MBI CIKANDE',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'MBIDFS', 
                'name' => 'MBI CIKANDE OFFSET',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'MMTDFS', 
                'name' => 'MMT DFS',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'SPP', 
                'name' => 'GUDANG SPAREPART',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'TRK', 
                'name' => 'GUDANG SPAREPART TRAILER',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
        ];
        
        foreach ($sampleDestinations as $data) {
            MmReceiveDestination::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
        
        return response()->json([
            'message' => 'Sample receive destinations seeded successfully',
        ], 200);
    }

    public function getAll()
    {
        return response()->json(MmReceiveDestination::all());
    }
} 