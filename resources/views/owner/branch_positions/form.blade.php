@extends('owner.master')
@section('title', isset($branchPosition) ? 'Edit Branch Position' : 'Assign Branch Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($branchPosition) ? 'Edit Branch Position' : 'Assign Branch Position' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.branch-positions.index', $branch ? ['branch_id' => $branch->id] : []) }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="branch-position-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field type="select" name="branch_id" label="Branch" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}" {{ old('branch_id', $branchPosition->branch_id ?? ($branch->id ?? '')) == $b->id ? 'selected' : '' }}>
                                            {{ $b->name }} ({{ $b->code }})
                                        </option>
                                    @endforeach
                                </x-input-field>
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="select" name="job_position_id" label="Job Position" required>
                                    <option value="">Select Job Position</option>
                                    @foreach($jobPositions as $jp)
                                        <option value="{{ $jp->id }}" {{ old('job_position_id', $branchPosition->job_position_id ?? '') == $jp->id ? 'selected' : '' }}>
                                            {{ $jp->name }} @if($jp->department) - {{ $jp->department }} @endif
                                        </option>
                                    @endforeach
                                </x-input-field>
                            </div>
                        </div>

                        <x-input-field 
                            type="textarea" 
                            name="notes" 
                            label="Notes" 
                            value="{{ old('notes', $branchPosition->notes ?? '') }}"
                            rows="3" 
                        />

                        <x-input-field type="select" name="is_active" label="Status" required>
                            <option value="1" {{ old('is_active', $branchPosition->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $branchPosition->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($branchPosition) ? 'Update Position' : 'Assign Position' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.branch-positions.index', $branch ? ['branch_id' => $branch->id] : []) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
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
        $("#branch-position-form").validate({
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
                
                @if(isset($branchPosition))
                    var url = "{{ route('owner.branch-positions.save', $branchPosition->id) }}";
                @else
                    var url = "{{ route('owner.branch-positions.save') }}";
                @endif
                
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#submit-btn').attr('disabled', true);
                        $("#submit-btn-spinner").removeClass('d-none');
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.branch-positions.index', $branch ? ['branch_id' => $branch->id] : []) }}";
                            }, 1000);
                        } else {
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data && data.hasOwnProperty('error')) {
                            // Display validation errors
                            $.each(data.error, function(field, messages) {
                                let errorMsg = Array.isArray(messages) ? messages[0] : messages;
                                sendError(errorMsg);
                            });
                        } else if (data && data.hasOwnProperty('message')) {
                            sendError(data.message);
                        } else {
                            sendError('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        $('#submit-btn').attr('disabled', false);
                        $("#submit-btn-spinner").addClass('d-none');
                    },
                });
            }
        });
    });
</script>
@endsection
