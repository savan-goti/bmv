@extends('owner.master')
@section('title', isset($hsnSac) ? 'Edit HSN/SAC' : 'Create HSN/SAC')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($hsnSac) ? 'Edit HSN/SAC' : 'Create HSN/SAC' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.master.hsn-sacs.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="hsn-sac-form">
                        @csrf
                        @if(isset($hsnSac))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="code" 
                                    label="Code" 
                                    placeholder="Enter HSN/SAC code"
                                    value="{{ old('code', $hsnSac->code ?? '') }}"
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
                                    <option value="hsn" {{ old('type', $hsnSac->type ?? '') == 'hsn' ? 'selected' : '' }}>HSN (Goods)</option>
                                    <option value="sac" {{ old('type', $hsnSac->type ?? '') == 'sac' ? 'selected' : '' }}>SAC (Services)</option>
                                    <option value="both" {{ old('type', $hsnSac->type ?? '') == 'both' ? 'selected' : '' }}>Both</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="number" 
                                    name="gst" 
                                    label="GST (%)" 
                                    placeholder="Enter GST percentage"
                                    value="{{ old('gst', $hsnSac->gst ?? '') }}"
                                    min="0"
                                    max="100"
                                    step="0.01"
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
                                    <option value="active" {{ old('status', isset($hsnSac) ? $hsnSac->status->value : 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', isset($hsnSac) ? $hsnSac->status->value : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea" 
                                    name="description" 
                                    label="Description" 
                                    placeholder="Enter description"
                                    value="{{ old('description', $hsnSac->description ?? '') }}"
                                    rows="3"
                                    required 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($hsnSac) ? 'Update HSN/SAC' : 'Create HSN/SAC' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.master.hsn-sacs.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#hsn-sac-form").validate({
            rules: {
                code: { required: true },
                type: { required: true },
                gst: { required: true, number: true, min: 0, max: 100 },
                description: { required: true },
                status: { required: true }
            },
            messages: {
                code: { required: "The code field is required" },
                type: { required: "The type field is required" },
                gst: { 
                    required: "The GST field is required",
                    number: "Please enter a valid number",
                    min: "GST must be at least 0",
                    max: "GST cannot exceed 100"
                },
                description: { required: "The description field is required" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($hsnSac))
                    var url = "{{ route('owner.master.hsn-sacs.update', $hsnSac->id) }}";
                @else
                    var url = "{{ route('owner.master.hsn-sacs.store') }}";
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
                                window.location.href = "{{ route('owner.master.hsn-sacs.index') }}";
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
