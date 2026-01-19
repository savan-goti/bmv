<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Enums\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    
    use ResponseTrait;


    /**
     * Get all available category types
     * 
     * @return JsonResponse
     */
    public function getCategoryTypes(): JsonResponse
    {
        try {
            $categoryTypes = [];
            
            foreach (CategoryType::cases() as $type) {
                $categoryTypes[] = [
                    'value' => $type->value,
                    'label' => $type->label()
                ];
            }

            return $this->sendResponse('Category types retrieved successfully', $categoryTypes);

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve category types: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all categories with optional filters
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategories(Request $request)
    {
        try {
            $query = Category::query();
            // Filter by status
            $query->where('status', 'active');

            // Filter by category type
            if ($request->has('category_type')) {
                $query->where('category_type', $request->category_type);
            }

            // Search by name
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $categories = $query->get();

            return $this->sendResponse('Categories retrieved successfully', $categories);

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve categories'. $e->getMessage(), 500);
        }
    }


    /**
     * Get all sub-categories with optional filters
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getSubCategories(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ], [
            'category_id.required' => 'Category ID is required',
            'category_id.exists' => 'Category ID does not exist',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $query = SubCategory::query();
            
            // Filter by status - always active
            $query->where('status', 'active');

            // Filter by category
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Search by name
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $subCategories = $query->get();

            return $this->sendResponse('Sub-categories retrieved successfully', $subCategories);

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve sub-categories: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all child categories with optional filters
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getChildCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ], [
            'category_id.required' => 'Category ID is required',
            'category_id.exists' => 'Category ID does not exist',
            'sub_category_id.required' => 'Sub-category ID is required',
            'sub_category_id.exists' => 'Sub-category ID does not exist',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $query = ChildCategory::query();
            
            // Filter by status - always active
            $query->where('status', 'active');

            // Filter by category
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filter by sub-category
            if ($request->has('sub_category_id')) {
                $query->where('sub_category_id', $request->sub_category_id);
            }

            // Search by name
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $childCategories = $query->get();

            return $this->sendResponse('Child categories retrieved successfully', $childCategories);

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve child categories: ' . $e->getMessage(), 500);
        }
    }
}
