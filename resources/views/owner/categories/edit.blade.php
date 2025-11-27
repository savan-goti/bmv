@extends('owner.master')
@section('title','Edit Category')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Category</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="categoryEditForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                            <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @if($category->image)
                                <div class="mt-2">
                                    <img src="{{ asset(\App\Http\Controllers\Owner\CategoryController::IMAGE_PATH . $category->image) }}" alt="{{ $category->name }}" width="100">
                                </div>
                            @endif
                            <label id="image-error" class="text-danger error" for="image" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="active" {{ $category->status === \App\Enums\Status::Active ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $category->status === \App\Enums\Status::Inactive ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label id="status-error" class="text-danger error" for="status" style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="categoryEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="categoryEditBtnSpinner"></i>Update Category
                        </button>
                        <a href="{{ route('owner.categories.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#categoryEditForm").validate({
            rules: {
                name: { required: true },
                status: { required: true }
            },
            messages: {
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
                    url: "{{ route('owner.categories.update', $category->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#categoryEditButton').attr('disabled', true);
                        $("#categoryEditBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.categories.index') }}";
                            }, 1000);
                        }else{
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
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
                        $('#categoryEditButton').attr('disabled', false);
                        $("#categoryEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
