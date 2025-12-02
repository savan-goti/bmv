<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SellerManagement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

use Yajra\DataTables\Facades\DataTables;

class SellerController extends Controller
{
    use \App\Http\Traits\ResponseTrait;

    public function index()
    {
        return view('admin.sellers.index');
    }

    public function ajaxData()
    {
        $admin = Auth::guard('admin')->user();
        
        // Filter sellers created by this admin
        $result = Seller::whereHas('managementRecords', function($query) use ($admin) {
            $query->where('created_by_type', get_class($admin))
                  ->where('created_by_id', $admin->id)
                  ->where('action', 'created');
        });
        
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $viewUrl = route('admin.sellers.show', $row->id);
                $editUrl = route('admin.sellers.edit', $row->id);
                $deleteUrl = route('admin.sellers.destroy', $row->id);
                $approveUrl = route('admin.sellers.approve', $row->id);
                
                $btn = '<a href="'.$viewUrl.'" class="btn btn-sm btn-primary me-1"><i class="bx bx-show"></i> View</a>';
                $btn .= '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                
                if(!$row->is_approved) {
                    $btn .= '<form action="'.$approveUrl.'" method="POST" class="d-inline me-1">
                                '.csrf_field().'
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>';
                    $btn .= '<form action="'.$approveUrl.'" method="POST" class="d-inline me-1">
                                '.csrf_field().'
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn btn-sm btn-warning">Reject</button>
                            </form>';
                }

                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $status = ucfirst($row->status);
                $badgeClass = match($row->status) {
                    'active' => 'success',
                    'pending' => 'warning',
                    'suspended', 'rejected' => 'danger',
                    default => 'secondary'
                };
                return '<span class="badge bg-'.$badgeClass.'">'.$status.'</span>';
            })
            ->editColumn('is_approved', function($row){
                return $row->is_approved ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>';
            })
            ->rawColumns(['action', 'status', 'is_approved'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('admin.sellers.create', compact('categories'));
    }

    public function show(Seller $seller)
    {
        return view('admin.sellers.show', compact('seller'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'business_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'email' => 'required|email|unique:sellers,email',
                'phone' => 'required|string|unique:sellers,phone',
                'password' => 'required|string|min:8|confirmed',
                'business_logo' => 'nullable|image|max:2048',
                'business_type' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'sub_category_id' => 'nullable|exists:sub_categories,id',
                'username' => 'nullable|string|unique:sellers,username',
                'bar_code' => 'nullable|string',
                'store_link' => 'nullable|string|unique:sellers,store_link',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|string',
                'aadhar_number' => 'nullable|string',
                'pancard_number' => 'nullable|string',
                'gst_number' => 'nullable|string',
                'aadhaar_front' => 'nullable|image|max:2048',
                'aadhaar_back' => 'nullable|image|max:2048',
                'pancard_image' => 'nullable|image|max:2048',
                'gst_certificate_image' => 'nullable|image|max:2048',
                'account_holder_name' => 'nullable|string',
                'bank_name' => 'nullable|string',
                'bank_account_number' => 'nullable|string',
                'ifsc_code' => 'nullable|string',
                'cancel_check_image' => 'nullable|image|max:2048',
                'kyc_document' => 'nullable|file|max:2048',
                'country' => 'nullable|string',
                'city' => 'nullable|string',
                'state' => 'nullable|string',
                'pincode' => 'nullable|string',
                'address' => 'nullable|string',
                'social_links' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            $validatedData['password'] = Hash::make($validatedData['password']);
            $validatedData['status'] = 'pending';
            $validatedData['is_approved'] = false;

            // Handle File Uploads
            $files = ['business_logo', 'aadhaar_front', 'aadhaar_back', 'pancard_image', 'gst_certificate_image', 'cancel_check_image', 'kyc_document'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $validatedData[$file] = uploadFile($request->file($file), SELLER_DOCUMENT_PATH);
                }
            }

            $seller = Seller::create($validatedData);

            // Create seller management record to track who created this seller
            SellerManagement::create([
                'seller_id' => $seller->id,
                'created_by_type' => get_class(Auth::guard('admin')->user()),
                'created_by_id' => Auth::guard('admin')->user()->id,
                'action' => 'created',
                'notes' => 'Admin created a new seller',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return $this->sendResponse('Seller created successfully.', $seller);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Seller $seller)
    {
        $categories = Category::where('status', 'active')->get();
        return view('admin.sellers.edit', compact('seller', 'categories'));
    }

    public function update(Request $request, Seller $seller)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'business_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('sellers')->ignore($seller->id)],
                'phone' => ['required', 'string', Rule::unique('sellers')->ignore($seller->id)],
                'password' => 'nullable|string|min:8|confirmed',
                'business_logo' => 'nullable|image|max:2048',
                'business_type' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'sub_category_id' => 'nullable|exists:sub_categories,id',
                'username' => ['nullable', 'string', Rule::unique('sellers')->ignore($seller->id)],
                'bar_code' => 'nullable|string',
                'store_link' => ['nullable', 'string', Rule::unique('sellers')->ignore($seller->id)],
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|string',
                'aadhar_number' => 'nullable|string',
                'pancard_number' => 'nullable|string',
                'gst_number' => 'nullable|string',
                'aadhaar_front' => 'nullable|image|max:2048',
                'aadhaar_back' => 'nullable|image|max:2048',
                'pancard_image' => 'nullable|image|max:2048',
                'gst_certificate_image' => 'nullable|image|max:2048',
                'account_holder_name' => 'nullable|string',
                'bank_name' => 'nullable|string',
                'bank_account_number' => 'nullable|string',
                'ifsc_code' => 'nullable|string',
                'cancel_check_image' => 'nullable|image|max:2048',
                'kyc_document' => 'nullable|file|max:2048',
                'country' => 'nullable|string',
                'city' => 'nullable|string',
                'state' => 'nullable|string',
                'pincode' => 'nullable|string',
                'address' => 'nullable|string',
                'social_links' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            // Handle File Uploads
            $files = ['business_logo', 'aadhaar_front', 'aadhaar_back', 'pancard_image', 'gst_certificate_image', 'cancel_check_image', 'kyc_document'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    // Delete old file
                    if ($seller->$file) {
                        deleteImage($seller->$file,SELLER_DOCUMENT_PATH);
                    }
                    $validatedData[$file] = uploadFile($request->file($file), SELLER_DOCUMENT_PATH);
                }
            }

            $seller->update($validatedData);

            DB::commit();
            return $this->sendResponse('Seller updated successfully.', $seller);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();
        return $this->sendSuccess('Seller deleted successfully.');
    }

    public function status(Request $request, Seller $seller)
    {
        $seller->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function approve(Request $request, Seller $seller)
    {
        $action = $request->action; // 'approve' or 'reject'
        
        if ($action == 'approve') {
            $seller->update([
                'approved_by_id' => Auth::guard('admin')->user()->id,
                'approved_by_type' => get_class(Auth::guard('admin')->user()),
                'is_approved' => true,
                'approved_at' => now(),
                'status' => 'active'
            ]);
            // Send email notification here
        } elseif ($action == 'reject') {
            $seller->update([
                'approved_by_id' => Auth::guard('admin')->user()->id,
                'approved_by_type' => get_class(Auth::guard('admin')->user()),
                'is_approved' => false,
                'status' => 'suspended' // or rejected
            ]);
            // Send email notification here
        }

        return redirect()->back()->with('success', 'Seller ' . $action . 'd successfully.');
    }
}
