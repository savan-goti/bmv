@extends('owner.master')
@section('title','Create Unit')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Unit</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="unitForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field name="name" label="Name" placeholder="Enter name" required />
                            </div>
                            <div class="col-md-6">
                                <x-input-field name="short_name" label="Short Name"
                                    placeholder="Enter short name (e.g. Kg, Pcs)" required />
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="select" name="category" label="Category" required>
                                    <option value="product">Product</option>
                                    <option value="service">Service</option>
                                    <option value="both">Both</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="select" name="status" label="Status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </x-input-field>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="btnSpinner"></i>Create Unit
                        </button>
                        <a href="{{ route('owner.master.units.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#unitForm").validate({
            rules: {
                name: { required: true },
                short_name: { required: true },
                category: { required: true },
                status: { required: true }
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.master.units.store') }}",
                    method: "post",
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $('#saveButton').attr('disabled', true);
                        $("#btnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                             sendSuccess(result.message);
                             setTimeout(function() {
                                window.location.href = "{{ route('owner.master.units.index') }}";
                             }, 1000);
                        }else{
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
                        $('#saveButton').attr('disabled', false);
                        $("#btnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection