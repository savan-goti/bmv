@extends('owner.master')
@section('title','Create Product')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Product</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="productCreateForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Product Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                            <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control" id="short_description" name="short_description" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="long_description" class="form-label">Long Description</label>
                                            <textarea class="form-control" id="long_description" name="long_description" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Images</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Main Image</label>
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                            <label id="image-error" class="text-danger error" for="image" style="display: none"></label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="gallery_images" class="form-label">Gallery Images</label>
                                            <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Manufacturer Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="manufacturer_name" class="form-label">Manufacturer Name</label>
                                                    <input type="text" class="form-control" id="manufacturer_name" name="manufacturer_name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="manufacturer_brand" class="form-label">Brand</label>
                                                    <input type="text" class="form-control" id="manufacturer_brand" name="manufacturer_brand">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="manufacturer_part_number" class="form-label">Part Number</label>
                                                    <input type="text" class="form-control" id="manufacturer_part_number" name="manufacturer_part_number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">SEO Meta Data</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title">
                                        </div>
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Organization</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select" name="category_id" id="category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <label id="category_id-error" class="text-danger error" for="category_id" style="display: none"></label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sub_category_id" class="form-label">Sub Category</label>
                                            <select class="form-select" name="sub_category_id" id="sub_category_id">
                                                <option value="">Select Sub Category</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" name="status" required>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="published_at" class="form-label">Publish Date</label>
                                            <input type="datetime-local" class="form-control" id="published_at" name="published_at">
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Pricing & Inventory</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                                            <label id="price-error" class="text-danger error" for="price" style="display: none"></label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Discount</label>
                                            <input type="number" class="form-control" id="discount" name="discount" step="0.01" min="0" value="0">
                                        </div>
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                                            <label id="quantity-error" class="text-danger error" for="quantity" style="display: none"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="productCreateButton">
                                    <i class="bx bx-loader spinner me-2" style="display: none" id="productCreateBtnSpinner"></i>Create Product
                                </button>
                                <a href="{{ route('owner.products.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // SubCategory Loading
        $('#category_id').change(function() {
            var category_id = $(this).val();
            if (category_id) {
                $.ajax({
                    url: "{{ route('owner.sub-categories.get-by-category') }}",
                    type: "GET",
                    data: { category_id: category_id },
                    success: function(data) {
                        $('#sub_category_id').empty();
                        $('#sub_category_id').append('<option value="">Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $('#sub_category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="">Select Sub Category</option>');
            }
        });

        // Validation & Submission
        $("#productCreateForm").validate({
            rules: {
                name: { required: true },
                category_id: { required: true },
                price: { required: true, number: true, min: 0 },
                quantity: { required: true, number: true, min: 0 },
                status: { required: true }
            },
            messages: {
                name: { required: "The name field is required" },
                category_id: { required: "The category field is required" },
                price: { required: "The price field is required" },
                quantity: { required: "The quantity field is required" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.products.store') }}",
                    method: "post",
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
                        }else{
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             // Handle specific field errors
                             $.each(data.error, function(key, value){
                                 $("#"+key+"-error").html(value).show();
                             });
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#productCreateButton').attr('disabled', false);
                        $("#productCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
