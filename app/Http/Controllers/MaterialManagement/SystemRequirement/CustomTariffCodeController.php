<?php
namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomTariffCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomTariffCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $category = $request->query('category', '');
        $isActive = $request->query('is_active', '');
        
        $query = CustomTariffCode::query();
        
        if ($search) {
            $query->search($search);
        }
        
        if ($category) {
            $query->where('category', $category);
        }
        
        if ($isActive !== '') {
            $query->where('is_active', $isActive);
        }
        
        $tariffCodes = $query->orderBy('code')->paginate(20);
        
        return response()->json($tariffCodes);
    }

    public function show($id)
    {
        return response()->json(CustomTariffCode::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:custom_tariff_codes,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duty_rate' => 'required|numeric|min:0|max:100',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'category' => 'nullable|string|max:100',
            'country_origin' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $data['created_by'] = Auth::user()->username ?? 'system';
        $data['is_active'] = $request->input('is_active', true);

        $tariffCode = CustomTariffCode::create($data);
        return response()->json($tariffCode, 201);
    }

    public function update(Request $request, $id)
    {
        $tariffCode = CustomTariffCode::findOrFail($id);
        
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:custom_tariff_codes,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duty_rate' => 'required|numeric|min:0|max:100',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'category' => 'nullable|string|max:100',
            'country_origin' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $data['updated_by'] = Auth::user()->username ?? 'system';
        $data['is_active'] = $request->input('is_active', $tariffCode->is_active);

        $tariffCode->update($data);
        return response()->json($tariffCode);
    }

    public function destroy($id)
    {
        $tariffCode = CustomTariffCode::findOrFail($id);
        $tariffCode->delete();
        return response()->json(['success' => true]);
    }

    // Get tariff code suggestions for search
    public function getSuggestions(Request $request)
    {
        $search = $request->query('search', '');
        
        $tariffCodes = CustomTariffCode::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
            })
            ->select('id', 'code', 'name', 'duty_rate', 'tax_rate', 'category')
            ->limit(20)
            ->get();
        
        return response()->json($tariffCodes);
    }

    // Get available categories
    public function getCategories()
    {
        $categories = CustomTariffCode::select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
        
        return response()->json($categories);
    }

    // Calculate customs duty and tax
    public function calculateCustoms(Request $request)
    {
        $request->validate([
            'tariff_code_id' => 'required|exists:custom_tariff_codes,id',
            'value' => 'required|numeric|min:0',
        ]);

        $tariffCode = CustomTariffCode::findOrFail($request->tariff_code_id);
        $value = $request->value;

        $result = [
            'tariff_code' => $tariffCode->code,
            'name' => $tariffCode->name,
            'duty_rate' => $tariffCode->duty_rate,
            'tax_rate' => $tariffCode->tax_rate,
            'total_rate' => $tariffCode->getTotalRate(),
            'duty_amount' => $tariffCode->calculateDuty($value),
            'tax_amount' => $tariffCode->calculateTax($value),
            'total_customs' => $tariffCode->calculateTotalCustoms($value),
            'base_value' => $value,
        ];

        return response()->json($result);
    }

    // Bulk operations
    public function bulkStore(Request $request)
    {
        $request->validate([
            'tariff_codes' => 'required|array',
            'tariff_codes.*.code' => 'required|string|max:20',
            'tariff_codes.*.name' => 'required|string|max:255',
            'tariff_codes.*.duty_rate' => 'required|numeric|min:0|max:100',
            'tariff_codes.*.tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();
            
            $created = [];
            foreach ($request->tariff_codes as $tariffData) {
                $tariffData['created_by'] = Auth::user()->username ?? 'system';
                $tariffData['is_active'] = $tariffData['is_active'] ?? true;

                $tariffCode = CustomTariffCode::updateOrCreate([
                    'code' => $tariffData['code']
                ], $tariffData);
                
                $created[] = $tariffCode;
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tariff codes created successfully',
                'data' => $created
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to create tariff codes: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle active status
    public function toggleActive($id)
    {
        $tariffCode = CustomTariffCode::findOrFail($id);
        $tariffCode->update([
            'is_active' => !$tariffCode->is_active,
            'updated_by' => Auth::user()->username ?? 'system'
        ]);
        
        return response()->json([
            'success' => true,
            'is_active' => $tariffCode->is_active
        ]);
    }

    // Get tariff code summary
    public function getSummary()
    {
        $summary = [
            'total_codes' => CustomTariffCode::count(),
            'active_codes' => CustomTariffCode::where('is_active', true)->count(),
            'inactive_codes' => CustomTariffCode::where('is_active', false)->count(),
            'categories' => CustomTariffCode::whereNotNull('category')->distinct('category')->count(),
            'avg_duty_rate' => CustomTariffCode::where('is_active', true)->avg('duty_rate'),
            'avg_tax_rate' => CustomTariffCode::where('is_active', true)->avg('tax_rate'),
        ];
        
        return response()->json($summary);
    }

    // Export tariff codes
    public function export(Request $request)
    {
        $format = $request->query('format', 'json');
        $search = $request->query('search', '');
        
        $query = CustomTariffCode::query();
        
        if ($search) {
            $query->search($search);
        }
        
        $tariffCodes = $query->orderBy('code')->get();
        
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="tariff_codes.csv"',
            ];
            
            $callback = function() use ($tariffCodes) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Code', 'Name', 'Description', 'Duty Rate', 'Tax Rate', 'Category', 'Country Origin', 'Active']);
                
                foreach ($tariffCodes as $code) {
                    fputcsv($file, [
                        $code->code,
                        $code->name,
                        $code->description,
                        $code->duty_rate,
                        $code->tax_rate,
                        $code->category,
                        $code->country_origin,
                        $code->is_active ? 'Yes' : 'No'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        return response()->json($tariffCodes);
    }

    // View & Print method for Custom Tariff Code
    public function getViewPrint(Request $request)
    {
        try {
            // Get all custom tariff codes
            $query = CustomTariffCode::query();
            
            // Apply filters if provided
            if ($request->filled('search')) {
                $query->search($request->search);
            }
            
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            
            if ($request->filled('is_active') && $request->is_active !== '') {
                $query->where('is_active', $request->is_active);
            }
            
            $tariffCodes = $query->orderBy('code')->get();
            
            // Transform data to match the desktop application format
            $result = $tariffCodes->map(function($tariffCode) {
                return [
                    'code' => $tariffCode->code,
                    'name' => $tariffCode->name,
                    'description' => $tariffCode->description,
                    'duty_rate' => $tariffCode->duty_rate,
                    'tax_rate' => $tariffCode->tax_rate,
                    'total_rate' => $tariffCode->getTotalRate(),
                    'category' => $tariffCode->category,
                    'country_origin' => $tariffCode->country_origin,
                    'is_active' => $tariffCode->is_active,
                    'formatted_code' => $tariffCode->getFormattedCode(),
                    'notes' => $tariffCode->notes,
                ];
            });
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in getViewPrint: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
} 