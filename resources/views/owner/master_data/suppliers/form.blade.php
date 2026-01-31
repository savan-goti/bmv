@extends('owner.master')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Create Supplier')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($supplier) ? 'Edit Supplier' : 'Create Supplier' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.master.suppliers.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="supplier-form">
                        @csrf
                        @if(isset($supplier))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter supplier name"
                                    value="{{ old('name', $supplier->name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="email" 
                                    name="email" 
                                    label="Email" 
                                    placeholder="Enter email address"
                                    value="{{ old('email', $supplier->email ?? '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="phone" 
                                    label="Phone" 
                                    placeholder="Enter phone number"
                                    value="{{ old('phone', $supplier->phone ?? '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status', isset($supplier) ? $supplier->status->value : 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', isset($supplier) ? $supplier->status->value : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea" 
                                    name="address" 
                                    label="Address" 
                                    placeholder="Enter supplier address"
                                    value="{{ old('address', $supplier->address ?? '') }}"
                                    rows="3"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($supplier) ? 'Update Supplier' : 'Create Supplier' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.master.suppliers.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#supplier-form").validate({
            rules: {
                name: { required: true },
                email: { email: true },
                status: { required: true }
            },
            messages: {
                name: { required: "The name field is required" },
                email: { email: "Please enter a valid email address" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($supplier))
                    var url = "{{ route('owner.master.suppliers.update', $supplier->id) }}";
                @else
                    var url = "{{ route('owner.master.suppliers.store') }}";
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
                                window.location.href = "{{ route('owner.master.suppliers.index') }}";
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
