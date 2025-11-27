@extends('owner.master')
@section('title','Create Customer')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Customer</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <form id="customerCreateForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Profile Section -->
                        <h5 class="mb-3">Profile Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                <label id="profile_image-error" class="text-danger error" for="profile_image" style="display: none"></label>
                                <div id="imagePreview" class="mt-2" style="display: none;">
                                    <img id="preview" src="" alt="Preview" style="max-width: 150px; max-height: 150px;">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username">
                                <label id="username-error" class="text-danger error" for="username" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <label id="email-error" class="text-danger error" for="email" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="country_code" class="form-label">Country Code</label>
                                <input type="text" class="form-control" id="country_code" name="country_code" placeholder="+1">
                                <label id="country_code-error" class="text-danger error" for="country_code" style="display: none"></label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                                <label id="phone-error" class="text-danger error" for="phone" style="display: none"></label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <label id="gender-error" class="text-danger error" for="gender" style="display: none"></label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob">
                                <label id="dob-error" class="text-danger error" for="dob" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <h5 class="mb-3 mt-4">Address Information</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                <label id="address-error" class="text-danger error" for="address" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                                <label id="city-error" class="text-danger error" for="city" style="display: none"></label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state">
                                <label id="state-error" class="text-danger error" for="state" style="display: none"></label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country">
                                <label id="country-error" class="text-danger error" for="country" style="display: none"></label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode">
                                <label id="pincode-error" class="text-danger error" for="pincode" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="e.g., 40.7128">
                                <label id="latitude-error" class="text-danger error" for="latitude" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="e.g., -74.0060">
                                <label id="longitude-error" class="text-danger error" for="longitude" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Social Links Section -->
                        <h5 class="mb-3 mt-4">Social Links</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="+1234567890">
                                <label id="whatsapp-error" class="text-danger error" for="whatsapp" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com">
                                <label id="website-error" class="text-danger error" for="website" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="url" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/username">
                                <label id="facebook-error" class="text-danger error" for="facebook" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="url" class="form-control" id="instagram" name="instagram" placeholder="https://instagram.com/username">
                                <label id="instagram-error" class="text-danger error" for="instagram" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/username">
                                <label id="linkedin-error" class="text-danger error" for="linkedin" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="youtube" class="form-label">YouTube</label>
                                <input type="url" class="form-control" id="youtube" name="youtube" placeholder="https://youtube.com/@username">
                                <label id="youtube-error" class="text-danger error" for="youtube" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telegram" class="form-label">Telegram</label>
                                <input type="url" class="form-control" id="telegram" name="telegram" placeholder="https://t.me/username">
                                <label id="telegram-error" class="text-danger error" for="telegram" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input type="url" class="form-control" id="twitter" name="twitter" placeholder="https://twitter.com/username">
                                <label id="twitter-error" class="text-danger error" for="twitter" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <h5 class="mb-3 mt-4">Account Settings</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                                <label id="status-error" class="text-danger error" for="status" style="display: none"></label>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <label id="password-error" class="text-danger error" for="password" style="display: none"></label>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <label id="password_confirmation-error" class="text-danger error" for="password_confirmation" style="display: none"></label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="customerCreateButton">
                                <i class="bx bx-loader spinner me-2" style="display: none" id="customerCreateBtnSpinner"></i>Create Customer
                            </button>
                            <a href="{{ route('owner.customers.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Image preview
        $('#profile_image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#imagePreview').show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').hide();
            }
        });

        $("#customerCreateForm").validate({
            rules: {
                name: { required: true },
                email: { required: true, email: true },
                status: { required: true },
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: "#password" },
                latitude: { number: true },
                longitude: { number: true },
                website: { url: true },
                facebook: { url: true },
                instagram: { url: true },
                linkedin: { url: true },
                youtube: { url: true },
                telegram: { url: true },
                twitter: { url: true }
            },
            messages: {
                name: { required: "The name field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                status: { required: "The status field is required" },
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
                    url: "{{ route('owner.customers.store') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#customerCreateButton').attr('disabled', true);
                        $("#customerCreateBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                             sendSuccess(result.message);
                             setTimeout(function() {
                                window.location.href = "{{ route('owner.customers.index') }}";
                             }, 1000);
                        }else{
                             sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            // Display all validation errors
                            $.each(data.error, function(field, messages) {
                                $("#" + field + "-error").html(messages).show();
                            });
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#customerCreateButton').attr('disabled', false);
                        $("#customerCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
