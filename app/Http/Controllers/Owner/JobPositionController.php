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
            ->addColumn('action', function($row){
                $viewUrl = route('owner.job-positions.show', $row->id);
                $editUrl = route('owner.job-positions.edit', $row->id);
                $deleteUrl = route('owner.job-positions.destroy', $row->id);
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
        return view('owner.job_positions.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'owner_id' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'department' => 'nullable|string|max:255',
                'level' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $jobPosition = JobPosition::create($validated);

            DB::commit();
            return $this->sendResponse('Job Position created successfully.', $jobPosition);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function show(JobPosition $jobPosition)
    {
        return view('owner.job_positions.show', compact('jobPosition'));
    }

    public function edit(JobPosition $jobPosition)
    {
        return view('owner.job_positions.edit', compact('jobPosition'));
    }

    public function update(Request $request, JobPosition $jobPosition)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'department' => 'nullable|string|max:255',
                'level' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $jobPosition->update($validated);

            DB::commit();
            return $this->sendResponse('Job Position updated successfully.', $jobPosition);

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
