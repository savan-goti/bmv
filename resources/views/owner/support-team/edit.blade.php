@extends('owner.master')
@section('title','Edit Support Team Member')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Support Team Member</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <form id="supportTeamEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                @if($supportTeamMember->getRawOriginal('profile_image'))
                                    <div class="mb-2">
                                        <img src="{{ $supportTeamMember->profile_image }}" alt="Profile" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                @endif
                                <x-input-field type="file" name="profile_image" label="Profile Image" accept="image/*" />
                            </div>
                            <div class="col-md-6"><x-input-field name="name" label="Name" placeholder="Enter name" value="{{ $supportTeamMember->name }}" required /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="email" name="email" label="Email" placeholder="Enter email" value="{{ $supportTeamMember->email }}" required /></div>
                            <div class="col-md-6"><x-input-field name="phone" label="Phone" placeholder="Enter phone" value="{{ $supportTeamMember->phone }}" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="select" name="role" label="Role" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role['value'] }}" {{ (is_object($supportTeamMember->role) ? $supportTeamMember->role->value : $supportTeamMember->role) == $role['value'] ? 'selected' : '' }}>{{ $role['label'] }}</option>
                                @endforeach
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" name="status" label="Status" required>
                                <option value="active" {{ $supportTeamMember->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="disabled" {{ $supportTeamMember->status == 'disabled' ? 'selected' : '' }}>Disabled</option>
                            </x-input-field></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="departments" class="form-label">Departments</label>
                                    <select class="form-select select2-multiple" id="departments" name="departments[]" multiple>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ in_array($department->id, $supportTeamMember->departments ?? []) ? 'selected' : '' }}>{{ $department->name }}</option>
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
                                            <option value="{{ $queue->id }}" {{ in_array($queue->id, $supportTeamMember->default_queues ?? []) ? 'selected' : '' }}>{{ $queue->name }} @if($queue->department) ({{ $queue->department->name }}) @endif</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select one or more queues</small>
                                    <label id="default_queues-error" class="text-danger error" for="default_queues" style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="select" name="notification_method" label="Notification Method" required>
                                <option value="email" {{ $supportTeamMember->notification_method == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="in_app" {{ $supportTeamMember->notification_method == 'in_app' ? 'selected' : '' }}>In-App</option>
                                <option value="both" {{ $supportTeamMember->notification_method == 'both' ? 'selected' : '' }}>Both</option>
                            </x-input-field></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="password" name="password" label="Password (Leave blank to keep current)" placeholder="Enter new password" /></div>
                            <div class="col-md-6"><x-input-field type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm password" /></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="supportTeamEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="supportTeamEditBtnSpinner"></i>Update Support Team Member
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
        $("#supportTeamEditForm").validate({
            rules: {
                name: { required: true },
                email: { required: true, email: true },
                role: { required: true },
                status: { required: true },
                notification_method: { required: true },
                password: { minlength: 8 },
                password_confirmation: { equalTo: "#password" }
            },
            messages: {
                name: { required: "The name field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                role: { required: "The role field is required" },
                status: { required: "The status field is required" },
                notification_method: { required: "The notification method field is required" },
                password: { minlength: "Password must be at least 8 characters long" },
                password_confirmation: { equalTo: "Passwords do not match" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.support-team.update', $supportTeamMember->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#supportTeamEditButton').attr('disabled', true);
                        $("#supportTeamEditBtnSpinner").show();
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
                        $('#supportTeamEditButton').attr('disabled', false);
                        $("#supportTeamEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
