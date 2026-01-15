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
                            <div class="col-md-6"><x-input-field type="select" name="job_position_id" label="Job Position" required>
                                <option value="">Select Job Position</option>
                                @foreach($jobPositions as $jp)
                                    <option value="{{ $jp->id }}">{{ $jp->name }} @if($jp->department) - {{ $jp->department }} @endif</option>
                                @endforeach
                            </x-input-field></div>
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

        $("#branchPositionCreateForm").validate({
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
                        $('#branchPositionCreateButton').attr('disabled', false);
                        $("#branchPositionCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
