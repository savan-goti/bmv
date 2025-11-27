@extends('owner.master')
@section('title','Edit Sub Category')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Sub Category</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="subCategoryEditForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <label id="category_id-error" class="text-danger error" for="category_id" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $subCategory->name }}" required>
                            <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @if($subCategory->image)
                                <div class="mt-2">
                                    <img src="{{ asset(\App\Http\Controllers\Owner\SubCategoryController::IMAGE_PATH . $subCategory->image) }}" alt="{{ $subCategory->name }}" width="100">
                                </div>
                            @endif
                            <label id="image-error" class="text-danger error" for="image" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="active" {{ $subCategory->status === \App\Enums\Status::Active ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $subCategory->status === \App\Enums\Status::Inactive ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label id="status-error" class="text-danger error" for="status" style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="subCategoryEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="subCategoryEditBtnSpinner"></i>Update Sub Category
                        </button>
                        <a href="{{ route('owner.sub-categories.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#subCategoryEditForm").validate({
            rules: {
                category_id: { required: true },
                name: { required: true },
                status: { required: true }
            },
            messages: {
                category_id: { required: "The category field is required" },
                name: { required: "The name field is required" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.sub-categories.update', $subCategory->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#subCategoryEditButton').attr('disabled', true);
                        $("#subCategoryEditBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.sub-categories.index') }}";
                            }, 1000);
                        }else{
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             if (data.error.hasOwnProperty('category_id')) $("#category_id-error").html(data.error.category_id).show();
                             if (data.error.hasOwnProperty('name')) $("#name-error").html(data.error.name).show();
                             if (data.error.hasOwnProperty('image')) $("#image-error").html(data.error.image).show();
                             if (data.error.hasOwnProperty('status')) $("#status-error").html(data.error.status).show();
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#subCategoryEditButton').attr('disabled', false);
                        $("#subCategoryEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
