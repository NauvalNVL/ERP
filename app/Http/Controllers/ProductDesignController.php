<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDesign;
use Illuminate\Support\Facades\DB;

class ProductDesignController extends Controller
{
    public function index()
    {
        $designs = ProductDesign::all();
        return view('product-design.index', compact('designs'));
    }

    public function create()
    {
        return view('product-design.create');
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
        return view('system-requirement.productdesign', compact('design'));
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
}
