<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $productGroups = ProductGroup::all();
        $categories = $this->getCategories();
        
        return view('sales-management.system-requirement.system-requirement.standard-requirement.product', compact('products', 'productGroups', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|string|max:10|unique:products',
            'description' => 'required|string|max:255',
            'product_group_id' => 'required|string|exists:product_groups,product_group_id',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Product::create([
            'product_code' => $request->product_code,
            'description' => $request->description,
            'product_group_id' => $request->product_group_id,
            'category' => $request->category,
            'is_active' => true
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::where('product_code', $id)->firstOrFail();
        $products = Product::all();
        $productGroups = ProductGroup::all();
        $categories = $this->getCategories();

        return view('sales-management.system-requirement.system-requirement.standard-requirement.product', compact('product', 'products', 'productGroups', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'product_group_id' => 'required|string|exists:product_groups,product_group_id',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = Product::where('product_code', $id)->firstOrFail();
        $product->update([
            'description' => $request->description,
            'product_group_id' => $request->product_group_id,
            'category' => $request->category,
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $product = Product::where('product_code', $id)->firstOrFail();
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::where('product_code', $id)->firstOrFail();
        return response()->json($product);
    }

    private function getCategories()
    {
        return [
            '1-Corrugated Carton Box',
            '2-Single Facer Roll',
            '3-Single Facer Roll/KG',
            '4-Single Facer Sheet',
            '5-Corrugated Sheet Board/Piece',
            '6-Corrugated Sheet Board/M2',
            '7-Other Packaging Products'
        ];
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \\Illuminate\\View\\View
     */
    public function viewAndPrint()
    {
        // Ambil semua data produk, urutkan berdasarkan deskripsi
        // Eager load ProductGroup jika ingin menampilkan nama grup di view
        $products = Product::with('productGroup')->orderBy('description')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintproduct', compact('products')); 
    }
}
