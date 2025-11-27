<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BranchPosition;
use App\Models\Branch;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class BranchPositionController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $branchId = $request->get('branch_id');
        $branch = null;
        
        if ($branchId) {
            $branch = Branch::findOrFail($branchId);
        }
        
        return view('owner.branch_positions.index', compact('branch'));
    }

    public function ajaxData(Request $request)
    {
        $query = BranchPosition::with(['branch', 'jobPosition', 'positionable']);
        
        if ($request->has('branch_id') && $request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        
        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('person_name', function($row){
                return $row->positionable ? $row->positionable->name : 'N/A';
            })
            ->addColumn('person_type', function($row){
                return class_basename($row->positionable_type);
            })
            ->addColumn('branch_name', function($row){
                return $row->branch ? $row->branch->name : 'N/A';
            })
            ->addColumn('job_position_name', function($row){
                return $row->jobPosition ? $row->jobPosition->name : 'N/A';
            })
            ->addColumn('action', function($row){
                $viewUrl = route('owner.branch-positions.show', $row->id);
                $editUrl = route('owner.branch-positions.edit', $row->id);
                $deleteUrl = route('owner.branch-positions.destroy', $row->id);
                $btn = '<a href="'.$viewUrl.'" class="btn btn-sm btn-success me-1"><i class="bx bx-show"></i> View</a>';
                $btn .= '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('is_active', function($row){
                $status = $row->is_active ? 'Active' : 'Inactive';
                $badgeClass = $row->is_active ? 'success' : 'danger';
                return '<span class="badge bg-'.$badgeClass.'">'.$status.'</span>';
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $branchId = $request->get('branch_id');
        $branch = null;
        
        if ($branchId) {
            $branch = Branch::findOrFail($branchId);
        }
        
        $branches = Branch::where('status', 'active')->get();
        $jobPositions = JobPosition::where('status', 'active')->get();
        
        // Get admins who don't have an active position
        $admins = Admin::where('status', 'active')
            ->whereDoesntHave('branchPositions', function($query) {
                $query->where('is_active', true);
            })
            ->get();
        
        // Get staff who don't have an active position
        $staffs = Staff::where('status', 'active')
            ->whereDoesntHave('branchPositions', function($query) {
                $query->where('is_active', true);
            })
            ->get();
        
        return view('owner.branch_positions.create', compact('branches', 'jobPositions', 'admins', 'staffs', 'branch'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'branch_id' => 'required|exists:branches,id',
                'person_type' => 'required|in:Admin,Staff',
                'person_id' => 'required',
                'job_position_id' => 'required|exists:job_positions,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'salary' => 'nullable|numeric|min:0',
                'is_active' => 'required|boolean',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            // Set polymorphic type and id
            $positionableType = 'App\\Models\\' . $validated['person_type'];
            $positionableId = $validated['person_id'];
            
            // Verify the person exists
            if ($validated['person_type'] === 'Admin') {
                Admin::findOrFail($positionableId);
            } else {
                Staff::findOrFail($positionableId);
            }

            // Check if person already has an active position
            if ($validated['is_active']) {
                $existingActivePosition = BranchPosition::where('positionable_type', $positionableType)
                    ->where('positionable_id', $positionableId)
                    ->where('is_active', true)
                    ->exists();

                if ($existingActivePosition) {
                    return $this->sendError('This person already has an active position. Please deactivate their current position first or set this position as inactive.', 422);
                }
            }

            $branchPosition = BranchPosition::create([
                'branch_id' => $validated['branch_id'],
                'positionable_type' => $positionableType,
                'positionable_id' => $positionableId,
                'job_position_id' => $validated['job_position_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'salary' => $validated['salary'] ?? null,
                'is_active' => $validated['is_active'],
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();
            return $this->sendResponse('Branch Position created successfully.', $branchPosition);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function show(BranchPosition $branchPosition)
    {
        $branchPosition->load(['branch', 'jobPosition', 'positionable']);
        return view('owner.branch_positions.show', compact('branchPosition'));
    }

    public function edit(BranchPosition $branchPosition)
    {
        $branchPosition->load(['branch', 'jobPosition', 'positionable']);
        
        $branches = Branch::where('status', 'active')->get();
        $jobPositions = JobPosition::where('status', 'active')->get();
        
        // Get admins who don't have an active position OR are the current person
        $admins = Admin::where('status', 'active')
            ->where(function($query) use ($branchPosition) {
                $query->whereDoesntHave('branchPositions', function($q) {
                    $q->where('is_active', true);
                })
                ->orWhere('id', $branchPosition->positionable_type === 'App\Models\Admin' ? $branchPosition->positionable_id : null);
            })
            ->get();
        
        // Get staff who don't have an active position OR are the current person
        $staffs = Staff::where('status', 'active')
            ->where(function($query) use ($branchPosition) {
                $query->whereDoesntHave('branchPositions', function($q) {
                    $q->where('is_active', true);
                })
                ->orWhere('id', $branchPosition->positionable_type === 'App\Models\Staff' ? $branchPosition->positionable_id : null);
            })
            ->get();
        
        return view('owner.branch_positions.edit', compact('branchPosition', 'branches', 'jobPositions', 'admins', 'staffs'));
    }

    public function update(Request $request, BranchPosition $branchPosition)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'branch_id' => 'required|exists:branches,id',
                'person_type' => 'required|in:Admin,Staff',
                'person_id' => 'required',
                'job_position_id' => 'required|exists:job_positions,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'salary' => 'nullable|numeric|min:0',
                'is_active' => 'required|boolean',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            // Set polymorphic type and id
            $positionableType = 'App\\Models\\' . $validated['person_type'];
            $positionableId = $validated['person_id'];
            
            // Verify the person exists
            if ($validated['person_type'] === 'Admin') {
                Admin::findOrFail($positionableId);
            } else {
                Staff::findOrFail($positionableId);
            }

            // Check if person already has an active position (excluding current position)
            if ($validated['is_active']) {
                $existingActivePosition = BranchPosition::where('positionable_type', $positionableType)
                    ->where('positionable_id', $positionableId)
                    ->where('is_active', true)
                    ->where('id', '!=', $branchPosition->id)
                    ->exists();

                if ($existingActivePosition) {
                    return $this->sendError('This person already has an active position. Please deactivate their current position first or set this position as inactive.', 422);
                }
            }

            $branchPosition->update([
                'branch_id' => $validated['branch_id'],
                'positionable_type' => $positionableType,
                'positionable_id' => $positionableId,
                'job_position_id' => $validated['job_position_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'salary' => $validated['salary'] ?? null,
                'is_active' => $validated['is_active'],
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();
            return $this->sendResponse('Branch Position updated successfully.', $branchPosition);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(BranchPosition $branchPosition)
    {
        $branchPosition->delete();
        return $this->sendSuccess('Branch Position deleted successfully.');
    }

    public function status(Request $request, BranchPosition $branchPosition)
    {
        $branchPosition->update(['is_active' => $request->is_active]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
