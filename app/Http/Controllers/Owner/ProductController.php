<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use App\Enums\Status;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use ResponseTrait;

    const IMAGE_PATH = 'uploads/products/';
    const GALLERY_PATH = 'uploads/products/gallery/';

    public function index()
    {
        return view('owner.products.index');
    }

    public function ajaxData()
    {
        $result = Product::with(['category', 'subCategory']);
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $viewUrl = route('owner.products.show', $row->id);
                $editUrl = route('owner.products.edit', $row->id);
                $deleteUrl = route('owner.products.destroy', $row->id);
                $btn = '<a href="'.$viewUrl.'" class="btn btn-sm btn-primary me-1"><i class="bx bx-show"></i> View</a>';
                $btn .= '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $status = $row->status->label();
                $badgeClass = $row->status->color();
                return '<div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                            <input type="checkbox" class="form-check-input status-toggle" id="customSwitch'.$row->id.'" data-id="'.$row->id.'" data-url="'.route('owner.products.status', $row->id).'" '.($row->status === Status::Active ? 'checked' : '').'>
                            <label class="form-check-label" for="customSwitch'.$row->id.'">'.$status.'</label>
                        </div>';
            })
            ->editColumn('image', function($row){
                if($row->image){
                    return '<img src="'.asset(self::IMAGE_PATH.$row->image).'" alt="'.$row->name.'" class="img-thumbnail" width="50">';
                }
                return 'N/A';
            })
            ->addColumn('category_name', function($row){
                return $row->category ? $row->category->name : 'N/A';
            })
            ->editColumn('price', function($row){
                return currency($row->price);
            })
            ->rawColumns(['action', 'status', 'image'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::where('status', Status::Active)->get();
        return view('owner.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'manufacturer_name' => 'nullable|string',
            'manufacturer_brand' => 'nullable|string',
            'manufacturer_part_number' => 'nullable|string',
            'specifications' => 'nullable|array',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $productData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'price' => $request->price,
                'discount' => $request->discount ?? 0,
                'quantity' => $request->quantity,
                'status' => $request->status,
                'published_at' => $request->published_at,
                'added_by_id' => Auth::guard('owner')->id(),
                'added_by_type' => get_class(Auth::guard('owner')->user()),
            ];

            if ($request->hasFile('image')) {
                $productData['image'] = uploadImgFile($request->image, self::IMAGE_PATH);
            }

            $product = Product::create($productData);

            // Product Information
            $product->productInformation()->create([
                'short_description' => $request->short_description,
                'long_description' => $request->long_description,
                'manufacturer_name' => $request->manufacturer_name,
                'manufacturer_brand' => $request->manufacturer_brand,
                'manufacturer_part_number' => $request->manufacturer_part_number,
                'specifications' => $request->specifications,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ]);

            // Gallery Images
            if ($request->hasFile('gallery_images')) {
                $galleryImages = uploadMultipleImages($request->file('gallery_images'), self::GALLERY_PATH);
                foreach ($galleryImages as $index => $imageName) {
                    $product->productImages()->create([
                        'image' => $imageName,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();
            return $this->sendResponse('Product created successfully.', $product);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Error creating product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', Status::Active)->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->where('status', Status::Active)->get();
        $product->load(['productInformation', 'productImages']);
        return view('owner.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'manufacturer_name' => 'nullable|string',
            'manufacturer_brand' => 'nullable|string',
            'manufacturer_part_number' => 'nullable|string',
            'specifications' => 'nullable|array',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $productData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'price' => $request->price,
                'discount' => $request->discount ?? 0,
                'quantity' => $request->quantity,
                'status' => $request->status,
                'published_at' => $request->published_at,
            ];

            if ($request->hasFile('image')) {
                if($product->image){
                    deleteImgFile($product->image, self::IMAGE_PATH);
                }
                $productData['image'] = uploadImgFile($request->image, self::IMAGE_PATH);
            }

            $product->update($productData);

            // Product Information
            $product->productInformation()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'short_description' => $request->short_description,
                    'long_description' => $request->long_description,
                    'manufacturer_name' => $request->manufacturer_name,
                    'manufacturer_brand' => $request->manufacturer_brand,
                    'manufacturer_part_number' => $request->manufacturer_part_number,
                    'specifications' => $request->specifications,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                ]
            );

            // Gallery Images (Append new ones)
            if ($request->hasFile('gallery_images')) {
                $galleryImages = uploadMultipleImages($request->file('gallery_images'), self::GALLERY_PATH);
                $currentMaxOrder = $product->productImages()->max('sort_order') ?? 0;
                foreach ($galleryImages as $index => $imageName) {
                    $product->productImages()->create([
                        'image' => $imageName,
                        'sort_order' => $currentMaxOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();
            return $this->sendResponse('Product updated successfully.', $product);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Error updating product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();
            
            if($product->image){
                // deleteImgFile($product->image, self::IMAGE_PATH);
            }

            foreach($product->productImages as $image){
                deleteImgFile($image->image, self::GALLERY_PATH);
                $image->delete();
            }

            $product->productInformation()->delete();
            $product->delete();

            DB::commit();
            return $this->sendSuccess('Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Error deleting product: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'subCategory', 'productInformation', 'productImages']);
        return view('owner.products.show', compact('product'));
    }

    public function status(Request $request, Product $product)
    {
        $product->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function deleteImage(ProductImage $productImage)
    {
        deleteImgFile($productImage->image, self::GALLERY_PATH);
        $productImage->delete();
        return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
    }
}
