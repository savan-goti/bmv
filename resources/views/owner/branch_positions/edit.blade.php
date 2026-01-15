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
                            <div class="col-md-6"><x-input-field type="select" name="branch_id" label="Branch" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $branchPosition->branch_id == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->code }})</option>
                                @endforeach
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" name="job_position_id" label="Job Position" required>
                                <option value="">Select Job Position</option>
                                @foreach($jobPositions as $jp)
                                    <option value="{{ $jp->id }}" {{ $branchPosition->job_position_id == $jp->id ? 'selected' : '' }}>{{ $jp->name }} @if($jp->department) - {{ $jp->department }} @endif</option>
                                @endforeach
                            </x-input-field></div>
                        </div>



                        <x-input-field type="textarea" name="notes" label="Notes" rows="3" value="{{ $branchPosition->notes }}" />

                        <x-input-field type="select" name="is_active" label="Status" required>
                            <option value="1" {{ $branchPosition->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$branchPosition->is_active ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

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

        $("#branchPositionEditForm").validate({
            rules: {
                branch_id: { required: true },
                job_position_id: { required: true },
                is_active: { required: true }
            },
            messages: {
                branch_id: { required: "Please select a branch" },
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
                             if (data.error.hasOwnProperty('job_position_id')) $("#job_position_id-error").html(data.error.job_position_id).show();
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
