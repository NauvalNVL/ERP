<?php

namespace App\Http\Controllers;

use App\Models\PaperSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Database\Seeders\PaperSizeSeeder;

class PaperSizeController extends Controller
{
    public function index()
    {
        try {
            // Jika request adalah AJAX, kembalikan data dalam format JSON
            if (request()->ajax() || request()->wantsJson()) {
                $paperSizes = PaperSize::orderBy('size', 'asc')->get();
                
                if ($paperSizes->isEmpty()) {
                    $this->seedData();
                    $paperSizes = PaperSize::orderBy('size', 'asc')->get();
                }
                
                return response()->json($paperSizes);
            }
            
            $paperSizes = PaperSize::orderBy('size', 'asc')->get();
            
            // If there are no paper sizes in the database, seed them
            if ($paperSizes->isEmpty()) {
                $this->seedData();
                $paperSizes = PaperSize::orderBy('size', 'asc')->get();
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.papersize', compact('paperSizes'));
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@index: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'Failed to load paper size data'], 500);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.papersize', ['paperSizes' => collect([])])
                ->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'size' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    Rule::unique('paper_sizes', 'size')
                ]
            ], [
                'size.required' => 'Ukuran kertas harus diisi',
                'size.numeric' => 'Ukuran kertas harus berupa angka',
                'size.min' => 'Ukuran kertas minimal 0.01',
                'size.unique' => 'Ukuran kertas ini sudah terdaftar'
            ]);

            // Hitung inches secara manual untuk memastikan data konsisten
            $inches = PaperSize::convertToInches($validated['size']);
            
            $createdBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            PaperSize::create([
                'size' => $validated['size'],
                'inches' => $inches,
                'unit' => 'Millimeter',
                'created_by' => $createdBy,
                'updated_by' => $createdBy
            ]);

            return redirect()->route('paper-size.index')
                ->with('success', 'Ukuran kertas berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan ukuran kertas: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $paperSize = PaperSize::findOrFail($id);
            $paperSizes = PaperSize::orderBy('size', 'asc')->get();
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.papersize', compact('paperSize', 'paperSizes'));
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@edit: ' . $e->getMessage());
            return redirect()->route('paper-size.index')
                ->with('error', 'Data ukuran kertas tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $paperSize = PaperSize::findOrFail($id);
            
            $validated = $request->validate([
                'size' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    Rule::unique('paper_sizes', 'size')->ignore($id)
                ]
            ], [
                'size.required' => 'Ukuran kertas harus diisi',
                'size.numeric' => 'Ukuran kertas harus berupa angka',
                'size.min' => 'Ukuran kertas minimal 0.01',
                'size.unique' => 'Ukuran kertas ini sudah terdaftar'
            ]);

            // Hitung inches secara manual untuk memastikan data konsisten
            $inches = PaperSize::convertToInches($validated['size']);
            
            $updatedBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            $paperSize->update([
                'size' => $validated['size'],
                'inches' => $inches,
                'updated_by' => $updatedBy
            ]);

            return redirect()->route('paper-size.index')
                ->with('success', 'Ukuran kertas berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui ukuran kertas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $paperSize = PaperSize::findOrFail($id);
            $paperSize->delete();
            
            return redirect()->route('paper-size.index')
                ->with('success', 'Ukuran kertas berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@destroy: ' . $e->getMessage());
            return redirect()->route('paper-size.index')
                ->with('error', 'Gagal menghapus ukuran kertas: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \\Illuminate\\View\\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data paper size, urutkan berdasarkan size
        $paperSizes = PaperSize::orderBy('size')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintpapersize', compact('paperSizes')); 
    }
    
    /**
     * Display a listing of the resource using Vue.
     *
     * @return \\Inertia\\Response
     */
    public function vueIndex()
    {
        try {
            // Ambil data paper size
            $paperSizes = PaperSize::orderBy('size', 'asc')->get();
            
            // Konversi data untuk format yang benar di frontend
            $formattedPaperSizes = $paperSizes->map(function($size) {
                return [
                    'id' => $size->id,
                    'size' => $size->size,
                    'inches' => $size->inches,
                    'unit' => $size->unit,
                    'description' => $size->description
                ];
            });
            
            return Inertia::render('sales-management/system-requirement/standard-requirement/paper-size', [
                'header' => 'Paper Size Management',
                'initialPaperSizes' => $formattedPaperSizes
            ]);
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load paper size data'], 500);
        }
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-paper-size');
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load paper size data for printing'], 500);
        }
    }

    /**
     * Return all paper sizes as JSON for the API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $paperSizes = PaperSize::orderBy('size', 'asc')->get();
            return response()->json($paperSizes);
        } catch (\Exception $e) {
            Log::error('Error in PaperSizeController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load paper size data'], 500);
        }
    }
    
    /**
     * Update the paper size via API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $paperSize = PaperSize::findOrFail($id);
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'size' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    Rule::unique('paper_sizes', 'size')->ignore($id)
                ],
                'inches' => 'required|numeric|min:0.01',
                'description' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            $updatedBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            $paperSize->update([
                'size' => $request->size,
                'inches' => $request->inches,
                'description' => $request->description,
                'updated_by' => $updatedBy
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paper size updated successfully',
                'data' => $paperSize
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating paper size: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update paper size: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a new paper size via API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'size' => 'required|numeric|min:0.01|unique:paper_sizes,size',
                'inches' => 'required|numeric|min:0.01',
                'description' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            $createdBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            $paperSize = PaperSize::create([
                'size' => $request->size,
                'inches' => $request->inches,
                'description' => $request->description,
                'unit' => $request->unit ?? 'mm',
                'created_by' => $createdBy,
                'updated_by' => $createdBy
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paper size created successfully',
                'data' => $paperSize
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating paper size: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create paper size: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a paper size via API.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        try {
            $paperSize = PaperSize::findOrFail($id);
            
            // Store info before deletion for the response
            $sizeInfo = [
                'id' => $paperSize->id,
                'size' => $paperSize->size,
                'description' => $paperSize->description
            ];
            
            $paperSize->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Paper size deleted successfully',
                'data' => $sizeInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting paper size: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete paper size: ' . $e->getMessage()
            ], 500);
        }
    }

    private function seedData()
    {
        try {
            $seeder = new PaperSizeSeeder();
            $seeder->run();
        } catch (\Exception $e) {
            Log::error('Error seeding paper size data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Paper size seed data created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating paper size seed data: ' . $e->getMessage()
            ], 500);
        }
    }
}
