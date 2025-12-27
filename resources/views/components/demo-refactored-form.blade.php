{{-- 
    DEMO: Refactored Product Create Form - Basic Info Tab
    
    This shows how your actual create.blade.php Basic Info tab could look
    when refactored to use the input-field component.
--}}

@extends('owner.master')
@section('title','Create Product')

@section('content')
<div class="container-fluid">
    <form id="productCreateForm" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        {{-- Product Name --}}
                        <x-input-field 
                            name="product_name" 
                            label="Product Name" 
                            placeholder="Enter product name"
                            maxlength="255"
                            required 
                        />

                        {{-- Product Slug --}}
                        <x-input-field 
                            name="slug" 
                            label="Product Slug" 
                            placeholder="product-slug"
                            help-text="URL-friendly version of the product name"
                        />

                        {{-- Short Description --}}
                        <x-input-field 
                            type="textarea" 
                            name="short_description" 
                            label="Short Description" 
                            placeholder="Brief product summary"
                            rows="3"
                            maxlength="500"
                            help-text="Maximum 500 characters"
                        />

                        {{-- Full Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Full Description</label>
                            <textarea class="form-control ckeditor" id="description" name="description" rows="6"></textarea>
                        </div>

                        {{-- Price Row --}}
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="number" 
                                    name="sell_price" 
                                    label="Sell Price" 
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    icon="bx bx-dollar"
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="number" 
                                    name="cost_price" 
                                    label="Cost Price" 
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    icon="bx bx-dollar"
                                />
                            </div>
                        </div>

                        {{-- Discount Row --}}
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="discount_type" 
                                    label="Discount Type" 
                                    required
                                >
                                    <option value="none" selected>No Discount</option>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="number" 
                                    name="discount_value" 
                                    label="Discount Value" 
                                    placeholder="0"
                                    step="0.01"
                                    min="0"
                                />
                            </div>
                        </div>

                        {{-- Stock Information --}}
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="number" 
                                    name="total_stock" 
                                    label="Total Stock" 
                                    value="0"
                                    min="0"
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="sku" 
                                    label="SKU" 
                                    placeholder="Enter SKU"
                                    help-text="Stock Keeping Unit"
                                />
                            </div>
                        </div>

                        {{-- Barcode --}}
                        <x-input-field 
                            name="barcode" 
                            label="Barcode" 
                            placeholder="Enter barcode number"
                        />

                        {{-- Variation Checkbox (keeping as-is since it's a checkbox) --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="has_variation" name="has_variation" value="1">
                                <label class="form-check-label" for="has_variation">This product has variations</label>
                            </div>
                        </div>

                        {{-- Tab Navigation --}}
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-tab">
                                <i class="bx bx-chevron-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary next-tab">
                                Next <i class="bx bx-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Organization</h5>
                    </div>
                    <div class="card-body">
                        {{-- Category --}}
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

                        {{-- Sub Category --}}
                        <x-input-field 
                            type="select" 
                            name="sub_category_id" 
                            label="Sub Category" 
                            placeholder="Select Sub Category"
                        >
                        </x-input-field>

                        {{-- Child Category --}}
                        <x-input-field 
                            type="select" 
                            name="child_category_id" 
                            label="Child Category" 
                            placeholder="Select Child Category"
                        >
                        </x-input-field>

                        {{-- Brand --}}
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

                        {{-- Collection --}}
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
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Status</h5>
                    </div>
                    <div class="card-body">
                        {{-- Product Status --}}
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

                        {{-- Active Status --}}
                        <x-input-field 
                            type="select" 
                            name="is_active" 
                            label="Active Status" 
                            required
                        >
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </x-input-field>

                        {{-- Featured Checkbox (keeping as-is) --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>
                        </div>

                        {{-- Returnable Checkbox (keeping as-is) --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_returnable" name="is_returnable" value="1" checked>
                                <label class="form-check-label" for="is_returnable">Returnable</label>
                            </div>
                        </div>

                        {{-- Return Days --}}
                        <x-input-field 
                            type="number" 
                            name="return_days" 
                            label="Return Days" 
                            value="7"
                            min="0"
                        />
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

{{-- 
    COMPARISON:
    
    BEFORE (Traditional):
    - 7-10 lines per input field
    - Repetitive code
    - Manual required indicators
    - Manual error handling setup
    
    AFTER (With Component):
    - 1-5 lines per input field
    - Clean, readable code
    - Automatic required indicators
    - Built-in error handling
    
    CODE REDUCTION:
    - Approximately 60-70% less code
    - Much easier to maintain
    - Consistent styling automatically
    - Faster development
--}}
