<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductGroupController extends Controller
{
    public function index()
    {
        $productGroups = ProductGroup::all();
        return view('system-requirement.productgroup', compact('productGroups'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_group_id' => 'required|string|max:10|unique:product_groups',
            'product_group_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProductGroup::create([
            'product_group_id' => $request->product_group_id,
            'product_group_name' => $request->product_group_name,
            'is_active' => true
        ]);

        return redirect()->route('product-group.index')->with('success', 'Grup Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $productGroup = ProductGroup::where('product_group_id', $id)->firstOrFail();
        $productGroups = ProductGroup::all();
        return view('system-requirement.productgroup', compact('productGroup', 'productGroups'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_group_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productGroup = ProductGroup::where('product_group_id', $id)->firstOrFail();
        $productGroup->update([
            'product_group_name' => $request->product_group_name,
        ]);

        return redirect()->route('product-group.index')->with('success', 'Grup Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $productGroup = ProductGroup::where('product_group_id', $id)->firstOrFail();
        $productGroup->delete();

        return redirect()->route('product-group.index')->with('success', 'Grup Produk berhasil dihapus');
    }
} 