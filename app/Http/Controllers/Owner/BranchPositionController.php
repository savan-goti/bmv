<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BranchPosition;
use App\Models\Branch;
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
        $query = BranchPosition::with(['branch', 'jobPosition']);
        
        if ($request->has('branch_id') && $request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        
        return DataTables::eloquent($query)
            ->addIndexColumn()
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
        
        return view('owner.branch_positions.create', compact('branches', 'jobPositions', 'branch'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'branch_id' => 'required|exists:branches,id',
                'job_position_id' => 'required|exists:job_positions,id',
                'is_active' => 'required|boolean',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $branchPosition = BranchPosition::create([
                'branch_id' => $validated['branch_id'],
                'job_position_id' => $validated['job_position_id'],
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
        $branchPosition->load(['branch', 'jobPosition']);
        return view('owner.branch_positions.show', compact('branchPosition'));
    }

    public function edit(BranchPosition $branchPosition)
    {
        $branchPosition->load(['branch', 'jobPosition']);
        
        $branches = Branch::where('status', 'active')->get();
        $jobPositions = JobPosition::where('status', 'active')->get();
        
        return view('owner.branch_positions.edit', compact('branchPosition', 'branches', 'jobPositions'));
    }

    public function update(Request $request, BranchPosition $branchPosition)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'branch_id' => 'required|exists:branches,id',
                'job_position_id' => 'required|exists:job_positions,id',
                'is_active' => 'required|boolean',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $branchPosition->update([
                'branch_id' => $validated['branch_id'],
                'job_position_id' => $validated['job_position_id'],
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
