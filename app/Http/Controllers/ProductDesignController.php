<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDesign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Database\Seeders\ProductDesignSeeder;

class ProductDesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->getDesignsJson();
            }

            $productDesigns = ProductDesign::paginate(10);
            
            // If there are no product designs in the database, seed them
            if ($productDesigns->isEmpty()) {
                $this->seedData();
                $productDesigns = ProductDesign::paginate(10);
            }
            
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return redirect()->route('productdesign.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pd_code' => 'required|string|max:10|unique:product_designs,pd_code',
            'pd_name' => 'required|string|max:255',
            'pd_alt_name' => 'nullable|string|max:255',
            'pd_design_type' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'idc' => 'nullable|string|max:100',
            'joint' => 'nullable|string|max:100',
            'joint_to_print' => 'nullable|string|max:100',
            'pcs_to_joint' => 'nullable|string|max:100',
            'score' => 'nullable|string|max:100',
            'slot' => 'nullable|string|max:100',
            'flute_style' => 'nullable|string|max:100',
            'print_flute' => 'nullable|string|max:100',
            'input_weight' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('productdesign.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ProductDesign::create([
                'pd_code' => strtoupper($request->pd_code),
                'pd_name' => $request->pd_name,
                'pd_alt_name' => $request->pd_alt_name,
                'pd_design_type' => $request->pd_design_type,
                'idc' => $request->idc,
                'product' => $request->product,
                'joint' => $request->joint,
                'joint_to_print' => $request->joint_to_print,
                'pcs_to_joint' => $request->pcs_to_joint,
                'score' => $request->score,
                'slot' => $request->slot,
                'flute_style' => $request->flute_style,
                'print_flute' => $request->print_flute,
                'input_weight' => $request->input_weight,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('productdesign.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $productDesign = ProductDesign::where('pd_code', $id)->firstOrFail();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pd_name' => 'required|string|max:255',
            'pd_design_type' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'idc' => 'nullable|string|max:100',
            'joint' => 'nullable|string|max:100',
            'joint_to_print' => 'nullable|string|max:100',
            'pcs_to_joint' => 'nullable|string|max:100',
            'score' => 'nullable|string|max:100',
            'slot' => 'nullable|string|max:100',
            'flute_style' => 'nullable|string|max:100',
            'print_flute' => 'nullable|string|max:100',
            'input_weight' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productDesign = ProductDesign::where('pd_code', $id)->firstOrFail();
            
            $productDesign->update([
                'pd_name' => $request->pd_name,
                'pd_alt_name' => $request->pd_alt_name,
                'pd_design_type' => $request->pd_design_type,
                'idc' => $request->idc,
                'product' => $request->product,
                'joint' => $request->joint,
                'joint_to_print' => $request->joint_to_print,
                'pcs_to_joint' => $request->pcs_to_joint,
                'score' => $request->score,
                'slot' => $request->slot,
                'flute_style' => $request->flute_style,
                'print_flute' => $request->print_flute,
                'input_weight' => $request->input_weight,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $productDesign = ProductDesign::where('pd_code', $id)->firstOrFail();
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
     * View and print product designs.
     *
     * @return \Illuminate\View\View
     */
    public function viewAndPrint()
    {
        $productDesigns = ProductDesign::orderBy('pd_code')->get();
        return view('sales-management.system-requirement.system-requirement.standard-requirement.viewandprintproductdesign', compact('productDesigns')); 
    }

    /**
     * Display a listing of the resource for printing in Vue.
     *
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
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
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
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
     * Display the Vue version of product design status management page
     *
     * @return \Inertia\Response
     */
    public function vueManageStatus()
    {
        try {
            $productDesigns = ProductDesign::orderBy('pd_code', 'asc')->paginate(15);

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-product-design', [
                'productDesigns' => $productDesigns->items(),
                'pagination' => [
                    'currentPage' => $productDesigns->currentPage(),
                    'perPage' => $productDesigns->perPage(),
                    'total' => $productDesigns->total()
                ],
                'header' => 'Manage Product Design Status'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@vueManageStatus: ' . $e->getMessage());

            return Inertia::render('sales-management/system-requirement/standard-requirement/obsolete-unobsolete-product-design', [
                'productDesigns' => [],
                'pagination' => [
                    'currentPage' => 1,
                    'perPage' => 15,
                    'total' => 0
                ],
                'header' => 'Manage Product Design Status',
                'error' => 'Error displaying product designs: ' . $e->getMessage()
            ]);
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
                'id',
                'pd_code',
                'pd_name',
                'pd_design_type',
                'idc',
                'product',
                'joint',
                'joint_to_print',
                'pcs_to_joint',
                'score',
                'slot',
                'flute_style',
                'print_flute',
                'input_weight',
            )->get();
            
            return response()->json($designs);
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@getDesignsJson: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve product designs'], 500);
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
                'pd_code' => 'required|string|max:10|unique:product_designs,pd_code',
                'pd_name' => 'required|string|max:255',
                'pd_alt_name' => 'nullable|string|max:255',
                'pd_design_type' => 'required|string|max:255',
                'product' => 'required|string|max:255',
                'idc' => 'nullable|string|max:100',
                'joint' => 'nullable|string|max:100',
                'joint_to_print' => 'nullable|string|max:100',
                'pcs_to_joint' => 'nullable|string|max:100',
                'score' => 'nullable|string|max:100',
                'slot' => 'nullable|string|max:100',
                'flute_style' => 'nullable|string|max:100',
                'print_flute' => 'nullable|string|max:100',
                'input_weight' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $productDesign = ProductDesign::create([
                'pd_code' => strtoupper($request->pd_code),
                'pd_name' => $request->pd_name,
                'pd_alt_name' => $request->pd_alt_name,
                'pd_design_type' => $request->pd_design_type,
                'idc' => $request->idc,
                'product' => $request->product,
                'joint' => $request->joint,
                'joint_to_print' => $request->joint_to_print,
                'pcs_to_joint' => $request->pcs_to_joint,
                'score' => $request->score,
                'slot' => $request->slot,
                'flute_style' => $request->flute_style,
                'print_flute' => $request->print_flute,
                'input_weight' => $request->input_weight,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product design created successfully',
                'data' => $productDesign
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
        $validator = Validator::make($request->all(), [
            'pd_code' => 'sometimes|string|max:10',
            'pd_name' => 'sometimes|string|max:255',
            'pd_alt_name' => 'nullable|string|max:255',
            'pd_design_type' => 'sometimes|string|max:255',
            'product' => 'sometimes|string|max:255',
            'idc' => 'nullable|string|max:100',
            'joint' => 'nullable|string|max:100',
            'joint_to_print' => 'nullable|string|max:100',
            'pcs_to_joint' => 'nullable|string|max:100',
            'score' => 'nullable|string|max:100',
            'slot' => 'nullable|string|max:100',
            'flute_style' => 'nullable|string|max:100',
            'print_flute' => 'nullable|string|max:100',
            'input_weight' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Try to find by id first, then by pd_code
            $productDesign = ProductDesign::where('id', $id)->first();
            if (!$productDesign) {
                $productDesign = ProductDesign::where('pd_code', $id)->first();
            }
            
            if (!$productDesign) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product design not found'
                ], 404);
            }

            $updateData = [];
            
            if ($request->has('pd_name')) {
                $updateData['pd_name'] = $request->pd_name;
            }
            if ($request->has('pd_design_type')) {
                $updateData['pd_design_type'] = $request->pd_design_type;
            }
            if ($request->has('idc')) {
                $updateData['idc'] = $request->idc;
            }
            if ($request->has('product')) {
                $updateData['product'] = $request->product;
            }
            if ($request->has('joint')) {
                $updateData['joint'] = $request->joint;
            }
            if ($request->has('joint_to_print')) {
                $updateData['joint_to_print'] = $request->joint_to_print;
            }
            if ($request->has('pcs_to_joint')) {
                $updateData['pcs_to_joint'] = $request->pcs_to_joint;
            }
            if ($request->has('score')) {
                $updateData['score'] = $request->score;
            }
            if ($request->has('slot')) {
                $updateData['slot'] = $request->slot;
            }
            if ($request->has('flute_style')) {
                $updateData['flute_style'] = $request->flute_style;
            }
            if ($request->has('print_flute')) {
                $updateData['print_flute'] = $request->print_flute;
            }
            if ($request->has('input_weight')) {
                $updateData['input_weight'] = $request->input_weight;
            }
            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }

            $productDesign->update($updateData);
            
            // Refresh the model from the database to get the latest state
            $productDesign->refresh();

            return response()->json(['success' => true, 'data' => $productDesign]);
        } catch (\Exception $e) {
            Log::error('Error in ProductDesignController@apiUpdate: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update product design'], 500);
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


    /**
     * Get a product design by its code
     * 
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCode($code)
    {
        try {
            $design = ProductDesign::where('pd_code', $code)->first();
            
            if (!$design) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product design not found'
                ], 404);
            }
            
            return response()->json($design);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving product design: ' . $e->getMessage()
            ], 500);
        }
    }

    private function seedData()
    {
        try {
            $seeder = new ProductDesignSeeder();
            $seeder->run();
        } catch (\Exception $e) {
            Log::error('Error seeding product design data: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json(['success' => true, 'message' => 'Product design seed data created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product design seed data: ' . $e->getMessage()
            ], 500);
        }
    }
}
