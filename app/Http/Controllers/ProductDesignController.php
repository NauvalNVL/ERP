<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDesign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class ProductDesignController extends Controller
{
    public function index()
    {
        $productDesigns = ProductDesign::all();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.productdesign', compact('productDesigns'));
    }

    public function create()
    {
        return view('sales-management.system-requirement.system-requirement.standard-requirement.productdesign');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pd_code' => 'required|unique:product_designs,pd_code',
            'pd_name' => 'required',
            'product_code' => 'required',
            'dimension' => 'required',
            'idc' => 'required'
        ]);

        ProductDesign::create($validated);

        return redirect()->route('product-design.index')
            ->with('success', 'Product design created successfully.');
    }

    public function edit($id)
    {
        $design = ProductDesign::findOrFail($id);
        $productDesigns = ProductDesign::all();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.productdesign', compact('design', 'productDesigns'));
    }

    public function update(Request $request, $id)
    {
        $design = ProductDesign::findOrFail($id);
        
        $validated = $request->validate([
            'pd_code' => 'required|unique:product_designs,pd_code,' . $id . ',id',
            'pd_name' => 'required',
            'product_code' => 'required',
            'dimension' => 'required',
            'idc' => 'required'
        ]);

        $design->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product design updated successfully.',
                'data' => $design->fresh()
            ]);
        }

        return redirect()->route('product-design.index')
            ->with('success', 'Product design updated successfully.');
    }

    public function destroy($id)
    {
        $design = ProductDesign::findOrFail($id);
        $design->delete();

        return redirect()->route('product-design.index')
            ->with('success', 'Product design deleted successfully.');
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        $productDesigns = ProductDesign::orderBy('pd_name')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintproductdesign', compact('productDesigns')); 
    }

    /**
     * Load seed data for product designs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadSeedData()
    {
        try {
            Artisan::call('db:seed', ['--class' => 'ProductDesignSeeder']);
            $count = ProductDesign::count();
            
            return response()->json([
                'success' => true,
                'message' => 'Product design data loaded successfully',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading product design data: ' . $e->getMessage()
            ], 500);
        }
    }
}
