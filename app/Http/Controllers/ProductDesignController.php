<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDesign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductDesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->getDesignsJson();
            }

            $productDesigns = ProductDesign::paginate(10);
        return view('sales-management.system-requirement.system-requirement.standard-requirement.productdesign', compact('productDesigns'));
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@index: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Failed to load product designs data'], 500);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.productdesign')
                ->with('error', 'Failed to load product designs data');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('productdesign.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'design_code' => 'required|string|max:10|unique:product_designs,design_code',
            'design_name' => 'required|string|max:255',
            'product_code' => 'required|string|exists:products,product_code',
            'dimension' => 'required|string|max:100',
            'idc' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('productdesign.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ProductDesign::create([
                'design_code' => strtoupper($request->design_code),
                'design_name' => $request->design_name,
                'product_code' => strtoupper($request->product_code),
                'dimension' => $request->dimension,
                'idc' => $request->idc,
            ]);

            return redirect()
                ->route('productdesign.index')
                ->with('success', 'Product design created successfully');
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@store: ' . $e->getMessage());
            return redirect()
                ->route('productdesign.index')
                ->with('error', 'Failed to create product design');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('productdesign.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $productDesign = ProductDesign::where('design_code', $id)->firstOrFail();
            return view('sales-management.system-requirement.system-requirement.standard-requirement.edit-productdesign', compact('productDesign'));
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@edit: ' . $e->getMessage());
            return redirect()->route('productdesign.index')->with('error', 'Product design not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'design_name' => 'required|string|max:255',
            'product_code' => 'required|string|exists:products,product_code',
            'dimension' => 'required|string|max:100',
            'idc' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productDesign = ProductDesign::where('design_code', $id)->firstOrFail();
            
            $productDesign->update([
                'design_name' => $request->design_name,
                'product_code' => strtoupper($request->product_code),
                'dimension' => $request->dimension,
                'idc' => $request->idc,
            ]);

            return redirect()
                ->route('productdesign.index')
                ->with('success', 'Product design updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@update: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to update product design');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $productDesign = ProductDesign::where('design_code', $id)->firstOrFail();
            $productDesign->delete();

            return redirect()
                ->route('productdesign.index')
                ->with('success', 'Product design deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@destroy: ' . $e->getMessage());
            return redirect()
                ->route('productdesign.index')
                ->with('error', 'Failed to delete product design');
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        $productDesigns = ProductDesign::orderBy('design_code')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintproductdesign', compact('productDesigns')); 
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-product-design');
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load product design data for printing'], 500);
        }
    }

    /**
     * Display product designs page using Vue.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/product-design', [
                'header' => 'Product Design Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load product designs data'], 500);
        }
    }

    /**
     * Get product designs as JSON
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDesignsJson()
    {
        try {
            $designs = ProductDesign::select(
                    'pd_code as design_code', 
                    'pd_name as design_name', 
                    'product_code', 
                    'dimension', 
                    'idc'
                )
                ->orderBy('pd_code')
                ->get();
            
            return response()->json($designs);
        } catch (\Exception $e) {
            Log::error('Error fetching product designs: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product designs'], 500);
        }
    }

    /**
     * Store a new product design via API
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'design_code' => 'required|string|max:10|unique:product_designs,pd_code',
                'design_name' => 'required|string|max:255',
                'product_code' => 'required|string|exists:products,product_code',
                'dimension' => 'required|string|max:100',
                'idc' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $productDesign = ProductDesign::create([
                'pd_code' => strtoupper($request->design_code),
                'pd_name' => $request->design_name,
                'product_code' => strtoupper($request->product_code),
                'dimension' => $request->dimension,
                'idc' => $request->idc,
            ]);

            // Transform the newly created record to match the expected format
            $responseData = [
                'design_code' => $productDesign->pd_code,
                'design_name' => $productDesign->pd_name,
                'product_code' => $productDesign->product_code,
                'dimension' => $productDesign->dimension,
                'idc' => $productDesign->idc
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product design created successfully',
                'data' => $responseData
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product design: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product design: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a product design via API
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $productDesign = ProductDesign::where('pd_code', $id)->first();
            
            if (!$productDesign) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product design not found'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'design_name' => 'required|string|max:255',
                'product_code' => 'required|string|exists:products,product_code',
                'dimension' => 'required|string|max:100',
                'idc' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $productDesign->update([
                'pd_name' => $request->design_name,
                'product_code' => strtoupper($request->product_code),
                'dimension' => $request->dimension,
                'idc' => $request->idc,
            ]);

            // Transform the updated record to match the expected format
            $responseData = [
                'design_code' => $productDesign->pd_code,
                'design_name' => $productDesign->pd_name,
                'product_code' => $productDesign->product_code,
                'dimension' => $productDesign->dimension,
                'idc' => $productDesign->idc
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product design updated successfully',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating product design: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product design: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product design via API
     * 
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        try {
            $productDesign = ProductDesign::where('pd_code', $id)->first();
            
            if (!$productDesign) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product design not found'
                ], 404);
            }
            
            $productDesign->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product design deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting product design: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product design: ' . $e->getMessage()
            ], 500);
        }
    }
}
