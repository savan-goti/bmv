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

    public function ajaxData(Request $request)
    {
        $result = Product::with(['category', 'subCategory', 'brand', 'collection']);
        
        // Apply filters
        if ($request->has('is_active') && $request->is_active != '') {
            $result->where('is_active', $request->is_active);
        }
        
        if ($request->has('product_status') && $request->product_status != '') {
            $result->where('product_status', $request->product_status);
        }
        
        if ($request->has('product_type') && $request->product_type != '') {
            $result->where('product_type', $request->product_type);
        }
        
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
            ->editColumn('is_active', function($row){
                $status = $row->is_active->label();
                $badgeClass = $row->is_active->color();
                return '<div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                            <input type="checkbox" class="form-check-input status-toggle" id="customSwitch'.$row->id.'" data-id="'.$row->id.'" data-url="'.route('owner.products.status', $row->id).'" '.($row->is_active === Status::Active ? 'checked' : '').'>
                            <label class="form-check-label" for="customSwitch'.$row->id.'">'.$status.'</label>
                        </div>';
            })
            ->editColumn('thumbnail_image', function($row){
                if($row->thumbnail_image){
                    return '<img src="'.asset(self::IMAGE_PATH.$row->thumbnail_image).'" alt="'.$row->product_name.'" class="img-thumbnail" width="50">';
                }
                return 'N/A';
            })
            ->addColumn('category_name', function($row){
                return $row->category ? $row->category->name : 'N/A';
            })
            ->editColumn('sell_price', function($row){
                return currency($row->sell_price);
            })
            ->editColumn('product_status', function($row){
                $badges = [
                    'draft' => 'secondary',
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger'
                ];
                $badgeClass = $badges[$row->product_status] ?? 'secondary';
                return '<span class="badge bg-'.$badgeClass.'">'.ucfirst($row->product_status).'</span>';
            })
            ->rawColumns(['action', 'is_active', 'thumbnail_image', 'product_status'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::where('status', Status::Active)->get();
        $brands = \App\Models\Brand::where('status', Status::Active)->get();
        $collections = \App\Models\Collection::where('status', Status::Active)->get();
        return view('owner.products.create', compact('categories', 'brands', 'collections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Info
            'product_type' => 'required|in:simple,variable,digital,service',
            'product_name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            
            // Category & Brand
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'collection_id' => 'nullable|exists:collections,id',
            
            // Pricing
            'purchase_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'tax_included' => 'nullable|boolean',
            'commission_type' => 'required|in:flat,percentage',
            'commission_value' => 'nullable|numeric|min:0',
            
            // Inventory
            'stock_type' => 'required|in:limited,unlimited',
            'total_stock' => 'required|integer|min:0',
            'low_stock_alert' => 'nullable|integer|min:0',
            'warehouse_location' => 'nullable|string|max:100',
            
            // Variations
            'has_variation' => 'nullable|boolean',
            
            // Media
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_url' => 'nullable|url',
            'image_alt_text' => 'nullable|string|max:255',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Shipping
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'shipping_class' => 'required|in:normal,heavy',
            'free_shipping' => 'nullable|boolean',
            'cod_available' => 'nullable|boolean',
            
            // Status & Workflow
            'product_status' => 'required|in:draft,pending,approved,rejected',
            'is_active' => 'required|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            'is_returnable' => 'nullable|boolean',
            'return_days' => 'nullable|integer|min:0',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'search_tags' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $productData = [
                // Basic Info
                'product_type' => $request->product_type,
                'product_name' => $request->product_name,
                'slug' => Str::slug($request->product_name),
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'short_description' => $request->short_description,
                'full_description' => $request->full_description,
                
                // Ownership & Audit
                'owner_id' => Auth::guard('owner')->id(),
                'added_by_role' => 'owner',
                'added_by_user_id' => Auth::guard('owner')->id(),
                
                // Category & Brand
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'child_category_id' => $request->child_category_id,
                'brand_id' => $request->brand_id,
                'collection_id' => $request->collection_id,
                
                // Pricing
                'purchase_price' => $request->purchase_price,
                'original_price' => $request->original_price,
                'sell_price' => $request->sell_price,
                'discount_type' => $request->discount_type ?? 'flat',
                'discount_value' => $request->discount_value ?? 0,
                'gst_rate' => $request->gst_rate ?? 0,
                'tax_included' => $request->tax_included ?? false,
                'commission_type' => $request->commission_type ?? 'percentage',
                'commission_value' => $request->commission_value ?? 0,
                
                // Inventory
                'stock_type' => $request->stock_type,
                'total_stock' => $request->total_stock,
                'reserved_stock' => 0,
                'available_stock' => $request->total_stock,
                'low_stock_alert' => $request->low_stock_alert ?? 10,
                'warehouse_location' => $request->warehouse_location,
                
                // Variations
                'has_variation' => $request->has_variation ?? false,
                
                // Media
                'video_url' => $request->video_url,
                'image_alt_text' => $request->image_alt_text,
                
                // Shipping
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'shipping_class' => $request->shipping_class ?? 'normal',
                'free_shipping' => $request->free_shipping ?? false,
                'cod_available' => $request->cod_available ?? true,
                
                // Status & Workflow
                'product_status' => $request->product_status,
                'is_active' => $request->is_active,
                'is_featured' => $request->is_featured ?? false,
                'is_returnable' => $request->is_returnable ?? true,
                'return_days' => $request->return_days ?? 7,
                
                // SEO
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'search_tags' => $request->search_tags,
            ];

            if ($request->hasFile('thumbnail_image')) {
                $productData['thumbnail_image'] = uploadImgFile($request->thumbnail_image, self::IMAGE_PATH);
            }

            $product = Product::create($productData);

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
        $childCategories = \App\Models\ChildCategory::where('sub_category_id', $product->sub_category_id)->where('status', Status::Active)->get();
        $brands = \App\Models\Brand::where('status', Status::Active)->get();
        $collections = \App\Models\Collection::where('status', Status::Active)->get();
        $product->load(['productImages', 'productInformation']);
        return view('owner.products.edit', compact('product', 'categories', 'subCategories', 'childCategories', 'brands', 'collections'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            // Basic Info
            'product_type' => 'required|in:simple,variable,digital,service',
            'product_name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,'.$product->id,
            'barcode' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            
            // Category & Brand
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'collection_id' => 'nullable|exists:collections,id',
            
            // Pricing
            'purchase_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'tax_included' => 'nullable|boolean',
            'commission_type' => 'required|in:flat,percentage',
            'commission_value' => 'nullable|numeric|min:0',
            
            // Inventory
            'stock_type' => 'required|in:limited,unlimited',
            'total_stock' => 'required|integer|min:0',
            'low_stock_alert' => 'nullable|integer|min:0',
            'warehouse_location' => 'nullable|string|max:100',
            
            // Variations
            'has_variation' => 'nullable|boolean',
            
            // Media
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_url' => 'nullable|url',
            'image_alt_text' => 'nullable|string|max:255',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Shipping
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'shipping_class' => 'required|in:normal,heavy',
            'free_shipping' => 'nullable|boolean',
            'cod_available' => 'nullable|boolean',
            
            // Status & Workflow
            'product_status' => 'required|in:draft,pending,approved,rejected',
            'is_active' => 'required|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            'is_returnable' => 'nullable|boolean',
            'return_days' => 'nullable|integer|min:0',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'search_tags' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $productData = [
                // Basic Info
                'product_type' => $request->product_type,
                'product_name' => $request->product_name,
                'slug' => Str::slug($request->product_name),
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'short_description' => $request->short_description,
                'full_description' => $request->full_description,
                
                // Category & Brand
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'child_category_id' => $request->child_category_id,
                'brand_id' => $request->brand_id,
                'collection_id' => $request->collection_id,
                
                // Pricing
                'purchase_price' => $request->purchase_price,
                'original_price' => $request->original_price,
                'sell_price' => $request->sell_price,
                'discount_type' => $request->discount_type ?? 'flat',
                'discount_value' => $request->discount_value ?? 0,
                'gst_rate' => $request->gst_rate ?? 0,
                'tax_included' => $request->tax_included ?? false,
                'commission_type' => $request->commission_type ?? 'percentage',
                'commission_value' => $request->commission_value ?? 0,
                
                // Inventory
                'stock_type' => $request->stock_type,
                'total_stock' => $request->total_stock,
                'available_stock' => $request->total_stock - ($product->reserved_stock ?? 0),
                'low_stock_alert' => $request->low_stock_alert ?? 10,
                'warehouse_location' => $request->warehouse_location,
                
                // Variations
                'has_variation' => $request->has_variation ?? false,
                
                // Media
                'video_url' => $request->video_url,
                'image_alt_text' => $request->image_alt_text,
                
                // Shipping
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'shipping_class' => $request->shipping_class ?? 'normal',
                'free_shipping' => $request->free_shipping ?? false,
                'cod_available' => $request->cod_available ?? true,
                
                // Status & Workflow
                'product_status' => $request->product_status,
                'is_active' => $request->is_active,
                'is_featured' => $request->is_featured ?? false,
                'is_returnable' => $request->is_returnable ?? true,
                'return_days' => $request->return_days ?? 7,
                
                // SEO
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'search_tags' => $request->search_tags,
            ];

            if ($request->hasFile('thumbnail_image')) {
                if($product->thumbnail_image){
                    deleteImgFile($product->thumbnail_image, self::IMAGE_PATH);
                }
                $productData['thumbnail_image'] = uploadImgFile($request->thumbnail_image, self::IMAGE_PATH);
            }

            $product->update($productData);

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
            
            if($product->thumbnail_image){
                deleteImgFile($product->thumbnail_image, self::IMAGE_PATH);
            }

            foreach($product->productImages as $image){
                deleteImgFile($image->image, self::GALLERY_PATH);
                $image->delete();
            }

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
        $product->load(['category', 'subCategory', 'childCategory', 'brand', 'collection', 'productImages']);
        return view('owner.products.show', compact('product'));
    }

    public function status(Request $request, Product $product)
    {
        $product->update(['is_active' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function deleteImage(ProductImage $productImage)
    {
        deleteImgFile($productImage->image, self::GALLERY_PATH);
        $productImage->delete();
        return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
    }
}
