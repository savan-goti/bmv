<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class BranchController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.branches.index');
    }

    public function ajaxData()
    {
        $result = Branch::query();
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $viewUrl = route('owner.branches.show', $row->id);
                $editUrl = route('owner.branches.edit', $row->id);
                $deleteUrl = route('owner.branches.destroy', $row->id);
                $btn = '<a href="'.$viewUrl.'" class="btn btn-sm btn-success me-1"><i class="bx bx-show"></i> View</a>';
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
        return view('owner.branches.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'owner_id' => 'required',
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:branches,code',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:20',
                'manager_name' => 'nullable|string|max:255',
                'manager_phone' => 'nullable|string|max:20',
                'opening_date' => 'nullable|date',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $branch = Branch::create($validated);

            DB::commit();
            return $this->sendResponse('Branch created successfully.', $branch);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function show(Branch $branch)
    {
        $branch->load('positions.positionable', 'positions.jobPosition');
        return view('owner.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('owner.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => ['required', 'string', 'max:255', Rule::unique('branches')->ignore($branch->id)],
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:20',
                'manager_name' => 'nullable|string|max:255',
                'manager_phone' => 'nullable|string|max:20',
                'opening_date' => 'nullable|date',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $branch->update($validated);

            DB::commit();
            return $this->sendResponse('Branch updated successfully.', $branch);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return $this->sendSuccess('Branch deleted successfully.');
    }

    public function status(Request $request, Branch $branch)
    {
        $branch->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
