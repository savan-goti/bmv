@extends('owner.master')
@section('title','Assign Branch Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Assign Branch Position</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <form id="branchPositionCreateForm" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6"><x-input-field type="select" name="branch_id" label="Branch" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $branch && $branch->id == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->code }})</option>
                                @endforeach
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" id="person_type" name="person_type" label="Person Type" required>
                                <option value="">Select Type</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            </x-input-field></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div id="admin_select_wrapper" style="display: none;">
                                    <x-input-field type="select" id="admin_id" name="person_id" label="Select Admin" disabled>
                                        <option value="">Select Admin</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->name }} - {{ $admin->email }}</option>
                                        @endforeach
                                    </x-input-field>
                                </div>
                                <div id="staff_select_wrapper" style="display: none;">
                                    <x-input-field type="select" id="staff_id" name="person_id" label="Select Staff" disabled>
                                        <option value="">Select Staff</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }} - {{ $staff->email }}</option>
                                        @endforeach
                                    </x-input-field>
                                </div>
                            </div>
                            <div class="col-md-6"><x-input-field type="select" name="job_position_id" label="Job Position" required>
                                <option value="">Select Job Position</option>
                                @foreach($jobPositions as $jp)
                                    <option value="{{ $jp->id }}">{{ $jp->name }} @if($jp->department) - {{ $jp->department }} @endif</option>
                                @endforeach
                            </x-input-field></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><x-input-field type="date" name="start_date" label="Start Date" /></div>
                            <div class="col-md-4"><x-input-field type="date" name="end_date" label="End Date" /></div>
                            <div class="col-md-4"><x-input-field type="number" name="salary" label="Salary" placeholder="0.00" step="0.01" /></div>
                        </div>

                        <x-input-field type="textarea" name="notes" label="Notes" rows="3" />

                        <x-input-field type="select" name="is_active" label="Status" required>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </x-input-field>

                        <button type="submit" class="btn btn-primary" id="branchPositionCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="branchPositionCreateBtnSpinner"></i>Assign Position
                        </button>
                        <a href="{{ route('owner.branch-positions.index', $branch ? ['branch_id' => $branch->id] : []) }}" class="btn btn-secondary">Cancel</a>
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
            
            // Hide both wrappers and disable both selects
            $('#admin_select_wrapper, #staff_select_wrapper').hide();
            $('#admin_id, #staff_id').val('').prop('required', false).prop('disabled', true);
            
            // Clear any existing validation errors
            $('#admin_id-error, #staff_id-error').hide().html('');
            
            if (type === 'Admin') {
                $('#admin_select_wrapper').show();
                $('#admin_id').prop('required', true).prop('disabled', false);
            } else if (type === 'Staff') {
                $('#staff_select_wrapper').show();
                $('#staff_id').prop('required', true).prop('disabled', false);
            }
        });

        $("#branchPositionCreateForm").validate({
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
                    url: "{{ route('owner.branch-positions.store') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#branchPositionCreateButton').attr('disabled', true);
                        $("#branchPositionCreateBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.branch-positions.index', $branch ? ['branch_id' => $branch->id] : []) }}";
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
                        $('#branchPositionCreateButton').attr('disabled', false);
                        $("#branchPositionCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
