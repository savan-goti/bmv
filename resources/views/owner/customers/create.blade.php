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
                            <div class="col-md-6">
                                <x-input-field type="file" name="profile_image" label="Profile Image" accept="image/*" />
                                <div id="imagePreview" class="mt-2" style="display: none;">
                                    <img id="preview" src="" alt="Preview" style="max-width: 150px; max-height: 150px;">
                                </div>
                            </div>
                            <div class="col-md-6"><x-input-field name="username" label="Username" placeholder="Enter username" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field name="name" label="Name" placeholder="Enter name" required /></div>
                            <div class="col-md-6"><x-input-field type="email" name="email" label="Email" placeholder="Enter email" required /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><x-input-field name="country_code" label="Country Code" placeholder="+1" /></div>
                            <div class="col-md-3"><x-input-field name="phone" label="Phone" placeholder="Enter phone" /></div>
                            <div class="col-md-3"><x-input-field type="select" name="gender" label="Gender" placeholder="Select Gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </x-input-field></div>
                            <div class="col-md-3"><x-input-field type="date" name="dob" label="Date of Birth" /></div>
                        </div>

                        <!-- Address Section -->
                        <h5 class="mb-3 mt-4">Address Information</h5>
                        <div class="row">
                            <div class="col-md-12"><x-input-field type="textarea" name="address" label="Address" placeholder="Enter address" rows="2" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><x-input-field name="city" label="City" placeholder="Enter city" /></div>
                            <div class="col-md-3"><x-input-field name="state" label="State" placeholder="Enter state" /></div>
                            <div class="col-md-3"><x-input-field name="country" label="Country" placeholder="Enter country" /></div>
                            <div class="col-md-3"><x-input-field name="pincode" label="Pincode" placeholder="Enter pincode" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field name="latitude" label="Latitude" placeholder="e.g., 40.7128" /></div>
                            <div class="col-md-6"><x-input-field name="longitude" label="Longitude" placeholder="e.g., -74.0060" /></div>
                        </div>

                        <!-- Social Links Section -->
                        <h5 class="mb-3 mt-4">Social Links</h5>
                        <div class="row">
                            <div class="col-md-6"><x-input-field name="whatsapp" label="WhatsApp" placeholder="+1234567890" /></div>
                            <div class="col-md-6"><x-input-field type="url" name="website" label="Website" placeholder="https://example.com" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="url" name="facebook" label="Facebook" placeholder="https://facebook.com/username" /></div>
                            <div class="col-md-6"><x-input-field type="url" name="instagram" label="Instagram" placeholder="https://instagram.com/username" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="url" name="linkedin" label="LinkedIn" placeholder="https://linkedin.com/in/username" /></div>
                            <div class="col-md-6"><x-input-field type="url" name="youtube" label="YouTube" placeholder="https://youtube.com/@username" /></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><x-input-field type="url" name="telegram" label="Telegram" placeholder="https://t.me/username" /></div>
                            <div class="col-md-6"><x-input-field type="url" name="twitter" label="Twitter" placeholder="https://twitter.com/username" /></div>
                        </div>

                        <!-- Account Settings -->
                        <h5 class="mb-3 mt-4">Account Settings</h5>
                        <div class="row">
                            <div class="col-md-4"><x-input-field type="select" name="status" label="Status" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="blocked">Blocked</option>
                            </x-input-field></div>
                            <div class="col-md-4"><x-input-field type="password" name="password" label="Password" placeholder="Enter password" required /></div>
                            <div class="col-md-4"><x-input-field type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm password" required /></div>
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
