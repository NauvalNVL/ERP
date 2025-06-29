<?php

namespace App\Http\Controllers;

use App\Models\CorrugatorSpecByProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CorrugatorSpecByProductController extends Controller
{
    /**
     * Display the corrugator specifications by product page.
     */
    public function index()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/CorrugatorSpesificationByProduct');
    }

    /**
     * Display the view and print page for corrugator specifications by product.
     */
    public function viewPrint()
    {
        return Inertia::render('sales-management/standard-formula/setup-corrugator-run-size-formula/ViewPrintCorrugatorSpesificationByProduct');
    }

    /**
     * API: Get all corrugator specifications by product
     */
    public function apiIndex()
    {
        $products = Product::leftJoin('corrugator_spec_by_products as cs', 'products.product_code', '=', 'cs.product_code')
            ->select(
                'products.id as product_id',
                'products.product_code',
                'products.description as product_name',
                'cs.id as spec_id',
                'cs.compute',
                'cs.min_sheet_length',
                'cs.max_sheet_length',
                'cs.min_sheet_width',
                'cs.max_sheet_width'
            )
            ->orderBy('products.product_code')
            ->get()
            ->map(function ($item) {
                $item->compute = $item->compute ?? false;
                $item->min_sheet_length = $item->min_sheet_length ?? 0;
                $item->max_sheet_length = $item->max_sheet_length ?? 0;
                $item->min_sheet_width = $item->min_sheet_width ?? 0;
                $item->max_sheet_width = $item->max_sheet_width ?? 0;
                return $item;
            });
    
        return response()->json($products);
    }

    /**
     * API: Get a specific corrugator specification by product
     */
    public function apiShow($id)
    {
        $spec = CorrugatorSpecByProduct::with('product')->findOrFail($id);
        return response()->json($spec);
    }

    /**
     * API: Store a new corrugator specification by product
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|exists:products,product_code',
            'compute' => 'sometimes|boolean',
            'min_sheet_length' => 'nullable|integer|min:1',
            'max_sheet_length' => 'nullable|integer|min:1',
            'min_sheet_width' => 'nullable|integer|min:1',
            'max_sheet_width' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if specification already exists for this product
        $existingSpec = CorrugatorSpecByProduct::where('product_code', $request->product_code)->first();
        if ($existingSpec) {
            // Update existing specification
            $existingSpec->update($request->all());
            return response()->json(['success' => true, 'data' => $existingSpec], 200);
        }

        $spec = CorrugatorSpecByProduct::create($request->all());
        return response()->json(['success' => true, 'data' => $spec], 201);
    }

    /**
     * API: Update a corrugator specification by product
     */
    public function apiUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'sometimes|exists:products,product_code',
            'compute' => 'sometimes|boolean',
            'min_sheet_length' => 'nullable|integer|min:1',
            'max_sheet_length' => 'nullable|integer|min:1',
            'min_sheet_width' => 'nullable|integer|min:1',
            'max_sheet_width' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $spec = CorrugatorSpecByProduct::findOrFail($id);
        $spec->update($request->all());
        
        return response()->json(['success' => true, 'data' => $spec]);
    }

    /**
     * API: Delete a corrugator specification by product
     */
    public function apiDestroy($id)
    {
        $spec = CorrugatorSpecByProduct::findOrFail($id);
        $spec->delete();
        
        return response()->json(null, 204);
    }

    /**
     * API: Batch update or create corrugator specifications by product
     */
    public function apiBatchUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.product_code' => 'required|exists:products,product_code',
            '*.compute' => 'required|boolean',
            '*.min_sheet_length' => 'nullable|integer|min:0',
            '*.max_sheet_length' => 'nullable|integer|min:0',
            '*.min_sheet_width' => 'nullable|integer|min:0',
            '*.max_sheet_width' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $results = [];
        $errors = [];

        foreach ($request->all() as $specData) {
            try {
                $spec = CorrugatorSpecByProduct::updateOrCreate(
                    ['product_code' => $specData['product_code']],
                    [
                        'compute' => $specData['compute'],
                        'min_sheet_length' => $specData['min_sheet_length'] ?? 0,
                        'max_sheet_length' => $specData['max_sheet_length'] ?? 0,
                        'min_sheet_width' => $specData['min_sheet_width'] ?? 0,
                        'max_sheet_width' => $specData['max_sheet_width'] ?? 0,
                    ]
                );

                $results[] = $spec;
            } catch (\Exception $e) {
                Log::error('Error updating corrugator spec for product ' . $specData['product_code'] . ': ' . $e->getMessage());
                $errors[] = [
                    'product_code' => $specData['product_code'],
                    'error' => $e->getMessage()
                ];
            }
        }

        if (count($errors) > 0) {
            return response()->json([
                'message' => 'Some specifications could not be updated',
                'results' => $results,
                'errors' => $errors
            ], 207); // 207 Multi-Status
        }

        return response()->json([
            'message' => 'All specifications updated successfully',
            'results' => $results
        ]);
    }

    /**
     * API: Export corrugator specifications to Excel
     */
    public function apiExport(Request $request)
    {
        $products = Product::leftJoin('corrugator_spec_by_products as cs', 'products.product_code', '=', 'cs.product_code')
            ->select(
                'products.product_code',
                'products.description as product_name',
                'cs.compute',
                'cs.min_sheet_length',
                'cs.max_sheet_length',
                'cs.min_sheet_width',
                'cs.max_sheet_width'
            )
            ->orderBy('products.product_code')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Product Code');
        $sheet->setCellValue('B1', 'Product Name');
        $sheet->setCellValue('C1', 'To Compute');
        $sheet->setCellValue('D1', 'Min Sheet Length (mm)');
        $sheet->setCellValue('E1', 'Max Sheet Length (mm)');
        $sheet->setCellValue('F1', 'Min Sheet Width (mm)');
        $sheet->setCellValue('G1', 'Max Sheet Width (mm)');

        // Set data
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->product_code);
            $sheet->setCellValue('B' . $row, $product->product_name);
            $sheet->setCellValue('C' . $row, ($product->compute ?? false) ? 'Yes' : 'No');
            $sheet->setCellValue('D' . $row, $product->min_sheet_length ?? 0);
            $sheet->setCellValue('E' . $row, $product->max_sheet_length ?? 0);
            $sheet->setCellValue('F' . $row, $product->min_sheet_width ?? 0);
            $sheet->setCellValue('G' . $row, $product->max_sheet_width ?? 0);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'corrugator_specifications_by_product.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
} 