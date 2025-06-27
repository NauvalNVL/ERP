<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\MmCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Database\Seeders\MmCategorySeeder;

class MmCategoryController extends Controller
{
    /**
     * Display the category management interface.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/Category');
    }
    
    /**
     * Display a view for printing the categories.
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintCategory');
    }
    
    /**
     * Get all categories for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = MmCategory::orderBy('code')->get();
        return response()->json($categories);
    }
    
    /**
     * Show a specific category for API
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $category = MmCategory::findOrFail($code);
        return response()->json($category);
    }
    
    /**
     * Store a new category
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:mm_categories,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $category = MmCategory::create($request->all());
        
        return response()->json($category, 201);
    }
    
    /**
     * Update an existing category
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $category = MmCategory::findOrFail($code);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $category->update($request->all());
        
        return response()->json($category);
    }
    
    /**
     * Delete a category
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        $category = MmCategory::findOrFail($code);
        $category->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Toggle category active status
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive($code)
    {
        $category = MmCategory::findOrFail($code);
        $category->is_active = !$category->is_active;
        $category->save();
        
        return response()->json($category);
    }
    
    /**
     * Get categories for the ViewPrint page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriesForPrint()
    {
        $categories = MmCategory::orderBy('code')->get();
        return response()->json($categories);
    }
    
    /**
     * Seed sample category data for testing purposes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seedSampleData()
    {
        $seeder = new MmCategorySeeder();
        $seeder->run();
        
        return response()->json(['message' => 'Sample categories seeded successfully']);
    }
} 