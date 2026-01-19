<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Industry;
use App\Models\Geo;
use App\Models\Salesperson;
use App\Models\CustomerGroup;
use Inertia\Inertia;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UpdateCustomerAccountController extends Controller
{
    private function normalizeNpwpValue($raw)
    {
        if ($raw === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) $raw);
        if ($digits === '') {
            return null;
        }

        if (strlen($digits) === 15) {
            return '0' . $digits;
        }

        if (strlen($digits) === 16) {
            return $digits;
        }

        return false;
    }

    public function index()
    {
        $customers = Customer::all();
        $industries = Industry::all();
        $geoData = Geo::all();
        $salespersons = Salesperson::all();
        $customerGroups = CustomerGroup::all();

        return view(
            'sales-management.system-requirement.system-requirement.customer account.updatecustomeraccount',
            compact('customers', 'industries', 'geoData', 'salespersons', 'customerGroups')
        );
    }

    public function store(Request $request)
    {
        $normalizedNpwp = $this->normalizeNpwpValue($request->input('npwp'));
        if ($normalizedNpwp === false) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => [
                    'npwp' => ['NPWP harus 16 digit'],
                ],
            ], 422);
        }

        $request->merge(['npwp' => $normalizedNpwp]);

        $validated = $request->validate([
            'customer_code' => 'required|string|max:50',
            'customer_name' => 'required|string|max:250',
            'short_name' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'address2' => 'nullable|string',
            'address3' => 'nullable|string',
            'contact_person' => 'nullable|string|max:50',
            'telephone_no' => 'nullable|string|max:100',
            'fax_no' => 'nullable|string|max:50',
            'co_email' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric',
            'credit_terms' => 'nullable|numeric',
            'ac_type' => 'required|string|in:Y-Foreign,N-Local',
            'currency_code' => 'nullable|string|max:50',
            'npwp' => 'nullable|digits:16',
            'sales_type' => 'nullable|string|max:50',
            'salesperson_code' => 'nullable|string|max:50|exists:salesperson,Code',
            'industrial_code' => 'nullable|string|max:50|exists:industry,code',
            'geographical' => 'nullable|string|max:50|exists:GEO,CODE',
            'grouping_code' => 'nullable|string|max:50|exists:CUST_GROUP,Group_ID',
            'print_ar_aging' => 'required|string|in:Y-Yes,N-No'
        ], [
            'npwp.digits' => 'NPWP harus 16 digit',
            'salesperson_code.exists' => 'Invalid salesperson code. Please select an existing salesperson.',
            'industrial_code.exists' => 'Invalid industrial code. Please select an existing industry.',
            'geographical.exists' => 'Invalid geographical code. Please select an existing geographical area.',
            'grouping_code.exists' => 'Invalid grouping code. Please select an existing customer group.',
        ]);

        // Map frontend data to CUSTOMER table structure
        $customerData = [
            'CODE' => $validated['customer_code'],
            'AC_STS' => 'A', // Active status
            'NAME' => $validated['customer_name'],
            'ADDRESS1' => $validated['address'] ?? '',
            'ADDRESS2' => $validated['address2'] ?? '',
            'ADDRESS3' => $validated['address3'] ?? '',
            'PERSON_CONTACT' => $validated['contact_person'] ?? '',
            'TEL_NO' => $validated['telephone_no'] ?? '',
            'FAX_NO' => $validated['fax_no'] ?? '',
            'EMAIL' => $validated['co_email'] ?? '',
            'CREDIT_LIMIT' => $validated['credit_limit'] ?? 0,
            'TERM' => $validated['credit_terms'] ?? 0,
            'TYPE' => $validated['ac_type'],
            'CURRENCY' => $validated['currency_code'] ?? '',
            'SLM' => !empty($validated['salesperson_code']) ? $validated['salesperson_code'] : null,
            'AREA' => !empty($validated['geographical']) ? $validated['geographical'] : null,
            'IND' => !empty($validated['industrial_code']) ? $validated['industrial_code'] : null,
            'GROUP_' => !empty($validated['grouping_code']) ? $validated['grouping_code'] : null,
            'NPWP' => $validated['npwp'] ?? '',
            'CUST_TYPE' => $validated['sales_type'] ?? ''
        ];

        // Check if customer with this code already exists
        $existingCustomer = Customer::where('CODE', $validated['customer_code'])->first();

        try {
            if ($existingCustomer) {
                $existingCustomer->update($customerData);
                return response()->json(['success' => true, 'message' => 'Customer updated successfully']);
            }

            Customer::create($customerData);
            return response()->json(['success' => true, 'message' => 'Customer created successfully']);
        } catch (QueryException $e) {
            $msg = $e->getMessage();
            if (stripos($msg, 'customer_slm_foreign') !== false) {
                return response()->json([
                    'message' => 'Invalid salesperson code. Please select an existing salesperson.',
                    'errors' => [
                        'salesperson_code' => ['Invalid salesperson code. Please select an existing salesperson.'],
                    ],
                ], 422);
            }


            if (stripos($msg, 'customer_ind_foreign') !== false || stripos($msg, 'dbo.industry') !== false) {
                return response()->json([
                    'message' => 'Invalid industrial code. Please select an existing industry.',
                    'errors' => [
                        'industrial_code' => ['Invalid industrial code. Please select an existing industry.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_area_foreign') !== false || stripos($msg, 'dbo.GEO') !== false) {
                return response()->json([
                    'message' => 'Invalid geographical code. Please select an existing geographical area.',
                    'errors' => [
                        'geographical' => ['Invalid geographical code. Please select an existing geographical area.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_group__foreign') !== false || stripos($msg, 'dbo.CUST_GROUP') !== false) {
                return response()->json([
                    'message' => 'Invalid grouping code. Please select an existing customer group.',
                    'errors' => [
                        'grouping_code' => ['Invalid grouping code. Please select an existing customer group.'],
                    ],
                ], 422);
            }

            throw $e;
        }
    }

    public function apiIndex(Request $request)
    {
        try {
            Log::info('Fetching customers...');
            $query = Customer::query();

            // Apply search filter
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('CODE', 'like', '%' . $search . '%')
                      ->orWhere('NAME', 'like', '%' . $search . '%');
                });
            }

            // Apply status filter (defaults to "all" for UI consistency)
            $status = strtolower($request->input('status', 'all'));

            if (in_array($status, ['active', 'inactive', 'obsolete'])) {
                $query->where(function ($q) use ($status) {
                    if ($status === 'active') {
                        $q->whereIn('AC_STS', ['A', 'Active', 'Y', ''])
                          ->orWhereNull('AC_STS');
                    } elseif ($status === 'inactive') {
                        $q->whereIn('AC_STS', ['I', 'Inactive', 'N']);
                    } elseif ($status === 'obsolete') {
                        $q->where('AC_STS', 'Obsolete');
                    }
                });
            }

            // Apply sort order
            if ($request->has('sort_by')) {
                $sortBy = $request->input('sort_by');
                $query->orderBy($sortBy);
            } else {
                $query->orderBy('CODE'); // Default sort
            }

            $customers = $query->get();
            Log::info('Found ' . $customers->count() . ' customers with applied filters');

            // Transform data to ensure consistent format with expected fields
            $formatted = $customers->map(function($customer) {
                $statusMeta = $this->normalizeStatus($customer->AC_STS);
                return [
                    'id' => $customer->CODE, // Use CODE as ID
                    'customer_code' => $customer->CODE,
                    'customer_name' => $customer->NAME,
                    'short_name' => $customer->SHORT_NAME ?? '',
                    'address' => $customer->ADDRESS1 ?? '',
                    'address2' => $customer->ADDRESS2 ?? '',
                    'address3' => $customer->ADDRESS3 ?? '',
                    'contact_person' => $customer->PERSON_CONTACT ?? '',
                    'telephone_no' => $customer->TEL_NO ?? '',
                    'fax_no' => $customer->FAX_NO ?? '',
                    'co_email' => $customer->EMAIL ?? '',
                    'credit_limit' => $customer->CREDIT_LIMIT ?? 0,
                    'credit_terms' => $customer->TERM ?? 0,
                    'ac_type' => $customer->TYPE ?? 'N-Local',
                    'account_type' => $customer->TYPE ?? 'N-Local',
                    'currency_code' => $customer->CURRENCY ?? '',
                    'npwp' => $customer->NPWP ?? '',
                    'salesperson_code' => $customer->SLM ?? '',
                    'industrial_code' => $customer->IND ?? '',
                    'geographical' => $customer->AREA ?? '',
                    'grouping_code' => $customer->GROUP_ ?? '',
                    'sales_type' => $customer->CUST_TYPE ?? '',
                    'print_ar_aging' => $customer->CUST_TYPE === 'Y' ? 'Y-Yes' : 'N-No',
                    'status_raw' => $customer->AC_STS ?? '',
                    'status' => $statusMeta['label'],
                    'status_label' => $statusMeta['label'],
                    'status_category' => $statusMeta['category'],
                ];
            });

            Log::info('API response data structure check:', [
                'sample' => $formatted->first() ? json_encode($formatted->first()) : 'No data',
                'count' => $formatted->count()
            ]);

            return response()->json(['data' => $formatted]);
        } catch (\Exception $e) {
            Log::error('Error in CustomerAccount API:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Gagal mengambil data customer account: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Normalize various AC_STS representations into display label + category.
     */
    private function normalizeStatus(?string $status): array
    {
        $raw = trim((string) $status);
        $lower = strtolower($raw);

        if ($raw === '' || in_array($lower, ['a', 'active', 'y'], true)) {
            return ['label' => 'Active', 'category' => 'active'];
        }

        if (in_array($lower, ['i', 'inactive', 'n'], true)) {
            return ['label' => 'Inactive', 'category' => 'inactive'];
        }

        if ($lower === 'obsolete') {
            return ['label' => 'Obsolete', 'category' => 'inactive'];
        }

        return [
            'label' => $raw !== '' ? $raw : 'Active',
            'category' => 'other',
        ];
    }

    public function apiStore(Request $request)
    {
        try {
            Log::info('API Store Customer Request:', ['data' => $request->all()]);

            $normalizedNpwp = $this->normalizeNpwpValue($request->input('npwp'));
            if ($normalizedNpwp === false) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [
                        'npwp' => ['NPWP harus 16 digit'],
                    ],
                ], 422);
            }

            $request->merge(['npwp' => $normalizedNpwp]);

            $validated = $request->validate([
                'customer_code' => 'required|string|max:50',
                'customer_name' => 'required|string|max:250',
                'short_name' => 'nullable|string|max:50',
                'address' => 'nullable|string',
                'address2' => 'nullable|string',
                'address3' => 'nullable|string',
                'contact_person' => 'nullable|string|max:50',
                'telephone_no' => 'nullable|string|max:100',
                'fax_no' => 'nullable|string|max:50',
                'co_email' => 'nullable|string|max:50',
                'credit_limit' => 'nullable|numeric',
                'credit_terms' => 'nullable|numeric',
                'ac_type' => 'required|string|in:Y-Foreign,N-Local',
                'currency_code' => 'nullable|string|max:50',
                'npwp' => 'nullable|digits:16',
                'sales_type' => 'nullable|string|max:50',
                'salesperson_code' => 'nullable|string|max:50|exists:salesperson,Code',
                'industrial_code' => 'nullable|string|max:50|exists:industry,code',
                'geographical' => 'nullable|string|max:50|exists:GEO,CODE',
                'grouping_code' => 'nullable|string|max:50|exists:CUST_GROUP,Group_ID',
                'print_ar_aging' => 'required|string|in:Y-Yes,N-No'
            ], [
                'npwp.digits' => 'NPWP harus 16 digit',
                'salesperson_code.exists' => 'Invalid salesperson code. Please select an existing salesperson.',
                'industrial_code.exists' => 'Invalid industrial code. Please select an existing industry.',
                'geographical.exists' => 'Invalid geographical code. Please select an existing geographical area.',
                'grouping_code.exists' => 'Invalid grouping code. Please select an existing customer group.',
            ]);

            Log::info('Validated data:', $validated);

            // Additional email validation if not empty
            if (!empty($validated['co_email']) && !filter_var($validated['co_email'], FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => ['co_email' => ['The co_email field must be a valid email address.']]
                ], 422);
            }

            // Map frontend data to CUSTOMER table structure
            $customerData = [
                'CODE' => $validated['customer_code'],
                'AC_STS' => 'A', // Active status
                'NAME' => $validated['customer_name'],
                'ADDRESS1' => $validated['address'] ?? '',
                'ADDRESS2' => $validated['address2'] ?? '',
                'ADDRESS3' => $validated['address3'] ?? '',
                'PERSON_CONTACT' => $validated['contact_person'] ?? '',
                'TEL_NO' => $validated['telephone_no'] ?? '',
                'FAX_NO' => $validated['fax_no'] ?? '',
                'EMAIL' => $validated['co_email'] ?? '',
                'CREDIT_LIMIT' => $validated['credit_limit'] ?? 0,
                'TERM' => $validated['credit_terms'] ?? 0,
                'TYPE' => $validated['ac_type'],
                'CURRENCY' => $validated['currency_code'] ?? '',
                'SLM' => !empty($validated['salesperson_code']) ? $validated['salesperson_code'] : null,
                'AREA' => !empty($validated['geographical']) ? $validated['geographical'] : null,
                'IND' => !empty($validated['industrial_code']) ? $validated['industrial_code'] : null,
                'GROUP_' => !empty($validated['grouping_code']) ? $validated['grouping_code'] : null,
                'NPWP' => $validated['npwp'] ?? '',
                'CUST_TYPE' => $validated['sales_type'] ?? ''
            ];

            // Check if customer with this code already exists
            $existingCustomer = Customer::where('CODE', $validated['customer_code'])->first();

            if ($existingCustomer) {
                // Update existing customer
                Log::info('Updating existing customer:', ['code' => $existingCustomer->CODE]);
                $existingCustomer->update($customerData);
                return response()->json([
                    'message' => 'Customer updated successfully',
                    'data' => $existingCustomer
                ]);
            } else {
                // Create new customer
                Log::info('Creating new customer:', ['code' => $validated['customer_code']]);
                $newCustomer = Customer::create($customerData);
                return response()->json([
                    'message' => 'Customer created successfully',
                    'data' => $newCustomer
                ], 201);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            $msg = $e->getMessage();
            if (stripos($msg, 'customer_slm_foreign') !== false) {
                return response()->json([
                    'message' => 'Invalid salesperson code. Please select an existing salesperson.',
                    'errors' => [
                        'salesperson_code' => ['Invalid salesperson code. Please select an existing salesperson.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_ind_foreign') !== false || stripos($msg, 'dbo.industry') !== false) {
                return response()->json([
                    'message' => 'Invalid industrial code. Please select an existing industry.',
                    'errors' => [
                        'industrial_code' => ['Invalid industrial code. Please select an existing industry.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_area_foreign') !== false || stripos($msg, 'dbo.GEO') !== false) {
                return response()->json([
                    'message' => 'Invalid geographical code. Please select an existing geographical area.',
                    'errors' => [
                        'geographical' => ['Invalid geographical code. Please select an existing geographical area.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_group__foreign') !== false || stripos($msg, 'dbo.CUST_GROUP') !== false) {
                return response()->json([
                    'message' => 'Invalid grouping code. Please select an existing customer group.',
                    'errors' => [
                        'grouping_code' => ['Invalid grouping code. Please select an existing customer group.'],
                    ],
                ], 422);
            }

            Log::error('Database error saving customer account:', ['error' => $msg, 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to save customer account due to a database error.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error saving customer account:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to save customer account.'
            ], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $customer = Customer::where('CODE', $id)->firstOrFail();

            $normalizedNpwp = $this->normalizeNpwpValue($request->input('npwp'));
            if ($normalizedNpwp === false) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [
                        'npwp' => ['NPWP harus 16 digit'],
                    ],
                ], 422);
            }

            $request->merge(['npwp' => $normalizedNpwp]);

            $validated = $request->validate([
                'customer_code' => 'required|string|max:50',
                'customer_name' => 'required|string|max:250',
                'short_name' => 'nullable|string|max:50',
                'address' => 'nullable|string',
                'address2' => 'nullable|string',
                'address3' => 'nullable|string',
                'contact_person' => 'nullable|string|max:50',
                'telephone_no' => 'nullable|string|max:100',
                'fax_no' => 'nullable|string|max:50',
                'co_email' => 'nullable|string|max:50',
                'credit_limit' => 'nullable|numeric',
                'credit_terms' => 'nullable|numeric',
                'ac_type' => 'required|string|in:Y-Foreign,N-Local',
                'currency_code' => 'nullable|string|max:50',
                'npwp' => 'nullable|digits:16',
                'sales_type' => 'nullable|string|max:50',
                'salesperson_code' => 'nullable|string|max:50|exists:salesperson,Code',
                'industrial_code' => 'nullable|string|max:50|exists:industry,code',
                'geographical' => 'nullable|string|max:50|exists:GEO,CODE',
                'grouping_code' => 'nullable|string|max:50|exists:CUST_GROUP,Group_ID',
                'print_ar_aging' => 'required|string|in:Y-Yes,N-No'
            ], [
                'npwp.digits' => 'NPWP harus 16 digit',
                'salesperson_code.exists' => 'Invalid salesperson code. Please select an existing salesperson.',
                'industrial_code.exists' => 'Invalid industrial code. Please select an existing industry.',
                'geographical.exists' => 'Invalid geographical code. Please select an existing geographical area.',
                'grouping_code.exists' => 'Invalid grouping code. Please select an existing customer group.',
            ]);

            $customerData = [
                'CODE' => $validated['customer_code'],
                'AC_STS' => 'A',
                'NAME' => $validated['customer_name'],
                'SHORT_NAME' => $validated['short_name'] ?? '',
                'ADDRESS1' => $validated['address'] ?? '',
                'ADDRESS2' => $validated['address2'] ?? '',
                'ADDRESS3' => $validated['address3'] ?? '',
                'PERSON_CONTACT' => $validated['contact_person'] ?? '',
                'TEL_NO' => $validated['telephone_no'] ?? '',
                'FAX_NO' => $validated['fax_no'] ?? '',
                'EMAIL' => $validated['co_email'] ?? '',
                'CREDIT_LIMIT' => $validated['credit_limit'] ?? 0,
                'TERM' => $validated['credit_terms'] ?? 0,
                'TYPE' => $validated['ac_type'],
                'CURRENCY' => $validated['currency_code'] ?? '',
                'SLM' => !empty($validated['salesperson_code']) ? $validated['salesperson_code'] : null,
                'AREA' => !empty($validated['geographical']) ? $validated['geographical'] : null,
                'IND' => !empty($validated['industrial_code']) ? $validated['industrial_code'] : null,
                'GROUP_' => !empty($validated['grouping_code']) ? $validated['grouping_code'] : null,
                'NPWP' => $validated['npwp'] ?? '',
                'CUST_TYPE' => $validated['sales_type'] ?? ''
            ];

            $customer->update($customerData);
            return response()->json($customer);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            $msg = $e->getMessage();
            if (stripos($msg, 'customer_slm_foreign') !== false) {
                return response()->json([
                    'message' => 'Invalid salesperson code. Please select an existing salesperson.',
                    'errors' => [
                        'salesperson_code' => ['Invalid salesperson code. Please select an existing salesperson.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_ind_foreign') !== false || stripos($msg, 'dbo.industry') !== false) {
                return response()->json([
                    'message' => 'Invalid industrial code. Please select an existing industry.',
                    'errors' => [
                        'industrial_code' => ['Invalid industrial code. Please select an existing industry.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_area_foreign') !== false || stripos($msg, 'dbo.GEO') !== false) {
                return response()->json([
                    'message' => 'Invalid geographical code. Please select an existing geographical area.',
                    'errors' => [
                        'geographical' => ['Invalid geographical code. Please select an existing geographical area.'],
                    ],
                ], 422);
            }

            if (stripos($msg, 'customer_group__foreign') !== false || stripos($msg, 'dbo.CUST_GROUP') !== false) {
                return response()->json([
                    'message' => 'Invalid grouping code. Please select an existing customer group.',
                    'errors' => [
                        'grouping_code' => ['Invalid grouping code. Please select an existing customer group.'],
                    ],
                ], 422);
            }

            Log::error('Database error updating customer account:', ['error' => $msg, 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to save customer account due to a database error.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error updating customer account:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to save customer account.'
            ], 500);
        }
    }

    /**
     * Get a single customer account by ID
     */
    public function apiShow($id)
    {
        try {
            Log::info('Fetching single customer', ['id' => $id]);
            $customer = Customer::where('CODE', $id)->firstOrFail();

            Log::info('Found customer', ['customer_code' => $customer->CODE]);

            // Transform data to match frontend expectations
            $formatted = [
                'id' => $customer->CODE,
                'customer_code' => $customer->CODE,
                'customer_name' => $customer->NAME,
                'short_name' => $customer->SHORT_NAME ?? '',
                'address' => $customer->ADDRESS1 ?? '',
                'address2' => $customer->ADDRESS2 ?? '',
                'address3' => $customer->ADDRESS3 ?? '',
                'contact_person' => $customer->PERSON_CONTACT ?? '',
                'telephone_no' => $customer->TEL_NO ?? '',
                'fax_no' => $customer->FAX_NO ?? '',
                'co_email' => $customer->EMAIL ?? '',
                'credit_limit' => $customer->CREDIT_LIMIT ?? 0,
                'credit_terms' => $customer->TERM ?? 0,
                'ac_type' => $customer->TYPE ?? 'N-Local',
                'account_type' => $customer->TYPE ?? 'N-Local',
                'currency_code' => $customer->CURRENCY ?? '',
                'npwp' => $customer->NPWP ?? '',
                'salesperson_code' => $customer->SLM ?? '',
                'industrial_code' => $customer->IND ?? '',
                'geographical' => $customer->AREA ?? '',
                'grouping_code' => $customer->GROUP_ ?? '',
                'sales_type' => $customer->CUST_TYPE ?? '',
                'print_ar_aging' => $customer->CUST_TYPE === 'Y' ? 'Y-Yes' : 'N-No',
                'status' => $customer->AC_STS ?? 'A'
            ];

            return response()->json($formatted);
        } catch (\Exception $e) {
            Log::error('Error fetching customer', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Customer not found: ' . $e->getMessage()
            ], 404);
        }
    }

    public function apiIndexAcAutoWoCustomers()
    {
        // For demonstration, returning dummy data
        $dummyCustomers = [
            ['customer_code' => 'CUST001', 'customer_name' => 'Customer A Ltd.', 'status' => 'Active'],
            ['customer_code' => 'CUST002', 'customer_name' => 'Customer B Corp.', 'status' => 'Inactive'],
            ['customer_code' => 'CUST003', 'customer_name' => 'Customer C Inc.', 'status' => 'Active'],
            ['customer_code' => 'CUST004', 'customer_name' => 'Customer D LLC', 'status' => 'Active'],
            ['customer_code' => 'CUST005', 'customer_name' => 'Customer E Group', 'status' => 'Inactive'],
        ];

        return response()->json($dummyCustomers);
    }

    public function updateStatus(Request $request, $customer_code)
    {
        try {
            $request->validate([
                'active' => 'required|in:Y,N',
                'reason' => 'required|string|max:255'
            ]);

            $customerAccount = Customer::where('CODE', $customer_code)->first();

            if (!$customerAccount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer account not found'
                ], 404);
            }

            // Get current user name, or use 'System'
            $username = 'System';
            if (request()->user()) {
                $username = request()->user()->name;
            }

            // Update AC_STS (Account Status) - correct column name in CUSTOMER table
            $customerAccount->AC_STS = $request->active === 'Y' ? 'Active' : 'Inactive';
            $customerAccount->save();

            // Log status change with reason
            Log::info('Customer account status changed', [
                'customer_code' => $customer_code,
                'customer_name' => $customerAccount->NAME,
                'new_status' => $request->active === 'Y' ? 'Active' : 'Inactive',
                'reason' => $request->reason,
                'changed_by' => $username
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer account status updated successfully',
                'customer' => $customerAccount
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating customer account status', [
                'customer_code' => $customer_code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating customer account status: ' . $e->getMessage()
            ], 500);
        }
    }
}
