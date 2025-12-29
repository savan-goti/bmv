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
                                <a class="nav-link" data-bs-toggle="tab" href="#tax-units" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-percentage"></i></span>
                                    <span class="d-none d-sm-block">Tax & Units</span>
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
                                <a class="nav-link" data-bs-toggle="tab" href="#variations" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-tags"></i></span>
                                    <span class="d-none d-sm-block">Variations</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#other-details" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                    <span class="d-none d-sm-block">Other</span>
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
                                <x-input-field 
                                    name="product_name" 
                                    label="Product Name" 
                                    placeholder="Enter product name"
                                    value="{{ $product->product_name }}"
                                    required 
                                />

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input-field 
                                            name="sku" 
                                            id="sku_input"
                                            label="SKU" 
                                            placeholder="Example: ms-32-red-puma"
                                            value="{{ $product->sku }}"
                                            help-text="Stock Keeping Unit (Auto-suggest format: ms-32-red-puma)"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            name="barcode" 
                                            label="Barcode" 
                                            placeholder="Auto-generated"
                                            value="{{ $product->barcode }}"
                                            help-text="Barcode is fixed for existing products"
                                            readonly
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="number"
                                            name="warranty_value" 
                                            label="Warranty" 
                                            placeholder="Enter numeric value"
                                            value="{{ $product->warranty_value }}"
                                            min="0"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="select"
                                            name="warranty_unit" 
                                            label="Warranty Unit"
                                        >
                                            <option value="">Select Unit</option>
                                            <option value="months" {{ $product->warranty_unit == 'months' ? 'selected' : '' }}>Months</option>
                                            <option value="years" {{ $product->warranty_unit == 'years' ? 'selected' : '' }}>Years</option>
                                        </x-input-field>
                                    </div>
                                </div>

                                <x-input-field 
                                    type="textarea" 
                                    name="short_description" 
                                    label="Short Description" 
                                    placeholder="Brief product summary"
                                    value="{{ $product->short_description }}"
                                    rows="3"
                                />

                                <x-input-field 
                                    type="textarea" 
                                    name="full_description" 
                                    label="Full Description" 
                                    placeholder="Detailed product description"
                                    value="{{ $product->full_description }}"
                                    rows="5"
                                />

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
                                        <x-input-field 
                                            type="number" 
                                            name="purchase_price" 
                                            label="Purchase Price" 
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0"
                                            value="{{ $product->purchase_price }}"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="original_price" 
                                            id="original_price"
                                            label="Original Price (MRP)" 
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0"
                                            value="{{ $product->original_price }}"
                                            required
                                            help-text="Must be ≥ Sell Price"
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="sell_price" 
                                            id="sell_price"
                                            label="Sell Price" 
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0"
                                            value="{{ $product->sell_price }}"
                                            required
                                            help-text="Must be ≤ Original Price"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-radio-group 
                                            name="discount_type" 
                                            label="Discount Type" 
                                            :options="['flat' => 'Flat', 'percentage' => 'Percentage']"
                                            selected="{{ $product->discount_type }}"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="number" 
                                            name="discount_value" 
                                            label="Discount Value" 
                                            placeholder="0"
                                            step="0.01"
                                            min="0"
                                            value="{{ $product->discount_value }}"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="number" 
                                            name="gst_rate" 
                                            label="GST Rate (%)" 
                                            placeholder="0"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            value="{{ $product->gst_rate }}"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-checkbox 
                                            name="tax_included" 
                                            value="1" 
                                            label="Tax Included in Price"
                                            :checked="$product->tax_included == 1"
                                            container-class="mb-3 mt-4"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-radio-group 
                                            name="commission_type" 
                                            label="Commission Type" 
                                            :options="['flat' => 'Flat', 'percentage' => 'Percentage']"
                                            selected="{{ $product->commission_type }}"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="number" 
                                            name="commission_value" 
                                            label="Commission Value" 
                                            placeholder="0"
                                            step="0.01"
                                            min="0"
                                            value="{{ $product->commission_value }}"
                                        />
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

                            <!-- Tax & Units Tab -->
                            <div class="tab-pane" id="tax-units" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">HSN/SAC Type</label>
                                            <select class="form-select" id="hsn_sac_type">
                                                <option value="product" {{ ($product->hsnSac->type ?? 'product') == 'product' ? 'selected' : '' }}>Product (HSN)</option>
                                                <option value="service" {{ ($product->hsnSac->type ?? '') == 'service' ? 'selected' : '' }}>Service (SAC)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="select" 
                                            name="hsn_sac_id" 
                                            label="HSN/SAC Code" 
                                            placeholder="Select HSN/SAC"
                                            required
                                            class="select2"
                                        >
                                            @foreach($hsnSacs as $hsn)
                                                <option value="{{ $hsn->id }}" data-type="{{ $hsn->type }}" {{ $product->hsn_sac_id == $hsn->id ? 'selected' : '' }}>{{ $hsn->code }} - {{ $hsn->description }}</option>
                                            @endforeach
                                        </x-input-field>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="number" 
                                            name="total_stock" 
                                            label="Quantity (Total Stock)" 
                                            placeholder="1"
                                            min="1"
                                            value="{{ $product->total_stock }}"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="select" 
                                            name="unit_id" 
                                            label="Unit" 
                                            placeholder="Select Unit"
                                            required
                                            class="select2"
                                        >
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" data-type="{{ $unit->type }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                                            @endforeach
                                        </x-input-field>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-radio-group 
                                            name="stock_type" 
                                            label="Stock Type" 
                                            :options="['limited' => 'Limited', 'unlimited' => 'Unlimited']"
                                            selected="{{ $product->stock_type }}"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="number" 
                                            name="low_stock_alert" 
                                            label="Low Stock Alert" 
                                            placeholder="10"
                                            min="0"
                                            value="{{ $product->low_stock_alert }}"
                                        />
                                    </div>
                                </div>

                                <x-input-field 
                                    name="warehouse_location" 
                                    label="Warehouse Location" 
                                    placeholder="Enter warehouse location"
                                    value="{{ $product->warehouse_location }}"
                                />

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
                                    <input type="file" class="filepond-thumbnail-edit" id="thumbnail_image" name="thumbnail_image" accept="image/jpeg,image/png,image/webp">
                                    <small class="text-muted">Optional (Keep empty to preserve current). JPG, PNG, WEBP only. Max 5MB.</small>
                                    @if($product->thumbnail_image)
                                        <div class="mt-3">
                                            <p class="text-muted mb-2"><strong>Current Thumbnail:</strong></p>
                                            <img src="{{ asset(PRODUCT_IMAGE_PATH . $product->thumbnail_image) }}" alt="{{ $product->product_name }}" width="150" class="img-thumbnail border border-primary">
                                        </div>
                                    @endif
                                </div>

                                <x-input-field 
                                    name="image_alt_text" 
                                    label="Image Alt Text" 
                                    placeholder="Enter descriptive alt text for SEO"
                                    value="{{ $product->image_alt_text }}"
                                    help-text="Improves SEO and accessibility"
                                />

                                <div class="mb-3">
                                    <label for="gallery_images" class="form-label">Gallery Images</label>
                                    <input type="file" class="filepond-gallery-edit" id="gallery_images" name="gallery_images[]" multiple accept="image/*">
                                    <small class="text-muted">Optional. You can upload multiple images. Max 10 images, 5MB each</small>
                                    
                                    @if($product->productImages->count() > 0)
                                        <div class="mt-3">
                                            <p class="text-muted mb-2"><strong>Current Gallery Images:</strong></p>
                                            <div class="row">
                                                @foreach($product->productImages as $image)
                                                    <div class="col-md-3 mb-2" id="gallery-image-{{ $image->id }}">
                                                         <div class="position-relative">
                                                             <img src="{{ asset(PRODUCT_GALLERY_PATH . $image->image) }}" class="img-thumbnail w-100 border border-info">
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
                                    <label for="product_videos" class="form-label">Product Videos</label>
                                    <input type="file" class="filepond-videos" id="product_videos" name="product_videos[]" multiple accept="video/mp4,video/webm">
                                    <small class="text-muted">Optional. MP4, WEBM only. Max 20MB per video.</small>
                                    
                                    @if($product->product_videos && count($product->product_videos) > 0)
                                        <div class="mt-3">
                                            <p class="text-muted mb-2"><strong>Current Videos:</strong></p>
                                            <div class="row">
                                                @foreach($product->product_videos as $index => $video)
                                                    <div class="col-md-4 mb-2" id="video-{{ $index }}">
                                                        <div class="card bg-light p-2 text-center h-100 border border-info shadow-none">
                                                            <div class="card-body">
                                                                <i class="fas fa-video fa-2x text-primary mb-2"></i>
                                                                <p class="small mb-0 text-truncate text-dark">{{ basename($video) }}</p>
                                                                <a href="{{ asset($video) }}" target="_blank" class="btn btn-sm btn-soft-info mt-2">
                                                                    <i class="fas fa-play me-1"></i> View
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
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
                                        <x-input-field 
                                            type="number" 
                                            name="weight" 
                                            label="Weight (kg)" 
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0.01"
                                            value="{{ $product->weight }}"
                                            required
                                            help-text="Numeric value > 0"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="select" 
                                            name="shipping_class" 
                                            label="Shipping Class" 
                                            required
                                            class="select2"
                                        >
                                            <option value="normal" {{ $product->shipping_class == 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="heavy" {{ $product->shipping_class == 'heavy' ? 'selected' : '' }}>Heavy</option>
                                        </x-input-field>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="length" 
                                            label="Length (cm)" 
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0.01"
                                            value="{{ $product->length }}"
                                            required
                                            help-text="Numeric value > 0"
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="width" 
                                            label="Width (cm)" 
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0.01"
                                            value="{{ $product->width }}"
                                            required
                                            help-text="Numeric value > 0"
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="height" 
                                            label="Height (cm)" 
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0.01"
                                            value="{{ $product->height }}"
                                            required
                                            help-text="Numeric value > 0"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-checkbox 
                                            name="free_shipping" 
                                            value="1" 
                                            label="Free Shipping"
                                            :checked="$product->free_shipping == 1"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-checkbox 
                                            name="cod_available" 
                                            value="1" 
                                            label="COD Available"
                                            :checked="$product->cod_available == 1"
                                        />
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

                            <!-- Variations Tab -->
                            <div class="tab-pane" id="variations" role="tabpanel">
                                <x-checkbox 
                                    name="has_variation" 
                                    id="has_variation"
                                    value="1" 
                                    label="This product has variations"
                                    :checked="$product->has_variation == 1"
                                />

                                <div class="variation-fields mt-3" style="{{ $product->has_variation == 1 ? '' : 'display: none;' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="select" 
                                                name="color_id" 
                                                id="color_id"
                                                label="Base Color" 
                                                placeholder="Select Color"
                                                class="select2"
                                            >
                                                <option value="">No Color</option>
                                                @foreach($colors as $color)
                                                    <option value="{{ $color->id }}" {{ $product->color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                                @endforeach
                                            </x-input-field>
                                        </div>
                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="select" 
                                                name="size_id" 
                                                id="size_id"
                                                label="Base Size" 
                                                placeholder="Select Size"
                                                class="select2"
                                            >
                                                <option value="">No Size</option>
                                                @foreach($sizes as $size)
                                                    <option value="{{ $size->id }}" {{ $product->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                                @endforeach
                                            </x-input-field>
                                        </div>
                                    </div>
                                    <div class="alert alert-info py-2 mt-2" role="alert">
                                        <i class="fas fa-info-circle me-1"></i> These selections will be used for auto-generating the SKU for each variation.
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

                            <!-- Other Details Tab -->
                            <div class="tab-pane" id="other-details" role="tabpanel">
                                <div class="mb-4">
                                    <h5 class="font-size-14 mb-3"><i class="fas fa-truck-loading me-2 text-primary"></i>Supplier Information</h5>
                                    <x-input-field 
                                        type="select" 
                                        name="supplier_id" 
                                        label="Supplier" 
                                        placeholder="Select Supplier"
                                        class="select2"
                                    >
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }} ({{ $supplier->email }})</option>
                                        @endforeach
                                    </x-input-field>
                                </div>

                                <div class="card bg-light border-0 shadow-none border">
                                    <div class="card-body">
                                        <h5 class="font-size-14 mb-3 text-dark"><i class="fas fa-box-open me-2 text-warning"></i>Packer Details</h5>
                                        <x-input-field 
                                            name="packer_name" 
                                            label="Packer Name" 
                                            placeholder="Enter company/individual name"
                                            value="{{ $product->packer_name }}"
                                        />
                                        
                                        <x-input-field 
                                            type="textarea" 
                                            name="packer_address" 
                                            label="Packer Address" 
                                            placeholder="Enter full address"
                                            value="{{ $product->packer_address }}"
                                            rows="2"
                                        />
                                        
                                        <x-input-field 
                                            name="packer_gst" 
                                            label="Packer GST Number" 
                                            placeholder="Ex: 22AAAAA0000A1Z5"
                                            value="{{ $product->packer_gst }}"
                                        />
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
                                <x-input-field 
                                    name="meta_title" 
                                    label="Meta Title" 
                                    placeholder="Enter meta title"
                                    maxlength="255"
                                    value="{{ $product->meta_title ?? '' }}"
                                    help-text="Recommended: 50-60 characters"
                                />

                                <x-input-field 
                                    type="textarea" 
                                    name="meta_description" 
                                    label="Meta Description" 
                                    placeholder="Enter meta description"
                                    rows="3"
                                    maxlength="160"
                                    value="{{ $product->meta_description ?? '' }}"
                                    help-text="Recommended: 150-160 characters"
                                />

                                <x-input-field 
                                    name="meta_keywords" 
                                    label="Meta Keywords" 
                                    placeholder="keyword1, keyword2, keyword3"
                                    value="{{ $product->meta_keywords ?? '' }}"
                                />

                                <x-input-field 
                                    name="search_tags" 
                                    label="Search Tags" 
                                    placeholder="tag1, tag2, tag3"
                                    value="{{ $product->search_tags ?? '' }}"
                                />

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
                        <x-input-field 
                            type="select" 
                            name="category_id" 
                            label="Category" 
                            placeholder="Select Category"
                            required
                        >
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="sub_category_id" 
                            label="Sub Category" 
                            placeholder="Select Sub Category"
                        >
                            @foreach($subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="child_category_id" 
                            label="Child Category" 
                            placeholder="Select Child Category"
                        >
                            @foreach($childCategories as $childCategory)
                                <option value="{{ $childCategory->id }}" {{ $product->child_category_id == $childCategory->id ? 'selected' : '' }}>{{ $childCategory->name }}</option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="brand_id" 
                            label="Brand" 
                            placeholder="Select Brand"
                        >
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ ($product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="collection_id" 
                            label="Collection" 
                            placeholder="Select Collection"
                        >
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}" {{ ($product->collection_id ?? '') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                            @endforeach
                        </x-input-field>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Status</h5>
                    </div>
                    <div class="card-body">
                        <x-input-field 
                            type="select" 
                            name="product_status" 
                            label="Product Status" 
                            required
                        >
                            <option value="draft" {{ ($product->product_status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ ($product->product_status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ ($product->product_status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ ($product->product_status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="is_active" 
                            label="Active Status" 
                            required
                        >
                            <option value="active" {{ $product->is_active === \App\Enums\Status::Active ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $product->is_active === \App\Enums\Status::Inactive ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

                        <x-checkbox 
                            name="is_featured" 
                            value="1" 
                            label="Featured Product"
                            :checked="$product->is_featured ?? false"
                        />

                        <x-checkbox 
                            name="is_returnable" 
                            value="1" 
                            label="Returnable"
                            :checked="$product->is_returnable ?? true"
                        />

                        <x-input-field 
                            type="number" 
                            name="return_days" 
                            label="Return Days" 
                            placeholder="7"
                            min="0"
                            value="{{ $product->return_days ?? 7 }}"
                            help-text="Number of days for product return"
                        />
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
        // Tab configuration
        const tabs = [
            '#basic-info', 
            '#pricing', 
            '#tax-units', 
            '#media', 
            '#shipping', 
            '#variations', 
            '#other-details', 
            '#seo'
        ];

        // Track which tabs have been completed/unlocked
        let unlockedTabs = tabs.slice(); // Unlock all tabs for Edit mode

        function validateCurrentTab(tabPane) {
            let isValid = true;
            const requiredFields = $(tabPane).find('input[required], select[required], textarea[required]');
            
            requiredFields.each(function() {
                const field = $(this);
                const value = field.val();
                
                if (!value || (typeof value === 'string' && value.trim() === '')) {
                    isValid = false;
                    field.addClass('is-invalid');
                } else {
                    field.removeClass('is-invalid');
                }
                
                if (field.attr('type') === 'radio') {
                    const radioName = field.attr('name');
                    const isChecked = $('input[name="' + radioName + '"]:checked').length > 0;
                    if (!isChecked) isValid = false;
                }
            });

            // Special check for Sell Price vs Original Price
            if ($(tabPane).attr('id') === 'pricing') {
                const originalPrice = parseFloat($('#original_price').val()) || 0;
                const sellPrice = parseFloat($('#sell_price').val()) || 0;
                if (sellPrice > originalPrice) {
                    isValid = false;
                    $('#sell_price').addClass('is-invalid');
                }
            }
            
            return isValid;
        }
        
        function updateNextButtonState() {
            const currentTabPane = $('.tab-pane.active');
            const nextButton = currentTabPane.find('.next-tab');
            
            if (nextButton.length) {
                const isValid = validateCurrentTab(currentTabPane);
                nextButton.prop('disabled', !isValid).toggleClass('disabled', !isValid);
            }
        }
        
        function updateTabStates() {
            const currentTab = $('.tab-pane.active').attr('id');
            const currentIndex = tabs.indexOf('#' + currentTab);
            const isCurrentValid = validateCurrentTab($('.tab-pane.active'));
            
            if (isCurrentValid && currentIndex < tabs.length - 1) {
                const nextTab = tabs[currentIndex + 1];
                if (unlockedTabs.indexOf(nextTab) === -1) {
                    unlockedTabs.push(nextTab);
                }
            }
            
            $('.nav-tabs .nav-link').each(function() {
                const tabHref = $(this).attr('href');
                if (unlockedTabs.indexOf(tabHref) === -1) {
                    $(this).addClass('tab-locked');
                } else {
                    $(this).removeClass('tab-locked');
                }
            });
        }
        
        function toggleSubmitButton() {
            const currentTab = $('.tab-pane.active').attr('id');
            if (currentTab === 'seo') {
                $('#submitButtonCard').fadeIn(300);
            } else {
                $('#submitButtonCard').fadeOut(300);
            }
        }

        // Initial check on page load
        updateNextButtonState();
        updateTabStates();
        toggleSubmitButton();
        
        // Listen to all input changes in the form
        $('#productEditForm').on('input change', 'input, select, textarea', function() {
            updateNextButtonState();
            updateTabStates();
        });
        
        // Validate tab navigation when clicking on tab headers
        $('a[data-bs-toggle="tab"]').on('show.bs.tab', function (e) {
            const targetTab = $(e.target).attr('href');
            const currentTab = $(e.relatedTarget).attr('href');
            
            if (unlockedTabs.indexOf(targetTab) === -1) {
                e.preventDefault();
                sendWarning('Please complete the current tab before proceeding.');
                return false;
            }
            
            const currentIndex = tabs.indexOf(currentTab);
            const targetIndex = tabs.indexOf(targetTab);
            
            if (targetIndex > currentIndex) {
                if (!validateCurrentTab($(currentTab))) {
                    e.preventDefault();
                    sendError('Please fill all required fields in the current tab.');
                    return false;
                }
            }
        });
        
        // Update button state when tab is shown
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            updateNextButtonState();
            updateTabStates();
            toggleSubmitButton();
        });

        $('.next-tab').click(function() {
            const currentTabPane = $('.tab-pane.active');
            if (validateCurrentTab(currentTabPane)) {
                const currentTab = $('.nav-tabs .nav-link.active');
                const nextTab = currentTab.parent().next('li').find('a');
                if (nextTab.length) {
                    nextTab.tab('show');
                    $('html, body').animate({ scrollTop: $('.tab-content').offset().top - 100 }, 300);
                }
            } else {
                sendError('Please fill all required fields before proceeding.');
            }
        });

        $('.prev-tab').click(function() {
            const currentTab = $('.nav-tabs .nav-link.active');
            const prevTab = currentTab.parent().prev('li').find('a');
            if (prevTab.length) {
                prevTab.tab('show');
                $('html, body').animate({ scrollTop: $('.tab-content').offset().top - 100 }, 300);
            }
        });

        // SKU Auto-suggestion Logic
        function suggestSKU() {
            const name = $('input[name="product_name"]').val() || '';
            const color = $('#color_id option:selected').text().trim();
            const size = $('#size_id option:selected').text().trim();
            
            if (name) {
                let sku = name.substring(0, 3).toUpperCase();
                if (color && color !== 'No Color') sku += '-' + color.substring(0, 3).toUpperCase();
                if (size && size !== 'No Size') sku += '-' + size.substring(0, 2).toUpperCase();
                sku += '-' + Math.floor(1000 + Math.random() * 9000);
                
                // Only suggest if SKU is empty
                if (!$('#sku_input').val()) {
                    $('#sku_input').val(sku);
                }
            }
        }

        // HSN/SAC and Unit Filtering
        function filterHsnAndUnits() {
            const type = $('#hsn_sac_type').val();
            
            // Filter HSN/SAC options
            $('#hsn_sac_id option').each(function() {
                const optionType = $(this).data('type');
                if (!optionType || optionType === type) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Filter Unit options
            $('#unit_id option').each(function() {
                const optionType = $(this).data('type');
                if (!optionType || optionType === type) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Re-initialize Select2 to reflect hidden options
            $('#hsn_sac_id, #unit_id').select2({ width: '100%' });
        }

        // Trigger SKU suggestion
        $('input[name="product_name"], #color_id, #size_id').on('change', suggestSKU);

        // HSN/SAC Type change
        $('#hsn_sac_type').on('change', function() {
            $('#hsn_sac_id, #unit_id').val('').trigger('change');
            filterHsnAndUnits();
        });

        // Variation toggle
        $('#has_variation').change(function() {
            if ($(this).is(':checked')) {
                $('.variation-fields').slideDown();
            } else {
                $('.variation-fields').slideUp();
                $('#color_id, #size_id').val('').trigger('change');
            }
        });

        // Initial filter call
        filterHsnAndUnits();

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

        // Form Validation & Submission
        $("#productEditForm").validate({
            rules: {
                product_name: { required: true },
                sku: { required: true },
                purchase_price: { required: true, number: true },
                original_price: { required: true, number: true },
                sell_price: { 
                    required: true, 
                    number: true,
                    max: function() { return parseFloat($('#original_price').val()) || 9999999; }
                },
                hsn_sac_id: { required: true },
                unit_id: { required: true },
                total_stock: { required: true, min: 1 },
                weight: { required: true, min: 0.01 },
                length: { required: true, min: 0.01 },
                width: { required: true, min: 0.01 },
                height: { required: true, min: 0.01 }
            },
            messages: {
                sell_price: { max: "Sell price cannot be greater than Original Price" }
            },
            errorPlacement: function (error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {
                    error.insertAfter(element.closest('.form-check').parent());
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
                            setTimeout(() => { window.location.href = "{{ route('owner.products.index') }}"; }, 1000);
                        } else {
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) { sendError(value[0]); });
                        } else {
                            sendError('An unexpected error occurred.');
                        }
                    },
                    complete: function () {
                        $('#productEditButton').attr('disabled', false);
                        $("#productEditBtnSpinner").hide();
                    }
                });
            }
        });
        
        // FilePond Initialization
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );
        
        FilePond.create(document.querySelector('.filepond-thumbnail-edit'), {
            acceptedFileTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'],
            maxFileSize: '5MB',
            labelIdle: 'Drop thumbnail or <span class="filepond--label-action">Browse</span>'
        });
        
        FilePond.create(document.querySelector('.filepond-gallery-edit'), {
            allowMultiple: true,
            maxFiles: 10,
            acceptedFileTypes: ['image/*'],
            maxFileSize: '5MB'
        });

        FilePond.create(document.querySelector('.filepond-videos'), {
            allowMultiple: true,
            maxFiles: 5,
            acceptedFileTypes: ['video/mp4', 'video/webm'],
            maxFileSize: '20MB',
            labelIdle: 'Drop videos or <span class="filepond--label-action">Browse</span>'
        });

        // Select2 Initialization
        $('.select2').each(function() {
            $(this).select2({
                placeholder: $(this).attr('placeholder') || 'Select Option',
                allowClear: true,
                width: '100%'
            });
        });

        // Stock Type Handle
        $('input[name="stock_type"]').change(function() {
            if ($(this).val() === 'unlimited') {
                $('input[name="total_stock"]').val(9999).prop('readonly', true);
            } else {
                $('input[name="total_stock"]').val("{{ $product->total_stock }}").prop('readonly', false);
            }
        });
    });
</script>
@endsection
