@extends('owner.master')
@section('title','Create Category')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Category</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="categoryCreateForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="category_type" class="form-label">Category Type</label>
                            <select class="form-select" name="category_type" id="category_type" required>
                                <option value="">Select Category Type</option>
                                @foreach($categoryTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <label id="category_type-error" class="text-danger error" for="category_type" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <label id="image-error" class="text-danger error" for="image" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label id="status-error" class="text-danger error" for="status" style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="categoryCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="categoryCreateBtnSpinner"></i>Create Category
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
        $("#categoryCreateForm").validate({
            rules: {
                name: { required: true },
                category_type: { required: true },
                status: { required: true }
            },
            messages: {
                name: { required: "The name field is required" },
                category_type: { required: "The category type field is required" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.categories.store') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#categoryCreateButton').attr('disabled', true);
                        $("#categoryCreateBtnSpinner").show();
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
                             if (data.error.hasOwnProperty('category_type')) $("#category_type-error").html(data.error.category_type).show();
                             if (data.error.hasOwnProperty('image')) $("#image-error").html(data.error.image).show();
                             if (data.error.hasOwnProperty('status')) $("#status-error").html(data.error.status).show();
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#categoryCreateButton').attr('disabled', false);
                        $("#categoryCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
