<?php

namespace App\Http\Controllers;

use App\Models\BusinessForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BusinessFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan halaman utama "Define Business Form"
        // Biasanya akan menampilkan form kosong atau form edit jika ada ID yang dilewatkan
        return view('system-manager.system-maintenance.definebusinessform');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect ke index karena form ada di index
        return redirect()->route('business-form.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bf_code' => 'required|string|max:255|unique:business_forms,bf_code',
            'bf_name' => 'required|string|max:255',
            'bf_group' => 'required|string|max:255',
            'bf_iso' => 'nullable|string|max:255',
            'check_by_name' => 'nullable|string|max:255',
            'check_by_title' => 'nullable|string|max:255',
            'approve_by_name' => 'nullable|string|max:255',
            'approve_by_title' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('business-form.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            BusinessForm::create($request->all());
            return redirect()->route('business-form.index')
                        ->with('success', 'Business Form created successfully.');
        } catch (\Exception $e) {
            Log::error("Error creating Business Form: " . $e->getMessage());
            return redirect()->route('business-form.index')
                        ->with('error', 'Failed to create Business Form. Please try again.')
                        ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessForm $businessForm)
    {
        // Menampilkan detail (jika diperlukan), atau redirect ke index dengan data
        // Dalam kasus ini, kita mungkin tidak perlu method show terpisah
        // Atau, kita bisa return JSON jika dibutuhkan
        return response()->json($businessForm);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessForm $businessForm)
    {
        // Data akan dikirim ke view index untuk mengisi form
        return view('system-manager.system-maintenance.definebusinessform', compact('businessForm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessForm $businessForm)
    {
        $validator = Validator::make($request->all(), [
            'bf_code' => 'required|string|max:255|unique:business_forms,bf_code,' . $businessForm->id,
            'bf_name' => 'required|string|max:255',
            'bf_group' => 'required|string|max:255',
            'bf_iso' => 'nullable|string|max:255',
            'check_by_name' => 'nullable|string|max:255',
            'check_by_title' => 'nullable|string|max:255',
            'approve_by_name' => 'nullable|string|max:255',
            'approve_by_title' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('business-form.edit', $businessForm->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $businessForm->update($request->all());
            return redirect()->route('business-form.index')
                        ->with('success', 'Business Form updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error updating Business Form (ID: {$businessForm->id}): " . $e->getMessage());
            return redirect()->route('business-form.edit', $businessForm->id)
                        ->with('error', 'Failed to update Business Form. Please try again.')
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessForm $businessForm)
    {
        try {
            $businessForm->delete();
            return redirect()->route('business-form.index')
                        ->with('success', 'Business Form deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Error deleting Business Form (ID: {$businessForm->id}): " . $e->getMessage());
            return redirect()->route('business-form.index')
                        ->with('error', 'Failed to delete Business Form. Please try again.');
        }
    }

    /**
     * Search for business forms (for modal lookup).
     */
    public function search(Request $request)
    {
        $query = BusinessForm::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('bf_code', 'like', $searchTerm)
                  ->orWhere('bf_name', 'like', $searchTerm)
                  ->orWhere('bf_group', 'like', $searchTerm);
            });
        }

        $businessForms = $query->orderBy('bf_code')->paginate(10); // Paginate results

        if ($request->ajax()) {
            return response()->json($businessForms);
        }

        // Jika bukan AJAX, mungkin redirect atau tampilkan view berbeda (opsional)
        // Seharusnya tidak terpanggil jika request berasal dari modal
        return view('system-manager.system-maintenance.definebusinessform');
    }

    /**
     * Display the view and print page for Business Forms.
     */
    public function viewAndPrint(Request $request)
    {
        $query = BusinessForm::query();

        $search_group = $request->input('search_group');
        $search_code = $request->input('search_code');

        if ($search_group) {
            $query->where('bf_group', 'like', '%' . $search_group . '%');
        }

        if ($search_code) {
            $query->where('bf_code', 'like', '%' . $search_code . '%');
        }

        // Mungkin filter berdasarkan check/approve name/title juga di sini jika diperlukan di masa depan

        $businessForms = $query->orderBy('bf_group')->orderBy('bf_code')->get(); // Ambil semua hasil yang cocok untuk dicetak

        return view('system-manager.system-maintenance.viewandprintbusinessform', compact('businessForms', 'search_group', 'search_code'));
    }

    /**
     * API: Get all business forms
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $businessForms = BusinessForm::orderBy('bf_code')->get();
            return response()->json($businessForms);
        } catch (\Exception $e) {
            Log::error('Error in BusinessFormController@apiIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch business forms'], 500);
        }
    }

    /**
     * API: Get a specific business form
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow($id)
    {
        try {
            $businessForm = BusinessForm::findOrFail($id);
            return response()->json($businessForm);
        } catch (\Exception $e) {
            Log::error('Error in BusinessFormController@apiShow: ' . $e->getMessage());
            return response()->json(['error' => 'Business form not found'], 404);
        }
    }

    /**
     * API: Store a new business form
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bf_code' => 'required|string|max:255|unique:business_forms,bf_code',
            'bf_name' => 'required|string|max:255',
            'bf_group' => 'required|string|max:255',
            'bf_iso' => 'nullable|string|max:255',
            'check_by_name' => 'nullable|string|max:255',
            'check_by_title' => 'nullable|string|max:255',
            'approve_by_name' => 'nullable|string|max:255',
            'approve_by_title' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $businessForm = BusinessForm::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Business form created successfully',
                'data' => $businessForm
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error in BusinessFormController@apiStore: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create business form'], 500);
        }
    }

    /**
     * API: Update a business form
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $businessForm = BusinessForm::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'bf_code' => 'required|string|max:255|unique:business_forms,bf_code,' . $id,
                'bf_name' => 'required|string|max:255',
                'bf_group' => 'required|string|max:255',
                'bf_iso' => 'nullable|string|max:255',
                'check_by_name' => 'nullable|string|max:255',
                'check_by_title' => 'nullable|string|max:255',
                'approve_by_name' => 'nullable|string|max:255',
                'approve_by_title' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $businessForm->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Business form updated successfully',
                'data' => $businessForm
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BusinessFormController@apiUpdate: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update business form'], 500);
        }
    }

    /**
     * API: Delete a business form
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        try {
            $businessForm = BusinessForm::findOrFail($id);
            $businessForm->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Business form deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BusinessFormController@apiDestroy: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete business form'], 500);
        }
    }
}
