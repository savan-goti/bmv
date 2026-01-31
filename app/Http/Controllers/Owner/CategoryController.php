<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use App\Enums\Status;
use App\Enums\CategoryType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class CategoryController extends Controller
{
    use ResponseTrait;

    const IMAGE_PATH = 'uploads/categories/';

    public function index()
    {
        return view('owner.categories.index');
    }

    public function ajaxData()
    {
        $result = Category::query();

        // Filter by category type if provided
        if (request()->has('category_type') && request()->category_type != '') {
            $result->where('category_type', request()->category_type);
        }

        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('owner.categories.edit', $row->id);
                $deleteUrl = route('owner.categories.destroy', $row->id);
                $btn = '<a href="' . $editUrl . '" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="' . $deleteUrl . '"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('category_type', function ($row) {
                return '<span class="badge bg-primary">' . $row->category_type->label() . '</span>';
            })
            ->editColumn('status', function ($row) {
                $status = $row->status->label();
                $badgeClass = $row->status->color();
                return '<div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                            <input type="checkbox" class="form-check-input status-toggle" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" data-url="' . route('owner.categories.status', $row->id) . '" ' . ($row->status === Status::Active ? 'checked' : '') . '>
                            <label class="form-check-label" for="customSwitch' . $row->id . '">' . $status . '</label>
                        </div>';
            })
            ->editColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . asset(self::IMAGE_PATH . $row->image) . '" alt="' . $row->name . '" class="img-thumbnail" width="50">';
                }
                return 'N/A';
            })
            ->rawColumns(['action', 'category_type', 'status', 'image'])
            ->make(true);
    }

    public function create()
    {
        $categoryTypes = CategoryType::options();
        return view('owner.categories.form', compact('categoryTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'category_type' => 'required|in:' . implode(',', CategoryType::values()),
                'name' => 'required|string|max:255|unique:categories,name',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ];
            $messages = [
                'name.unique' => 'This Category already exists in records.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $validated['slug'] = Str::slug($validated['name']);

            if ($request->hasFile('image')) {
                $validated['image'] = uploadImgFile($request->image, self::IMAGE_PATH);
            }

            $category = Category::create($validated);

            DB::commit();
            return $this->sendResponse('Category created successfully.', $category);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        $categoryTypes = CategoryType::options();
        return view('owner.categories.form', compact('category', 'categoryTypes'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            DB::beginTransaction();
            $messages = [
                'name.unique' => 'This Category already exists in records.',
            ];
            $validator = Validator::make($request->all(), [
                'category_type' => 'required|in:' . implode(',', CategoryType::values()),
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'status' => 'required|in:active,inactive',
            ], $messages);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validated = $validator->validated();

            $validated['slug'] = Str::slug($validated['name']);

            if ($request->hasFile('image')) {
                if ($category->image) {
                    deleteImgFile($category->image, self::IMAGE_PATH);
                }
                $validated['image'] = uploadImgFile($request->image, self::IMAGE_PATH);
            }

            $category->update($validated);

            DB::commit();
            return $this->sendResponse('Category updated successfully.', $category);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        // if($category->image){
        //     deleteImgFile($category->image, self::IMAGE_PATH);
        // }
        $category->delete();
        return $this->sendSuccess('Category deleted successfully.');
    }

    public function status(Request $request, Category $category)
    {
        $category->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return $this->sendSuccess('Status updated successfully.');
    }
}
