<?php

namespace App\Http\Controllers;

use App\Models\PaperQuality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PaperQualityController extends Controller
{
    public function index()
    {
        try {
            $paperQualities = PaperQuality::orderBy('paper_quality', 'asc')->get();
            return view('system-requirement.paperquality', compact('paperQualities'));
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@index: ' . $e->getMessage());
            return view('system-requirement.paperquality', ['paperQualities' => collect([])])
                ->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    public function create()
    {
        return view('system-requirement.paperquality');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'paper_quality' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('paper_qualities', 'paper_quality')
                ],
                'paper_name' => 'required|string|max:50',
                'weight_kg_m' => 'nullable|numeric|between:0,9.9999',
                'commercial_code' => 'nullable|string|max:10',
                'wet_end_code' => 'nullable|string|max:10',
                'decc_code' => 'nullable|string|max:10',
                'status' => 'nullable|string|max:3',
                'flute' => 'nullable|string|max:5',
                'db' => 'nullable|string|max:5',
                'b' => 'nullable|string|max:5',
                'il' => 'nullable|string|max:5',
                'a_c_e' => 'nullable|string|max:5',
                '2l' => 'nullable|string|max:5',
                'is_active' => 'required|boolean'
            ], [
                'paper_quality.required' => 'Kode kualitas kertas harus diisi',
                'paper_quality.unique' => 'Kode kualitas kertas ini sudah terdaftar',
                'paper_name.required' => 'Nama kualitas kertas harus diisi'
            ]);
            
            $createdBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            // Set status if not provided
            if (!isset($validated['status'])) {
                $validated['status'] = $validated['is_active'] ? 'Act' : 'Obs';
            }
            
            $paperQuality = PaperQuality::create([
                'paper_quality' => $validated['paper_quality'],
                'paper_name' => $validated['paper_name'],
                'weight_kg_m' => $validated['weight_kg_m'] ?? null,
                'commercial_code' => $validated['commercial_code'] ?? null,
                'wet_end_code' => $validated['wet_end_code'] ?? null,
                'decc_code' => $validated['decc_code'] ?? null,
                'status' => $validated['status'],
                'flute' => $validated['flute'] ?? null,
                'db' => $validated['db'] ?? null,
                'b' => $validated['b'] ?? null,
                'il' => $validated['il'] ?? null,
                'a_c_e' => $validated['a_c_e'] ?? null,
                '2l' => $validated['2l'] ?? null,
                'is_active' => $validated['is_active'],
                'created_by' => $createdBy,
                'updated_by' => $createdBy
            ]);

            return redirect()->route('paper-quality.index')
                ->with('success', 'Kualitas kertas berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan kualitas kertas: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $paperQuality = PaperQuality::findOrFail($id);
            $paperQualities = PaperQuality::orderBy('paper_quality', 'asc')->get();
            
            return view('system-requirement.paperquality', compact('paperQuality', 'paperQualities'));
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@edit: ' . $e->getMessage());
            return redirect()->route('paper-quality.index')
                ->with('error', 'Data kualitas kertas tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $paperQuality = PaperQuality::findOrFail($id);
            
            $validated = $request->validate([
                'paper_quality' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('paper_qualities', 'paper_quality')->ignore($id)
                ],
                'paper_name' => 'required|string|max:50',
                'weight_kg_m' => 'nullable|numeric|between:0,9.9999',
                'commercial_code' => 'nullable|string|max:10',
                'wet_end_code' => 'nullable|string|max:10',
                'decc_code' => 'nullable|string|max:10',
                'status' => 'nullable|string|max:3',
                'flute' => 'nullable|string|max:5',
                'db' => 'nullable|string|max:5',
                'b' => 'nullable|string|max:5',
                'il' => 'nullable|string|max:5',
                'a_c_e' => 'nullable|string|max:5',
                '2l' => 'nullable|string|max:5',
                'is_active' => 'required|boolean'
            ], [
                'paper_quality.required' => 'Kode kualitas kertas harus diisi',
                'paper_quality.unique' => 'Kode kualitas kertas ini sudah terdaftar',
                'paper_name.required' => 'Nama kualitas kertas harus diisi'
            ]);
            
            $updatedBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            // Set status if not provided
            if (!isset($validated['status'])) {
                $validated['status'] = $validated['is_active'] ? 'Act' : 'Obs';
            }
            
            $paperQuality->update([
                'paper_quality' => $validated['paper_quality'],
                'paper_name' => $validated['paper_name'],
                'weight_kg_m' => $validated['weight_kg_m'] ?? null,
                'commercial_code' => $validated['commercial_code'] ?? null,
                'wet_end_code' => $validated['wet_end_code'] ?? null,
                'decc_code' => $validated['decc_code'] ?? null,
                'status' => $validated['status'],
                'flute' => $validated['flute'] ?? null,
                'db' => $validated['db'] ?? null,
                'b' => $validated['b'] ?? null,
                'il' => $validated['il'] ?? null,
                'a_c_e' => $validated['a_c_e'] ?? null,
                '2l' => $validated['2l'] ?? null,
                'is_active' => $validated['is_active'],
                'updated_by' => $updatedBy
            ]);

            return redirect()->route('paper-quality.index')
                ->with('success', 'Kualitas kertas berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kualitas kertas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $paperQuality = PaperQuality::findOrFail($id);
            $paperQuality->delete();

            return redirect()->route('paper-quality.index')
                ->with('success', 'Kualitas kertas berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus kualitas kertas: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $paperQuality = PaperQuality::findOrFail($id);
            $paperQuality->is_active = !$paperQuality->is_active;
            $paperQuality->status = $paperQuality->is_active ? 'Act' : 'Obs';
            $paperQuality->updated_by = Auth::check() ? Auth::user()->user_id : 'system';
            $paperQuality->save();

            $status = $paperQuality->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('paper-quality.index')
                ->with('success', "Kualitas kertas berhasil $status");
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@toggleStatus: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengubah status kualitas kertas: ' . $e->getMessage());
        }
    }

    public function viewAndPrint()
    {
        try {
            $paperQualities = PaperQuality::orderBy('paper_quality', 'asc')->get();
            return view('system-requirement.viewandprintpaperquality', compact('paperQualities'));
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@viewAndPrint: ' . $e->getMessage());
            return view('system-requirement.viewandprintpaperquality', ['paperQualities' => collect([])])
                ->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }
}
