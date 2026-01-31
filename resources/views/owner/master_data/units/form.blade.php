@extends('owner.master')
@section('title', isset($unit) ? 'Edit Unit' : 'Create Unit')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($unit) ? 'Edit Unit' : 'Create Unit' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.master.units.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="unit-form">
                        @csrf
                        @if(isset($unit))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter unit name (e.g. Kilogram, Piece)"
                                    value="{{ old('name', $unit->name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="short_name" 
                                    label="Short Name" 
                                    placeholder="Enter short name (e.g. Kg, Pcs)"
                                    value="{{ old('short_name', $unit->short_name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="category" 
                                    label="Category" 
                                    required
                                >
                                    <option value="">Select Category</option>
                                    <option value="product" {{ old('category', $unit->category ?? '') == 'product' ? 'selected' : '' }}>Product</option>
                                    <option value="service" {{ old('category', $unit->category ?? '') == 'service' ? 'selected' : '' }}>Service</option>
                                    <option value="both" {{ old('category', $unit->category ?? '') == 'both' ? 'selected' : '' }}>Both</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status', isset($unit) ? $unit->status->value : 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', isset($unit) ? $unit->status->value : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($unit) ? 'Update Unit' : 'Create Unit' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.master.units.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#unit-form").validate({
            rules: {
                name: { required: true },
                short_name: { required: true },
                category: { required: true },
                status: { required: true }
            },
            messages: {
                name: { required: "The name field is required" },
                short_name: { required: "The short name field is required" },
                category: { required: "The category field is required" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($unit))
                    var url = "{{ route('owner.master.units.update', $unit->id) }}";
                @else
                    var url = "{{ route('owner.master.units.store') }}";
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
                                window.location.href = "{{ route('owner.master.units.index') }}";
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
