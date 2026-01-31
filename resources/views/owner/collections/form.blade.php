@extends('owner.master')
@section('title', isset($collection) ? 'Edit Collection' : 'Create Collection')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($collection) ? 'Edit Collection' : 'Create Collection' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.collections.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="collection-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($collection))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter collection name"
                                    value="{{ old('name', $collection->name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status', isset($collection) ? $collection->status->value : 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', isset($collection) ? $collection->status->value : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="date"
                                    name="start_date" 
                                    label="Start Date" 
                                    value="{{ old('start_date', isset($collection) && $collection->start_date ? $collection->start_date->format('Y-m-d') : '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="date"
                                    name="end_date" 
                                    label="End Date" 
                                    value="{{ old('end_date', isset($collection) && $collection->end_date ? $collection->end_date->format('Y-m-d') : '') }}"
                                />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="image" 
                                    label="Image" 
                                    accept="image/*"
                                />
                                <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                                <div id="image-preview" class="mt-2">
                                    @if(isset($collection) && $collection->getRawOriginal('image'))
                                        <img src="{{ asset('uploads/collections/' . $collection->getRawOriginal('image')) }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px;"
                                             alt="Current Image">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <x-checkbox 
                                    name="is_featured" 
                                    value="1" 
                                    label="Featured Collection"
                                    :checked="old('is_featured', isset($collection) ? $collection->is_featured : false)"
                                    container-class="mt-4"
                                />
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea"
                                    name="description" 
                                    label="Description" 
                                    placeholder="Enter collection description"
                                    value="{{ old('description', $collection->description ?? '') }}"
                                    rows="4"
                                />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Select Products</label>
                                <select name="products[]" id="products" class="form-select select2" multiple data-placeholder="Select Products">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                            {{ in_array($product->id, old('products', isset($selectedProducts) ? $selectedProducts : [])) ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($collection) ? 'Update Collection' : 'Create Collection' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.collections.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select Products',
            allowClear: true
        });

        // Image preview
        $('input[name="image"]').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;" alt="Image Preview">');
                }
                reader.readAsDataURL(file);
            } else {
                @if(isset($collection) && $collection->getRawOriginal('image'))
                    $('#image-preview').html('<img src="{{ asset('uploads/collections/' . $collection->getRawOriginal('image')) }}" class="img-thumbnail" style="max-width: 200px;" alt="Current Image">');
                @else
                    $('#image-preview').html('');
                @endif
            }
        });

        // Form validation and AJAX submission
        $("#collection-form").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                status: {
                    required: true
                },
                start_date: {
                    date: true
                },
                end_date: {
                    date: true
                },
                // image: {
                //     extension: "jpg|jpeg|png|gif"
                // }
            },
            messages: {
                name: {
                    required: "The name field is required",
                    maxlength: "The name may not be greater than 255 characters"
                },
                status: {
                    required: "The status field is required"
                },
                start_date: {
                    date: "Please enter a valid date"
                },
                end_date: {
                    date: "Please enter a valid date"
                },
                // image: {
                //     extension: "Please upload a valid image file (jpg, jpeg, png, gif)"
                // }
            },
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                element.after(error);
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($collection))
                    var url = "{{ route('owner.collections.update', $collection->id) }}";
                @else
                    var url = "{{ route('owner.collections.store') }}";
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
                                window.location.href = "{{ route('owner.collections.index') }}";
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

