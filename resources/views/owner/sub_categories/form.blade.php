@extends('owner.master')
@section('title', isset($subCategory) ? 'Edit Sub Category' : 'Create Sub Category')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($subCategory) ? 'Edit Sub Category' : 'Create Sub Category' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.sub-categories.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="sub-category-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($subCategory))
                            @method('PUT')
                        @endif

                        <x-input-field 
                            type="select" 
                            name="category_id" 
                            label="Category" 
                            placeholder="Select Category"
                            required
                        >
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', isset($subCategory) ? $subCategory->category_id : '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-input-field>

                        <x-input-field 
                            name="name" 
                            label="Name" 
                            placeholder="Enter sub category name"
                            value="{{ old('name', $subCategory->name ?? '') }}"
                            required 
                        />

                        <x-input-field 
                            type="file" 
                            name="image" 
                            label="Image" 
                            accept="image/*"
                        />
                        <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB)</small>
                        @if(isset($subCategory) && $subCategory->image)
                            <div class="mt-2">
                                <img src="{{ asset('uploads/sub_categories/' . $subCategory->image) }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 150px;"
                                     alt="Current Image">
                            </div>
                        @endif

                        <x-input-field 
                            type="select" 
                            name="status" 
                            label="Status" 
                            required
                        >
                            <option value="active" {{ old('status', isset($subCategory) ? $subCategory->status->value : 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', isset($subCategory) ? $subCategory->status->value : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            {{ isset($subCategory) ? 'Update Sub Category' : 'Create Sub Category' }}
                            <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
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
        $("#sub-category-form").validate({
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
                
                @if(isset($subCategory))
                    var url = "{{ route('owner.sub-categories.update', $subCategory->id) }}";
                @else
                    var url = "{{ route('owner.sub-categories.store') }}";
                @endif
                
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#submit-btn').attr('disabled', true);
                        $("#submit-btn-spinner").removeClass('d-none');
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.sub-categories.index') }}";
                            }, 1000);
                        } else {
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data && data.hasOwnProperty('error')) {
                            // Display validation errors
                            $.each(data.error, function(field, messages) {
                                let errorMsg = Array.isArray(messages) ? messages[0] : messages;
                                sendError(errorMsg);
                            });
                        } else if (data && data.hasOwnProperty('message')) {
                            sendError(data.message);
                        } else {
                            sendError('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        $('#submit-btn').attr('disabled', false);
                        $("#submit-btn-spinner").addClass('d-none');
                    },
                });
            }
        });
    });
</script>
@endsection
