<?php

namespace App\Http\Controllers;

use App\Models\PapperSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PapperSizeController extends Controller
{
    public function index()
    {
        try {
            $papperSizes = PapperSize::orderBy('size', 'asc')->get();
            return view('system-requirement.pappersize', compact('papperSizes'));
        } catch (\Exception $e) {
            Log::error('Error in PapperSizeController@index: ' . $e->getMessage());
            return view('system-requirement.pappersize', ['papperSizes' => collect([])])
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
            $inches = PapperSize::convertToInches($validated['size']);
            
            $createdBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            PapperSize::create([
                'size' => $validated['size'],
                'inches' => $inches,
                'unit' => 'Millimeter',
                'created_by' => $createdBy,
                'updated_by' => $createdBy
            ]);

            return redirect()->route('paper-size.index')
                ->with('success', 'Ukuran kertas berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in PapperSizeController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan ukuran kertas: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $papperSize = PapperSize::findOrFail($id);
            $papperSizes = PapperSize::orderBy('size', 'asc')->get();
            
            return view('system-requirement.pappersize', compact('papperSize', 'papperSizes'));
        } catch (\Exception $e) {
            Log::error('Error in PapperSizeController@edit: ' . $e->getMessage());
            return redirect()->route('paper-size.index')
                ->with('error', 'Data ukuran kertas tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $papperSize = PapperSize::findOrFail($id);
            
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
            $inches = PapperSize::convertToInches($validated['size']);
            
            $updatedBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            $papperSize->update([
                'size' => $validated['size'],
                'inches' => $inches,
                'updated_by' => $updatedBy
            ]);

            return redirect()->route('paper-size.index')
                ->with('success', 'Ukuran kertas berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in PapperSizeController@update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui ukuran kertas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $papperSize = PapperSize::findOrFail($id);
            $papperSize->delete();
            
            return redirect()->route('paper-size.index')
                ->with('success', 'Ukuran kertas berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in PapperSizeController@destroy: ' . $e->getMessage());
            return redirect()->route('paper-size.index')
                ->with('error', 'Gagal menghapus ukuran kertas: ' . $e->getMessage());
        }
    }
}
