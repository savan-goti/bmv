@extends('owner.master')
@section('title','Edit Supplier')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Supplier</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="supplierForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6"><x-input-field name="name" label="Name" placeholder="Enter name" value="{{ $supplier->name }}" required /></div>
                            <div class="col-md-6"><x-input-field type="email" name="email" label="Email" placeholder="Enter email" value="{{ $supplier->email }}" /></div>
                            <div class="col-md-6"><x-input-field name="phone" label="Phone" placeholder="Enter phone" value="{{ $supplier->phone }}" /></div>
                            <div class="col-md-6"><x-input-field type="select" name="status" label="Status" required>
                                <option value="active" {{ $supplier->status->value == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $supplier->status->value == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </x-input-field></div>
                            <div class="col-md-12"><x-input-field type="textarea" name="address" label="Address" placeholder="Enter address" rows="3" value="{{ $supplier->address }}" /></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="btnSpinner"></i>Update Supplier
                        </button>
                        <a href="{{ route('owner.master.suppliers.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#supplierForm").validate({
            rules: {
                name: { required: true },
                status: { required: true }
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.master.suppliers.update', $supplier->id) }}",
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
                                window.location.href = "{{ route('owner.master.suppliers.index') }}";
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
