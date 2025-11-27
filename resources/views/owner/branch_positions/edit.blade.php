@extends('owner.master')
@section('title','Edit Branch Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Branch Position</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <form id="branchPositionEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                                    <select class="form-select" id="branch_id" name="branch_id" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $b)
                                            <option value="{{ $b->id }}" {{ $branchPosition->branch_id == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->code }})</option>
                                        @endforeach
                                    </select>
                                    <label id="branch_id-error" class="text-danger error" for="branch_id" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="person_type" class="form-label">Person Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="person_type" name="person_type" required>
                                        <option value="">Select Type</option>
                                        <option value="Admin" {{ class_basename($branchPosition->positionable_type) == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="Staff" {{ class_basename($branchPosition->positionable_type) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    </select>
                                    <label id="person_type-error" class="text-danger error" for="person_type" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3" id="admin_select_wrapper" style="display: {{ class_basename($branchPosition->positionable_type) == 'Admin' ? 'block' : 'none' }};">
                                    <label for="admin_id" class="form-label">Select Admin <span class="text-danger">*</span></label>
                                    <select class="form-select" id="admin_id" name="person_id">
                                        <option value="">Select Admin</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ $branchPosition->positionable_id == $admin->id && class_basename($branchPosition->positionable_type) == 'Admin' ? 'selected' : '' }}>{{ $admin->name }} - {{ $admin->email }}</option>
                                        @endforeach
                                    </select>
                                    <label id="person_id-error" class="text-danger error" for="person_id" style="display: none"></label>
                                </div>
                                <div class="mb-3" id="staff_select_wrapper" style="display: {{ class_basename($branchPosition->positionable_type) == 'Staff' ? 'block' : 'none' }};">
                                    <label for="staff_id" class="form-label">Select Staff <span class="text-danger">*</span></label>
                                    <select class="form-select" id="staff_id" name="person_id">
                                        <option value="">Select Staff</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}" {{ $branchPosition->positionable_id == $staff->id && class_basename($branchPosition->positionable_type) == 'Staff' ? 'selected' : '' }}>{{ $staff->name }} - {{ $staff->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_position_id" class="form-label">Job Position <span class="text-danger">*</span></label>
                                    <select class="form-select" id="job_position_id" name="job_position_id" required>
                                        <option value="">Select Job Position</option>
                                        @foreach($jobPositions as $jp)
                                            <option value="{{ $jp->id }}" {{ $branchPosition->job_position_id == $jp->id ? 'selected' : '' }}>{{ $jp->name }} @if($jp->department) - {{ $jp->department }} @endif</option>
                                        @endforeach
                                    </select>
                                    <label id="job_position_id-error" class="text-danger error" for="job_position_id" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $branchPosition->start_date }}">
                                    <label id="start_date-error" class="text-danger error" for="start_date" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $branchPosition->end_date }}">
                                    <label id="end_date-error" class="text-danger error" for="end_date" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Salary</label>
                                    <input type="number" step="0.01" class="form-control" id="salary" name="salary" value="{{ $branchPosition->salary }}" placeholder="0.00">
                                    <label id="salary-error" class="text-danger error" for="salary" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $branchPosition->notes }}</textarea>
                            <label id="notes-error" class="text-danger error" for="notes" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="is_active" required>
                                <option value="1" {{ $branchPosition->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$branchPosition->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label id="is_active-error" class="text-danger error" for="is_active" style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="branchPositionEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="branchPositionEditBtnSpinner"></i>Update Position
                        </button>
                        <a href="{{ route('owner.branch-positions.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Show/hide person select based on type
        $('#person_type').change(function() {
            var type = $(this).val();
            $('#admin_select_wrapper, #staff_select_wrapper').hide();
            $('#admin_id, #staff_id').val('').prop('required', false);
            
            if (type === 'Admin') {
                $('#admin_select_wrapper').show();
                $('#admin_id').prop('required', true);
            } else if (type === 'Staff') {
                $('#staff_select_wrapper').show();
                $('#staff_id').prop('required', true);
            }
        });

        $("#branchPositionEditForm").validate({
            rules: {
                branch_id: { required: true },
                person_type: { required: true },
                person_id: { required: true },
                job_position_id: { required: true },
                is_active: { required: true }
            },
            messages: {
                branch_id: { required: "Please select a branch" },
                person_type: { required: "Please select person type" },
                person_id: { required: "Please select a person" },
                job_position_id: { required: "Please select a job position" },
                is_active: { required: "Please select status" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.branch-positions.update', $branchPosition->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#branchPositionEditButton').attr('disabled', true);
                        $("#branchPositionEditBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.branch-positions.index') }}";
                            }, 1000);
                        }else{
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             if (data.error.hasOwnProperty('branch_id')) $("#branch_id-error").html(data.error.branch_id).show();
                             if (data.error.hasOwnProperty('person_type')) $("#person_type-error").html(data.error.person_type).show();
                             if (data.error.hasOwnProperty('person_id')) $("#person_id-error").html(data.error.person_id).show();
                             if (data.error.hasOwnProperty('job_position_id')) $("#job_position_id-error").html(data.error.job_position_id).show();
                             if (data.error.hasOwnProperty('start_date')) $("#start_date-error").html(data.error.start_date).show();
                             if (data.error.hasOwnProperty('end_date')) $("#end_date-error").html(data.error.end_date).show();
                             if (data.error.hasOwnProperty('salary')) $("#salary-error").html(data.error.salary).show();
                             if (data.error.hasOwnProperty('is_active')) $("#is_active-error").html(data.error.is_active).show();
                             if (data.error.hasOwnProperty('notes')) $("#notes-error").html(data.error.notes).show();
                        } else if (data.hasOwnProperty('message')) {
                            // Show custom validation error (like "person already has active position")
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: data.message,
                                confirmButtonColor: '#3085d6'
                            });
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#branchPositionEditButton').attr('disabled', false);
                        $("#branchPositionEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
