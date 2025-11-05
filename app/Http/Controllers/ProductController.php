<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Database\Seeders\ProductSeeder;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        // If there are no products in the database, seed them
        if ($products->isEmpty()) {
            $this->seedData();
            $products = Product::all();
        }
        
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

    public function vueIndex()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/product', [
                'header' => 'Product Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load product data'], 500);
        }
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-product');
        } catch (\Exception $e) {
            Log::error('Error in ProductController@vueViewAndPrint: ' . $e->getMessage());
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-product', [
                'error' => 'Failed to load product data for printing: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get product categories as JSON
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriesJson()
    {
        try {
            // Get unique categories from products table
            $categories = DB::table('products')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->get();
            
            // Transform to format expected by Vue component
            $transformedCategories = $categories->map(function($category) {
                return [
                    'id' => $category->category,
                    'name' => $category->category,
                    'category_code' => $category->category
                ];
            });
            
            return response()->json($transformedCategories);
        } catch (\Exception $e) {
            Log::error('Error fetching product categories: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product categories'], 500);
        }
    }

    /**
     * Get products as JSON
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductsJson()
    {
        try {
            $products = Product::select(
                    'id',
                    'product_code',
                    'description',
                    'category',
                    'unit',
                    'product_group_id',
                    'is_active',
                    'created_at',
                    'updated_at'
                )
                ->orderBy('product_code')
                ->get();
            
            // Transform data to match Vue component expectations
            $transformedProducts = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'product_code' => $product->product_code,
                    'name' => $product->description, // Vue component uses 'name' for what is 'description' in DB
                    'description' => $product->description,
                    'category' => $product->category,
                    'category_id' => $product->category,
                    'category_code' => $product->category,
                    'unit' => $product->unit ?? '',
                    'product_group_id' => $product->product_group_id,
                    'is_active' => $product->is_active ? true : false,
                    'created_at' => $product->created_at ? $product->created_at->toISOString() : null,
                    'updated_at' => $product->updated_at ? $product->updated_at->toISOString() : null
                ];
            });
            
            return response()->json($transformedProducts);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    /**
     * Store a new product via API
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_code' => 'required|string|max:10|unique:products,product_code',
                'name' => 'required|string|max:255', // Vue sends 'name' but DB uses 'description'
                'category_id' => 'required|string', // Vue sends 'category_id' but DB uses 'category'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $product = Product::create([
                'product_code' => strtoupper($request->product_code),
                'description' => $request->name, // Map Vue 'name' to DB 'description'
                'category' => $request->category_id, // Map Vue 'category_id' to DB 'category'
                'unit' => $request->unit ?? '',
                'product_group_id' => $request->product_group_id ?? '',
                'is_active' => true
            ]);

            // Transform for response
            $responseData = [
                'id' => $product->id,
                'product_code' => $product->product_code,
                'name' => $product->description,
                'description' => $product->description,
                'category_id' => $product->category,
                'category_code' => $product->category,
                'unit' => $product->unit ?? '',
                'product_group_id' => $product->product_group_id
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $responseData
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a product via API
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            // Use where('id') instead of find() because primary key is 'product_code', not 'id'
            $product = Product::where('id', $id)->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255', // Vue sends 'name' but DB uses 'description'
                'category_id' => 'required|string', // Vue sends 'category_id' but DB uses 'category'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $product->update([
                'description' => $request->name, // Map Vue 'name' to DB 'description'
                'category' => $request->category_id, // Map Vue 'category_id' to DB 'category'
                'unit' => $request->unit ?? $product->unit,
                'product_group_id' => $request->product_group_id ?? $product->product_group_id
            ]);

            // Transform for response
            $responseData = [
                'id' => $product->id,
                'product_code' => $product->product_code,
                'name' => $product->description,
                'description' => $product->description,
                'category_id' => $product->category,
                'category_code' => $product->category,
                'unit' => $product->unit ?? '',
                'product_group_id' => $product->product_group_id
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product via API
     * 
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        try {
            $product = Product::where('product_code', $id)->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    private function seedData()
    {
        try {
            $seeder = new ProductSeeder();
            $seeder->run();
        } catch (\Exception $e) {
            Log::error('Error seeding product data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Product seed data created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product seed data: ' . $e->getMessage()
            ], 500);
        }
    }
}
