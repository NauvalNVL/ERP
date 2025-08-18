<?php
namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SkuCustomTariffCode;
use App\Models\MmSku;
use App\Models\CustomTariffCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkuCustomTariffCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $skuId = $request->query('sku_id', '');
        $isActive = $request->query('is_active', '');
        
        $query = SkuCustomTariffCode::with(['sku', 'customTariffCode']);
        
        if ($search) {
            $query->search($search);
        }
        
        if ($skuId) {
            $query->where('sku_id', $skuId);
        }
        
        if ($isActive !== '') {
            $query->where('is_active', $isActive);
        }
        
        $skuTariffCodes = $query->orderBy('sku_id')->paginate(20);
        
        return response()->json($skuTariffCodes);
    }

    public function show($id)
    {
        return response()->json(SkuCustomTariffCode::with(['sku', 'customTariffCode'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku_id' => 'required|string|exists:mm_skus,sku',
            'custom_tariff_code_id' => 'required|exists:custom_tariff_codes,id',
            'tariff_description' => 'nullable|string',
            'duty_rate' => 'required|numeric|min:0|max:100',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'pph_import_rate' => 'required|numeric|min:0|max:100',
            'country_origin' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // Check for existing record
        $existing = SkuCustomTariffCode::where('sku_id', $data['sku_id'])
            ->where('custom_tariff_code_id', $data['custom_tariff_code_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'error' => 'SKU already has this tariff code assigned'
            ], 422);
        }

        $data['created_by'] = Auth::user()->username ?? 'system';
        $data['is_active'] = $request->input('is_active', true);

        $skuTariffCode = SkuCustomTariffCode::create($data);
        return response()->json($skuTariffCode->load(['sku', 'customTariffCode']), 201);
    }

    public function update(Request $request, $id)
    {
        $skuTariffCode = SkuCustomTariffCode::findOrFail($id);
        
        $data = $request->validate([
            'sku_id' => 'required|string|exists:mm_skus,sku',
            'custom_tariff_code_id' => 'required|exists:custom_tariff_codes,id',
            'tariff_description' => 'nullable|string',
            'duty_rate' => 'required|numeric|min:0|max:100',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'pph_import_rate' => 'required|numeric|min:0|max:100',
            'country_origin' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // Check for existing record (excluding current)
        $existing = SkuCustomTariffCode::where('sku_id', $data['sku_id'])
            ->where('custom_tariff_code_id', $data['custom_tariff_code_id'])
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'error' => 'SKU already has this tariff code assigned'
            ], 422);
        }

        $data['updated_by'] = Auth::user()->username ?? 'system';
        $data['is_active'] = $request->input('is_active', $skuTariffCode->is_active);

        $skuTariffCode->update($data);
        return response()->json($skuTariffCode->load(['sku', 'customTariffCode']));
    }

    public function destroy($id)
    {
        $skuTariffCode = SkuCustomTariffCode::findOrFail($id);
        $skuTariffCode->delete();
        return response()->json(['success' => true]);
    }

    // Get SKU suggestions for search
    public function getSkuSuggestions(Request $request)
    {
        $search = $request->query('search', '');
        
        $skus = MmSku::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('sku', 'like', "%{$search}%")
                      ->orWhere('sku_name', 'like', "%{$search}%");
            })
            ->select('sku', 'sku_name', 'sku_additional_name')
            ->limit(20)
            ->get();
        
        return response()->json($skus);
    }

    // Get tariff code suggestions for search
    public function getTariffCodeSuggestions(Request $request)
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

    // Get SKU tariff code by SKU ID
    public function getBySkuId($skuId)
    {
        $skuTariffCode = SkuCustomTariffCode::with(['sku', 'customTariffCode'])
            ->where('sku_id', $skuId)
            ->where('is_active', true)
            ->first();
        
        return response()->json($skuTariffCode);
    }

    // Calculate customs for SKU
    public function calculateCustoms(Request $request)
    {
        $request->validate([
            'sku_id' => 'required|string|exists:mm_skus,sku',
            'value' => 'required|numeric|min:0',
        ]);

        $skuTariffCode = SkuCustomTariffCode::with(['sku', 'customTariffCode'])
            ->where('sku_id', $request->sku_id)
            ->where('is_active', true)
            ->first();

        if (!$skuTariffCode) {
            return response()->json([
                'success' => false,
                'error' => 'No tariff code assigned to this SKU'
            ], 404);
        }

        $value = $request->value;

        $result = [
            'sku_id' => $skuTariffCode->sku_id,
            'sku_name' => $skuTariffCode->sku->sku_name,
            'tariff_code' => $skuTariffCode->customTariffCode->code,
            'tariff_name' => $skuTariffCode->customTariffCode->name,
            'duty_rate' => $skuTariffCode->duty_rate,
            'vat_rate' => $skuTariffCode->vat_rate,
            'pph_import_rate' => $skuTariffCode->pph_import_rate,
            'total_rate' => $skuTariffCode->getTotalRate(),
            'duty_amount' => $skuTariffCode->calculateDuty($value),
            'vat_amount' => $skuTariffCode->calculateVat($value),
            'pph_import_amount' => $skuTariffCode->calculatePphImport($value),
            'total_customs' => $skuTariffCode->calculateTotalCustoms($value),
            'base_value' => $value,
        ];

        return response()->json($result);
    }

    // Bulk operations
    public function bulkStore(Request $request)
    {
        $request->validate([
            'sku_tariff_codes' => 'required|array',
            'sku_tariff_codes.*.sku_id' => 'required|string|exists:mm_skus,sku',
            'sku_tariff_codes.*.custom_tariff_code_id' => 'required|exists:custom_tariff_codes,id',
            'sku_tariff_codes.*.duty_rate' => 'required|numeric|min:0|max:100',
            'sku_tariff_codes.*.vat_rate' => 'required|numeric|min:0|max:100',
            'sku_tariff_codes.*.pph_import_rate' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();
            
            $created = [];
            foreach ($request->sku_tariff_codes as $data) {
                $data['created_by'] = Auth::user()->username ?? 'system';
                $data['is_active'] = $data['is_active'] ?? true;

                $skuTariffCode = SkuCustomTariffCode::updateOrCreate([
                    'sku_id' => $data['sku_id'],
                    'custom_tariff_code_id' => $data['custom_tariff_code_id']
                ], $data);
                
                $created[] = $skuTariffCode->load(['sku', 'customTariffCode']);
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'SKU tariff codes created successfully',
                'data' => $created
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to create SKU tariff codes: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle active status
    public function toggleActive($id)
    {
        $skuTariffCode = SkuCustomTariffCode::findOrFail($id);
        $skuTariffCode->update([
            'is_active' => !$skuTariffCode->is_active,
            'updated_by' => Auth::user()->username ?? 'system'
        ]);
        
        return response()->json([
            'success' => true,
            'is_active' => $skuTariffCode->is_active
        ]);
    }

    // Get summary statistics
    public function getSummary()
    {
        $summary = [
            'total_assignments' => SkuCustomTariffCode::count(),
            'active_assignments' => SkuCustomTariffCode::where('is_active', true)->count(),
            'inactive_assignments' => SkuCustomTariffCode::where('is_active', false)->count(),
            'skus_with_tariff_codes' => SkuCustomTariffCode::distinct('sku_id')->count(),
            'skus_without_tariff_codes' => MmSku::where('is_active', true)->count() - SkuCustomTariffCode::distinct('sku_id')->count(),
            'avg_duty_rate' => SkuCustomTariffCode::where('is_active', true)->avg('duty_rate'),
            'avg_vat_rate' => SkuCustomTariffCode::where('is_active', true)->avg('vat_rate'),
            'avg_pph_rate' => SkuCustomTariffCode::where('is_active', true)->avg('pph_import_rate'),
        ];
        
        return response()->json($summary);
    }

    // Export SKU tariff codes
    public function export(Request $request)
    {
        $format = $request->query('format', 'json');
        $search = $request->query('search', '');
        
        $query = SkuCustomTariffCode::with(['sku', 'customTariffCode']);
        
        if ($search) {
            $query->search($search);
        }
        
        $skuTariffCodes = $query->orderBy('sku_id')->get();
        
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="sku_tariff_codes.csv"',
            ];
            
            $callback = function() use ($skuTariffCodes) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['SKU Code', 'SKU Name', 'Tariff Code', 'Tariff Name', 'Duty Rate', 'VAT Rate', 'PPh Rate', 'Country Origin', 'Active']);
                
                foreach ($skuTariffCodes as $record) {
                    fputcsv($file, [
                        $record->sku_id,
                        $record->sku->sku_name,
                        $record->customTariffCode->code,
                        $record->customTariffCode->name,
                        $record->duty_rate,
                        $record->vat_rate,
                        $record->pph_import_rate,
                        $record->country_origin,
                        $record->is_active ? 'Yes' : 'No'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        return response()->json($skuTariffCodes);
    }

    // Get SKUs without tariff codes
    public function getSkusWithoutTariffCodes()
    {
        $skusWithoutTariffCodes = MmSku::where('is_active', true)
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('sku_custom_tariff_codes')
                      ->whereColumn('sku_custom_tariff_codes.sku_id', 'mm_skus.sku')
                      ->where('sku_custom_tariff_codes.is_active', true);
            })
            ->select('sku', 'sku_name', 'sku_additional_name')
            ->orderBy('sku')
            ->get();
        
        return response()->json($skusWithoutTariffCodes);
    }
} 