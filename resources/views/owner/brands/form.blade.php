@extends('owner.master')
@section('title', isset($brand) ? 'Edit Brand' : 'Create Brand')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($brand) ? 'Edit Brand' : 'Create Brand' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.brands.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="brand-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($brand))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter brand name"
                                    value="{{ old('name', $brand->name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="url"
                                    name="website" 
                                    label="Website" 
                                    placeholder="https://example.com"
                                    value="{{ old('website', $brand->website ?? '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status', isset($brand) ? $brand->status->value : 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', isset($brand) ? $brand->status->value : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="logo" 
                                    label="Logo" 
                                    accept="image/*"
                                />
                                <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF, SVG (Max: 2MB)</small>
                                <div id="logo-preview" class="mt-2">
                                    @if(isset($brand) && $brand->getRawOriginal('logo'))
                                        <img src="{{ asset('uploads/brands/' . $brand->getRawOriginal('logo')) }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px;"
                                             alt="Current Logo">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea"
                                    name="description" 
                                    label="Description" 
                                    placeholder="Enter brand description"
                                    value="{{ old('description', $brand->description ?? '') }}"
                                    rows="4"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($brand) ? 'Update Brand' : 'Create Brand' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.brands.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Logo preview
        $('input[name="logo"]').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;" alt="Logo Preview">');
                }
                reader.readAsDataURL(file);
            } else {
                @if(isset($brand) && $brand->getRawOriginal('logo'))
                    $('#logo-preview').html('<img src="{{ asset('uploads/brands/' . $brand->getRawOriginal('logo')) }}" class="img-thumbnail" style="max-width: 200px;" alt="Current Logo">');
                @else
                    $('#logo-preview').html('');
                @endif
            }
        });

        // Form validation and AJAX submission
        $("#brand-form").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                website: {
                    url: true,
                    maxlength: 255
                },
                status: {
                    required: true
                },
                // logo: {
                //     extension: "jpg|jpeg|png|gif|svg"
                // }
            },
            messages: {
                name: {
                    required: "The name field is required",
                    maxlength: "The name may not be greater than 255 characters"
                },
                website: {
                    url: "Please enter a valid URL",
                    maxlength: "The website may not be greater than 255 characters"
                },
                status: {
                    required: "The status field is required"
                },
                // logo: {
                //     extension: "Please upload a valid image file (jpg, jpeg, png, gif, svg)"
                // }
            },
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                element.after(error);
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($brand))
                    var url = "{{ route('owner.brands.update', $brand->id) }}";
                @else
                    var url = "{{ route('owner.brands.store') }}";
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
                                window.location.href = "{{ route('owner.brands.index') }}";
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

