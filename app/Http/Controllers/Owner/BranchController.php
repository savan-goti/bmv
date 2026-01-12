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
                'type' => 'required|in:product,service',
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
                'social_media.facebook_url' => 'nullable|url',
                'social_media.instagram_url' => 'nullable|url',
                'social_media.twitter_url' => 'nullable|url',
                'social_media.linkedin_url' => 'nullable|url',
                'social_media.youtube_url' => 'nullable|url',
                'social_media.pinterest_url' => 'nullable|url',
                'social_media.tiktok_url' => 'nullable|url',
                'social_media.whatsapp_url' => 'nullable|url',
                'social_media.telegram_url' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            // Auto-generate username from branch name
            $username = $this->generateUsername($validated['name']);
            $validated['username'] = $username;

            // Auto-generate branch link
            $validated['branch_link'] = 'https://shop.indstary.com/branch/' . $username;

            // Handle social media as JSON
            if ($request->has('social_media')) {
                $validated['social_media'] = $request->input('social_media');
            }

            $branch = Branch::create($validated);

            DB::commit();
            return $this->sendResponse('Branch created successfully.', $branch);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Generate unique username from branch name
     */
    private function generateUsername($name, $excludeId = null)
    {
        // Convert to lowercase and replace spaces with underscores
        $username = strtolower(str_replace(' ', '_', $name));
        
        // Remove special characters, keep only alphanumeric and underscores
        $username = preg_replace('/[^a-z0-9_]/', '', $username);
        
        // Check if username exists
        $originalUsername = $username;
        $counter = 1;
        
        while (true) {
            $query = Branch::where('username', $username);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $username = $originalUsername . '_' . $counter;
            $counter++;
        }
        
        return $username;
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
                'type' => 'required|in:product,service',
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
                'social_media.facebook_url' => 'nullable|url',
                'social_media.instagram_url' => 'nullable|url',
                'social_media.twitter_url' => 'nullable|url',
                'social_media.linkedin_url' => 'nullable|url',
                'social_media.youtube_url' => 'nullable|url',
                'social_media.pinterest_url' => 'nullable|url',
                'social_media.tiktok_url' => 'nullable|url',
                'social_media.whatsapp_url' => 'nullable|url',
                'social_media.telegram_url' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            // If name changed, regenerate username and branch_link
            if ($validated['name'] !== $branch->name) {
                $username = $this->generateUsername($validated['name'], $branch->id);
                $validated['username'] = $username;
                $validated['branch_link'] = 'https://shop.indstary.com/branch/' . $username;
            }

            // Handle social media as JSON
            if ($request->has('social_media')) {
                $validated['social_media'] = $request->input('social_media');
            }

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
