<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDesign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            'pd_code' => 'required|string|max:10|unique:product_designs,pd_code',
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
            'compute' => 'nullable|string|max:10',
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
                'compute' => $request->compute ?? 'No',
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
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
            'compute' => 'nullable|string|max:10',
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
                'compute' => $request->compute ?? 'No',
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
     * Display a listing of the resource for printing.
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
                'compute'
            )->get()->map(function ($design) {
                $design->compute = (strtolower($design->compute) === 'yes');
                return $design;
            });
            
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
                'compute' => 'nullable|string|max:10',
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
                'compute' => $request->compute ?? 'No',
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
            'pd_code' => 'required|string|max:10',
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
            'compute' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $productDesign = ProductDesign::where('pd_code', $id)->firstOrFail();
            
            $computeValue = $request->compute;
            // Handle boolean and string 'true'/'false' from JS
            if (is_bool($computeValue)) {
                $computeString = $computeValue ? 'Yes' : 'No';
            } elseif (in_array(strtolower($computeValue), ['true', 'yes', '1'])) {
                $computeString = 'Yes';
            } elseif (in_array(strtolower($computeValue), ['false', 'no', '0'])) {
                $computeString = 'No';
            } else {
                $computeString = 'No'; // Default value
            }

            $productDesign->update([
                'pd_name' => $request->pd_name,
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
                'compute' => $computeString,
            ]);
            
            // Refresh the model from the database to get the latest state
            $productDesign->refresh();
            
            // Apply the same transformation as getDesignsJson
            $productDesign->compute = (strtolower($productDesign->compute) === 'yes');

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
     * Export product designs to CSV
     * 
     * @return \Illuminate\Http\Response
     */
    public function apiExport()
    {
        try {
            $productDesigns = ProductDesign::with('productDetail')->get();

            $data = $productDesigns->map(function ($design) {
                return [
                    'pd_code' => $design->pd_code,
                    'pd_name' => $design->pd_name,
                    'pd_design_type' => $design->pd_design_type,
                    'product' => optional($design->productDetail)->description,
                    'joint' => $design->joint ? 'Yes' : 'No',
                    'joint_to_print' => $design->joint_to_print ? 'Yes' : 'No',
                    'pcs_to_joint' => $design->pcs_to_joint,
                    'score' => $design->score ? 'Yes' : 'No',
                    'slot' => $design->slot ? 'Yes' : 'No',
                    'flute_style' => $design->flute_style,
                    'print_flute' => $design->print_flute ? 'Yes' : 'No',
                    'input_weight' => $design->input_weight ? 'Yes' : 'No',
                    'compute' => $design->compute ? 'Yes' : 'No',
                ];
            });

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set Title
            $sheet->mergeCells('A1:M1');
            $sheet->setCellValue('A1', 'Product Design List');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Set Date
            $sheet->mergeCells('A2:M2');
            $sheet->setCellValue('A2', 'Date: ' . now()->format('Y-m-d'));
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Set Headers
            $headers = [
                'Product Design Code', 'Product Design Name', 'Design Type', 'Product',
                'Joint', 'Joint to Print', 'Pcs to Joint', 'Score', 'Slot',
                'Flute Style', 'Print Flute', 'Input Weight', 'To Compute'
            ];
            $sheet->fromArray($headers, NULL, 'A4');
            $headerStyle = $sheet->getStyle('A4:M4');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0E0E0');

            // Populate Data
            $sheet->fromArray($data->toArray(), NULL, 'A5');

            // Apply borders to the entire table
            $highestRow = $sheet->getHighestRow();
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A4:M' . $highestRow)->applyFromArray($styleArray);

            // Auto-size columns
            foreach (range('A', 'M') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            $writer = new Xlsx($spreadsheet);
            $fileName = 'product_designs_' . now()->format('Ymd') . '.xlsx';

            return response()->stream(function () use ($writer) {
                $writer->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                'Cache-Control' => 'max-age=0',
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Export Product Designs Failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Server error while exporting product designs: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a product design by its code
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
}
