@extends('owner.master')
@section('title','Edit HSN/SAC')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit HSN/SAC</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="hsnSacForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6"><x-input-field name="code" label="Code" placeholder="Enter HSN/SAC code" value="{{ $hsnSac->code }}" required /></div>
                            <div class="col-md-6"><x-input-field type="select" name="type" label="Type" required>
                                <option value="">Select Type</option>
                                <option value="hsn" {{ $hsnSac->type == 'hsn' ? 'selected' : '' }}>HSN (Goods)</option>
                                <option value="sac" {{ $hsnSac->type == 'sac' ? 'selected' : '' }}>SAC (Services)</option>
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="number" name="gst" label="GST (%)" placeholder="Enter GST percentage" min="0" max="100" step="0.01" value="{{ $hsnSac->gst }}" required /></div>
                            <div class="col-md-6"><x-input-field type="select" name="status" label="Status" required>
                                <option value="active" {{ $hsnSac->status->value == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $hsnSac->status->value == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </x-input-field></div>
                            <div class="col-md-12"><x-input-field type="textarea" name="description" label="Description" placeholder="Enter description" rows="3" value="{{ $hsnSac->description }}" required /></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="btnSpinner"></i>Update HSN/SAC
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
                    url: "{{ route('owner.master.hsn-sacs.update', $hsnSac->id) }}",
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
