@extends('owner.master')
@section('title','Edit Branch')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Branch</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <form id="branchEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6"><x-input-field name="name" label="Branch Name" placeholder="Enter branch name" value="{{ $branch->name }}" required /></div>
                            <div class="col-md-6"><x-input-field name="code" label="Branch Code" placeholder="e.g., BR001" value="{{ $branch->code }}" required /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="email" name="email" label="Email" placeholder="Enter email" value="{{ $branch->email }}" /></div>
                            <div class="col-md-6"><x-input-field name="phone" label="Phone" placeholder="Enter phone" value="{{ $branch->phone }}" /></div>
                        </div>

                        <x-input-field type="textarea" name="address" label="Address" placeholder="Enter address" value="{{ $branch->address }}" rows="2" />

                        <div class="row">
                            <div class="col-md-4"><x-input-field name="city" label="City" placeholder="Enter city" value="{{ $branch->city }}" /></div>
                            <div class="col-md-4"><x-input-field name="state" label="State" placeholder="Enter state" value="{{ $branch->state }}" /></div>
                            <div class="col-md-4"><x-input-field name="country" label="Country" placeholder="Enter country" value="{{ $branch->country }}" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field name="postal_code" label="Postal Code" placeholder="Enter postal code" value="{{ $branch->postal_code }}" /></div>
                            <div class="col-md-6"><x-input-field type="date" name="opening_date" label="Opening Date" value="{{ $branch->opening_date }}" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field name="manager_name" label="Manager Name" placeholder="Enter manager name" value="{{ $branch->manager_name }}" /></div>
                            <div class="col-md-6"><x-input-field name="manager_phone" label="Manager Phone" placeholder="Enter manager phone" value="{{ $branch->manager_phone }}" /></div>
                        </div>

                        <x-input-field type="select" name="status" label="Status" required>
                            <option value="active" {{ $branch->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $branch->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

                        <button type="submit" class="btn btn-primary" id="branchEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="branchEditBtnSpinner"></i>Update Branch
                        </button>
                        <a href="{{ route('owner.branches.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#branchEditForm").validate({
            rules: {
                name: { required: true },
                code: { required: true },
                status: { required: true },
                email: { email: true }
            },
            messages: {
                name: { required: "The branch name field is required" },
                code: { required: "The branch code field is required" },
                status: { required: "The status field is required" },
                email: { email: "Please enter a valid email address" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.branches.update', $branch->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#branchEditButton').attr('disabled', true);
                        $("#branchEditBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.branches.index') }}";
                            }, 1000);
                        }else{
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             if (data.error.hasOwnProperty('name')) $("#name-error").html(data.error.name).show();
                             if (data.error.hasOwnProperty('code')) $("#code-error").html(data.error.code).show();
                             if (data.error.hasOwnProperty('email')) $("#email-error").html(data.error.email).show();
                             if (data.error.hasOwnProperty('phone')) $("#phone-error").html(data.error.phone).show();
                             if (data.error.hasOwnProperty('address')) $("#address-error").html(data.error.address).show();
                             if (data.error.hasOwnProperty('city')) $("#city-error").html(data.error.city).show();
                             if (data.error.hasOwnProperty('state')) $("#state-error").html(data.error.state).show();
                             if (data.error.hasOwnProperty('country')) $("#country-error").html(data.error.country).show();
                             if (data.error.hasOwnProperty('postal_code')) $("#postal_code-error").html(data.error.postal_code).show();
                             if (data.error.hasOwnProperty('manager_name')) $("#manager_name-error").html(data.error.manager_name).show();
                             if (data.error.hasOwnProperty('manager_phone')) $("#manager_phone-error").html(data.error.manager_phone).show();
                             if (data.error.hasOwnProperty('opening_date')) $("#opening_date-error").html(data.error.opening_date).show();
                             if (data.error.hasOwnProperty('status')) $("#status-error").html(data.error.status).show();
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#branchEditButton').attr('disabled', false);
                        $("#branchEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
