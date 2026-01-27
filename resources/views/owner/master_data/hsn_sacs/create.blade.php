@extends('owner.master')
@section('title','Create HSN/SAC')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create HSN/SAC</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="hsnSacForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6"><x-input-field name="code" label="Code" placeholder="Enter HSN/SAC code" required /></div>
                            <div class="col-md-6"><x-input-field type="select" name="type" label="Type" required>
                                <option value="">Select Type</option>
                                <option value="hsn">HSN (Goods)</option>
                                <option value="sac">SAC (Services)</option>
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="number" name="gst" label="GST (%)" placeholder="Enter GST percentage" min="0" max="100" step="0.01" required /></div>
                            <div class="col-md-6"><x-input-field type="select" name="status" label="Status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </x-input-field></div>
                            <div class="col-md-12"><x-input-field type="textarea" name="description" label="Description" placeholder="Enter description" rows="3" required /></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="btnSpinner"></i>Create HSN/SAC
                        </button>
                        <a href="{{ route('owner.master.hsn-sacs.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#hsnSacForm").validate({
            rules: {
                code: { required: true },
                type: { required: true },
                gst: { required: true, number: true, min: 0, max: 100 },
                description: { required: true },
                status: { required: true }
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.master.hsn-sacs.store') }}",
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
                                window.location.href = "{{ route('owner.master.hsn-sacs.index') }}";
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
