@extends('owner.master')
@section('title','Create Support Team Member')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Support Team Member</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <form id="supportTeamCreateForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6"><x-input-field type="file" name="profile_image" label="Profile Image" accept="image/*" /></div>
                            <div class="col-md-6"><x-input-field name="name" label="Name" placeholder="Enter name" required /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="email" name="email" label="Email" placeholder="Enter email" required /></div>
                            <div class="col-md-6"><x-input-field name="phone" label="Phone" placeholder="Enter phone" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="select" name="role" label="Role" placeholder="Select Role" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role['value'] }}">{{ $role['label'] }}</option>
                                @endforeach
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" name="status" label="Status" required>
                                <option value="active">Active</option>
                                <option value="disabled">Disabled</option>
                            </x-input-field></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="departments" class="form-label">Departments</label>
                                    <select class="form-select select2-multiple" id="departments" name="departments[]" multiple>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select one or more departments</small>
                                    <label id="departments-error" class="text-danger error" for="departments" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_queues" class="form-label">Default Queues</label>
                                    <select class="form-select select2-multiple" id="default_queues" name="default_queues[]" multiple>
                                        @foreach($queues as $queue)
                                            <option value="{{ $queue->id }}">{{ $queue->name }} @if($queue->department) ({{ $queue->department->name }}) @endif</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select one or more queues</small>
                                    <label id="default_queues-error" class="text-danger error" for="default_queues" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="select" name="notification_method" label="Notification Method" required>
                                <option value="email">Email</option>
                                <option value="in_app">In-App</option>
                                <option value="both" selected>Both</option>
                            </x-input-field></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="password" name="password" label="Password" placeholder="Enter password" required /></div>
                            <div class="col-md-6"><x-input-field type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm password" required /></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="supportTeamCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="supportTeamCreateBtnSpinner"></i>Create Support Team Member
                        </button>
                        <a href="{{ route('owner.support-team.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#supportTeamCreateForm").validate({
            rules: {
                name: { required: true },
                email: { required: true, email: true },
                role: { required: true },
                status: { required: true },
                notification_method: { required: true },
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: "#password" }
            },
            messages: {
                name: { required: "The name field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                role: { required: "The role field is required" },
                status: { required: "The status field is required" },
                notification_method: { required: "The notification method field is required" },
                password: { required: "The password field is required", minlength: "Password must be at least 8 characters long" },
                password_confirmation: { required: "The confirm password field is required", equalTo: "Passwords do not match" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.support-team.store') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#supportTeamCreateButton').attr('disabled', true);
                        $("#supportTeamCreateBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                             sendSuccess(result.message);
                             setTimeout(function() {
                                window.location.href = "{{ route('owner.support-team.index') }}";
                             }, 1000);
                        }else{
                             sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             if (data.error.hasOwnProperty('profile_image')) $("#profile_image-error").html(data.error.profile_image).show();
                             if (data.error.hasOwnProperty('name')) $("#name-error").html(data.error.name).show();
                             if (data.error.hasOwnProperty('email')) $("#email-error").html(data.error.email).show();
                             if (data.error.hasOwnProperty('phone')) $("#phone-error").html(data.error.phone).show();
                             if (data.error.hasOwnProperty('role')) $("#role-error").html(data.error.role).show();
                             if (data.error.hasOwnProperty('departments')) $("#departments-error").html(data.error.departments).show();
                             if (data.error.hasOwnProperty('default_queues')) $("#default_queues-error").html(data.error.default_queues).show();
                             if (data.error.hasOwnProperty('status')) $("#status-error").html(data.error.status).show();
                             if (data.error.hasOwnProperty('notification_method')) $("#notification_method-error").html(data.error.notification_method).show();
                             if (data.error.hasOwnProperty('password')) $("#password-error").html(data.error.password).show();
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#supportTeamCreateButton').attr('disabled', false);
                        $("#supportTeamCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
