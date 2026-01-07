@extends('owner.master')
@section('title','Create Product')

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
                <h4 class="mb-sm-0">Create Product</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.products.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <form id="productCreateForm" method="POST" enctype="multipart/form-data">
        @csrf
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
                                    required 
                                />

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input-field 
                                            name="sku" 
                                            id="sku_input"
                                            label="SKU" 
                                            placeholder="Example: ms-32-red-puma"
                                            help-text="Stock Keeping Unit (Auto-suggest format: ms-32-red-puma)"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input-field 
                                            name="barcode" 
                                            label="Barcode" 
                                            placeholder="Auto-generated"
                                            help-text="Barcode will be auto-generated in controller"
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
                                            <option value="months">Months</option>
                                            <option value="years">Years</option>
                                        </x-input-field>
                                    </div>
                                </div>

                                <x-input-field 
                                    type="textarea" 
                                    name="short_description" 
                                    label="Short Description" 
                                    placeholder="Brief product summary"
                                    rows="3"
                                />

                                <x-input-field 
                                    type="textarea" 
                                    name="full_description" 
                                    label="Full Description" 
                                    placeholder="Detailed product description"
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
                                            selected="flat"
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
                                            value="0"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-checkbox 
                                            name="tax_included" 
                                            value="1" 
                                            label="Tax Included in Price"
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
                                            selected="percentage"
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
                                            value="0"
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
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">HSN/SAC Type</label>
                                            <select class="form-select" id="hsn_sac_type">
                                                <option value="product">Product (HSN)</option>
                                                <option value="service">Service (SAC)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="select" 
                                            name="hsn_sac_id" 
                                            label="HSN/SAC Code" 
                                            placeholder="Select HSN/SAC"
                                            required
                                            class="select2"
                                        >
                                            @foreach($hsnSacs as $hsn)
                                                <option value="{{ $hsn->id }}" data-type="{{ $hsn->type }}" data-gst="{{ $hsn->gst }}">{{ $hsn->code }} - {{ $hsn->description }}</option>
                                            @endforeach
                                        </x-input-field>
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="gst_rate" 
                                            id="gst_rate_input"
                                            label="GST Rate (%)" 
                                            placeholder="0"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            value="0"
                                            readonly
                                        />
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
                                            value="1"
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
                                                <option value="{{ $unit->id }}" data-category="{{ $unit->category }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
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
                                            selected="limited"
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
                                            value="10"
                                        />
                                    </div>
                                </div>

                                <x-input-field 
                                    name="warehouse_location" 
                                    label="Warehouse Location" 
                                    placeholder="Enter warehouse location"
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
                                    <label for="thumbnail_image" class="form-label">Thumbnail Image <span class="text-danger">*</span></label>
                                    <input type="file" class="filepond-thumbnail" id="thumbnail_image" name="thumbnail_image" accept="image/jpeg,image/png,image/webp" required>
                                    <small class="text-muted">Required. JPG, PNG, WEBP only. Max 5MB.</small>
                                </div>

                                <x-input-field 
                                    name="image_alt_text" 
                                    label="Image Alt Text" 
                                    placeholder="Enter descriptive alt text for SEO"
                                    help-text="Improves SEO and accessibility"
                                />

                                <div class="mb-3">
                                    <label for="gallery_images" class="form-label">Gallery Images</label>
                                    <input type="file" class="filepond-gallery" id="gallery_images" name="gallery_images[]" multiple accept="image/*">
                                    <small class="text-muted">Optional. You can upload multiple images. Max 10 images, 5MB each</small>
                                </div>

                                <div class="mb-3">
                                    <label for="product_videos" class="form-label">Product Videos</label>
                                    <input type="file" class="filepond-videos" id="product_videos" name="product_videos[]" multiple accept="video/mp4,video/webm">
                                    <small class="text-muted">Optional. MP4, WEBM only. Max 20MB per video.</small>
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
                                    value="1" 
                                    label="This product has variations"
                                />

                                <div class="variation-fields mt-3" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="select" 
                                                name="color_id" 
                                                label="Color" 
                                                placeholder="Select Color"
                                                class="select2"
                                            >
                                                <option value="">No Color</option>
                                                @foreach($colors as $color)
                                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                @endforeach
                                            </x-input-field>
                                        </div>
                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="select" 
                                                name="size_id" 
                                                label="Size" 
                                                placeholder="Select Size"
                                                class="select2"
                                            >
                                                <option value="">No Size</option>
                                                @foreach($sizes as $size)
                                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                                @endforeach
                                            </x-input-field>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="number" 
                                                name="product_weight" 
                                                label="Product Weight (kg)" 
                                                placeholder="0.00"
                                                step="0.01"
                                                min="0"
                                                help-text="Actual weight of the product"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="number" 
                                                name="shipping_weight" 
                                                label="Shipping Weight (kg)" 
                                                placeholder="0.00"
                                                step="0.01"
                                                min="0"
                                                help-text="Weight including packaging"
                                            />
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

                            <!-- Other Details Tab -->
                            <div class="tab-pane" id="other-details" role="tabpanel">
                                <x-input-field 
                                    type="select" 
                                    name="supplier_id" 
                                    label="Supplier" 
                                    placeholder="Select Supplier"
                                    class="select2"
                                >
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </x-input-field>

                                <div class="card mt-3">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Packer Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <x-input-field 
                                            name="packer_name" 
                                            label="Packer Name" 
                                            placeholder="Enter packer name"
                                        />
                                        <x-input-field 
                                            type="textarea"
                                            name="packer_address" 
                                            label="Packer Address" 
                                            placeholder="Enter packer address"
                                            rows="2"
                                        />
                                        <x-input-field 
                                            name="packer_gst" 
                                            label="Packer GST Number" 
                                            placeholder="Enter packer GST (Optional)"
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

                            <!-- Shipping Tab -->
                            <div class="tab-pane" id="shipping" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input-field 
                                            type="number" 
                                            name="weight" 
                                            label="Weight (kg)" 
                                            placeholder="0.01"
                                            step="0.01"
                                            min="0.01"
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
                                        >
                                            <option value="normal" selected>Normal</option>
                                            <option value="heavy">Heavy</option>
                                        </x-input-field>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="length" 
                                            label="Length (cm)" 
                                            placeholder="0.01"
                                            step="0.01"
                                            min="0.01"
                                            required
                                            help-text="Numeric value > 0"
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="width" 
                                            label="Width (cm)" 
                                            placeholder="0.01"
                                            step="0.01"
                                            min="0.01"
                                            required
                                            help-text="Numeric value > 0"
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-input-field 
                                            type="number" 
                                            name="height" 
                                            label="Height (cm)" 
                                            placeholder="0.01"
                                            step="0.01"
                                            min="0.01"
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
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-checkbox 
                                            name="cod_available" 
                                            value="1" 
                                            label="COD Available"
                                            checked
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
                                    help-text="Recommended: 50-60 characters"
                                />

                                <x-input-field 
                                    type="textarea" 
                                    name="meta_description" 
                                    label="Meta Description" 
                                    placeholder="Enter meta description"
                                    rows="3"
                                    maxlength="160"
                                    help-text="Recommended: 150-160 characters"
                                />

                                <x-input-field 
                                    name="meta_keywords" 
                                    label="Meta Keywords" 
                                    placeholder="keyword1, keyword2, keyword3"
                                />

                                <x-input-field 
                                    name="search_tags" 
                                    label="Search Tags" 
                                    placeholder="tag1, tag2, tag3"
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
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="sub_category_id" 
                            label="Sub Category" 
                            placeholder="Select Sub Category"
                        >
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="child_category_id" 
                            label="Child Category" 
                            placeholder="Select Child Category"
                        >
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="brand_id" 
                            label="Brand" 
                            placeholder="Select Brand"
                        >
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="collection_id" 
                            label="Collection" 
                            placeholder="Select Collection"
                        >
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="branch_id" 
                            label="Branch" 
                            placeholder="Select Branch"
                        >
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
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
                            <option value="draft" selected>Draft</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </x-input-field>

                        <x-input-field 
                            type="select" 
                            name="is_active" 
                            label="Active Status" 
                            required
                        >
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </x-input-field>

                        <x-checkbox 
                            name="is_featured" 
                            value="1" 
                            label="Featured Product"
                        />

                        <x-checkbox 
                            name="is_returnable" 
                            value="1" 
                            label="Returnable"
                            checked
                        />

                        <x-input-field 
                            type="number" 
                            name="return_days" 
                            label="Return Days" 
                            placeholder="7"
                            min="0"
                            value="7"
                            help-text="Number of days for product return"
                        />
                    </div>
                </div>

                <div class="card mt-3" id="submitButtonCard" style="display: none;">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100" id="productCreateButton">
                            <i class="bx bx-loader bx-spin me-2" style="display: none" id="productCreateBtnSpinner"></i>
                            Create Product
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
            var tabs = ['#basic-info', '#pricing', '#tax-units', '#media', '#shipping', '#variations', '#other-details', '#seo'];
            var currentTab = $('.tab-pane.active').attr('id');
            var currentIndex = tabs.indexOf('#' + currentTab);
            
            // Check if current tab is valid
            var isCurrentValid = validateCurrentTab($('.tab-pane.active'));
            
            // If current tab is valid and not already unlocked, unlock the next tab
            if (isCurrentValid && currentIndex < tabs.length - 1) {
                var nextTab = tabs[currentIndex + 1];
                
                if (unlockedTabs.indexOf(nextTab) === -1) {
                    unlockedTabs.push(nextTab);
                }
            }
            
            // Update each tab link based on unlocked status
            $('.nav-tabs .nav-link').each(function() {
                var tabHref = $(this).attr('href');
                
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
        $('#productCreateForm').on('input change', 'input, select, textarea', function() {
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
            var tabs = ['#basic-info', '#pricing', '#tax-units', '#media', '#shipping', '#variations', '#other-details', '#seo'];
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

        // SKU Auto-suggestion
        function generateSKU() {
            let name = $('#product_name').val().toLowerCase().replace(/[^a-z0-9]/g, '-');
            let color = $('#color_id option:selected').text().toLowerCase().trim();
            let size = $('#size_id option:selected').text().toLowerCase().trim();
            
            let parts = [];
            if (name) parts.push(name);
            if (size && size !== 'no size' && size !== 'select size') parts.push(size);
            if (color && color !== 'no color' && color !== 'select color') parts.push(color);
            
            let sku = parts.join('-');
            // Only update if SKU is empty or was previously auto-generated
            if (!$('#sku_input').val() || $('#sku_input').data('is-auto')) {
                $('#sku_input').val(sku).data('is-auto', true);
            }
        }

        $('#product_name, #color_id, #size_id').on('input change', function() {
            generateSKU();
        });

        $('#sku_input').on('input', function() {
            $(this).data('is-auto', false);
        });

        // HSN/SAC & Unit Filtering
        $('#hsn_sac_type').change(function() {
            let type = $(this).val();
            $('#hsn_sac_id option').each(function() {
                if ($(this).val() === "" || $(this).data('type') === type) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            $('#hsn_sac_id').val('').trigger('change');
        });

        // Update GST Rate when HSN/SAC is selected
        $('#hsn_sac_id').on('change', function() {
            let selectedOption = $(this).find('option:selected');
            let gst = selectedOption.data('gst') || 0;
            $('#gst_rate_input').val(gst);
        });

        // Sync Unit type with HSN/SAC type
        $('#hsn_sac_type').on('change', function() {
            let type = $(this).val();
            $('#unit_id option').each(function() {
                if ($(this).val() === "" || $(this).data('type') === type) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            $('#unit_id').val('').trigger('change');
        });

        // Variation Toggle
        $('#has_variation_1').change(function() {
            if ($(this).is(':checked')) {
                $('.variation-fields').slideDown();
            } else {
                $('.variation-fields').slideUp();
                $('#color_id, #size_id').val('').trigger('change');
            }
        });

        // Pricing Validation (MRP >= Sell Price)
        $('#original_price, #sell_price').on('input', function() {
            let originalPrice = parseFloat($('#original_price').val()) || 0;
            let sellPrice = parseFloat($('#sell_price').val()) || 0;
            
            if (sellPrice > originalPrice && originalPrice > 0) {
                $('#sell_price').addClass('is-invalid');
                if (!$('#sell_price_error').length) {
                    $('#sell_price').after('<div id="sell_price_error" class="text-danger small">Sell Price cannot be greater than Original Price (MRP)</div>');
                }
            } else {
                $('#sell_price').removeClass('is-invalid');
                $('#sell_price_error').remove();
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
                        sendError('Child category route not available');
                    }
                });
            }
        });

        // Stock Type Toggle
        $('input[name="stock_type"]').change(function() {
            if ($(this).val() === 'unlimited') {
                $('#total_stock').val(999999).prop('readonly', true);
            } else {
                $('#total_stock').val(1).prop('readonly', false);
            }
        });

        // Form Submission
        $("#productCreateForm").validate({
            rules: {
                product_name: { required: true },
                sku: { required: true },
                hsn_sac_id: { required: true },
                unit_id: { required: true },
                category_id: { required: true },
                purchase_price: { required: true, number: true, min: 0 },
                original_price: { required: true, number: true, min: 0 },
                sell_price: { 
                    required: true, 
                    number: true, 
                    min: 0,
                    max: function() {
                        return parseFloat($('#original_price').val()) || Infinity;
                    }
                },
                total_stock: { required: true, number: true, min: 1 },
                weight: { required: true, number: true, min: 0.01 },
                length: { required: true, number: true, min: 0.01 },
                width: { required: true, number: true, min: 0.01 },
                height: { required: true, number: true, min: 0.01 },
                discount_type: { required: true },
                commission_type: { required: true },
                stock_type: { required: true },
                shipping_class: { required: true },
                product_status: { required: true },
                is_active: { required: true },
            },
            messages: {
                sell_price: {
                    max: "Sell price cannot exceed original price (MRP)"
                },
                total_stock: { min: "Quantity must be at least 1" },
                weight: { min: "Weight must be greater than 0" },
                length: { min: "Length must be greater than 0" },
                width: { min: "Width must be greater than 0" },
                height: { min: "Height must be greater than 0" }
            },
            errorPlacement: function (error, element) {
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.products.store') }}",
                    method: "POST",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#productCreateButton').attr('disabled', true);
                        $("#productCreateBtnSpinner").show();
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
                        $('#productCreateButton').attr('disabled', false);
                        $("#productCreateBtnSpinner").hide();
                    },
                });
            }
        });
        
        // ========================================
        // FilePond Initialization
        // ========================================
        
        // Register FilePond plugins
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateType
        );
        
        // Initialize FilePond for Thumbnail Image (Single Upload)
        const thumbnailPond = FilePond.create(document.querySelector('.filepond-thumbnail'), {
            acceptedFileTypes: ['image/jpeg', 'image/png', 'image/webp'],
            maxFileSize: '5MB',
            labelIdle: 'Drag & Drop your thumbnail image or <span class="filepond--label-action">Browse</span>',
            required: true,
            labelFileTypeNotAllowed: 'Invalid file type',
            fileValidateTypeLabelExpectedTypes: 'Expects JPG, PNG or WEBP',
            labelMaxFileSizeExceeded: 'File is too large',
            labelMaxFileSize: 'Maximum file size is {filesize}',
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeMode: 'contain',
            imageResizeUpscale: false,
            stylePanelLayout: 'compact',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
        });
        
        // Handle thumbnail file errors
        thumbnailPond.on('addfile', (error, file) => {
            if (error) {
                sendError(error.main);
                setTimeout(() => {
                    thumbnailPond.removeFile(file.id);
                }, 2000);
            }
        });
        
        // Initialize FilePond for Gallery Images (Multiple Upload)
        const galleryPond = FilePond.create(document.querySelector('.filepond-gallery'), {
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
        galleryPond.on('addfile', (error, file) => {
            if (error) {
                sendError(error.main);
                setTimeout(() => {
                    galleryPond.removeFile(file.id);
                }, 2000);
            }
        });
        
        // Warning when max files reached
        galleryPond.on('warning', (error, file) => {
            if (error.body === 'Max files') {
                sendWarning('Maximum 10 images allowed in gallery');
            }
        });
        
        // Initialize FilePond for Product Videos
        FilePond.create(document.querySelector('.filepond-videos'), {
            allowMultiple: true,
            maxFiles: 5,
            acceptedFileTypes: ['video/mp4', 'video/webm'],
            maxFileSize: '20MB',
            labelIdle: 'Drag & Drop your videos or <span class="filepond--label-action">Browse</span>',
            labelFileTypeNotAllowed: 'Invalid file type',
            fileValidateTypeLabelExpectedTypes: 'Expects MP4 or WEBM',
            labelMaxFileSizeExceeded: 'File is too large',
            labelMaxFileSize: 'Maximum file size is {filesize}',
        });

        // ========================================
        // Select2 Initialization
        // ========================================
        
        // Initialize Select2 for all select fields
        $('.select2').each(function() {
            $(this).select2({
                placeholder: $(this).attr('placeholder'),
                allowClear: true,
                width: '100%'
            });
        });

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
            minimumResultsForSearch: Infinity
        });
        
        $('#is_active').select2({
            placeholder: 'Select Active Status',
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: Infinity 
        });
        
        $('#shipping_class').select2({
            placeholder: 'Select Shipping Class',
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: Infinity
        });

        // Initialize filtering
        $('#hsn_sac_type').trigger('change');
    });
</script>
@endsection
