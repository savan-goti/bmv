<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ChildCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Exception;

class ChildCategoryController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('owner.child_categories.index');
    }

    /**
     * Get AJAX data for DataTables
     */
    public function ajaxData(Request $request)
    {
        if ($request->ajax()) {
            $data = ChildCategory::with(['category', 'subCategory'])->select('child_categories.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category ? $row->category->name : '-';
                })
                ->addColumn('sub_category', function ($row) {
                    return $row->subCategory ? $row->subCategory->name : '-';
                })
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset('uploads/child_categories/' . $row->image) . '" alt="' . $row->name . '" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                    return '<img src="' . asset('assets/img/no_img.jpg') . '" alt="No Image" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == Status::Active ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                    data-id="' . $row->id . '" ' . $checked . '>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('owner.child-categories.edit', $row->id);
                    $deleteUrl = route('owner.child-categories.destroy', $row->id);

                    return '<div class="btn-group" role="group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', Status::Active)->get();
        return view('owner.child_categories.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255|unique:child_categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'name.unique' => 'This Child Category name already exists in our records.',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/child_categories'), $imageName);
            $data['image'] = $imageName;
        }

        ChildCategory::create($data);

        return redirect()->route('owner.child-categories.index')
            ->with('success', 'Child Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChildCategory $childCategory)
    {
        $categories = Category::where('status', Status::Active)->get();
        $subCategories = SubCategory::where('category_id', $childCategory->category_id)
            ->where('status', Status::Active)
            ->get();

        return view('owner.child_categories.form', compact('childCategory', 'categories', 'subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChildCategory $childCategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255|unique:child_categories,name,' . $childCategory->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'name.unique' => 'This Child Category name already exists in our records.',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($childCategory->image && file_exists(public_path('uploads/child_categories/' . $childCategory->image))) {
                unlink(public_path('uploads/child_categories/' . $childCategory->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/child_categories'), $imageName);
            $data['image'] = $imageName;
        }

        $childCategory->update($data);

        return redirect()->route('owner.child-categories.index')
            ->with('success', 'Child Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChildCategory $childCategory)
    {
        // Check if child category has products
        if ($childCategory->products()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete child category with associated products.'
            ], 400);
        }

        // Delete image
        if ($childCategory->image && file_exists(public_path('uploads/child_categories/' . $childCategory->image))) {
            unlink(public_path('uploads/child_categories/' . $childCategory->image));
        }

        $childCategory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Child Category deleted successfully.'
        ]);
    }

    /**
     * Toggle status
     */
    public function status(ChildCategory $childCategory)
    {
        $childCategory->status = $childCategory->status == Status::Active ? Status::Inactive : Status::Active;
        $childCategory->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.'
        ]);
    }

    /**
     * Get sub-categories by category
     */
    public function getByCategory(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->category_id)
            ->where('status', Status::Active)
            ->get();

        return response()->json($subCategories);
    }

    /**
     * Get child-categories by sub-category
     */
    public function getBySubCategory(Request $request)
    {
        $childCategories = ChildCategory::where('sub_category_id', $request->sub_category_id)
            ->where('status', Status::Active)
            ->get();

        return response()->json($childCategories);
    }
}
