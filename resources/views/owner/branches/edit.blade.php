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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $branch->name }}" required>
                                    <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Branch Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{ $branch->code }}" required>
                                    <label id="code-error" class="text-danger error" for="code" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $branch->email }}">
                                    <label id="email-error" class="text-danger error" for="email" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $branch->phone }}">
                                    <label id="phone-error" class="text-danger error" for="phone" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ $branch->address }}</textarea>
                            <label id="address-error" class="text-danger error" for="address" style="display: none"></label>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ $branch->city }}">
                                    <label id="city-error" class="text-danger error" for="city" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ $branch->state }}">
                                    <label id="state-error" class="text-danger error" for="state" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{ $branch->country }}">
                                    <label id="country-error" class="text-danger error" for="country" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ $branch->postal_code }}">
                                    <label id="postal_code-error" class="text-danger error" for="postal_code" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="opening_date" class="form-label">Opening Date</label>
                                    <input type="date" class="form-control" id="opening_date" name="opening_date" value="{{ $branch->opening_date }}">
                                    <label id="opening_date-error" class="text-danger error" for="opening_date" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manager_name" class="form-label">Manager Name</label>
                                    <input type="text" class="form-control" id="manager_name" name="manager_name" value="{{ $branch->manager_name }}">
                                    <label id="manager_name-error" class="text-danger error" for="manager_name" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manager_phone" class="form-label">Manager Phone</label>
                                    <input type="text" class="form-control" id="manager_phone" name="manager_phone" value="{{ $branch->manager_phone }}">
                                    <label id="manager_phone-error" class="text-danger error" for="manager_phone" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="active" {{ $branch->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $branch->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label id="status-error" class="text-danger error" for="status" style="display: none"></label>
                        </div>

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
