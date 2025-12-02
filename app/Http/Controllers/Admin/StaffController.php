<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Admin;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    use \App\Http\Traits\ResponseTrait;

    public function index()
    {
        return view('admin.staffs.index');
    }

    public function ajaxData()
    {
        $result = Staff::whereHas('admin');
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $viewUrl = route('admin.staffs.show', $row->id);
                $editUrl = route('admin.staffs.edit', $row->id);
                $deleteUrl = route('admin.staffs.destroy', $row->id);
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
            ->editColumn('assigned_role', function($row){
                return ucfirst($row->assigned_role);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $admins = Admin::where('status', 'active')->get();
        $jobPositions = JobPosition::where('status', 'active')->get();
        return view('admin.staffs.create', compact('admins', 'jobPositions'));
    }

    public function show(Staff $staff)
    {
        $staff->load('admin', 'jobPosition', 'branchPositions.branch', 'branchPositions.jobPosition');
        return view('admin.staffs.show', compact('staff'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:staffs,email',
                'phone' => 'nullable|string|unique:staffs,phone',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'password' => 'required|string|min:8|confirmed',
                'assigned_role' => 'required|string',
                'permissions' => 'nullable|array',
                'education' => 'nullable|string|max:255',
                'position_id' => 'nullable|exists:job_positions,id',
                'address' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'resignation_date' => 'nullable|date',
                'purpose' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            if ($request->hasFile('profile_image')) {
                $validatedData['profile_image'] = uploadImgFile($request->profile_image, STAFF_PROFILE_IMAGE_PATH);
            }

            $validatedData['password'] = Hash::make($validatedData['password']);
            $validatedData['admin_id'] = Auth::guard('admin')->user()->id;

            $staff = Staff::create($validatedData);

            DB::commit();
            return $this->sendResponse('Staff created successfully.', $staff);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Staff $staff)
    {
        $admins = Admin::where('status', 'active')->get();
        $jobPositions = JobPosition::where('status', 'active')->get();
        return view('admin.staffs.edit', compact('staff', 'admins', 'jobPositions'));
    }

    public function update(Request $request, Staff $staff)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'email' => ['required', 'email', Rule::unique('staffs')->ignore($staff->id)],
                'phone' => ['nullable', 'string', Rule::unique('staffs')->ignore($staff->id)],
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'assigned_role' => 'required|string',
                'permissions' => 'nullable|array',
                'education' => 'nullable|string|max:255',
                'position_id' => 'nullable|exists:job_positions,id',
                'address' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'password' => 'nullable|string|min:8|confirmed',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'resignation_date' => 'nullable|date',
                'purpose' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            if ($request->hasFile('profile_image')) {
                if ($staff->profile_image) {
                    deleteImage($staff->profile_image, STAFF_PROFILE_IMAGE_PATH);
                }
                $validatedData['profile_image'] = uploadImgFile($request->profile_image, STAFF_PROFILE_IMAGE_PATH);
            } else {
                unset($validatedData['profile_image']);
            }

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $staff->update($validatedData);

            DB::commit();
            return $this->sendResponse('Staff updated successfully.', $staff);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return $this->sendSuccess('Staff deleted successfully.');
    }

    public function status(Request $request, Staff $staff)
    {
        $staff->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
