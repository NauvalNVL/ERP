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
            $paperQualities = PaperQuality::orderBy('code', 'asc')->get();
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
                'code' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('paper_qualities', 'code')
                ],
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:255',
                'gsm' => 'nullable|integer|min:0',
                'paper_type' => 'nullable|string|max:50',
                'is_active' => 'required|boolean'
            ], [
                'code.required' => 'Kode kualitas kertas harus diisi',
                'code.unique' => 'Kode kualitas kertas ini sudah terdaftar',
                'name.required' => 'Nama kualitas kertas harus diisi'
            ]);
            
            $createdBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            $paperQuality = PaperQuality::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'gsm' => $validated['gsm'] ?? null,
                'paper_type' => $validated['paper_type'] ?? null,
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
            $paperQualities = PaperQuality::orderBy('code', 'asc')->get();
            
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
                'code' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('paper_qualities', 'code')->ignore($id)
                ],
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:255',
                'gsm' => 'nullable|integer|min:0',
                'paper_type' => 'nullable|string|max:50',
                'is_active' => 'required|boolean'
            ], [
                'code.required' => 'Kode kualitas kertas harus diisi',
                'code.unique' => 'Kode kualitas kertas ini sudah terdaftar',
                'name.required' => 'Nama kualitas kertas harus diisi'
            ]);
            
            $updatedBy = Auth::check() ? Auth::user()->user_id : 'system';
            
            $paperQuality->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'gsm' => $validated['gsm'] ?? null,
                'paper_type' => $validated['paper_type'] ?? null,
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
            $paperQualities = PaperQuality::orderBy('code', 'asc')->get();
            return view('system-requirement.viewandprintpaperquality', compact('paperQualities'));
        } catch (\Exception $e) {
            Log::error('Error in PaperQualityController@viewAndPrint: ' . $e->getMessage());
            return view('system-requirement.viewandprintpaperquality', ['paperQualities' => collect([])])
                ->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }
}
