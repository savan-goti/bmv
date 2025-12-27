@extends('seller.master')
@section('title', 'Seller Profile')
@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Seller Profile</div>

                    <div class="card-body">
                        <form method="POST" id="profileSettingForm">
                            @csrf

                            <div class="text-center">
                                <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                    <img src="{{ $seller->business_logo }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                        <input id="profile-img-file-input" type="file" name="business_logo" class="profile-img-file-input">
                                        <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                <i class="ri-camera-fill"></i>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <x-input-field 
                                name="business_name" 
                                id="business_name" 
                                label="Business Name" 
                                value="{{ $seller->business_name }}" 
                                required 
                            />

                            <x-input-field 
                                name="owner_name" 
                                id="owner_name" 
                                label="Owner Name" 
                                value="{{ $seller->owner_name }}" 
                                required 
                            />

                            <x-input-field 
                                type="email"
                                name="email" 
                                id="email" 
                                label="Email" 
                                value="{{ $seller->email }}" 
                                required 
                            />

                            <x-input-field 
                                name="phone" 
                                id="phone" 
                                label="Phone" 
                                value="{{ $seller->phone }}" 
                                maxlength="15"
                            >
                                <x-slot:prefix>
                                    <span class="input-group-text">+91</span>
                                </x-slot:prefix>
                            </x-input-field>

                            <x-input-field 
                                type="textarea"
                                name="address" 
                                id="address" 
                                label="Address" 
                                value="{{ $seller->address }}" 
                                rows="3"
                            />

                            <button type="submit" name="submit" value="submit" class="btn btn-primary" id="profileUpdateButton"><i class="bx bx-loader spinner me-2" style="display: none" id="profileUpdateBtnSpinner"></i>Update</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">Change Password</div>

                    <div class="card-body">
                        <form method="POST" id="changePasswordForm">
                            @csrf

                            <x-input-field 
                                type="password" 
                                name="oldPassword" 
                                id="oldPasswordInput" 
                                label="Old Password" 
                                placeholder="Enter Current Password" 
                                required 
                                inputClass="pe-5 password-input"
                            >
                                <x-slot:suffix>
                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" 
                                            type="button" id="password-addon">
                                        <i class="ri-eye-fill align-middle"></i>
                                    </button>
                                </x-slot:suffix>
                            </x-input-field>

                            <x-input-field 
                                type="password" 
                                name="newPassword" 
                                id="newPasswordInput" 
                                label="New Password" 
                                placeholder="Enter New Password" 
                                required 
                                inputClass="pe-5 password-input"
                            >
                                <x-slot:suffix>
                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" 
                                            type="button" id="password-addon1">
                                        <i class="ri-eye-fill align-middle"></i>
                                    </button>
                                </x-slot:suffix>
                            </x-input-field>

                            <x-input-field 
                                type="password" 
                                name="confirm_password" 
                                id="confirmPasswordInput" 
                                label="Confirm Password" 
                                placeholder="Enter Confirm Password" 
                                required 
                                inputClass="pe-5 password-input"
                            >
                                <x-slot:suffix>
                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" 
                                            type="button" id="password-addon2">
                                        <i class="ri-eye-fill align-middle"></i>
                                    </button>
                                </x-slot:suffix>
                            </x-input-field>

                            <div class="mt-3">
                                <button type="submit" name="submit" value="submit" class="btn btn-primary" id="changePasswordButton"><i class="bx bx-loader spinner me-2" style="display: none" id="changePasswordBtnSpinner"></i>Change Password</button>
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
                business_name: {
                    required: true,
                },
                owner_name: {
                    required: true,
                },
                email: {
                    required: true,
                }
            },
            messages: {
                business_name: {
                    required: "The business name field is required",
                },
                owner_name: {
                    required: "The owner name field is required",
                },
                email: {
                    required: "Email is required",
                }
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    element.after(error);
                }
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('seller.profile.update') }}",
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
                        $('#headerProfileName').html(result.data.business_name);
                        $('#headerProfileImage').attr('src',result.data.business_logo);
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            if (data.error.hasOwnProperty('business_name')) {
                                $("#business_name-error").html(data.error.business_name).show();
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
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    element.after(error);
                }
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{route('seller.change.password')}}",
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

        // Toggle password visibility
        $('.password-addon').on('click', function() {
            var input = $(this).siblings('input');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).find('i').removeClass('ri-eye-fill').addClass('ri-eye-off-fill');
            } else {
                input.attr('type', 'password');
                $(this).find('i').removeClass('ri-eye-off-fill').addClass('ri-eye-fill');
            }
        });
    });
</script>
@endsection
