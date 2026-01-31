@extends('owner.master')
@section('title', isset($customer) ? 'Edit Customer' : 'Create Customer')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($customer) ? 'Edit Customer' : 'Create Customer' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.customers.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <form id="customer-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($customer))
                            @method('PUT')
                        @endif
                        
                        <!-- Profile Section -->
                        <h5 class="mb-3">Profile Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field type="file" name="profile_image" label="Profile Image" accept="image/*" />
                                <div id="imagePreview" class="mt-2" style="{{ isset($customer) && $customer->profile_image ? '' : 'display: none;' }}">
                                    <img id="preview" src="{{ isset($customer) && $customer->profile_image ? asset($customer->profile_image) : '' }}" alt="Preview" style="max-width: 150px; max-height: 150px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <x-input-field name="username" label="Username" placeholder="Enter username" value="{{ old('username', $customer->username ?? '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field name="name" label="Name" placeholder="Enter name" value="{{ old('name', $customer->name ?? '') }}" required />
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="email" name="email" label="Email" placeholder="Enter email" value="{{ old('email', $customer->email ?? '') }}" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <x-input-field name="country_code" label="Country Code" placeholder="+1" value="{{ old('country_code', $customer->country_code ?? '') }}" />
                            </div>
                            <div class="col-md-3">
                                <x-input-field name="phone" label="Phone" placeholder="Enter phone" value="{{ old('phone', $customer->phone ?? '') }}" />
                            </div>
                            <div class="col-md-3">
                                <x-input-field type="select" name="gender" label="Gender" placeholder="Select Gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $customer->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $customer->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $customer->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-3">
                                <x-input-field type="date" name="dob" label="Date of Birth" value="{{ old('dob', isset($customer) && $customer->dob ? $customer->dob->format('Y-m-d') : '') }}" />
                            </div>
                        </div>

                        <!-- Address Section -->
                        <h5 class="mb-3 mt-4">Address Information</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <x-input-field type="textarea" name="address" label="Address" placeholder="Enter address" value="{{ old('address', $customer->address ?? '') }}" rows="2" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <x-input-field name="city" label="City" placeholder="Enter city" value="{{ old('city', $customer->city ?? '') }}" />
                            </div>
                            <div class="col-md-3">
                                <x-input-field name="state" label="State" placeholder="Enter state" value="{{ old('state', $customer->state ?? '') }}" />
                            </div>
                            <div class="col-md-3">
                                <x-input-field name="country" label="Country" placeholder="Enter country" value="{{ old('country', $customer->country ?? '') }}" />
                            </div>
                            <div class="col-md-3">
                                <x-input-field name="pincode" label="Pincode" placeholder="Enter pincode" value="{{ old('pincode', $customer->pincode ?? '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field name="latitude" label="Latitude" placeholder="e.g., 40.7128" value="{{ old('latitude', $customer->latitude ?? '') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-input-field name="longitude" label="Longitude" placeholder="e.g., -74.0060" value="{{ old('longitude', $customer->longitude ?? '') }}" />
                            </div>
                        </div>

                        <!-- Social Links Section -->
                        <h5 class="mb-3 mt-4">Social Links</h5>
                        @php
                            $socialLinks = isset($customer) && $customer->social_links ? $customer->social_links : [];
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field name="whatsapp" label="WhatsApp" placeholder="+1234567890" value="{{ old('whatsapp', $socialLinks['whatsapp'] ?? '') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="url" name="website" label="Website" placeholder="https://example.com" value="{{ old('website', $socialLinks['website'] ?? '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field type="url" name="facebook" label="Facebook" placeholder="https://facebook.com/username" value="{{ old('facebook', $socialLinks['facebook'] ?? '') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="url" name="instagram" label="Instagram" placeholder="https://instagram.com/username" value="{{ old('instagram', $socialLinks['instagram'] ?? '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field type="url" name="linkedin" label="LinkedIn" placeholder="https://linkedin.com/in/username" value="{{ old('linkedin', $socialLinks['linkedin'] ?? '') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="url" name="youtube" label="YouTube" placeholder="https://youtube.com/@username" value="{{ old('youtube', $socialLinks['youtube'] ?? '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field type="url" name="telegram" label="Telegram" placeholder="https://t.me/username" value="{{ old('telegram', $socialLinks['telegram'] ?? '') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="url" name="twitter" label="Twitter" placeholder="https://twitter.com/username" value="{{ old('twitter', $socialLinks['twitter'] ?? '') }}" />
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <h5 class="mb-3 mt-4">Account Settings</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <x-input-field type="select" name="status" label="Status" required>
                                    <option value="active" {{ old('status', $customer->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="blocked" {{ old('status', $customer->status ?? '') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-4">
                                <x-input-field type="password" name="password" label="Password" placeholder="Enter password" :required="!isset($customer)" />
                                @if(isset($customer))
                                    <small class="text-muted">Leave blank to keep current password</small>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <x-input-field type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm password" :required="!isset($customer)" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                {{ isset($customer) ? 'Update Customer' : 'Create Customer' }}
                                <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
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
        $('input[name="profile_image"]').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#imagePreview').show();
                }
                reader.readAsDataURL(file);
            } else {
                @if(!isset($customer) || !$customer->profile_image)
                    $('#imagePreview').hide();
                @endif
            }
        });

        $("#customer-form").validate({
            rules: {
                name: { required: true },
                email: { required: true, email: true },
                status: { required: true },
                @if(!isset($customer))
                    password: { required: true, minlength: 8 },
                    password_confirmation: { required: true, equalTo: "[name='password']" },
                @else
                    password: { minlength: 8 },
                    password_confirmation: { equalTo: "[name='password']" },
                @endif
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
                
                @if(isset($customer))
                    var url = "{{ route('owner.customers.update', $customer->id) }}";
                @else
                    var url = "{{ route('owner.customers.store') }}";
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
                                window.location.href = "{{ route('owner.customers.index') }}";
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
