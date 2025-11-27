@extends('owner.master')
@section('title', 'Admin Profile')
@section('main')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Admin Profile</div>

                    <div class="card-body">
                        <form method="POST" id="profileSettingForm">
                            @csrf

                            <div class="text-center">
                                <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                    <img src="{{ $owner->profile_image }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                        <input id="profile-img-file-input" type="file" name="profile_image" class="profile-img-file-input">
                                        <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                <i class="ri-camera-fill"></i>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="full_name" value="{{ $owner->full_name }}">
                                <label id="full_name-error" class="text-danger error" for="full_name" style="display: none"></label>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $owner->email }}" required>
                                <label id="email-error" class="text-danger error" for="email" style="display: none"></label>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="text" class="form-control" id="phone" maxlength="15" name="phone" value="{{ $owner->phone }}">
                                </div>
                                <label id="phone-error" class="text-danger error" for="phone" style="display: none"></label>
                            </div>

                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ $owner->dob ? $owner->dob->format('Y-m-d') : '' }}">
                                <label id="dob-error" class="text-danger error" for="dob" style="display: none"></label>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" @if($owner->gender == 'male') selected @endif>Male</option>
                                    <option value="female" @if($owner->gender == 'female') selected @endif>Female</option>
                                    <option value="other" @if($owner->gender == 'other') selected @endif>Other</option>
                                </select>
                                <label id="gender-error" class="text-danger error" for="gender" style="display: none"></label>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ $owner->address }}</textarea>
                                <label id="address-error" class="text-danger error" for="address" style="display: none"></label>
                            </div>

                            <button type="submit" name="submit" value="submit" class="btn btn-primary" id="profileUpdateButton"><i class="bx bx-loader spinner me-2" style="display: none" id="profileUpdateBtnSpinner"></i>Update</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">Change Password</div>

                    <div class="card-body">
                        <form method="POST" id="changePasswordForm">
                            @csrf

                            <div class="mb-2">
                                <label for="oldPasswordInput" class="form-label">Old Password<span class="text-danger">*</span></label>
                                <div class="position-relative auth-pass-inputgroup">
                                    <input type="password" class="form-control pe-5 password-input"
                                        name="oldPassword" placeholder="Enter Current Password" id="oldPasswordInput">
                                    <label id="old-password-error" class="text-danger error" for="password" style="display: none"></label>
                                    <button
                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                        type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="newPasswordInput" class="form-label">New Password<span class="text-danger">*</span></label>
                                <div class="position-relative auth-pass-inputgroup">
                                    <input type="password" class="form-control pe-5 password-input"
                                        name="newPassword" placeholder="Enter New Password" id="newPasswordInput">
                                    <label id="new-password-error" class="text-danger error" for="password" style="display: none"></label>
                                    <button
                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                        type="button" id="password-addon1"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="confirmPasswordInput" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                                <div class="position-relative auth-pass-inputgroup">
                                    <input type="password" class="form-control pe-5 password-input"
                                        name="confirm_password" placeholder="Enter Confirm Password" id="confirmPasswordInput">
                                    <label id="confirm_password-error" class="text-danger error" for="confirm_password" style="display: none"></label>
                                    <button
                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                        type="button" id="password-addon2"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" name="submit" value="submit" class="btn btn-primary" id="changePasswordButton"><i class="bx bx-loader spinner me-2" style="display: none" id="changePasswordBtnSpinner"></i>Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Login History</h4>
                    </div>
                    <div class="card-body">
                        <table id="loginHistoryTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Login Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Profile image preview
        $('#profile-img-file-input').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('.user-profile-image').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
        
        $("#profileSettingForm").validate({
            rules: {
                full_name: {
                    required: true,
                },
                email: {
                    required: true,
                }
            },
            messages: {
                full_name: {
                    required: "The name field is required",
                },
                email: {
                    required: "Email is required",
                }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.profile.update') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#profileUpdateButton').attr('disabled', true);
                        $("#profileUpdateBtnSpinner").show();
                    },
                    success: function (result) {
                        sendSuccess(result.message);
                        $('#headerProfileName').html(result.data.full_name);
                        $('#headerProfileImage').attr('src',result.data.profile_image);
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            if (data.error.hasOwnProperty('full_name')) {
                                $("#full_name-error").html(data.error.full_name).show();
                            }
                            if (data.error.hasOwnProperty('email')) {
                                $("#email-error").html(data.error.email).show();
                            }
                            if (data.error.hasOwnProperty('phone')) {
                                $("#phone-error").html(data.error.phone).show();
                            }
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#profileUpdateButton').attr('disabled', false);
                        $("#profileUpdateBtnSpinner").hide();
                    },
                });
            }
        });

        $("#changePasswordForm").validate({
            rules: {
                oldPassword: {
                    required: true,
                },
                newPassword: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    equalTo: '[name="newPassword"]'
                }
            },
            messages: {
                oldPassword: {
                    required: "The old Password field is required.",
                },
                newPassword: {
                    required: "The new password field is required.",
                    minlength: "Password must be at least 6 characters long."
                },
                confirm_password: {
                    required: "The confirm password field is required.",
                    equalTo: "Passwords do not match with new password."
                }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{route('owner.change.password')}}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#changePasswordButton').attr('disabled', true);
                        $("#changePasswordBtnSpinner").show();
                    },
                    success: function (result) {
                        sendSuccess(result.message);
                        $('#changePasswordForm')[0].reset();
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            if (data.error.hasOwnProperty('oldPassword')) {
                                $("#old-password-error").html(data.error.oldPassword).show();
                            }
                            if (data.error.hasOwnProperty('newPassword')) {
                                $("#new-password-error").html(data.error.newPassword).show();
                            }
                            if (data.error.hasOwnProperty('confirmPassword')) {
                                $("#confirm-password-error").html(data.error.confirmPassword).show();
                            }
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#changePasswordButton').attr('disabled', false);
                        $("#changePasswordBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection