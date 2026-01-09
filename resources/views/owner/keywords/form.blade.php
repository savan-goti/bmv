@extends('owner.master')
@section('title', isset($keyword) ? 'Edit Keyword' : 'Create Keyword')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($keyword) ? 'Edit Keyword' : 'Create Keyword' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.master.keywords.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="keyword-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter keyword name"
                                    value="{{ old('name', $keyword->name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="type" 
                                    label="Type" 
                                    required
                                >
                                    <option value="">Select Type</option>
                                    <option value="product" {{ old('type', $keyword->type ?? '') == 'product' ? 'selected' : '' }}>Product</option>
                                    <option value="service" {{ old('type', $keyword->type ?? '') == 'service' ? 'selected' : '' }}>Service</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status', isset($keyword) ? $keyword->status->value : 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', isset($keyword) ? $keyword->status->value : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea"
                                    name="description" 
                                    label="Description" 
                                    placeholder="Enter keyword description"
                                    value="{{ old('description', $keyword->description ?? '') }}"
                                    rows="4"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($keyword) ? 'Update Keyword' : 'Create Keyword' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.master.keywords.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#keyword-form").validate({
            rules: {
                name: { required: true },
                type: { required: true },
                status: { required: true }
            },
            messages: {
                name: { required: "The name field is required" },
                type: { required: "The type field is required" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($keyword))
                    var url = "{{ route('owner.master.keywords.save', $keyword->id) }}";
                @else
                    var url = "{{ route('owner.master.keywords.save') }}";
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
                                window.location.href = "{{ route('owner.master.keywords.index') }}";
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
