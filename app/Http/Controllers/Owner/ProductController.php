<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductImage;
use App\Models\ChildCategory;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\Unit;
use App\Models\HsnSac;
use App\Models\Color;
use App\Models\Size;
use App\Models\Supplier;
use App\Models\Branch;
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

    public function index()
    {
        return view('owner.products.index');
    }

    public function ajaxData(Request $request)
    {
        $result = Product::with(['category', 'subCategory', 'brand', 'collection', 'unit']);
        
        // Apply filters
        if ($request->has('is_active') && $request->is_active != '') {
            $result->where('is_active', $request->is_active);
        }
        
        if ($request->has('product_status') && $request->product_status != '') {
            $result->where('product_status', $request->product_status);
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
                    return '<img src="'.asset(PRODUCT_IMAGE_PATH.$row->thumbnail_image).'" alt="'.$row->product_name.'" class="img-thumbnail" width="50">';
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
        $brands = Brand::where('status', Status::Active)->get();
        $collections = Collection::where('status', Status::Active)->get();
        $units = Unit::where('status', Status::Active)->where('category', 'product')->get();
        $hsnSacs = HsnSac::where('status', Status::Active)->get();
        $colors = Color::where('status', Status::Active)->get();
        $sizes = Size::where('status', Status::Active)->get();
        $suppliers = Supplier::where('status', Status::Active)->get();
        $branches = Branch::where('status', Status::Active)->get();

        return view('owner.products.create', compact(
            'categories', 
            'brands', 
            'collections', 
            'units', 
            'hsnSacs', 
            'colors', 
            'sizes', 
            'suppliers',
            'branches'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Info
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'warranty_value' => 'nullable|numeric|min:0',
            'warranty_unit' => 'nullable|in:months,years',
            
            // Category & Brand
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'collection_id' => 'nullable|exists:collections,id',
            'branch_id' => 'nullable|exists:branches,id',
            
            // Pricing
            'purchase_price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|lte:original_price',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'tax_included' => 'nullable|boolean',
            'commission_type' => 'required|in:flat,percentage',
            'commission_value' => 'nullable|numeric|min:0',
            
            // Tax & Units
            'hsn_sac_id' => 'required|exists:hsn_sacs,id',
            'unit_id' => 'required|exists:units,id',

            // Inventory
            'stock_type' => 'required|in:limited,unlimited',
            'total_stock' => 'required|integer|min:1',
            'low_stock_alert' => 'nullable|integer|min:0',
            'warehouse_location' => 'nullable|string|max:100',
            
            // Variations
            'has_variation' => 'nullable|boolean',
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'product_weight' => 'nullable|numeric|min:0',
            'shipping_weight' => 'nullable|numeric|min:0',
            
            // Media
            'thumbnail_image' => 'required',
            'image_alt_text' => 'nullable|string|max:255',
            'gallery_images.*' => 'nullable',
            'product_videos.*' => 'nullable|file|mimes:mp4,webm|max:20480', // 20MB limit
            
            // Shipping
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'width' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'shipping_class' => 'required|in:normal,heavy',
            'free_shipping' => 'nullable|boolean',
            'cod_available' => 'nullable|boolean',
            
            // Status & Workflow
            'product_status' => 'required|in:draft,pending,approved,rejected',
            'is_active' => 'required|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            'is_returnable' => 'nullable|boolean',
            'return_days' => 'nullable|integer|min:0',
            
            // Other Details
            'supplier_id' => 'nullable|exists:suppliers,id',
            'packer_name' => 'nullable|string|max:255',
            'packer_address' => 'nullable|string',
            'packer_gst' => 'nullable|string|max:20',

            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'search_tags' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Auto-generate unique barcode
            $barcode = $this->generateUniqueBarcode();

            $productData = [
                // Basic Info
                'product_name' => $request->product_name,
                'slug' => Str::slug($request->product_name),
                'sku' => $request->sku,
                'barcode' => $barcode,
                'short_description' => $request->short_description,
                'full_description' => $request->full_description,
                'warranty_value' => $request->warranty_value,
                'warranty_unit' => $request->warranty_unit,
                
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
                'branch_id' => $request->branch_id,
                
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
                
                // Tax & Units
                'hsn_sac_id' => $request->hsn_sac_id,
                'unit_id' => $request->unit_id,

                // Inventory
                'stock_type' => $request->stock_type,
                'total_stock' => $request->total_stock,
                'reserved_stock' => 0,
                'available_stock' => $request->total_stock,
                'low_stock_alert' => $request->low_stock_alert ?? 10,
                'warehouse_location' => $request->warehouse_location,
                
                // Variations
                'has_variation' => $request->has_variation ?? false,
                'color_id' => $request->color_id,
                'size_id' => $request->size_id,
                'product_weight' => $request->product_weight,
                'shipping_weight' => $request->shipping_weight,
                
                // Media
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
                
                // Other Details
                'supplier_id' => $request->supplier_id,
                'packer_name' => $request->packer_name,
                'packer_address' => $request->packer_address,
                'packer_gst' => $request->packer_gst,

                // SEO
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'search_tags' => $request->search_tags,
            ];

            if ($request->has('thumbnail_image')) {
                $productData['thumbnail_image'] = uploadFilepondFile($request->thumbnail_image, PRODUCT_IMAGE_PATH);
            }

            // Handle multiple videos
            if ($request->hasFile('product_videos')) {
                $videoPaths = [];
                foreach ($request->file('product_videos') as $video) {
                    $videoPaths[] = uploadFile($video, PRODUCT_VIDEO_PATH);
                }
                $productData['product_videos'] = $videoPaths;
            }

            $product = Product::create($productData);

            // Gallery Images
            if ($request->has('gallery_images') && is_array($request->gallery_images)) {
                $galleryImages = uploadMultipleImages($request->gallery_images, PRODUCT_GALLERY_PATH);
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

    private function generateUniqueBarcode()
    {
        do {
            $barcode = mt_rand(1000000000, 9999999999);
        } while (Product::where('barcode', $barcode)->exists());
        return (string)$barcode;
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', Status::Active)->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->where('status', Status::Active)->get();
        $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->where('status', Status::Active)->get();
        $brands = Brand::where('status', Status::Active)->get();
        $collections = Collection::where('status', Status::Active)->get();
        
        $units = Unit::where('status', Status::Active)->where('category', 'product')->get();
        $hsnSacs = HsnSac::where('status', Status::Active)->get();
        $colors = Color::where('status', Status::Active)->get();
        $sizes = Size::where('status', Status::Active)->get();
        $suppliers = Supplier::where('status', Status::Active)->get();
        $branches = Branch::where('status', Status::Active)->get();

        $product->load(['productImages', 'productInformation']);
        
        return view('owner.products.edit', compact(
            'product', 
            'categories', 
            'subCategories', 
            'childCategories', 
            'brands', 
            'collections',
            'units',
            'hsnSacs',
            'colors',
            'sizes',
            'suppliers',
            'branches'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            // Basic Info
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,'.$product->id,
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'warranty_value' => 'nullable|numeric|min:0',
            'warranty_unit' => 'nullable|in:months,years',
            
            // Category & Brand
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'collection_id' => 'nullable|exists:collections,id',
            'branch_id' => 'nullable|exists:branches,id',
            
            // Pricing
            'purchase_price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|lte:original_price',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'tax_included' => 'nullable|boolean',
            'commission_type' => 'required|in:flat,percentage',
            'commission_value' => 'nullable|numeric|min:0',
            
            // Tax & Units
            'hsn_sac_id' => 'required|exists:hsn_sacs,id',
            'unit_id' => 'required|exists:units,id',

            // Inventory
            'stock_type' => 'required|in:limited,unlimited',
            'total_stock' => 'required|integer|min:1',
            'low_stock_alert' => 'nullable|integer|min:0',
            'warehouse_location' => 'nullable|string|max:100',
            
            // Variations
            'has_variation' => 'nullable|boolean',
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'product_weight' => 'nullable|numeric|min:0',
            'shipping_weight' => 'nullable|numeric|min:0',
            
            // Media
            'thumbnail_image' => 'nullable',
            'image_alt_text' => 'nullable|string|max:255',
            'gallery_images.*' => 'nullable',
            'product_videos.*' => 'nullable|file|mimes:mp4,webm|max:20480',
            
            // Shipping
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'width' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'shipping_class' => 'required|in:normal,heavy',
            'free_shipping' => 'nullable|boolean',
            'cod_available' => 'nullable|boolean',
            
            // Status & Workflow
            'product_status' => 'required|in:draft,pending,approved,rejected',
            'is_active' => 'required|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            'is_returnable' => 'nullable|boolean',
            'return_days' => 'nullable|integer|min:0',
            
            // Other Details
            'supplier_id' => 'nullable|exists:suppliers,id',
            'packer_name' => 'nullable|string|max:255',
            'packer_address' => 'nullable|string',
            'packer_gst' => 'nullable|string|max:20',

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
                'product_name' => $request->product_name,
                'slug' => Str::slug($request->product_name),
                'sku' => $request->sku,
                'short_description' => $request->short_description,
                'full_description' => $request->full_description,
                'warranty_value' => $request->warranty_value,
                'warranty_unit' => $request->warranty_unit,
                
                // Category & Brand
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'child_category_id' => $request->child_category_id,
                'brand_id' => $request->brand_id,
                'collection_id' => $request->collection_id,
                'branch_id' => $request->branch_id,
                
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
                
                // Tax & Units
                'hsn_sac_id' => $request->hsn_sac_id,
                'unit_id' => $request->unit_id,

                // Inventory
                'stock_type' => $request->stock_type,
                'total_stock' => $request->total_stock,
                'available_stock' => $request->total_stock - ($product->reserved_stock ?? 0),
                'low_stock_alert' => $request->low_stock_alert ?? 10,
                'warehouse_location' => $request->warehouse_location,
                
                // Variations
                'has_variation' => $request->has_variation ?? false,
                'color_id' => $request->color_id,
                'size_id' => $request->size_id,
                'product_weight' => $request->product_weight,
                'shipping_weight' => $request->shipping_weight,
                
                // Media
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
                
                // Other Details
                'supplier_id' => $request->supplier_id,
                'packer_name' => $request->packer_name,
                'packer_address' => $request->packer_address,
                'packer_gst' => $request->packer_gst,

                // SEO
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'search_tags' => $request->search_tags,
            ];

            if ($request->has('thumbnail_image')) {
                if($product->thumbnail_image){
                    deleteImgFile($product->thumbnail_image, PRODUCT_IMAGE_PATH);
                }
                $productData['thumbnail_image'] = uploadFilepondFile($request->thumbnail_image, PRODUCT_IMAGE_PATH);
            }

            // Handle multiple videos (appending to existing ones)
            if ($request->hasFile('product_videos')) {
                $videoPaths = $product->product_videos ?? [];
                foreach ($request->file('product_videos') as $video) {
                    $videoPaths[] = uploadFile($video, PRODUCT_VIDEO_PATH);
                }
                $productData['product_videos'] = $videoPaths;
            }

            $product->update($productData);

            // Gallery Images (Append new ones)
            if ($request->has('gallery_images') && $request->gallery_images && is_array($request->gallery_images)) {
                $galleryImages = uploadMultipleImages($request->gallery_images, PRODUCT_GALLERY_PATH);
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
                deleteImgFile($product->thumbnail_image, PRODUCT_IMAGE_PATH);
            }

            foreach($product->productImages as $image){
                deleteImgFile($image->image, PRODUCT_GALLERY_PATH);
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
        $product->load(['category', 'subCategory', 'childCategory', 'brand', 'collection', 'productImages', 'productInformation', 'productReviews']);
        return view('owner.products.show', compact('product'));
    }

    public function status(Request $request, Product $product)
    {
        $product->update(['is_active' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function deleteImage(ProductImage $productImage)
    {
        deleteImgFile($productImage->image, PRODUCT_GALLERY_PATH);
        $productImage->delete();
        return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
    }
}
