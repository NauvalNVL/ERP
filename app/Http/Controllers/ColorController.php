<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\ColorGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    /**
     * Display a listing of the colors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $colors = DB::table('colors')
                    ->where('color_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('color_name', 'like', '%' . $searchTerm . '%')
                    ->orderBy('color_id', 'asc')
                    ->get();
            } else {
                // Get all colors matching the seed data structure
                $colors = DB::table('colors')
                    ->select([
                        'color_id',
                        'color_name',
                        'origin',
                        'color_group_id',
                        'cg_type'
                    ])
                    ->orderBy('color_id', 'asc')
                    ->get();
            }
            
            $colorGroups = ColorGroup::all();
            
            // For debugging
            if ($colors->isEmpty()) {
                Log::info('No colors found in the database');
            } else {
                Log::info('Found ' . $colors->count() . ' colors in the database');
            }
            
            return view('system-requirement.color', [
                'colors' => $colors,
                'colorGroups' => $colorGroups
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@index: ' . $e->getMessage());
            return view('system-requirement.color', [
                'colors' => [],
                'colorGroups' => [],
                'error' => 'Terjadi kesalahan dalam menampilkan data warna: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new color.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colorGroups = ColorGroup::all();
        return view('system-requirement.color', compact('colorGroups'));
    }

    /**
     * Store a newly created color in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'color_id' => 'required|unique:colors|max:10',
                'color_name' => 'required|max:50',
                'color_group_id' => 'required|exists:color_groups,id',
                'origin' => 'nullable|max:100'
            ]);

            Color::create($validatedData);

            return redirect()->route('colors.index')->with('success', 'Color created successfully');
        } catch (\Exception $e) {
            Log::error('Error in ColorController@store: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified color.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $color = DB::table('colors')->where('color_id', $id)->first();
            
            if (!$color) {
                return redirect()->route('color.index')->with('error', 'Warna tidak ditemukan');
            }
            
            return view('system-requirement.color-edit', ['color' => $color]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@edit: ' . $e->getMessage());
            return redirect()->route('color.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified color in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'color_name' => 'required|string|max:100',
                'origin' => 'required|string|max:2',
                'color_group_id' => 'required|string|max:5',
                'cg_type' => 'nullable|string|max:50',
            ]);

            $affected = DB::table('colors')
                ->where('color_id', $id)
                ->update([
                    'color_name' => $validated['color_name'],
                    'origin' => $validated['origin'],
                    'color_group_id' => $validated['color_group_id'],
                    'cg_type' => $validated['cg_type'] ?? null,
                    'updated_at' => now()
                ]);

            if ($affected) {
                return redirect()->route('color.index')->with('success', 'Warna berhasil diperbarui');
            } else {
                return back()->with('error', 'Tidak ada perubahan data');
            }
        } catch (\Exception $e) {
            Log::error('Error in ColorController@update: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified color from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $affected = DB::table('colors')->where('color_id', $id)->delete();
            
            if ($affected) {
                return redirect()->route('color.index')->with('success', 'Warna berhasil dihapus');
            } else {
                return back()->with('error', 'Warna tidak ditemukan');
            }
        } catch (\Exception $e) {
            Log::error('Error in ColorController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the colors for Vue component.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            // Get all colors matching the seed data structure
            $colors = DB::table('colors')
                ->select([
                    'color_id',
                    'color_name',
                    'origin',
                    'color_group_id',
                    'cg_type'
                ])
                ->orderBy('color_id', 'asc')
                ->get();
            
            $colorGroups = ColorGroup::all();
            
            return Inertia::render('system-requirement/color', [
                'colors' => $colors,
                'colorGroups' => $colorGroups
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ColorController@vueIndex: ' . $e->getMessage());
            return Inertia::render('system-requirement/color', [
                'colors' => [],
                'colorGroups' => [],
                'error' => 'Terjadi kesalahan dalam menampilkan data warna: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data warna, urutkan berdasarkan nama
        // Eager load ColorGroup
        $colors = Color::with('colorGroup')->orderBy('color_name')->get(); 
        return view('system-requirement.viewandprintcolor', compact('colors')); 
    }
}
