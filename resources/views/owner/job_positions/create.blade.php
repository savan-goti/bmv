@extends('owner.master')
@section('title','Create Job Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Job Position</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="jobPositionCreateForm" method="POST">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ Auth::user()->id }}">
                        
                        <x-input-field name="name" label="Position Name" placeholder="Enter position name" required />

                        <x-input-field name="department" label="Department" placeholder="e.g., Sales, IT, HR" />

                        <x-input-field type="select" name="level" label="Level">
                            <option value="">Select Level</option>
                            <option value="entry">Entry Level</option>
                            <option value="mid">Mid Level</option>
                            <option value="senior">Senior Level</option>
                            <option value="executive">Executive</option>
                            <option value="management">Management</option>
                        </x-input-field>

                        <x-input-field type="textarea" name="description" label="Description" rows="4" placeholder="Job position description, responsibilities, etc." />

                        <x-input-field type="select" name="status" label="Status" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </x-input-field>

                        <button type="submit" class="btn btn-primary" id="jobPositionCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="jobPositionCreateBtnSpinner"></i>Create Job Position
                        </button>
                        <a href="{{ route('owner.job-positions.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#jobPositionCreateForm").validate({
            rules: {
                name: { required: true },
                status: { required: true }
            },
            messages: {
                name: { required: "The position name field is required" },
                status: { required: "The status field is required" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.job-positions.store') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#jobPositionCreateButton').attr('disabled', true);
                        $("#jobPositionCreateBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.job-positions.index') }}";
                            }, 1000);
                        }else{
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             if (data.error.hasOwnProperty('name')) $("#name-error").html(data.error.name).show();
                             if (data.error.hasOwnProperty('department')) $("#department-error").html(data.error.department).show();
                             if (data.error.hasOwnProperty('level')) $("#level-error").html(data.error.level).show();
                             if (data.error.hasOwnProperty('description')) $("#description-error").html(data.error.description).show();
                             if (data.error.hasOwnProperty('status')) $("#status-error").html(data.error.status).show();
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#jobPositionCreateButton').attr('disabled', false);
                        $("#jobPositionCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
