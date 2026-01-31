<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use App\Enums\Status;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SubCategoryController extends Controller
{
    use ResponseTrait;

    const IMAGE_PATH = 'uploads/sub_categories/';

    public function index()
    {
        return view('owner.sub_categories.index');
    }

    public function ajaxData()
    {
        $result = SubCategory::with('category');
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('owner.sub-categories.edit', $row->id);
                $deleteUrl = route('owner.sub-categories.destroy', $row->id);
                $btn = '<a href="' . $editUrl . '" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="' . $deleteUrl . '"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function ($row) {
                $status = $row->status->label();
                $badgeClass = $row->status->color();
                return '<div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                            <input type="checkbox" class="form-check-input status-toggle" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" data-url="' . route('owner.sub-categories.status', $row->id) . '" ' . ($row->status === Status::Active ? 'checked' : '') . '>
                            <label class="form-check-label" for="customSwitch' . $row->id . '">' . $status . '</label>
                        </div>';
            })
            ->editColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . asset(self::IMAGE_PATH . $row->image) . '" alt="' . $row->name . '" class="img-thumbnail" width="50">';
                }
                return 'N/A';
            })
            ->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : 'N/A';
            })
            ->rawColumns(['action', 'status', 'image'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::where('status', Status::Active)->get();
        return view('owner.sub_categories.form', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $messages = [
                'name.unique' => 'This Sub Category already exists in records.',
            ];
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255|unique:sub_categories,name',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ], $messages);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $validated['slug'] = Str::slug($validated['name']);
            
            if ($request->hasFile('image')) {
                $validated['image'] = uploadImgFile($request->image, self::IMAGE_PATH);
            }

            $subCategory = SubCategory::create($validated);

            DB::commit();
            return $this->sendResponse('SubCategory created successfully.', $subCategory);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::where('status', Status::Active)->get();
        return view('owner.sub_categories.form', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255|unique:sub_categories,name,' . $subCategory->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ], [
                'name.unique' => 'This Sub Category already exists in records.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $validated['slug'] = Str::slug($validated['name']);

            if ($request->hasFile('image')) {
                if ($subCategory->image) {
                    deleteImgFile($subCategory->image, self::IMAGE_PATH);
                }
                $validated['image'] = uploadImgFile($request->image, self::IMAGE_PATH);
            }

            $subCategory->update($validated);

            DB::commit();
            return $this->sendResponse('SubCategory updated successfully.', $subCategory);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(SubCategory $subCategory)
    {
        if ($subCategory->image) {
            deleteImgFile($subCategory->image, self::IMAGE_PATH);
        }
        $subCategory->delete();
        return $this->sendSuccess('SubCategory deleted successfully.');
    }

    public function status(Request $request, SubCategory $subCategory)
    {
        $subCategory->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return $this->sendSuccess('Status updated successfully.');
    }

    public function getByCategory(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->category_id)
            ->where('status', Status::Active)
            ->get();
        return response()->json($subCategories);
    }
}
