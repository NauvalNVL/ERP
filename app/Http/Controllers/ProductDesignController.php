<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDesign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductDesignController extends Controller
{
    public function index()
    {
        $designs = ProductDesign::all();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.productdesign', compact('designs'));
    }

    public function create()
    {
        return view('sales-management.system-requirement.system-requirement.standard-requirement.productdesign');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'design_code' => 'required|unique:product_designs,design_code',
            'design_name' => 'required',
            'product_code' => 'required',
            'description' => 'nullable',
            'status' => 'required|boolean'
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
            'design_code' => 'required|unique:product_designs,design_code,' . $id,
            'design_name' => 'required',
            'product_code' => 'required',
            'description' => 'nullable',
            'status' => 'required|boolean'
        ]);

        $design->update($validated);

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
     * @return \\Illuminate\\View\\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data desain produk, urutkan berdasarkan nama
        $productDesigns = ProductDesign::orderBy('design_name')->get(); 
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintproductdesign', compact('productDesigns')); 
    }
}
