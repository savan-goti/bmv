@extends('owner.master')
@section('title','Edit Job Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Job Position</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="jobPositionEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Position Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $jobPosition->name }}" required>
                            <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" value="{{ $jobPosition->department }}" placeholder="e.g., Sales, IT, HR">
                            <label id="department-error" class="text-danger error" for="department" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select" id="level" name="level">
                                <option value="">Select Level</option>
                                <option value="entry" {{ $jobPosition->level == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                <option value="mid" {{ $jobPosition->level == 'mid' ? 'selected' : '' }}>Mid Level</option>
                                <option value="senior" {{ $jobPosition->level == 'senior' ? 'selected' : '' }}>Senior Level</option>
                                <option value="executive" {{ $jobPosition->level == 'executive' ? 'selected' : '' }}>Executive</option>
                                <option value="management" {{ $jobPosition->level == 'management' ? 'selected' : '' }}>Management</option>
                            </select>
                            <label id="level-error" class="text-danger error" for="level" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Job position description, responsibilities, etc.">{{ $jobPosition->description }}</textarea>
                            <label id="description-error" class="text-danger error" for="description" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="active" {{ $jobPosition->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $jobPosition->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label id="status-error" class="text-danger error" for="status" style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="jobPositionEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="jobPositionEditBtnSpinner"></i>Update Job Position
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
        $("#jobPositionEditForm").validate({
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
                    url: "{{ route('owner.job-positions.update', $jobPosition->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#jobPositionEditButton').attr('disabled', true);
                        $("#jobPositionEditBtnSpinner").show();
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
                        $('#jobPositionEditButton').attr('disabled', false);
                        $("#jobPositionEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
