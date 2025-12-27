@extends('owner.master')
@section('title','Edit Product')

@section('style')
<style>
    /* Disabled Next Button Styling */
    .next-tab.disabled,
    .next-tab:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    /* Invalid Field Styling */
    .is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        padding-right: calc(1.5em + 0.75rem);
    }
    
    /* Shake Animation */
    @keyframes shakeX {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }
    
    .animate__animated.animate__shakeX {
        animation: shakeX 0.82s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    }
    
    
    /* Required Field Indicator */
    .form-label .text-danger {
        font-weight: bold;
    }
    
    /* Tab Header Styling */
    .nav-tabs .nav-link {
        position: relative;
        transition: all 0.3s ease;
    }
    
    /* Locked tab indicator (for forward navigation with incomplete current tab) */
    .nav-tabs .nav-link.tab-locked {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .nav-tabs .nav-link.tab-locked::after {
        content: '\f023'; /* Lock icon */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 10px;
        color: #dc3545;
    }
    
    /* Hover effect for accessible tabs */
    .nav-tabs .nav-link:not(.active):not(.tab-locked):hover {
        background-color: rgba(0, 123, 255, 0.1);
        border-color: transparent;
    }
    
    
    /* Active tab styling */
    .nav-tabs .nav-link.active {
        font-weight: 600;
    }
    
    /* Product Type Button Group Styling */
    .btn-group label.btn {
        font-size: 14px;
        padding: 8px 12px;
        white-space: nowrap;
    }
    
    .btn-group label.btn i {
        font-size: 16px;
    }
    
    /* Responsive product type buttons */
    @media (max-width: 768px) {
        .btn-group label.btn {
            font-size: 12px;
            padding: 6px 8px;
        }
        
        .btn-group label.btn i {
            font-size: 14px;
        }
    }

</style>
@endsection


@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Product</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.products.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <form id="productEditForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#basic-info" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-info"></i></span>
                                    <span class="d-none d-sm-block">Basic Info</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#pricing" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-dollar-sign"></i></span>
                                    <span class="d-none d-sm-block">Pricing</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#inventory" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-boxes"></i></span>
                                    <span class="d-none d-sm-block">Inventory</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#media" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-image"></i></span>
                                    <span class="d-none d-sm-block">Media</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#shipping" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-shipping-fast"></i></span>
                                    <span class="d-none d-sm-block">Shipping</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#seo" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-search"></i></span>
                                    <span class="d-none d-sm-block">SEO</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <!-- Basic Info Tab -->
                            <div class="tab-pane active" id="basic-info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku" class="form-label">SKU</label>
                                            <input type="text" class="form-control" id="sku" name="sku" value="{{ $product->sku ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode" class="form-label">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode" value="{{ $product->barcode ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ $product->short_description ?? '' }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="full_description" class="form-label">Full Description</label>
                                    <textarea class="form-control" id="full_description" name="full_description" rows="5">{{ $product->full_description ?? '' }}</textarea>
                                </div>

                                <!-- Tab Navigation -->
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary next-tab">
                                        Next <i class="bx bx-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Pricing Tab -->
                            <div class="tab-pane" id="pricing" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="purchase_price" class="form-label">Purchase Price</label>
                                            <input type="number" class="form-control" id="purchase_price" name="purchase_price" step="0.01" min="0" value="{{ $product->purchase_price ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="original_price" class="form-label">Original Price</label>
                                            <input type="number" class="form-control" id="original_price" name="original_price" step="0.01" min="0" value="{{ $product->original_price ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="sell_price" class="form-label">Sell Price <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="sell_price" name="sell_price" step="0.01" min="0" value="{{ $product->sell_price }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="discount_type" id="discount_flat" value="flat" {{ ($product->discount_type ?? 'flat') == 'flat' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="discount_flat">Flat</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="discount_type" id="discount_percentage" value="percentage" {{ ($product->discount_type ?? '') == 'percentage' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="discount_percentage">Percentage</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="discount_value" class="form-label">Discount Value</label>
                                            <input type="number" class="form-control" id="discount_value" name="discount_value" step="0.01" min="0" value="{{ $product->discount_value ?? 0 }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gst_rate" class="form-label">GST Rate (%)</label>
                                            <input type="number" class="form-control" id="gst_rate" name="gst_rate" step="0.01" min="0" max="100" value="{{ $product->gst_rate ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="tax_included" name="tax_included" value="1" {{ ($product->tax_included ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tax_included">Tax Included in Price</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Commission Type <span class="text-danger">*</span></label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="commission_type" id="commission_flat" value="flat" {{ ($product->commission_type ?? '') == 'flat' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="commission_flat">Flat</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="commission_type" id="commission_percentage" value="percentage" {{ ($product->commission_type ?? 'percentage') == 'percentage' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="commission_percentage">Percentage</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="commission_value" class="form-label">Commission Value</label>
                                            <input type="number" class="form-control" id="commission_value" name="commission_value" step="0.01" min="0" value="{{ $product->commission_value ?? 0 }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Navigation -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary prev-tab">
                                        <i class="bx bx-chevron-left"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary next-tab">
                                        Next <i class="bx bx-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Inventory Tab -->
                            <div class="tab-pane" id="inventory" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Stock Type <span class="text-danger">*</span></label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="stock_type" id="stock_limited" value="limited" {{ ($product->stock_type ?? 'limited') == 'limited' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="stock_limited">Limited</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="stock_type" id="stock_unlimited" value="unlimited" {{ ($product->stock_type ?? '') == 'unlimited' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="stock_unlimited">Unlimited</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="total_stock" class="form-label">Total Stock <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="total_stock" name="total_stock" min="0" value="{{ $product->total_stock }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="low_stock_alert" class="form-label">Low Stock Alert</label>
                                            <input type="number" class="form-control" id="low_stock_alert" name="low_stock_alert" min="0" value="{{ $product->low_stock_alert ?? 10 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="warehouse_location" class="form-label">Warehouse Location</label>
                                            <input type="text" class="form-control" id="warehouse_location" name="warehouse_location" value="{{ $product->warehouse_location ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="has_variation" name="has_variation" value="1" {{ ($product->has_variation ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_variation">This product has variations</label>
                                    </div>
                                </div>

                                <!-- Tab Navigation -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary prev-tab">
                                        <i class="bx bx-chevron-left"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary next-tab">
                                        Next <i class="bx bx-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Media Tab -->
                            <div class="tab-pane" id="media" role="tabpanel">
                                <div class="mb-3">
                                    <label for="thumbnail_image" class="form-label">Thumbnail Image</label>
                                    <input type="file" class="filepond-thumbnail-edit" id="thumbnail_image" name="thumbnail_image" accept="image/*">
                                    <small class="text-muted">Recommended size: 800x800px. Max file size: 5MB</small>
                                    @if($product->thumbnail_image)
                                        <div class="mt-3">
                                            <p class="text-muted mb-2"><strong>Current Thumbnail:</strong></p>
                                            <img src="{{ asset(PRODUCT_IMAGE_PATH . $product->thumbnail_image) }}" alt="{{ $product->product_name }}" width="150" class="img-thumbnail">
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text" value="{{ $product->image_alt_text ?? '' }}" placeholder="Enter descriptive alt text for SEO">
                                </div>

                                <div class="mb-3">
                                    <label for="gallery_images" class="form-label">Gallery Images</label>
                                    <input type="file" class="filepond-gallery-edit" id="gallery_images" name="gallery_images[]" multiple accept="image/*">
                                    <small class="text-muted">You can upload multiple images. Max 10 images, 5MB each</small>
                                    
                                    @if($product->productImages->count() > 0)
                                        <div class="mt-3">
                                            <p class="text-muted mb-2"><strong>Current Gallery Images:</strong></p>
                                            <div class="row">
                                                @foreach($product->productImages as $image)
                                                    <div class="col-md-3 mb-2" id="gallery-image-{{ $image->id }}">
                                                        <div class="position-relative">
                                                            <img src="{{ asset(PRODUCT_GALLERY_PATH . $image->image) }}" class="img-thumbnail w-100">
                                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-gallery-image" data-id="{{ $image->id }}" data-url="{{ route('owner.products.image.delete', $image->id) }}">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="video_url" class="form-label">Video URL</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" value="{{ $product->video_url ?? '' }}" placeholder="https://youtube.com/watch?v=...">
                                    <small class="text-muted">Add a YouTube or Vimeo video URL</small>
                                </div>

                                <!-- Tab Navigation -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary prev-tab">
                                        <i class="bx bx-chevron-left"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary next-tab">
                                        Next <i class="bx bx-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Shipping Tab -->
                            <div class="tab-pane" id="shipping" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" id="weight" name="weight" step="0.01" min="0" value="{{ $product->weight ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_class" class="form-label">Shipping Class <span class="text-danger">*</span></label>
                                            <select class="form-select" name="shipping_class" id="shipping_class" required>
                                                <option value="normal" {{ ($product->shipping_class ?? 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                                <option value="heavy" {{ ($product->shipping_class ?? '') == 'heavy' ? 'selected' : '' }}>Heavy</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="length" class="form-label">Length (cm)</label>
                                            <input type="number" class="form-control" id="length" name="length" step="0.01" min="0" value="{{ $product->length ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="width" class="form-label">Width (cm)</label>
                                            <input type="number" class="form-control" id="width" name="width" step="0.01" min="0" value="{{ $product->width ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="height" class="form-label">Height (cm)</label>
                                            <input type="number" class="form-control" id="height" name="height" step="0.01" min="0" value="{{ $product->height ?? 0 }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="free_shipping" name="free_shipping" value="1" {{ ($product->free_shipping ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="free_shipping">Free Shipping</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="cod_available" name="cod_available" value="1" {{ ($product->cod_available ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cod_available">COD Available</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Navigation -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary prev-tab">
                                        <i class="bx bx-chevron-left"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary next-tab">
                                        Next <i class="bx bx-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- SEO Tab -->
                            <div class="tab-pane" id="seo" role="tabpanel">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="255" value="{{ $product->meta_title ?? '' }}">
                                    <small class="text-muted">Recommended: 50-60 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3" maxlength="160">{{ $product->meta_description ?? '' }}</textarea>
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ $product->meta_keywords ?? '' }}" placeholder="keyword1, keyword2, keyword3">
                                </div>

                                <div class="mb-3">
                                    <label for="search_tags" class="form-label">Search Tags</label>
                                    <input type="text" class="form-control" id="search_tags" name="search_tags" value="{{ $product->search_tags ?? '' }}" placeholder="tag1, tag2, tag3">
                                </div>

                                <!-- Tab Navigation -->
                                <div class="d-flex justify-content-start mt-4">
                                    <button type="button" class="btn btn-secondary prev-tab">
                                        <i class="bx bx-chevron-left"></i> Previous
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Organization</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sub_category_id" class="form-label">Sub Category</label>
                            <select class="form-select" name="sub_category_id" id="sub_category_id">
                                <option value="">Select Sub Category</option>
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}" {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="child_category_id" class="form-label">Child Category</label>
                            <select class="form-select" name="child_category_id" id="child_category_id">
                                <option value="">Select Child Category</option>
                                @foreach($childCategories as $childCategory)
                                    <option value="{{ $childCategory->id }}" {{ $product->child_category_id == $childCategory->id ? 'selected' : '' }}>{{ $childCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select class="form-select" name="brand_id" id="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ ($product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="collection_id" class="form-label">Collection</label>
                            <select class="form-select" name="collection_id" id="collection_id">
                                <option value="">Select Collection</option>
                                @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}" {{ ($product->collection_id ?? '') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="product_status" class="form-label">Product Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="product_status" id="product_status" required>
                                <option value="draft" {{ ($product->product_status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ ($product->product_status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ ($product->product_status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ ($product->product_status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="is_active" class="form-label">Active Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="is_active" id="is_active" required>
                                <option value="active" {{ $product->is_active === \App\Enums\Status::Active ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $product->is_active === \App\Enums\Status::Inactive ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ ($product->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_returnable" name="is_returnable" value="1" {{ ($product->is_returnable ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_returnable">Returnable</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="return_days" class="form-label">Return Days</label>
                            <input type="number" class="form-control" id="return_days" name="return_days" min="0" value="{{ $product->return_days ?? 7 }}">
                        </div>
                    </div>
                </div>

                <div class="card mt-3" id="submitButtonCard" style="display: none;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100" id="productEditButton">
                            <i class="bx bx-loader bx-spin me-2" style="display: none" id="productEditBtnSpinner"></i>
                            Update Product
                        </button>
                        <a href="{{ route('owner.products.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Function to validate required fields in current tab
        function validateCurrentTab(tabPane) {
            var isValid = true;
            var requiredFields = $(tabPane).find('input[required], select[required], textarea[required]');
            
            requiredFields.each(function() {
                var field = $(this);
                var value = field.val();
                
                // Check if field is empty
                if (!value || value.trim() === '') {
                    isValid = false;
                    // Add visual feedback
                    field.addClass('is-invalid');
                } else {
                    field.removeClass('is-invalid');
                }
                
                // Special handling for radio buttons
                if (field.attr('type') === 'radio') {
                    var radioName = field.attr('name');
                    var isChecked = $('input[name="' + radioName + '"]:checked').length > 0;
                    if (!isChecked) {
                        isValid = false;
                    }
                }
            });
            
            return isValid;
        }
        
        // Function to update Next button state
        function updateNextButtonState() {
            var currentTabPane = $('.tab-pane.active');
            var nextButton = currentTabPane.find('.next-tab');
            
            if (nextButton.length) {
                var isValid = validateCurrentTab(currentTabPane);
                nextButton.prop('disabled', !isValid);
                
                if (!isValid) {
                    nextButton.addClass('disabled');
                } else {
                    nextButton.removeClass('disabled');
                }
            }
        }
        
        
        // Track which tabs have been completed/unlocked
        var unlockedTabs = ['#basic-info']; // Basic Info is always unlocked
        
        // Function to update tab header states (progressive unlock)
        function updateTabStates() {
            var tabs = ['#basic-info', '#pricing', '#inventory', '#media', '#shipping', '#seo'];
            var currentTab = $('.tab-pane.active').attr('id');
            var currentIndex = tabs.indexOf('#' + currentTab);
            
            // Product type field removed - no longer checking for digital products
            var isDigital = false;
            
            // Check if current tab is valid
            var isCurrentValid = validateCurrentTab($('.tab-pane.active'));
            
            // If current tab is valid and not already unlocked, unlock the next tab
            if (isCurrentValid && currentIndex < tabs.length - 1) {
                var nextTab = tabs[currentIndex + 1];
                
                // Skip shipping tab for digital products
                if (isDigital && nextTab === '#shipping') {
                    nextTab = '#seo'; // Jump directly to SEO
                }
                
                if (unlockedTabs.indexOf(nextTab) === -1) {
                    unlockedTabs.push(nextTab);
                }
            }
            
            // Update each tab link based on unlocked status
            $('.nav-tabs .nav-link').each(function() {
                var tabHref = $(this).attr('href');
                var tabIndex = tabs.indexOf(tabHref);
                
                // Hide shipping tab for digital products
                if (isDigital && tabHref === '#shipping') {
                    $(this).parent().hide();
                } else {
                    $(this).parent().show();
                }
                
                // Lock tabs that haven't been unlocked yet
                if (unlockedTabs.indexOf(tabHref) === -1) {
                    $(this).addClass('tab-locked');
                } else {
                    $(this).removeClass('tab-locked');
                }
            });
        }
        
        // Initial check on page load
        updateNextButtonState();
        updateTabStates();
        
        // Listen to all input changes in the form
        $('#productEditForm').on('input change', 'input, select, textarea', function() {
            updateNextButtonState();
            updateTabStates();
        });
        
        // Validate tab navigation when clicking on tab headers
        $('a[data-bs-toggle="tab"]').on('show.bs.tab', function (e) {
            var targetTab = $(e.target).attr('href'); // Tab being navigated TO
            var currentTab = $(e.relatedTarget).attr('href'); // Current tab
            
            // Check if target tab is unlocked
            if (unlockedTabs.indexOf(targetTab) === -1) {
                // Prevent navigation to locked tabs
                e.preventDefault();
                sendWarning('Please complete the current tab before proceeding to this section.');
                return false;
            }
            
            // Get tab indices to determine direction
            var tabs = ['#basic-info', '#pricing', '#inventory', '#media', '#shipping', '#seo'];
            var currentIndex = tabs.indexOf(currentTab);
            var targetIndex = tabs.indexOf(targetTab);
            
            // Only validate if moving forward (not backward)
            if (targetIndex > currentIndex) {
                var currentTabPane = $(currentTab);
                var isValid = validateCurrentTab(currentTabPane);
                
                if (!isValid) {
                    // Prevent tab change
                    e.preventDefault();
                    
                    // Show validation message
                    sendError('Please fill all required fields in the current tab before proceeding.');
                    
                    // Highlight empty required fields
                    currentTabPane.find('input[required], select[required], textarea[required]').each(function() {
                        if (!$(this).val() || $(this).val().trim() === '') {
                            $(this).addClass('is-invalid');
                            
                            // Add shake animation
                            $(this).addClass('animate__animated animate__shakeX');
                            setTimeout(() => {
                                $(this).removeClass('animate__animated animate__shakeX');
                            }, 1000);
                        }
                    });
                    
                    return false;
                }
            }
        });
        
        // Function to toggle submit button visibility
        function toggleSubmitButton() {
            var currentTab = $('.tab-pane.active').attr('id');
            
            // Show submit button only on SEO tab (last tab)
            if (currentTab === 'seo') {
                $('#submitButtonCard').fadeIn(300);
            } else {
                $('#submitButtonCard').fadeOut(300);
            }
        }
        
        // Initial check on page load
        toggleSubmitButton();
        
        // Update button state when tab is shown
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            updateNextButtonState();
            updateTabStates();
            toggleSubmitButton();
        });

        // Tab Navigation - Next/Previous buttons
        $('.next-tab').click(function() {
            var currentTabPane = $('.tab-pane.active');
            var isValid = validateCurrentTab(currentTabPane);
            
            if (!isValid) {
                // Show validation message
                sendError('Please fill all required fields before proceeding.');
                
                // Highlight empty required fields
                currentTabPane.find('input[required], select[required], textarea[required]').each(function() {
                    if (!$(this).val() || $(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        
                        // Add shake animation
                        $(this).addClass('animate__animated animate__shakeX');
                        setTimeout(() => {
                            $(this).removeClass('animate__animated animate__shakeX');
                        }, 1000);
                    }
                });
                
                return false;
            }
            
            var currentTab = $('.nav-tabs .nav-link.active');
            var nextTab = currentTab.parent().next('li').find('a');
            
            // Product type field removed - no longer checking for digital products
            var isDigital = false;
            var currentTabId = currentTab.attr('href');
            
            // Skip shipping tab for digital products
            if (isDigital && currentTabId === '#media') {
                nextTab = $('.nav-link[href="#seo"]'); // Jump to SEO
            }
            
            if (nextTab.length) {
                nextTab.tab('show');
                // Scroll to top of tab content
                $('html, body').animate({
                    scrollTop: $('.tab-content').offset().top - 100
                }, 300);
            }
        });

        $('.prev-tab').click(function() {
            var currentTab = $('.nav-tabs .nav-link.active');
            var prevTab = currentTab.parent().prev('li').find('a');
            if (prevTab.length) {
                prevTab.tab('show');
                // Scroll to top of tab content
                $('html, body').animate({
                    scrollTop: $('.tab-content').offset().top - 100
                }, 300);
            }
        });

        // Cascading Category Dropdowns
        $('#category_id').change(function() {
            var category_id = $(this).val();
            $('#sub_category_id').html('<option value="">Select Sub Category</option>').trigger('change');
            $('#child_category_id').html('<option value="">Select Child Category</option>').trigger('change');
            
            if (category_id) {
                $.ajax({
                    url: "{{ route('owner.sub-categories.get-by-category') }}",
                    type: "GET",
                    data: { category_id: category_id },
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#sub_category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#sub_category_id').trigger('change'); // Trigger Select2 update
                    }
                });
            }
        });

        $('#sub_category_id').change(function() {
            var sub_category_id = $(this).val();
            $('#child_category_id').html('<option value="">Select Child Category</option>').trigger('change');
            
            if (sub_category_id) {
                $.ajax({
                    url: "{{ route('owner.child-categories.get-by-sub-category') }}",
                    type: "GET",
                    data: { sub_category_id: sub_category_id },
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#child_category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#child_category_id').trigger('change'); // Trigger Select2 update
                    },
                    error: function() {
                        // If route doesn't exist, silently fail
                        console.log('Child category route not available');
                    }
                });
            }
        });



        // Stock Type Toggle
        $('input[name="stock_type"]').change(function() {
            if ($(this).val() === 'unlimited') {
                $('#total_stock').val(999999).prop('readonly', true);
            } else {
                $('#total_stock').val({{ $product->total_stock }}).prop('readonly', false);
            }
        });

        // Delete Gallery Image
        $('.delete-gallery-image').click(function() {
            var id = $(this).data('id');
            var url = $(this).data('url');
            var element = $('#gallery-image-' + id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                element.remove();
                                toastr.success(response.message);
                            } else {
                                toastr.error('Something went wrong.');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Something went wrong.');
                        }
                    });
                }
            })
        });

        // Form Submission
        $("#productEditForm").validate({
            rules: {
                product_name: { required: true },

                category_id: { required: true },
                sell_price: { required: true, number: true, min: 0 },
                total_stock: { required: true, number: true, min: 0 },
                discount_type: { required: true },
                commission_type: { required: true },
                stock_type: { required: true },
                shipping_class: { required: true },
                product_status: { required: true },
                status: { required: true },
                
                // Media validation
                thumbnail_image: {
                    extension: "jpeg|jpg|png|gif|webp"
                }
            },
            messages: {
                product_name: { required: "Product name is required" },
                category_id: { required: "Category is required" },
                sell_price: { required: "Sell price is required", number: "Sell price must be a number", min: "Sell price must be greater than or equal to 0" },
                total_stock: { required: "Total stock is required", number: "Total stock must be a number", min: "Total stock must be greater than or equal to 0" },
                discount_type: { required: "Discount type is required" },
                commission_type: { required: "Commission type is required" },
                stock_type: { required: "Stock type is required" },
                shipping_class: { required: "Shipping class is required" },
                product_status: { required: "Product status is required" },
                status: { required: "Status is required" },
                
                // Media validation messages
                thumbnail_image: {
                    extension: "Please upload a valid image file (jpeg, jpg, png, gif, or webp)"
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.products.update', $product->id) }}",
                    method: "POST",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#productEditButton').attr('disabled', true);
                        $("#productEditBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.products.index') }}";
                            }, 1000);
                        } else {
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data && data.errors) {
                            $.each(data.errors, function(key, value){
                                sendError(value[0]);
                            });
                        } else if (data && data.message) {
                            sendError(data.message);
                        } else {
                            sendError('Something went wrong. Please try again.');
                        }
                    },
                    complete: function () {
                        $('#productEditButton').attr('disabled', false);
                        $("#productEditBtnSpinner").hide();
                    },
                });
            }
        });
        
        // ========================================
        // FilePond Initialization for Edit Form
        // ========================================
        
        // Register FilePond plugins
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateType
        );
        
        // Initialize FilePond for Thumbnail Image (Single Upload) - Edit Form
        const thumbnailPondEdit = FilePond.create(document.querySelector('.filepond-thumbnail-edit'), {
            acceptedFileTypes: ['image/*'],
            maxFileSize: '5MB',
            labelIdle: 'Drag & Drop your thumbnail image or <span class="filepond--label-action">Browse</span>',
            labelFileTypeNotAllowed: 'Invalid file type',
            fileValidateTypeLabelExpectedTypes: 'Expects image files',
            labelMaxFileSizeExceeded: 'File is too large',
            labelMaxFileSize: 'Maximum file size is {filesize}',
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 800,
            imageResizeTargetHeight: 800,
            imageResizeMode: 'contain',
            imageResizeUpscale: false,
            stylePanelLayout: 'compact',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
        });
        
        // Handle thumbnail file errors
        thumbnailPondEdit.on('addfile', (error, file) => {
            if (error) {
                sendError(error.main);
                setTimeout(() => {
                    thumbnailPondEdit.removeFile(file.id);
                }, 2000);
            }
        });
        
        // Initialize FilePond for Gallery Images (Multiple Upload) - Edit Form
        const galleryPondEdit = FilePond.create(document.querySelector('.filepond-gallery-edit'), {
            allowMultiple: true,
            maxFiles: 10,
            acceptedFileTypes: ['image/*'],
            maxFileSize: '5MB',
            labelIdle: 'Drag & Drop your gallery images or <span class="filepond--label-action">Browse</span>',
            labelFileTypeNotAllowed: 'Invalid file type',
            fileValidateTypeLabelExpectedTypes: 'Expects image files',
            labelMaxFileSizeExceeded: 'File is too large',
            labelMaxFileSize: 'Maximum file size is {filesize}',
            labelMaxTotalFileSizeExceeded: 'Maximum total size exceeded',
            maxTotalFileSize: '50MB',
            imagePreviewHeight: 120,
            imageResizeTargetWidth: 1200,
            imageResizeTargetHeight: 1200,
            imageResizeMode: 'contain',
            imageResizeUpscale: false,
            allowReorder: true,
            itemInsertLocation: 'after',
        });
        
        // Handle gallery file errors
        galleryPondEdit.on('addfile', (error, file) => {
            if (error) {
                sendError(error.main);
                setTimeout(() => {
                    galleryPondEdit.removeFile(file.id);
                }, 2000);
            }
        });
        
        // Warning when max files reached
        galleryPondEdit.on('warning', (error, file) => {
            if (error.body === 'Max files') {
                sendWarning('Maximum 10 images allowed in gallery');
            }
        });
        
        // ========================================
        // Select2 Initialization
        // ========================================
        
        // Initialize Select2 for all select fields
        $('#category_id').select2({
            placeholder: 'Select Category',
            allowClear: true,
            width: '100%'
        });
        
        $('#sub_category_id').select2({
            placeholder: 'Select Sub Category',
            allowClear: true,
            width: '100%'
        });
        
        $('#child_category_id').select2({
            placeholder: 'Select Child Category',
            allowClear: true,
            width: '100%'
        });
        
        $('#brand_id').select2({
            placeholder: 'Select Brand',
            allowClear: true,
            width: '100%'
        });
        
        $('#collection_id').select2({
            placeholder: 'Select Collection',
            allowClear: true,
            width: '100%'
        });
        
        $('#product_status').select2({
            placeholder: 'Select Product Status',
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: Infinity // Disable search for simple dropdown
        });
        
        $('#is_active').select2({
            placeholder: 'Select Active Status',
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: Infinity // Disable search for simple dropdown
        });
        
        $('#shipping_class').select2({
            placeholder: 'Select Shipping Class',
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: Infinity // Disable search for simple dropdown
        });
    });
</script>
@endsection
