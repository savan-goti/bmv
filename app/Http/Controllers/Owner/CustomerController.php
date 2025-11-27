<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    use \App\Http\Traits\ResponseTrait;

    public function index()
    {
        return view('owner.customers.index');
    }

    public function ajaxData()
    {
        $result = Customer::query();
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $viewUrl = route('owner.customers.show', $row->id);
                $editUrl = route('owner.customers.edit', $row->id);
                $deleteUrl = route('owner.customers.destroy', $row->id);
                $btn = '<a href="'.$viewUrl.'" class="btn btn-sm btn-primary me-1"><i class="bx bx-show"></i> View</a>';
                $btn .= '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $status = ucfirst($row->status);
                $badgeClass = $row->status == 'active' ? 'success' : 'danger';
                return '<span class="badge bg-'.$badgeClass.'">'.$status.'</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('owner.customers.create');
    }

    public function show(Customer $customer)
    {
        return view('owner.customers.show', compact('customer'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'username' => 'nullable|string|unique:customers,username|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'nullable|string|unique:customers,phone',
                'country_code' => 'nullable|string|max:10',
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date',
                'address' => 'nullable|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'pincode' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:active,blocked',
                // Social Links
                'whatsapp' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'telegram' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/customers'), $imageName);
                $validatedData['profile_image'] = 'uploads/customers/' . $imageName;
            }

            // Generate canonical URL if username is provided
            if (!empty($validatedData['username'])) {
                $validatedData['canonical'] = url('/customer/' . $validatedData['username']);
            }

            // Handle social links as JSON
            $socialLinks = [];
            $socialFields = ['whatsapp', 'website', 'facebook', 'instagram', 'linkedin', 'youtube', 'telegram', 'twitter'];
            foreach ($socialFields as $field) {
                if (!empty($validatedData[$field])) {
                    $socialLinks[$field] = $validatedData[$field];
                    unset($validatedData[$field]);
                }
            }
            if (!empty($socialLinks)) {
                $validatedData['social_links'] = $socialLinks;
            }

            $validatedData['password'] = Hash::make($validatedData['password']);

            $customer = Customer::create($validatedData);

            DB::commit();
            return $this->sendResponse('Customer created successfully.', $customer);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Customer $customer)
    {
        return view('owner.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'username' => ['nullable', 'string', 'max:255', Rule::unique('customers')->ignore($customer->id)],
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('customers')->ignore($customer->id)],
                'phone' => ['nullable', 'string', Rule::unique('customers')->ignore($customer->id)],
                'country_code' => 'nullable|string|max:10',
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date',
                'address' => 'nullable|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'pincode' => 'nullable|string|max:20',
                'status' => 'required|in:active,blocked',
                'password' => 'nullable|string|min:8|confirmed',
                // Social Links
                'whatsapp' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'telegram' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($customer->profile_image && file_exists(public_path($customer->profile_image))) {
                    unlink(public_path($customer->profile_image));
                }
                
                $image = $request->file('profile_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/customers'), $imageName);
                $validatedData['profile_image'] = 'uploads/customers/' . $imageName;
            }

            // Generate canonical URL if username is provided
            if (!empty($validatedData['username'])) {
                $validatedData['canonical'] = url('/customer/' . $validatedData['username']);
            }

            // Handle social links as JSON
            $socialLinks = $customer->social_links ?? [];
            $socialFields = ['whatsapp', 'website', 'facebook', 'instagram', 'linkedin', 'youtube', 'telegram', 'twitter'];
            foreach ($socialFields as $field) {
                if (isset($validatedData[$field])) {
                    if (!empty($validatedData[$field])) {
                        $socialLinks[$field] = $validatedData[$field];
                    } else {
                        unset($socialLinks[$field]);
                    }
                    unset($validatedData[$field]);
                }
            }
            if (!empty($socialLinks)) {
                $validatedData['social_links'] = $socialLinks;
            }

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $customer->update($validatedData);

            DB::commit();
            return $this->sendResponse('Customer updated successfully.', $customer);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return $this->sendSuccess('Customer deleted successfully.');
    }

    public function status(Request $request, Customer $customer)
    {
        $customer->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
