<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class JobPositionController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.job_positions.index');
    }

    public function ajaxData()
    {
        $result = JobPosition::query();
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $viewUrl = route('owner.job-positions.show', $row->id);
                $editUrl = route('owner.job-positions.edit', $row->id);
                $deleteUrl = route('owner.job-positions.destroy', $row->id);
                $btn = '<a href="' . $viewUrl . '" class="btn btn-sm btn-success me-1"><i class="bx bx-show"></i> View</a>';
                $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="' . $deleteUrl . '"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function ($row) {
                $status = ucfirst($row->status);
                $badgeClass = $row->status == 'active' ? 'success' : 'danger';
                return '<span class="badge bg-' . $badgeClass . '">' . $status . '</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('owner.job_positions.form');
    }

    public function show(JobPosition $jobPosition)
    {
        return view('owner.job_positions.show', compact('jobPosition'));
    }

    public function edit(JobPosition $jobPosition)
    {
        return view('owner.job_positions.form', compact('jobPosition'));
    }

    /**
     * Save job position (create or update) via AJAX
     */
    public function save(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();

            // Build validation rules
            $rules = [
                'name' => 'required|string|max:255|unique:job_positions,name,' . $id,
                'description' => 'nullable|string',
                'department' => 'nullable|string|max:255',
                'level' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
            ];

            $messages = [
                'name.unique' => 'This Job Position already exists in records.',
            ];

            if (!$id) {
                // Create validation rules
                $rules['owner_id'] = 'required';
            }

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            if ($id) {
                // Update existing job position
                $jobPosition = JobPosition::findOrFail($id);
                $jobPosition->update($validated);
                $message = 'Job Position updated successfully.';
            } else {
                // Create new job position
                $jobPosition = JobPosition::create($validated);
                $message = 'Job Position created successfully.';
            }

            DB::commit();
            return $this->sendSuccess($message);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(JobPosition $jobPosition)
    {
        $jobPosition->delete();
        return $this->sendSuccess('Job Position deleted successfully.');
    }

    public function status(Request $request, JobPosition $jobPosition)
    {
        $jobPosition->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
