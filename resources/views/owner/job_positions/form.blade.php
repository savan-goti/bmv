@extends('owner.master')
@section('title', isset($jobPosition) ? 'Edit Job Position' : 'Create Job Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($jobPosition) ? 'Edit Job Position' : 'Create Job Position' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.job-positions.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="job-position-form">
                        @csrf
                        @if(!isset($jobPosition))
                            <input type="hidden" name="owner_id" value="{{ Auth::user()->id }}">
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Position Name" 
                                    placeholder="Enter position name" 
                                    value="{{ old('name', $jobPosition->name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="department" 
                                    label="Department" 
                                    placeholder="e.g., Sales, IT, HR" 
                                    value="{{ old('department', $jobPosition->department ?? '') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field type="select" name="level" label="Level">
                                    <option value="">Select Level</option>
                                    <option value="entry" {{ old('level', $jobPosition->level ?? '') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                    <option value="mid" {{ old('level', $jobPosition->level ?? '') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                                    <option value="senior" {{ old('level', $jobPosition->level ?? '') == 'senior' ? 'selected' : '' }}>Senior Level</option>
                                    <option value="executive" {{ old('level', $jobPosition->level ?? '') == 'executive' ? 'selected' : '' }}>Executive</option>
                                    <option value="management" {{ old('level', $jobPosition->level ?? '') == 'management' ? 'selected' : '' }}>Management</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field type="select" name="status" label="Status" required>
                                    <option value="active" {{ old('status', $jobPosition->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $jobPosition->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>
                        </div>

                        <x-input-field 
                            type="textarea" 
                            name="description" 
                            label="Description" 
                            rows="4" 
                            placeholder="Job position description, responsibilities, etc." 
                            value="{{ old('description', $jobPosition->description ?? '') }}"
                        />

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($jobPosition) ? 'Update Job Position' : 'Create Job Position' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.job-positions.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#job-position-form").validate({
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
                
                @if(isset($jobPosition))
                    var url = "{{ route('owner.job-positions.save', $jobPosition->id) }}";
                @else
                    var url = "{{ route('owner.job-positions.save') }}";
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
                                window.location.href = "{{ route('owner.job-positions.index') }}";
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
