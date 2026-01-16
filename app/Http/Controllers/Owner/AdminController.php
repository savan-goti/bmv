<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class AdminController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.admins.index');
    }

    public function ajaxData()
    {
        $result = Admin::query();
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $viewUrl = route('owner.admins.show', $row->id);
                $editUrl = route('owner.admins.edit', $row->id);
                $deleteUrl = route('owner.admins.destroy', $row->id);
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
            ->editColumn('role', function($row){
                return ucfirst($row->role);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $jobPositions = JobPosition::where('status', 'active')->get();
        return view('owner.admins.create', compact('jobPositions'));
    }

    public function show(Admin $admin)
    {
        $admin->load('jobPosition', 'branchPositions.branch', 'branchPositions.jobPosition');
        return view('owner.admins.show', compact('admin'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'owner_id' => 'required',
                'name' => 'required|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:admins,email',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',

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
                $validatedData['profile_image'] = uploadImgFile($request->profile_image, ADMIN_PROFILE_IMAGE_PATH);
            }

            $validatedData['password'] = Hash::make($validatedData['password']);

            $admin = Admin::create($validatedData);

            DB::commit();
            return $this->sendResponse('Admin created successfully.', $admin);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Admin $admin)
    {
        $jobPositions = JobPosition::where('status', 'active')->get();
        return view('owner.admins.edit', compact('admin', 'jobPositions'));
    }

    public function update(Request $request, Admin $admin)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'email' => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'phone' => 'nullable|string|max:20',

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
                if ($admin->profile_image) {
                    deleteImage($admin->profile_image, ADMIN_PROFILE_IMAGE_PATH);
                }
                $validatedData['profile_image'] = uploadImgFile($request->profile_image, ADMIN_PROFILE_IMAGE_PATH);
            } else {
                unset($validatedData['profile_image']);
            }

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']); // Do not update password if it's empty
            }

            $admin->update($validatedData);

            DB::commit();
            return $this->sendResponse('Admin updated successfully.', $admin);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return $this->sendSuccess('Admin deleted successfully.');
    }

    public function status(Request $request, Admin $admin)
    {
        $admin->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
