{{-- 
    EXAMPLE: Using Input Field Component with jQuery Validation Plugin
    
    This demonstrates how the input-field component works seamlessly 
    with jQuery Validation plugin.
--}}

@extends('owner.master')
@section('title', 'jQuery Validation Example')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Form with jQuery Validation</h5>
                </div>
                <div class="card-body">
                    <form id="productForm" method="POST" action="{{ route('owner.products.store') }}">
                        @csrf

                        {{-- Product Name - Required --}}
                        <x-input-field 
                            name="product_name" 
                            label="Product Name" 
                            placeholder="Enter product name"
                            maxlength="255"
                            required 
                        />

                        {{-- Email - Required, Email Format --}}
                        <x-input-field 
                            type="email" 
                            name="email" 
                            label="Contact Email" 
                            placeholder="example@domain.com"
                            icon="bx bx-envelope"
                            required 
                        />

                        {{-- Price - Required, Number, Min 0 --}}
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="number" 
                                    name="sell_price" 
                                    label="Sell Price" 
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
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
                                />
                            </div>
                        </div>

                        {{-- URL - URL Format --}}
                        <x-input-field 
                            type="url" 
                            name="website" 
                            label="Website URL" 
                            placeholder="https://example.com"
                        />

                        {{-- Stock - Required, Number, Min 0 --}}
                        <x-input-field 
                            type="number" 
                            name="total_stock" 
                            label="Total Stock" 
                            value="0"
                            min="0"
                            required 
                        />

                        {{-- Description - Required, Min Length 10 --}}
                        <x-input-field 
                            type="textarea" 
                            name="description" 
                            label="Description" 
                            placeholder="Enter product description"
                            rows="4"
                            required
                        />

                        {{-- Category - Required --}}
                        <x-input-field 
                            type="select" 
                            name="category_id" 
                            label="Category" 
                            placeholder="Select Category"
                            required
                        >
                            <option value="1">Electronics</option>
                            <option value="2">Clothing</option>
                            <option value="3">Home & Garden</option>
                        </x-input-field>

                        {{-- Submit Button --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-2"></i>Submit Product
                            </button>
                            <button type="reset" class="btn btn-secondary ms-2">
                                <i class="bx bx-reset me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="fw-bold">How It Works:</h6>
                    <ul class="mb-0">
                        <li>Each input field has a hidden error label: <code>id="{name}-error"</code></li>
                        <li>jQuery Validation automatically shows/hides these labels</li>
                        <li>Error messages appear below the input fields</li>
                        <li>No additional HTML needed - it's built into the component!</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Initialize jQuery Validation
        $("#productForm").validate({
            // Validation Rules
            rules: {
                product_name: {
                    required: true,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true
                },
                sell_price: {
                    required: true,
                    number: true,
                    min: 0
                },
                cost_price: {
                    number: true,
                    min: 0
                },
                website: {
                    url: true
                },
                total_stock: {
                    required: true,
                    number: true,
                    min: 0,
                    digits: true
                },
                description: {
                    required: true,
                    minlength: 10,
                    maxlength: 1000
                },
                category_id: {
                    required: true
                }
            },

            // Custom Error Messages
            messages: {
                product_name: {
                    required: 'Product name is required',
                    maxlength: 'Product name cannot exceed 255 characters'
                },
                email: {
                    required: 'Email is required',
                    email: 'Please enter a valid email address'
                },
                sell_price: {
                    required: 'Sell price is required',
                    number: 'Sell price must be a valid number',
                    min: 'Sell price must be greater than or equal to 0'
                },
                cost_price: {
                    number: 'Cost price must be a valid number',
                    min: 'Cost price must be greater than or equal to 0'
                },
                website: {
                    url: 'Please enter a valid URL (e.g., https://example.com)'
                },
                total_stock: {
                    required: 'Total stock is required',
                    number: 'Total stock must be a number',
                    min: 'Total stock must be greater than or equal to 0',
                    digits: 'Total stock must be a whole number'
                },
                description: {
                    required: 'Description is required',
                    minlength: 'Description must be at least 10 characters',
                    maxlength: 'Description cannot exceed 1000 characters'
                },
                category_id: {
                    required: 'Please select a category'
                }
            },

            // Error Placement (optional - customize where errors appear)
            errorPlacement: function(error, element) {
                // The error label is already in the DOM thanks to the component
                // jQuery Validation will automatically use it
                error.insertAfter(element);
            },

            // Highlight invalid fields
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },

            // Remove highlight from valid fields
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },

            // Submit Handler
            submitHandler: function(form, e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = $(form).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="bx bx-loader bx-spin me-2"></i>Submitting...');

                // Simulate AJAX submission
                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.status) {
                            // Show success message (assuming you have a sendSuccess function)
                            if(typeof sendSuccess === 'function') {
                                sendSuccess(response.message || 'Product created successfully!');
                            } else {
                                alert('Success: ' + (response.message || 'Product created successfully!'));
                            }
                            
                            // Redirect or reset form
                            setTimeout(() => {
                                window.location.href = response.redirect || '/owner/products';
                            }, 1000);
                        } else {
                            // Show error message
                            if(typeof sendError === 'function') {
                                sendError(response.message || 'Something went wrong');
                            } else {
                                alert('Error: ' + (response.message || 'Something went wrong'));
                            }
                        }
                    },
                    error: function(xhr) {
                        let data = xhr.responseJSON;
                        if (data && data.errors) {
                            // Display Laravel validation errors
                            $.each(data.errors, function(key, value) {
                                if(typeof sendError === 'function') {
                                    sendError(value[0]);
                                } else {
                                    alert('Error: ' + value[0]);
                                }
                            });
                        } else if (data && data.message) {
                            if(typeof sendError === 'function') {
                                sendError(data.message);
                            } else {
                                alert('Error: ' + data.message);
                            }
                        } else {
                            if(typeof sendError === 'function') {
                                sendError('Something went wrong. Please try again.');
                            } else {
                                alert('Error: Something went wrong. Please try again.');
                            }
                        }
                    },
                    complete: function() {
                        // Restore button state
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            }
        });

        // Optional: Real-time validation on blur
        $('#productForm input, #productForm select, #productForm textarea').on('blur', function() {
            $(this).valid();
        });
    });
</script>
@endsection

{{-- 
    GENERATED HTML STRUCTURE:
    
    Each input field component generates:
    
    <div class="mb-3">
        <label for="product_name" class="form-label">
            Product Name <span class="text-danger">*</span>
        </label>
        <div class="position-relative">
            <input type="text" name="product_name" id="product_name" 
                   class="form-control" placeholder="Enter product name" required>
        </div>
        <small class="text-muted d-block mt-1">Help text here (if provided)</small>
        <div class="text-danger small mt-1">Laravel error (if any)</div>
        
        <!-- THIS IS THE KEY: jQuery Validation Error Label -->
        <label id="product_name-error" class="text-danger error" 
               for="product_name" style="display: none;"></label>
    </div>
    
    jQuery Validation will automatically:
    1. Find the error label by ID ({name}-error)
    2. Show/hide it based on validation
    3. Insert error messages into it
    4. Apply the "error" class for styling
--}}
