<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductGroupController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Always load from database, ordered by product_group_id
            $productGroups = ProductGroup::orderBy('product_group_id')->get();
            
            // If there are no product groups in the database, seed them
            if ($productGroups->isEmpty()) {
                $this->seedData();
                $productGroups = ProductGroup::orderBy('product_group_id')->get();
            }
            
            // If the request wants JSON, return JSON response
            if ($request->wantsJson() || $request->ajax() || $request->is('api/*')) {
                return response()->json($productGroups);
            }
            
            return view('sales-management.system-requirement.system-requirement.standard-requirement.productgroup', compact('productGroups'));
        } catch (\Exception $e) {
            Log::error('Error loading product groups: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading data from database: ' . $e->getMessage()
                ], 500);
            }
            
            // Return view with error message using session flash
            return redirect()->back()->with('error', 'Error loading data from database: ' . $e->getMessage());
        }
    }

    /**
     * API method to store a new product group
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:10|unique:product_groups,product_group_id',
                'name' => 'required|string|max:100',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $productGroup = ProductGroup::create([
                'product_group_id' => $request->code,
                'product_group_name' => $request->name,
                'status' => $request->has('status') ? $request->status : 'Act',
                'is_active' => $request->has('is_active') ? $request->is_active : true
            ]);

            // Transform the data to match the expected format
            $responseData = [
                'id' => $productGroup->id,
                'code' => $productGroup->product_group_id,
                'name' => $productGroup->product_group_name,
                'is_active' => $productGroup->is_active
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product group created successfully',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@apiStore: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating product group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API method to update a product group
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $productGroup = ProductGroup::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $productGroup->update([
                'product_group_name' => $request->name,
                'is_active' => $request->is_active
            ]);

            // Transform the data to match the expected format
            $responseData = [
                'id' => $productGroup->id,
                'code' => $productGroup->product_group_id,
                'name' => $productGroup->product_group_name,
                'is_active' => $productGroup->is_active
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product group updated successfully',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@apiUpdate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating product group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API method to delete a product group
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        try {
            $productGroup = ProductGroup::findOrFail($id);
            $productGroup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product group deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@apiDestroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle product group status (Active/Obsolete)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        try {
            $productGroup = ProductGroup::findOrFail($id);
            // Toggle status between 'Act' and 'Obs'
            $productGroup->status = ($productGroup->status === 'Act') ? 'Obs' : 'Act';
            $productGroup->save();

            return response()->json([
                'success' => true,
                'message' => 'Product group status updated successfully',
                'data' => $productGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@toggleStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error toggling product group status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API method to seed product groups data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function seedData()
    {
        try {
            $sampleGroups = [
                ['code' => 'B', 'name' => 'Box', 'is_active' => true],
                ['code' => 'P', 'name' => 'Paper', 'is_active' => true],
                ['code' => 'C', 'name' => 'Carton', 'is_active' => true],
                ['code' => 'L', 'name' => 'Label', 'is_active' => true],
                ['code' => 'F', 'name' => 'Film', 'is_active' => true],
            ];

            foreach ($sampleGroups as $group) {
                // Check if the group already exists
                $existingGroup = ProductGroup::where('product_group_id', $group['code'])->first();
                
                if (!$existingGroup) {
                    ProductGroup::create([
                        'product_group_id' => $group['code'],
                        'product_group_name' => $group['name'],
                        'is_active' => $group['is_active']
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error seeding product group data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function apiSeed()
    {
        try {
            $this->seedData();
            return response()->json([
                'success' => true,
                'message' => 'Product group seed data created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@apiSeed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error seeding product groups: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_group_id' => 'required|string|max:10|unique:product_groups',
            'product_group_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productGroup = ProductGroup::create([
            'product_group_id' => $request->product_group_id,
            'product_group_name' => $request->product_group_name,
                'is_active' => $request->has('is_active') ? $request->is_active : true
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product group created successfully',
                    'data' => $productGroup
                ]);
            }

        return redirect()->route('product-group.index')->with('success', 'Grup Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating product group: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating product group: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error creating product group: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $productGroup = ProductGroup::where('product_group_id', $id)->firstOrFail();
        $productGroups = ProductGroup::all();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.productgroup', compact('productGroup', 'productGroups'));
    }

    public function update(Request $request, $id)
    {
        try {
            $productGroup = ProductGroup::where('product_group_id', $id)->firstOrFail();
            
            // Log the incoming request data to debug
            Log::info('Update product group request data', [
                'id' => $id,
                'request_data' => $request->all()
            ]);
            
        $validator = Validator::make($request->all(), [
            'product_group_name' => 'required|string|max:100',
                'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
                Log::warning('Product group validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Update the product group
        $productGroup->update([
            'product_group_name' => $request->product_group_name,
                'is_active' => $request->is_active
            ]);

            Log::info('Product group updated successfully', [
                'id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product group updated successfully',
                'data' => $productGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating product group: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating product group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
        $productGroup = ProductGroup::where('product_group_id', $id)->firstOrFail();
        $productGroup->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product group deleted successfully'
                ]);
            }

        return redirect()->route('product-group.index')->with('success', 'Grup Produk berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting product group: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting product group: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error deleting product group: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource for printing.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        $productGroups = ProductGroup::orderBy('product_group_name')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintproductgroup', compact('productGroups'));
    }
    
    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response
     */
    public function vueViewAndPrint()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/view-and-print-product-group');
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@vueViewAndPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load product group data for printing'], 500);
        }
    }
    
    /**
     * Render the Vue component for product group management.
     *
     * @return \Inertia\Response
     */
    public function vueIndex()
    {
        try {
            return Inertia::render('sales-management/system-requirement/standard-requirement/product-group', [
                'header' => 'Product Group Management'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@vueIndex: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load product groups data'], 500);
        }
    }

    /**
     * Display the Vue version of product group status management page
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $productGroups = ProductGroup::orderBy('product_group_id', 'asc')->paginate(15);

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-product-group', [
                'productGroups' => $productGroups->items(),
                'pagination' => [
                    'currentPage' => $productGroups->currentPage(),
                    'perPage' => $productGroups->perPage(),
                    'total' => $productGroups->total()
                ],
                'header' => 'Manage Product Group Status'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductGroupController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-product-group', [
                'productGroups' => [],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => 15,
                    'total' => 0
                ],
                'header' => 'Manage Product Group Status',
                'error' => 'Error displaying product groups: ' . $e->getMessage()
            ]);
        }
    }
} 