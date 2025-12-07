<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTeamMember;
use App\Models\SupportDepartment;
use App\Models\SupportQueue;
use App\Models\SupportAuditLog;
use App\Enums\SupportRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class SupportTeamController extends Controller
{
    use \App\Http\Traits\ResponseTrait;

    public function __construct()
    {
        // You can add authorization middleware here if needed
    }

    public function index()
    {
        return view('admin.support-team.index');
    }

    public function ajaxData()
    {
        $result = SupportTeamMember::query();
        
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $viewUrl = route('admin.support-team.show', $row->id);
                $editUrl = route('admin.support-team.edit', $row->id);
                $deleteUrl = route('admin.support-team.destroy', $row->id);
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
                return $row->role->label();
            })
            ->editColumn('notification_method', function($row){
                return ucfirst(str_replace('_', ' ', $row->notification_method));
            })
            ->addColumn('departments_count', function($row){
                return $row->departments ? count($row->departments) : 0;
            })
            ->addColumn('queues_count', function($row){
                return $row->default_queues ? count($row->default_queues) : 0;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $roles = SupportRole::options();
        $departments = SupportDepartment::active()->get();
        $queues = SupportQueue::active()->get();
        
        return view('admin.support-team.create', compact('roles', 'departments', 'queues'));
    }

    public function show(SupportTeamMember $supportTeamMember)
    {
        $supportTeamMember->load('auditLogs');
        $departments = $supportTeamMember->departmentRecords();
        $queues = $supportTeamMember->queueRecords();
        
        return view('admin.support-team.show', compact('supportTeamMember', 'departments', 'queues'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:support_team_members,email',
                'phone' => 'nullable|string|unique:support_team_members,phone',
                'password' => 'required|string|min:8|confirmed',
                'role' => ['required', Rule::in(SupportRole::values())],
                'departments' => 'nullable|array',
                'departments.*' => 'exists:support_departments,id',
                'default_queues' => 'nullable|array',
                'default_queues.*' => 'exists:support_queues,id',
                'status' => 'required|in:active,disabled',
                'notification_method' => 'required|in:email,in_app,both',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/support_team'), $imageName);
                $validatedData['profile_image'] = $imageName;
            }

            $validatedData['password'] = Hash::make($validatedData['password']);
            $validatedData['created_by_id'] = Auth::guard('admin')->user()->id;
            $validatedData['created_by_type'] = 'App\Models\Admin';

            $supportTeamMember = SupportTeamMember::create($validatedData);

            // Log the creation
            SupportAuditLog::log(
                $supportTeamMember->id,
                'created',
                'Support team member created',
                null,
                $validatedData,
                Auth::guard('admin')->user()
            );

            DB::commit();
            return $this->sendResponse('Support team member created successfully.', $supportTeamMember);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(SupportTeamMember $supportTeamMember)
    {
        $roles = SupportRole::options();
        $departments = SupportDepartment::active()->get();
        $queues = SupportQueue::active()->get();
        
        return view('admin.support-team.edit', compact('supportTeamMember', 'roles', 'departments', 'queues'));
    }

    public function update(Request $request, SupportTeamMember $supportTeamMember)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('support_team_members')->ignore($supportTeamMember->id)],
                'phone' => ['nullable', 'string', Rule::unique('support_team_members')->ignore($supportTeamMember->id)],
                'password' => 'nullable|string|min:8|confirmed',
                'role' => ['required', Rule::in(SupportRole::values())],
                'departments' => 'nullable|array',
                'departments.*' => 'exists:support_departments,id',
                'default_queues' => 'nullable|array',
                'default_queues.*' => 'exists:support_queues,id',
                'status' => 'required|in:active,disabled',
                'notification_method' => 'required|in:email,in_app,both',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $oldValues = $supportTeamMember->toArray();
            $validatedData = $validator->validated();

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($supportTeamMember->getRawOriginal('profile_image')) {
                    $oldImagePath = public_path('uploads/support_team/') . $supportTeamMember->getRawOriginal('profile_image');
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $image = $request->file('profile_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/support_team'), $imageName);
                $validatedData['profile_image'] = $imageName;
            } else {
                unset($validatedData['profile_image']);
            }

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $supportTeamMember->update($validatedData);

            // Log the update
            SupportAuditLog::log(
                $supportTeamMember->id,
                'updated',
                'Support team member updated',
                $oldValues,
                $validatedData,
                Auth::guard('admin')->user()
            );

            DB::commit();
            return $this->sendResponse('Support team member updated successfully.', $supportTeamMember);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(SupportTeamMember $supportTeamMember)
    {
        try {
            $oldValues = $supportTeamMember->toArray();
            
            $supportTeamMember->delete();

            // Log the deletion
            SupportAuditLog::log(
                $supportTeamMember->id,
                'deleted',
                'Support team member deleted',
                $oldValues,
                null,
                Auth::guard('admin')->user()
            );

            return $this->sendSuccess('Support team member deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function status(Request $request, SupportTeamMember $support_team)
    {
        try {
            $oldStatus = $support_team->status;
            
            $support_team->update(['status' => $request->status]);

            // Log the status change
            SupportAuditLog::log(
                $support_team->id,
                'status_changed',
                "Status changed from {$oldStatus} to {$request->status}",
                ['status' => $oldStatus],
                ['status' => $request->status],
                Auth::guard('admin')->user()
            );

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
